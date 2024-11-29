<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    // fillable プロパティに必要なカラムを追加
    protected $fillable = ['product_id', 'quantity'];

    /**
     * Product モデルとのリレーション
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
