<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletUsdtTransferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_usdt_transfer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_member_id');
            $table->unsignedBigInteger('receiver_member_id');
            $table->unsignedBigInteger('sender_transaction_id');
            $table->unsignedBigInteger('receiver_transaction_id');
            $table->unsignedBigInteger('sender_fee_deduction_transaction_id');
            $table->unsignedBigInteger('system_receive_fee_transaction_id');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('sender_member_id')->references('id')->on('members');
            $table->foreign('receiver_member_id')->references('id')->on('members');
            $table->foreign('sender_transaction_id')->references('id')->on('wallet_usdt');
            $table->foreign('receiver_transaction_id')->references('id')->on('wallet_usdt');
            $table->foreign('sender_fee_deduction_transaction_id')->references('id')->on('wallet_usdt');
            $table->foreign('system_receive_fee_transaction_id')->references('id')->on('wallet_usdt');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wallet_usdt_transfer', function (Blueprint $table) {
            $table->dropForeign(['sender_member_id']);
            $table->dropForeign(['receiver_member_id']);
            $table->dropForeign(['sender_transaction_id']);
            $table->dropForeign(['receiver_transaction_id']);
            $table->dropForeign(['sender_fee_deduction_transaction_id']);
            $table->dropForeign(['system_receive_fee_transaction_id']);
        });

        Schema::dropIfExists('wallet_usdt_transfer');
    }
}
