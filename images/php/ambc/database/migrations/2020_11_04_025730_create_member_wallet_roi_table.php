<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberWalletRoiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_roi_transaction', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id');
            $table->unsignedBigInteger('transaction_type_id');
            $table->string('txcode', 50)->index();
            $table->decimal('credit', 8, 2)->nullable()->index();
            $table->decimal('debit', 8, 2)->nullable()->index();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('members');
            $table->foreign('transaction_type_id')->references('id')->on('transaction_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_roi_transaction', function (Blueprint $table) {
            $table->dropForeign(['member_id']);
            $table->dropForeign(['transaction_type_id']);
        });
        Schema::dropIfExists('member_roi_transaction');
    }
}
