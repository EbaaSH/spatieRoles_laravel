<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // define roles
        $adminRole = Role::create(['name' => 'admin']);
        $clientRole = Role::create(['name' => 'client']);
        // define permisions
        $permissions = [
            'report.delete',
            'report.update',
            'report.create',
            'report.index'
        ];
        foreach ($permissions as $permission) {
            Permission::findorcreate($permission, 'api');
        }// if we use api we also use web guardName 

        //assign permission to roles
        $adminRole->syncPermissions($permissions);//delete old permissions and keep those inside the $permissions
        $clientRole->givePermissionTo(['report.create', 'report.index']);

        /////////////////////////////////////////////////

        //create Users an assign roles
        $adminUser = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $adminUser->assignRole($adminRole);

        // Asign permissions associated with the role to the user
        $permissions = $adminRole->permissions()->pluck('name')->toArray();
        $adminUser->givePermissionTo($permissions);
        // this is the same that we do in the last command but assignRole dont work

        /////////////////////////////////////////////////////////

        $clientUser = User::factory()->create([
            'name' => 'Client User',
            'email' => 'clent@example.com',
            'password' => bcrypt('password'),
        ]);
        $clientUser->assignRole($clientRole);
        // Assign permissions associated with the role to the user
        $permissions = $clientRole->permissions()->pluck('name')->toArray();
        $clientUser->givePermissionTo($permissions);
    }
}
