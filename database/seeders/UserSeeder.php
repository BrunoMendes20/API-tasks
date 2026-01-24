<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // add user
        $user = new User();
        $user->name = 'AppConsumer001';
        $user->email = 'appconsumer_001@api.com';
        $user->password = Hash::make('Aa123456');
        $user->save();
    }
}
