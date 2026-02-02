<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\Wallet;
use App\Models\AiChatHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\GeminiService; // Dịch vụ gọi API Gemini

class AIAssistantController extends Controller
{
    private GeminiService $gemini;

    public function __construct(GeminiService $gemini)
    {
        // Inject GeminiService từ container Laravel
        $this->gemini = $gemini;
    }

    /**
     * Hiển thị trang giao diện 
     */
    public function index()
    {
        return view('ai-assistant.index');
    }

    /**
     * Xử lý tin nhắn người dùng gửi lên từ frontend 
     */
     public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $userMessage = $request->input('message');
        $userId = Auth::id();

        try {
            // 1. Lấy dữ liệu tài chính
            $financialData = $this->getUserFinancialData($userId);

            // 2. Lấy lịch sử chat 
            $history = DB::table('ai_chat_history')
                ->where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->reverse()
                ->map(function ($item) {
                    return [
                        ['role' => 'user', 'parts' => [['text' => $item->user_message]]],
                        ['role' => 'model', 'parts' => [['text' => $item->ai_response]]]
                    ];
                })
                ->flatten(1)
                ->toArray();

            // 3. Tạo system prompt
            $systemPrompt = $this->buildSystemPrompt($financialData);

            // 4. GỘP system prompt VÀO user message
            $combinedMessage = $systemPrompt . "\n\nCâu hỏi của người dùng: " . $userMessage;

            // 5. Build contents (chỉ có 1 user message cuối)
           $contents = array_merge(
            [
                ['role' => 'user', 'parts' => [['text' => $systemPrompt]]],
                ['role' => 'model', 'parts' => [['text' => 'Tôi đã hiểu thông tin tài chính của bạn. Hãy hỏi tôi bất cứ điều gì!']]]
            ],
            $history,
            [['role' => 'user', 'parts' => [['text' => $userMessage]]]]
        );

            // 6. Gọi Gemini API
            $response = $this->gemini->generateContent([
                'model' => 'models/gemini-2.5-flash',
                'contents' => $contents,
                'generationConfig' => [
                    'maxOutputTokens' => 500,
                    'temperature' => 0.7,
                    'topP' => 0.9,
                    'topK' => 40,
                ],
                'safetySettings' => [
                    [
                'category' => 'HARM_CATEGORY_HARASSMENT',
                        'threshold' => 'BLOCK_NONE'
                    ],
                    [
                        'category' => 'HARM_CATEGORY_HATE_SPEECH',
                        'threshold' => 'BLOCK_NONE'
                    ],
                ]
            ]);

            // 7. Extract response
            $aiResponse = $response['candidates'][0]['content']['parts'][0]['text'] 
                ?? 'Xin lỗi, tôi không thể tạo phản hồi lúc này.';

            // 8. Lưu lịch sử chat (chỉ lưu user message gốc, không lưu system prompt)
            DB::table('ai_chat_history')->insert([
                'user_id' => $userId,
                'user_message' => $userMessage, // Chỉ lưu message gốc
                'ai_response' => $aiResponse,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => $aiResponse
            ]);

        } catch (\Exception $e) {
            Log::error('AI Chat Error', [
                'user_id' => $userId,
                'message' => $userMessage,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Xin lỗi, đã có lỗi xảy ra. Vui lòng thử lại sau.',
                'error_detail' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Xóa toàn bộ lịch sử chat của user hiện tại
     */
    public function clearHistory()
    {
        $userId = Auth::id();
        DB::table('ai_chat_history')->where('user_id', $userId)->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Lấy toàn bộ dữ liệu tài chính để đưa vào prompt
     */
    private function getUserFinancialData($userId)
    {
        $totalIncome = Transaction::where('user_id', $userId)
            ->where('loai_giao_dich', 'THU')
            ->sum('so_tien');

        $totalExpense = Transaction::where('user_id', $userId)
            ->where('loai_giao_dich', 'CHI')
            ->sum('so_tien');

        $balance = $totalIncome - $totalExpense;

        $categories = Category::where('user_id', $userId)
            ->select('id', 'ten_danh_muc', 'bieu_tuong', 'loai_danh_muc')
            ->get();

        $wallets = Wallet::where('user_id', $userId)
            ->select('id', 'ten_ngan_sach', 'so_du', 'ngan_sach_goc', 'category_id')
            ->get()
            ->map(function ($wallet) use ($userId) {
                $spent = Transaction::where('user_id', $userId)
                    ->where('loai_giao_dich', 'CHI')
                    ->where('category_id', $wallet->category_id)
                    ->sum('so_tien');
$wallet->spent_percentage = $wallet->ngan_sach_goc > 0 
                    ? round(($spent / $wallet->ngan_sach_goc) * 100, 1) 
                    : 0;

                return $wallet;
            });

        $categoryExpenses = DB::table('transactions')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->where('transactions.user_id', $userId)
            ->where('transactions.loai_giao_dich', 'CHI')
            ->select('categories.ten_danh_muc', DB::raw('SUM(transactions.so_tien) as total'))
            ->groupBy('categories.id', 'categories.ten_danh_muc')
            ->orderByDesc('total')
            ->get();

        $transactions = Transaction::where('user_id', $userId)
            ->with('category')
            ->orderByDesc('ngay_giao_dich')
            ->limit(10)
            ->get();

        $paymentMethods = DB::table('transactions')
            ->where('user_id', $userId)
            ->where('loai_giao_dich', 'CHI')
            ->selectRaw('phuong_thuc_thanh_toan, SUM(so_tien) as total')
            ->groupBy('phuong_thuc_thanh_toan')
            ->get();

        return [
            'total_income'      => $totalIncome,
            'total_expense'     => $totalExpense,
            'balance'           => $balance,
            'categories'        => $categories,
            'wallets'           => $wallets,
            'category_expenses' => $categoryExpenses,
            'transactions'      => $transactions,
            'payment_methods'   => $paymentMethods
        ];
    }

    /**
     * Tạo system prompt chi tiết gửi cho Gemini
     */
    private function buildSystemPrompt($data)
    {
        $prompt = "Bạn là trợ lý tài chính thông minh của ứng dụng Monexa, chuyên tư vấn về quản lý chi tiêu cá nhân. ";
        $prompt .= "Hãy trả lời bằng tiếng Việt một cách thân thiện, súc tích và chuyên nghiệp. Chỉ trả lời các câu hỏi liên quan đến quản lý tài chính cá nhân dựa trên dữ liệu dưới đây. Nếu câu hỏi không liên quan, trả lời ngắn gọn: 'Xin lỗi, tôi chỉ hỗ trợ tư vấn về tài chính cá nhân trong ứng dụng Monexa.'\n\n";

        $prompt .= "THÔNG TIN TÀI CHÍNH NGƯỜI DÙNG:\n";
        $prompt .= "- Tổng thu nhập: " . number_format($data['total_income']) . " VNĐ\n";
        $prompt .= "- Tổng chi tiêu: " . number_format($data['total_expense']) . " VNĐ\n";
        $prompt .= "- Số dư hiện tại: " . number_format($data['balance']) . " VNĐ\n";

        $savingRate = $data['total_income'] > 0 
            ? (($data['total_income'] - $data['total_expense']) / $data['total_income'] * 100) 
            : 0;
        $prompt .= "- Tỷ lệ tiết kiệm: " . number_format($savingRate, 1) . "%\n\n";

        if ($data['categories']->count() > 0) {
            $prompt .= "DANH MỤC:\n";
            foreach ($data['categories'] as $cat) {
                $prompt .= "- {$cat->ten_danh_muc} ({$cat->loai_danh_muc})\n";
}
            $prompt .= "\n";
        }

        if ($data['wallets']->count() > 0) {
            $prompt .= "NGÂN SÁCH:\n";
            foreach ($data['wallets'] as $wallet) {
                $prompt .= "- {$wallet->ten_ngan_sach}: Số dư " . number_format($wallet->so_du) . " VNĐ, Ngân sách gốc " . number_format($wallet->ngan_sach_goc) . " VNĐ, Đã chi {$wallet->spent_percentage}%\n";
            }
            $prompt .= "\n";
        }

        if ($data['category_expenses']->count() > 0) {
            $prompt .= "CHI TIÊU THEO DANH MỤC:\n";
            foreach ($data['category_expenses'] as $cat) {
                $percentage = $data['total_expense'] > 0 ? ($cat->total / $data['total_expense'] * 100) : 0;
                $prompt .= "- {$cat->ten_danh_muc}: " . number_format($cat->total) . " VNĐ (" . number_format($percentage, 1) . "%)\n";
            }
            $prompt .= "\n";
        }

        if ($data['payment_methods']->count() > 0) {
            $prompt .= "PHƯƠNG THỨC THANH TOÁN:\n";
            foreach ($data['payment_methods'] as $method) {
                $prompt .= "- {$method->phuong_thuc_thanh_toan}: " . number_format($method->total) . " VNĐ\n";
            }
            $prompt .= "\n";
        }

        if ($data['transactions']->count() > 0) {
            $prompt .= "GIAO DỊCH GẦN ĐÂY:\n";
            foreach ($data['transactions'] as $trans) {
                $type = $trans->loai_giao_dich == 'THU' ? 'Thu' : 'Chi';
                $date = \Carbon\Carbon::parse($trans->ngay_giao_dich)->format('d/m/Y');
                $category = $trans->category ? $trans->category->ten_danh_muc : 'Không rõ';
                $prompt .= "- {$date} | {$type} | " . number_format($trans->so_tien) . " VNĐ | {$category}";
                if ($trans->ghi_chu) $prompt .= " ({$trans->ghi_chu})";
                $prompt .= "\n";
            }
            $prompt .= "\n";
        }

        $prompt .= "NHIỆM VỤ: Phân tích dữ liệu, đưa lời khuyên cụ thể, trả lời chính xác, so sánh với quy tắc 50/30/20 nếu phù hợp.\n";
        $prompt .= "LƯU Ý: Trả lời ngắn gọn, dùng số liệu, không emoji, không markdown.";

        return $prompt;
    }

    
    public function analyze(Request $request)
{
    $userId = Auth::id();
    $period = $request->input('period', 30);

    try {
        $financialData = $this->getUserFinancialData($userId);
        $systemPrompt = $this->buildSystemPrompt($financialData);

        $analysisPrompt = "Hãy phân tích tình hình tài chính của tôi trong {$period} ngày qua và đưa ra 3 lời khuyên cụ thể.";

        $response = $this->gemini->generateContent([
            'model' => 'models/gemini-2.5-flash',
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $systemPrompt . "\n\n" . $analysisPrompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'maxOutputTokens' => 800,
                'temperature' => 0.7,
            ],
        ]);

        $analysis = $response['candidates'][0]['content']['parts'][0]['text']
            ?? 'Không nhận được phân tích từ Gemini.';

        return response()->json([
            'success' => true,
            'analysis' => $analysis,
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Không thể phân tích dữ liệu. (' . $e->getMessage() . ')'
        ], 500);
    }
}


    public function suggestions()
    {
        $suggestions = [
            'Phân tích chi tiêu của tôi tháng này',
            'Tôi nên tiết kiệm như thế nào?',
            'Danh mục nào tôi chi nhiều nhất?',
            'Đưa ra kế hoạch tiết kiệm cho tôi',
        ];

        return response()->json([
            'suggestions' => $suggestions
        ]);
    }

    public function insights()
    {
        $userId = Auth::id();

        try {
            $insights = [
                'spending_trend' => $this->getSpendingTrend($userId),
                'top_categories' => $this->getTopCategories($userId),
                'unusual_spending' => $this->getUnusualSpending($userId),
                'saving_rate' => $this->getSavingRate($userId)
            ];

            return response()->json([
                'success' => true,
                'insights' => $insights
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy insights.'
            ], 500);
        }
    }

    private function getSpendingTrend($userId)
    {
        $currentMonth = Transaction::where('user_id', $userId)
            ->where('loai_giao_dich', 'CHI')
            ->whereBetween('ngay_giao_dich', [now()->startOfMonth(), now()])
            ->sum('so_tien');

        $lastMonth = Transaction::where('user_id', $userId)
            ->where('loai_giao_dich', 'CHI')
            ->whereBetween('ngay_giao_dich', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->sum('so_tien');

        $change = $lastMonth > 0 ? (($currentMonth - $lastMonth) / $lastMonth * 100) : 0;

        return [
            'current_month' => $currentMonth,
            'last_month' => $lastMonth,
            'change_percentage' => round($change, 1),
            'trend' => $change > 0 ? 'increase' : 'decrease'
        ];
    }

    private function getTopCategories($userId)
    {
        // Sửa join để tránh ambiguous
        return DB::table('transactions')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->where('transactions.user_id', $userId)
            ->where('transactions.loai_giao_dich', 'CHI')
            ->whereBetween('transactions.ngay_giao_dich', [now()->subDays(30), now()])
->select(
                'categories.ten_danh_muc',
                'categories.bieu_tuong',
                DB::raw('SUM(transactions.so_tien) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('categories.id', 'categories.ten_danh_muc', 'categories.bieu_tuong')
            ->orderByDesc('total')
            ->limit(3)
            ->get();
    }

    private function getUnusualSpending($userId)
    {
        $avgSpending = Transaction::where('user_id', $userId)
            ->where('loai_giao_dich', 'CHI')
            ->whereBetween('ngay_giao_dich', [now()->subMonths(3), now()->subMonth()])
            ->avg('so_tien');

        $threshold = $avgSpending * 1.5;

        return Transaction::where('user_id', $userId)
            ->where('loai_giao_dich', 'CHI')
            ->whereBetween('ngay_giao_dich', [now()->startOfMonth(), now()])
            ->where('so_tien', '>', $threshold)
            ->with('category')
            ->orderByDesc('so_tien')
            ->limit(3)
            ->get();
    }

    private function getSavingRate($userId)
    {
        $income = Transaction::where('user_id', $userId)
            ->where('loai_giao_dich', 'THU')
            ->whereBetween('ngay_giao_dich', [now()->startOfMonth(), now()])
            ->sum('so_tien');

        $expense = Transaction::where('user_id', $userId)
            ->where('loai_giao_dich', 'CHI')
            ->whereBetween('ngay_giao_dich', [now()->startOfMonth(), now()])
            ->sum('so_tien');

        $savingRate = $income > 0 ? (($income - $expense) / $income * 100) : 0;

        return [
            'income' => $income,
            'expense' => $expense,
            'saved' => $income - $expense,
            'saving_rate' => round($savingRate, 1),
            'status' => $savingRate >= 20 ? 'good' : ($savingRate >= 10 ? 'fair' : 'poor')
        ];
    }
}