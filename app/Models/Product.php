<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // fillable プロパティにカラム名を統一
    protected $fillable = ['product_name', 'company_id', 'price', 'stock', 'img_path', 'comment'];

    /**
     * Company モデルとのリレーション
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}

