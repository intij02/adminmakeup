<?php
namespace App\Controllers;
use App\Models\CotizacionesModel;
use Config\Database;


class Home extends BaseController
{
    /*public function index()
    {
        if (session()->has('user')) {
            $fechaActual = new \DateTime();
            $fechaActual->modify('-1 hour');
            $fecha = $fechaConUnaHoraMenos = $fechaActual->format('Y-m-d');
            $mdCotizaciones = new CotizacionesModel();
            $cots = $mdCotizaciones
            ->selectSum('total')
            ->where('fecha', $fecha)
            ->first();
            $data['total'] = $cots['total'];
            return view('home', $data); // Retorna una vista
        } else {
            return redirect()->to('/control?url=' . urlencode(current_url())); // Redirige correctamente
        }
    }*/
    public function index(){
        if (session()->has('user')) {
            $fechaActual = new \DateTime();
            $fechaActual->modify('-1 hour');
            $fecha = $fechaConUnaHoraMenos = $fechaActual->format('Y-m-d');
            $mdCotizaciones = new CotizacionesModel();
            $cots = $mdCotizaciones
            ->selectSum('total')
            ->where('fecha', $fecha)
            ->first();
            $ventasPosMes = $this->ventasPosMes($fecha);
            $ventasWebMes = $this->ventasWebMes($fecha);
            $data['ventasPosMes'] = $ventasPosMes;
            $data['ventasWebMes'] = $ventasWebMes;
            $data['total'] = $cots['total'];
            return view('home-1', $data); // Retorna una vista
        } else {
            return redirect()->to('/control?url=' . urlencode(current_url())); // Redirige correctamente
        }
    }
    private function ventasPosMes($fecha = null){
/*
        // Conexión a la BD poscdmx_sis
        $db = \Config\Database::connect('poscdmx_sis');

        if ($fecha === null) {
            $fecha = date('Y-m-d');
        }
        // Tomamos el mes y año actual
        $mes  = date('m', strtotime($fecha));
        $anio = date('Y', strtotime($fecha));

        // Ejecutamos la consulta
        $builder = $db->table('pos_notas');
        $builder->selectSum('total', 'total_mes');
        $builder->where('MONTH(created_at)', $mes);
        $builder->where('YEAR(created_at)', $anio);

        $query = $builder->get();
        $row   = $query->getRow();
        $data = [
            'ventas_mes' => $row->total_mes ?? 0,
            'fecha' => $fecha
        ];

        // Regresamos el resultado
        return $data;
*/
        if ($fecha === null) {
            $fecha = date('Y-m-d');
        }

        $client = \Config\Services::curlrequest([
            'timeout'      => 30,
            'http_errors'  => false,
            'headers'      => [
                'Authorization' => 'Bearer ' . getenv('API_TOKEN_POS'),
                'Accept'        => 'application/json',
            ],
            'verify' => true, // SSL ON
        ]);

        $url = 'https://pos.centrodemayoreocdmx.com/ApiPosController/ventasMes?fecha=' . $fecha;

        $response = $client->get($url);

        if ($response->getStatusCode() !== 200) {
            return [
                    'success' => false,
                    'error'   => 'Error al consultar POS',
                    'body'    => $response->getBody()
                ];
        }

        $data = json_decode($response->getBody(), true);

        if (!$data) {
            return [
                    'success' => false,
                    'error'   => 'Respuesta inválida del POS'
                ];
        }

        return [
            'success'     => true,
            'ventas_mes' => $data['ventas_mes'] ?? 0,
            'fecha'      => $data['fecha'] ?? $fecha,
            'mes'        => $data['mes'] ?? null,
            'anio'       => $data['anio'] ?? null,
        ];
    }
    private function ventasWebMes($fecha = null){

        // Conexión a la BD poscdmx_sis
        $db = \Config\Database::connect();

        if ($fecha === null) {
            $fecha = date('Y-m-d');
        }
        $fechaInicio = date('Y-m-01', strtotime($fecha)); // primer día del mes actual
        $fechaFin    = date('Y-m-t', strtotime($fecha));  // último día del mes actual

        $builder = $db->table('cot_pagos');
        $builder->select("SUM(cotizaciones.total) as total_mes");
        $builder->join('cotizaciones', 'cot_pagos.id_cot = cotizaciones.id');
        $builder->where("DATE(cot_pagos.fecha) >=", $fechaInicio);
        $builder->where("DATE(cot_pagos.fecha) <=", $fechaFin);
        $query = $builder->get();
        $row   = $query->getRow();
        $data = [
            'ventas_mes' => $row->total_mes ?? 0,
            'fecha' => $fecha
        ];

        // Regresamos el resultado
        return $data;
    }
    public function apiProdXMes()
    {
        return $this->response->setJSON($this->prodxmes());
    }
    private function prodxmes(){
        $db = Database::connect();

        $query = $db->query("
            SELECT t.*
            FROM (
                SELECT 
                    MONTH(cp.created_at) AS mes,
                    p.descripcion,
                    SUM(cp.cantidad) AS total_vendido
                FROM cotizaciones_prods cp
                INNER JOIN productos p ON p.id = cp.id_prod
                WHERE cp.deleted_at IS NULL
                  AND p.deleted_at IS NULL
                  AND YEAR(cp.created_at) = YEAR(CURDATE())
                GROUP BY mes, cp.id_prod
            ) t
            INNER JOIN (
                SELECT 
                    mes,
                    MAX(total_cantidad) AS max_vendido
                FROM (
                    SELECT 
                        MONTH(cp.created_at) AS mes,
                        cp.id_prod,
                        SUM(cp.cantidad) AS total_cantidad
                    FROM cotizaciones_prods cp
                    WHERE cp.deleted_at IS NULL
                      AND YEAR(cp.created_at) = YEAR(CURDATE())
                    GROUP BY mes, cp.id_prod
                ) x
                GROUP BY mes
            ) m
            ON t.mes = m.mes AND t.total_vendido = m.max_vendido
            ORDER BY t.mes ASC;

        ");

        $result = $query->getResult();
        return $result;
    }
    public function apiVentasPorCP(){
        $db = db_connect();

        $query = $db->query("
SELECT d.estado, COUNT(*) AS total_pedidos
FROM cot_pagos p
JOIN clientes_dir d 
    ON d.id_cliente = p.id_cliente 
    AND d.selected = 1
WHERE p.fecha >= CONCAT(YEAR(CURDATE()), '-01-01')
  AND p.fecha <= CONCAT(YEAR(CURDATE()), '-12-31')
GROUP BY d.estado
ORDER BY total_pedidos DESC
LIMIT 10;
        ");

        return $this->response->setJSON($query->getResult());
    }
}
