<?php

use App\Services\PermissionService;

if (! function_exists('check_permisos')) {
    function check_permisos(string $permission = ''): bool
    {
        $service = new PermissionService();

        return $service->sessionHasPermission($permission);
    }
}
