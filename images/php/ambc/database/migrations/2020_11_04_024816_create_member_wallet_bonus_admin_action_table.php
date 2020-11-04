<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberWalletBonusAdminActionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_wallet_bonus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wallet_bonus_id');
            $table->string('remarks', 255)->nullable()->index();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('wallet_bonus_id')->references('id')
                ->on('wallet_bonus');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_wallet_bonus', function (Blueprint $table) {
            $table->dropForeign(['wallet_bonus_id']);
        });

        Schema::dropIfExists('admin_wallet_bonus');
    }
}
