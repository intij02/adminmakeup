<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Services\PermissionService;

class UsersController extends BaseController
{
    public function index(): string
    {
        helper('permissions');

        return view('admin/users/index', [
            'title' => 'Usuarios',
        ]);
    }

    public function list()
    {
        $users = new UserModel();
        $rows = $users->select('id, nombre, user, created_at, updated_at')
            ->orderBy('id', 'DESC')
            ->findAll();

        $permissionService = new PermissionService();

        foreach ($rows as &$row) {
            $row['permissions'] = $permissionService->getPermissionsForUserId((int) $row['id']);
        }

        return $this->response->setJSON([
            'ok'   => true,
            'data' => $rows,
        ]);
    }

    public function create()
    {
        $rules = [
            'nombre' => 'required|min_length[3]|max_length[120]',
            'user'   => 'required|min_length[3]|max_length[60]|is_unique[sys_users.user]',
            'pass'   => 'required|min_length[8]|max_length[255]',
        ];

        if (! $this->validateData($this->request->getPost(), $rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'ok'      => false,
                'message' => 'Validaci\u00f3n incorrecta.',
                'errors'  => $this->validator->getErrors(),
            ]);
        }

        $users = new UserModel();
        $id = $users->insert([
            'nombre' => (string) $this->request->getPost('nombre'),
            'user'   => (string) $this->request->getPost('user'),
            'pass'   => password_hash((string) $this->request->getPost('pass'), PASSWORD_DEFAULT),
        ], true);

        $permissions = $this->request->getPost('permissions') ?? [];
        $permissionService = new PermissionService();
        $permissionService->updatePermissions((int) $id, is_array($permissions) ? $permissions : []);

        log_message('notice', 'auth_event=user_created by={actor} created_user={createdUser}', [
            'actor'       => (string) session('user.user'),
            'createdUser' => (string) $this->request->getPost('user'),
        ]);

        return $this->response->setJSON([
            'ok'      => true,
            'message' => 'Usuario creado.',
        ]);
    }

    public function update(int $id)
    {
        $rules = [
            'nombre' => 'required|min_length[3]|max_length[120]',
            'user'   => "required|min_length[3]|max_length[60]|is_unique[sys_users.user,id,{$id}]",
            'pass'   => 'permit_empty|min_length[8]|max_length[255]',
        ];

        if (! $this->validateData($this->request->getPost(), $rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'ok'      => false,
                'message' => 'Validaci\u00f3n incorrecta.',
                'errors'  => $this->validator->getErrors(),
            ]);
        }

        $users = new UserModel();
        $data = [
            'nombre' => (string) $this->request->getPost('nombre'),
            'user'   => (string) $this->request->getPost('user'),
        ];

        $password = (string) $this->request->getPost('pass');
        if ($password !== '') {
            $data['pass'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $users->update($id, $data);

        $permissions = $this->request->getPost('permissions') ?? [];
        $permissionService = new PermissionService();
        $permissionService->updatePermissions($id, is_array($permissions) ? $permissions : []);

        log_message('notice', 'auth_event=user_updated by={actor} user_id={id}', [
            'actor' => (string) session('user.user'),
            'id'    => $id,
        ]);

        return $this->response->setJSON([
            'ok'      => true,
            'message' => 'Usuario actualizado.',
        ]);
    }

    public function delete(int $id)
    {
        $users = new UserModel();

        if ($users->delete($id) === false) {
            return $this->response->setStatusCode(400)->setJSON([
                'ok'      => false,
                'message' => 'No se pudo eliminar el usuario.',
            ]);
        }

        log_message('notice', 'auth_event=user_deleted by={actor} user_id={id}', [
            'actor' => (string) session('user.user'),
            'id'    => $id,
        ]);

        return $this->response->setJSON([
            'ok'      => true,
            'message' => 'Usuario eliminado.',
        ]);
    }
}
