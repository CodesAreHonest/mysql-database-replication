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
        Schema::create('member_wallet_conversion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_bonus_transaction_id')->nullable();
            $table->unsignedBigInteger('member_roi_transaction_id')->nullable();
            $table->unsignedBigInteger('member_usdt_transaction_id');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('member_bonus_transaction_id')->references('id')->on('member_bonus_transaction');
            $table->foreign('member_roi_transaction_id')->references('id')->on('member_roi_transaction');
            $table->foreign('member_usdt_transaction_id')->references('id')->on('member_usdt_transaction');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_wallet_conversion', function (Blueprint $table) {
            $table->dropForeign(['member_bonus_transaction_id']);
            $table->dropForeign(['member_roi_transaction_id']);
            $table->dropForeign(['member_usdt_transaction_id']);
        });

        Schema::dropIfExists('member_wallet_conversion');
    }
}
