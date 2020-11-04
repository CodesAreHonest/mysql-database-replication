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
        Schema::create('admin_bonus_transaction', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_bonus_transaction_id');
            $table->string('remarks', 255)->nullable()->index();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('member_bonus_transaction_id')->references('id')
                ->on('member_bonus_transaction');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_bonus_transaction', function (Blueprint $table) {
            $table->dropForeign(['member_bonus_transaction_id']);
        });

        Schema::dropIfExists('admin_bonus_transaction');
    }
}
