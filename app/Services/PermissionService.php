<?php

namespace App\Services;

use App\Models\UserPermissionModel;

class PermissionService
{
    public function getPermissionsForUserId(int $userId): array
    {
        $model = new UserPermissionModel();
        $row = $model->where('id_user', $userId)->first();

        if (! $row || empty($row['permisos'])) {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', (string) $row['permisos']))));
    }

    public function userHasPermission(int $userId, string $permission): bool
    {
        $permission = $this->normalizePermission($permission);

        if ($permission === '') {
            return false;
        }

        return in_array($permission, $this->getPermissionsForUserId($userId), true);
    }

    public function sessionHasPermission(string $permission): bool
    {
        $session = session();
        $user = $session->get('user');

        if (! is_array($user) || ! isset($user['id'])) {
            return false;
        }

        $permission = $this->normalizePermission($permission);
        $permissions = (array) $session->get('permissions');

        return in_array($permission, $permissions, true);
    }

    public function updatePermissions(int $userId, array $permissions): void
    {
        $normalized = array_values(array_unique(array_filter(array_map([$this, 'normalizePermission'], $permissions))));
        $permissionString = implode(',', $normalized);

        $model = new UserPermissionModel();
        $existing = $model->where('id_user', $userId)->first();

        if ($existing) {
            $model->update((int) $existing['id'], ['permisos' => $permissionString]);

            return;
        }

        $model->insert([
            'id_user'   => $userId,
            'permisos'  => $permissionString,
        ]);
    }

    public function normalizePermission(string $permission): string
    {
        return trim(str_replace('/', '', $permission));
    }
}
