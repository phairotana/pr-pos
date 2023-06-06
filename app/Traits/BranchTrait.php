<?php

namespace App\Traits;

trait BranchTrait{
    public function listDataByBranch($query, $column = 'branch_id')
    {
        try{
            $auth = \Auth::user();
            $authBranchId = $auth->branch_id ?? NULL;
            $authRoles = $auth->roles->pluck('name')->toArray();
            if(!in_array('DEVELOPER', $authRoles) && !in_array('ADMINISTRATOR', $authRoles)){
                $query->where($column, $authBranchId);
            }
        }catch(\Exception $exp){
            /**
             * ERROR
             */
        }
    }
}
