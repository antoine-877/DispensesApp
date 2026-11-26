<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class CoordinatorSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->coordinator()->create([
            'first_name' => 'Sophie',
            'last_name' => 'Marchal',
            'email' => 'sophie.marchal@example.com',
        ]);
    }
}
