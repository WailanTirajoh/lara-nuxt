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

        $userPutri = User::create([
            'email' => 'putririnding@gmail.com',
            'name' => 'Putri Rinding',
            'password' => bcrypt('putri')
        ]);


        Role::create([
            'name' => 'Super'
        ]);

        // Read detail about "Super" role permission at auth service provider
        // By default, super can bypass any permission

        $user->assignRole('Super');
        $userPutri->assignRole('Super');

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
            "post-restore",

            "role-access",
            "role-store",
            "role-update",
            "role-delete",

            "channel-access",
            "channel-store",
            "channel-update",
            "channel-delete",

            "react-access",
            "react-store",
            "react-update",
            "react-delete",

            "thread-access",
            "thread-store",
            "thread-update",
            "thread-delete",

            "reply-access",
            "reply-store",
            "reply-update",
            "reply-delete",
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                "name" => $permission
            ]);
        }
    }
}
