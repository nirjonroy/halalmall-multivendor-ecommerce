<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('products', function (Blueprint $table) {
        //     $table->text('short_desc')->nullable();
        //     $table->text('in_the_box')->nullable();
        //     $table->string('warranty_type')->nullable();
        //     $table->string('warranty')->nullable();
        //     $table->string('warranty_policy')->nullable();
        //     $table->integer('weight_id')->nullable();
        //     $table->decimal('length', 8, 2)->nullable()->default(0);
        //     $table->decimal('height', 8, 2)->nullable()->default(0);
        //     $table->decimal('width', 8, 2)->nullable()->default(0);
        //     $table->json('variation_images')->nullable();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('products', function (Blueprint $table) {
        //     //
        // });
    }
}
