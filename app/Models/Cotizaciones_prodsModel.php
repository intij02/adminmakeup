<?php 
namespace App\Models;

use CodeIgniter\Model;

class Cotizaciones_prodsModel extends Model
{
    protected $table      = 'cotizaciones_prods';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['id_prod', 'cantidad', 'descripcion', 'precio', 'id_cliente', 'id_user', 'id_cot', 'fecha', 'anotacion', 'created_at','deleted_at'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

}
?>