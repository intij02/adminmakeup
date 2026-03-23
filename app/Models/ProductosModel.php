<?php 
namespace App\Models;

use CodeIgniter\Model;

class ProductosModel extends Model
{
    protected $table      = 'productos';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['codigo', 'codigo_b', 'descripcion', 'precio_p', 'precio_1', 'precio_2', 'precio_3', 'precio_4', 'precio_5', 'id_marca', 'id_linea', 'id_linea_sec', 'peso', 'app', 'pos', 'web', 'activo', 'existencia', 'existencia_pos', 'show_existencia', 'limite', 'agotado', 'limite_num', 'minimo', 'envio', 'promo', 'cmaster', 'piezas_caja'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

}
?>