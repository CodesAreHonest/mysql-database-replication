<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Database\Seeders\TransactionTypeSeeder;
use Database\Seeders\MemberSeeder;
use Database\Seeders\AdminSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(TransactionTypeSeeder::class);
        $this->call(MemberSeeder::class);
        $this->call(AdminSeeder::class);
    }
}
