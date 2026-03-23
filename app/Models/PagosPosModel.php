<?php 
namespace App\Models;

use CodeIgniter\Model;

class PagosPosModel extends Model
{
    protected $table      = 'pagos_pos';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['id_cliente', 'id_cot', 'recibo', 'fecha', 'atiende', 'cta', 'hora', 'visto', 'guia', 'verificado', 'atencion', 'tienda'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

}
?>