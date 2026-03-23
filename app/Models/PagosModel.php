<?php 
namespace App\Models;

use CodeIgniter\Model;

class PagosModel extends Model
{
    protected $table      = 'cot_pagos';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['id_cliente', 'id_cot', 'recibo', 'fecha', 'atiende', 'cta', 'hora', 'visto', 'guia', 'verificado', 'atencion', 'tienda', 'notas'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

}
?>