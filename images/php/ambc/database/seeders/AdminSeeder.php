<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = $this->seedData();
        DB::table('admins')->insertOrIgnore($data);
    }

    private function seedData()
    {
        $currentTime = Carbon::now();
        return [
            [
                'id'    => 1,
                'first_name'    => 'Yinghua',
                'last_name'     => 'Chai',
                'deleted_at'    => null,
                'created_at'    => $currentTime,
                'updated_at'    => $currentTime
            ]
        ];
    }
}
