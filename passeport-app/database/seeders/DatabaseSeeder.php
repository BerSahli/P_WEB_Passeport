<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Module
        \App\Models\Module::create([
            'name' => 'Maître principal',
        ]);
        \App\Models\Module::create([
            'name' => 'Maître de classe',
        ]);
        \App\Models\Module::create([
            'name' => '293 - Web Statique',
            'type' => 'DEV',
        ]);
        \App\Models\Module::create([
            'name' => '319 - Programmation',
            'type' => 'DEV',
        ]);
        \App\Models\Module::create([
            'name' => '162 - Modélisation DB',
            'type' => 'DEV',
        ]);
        \App\Models\Module::create([
            'name' => '187 - Configuration PC',
            'type' => 'INFRA',
        ]);
        \App\Models\Module::create([
            'name' => '117 - Réseau PME',
            'type' => 'INFRA',
        ]);
        \App\Models\Module::create([
            'name' => '123 - Service serveur',
            'type' => 'INFRA',
        ]);

        // Role
        \Spatie\Permission\Models\Role::create([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);
        \Spatie\Permission\Models\Role::create([
            'name' => 'teacher',
            'guard_name' => 'web',
        ]);
        \Spatie\Permission\Models\Role::create([
            'name' => 'student',
            'guard_name' => 'web',
        ]);

        // User
        $admin = \App\Models\User::create([
            'name' => 'test admin',
            'email' => 'testAdmin@exemple.com',
            'password' => \Illuminate\Support\Facades\Hash::make('MotDePasseAdmin'),
        ]);

        $role = \Spatie\Permission\Models\Role::findByName('admin');

        $admin->assignRole($role);

        // $admin1 = \App\Models\User::create([
        //     'name' => 'Roberto Ferrari',
        //     'email' => 'roberto.ferrari@eduvaud.ch',
        //     'password' => \Illuminate\Support\Facades\Hash::make('MotDePasse'),
        // ]);
        // $admin1->assignRole($role);

        // $admin2 = \App\Models\User::create([
        //     'name' => 'Bertrand Sahli',
        //     'email' => 'bertrand.sahli@eduvaud.ch',
        //     'password' => \Illuminate\Support\Facades\Hash::make('MotDePasse'),
        // ]);
        // $admin2->assignRole($role);

        // $admin3 = \App\Models\User::create([
        //     'name' => 'Aurélie Curchod',
        //     'email' => 'aurelie.curchod@eduvaud.ch',
        //     'password' => \Illuminate\Support\Facades\Hash::make('MotDePasse'),
        // ]);
        // $admin3->assignRole($role);
        
    }
}
