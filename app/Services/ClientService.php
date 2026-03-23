<?php

namespace App\Services;

class ClientService
{
    public function listSummary(): array
    {
        $db = db_connect();
        $builder = $db->table('clientes c');

        $builder->select(
            'c.id, c.nombre, c.telefono, ' .
            "COUNT(DISTINCT CASE WHEN p.id_cot IS NOT NULL THEN co.id END) AS pedidos_pagados, " .
            "COUNT(DISTINCT CASE WHEN p.id_cot IS NULL THEN co.id END) AS pedidos_no_pagados"
        );

        $builder->join(
            'cotizaciones co',
            "co.id_cliente = c.id AND (co.deleted_at IS NULL OR LEFT(CAST(co.deleted_at AS CHAR), 4) = '0000')",
            'left'
        );

        $builder->join(
            '(SELECT DISTINCT id_cot FROM cot_pagos) p',
            'p.id_cot = co.id',
            'left'
        );

        $builder->where("(c.deleted_at IS NULL OR LEFT(CAST(c.deleted_at AS CHAR), 4) = '0000')", null, false);
        $builder->groupBy('c.id');
        $builder->orderBy('c.nombre', 'ASC');

        return $builder->get()->getResultArray();
    }

    public function listOrdersByClient(int $clientId): array
    {
        $db = db_connect();
        $builder = $db->table('cotizaciones co');

        $builder->select(
            'co.id, co.fecha, co.total, ' .
            'CASE WHEN p.id_cot IS NOT NULL THEN 1 ELSE 0 END AS pagado',
            false
        );

        $builder->join(
            '(SELECT DISTINCT id_cot FROM cot_pagos WHERE recibo IS NOT NULL AND recibo != "") p',
            'p.id_cot = co.id',
            'left'
        );

        $builder->where('co.id_cliente', $clientId);
        $builder->where("(co.deleted_at IS NULL OR LEFT(CAST(co.deleted_at AS CHAR), 4) = '0000')", null, false);
        $builder->orderBy('co.fecha', 'DESC');

        return $builder->get()->getResultArray();
    }
}
