<?php 
use CodeIgniter\CodeIgniter;

use App\Models\ProductosModel;

    function get_productos(){
        $mdProductos = new ProductosModel();
        $productos = $mdProductos->findAll();
        session()->set('productos', $productos);
        return session()->get('productos');
    }