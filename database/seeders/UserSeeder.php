<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\Agency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        User::truncate();

        foreach(Agency::rolesList() as $agency) {
            DB::table('users')->insert([
                'name' => $agency,
                'password' => \bcrypt('haslo'),
                'email' => mb_strtolower(mb_substr($agency, 0, 3).'_test@test.pl'),
                'agency' => $agency
            ]);
        }
    }
}
