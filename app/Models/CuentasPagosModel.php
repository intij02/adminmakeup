<?php 
namespace App\Models;

use CodeIgniter\Model;

class CuentasPagosModel extends Model
{
    protected $table      = 'cuentas_pagos';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['cuenta', 'imagen', 'nombre', 'num_cta', 'banco', 'activo', 'tipo', 'entienda', 'monto_min', 'monto_max', 'lugar'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

}
?>