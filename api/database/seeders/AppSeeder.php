<?php

namespace Database\Seeders;

use App\Models\User;
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
        $this->createRoles();
        $this->createPermissions();
        $this->syncRolePermissions();
        $this->createUsers();
    }

    private function createRoles()
    {
        $roles = [
            'Super',
            'Administrator',
            'Writer',
            'Visitor',
        ];

        foreach ($roles as $role) {
            Role::create([
                'name' => $role,
            ]);
        }
    }

    private function createPermissions()
    {
        $permissions = [
            'user-viewAny',
            'user-view',
            'user-create',
            'user-update',
            'user-delete',
            'user-restore',
            'user-forceDelete',

            'post-viewAny',
            'post-view',
            'post-create',
            'post-update',
            'post-delete',
            'post-restore',
            'post-forceDelete',

            'role-viewAny',
            'role-view',
            'role-create',
            'role-update',
            'role-delete',
            'role-restore',
            'role-forceDelete',

            'channel-viewAny',
            'channel-view',
            'channel-create',
            'channel-update',
            'channel-delete',
            'channel-restore',
            'channel-forceDelete',

            'react-viewAny',
            'react-view',
            'react-create',
            'react-update',
            'react-delete',
            'react-restore',
            'react-forceDelete',

            'thread-viewAny',
            'thread-view',
            'thread-create',
            'thread-update',
            'thread-delete',
            'thread-restore',
            'thread-forceDelete',

            'reply-viewAny',
            'reply-view',
            'reply-create',
            'reply-update',
            'reply-delete',
            'reply-restore',
            'reply-forceDelete',
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission,
            ]);
        }
    }

    private function syncRolePermissions()
    {
        $writerRole = Role::findByName('Writer');
        $writerRole->syncPermissions([
            'post-viewAny',
            'post-view',
            'post-create',
            'post-update',
            'post-delete',

            'channel-viewAny',
            'channel-view',
            'channel-create',
            'channel-update',
            'channel-delete',
            'channel-restore',
            'channel-forceDelete',

            'react-viewAny',
            'react-view',
            'react-create',
            'react-update',
            'react-delete',
            'react-restore',
            'react-forceDelete',

            'thread-viewAny',
            'thread-view',
            'thread-create',
            'thread-update',
            'thread-delete',
            'thread-restore',
            'thread-forceDelete',

            'reply-viewAny',
            'reply-view',
            'reply-create',
            'reply-update',
            'reply-delete',
            'reply-restore',
            'reply-forceDelete',
        ]);

        $visitorRole = Role::findByName('Visitor');
        $visitorRole->syncPermissions([
            'channel-viewAny',
            'channel-view',
            'channel-create',
            'channel-update',
            'channel-delete',
            'channel-restore',
            'channel-forceDelete',

            'react-viewAny',
            'react-view',
            'react-create',
            'react-update',
            'react-delete',
            'react-restore',
            'react-forceDelete',

            'thread-viewAny',
            'thread-view',
            'thread-create',
            'thread-update',
            'thread-delete',
            'thread-restore',
            'thread-forceDelete',

            'reply-viewAny',
            'reply-view',
            'reply-create',
            'reply-update',
            'reply-delete',
            'reply-restore',
            'reply-forceDelete',
        ]);
    }

    private function createUsers()
    {
        // Super user
        $user = User::create([
            'email' => 'wailantirajoh@gmail.com',
            'name' => 'Wailan Tirajoh',
            'password' => bcrypt('wailan'),
        ]);

        $userPutri = User::create([
            'email' => 'putririnding@gmail.com',
            'name' => 'Putri Rinding',
            'password' => bcrypt('putri'),
        ]);

        $writerUser = User::create([
            'email' => 'writer@gmail.com',
            'name' => 'Writer',
            'password' => bcrypt('writer'),
        ]);

        $visitorUser = User::create([
            'email' => 'visitor@gmail.com',
            'name' => 'Visitor',
            'password' => bcrypt('visitor'),
        ]);

        $writerUser->assignRole('Writer');
        $visitorUser->assignRole('Visitor');
        // Read detail about "Super" role permission at auth service provider
        // By default, super can bypass any permission
        $user->assignRole('Super');
        $userPutri->assignRole('Super');
    }
}
