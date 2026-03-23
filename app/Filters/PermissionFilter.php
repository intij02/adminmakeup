<?php

namespace App\Filters;

use App\Services\PermissionService;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class PermissionFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $permission = isset($arguments[0]) ? (string) $arguments[0] : '';

        $service = new PermissionService();

        if ($service->sessionHasPermission($permission)) {
            return null;
        }

        if ($request->isAJAX()) {
            return service('response')->setJSON([
                'ok'      => false,
                'message' => 'Sin permisos para esta acci\u00f3n.',
            ])->setStatusCode(403);
        }

        return response()->setStatusCode(403)->setBody(view('auth/forbidden'));
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
