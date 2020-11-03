<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberWalletAdminActionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_wallet_balances_admin_action', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_wallet_balance_id');
            $table->dateTime('admin_updated_at');
            $table->unsignedBigInteger('admin_id');
            $table->string('remarks', 255)->index();
            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('admins');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_wallet_balances_admin_action', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
        });

        Schema::dropIfExists('member_wallet_balance_admin_action');
    }
}
