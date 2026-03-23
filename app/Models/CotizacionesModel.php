<?php 
namespace App\Models;

use CodeIgniter\Model;

class CotizacionesModel extends Model
{
    protected $table      = 'cotizaciones';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['cid', 'id_cliente', 'id_usuario', 'fecha', 'hora', 'cancelada', 'observaciones', 'total', 'entienda', 'atiende', 'id_pedido', 'web', 'app', 'pagada', 'enviada', 'surtido', 'color', 'guia', 'num', 'status','deleted_at'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

}
?>