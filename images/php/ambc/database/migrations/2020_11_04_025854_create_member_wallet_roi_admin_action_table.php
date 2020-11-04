<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberWalletRoiAdminActionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_wallet_roi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wallet_roi_id');
            $table->string('remarks', 255)->nullable()->index();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('wallet_roi_id')
                ->references('id')
                ->on('wallet_roi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_wallet_roi', function (Blueprint $table) {
            $table->dropForeign(['wallet_roi_id']);
        });
        Schema::dropIfExists('admin_wallet_roi');
    }
}
