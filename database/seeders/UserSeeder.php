<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            [
                'name'=>'Admin',
                'email'=>'admin@qubicle.com',
                'role'=>'1',
                'referral_code' => 'ADMIN1',
                'password'=> bcrypt('123456'),
            ],
        ];
  
        foreach ($user as $key => $value) {
          //  dump($value['email']);
            User::firstOrCreate([
                'email' => $value['email'],
            ], $value);
        }
    }
}
