<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(){
        return true; // リクエストの権限を許可
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(){
        return [
            'product_name' => 'required|string|max:255', // 必須、文字列、最大255文字
            'company_id' => [
                'required',
                'integer',
                Rule::exists('companies', 'id'), // companiesテーブルのidに存在することを確認
            ],
            'price' => 'required|numeric|min:0', // 必須、数値、0以上
            'stock' => 'required|integer|min:0', // 必須、整数、0以上
        ];
    }

    /**
     * Custom error messages for validation.
     *
     * @return array<string, string>
     */
    public function messages(){
        return [
            'product_name.required' => '商品名は必須です。',
            'product_name.string' => '商品名は文字列で入力してください。',
            'product_name.max' => '商品名は255文字以内で入力してください。',
            'company_id.required' => 'メーカーを選択してください。',
            'company_id.integer' => 'メーカーIDは整数でなければなりません。',
            'company_id.exists' => '選択されたメーカーは存在しません。',
            'price.required' => '価格は必須です。',
            'price.numeric' => '価格は数値で入力してください。',
            'price.min' => '価格は0以上で入力してください。',
            'stock.required' => '在庫数は必須です。',
            'stock.integer' => '在庫数は整数で入力してください。',
            'stock.min' => '在庫数は0以上で入力してください。',
        ];
    }
}
