<?php

    use App\Models\admin\Permission;

    /**
     * It returns the permission of the user for a specific module.
     * 
     * @param ModuleId The id of the module you want to check the permission for.
     * 
     * @return A collection of Permission objects.
     */
    function getUserPermission($ModuleId = 0) {
        $data = Permission::where('DesignationId', Auth::user()->DesignationId ?? null)
            ->where('ModuleId', $ModuleId)
            ->limit(1)
            ->get();

        return $data;
    }

    /**
     * If the user is an admin, or if the user has read permission for the module, then return true.
     * Otherwise, return false.
     * 
     * @param ModuleId The ID of the module you want to check permissions for.
     * @param ReturnView If you want to return a 403 error page, set this to true.
     * 
     * @return a boolean value.
     */
    function isReadAllowed($ModuleId = 0, $ReturnView = false) {
        $flag = false;
        if ($ModuleId) {
            $permission = getUserPermission($ModuleId);
            if ($permission && count($permission)) {
                $flag = $permission[0]->Read == 1;
            }
            if (Auth::user()->IsAdmin) $flag = true;
        }
        
        if ($ReturnView) {
            if (!$flag) abort(403);
        }
        return $flag;
    }

    function isCreateAllowed($ModuleId = 0, $ReturnView = false) {
        $flag = false;
        if ($ModuleId) {
            $permission = getUserPermission($ModuleId);
            if ($permission && count($permission)) {
                $flag = $permission[0]->Create == 1;
            }
            if (Auth::user()->IsAdmin) $flag = true;
        }
        
        if ($ReturnView) {
            if (!$flag) abort(403);
        }
        return $flag;
    }

    function isEditAllowed($ModuleId = 0, $ReturnView = false) {
        $flag = false;
        if ($ModuleId) {
            $permission = getUserPermission($ModuleId);
            if ($permission && count($permission)) {
                $flag = $permission[0]->Edit == 1;
            }
            if (Auth::user()->IsAdmin) $flag = true;
        }
        
        if ($ReturnView) {
            if (!$flag) abort(403);
        }
        return $flag;
    }

    function isDeleteAllowed($ModuleId = 0, $ReturnView = false) {
        $flag = false;
        if ($ModuleId) {
            $permission = getUserPermission($ModuleId);
            if ($permission && count($permission)) {
                $flag = $permission[0]->Delete == 1;
            }
            if (Auth::user()->IsAdmin) $flag = true;
        }
        
        if ($ReturnView) {
            if (!$flag) abort(403);
        }
        return $flag;
    }