<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('laravel-products.products.table'), function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('upc');
            //product, service, combo or countable, uncountable etc depending on application
            $table->string('type')->default(config('laravel-products.product_default_type'));
            $table->string('unit')->default(config('laravel-products.product_default_unit'));
            $table->string('image')->nullable();
            $table->string('url')->nullable();
            $table->double('cost', 2)->default(0);
            $table->double('price', 2)->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('laravel-products.products.table'));
    }
}
