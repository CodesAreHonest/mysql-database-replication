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
        Schema::create('member_usdt_transfer_transaction', function (Blueprint $table) {
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
            $table->foreign('sender_transaction_id')->references('id')->on('member_usdt_transaction');
            $table->foreign('receiver_transaction_id')->references('id')->on('member_usdt_transaction');
            $table->foreign('sender_fee_deduction_transaction_id', 'sender_fee_fk')->references('id')->on('member_usdt_transaction');
            $table->foreign('system_receive_fee_transaction_id', 'system_receive_fee_fk')->references('id')->on('member_usdt_transaction');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_usdt_transfer_transaction', function (Blueprint $table) {
            $table->dropForeign(['sender_member_id']);
            $table->dropForeign(['receiver_member_id']);
            $table->dropForeign(['sender_transaction_id']);
            $table->dropForeign(['receiver_transaction_id']);
            $table->dropForeign('sender_fee_fk');
            $table->dropForeign('system_receive_fee_fk');
        });

        Schema::dropIfExists('member_usdt_transfer_transaction');
    }
}
