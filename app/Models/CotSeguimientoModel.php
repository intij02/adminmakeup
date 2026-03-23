<?php 
namespace App\Models;

use CodeIgniter\Model;

class CotSeguimientoModel extends Model
{
    protected $table      = 'cot_seguimiento';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['id_cot', 'id_user', 'fecha', 'hora', 'texto', 'imagen'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

}
?>