<?php 
namespace App\Models;

use CodeIgniter\Model;

class  TicketsTextosModel extends Model
{
    protected $table      = 'tickets_textos';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['id_ticket', 'cid_ticket', 'texto', 'respuesta', 'fecha', 'hora', 'visto_a', 'visto_c', 'id_user'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

}
?>