<?php

namespace App\Helpers;

class RolePermission
{
    /** 
     * @param string $moduleName
     * @param object $crud backpack crud ($this->crud)
     * @return string
     */
    public static function checkPermission($crud, $moduleName)
    {
        // DENY CREATE
        if (!backpack_user()->can('create ' . $moduleName)) :
            $crud->denyAccess('create');
        endif;
        // DENY UPDATE
        if (!backpack_user()->can('update ' . $moduleName)) :
            $crud->denyAccess('update');
        endif;
        // DENY SHOW
        if (!backpack_user()->can('show ' . $moduleName)) :
            $crud->denyAccess('show');
        endif;
        // DENY LIST
        if (!backpack_user()->can('list ' . $moduleName)) :
            Abort(404);
        endif;
        // DENY DELETE
        if (!backpack_user()->can('delete ' . $moduleName)) :
            $crud->denyAccess('delete');
            $crud->denyAccess('bulkDelete');
        endif;
    }
}
