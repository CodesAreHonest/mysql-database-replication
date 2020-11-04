<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberWalletUsdtAdminActionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_wallet_usdt', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wallet_usdt_id');
            $table->string('remarks', 255)->nullable()->index();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('wallet_usdt_id')
                ->references('id')
                ->on('wallet_usdt');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_wallet_usdt', function (Blueprint $table) {
            $table->dropForeign(['wallet_usdt_id']);
        });
        Schema::dropIfExists('admin_wallet_usdt');
    }
}
