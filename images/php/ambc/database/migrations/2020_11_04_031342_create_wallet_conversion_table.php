<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletConversionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_conversion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bonus_transaction_id')->nullable();
            $table->unsignedBigInteger('roi_transaction_id')->nullable();
            $table->unsignedBigInteger('usdt_transaction_id');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('bonus_transaction_id')->references('id')->on('wallet_bonus');
            $table->foreign('roi_transaction_id')->references('id')->on('wallet_roi');
            $table->foreign('usdt_transaction_id')->references('id')->on('wallet_usdt');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wallet_conversion', function (Blueprint $table) {
            $table->dropForeign(['bonus_transaction_id']);
            $table->dropForeign(['roi_transaction_id']);
            $table->dropForeign(['usdt_transaction_id']);
        });

        Schema::dropIfExists('wallet_conversion');
    }
}
