<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'ten_danh_muc',
        'loai_danh_muc',
        'danh_muc_cha_id',
        'bieu_tuong',
        'mo_ta',
        'trang_thai'
    ];

    // Relationship với danh mục cha
    public function parent()
    {
        return $this->belongsTo(Category::class, 'danh_muc_cha_id');
    }

    // Relationship với danh mục con
    public function children()
    {
        return $this->hasMany(Category::class, 'danh_muc_cha_id');
    }

    // Relationship với giao dịch
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'category_id');
    }

    // Kiểm tra danh mục có thể xóa không
    public function canDelete()
    {
        return $this->transactions()->count() === 0;
    }

    // Scope filter theo loại
    public function scopeLoai($query, $loai)
    {
        if ($loai && in_array($loai, ['THU', 'CHI'])) {
            return $query->where('loai_danh_muc', $loai);
        }
        return $query;
    }

    // Scope tìm kiếm theo tên
    public function scopeSearch($query, $keyword)
    {
        if ($keyword) {
            return $query->where('ten_danh_muc', 'like', '%' . $keyword . '%');
        }
        return $query;
    }

    // Scope lọc theo trạng thái
    public function scopeTrangThai($query, $status)
    {
        if ($status !== null && $status !== '') {
            return $query->where('trang_thai', $status);
        }
        return $query;
    }
}
