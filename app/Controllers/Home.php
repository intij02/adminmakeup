<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        helper('permissions');

        if (! session()->has('user')) {
            return redirect()->to('/control');
        }

        return view('home', [
            'title' => 'Inicio',
        ]);
    }
}
