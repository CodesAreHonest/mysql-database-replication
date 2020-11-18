<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WalletBalanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = $this->seedData();
        DB::table('wallet_balances')->insertOrIgnore($data);
    }

    private function seedData()
    {
        $currentTime = Carbon::now();

        return [
            [
                'id'    => 1,
                'member_id' => 1,
                'roi'   => 0,
                'bonus' => 0,
                'ambc'  => 0,
                'usdt'  => 0,
                'deleted_at'    => null,
                'created_at'    => $currentTime,
                'updated_at'    => $currentTime
            ],
            [
                'id'    => 2,
                'member_id' => 2,
                'roi'   => 0,
                'bonus' => 0,
                'ambc'  => 0,
                'usdt'  => 0,
                'deleted_at'    => null,
                'created_at'    => $currentTime,
                'updated_at'    => $currentTime
            ]
        ];
    }
}
