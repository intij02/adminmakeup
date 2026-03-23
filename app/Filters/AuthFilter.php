<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session()->has('user')) {
            return null;
        }

        session()->set('intended_url', current_url(true)->getPath());

        if ($request->isAJAX()) {
            return service('response')->setJSON([
                'ok'      => false,
                'message' => 'No autenticado.',
            ])->setStatusCode(401);
        }

        return redirect()->to('/control');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
