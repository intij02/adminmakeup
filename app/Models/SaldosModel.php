<?php 
namespace App\Models;

use CodeIgniter\Model;

class SaldosModel extends Model
{
    protected $table      = 'cot_saldos';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['id_cliente', 'folio', 'comprobante', 'mensaje'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

}
?>