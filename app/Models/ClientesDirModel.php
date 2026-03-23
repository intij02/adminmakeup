<?php 
namespace App\Models;

use CodeIgniter\Model;

class ClientesDirModel extends Model
{
    protected $table      = 'clientes_dir';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['id_cliente', 'recibe', 'calle', 'numExt', 'numInt', 'col', 'del_mun', 'estado', 'cp', 'selected'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

}
?>