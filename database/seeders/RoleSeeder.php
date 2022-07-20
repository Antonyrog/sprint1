<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()

    {
        $admin = Role::create(['name' => 'Administrador']);
        $seller = Role::create(['name' => 'Vendedor']);
        $client = Role::create(['name' => 'Cliente']);
        
        //Permisos de uUsuario
        Permission::create(['name' => 'user.refresh'])->assignRole($admin);
        Permission::create(['name' => 'user.me'])->assignRole($admin);
        Permission::create(['name' => 'user.register'])->assignRole($admin);
        Permission::create(['name' => 'user.index'])->assignRole($admin);
        Permission::create(['name' => 'user.update'])->assignRole($admin);
        Permission::create(['name' => 'user.destroy'])->assignRole($admin);

        //Permisos de Producto
        Permission::create(['name' => 'articulo.index'])->syncRoles([$admin, $seller]);
        Permission::create(['name' => 'articulo.store'])->syncRoles([$admin, $seller]);
        Permission::create(['name' => 'articulo.update'])->syncRoles([$admin, $seller]);
        Permission::create(['name' => 'articulo.destroy'])->syncRoles([$admin, $seller]);

        //permisos de roles
        Permission::create(['name' => 'rol.index'])->assignRole($admin);
        Permission::create(['name' => 'rol.store'])->assignRole($admin);
        Permission::create(['name' => 'rol.update'])->assignRole($admin);
        Permission::create(['name' => 'rol.destroy'])->assignRole($admin);

        //Permisos
        Permission::create(['name' => 'permission.index'])->assignRole($admin);  
        Permission::create(['name' => 'permission.UserCreateVendedor'])->assignRole($admin); 
        Permission::create(['name' => 'permission.UserCreateCliente'])->assignRole($admin);   
            
        //Permisos de compra
        Permission::create(['name' => 'venta.sale'])->assignRole($client); 
        Permission::create(['name' => 'venta.pago'])->assignRole($client); 
        Permission::create(['name' => 'compra.add'])->assignRole($client); 
    }
}
