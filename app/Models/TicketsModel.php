<?php 
namespace App\Models;

use CodeIgniter\Model;

class  TicketsModel extends Model
{
    protected $table      = 'tickets';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['cid', 'id_cliente', 'id_cot', 'fecha', 'hora', 'estatus', 'impreso', 'guia', 'soporte', 'creado'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

}
?>