<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $role1=Role::create(['name'=>'Administrador']);
        $role2=Role::create(['name'=>'Jefe de área']);
        $role3=Role::create(['name'=>'Técnico de soporte']);
        $role4=Role::create(['name'=>'Usuario estándar']);

        //*MODO ADMINISTRADOR:
        //1)Permisos de todos los usuarios registrados (en general), tambien tiene acceso
        //  a los roles 
        $permission = Permission::create(['name' => 'usuarios.index'])->syncRoles([$role1]);
        $permission = Permission::create(['name' => 'usuarios.edit'])->syncRoles([$role1]);
        $permission = Permission::create(['name' => 'usuarios.update'])->syncRoles([$role1]);
        $permission = Permission::create(['name' => 'usuarios.show'])->syncRoles([$role1]);

        //2)Permisos de todos los tickets reportados por los clientes
        $permission = Permission::create(['name' => 'Tickets.index'])->syncRoles([$role1]);
        $permission = Permission::create(['name' => 'Tickets.edit'])->syncRoles([$role1]);
        $permission = Permission::create(['name' => 'Tickets.update'])->syncRoles([$role1]);
        $permission = Permission::create(['name' => 'Tickets.show'])->syncRoles([$role1]);

        //3) Ver todos los comentarios de los clientes

        //4) Analisis de tickets

        //5) Grafico de rendimientos

        //*MODO TECNICO ADMINISTRADOR, JEFE DE AREA Y TECNICO DE SOPORTE
        //1)Permisos de los tickets asignados  
        $permission = Permission::create(['name' => 'misTickets.index'])->syncRoles([$role1,$role2,$role3]);
        $permission = Permission::create(['name' => 'misTickets.edit'])->syncRoles([$role1,$role2,$role3]);
        $permission = Permission::create(['name' => 'misTickets.update'])->syncRoles([$role1,$role2,$role3]);
        $permission = Permission::create(['name' => 'misTickets.show'])->syncRoles([$role1,$role2,$role3]);             

        // Permisos para que puedan elegir un tickets que esta sin asignar


        //* SOLO PARA EL JEFE DE AREA
        //1)Permiso para cambiar tickets asignado a un tecnico a otro

        //2)Ver los tickets asignados a los tecnicos de su area 

        //3)Ver los comentarios de los clientes referentes a la atencion de sus incidencias

        //4) Ver todos los agentes tecnicos que pertenecen a su area
        


        //* SOLO PARA EL USUARIO ESTANDAR (CLIENTES)
        //1)Crear Tickets

        //2)Consultas sus tickets reportados
        $permission = Permission::create(['name' => 'consultarTickets.index'])->syncRoles([$role4]);

    }
}
