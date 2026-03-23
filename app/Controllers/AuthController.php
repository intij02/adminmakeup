<?php

namespace App\Controllers;

use App\Services\AuthService;

class AuthController extends BaseController
{
    public function login()
    {
        if (session()->has('user')) {
            return redirect()->to('/');
        }

        if ($this->request->getMethod() !== 'POST') {
            return view('auth/login', [
                'title' => 'Acceso',
            ]);
        }

        $authService = new AuthService();
        $result = $authService->attemptLogin(
            (string) $this->request->getPost('user'),
            (string) $this->request->getPost('pass'),
        );

        if (! $result['ok']) {
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(401)->setJSON($result);
            }

            return redirect()->back()->withInput()->with('error', $result['message']);
        }

        $url = session()->get('intended_url') ?: '/';
        session()->remove('intended_url');

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'ok'       => true,
                'redirect' => $url,
            ]);
        }

        return redirect()->to($url);
    }

    public function logout()
    {
        $authService = new AuthService();
        $authService->logout();

        return redirect()->to('/control');
    }
}
