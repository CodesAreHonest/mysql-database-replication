<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = $this->seedData();
        DB::table('transaction_type')->insertOrIgnore($data);
    }

    private function seedData()
    {

        $currentTimeStamp = Carbon::now()->toDateTimeString();

        return [
            [
                'id'    => 1,
                'name'  => 'topup',
                'deleted_at'    => NULL,
                'created_at'    => $currentTimeStamp,
                'updated_at'    => $currentTimeStamp
            ],
            [
                'id'    => 2,
                'name'  => 'deduct_fund',
                'deleted_at'    => NULL,
                'created_at'    => $currentTimeStamp,
                'updated_at'    => $currentTimeStamp
            ],
            [
                'id'    => 3,
                'name'  => 'withdrawal',
                'deleted_at'    => NULL,
                'created_at'    => $currentTimeStamp,
                'updated_at'    => $currentTimeStamp
            ],
            [
                'id'    => 4,
                'name'  => 'convert',
                'deleted_at'    => NULL,
                'created_at'    => $currentTimeStamp,
                'updated_at'    => $currentTimeStamp
            ],
            [
                'id'    => 5,
                'name'  => 'transfer',
                'deleted_at'    => NULL,
                'created_at'    => $currentTimeStamp,
                'updated_at'    => $currentTimeStamp
            ],
            [
                'id'    => 6,
                'name'  => 'roi',
                'deleted_at'    => NULL,
                'created_at'    => $currentTimeStamp,
                'updated_at'    => $currentTimeStamp
            ],
            [
                'id'    => 7,
                'name'  => 'bonus',
                'deleted_at'    => NULL,
                'created_at'    => $currentTimeStamp,
                'updated_at'    => $currentTimeStamp
            ],
            [
                'id'    => 8,
                'name'  => 'usdt_topup',
                'deleted_at'    => NULL,
                'created_at'    => $currentTimeStamp,
                'updated_at'    => $currentTimeStamp
            ],
        ];
    }
}
