<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'ten_ngan_sach',
        'ngan_sach_goc',
        'so_du',
        'mo_ta',
        'trang_thai',
    ];

    protected $casts = [
        'ngan_sach_goc' => 'decimal:2',
        'so_du' => 'decimal:2',
        'trang_thai' => 'boolean',
    ];

    /**
     * Relationship: Ngân sách thuộc về một user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Ngân sách thuộc về một danh mục
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relationship: Ngân sách có nhiều giao dịch (danh mục là trung gian)
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'category_id', 'category_id')->where('user_id', $this->user_id);
    }

    /**
     * Accessor: Format ngân sách gốc
     */
    public function getFormattedBudgetAttribute()
    {
        return number_format($this->ngan_sach_goc, 0, ',', '.') . 'đ';
    }

    /**
     * Accessor: Format số dư
     */
    public function getFormattedBalanceAttribute()
    {
        return number_format($this->so_du, 0, ',', '.') . 'đ';
    }

    /**
     * Accessor: Phần trăm đã chi tiêu
     */
    public function getSpentPercentageAttribute()
    {
        if ($this->ngan_sach_goc <= 0) {
            return 0;
        }
        return round((($this->ngan_sach_goc - $this->so_du) / $this->ngan_sach_goc) * 100, 2);
    }

    /**
     * Accessor: Số tiền đã chi
     */
    public function getSpentAmountAttribute()
    {
        return $this->ngan_sach_goc - $this->so_du;
    }

    /**
     * Accessor: Check ngân sách có bị vượt không
     */
    public function getIsOverBudgetAttribute()
    {
        return $this->so_du < 0;
    }

    /**
     * Scope: Lọc theo danh mục
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope: Chỉ lấy ngân sách đang hoạt động
     */
    public function scopeActive($query)
    {
        return $query->where('trang_thai', true);
    }
}
