<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class CoordinatorSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrNew(['email' => 'sophie.marchal@example.com']);

        if (! $user->exists) {
            $user->password = 'password';
            $user->email_verified_at = now();
        }

        $user->forceFill([
            'first_name' => 'Sophie',
            'last_name' => 'Marchal',
            'role' => 'coordinator',
        ])->save();
    }
}
