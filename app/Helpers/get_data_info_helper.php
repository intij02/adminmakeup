<?php 
use CodeIgniter\CodeIgniter;
use App\Models\ClientesModel;
use App\Models\ClientesDirModel;


    function get_clientes_data(){
        $mdClientes = new ClientesModel();
        $clientes = $mdClientes->select('id, nombre,telefono')->findAll();        
        session()->set('clientes', $clientes);
    }
