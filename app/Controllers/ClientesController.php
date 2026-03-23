<?php

namespace App\Controllers;

use App\Services\ClientService;

class ClientesController extends BaseController
{
    public function index(): string
    {
        return view('clientes/index', [
            'title' => 'Clientes',
        ]);
    }

    public function datatable()
    {
        $service = new ClientService();

        return $this->response->setJSON([
            'ok'   => true,
            'data' => $service->listSummary(),
        ]);
    }

    public function pedidos(int $id)
    {
        $service = new ClientService();

        return $this->response->setJSON([
            'ok'   => true,
            'data' => $service->listOrdersByClient($id),
        ]);
    }
}
