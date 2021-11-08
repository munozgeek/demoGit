<?php
/**
 * Created by PhpStorm.
 * User: munoz
 * Date: 2/8/2019
 * Time: 21:14
 */

namespace App\Tools\Data;


trait AppSecurity
{
    /**
     * @return array
     */
    public function DataMenu(): array
    {
        $menuSolo[] = [
            'code' => 'DASHB', 'fatherCode' => null,  'name' => 'Tablero', 'longName'=> 'Tablero',
            'route' => 'dashboard_v1', 'icon' => 'fad fa-user-chart', 'flagVisibilidad' => true
        ];

        $menuSolo[] = [
            'code' => 'PROFILE', 'fatherCode' => null,  'name' => 'Perfil', 'longName'=> 'Perfil',
            'route' => 'app_profile_user', 'icon' => 'fad fa-user-cog', 'flagVisibilidad' => true
        ];

        $menuAdministracion = [
            /*ADM*/[
                'code' => 'ADM', 'fatherCode' => null,  'name' => 'Administración', 'longName'=> 'Administración',
                'route' => null, 'icon' => 'fad fa-cogs', 'flagVisibilidad' => true
            ],
            ##############################  Catalogo  ##########################
            /*ADMCAT*/[
                'code' => 'ADMCAT', 'fatherCode' => 'ADM',  'name' => 'Catálogo', 'longName'=> 'Catálogo',
                'route' => null, 'icon' => 'fad fa-books', 'flagVisibilidad' => true
            ],

            /*ADMPROFILE*/[
                'code' => 'ADMPROFILE', 'fatherCode' => 'ADM',  'name' => 'Perfil de usuario', 'longName'=> 'Perfil de usuario',
                'route' => 'app_profile_index', 'icon' => 'fad fa-id-card-alt', 'flagVisibilidad' => true
            ],
            /*ADMPROFILE-N*/[
                'code' => 'ADMPROFILE-N', 'fatherCode' => 'ADMPROFILE',  'name' => 'Nuevo perfil', 'longName'=> 'Nuevo perfil de usuario',
                'route' => null, 'icon' => 'fad fa-plus-square', 'flagVisibilidad' => false
            ],
            /*ADMPROFILE-M*/[
                'code' => 'ADMPROFILE-M', 'fatherCode' => 'ADMPROFILE',  'name' => 'Modificar perfil', 'longName'=> 'Modificar perfil de usuario',
                'route' => null, 'icon' => 'fad fa-edit', 'flagVisibilidad' => false
            ],
            /*ADMPROFILE-E*/[
                'code' => 'ADMPROFILE-E', 'fatherCode' => 'ADMPROFILE',  'name' => 'Eliminar perfil', 'longName'=> 'Eliminar perfil de usuario',
                'route' => null, 'icon' => 'fad fa-trash-alt', 'flagVisibilidad' => false
            ],

            /*ADMUSER*/[
                'code' => 'ADMUSER', 'fatherCode' => 'ADM',  'name' => 'Usuarios', 'longName'=> 'Usuarios',
                'route' => 'app_user_index', 'icon' => 'fad fa-users', 'flagVisibilidad' => true
            ],
            /*ADMUSER-N*/[
                'code' => 'ADMUSER-N', 'fatherCode' => 'ADMUSER',  'name' => 'Nuevo Usuario', 'longName'=> 'Nuevo Usuario',
                'route' => null, 'icon' => 'fad fa-plus-square', 'flagVisibilidad' => false
            ],
            /*ADMUSER-M*/[
                'code' => 'ADMUSER-M', 'fatherCode' => 'ADMUSER',  'name' => 'Modificar Usuario', 'longName'=> 'Modificar Usuario',
                'route' => null, 'icon' => 'fad fa-edit', 'flagVisibilidad' => false
            ],
            /*ADMUSER-E*/[
                'code' => 'ADMUSER-E', 'fatherCode' => 'ADMUSER',  'name' => 'Eliminar Usuario', 'longName'=> 'Eliminar Usuario',
                'route' => null, 'icon' => 'fad fa-trash-alt', 'flagVisibilidad' => false
            ],
        ];

        return array_merge(
            $menuSolo,
            $menuAdministracion
        );
    }

    public function DataProfile(): array
    {
        return [
            [
                'code' => 'SADM',
                'name' => 'Super Administrado',
                'description' => 'Super Administrado',
                'detail' => 'All'
            ],
        ];
    }

    public function DataUser(): array
    {
        return [
            [
                'name' => 'Daniel Andres',
                'surname' => 'Muñoz Velasquez',
                'gender' => true,
                'email' => 'dmunoz@cloudbase.cl',
                'password' => '0408',
                'profile'  => 'SADM'
            ],
            [
                'name' => 'Darko',
                'surname' => 'von Bischoffshausen',
                'gender' => true,
                'email' => 'dvon@cloudbase.cl',
                'password' => '0408',
                'profile'  => 'SADM'
            ]
        ];
    }
}
