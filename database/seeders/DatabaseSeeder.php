<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = new User;
        $user->name = 'Admin';
        $user->email = 'admin@gmail.com';
        $user->password = 'Nuñoa2023';
        $user->role = 'admin';
        $user->save();
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // $user = new User;
        // $user->name = 'Administrador';
        // $user->email = 'administrador@gmail.com';
        // $user->password = 'Nuñoa.2023';
        // $user->role = 'administrador';
        // $user->save();
    }
}
