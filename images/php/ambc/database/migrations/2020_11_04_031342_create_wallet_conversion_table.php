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
            $table->unsignedBigInteger('member_id');
            $table->unsignedBigInteger('bonus_transaction_id')->nullable();
            $table->unsignedBigInteger('roi_transaction_id')->nullable();
            $table->unsignedBigInteger('usdt_transaction_id');
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('members');
            $table->foreign('bonus_transaction_id')->references('id')->on('member_bonus_transaction');
            $table->foreign('roi_transaction_id')->references('id')->on('member_roi_transaction');
            $table->foreign('usdt_transaction_id')->references('id')->on('member_usdt_transaction');
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
            $table->dropForeign(['member_id']);
            $table->dropForeign(['bonus_transaction_id']);
            $table->dropForeign(['roi_transaction_id']);
            $table->dropForeign(['usdt_transaction_id']);
        });

        Schema::dropIfExists('member_wallet_conversion');
    }
}
