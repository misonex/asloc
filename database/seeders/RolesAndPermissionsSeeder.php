<?php
  
namespace Database\Seeders;
  
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
  
// pot rula independent cu: php artisan db:seed --class=RolesAndPermissionsSeeder

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crearea permisiunilor
        $permissions = [
            // Admin roles 
            'manage settings',
            'manage user roles',

            // Manager roles
            'manage associations',
            'manage residents',
            'send invites',
            'send notifications',

            // Auditor roles
            'view maintenance lists',
            'view financial reports',
            'view supplier invoices',
            'view resident reports',

            // Resident roles
            'view maintenance lists',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Crearea rolurilor
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $managerRole = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $auditorRole = Role::firstOrCreate(['name' => 'auditor', 'guard_name' => 'web']);
        $residentRole = Role::firstOrCreate(['name' => 'resident', 'guard_name' => 'web']);

        // Admin primește toate permisiunile
        $adminRole->syncPermissions(Permission::all());
        
        // Manager - permisiuni pentru gestionarea asociației și rezidenților
        $managerRole->syncPermissions([
            'manage associations',
            'manage residents',
            'send invites',
            'send notifications',
        ]);

        // Cenzor - doar permisiuni de vizualizare a stării unei asociații
        $auditorRole->syncPermissions([
            'view maintenance lists',
            'view financial reports',
            'view supplier invoices',
            'view resident reports',

        ]);

        // Rezident - doar permisiuni de vizualizare a listei de întreținere și gestionare a contului personal
        $residentRole->syncPermissions([
            'view maintenance lists',
        ]);
    }
}