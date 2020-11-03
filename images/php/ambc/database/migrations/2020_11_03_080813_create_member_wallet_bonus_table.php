<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberWalletBonusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_wallet_bonus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id');
            $table->unsignedBigInteger('transaction_type_id');
            $table->unsignedBigInteger('sender_member_id');
            $table->unsignedBigInteger('receiver_member_id');
            $table->string('txcode', 50)->index();
            $table->decimal('credit', 8, 2)->nullable()->index();
            $table->decimal('debit', 8, 2)->nullable()->index();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_wallet_bonus');
    }
}
