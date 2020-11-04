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
        Schema::create('admin_roi_transaction', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_roi_transaction_id');
            $table->string('remarks', 255)->nullable()->index();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('member_roi_transaction_id')
                ->references('id')
                ->on('member_roi_transaction');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_roi_transaction', function (Blueprint $table) {
            $table->dropForeign(['member_roi_transaction_id']);
        });
        Schema::dropIfExists('admin_roi_transaction');
    }
}
