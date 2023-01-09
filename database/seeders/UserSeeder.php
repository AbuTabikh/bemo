<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $user = User::factory()->create([
             'name' => 'Mohanmmed Abutabikh',
             'email' => 'm.abutabikh@gmail.com',
         ]);
         $token = $user->createToken('token');
        if (isset($this->command)) {
            $this->command->getOutput()->writeln("<info>Token: </info>".$token->plainTextToken);
        }
    }
}
