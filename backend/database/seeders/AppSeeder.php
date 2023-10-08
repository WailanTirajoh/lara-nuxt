<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super user
        $user = User::create([
            'email' => 'wailantirajoh@gmail.com',
            'name' => 'Wailan Tirajoh',
            'password' => bcrypt('wailan')
        ]);


        Role::create([
            'name' => 'Super'
        ]);

        // Read detail about "Super" role permission at auth service provider
        // By default, super can bypass any permission

        $user->assignRole('Super');

        $permissions = [
            "user-access",
            "user-store",
            "user-update",
            "user-delete",

            "post-access",
            "post-store",
            "post-update",
            "post-delete",
            "post-delete-permanent",
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                "name" => $permission
            ]);
        }
    }
}
