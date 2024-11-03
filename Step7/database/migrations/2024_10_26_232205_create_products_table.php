<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // 商品名
            $table->text('description')->nullable(); // 商品説明
            $table->unsignedBigInteger('company_id'); // 企業ID
            $table->decimal('price', 8, 2); // 価格
            $table->integer('stock'); // 在庫数
            $table->timestamps();
    
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
