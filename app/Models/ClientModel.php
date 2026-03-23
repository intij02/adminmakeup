<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'cid',
        'nombre',
        'email',
        'passw',
        'fbpic',
        'secure',
        'direccion',
        'cp',
        'rfc',
        'telefono',
        'r_social',
        'precio',
        'credito',
        'limite_c',
        'web',
        'app',
        'bloqueado',
        'aut',
        'fbtoken',
    ];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
}
