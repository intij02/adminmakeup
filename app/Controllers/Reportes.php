<?php namespace App\Controllers;
use App\Models\ProductosModel;
use App\Models\CotizacionesModel;
use App\Models\Cotizaciones_prodsModel;
use App\Models\CuentasPagosModel;
use App\Models\PagosModel;
require_once APPPATH.'ThirdParty/tcpdf/tcpdf.php';
use TCPDF;

class Reportes extends BaseController
{
	public function index(){
		if(check_permisos(service('uri')->getPath())){
			$productos = get_productos();
			return view('reportes', );
		}else{
			return redirect()->to('/control?url='.urlencode(current_url()));
		}
	}
	public function fecha($fecha = null){
		if(check_permisos('reportes')){
			$data = [];
			if($fecha){
				$data['fecha'] = $fecha;
			}
			return view('reportes', $data);
		}else{
			return redirect()->to('/control?url='.urlencode(current_url()));
		}
	}
	public function lista_reporte($getFecha = null){
		if(session()->has('user')){
			date_default_timezone_set('America/Mexico_City');
			$fechaActual = new \DateTime();
			$fechaActual->modify('-1 hour');
			$hoy = $fechaConUnaHoraMenos = $fechaActual->format('Y-m-d');
            //$hoy = date('Y-m-d');
            if($getFecha){
                $fecha = $getFecha;
            }else{
                $fecha = $hoy;
            }
            $lista = $this->get_top_cotizados($fecha);
            if($lista){
                foreach ($lista as $prod) {
                    if($prod['agotado']){
                        $agotado = "Si";
                    }else{
                        $agotado = "No";
                    }
                    echo '
                        <tr>
                          <td class="col-6"><small>'.$prod['prod'].'</small></td>
                          <td>'.$prod['total'].'</td>
                          <td>'.$prod['existencia'].'</td>
                          <td>'.$prod['limite'].'</td>
                          <td>'.$agotado.'</td>
                          <td><button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editarP" data-info="'.$prod['id'].'"><i class="fas fa-pencil-alt"></i></button></td>
                        </tr>
                    ';
                }
            }
        }
	}
	public function get_data_exis($id = null){
		$mdProductos = new ProductosModel();
		$prod = $mdProductos->find($id);
		if($prod){
			$data = [
				'id' => $prod['id'],
				'descripcion' => $prod['descripcion'],
				'existencia' => $prod['existencia'],
				'limite' => $prod['limite_num']
			];
			echo json_encode($data);
		}
	}
	private function get_top_cotizados($fecha){
        $prodCot = new Cotizaciones_prodsModel();
        $productos = new ProductosModel();

        $prod_cot_hoy_gp = $prodCot->where('fecha', $fecha)->orderBy('id_prod', 'ASC')->groupBy('id_prod')->get()->getResultArray();
        $prod_cot_hoy = $prodCot->where('fecha', $fecha)->orderBy('id_prod', 'ASC')->get()->getResultArray();
        $prouctos_lista = $productos->get()->getResultArray();

        if (!$prod_cot_hoy_gp || !$prod_cot_hoy || !$prouctos_lista) {
            return false;
        }

        // Convertir resultados a arrays para un acceso más eficiente
        $prod_cot_hoy_map = [];
        foreach ($prod_cot_hoy as $prod) {
            if (!isset($prod_cot_hoy_map[$prod['id_prod']])) {
                $prod_cot_hoy_map[$prod['id_prod']] = 0;
            }
            $prod_cot_hoy_map[$prod['id_prod']] += $prod['cantidad'];
        }

        $prouctos_lista_map = [];
        foreach ($prouctos_lista as $prod_list) {
            $prouctos_lista_map[$prod_list['id']] = $prod_list;
        }

        $productos = [];
        foreach ($prod_cot_hoy_gp as $item) {
            $id_prod = $item['id_prod'];
            if (isset($prod_cot_hoy_map[$id_prod]) && isset($prouctos_lista_map[$id_prod])) {
                $prod_list = $prouctos_lista_map[$id_prod];
                $data = [
                    'id' => $id_prod,
                    'prod' => $prod_list['descripcion'],
                    'existencia' => $prod_list['existencia'],
                    'limite' => $prod_list['limite_num'],
                    'agotado' => $prod_list['agotado'],
                    'total' => $prod_cot_hoy_map[$id_prod],
                ];
                $productos[] = $data;
            }
        }

        return $productos;
    }
    public function edit_agotado(){
    	if($_SERVER['REQUEST_METHOD'] === 'POST'){
        	$mdProductos = new ProductosModel();
        	$id_prod = $this->request->getVar('id');
	        $agotado = $mdProductos->find($id_prod)['agotado'];
	        if($agotado){
	            $agotado  = 0;
	        }else{
	            $agotado = 1;
	        }
	        $data = [
	            'agotado' => $agotado,
				'show_existencia' => 0,
				'existencia' => 0
	        ];
	        if($id_prod>0){
	        	$mdProductos->update($id_prod, $data);
	        }
	    }
	    $productos = get_productos();
    }
    public function edit_prod(){
    	if($_SERVER['REQUEST_METHOD'] === 'POST'){
    		$mdProductos = new ProductosModel();
    		$idP = $this->request->getVar('id');
	        $limite = $this->request->getVar('limite') > 0 ? $this->request->getVar('limite') : 0;

			$info = [
	            'existencia' => $this->request->getVar('existencia'),
	            'show_existencia' => 1,
	            'limite' => $limite > 0 ? 1 : 0,
	            'limite_num' => $limite
			];

	        if($idP>0){
	        	$mdProductos->update($idP, $info);
	        }

	    }
	    $productos = get_productos();
    }
    public function reporte_pagos(){
    	if(check_permisos('admin')){
		    $mdCuentas = new CuentasPagosModel();
		    $cuentas = $mdCuentas->findAll();
	    	$data = [
				'f' => null,
				'cuenta_S' => null,
				'cuenta_Nom' => null,
				'cots' => null,
				'pagos' => null,
				'cuentas' => $cuentas
	    	];
	    	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		    	$mdCotizaciones = new CotizacionesModel();
		    	$mdPagos = new PagosModel();
		        $fecha = $this->request->getVar('fecha_select');
		        $cuentaSR = $this->request->getVar('cta');
		        $cuentaSelect = $mdCuentas->where('cuenta', $cuentaSR)->first();
		        $nomCta = $cuentaSelect['nombre'];
		        $fecha_pagos = date('Y-m', strtotime($fecha));
		        $lista_cots = $mdCotizaciones->where("DATE_FORMAT(fecha, '%Y-%m') =", $fecha_pagos)->findAll();
		        $lista_pagos = $mdPagos->where('fecha', $fecha)->where('cta', $cuentaSR)->findAll();
		        $data['f'] = $fecha;
		        $data['cuenta_S'] = $cuentaSR;
		        $data['cuenta_Nom'] = $nomCta;
		        $data['cots'] = $lista_cots;
		        $data['pagos'] = $lista_pagos;
		    }
		    return view('reporte_pagos_sm', $data);
		}else{
			return redirect()->to('/control?url='.urlencode(current_url()));
		}
    }
    public function imprimirReporte($fecha = null, $cuenta = null){
    	if(check_permisos('admin')){

		    $mdCuentas = new CuentasPagosModel();
		    $cuentas = $mdCuentas->findAll();
		   	$mdCotizaciones = new CotizacionesModel();
		   	$mdPagos = new PagosModel();
		   	$cuentaSR = $cuenta;
		   	$cuentaSelect = $mdCuentas->where('cuenta', $cuentaSR)->first();
		   	$nomCta = $cuentaSelect['nombre'];
		   	$fecha_pagos = date('Y-m', strtotime($fecha));
		   	$lista_cots = $mdCotizaciones->where("DATE_FORMAT(fecha, '%Y-%m') =", $fecha_pagos)->findAll();
		   	$lista_pagos = $mdPagos->where('fecha', $fecha)->where('cta', $cuentaSR)->findAll();
		   	$data['f'] = $fecha;
		   	$data['cuenta_S'] = $cuentaSR;
		   	$data['cuenta_Nom'] = $nomCta;
		   	$data['cots'] = $lista_cots;
		   	$data['pagos'] = $lista_pagos;

	        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	        $pdf->SetTitle('Reporte de pagos del día'. $fecha);
	        $pdf->SetMargins(10, 10, 10);
	        $pdf->SetAutoPageBreak(TRUE, 10);
	        $pdf->AddPage();
	        $viewContent = view('reporte_pagos_sm_print', $data);
	        $pdf->writeHTML($viewContent, false, false, false, false, '');
	        $randomFile = substr(md5(rand()), 0, 5);
	        $pdf->Output('REPORTE-'.$randomFile.'.pdf', 'D');
		}else{
			return redirect()->to('/control?url='.urlencode(current_url()));
		}
    }
	public function totalHoy(){
	        $db = \Config\Database::connect();
	        $hoy = date('Y-m-d');

	        $builder = $db->table('cot_pagos');
	        $builder->selectSum('cotizaciones.total', 'total_pagado');
	        $builder->join('cotizaciones', 'cot_pagos.id_cot = cotizaciones.id');
	        $builder->where('DATE(cot_pagos.fecha)', $hoy);

	        $query = $builder->get()->getRow();

	        return $this->response->setJSON([
	            'fecha' => $hoy,
	            'total' => $query->total_pagado ?? 0
	        ]);
	}
	public function pagosPorDia(){
		if(check_permisos('admin')){
		$db = \Config\Database::connect();
		$fechaInicio = date('Y-m-01');
		$fechaFin = date('Y-m-t');

		$builder = $db->table('cot_pagos');
		$builder->select("DATE(cot_pagos.fecha) as dia, SUM(cotizaciones.total) as total");
		$builder->join('cotizaciones', 'cot_pagos.id_cot = cotizaciones.id');
		$builder->where("DATE(cot_pagos.fecha) >=", $fechaInicio);
		$builder->where("DATE(cot_pagos.fecha) <=", $fechaFin);
		$builder->groupBy("DATE(cot_pagos.fecha)");
		$builder->orderBy("dia", "ASC");

		$result = $builder->get()->getResult();

		return $this->response->setJSON($result);
		}
	}
	public function ventasPosDia(){

        $client = \Config\Services::curlrequest([
            'timeout'      => 30,
            'http_errors'  => false,
            'headers'      => [
                'Authorization' => 'Bearer ' . getenv('API_TOKEN_POS'),
                'Accept'        => 'application/json',
            ],
            'verify' => true, // SSL ON
        ]);

        $url = 'https://pos.centrodemayoreocdmx.com/ApiPosController/ventasPosDia';

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

        return $this->response->setJSON($data);


		/*if(check_permisos('admin')){
	        // Conexión a la BD poscdmx_sis
	        $db = \Config\Database::connect('poscdmx_sis');

			$fechaInicio = date('Y-m-01');
			$fechaFin = date('Y-m-t');

	        // Consulta: suma de ventas en la fecha indicada
	        $builder = $db->table('pos_notas');
	    $builder->select("DATE(pos_notas.created_at) as dia, SUM(pos_notas.total) as total");
		$builder->where("DATE(pos_notas.created_at) >=", $fechaInicio);
		$builder->where("DATE(pos_notas.created_at) <=", $fechaFin);
		$builder->groupBy("DATE(pos_notas.created_at)");
		$builder->orderBy("dia", "ASC");

		$result = $builder->get()->getResult();

		return $this->response->setJSON($result);
		}*/
	}

	public function ventasAnuales()
	{
	    if (!check_permisos('admin')) {
	        return $this->response->setStatusCode(403);
	    }

	    $anioActual = date('Y');

	    $dbWeb = \Config\Database::connect();

	    $builderWeb = $dbWeb->table('cot_pagos');
	    $builderWeb->select("MONTH(cot_pagos.fecha) as mes, SUM(cotizaciones.total) as total_web");
	    $builderWeb->join('cotizaciones', 'cot_pagos.id_cot = cotizaciones.id');
	    $builderWeb->where("YEAR(cot_pagos.fecha)", $anioActual);
	    $builderWeb->groupBy("MONTH(cot_pagos.fecha)");

	    $web = $builderWeb->get()->getResultArray();

	    $client = \Config\Services::curlrequest([
	        'timeout'     => 30,
	        'http_errors' => false,
	        'headers'     => [
	            'Authorization' => 'Bearer ' . getenv('API_TOKEN_POS'),
	            'Accept'        => 'application/json',
	        ],
	        'verify' => true,
	    ]);

	    $url = 'https://pos.centrodemayoreocdmx.com/ApiPosController/ventasAnuales';

	    $response = $client->get($url);

	    if ($response->getStatusCode() !== 200) {
	        return $this->response->setJSON([
	            'success' => false,
	            'error'   => 'Error al consultar POS',
	            'body'    => $response->getBody()
	        ]);
	    }

	    $pos = json_decode($response->getBody(), true);

	    if (!is_array($pos)) {
	        return $this->response->setJSON([
	            'success' => false,
	            'error'   => 'Respuesta inválida del POS'
	        ]);
	    }

	    $data = [];

	    for ($mes = 1; $mes <= 12; $mes++) {

	        $mesWeb = array_values(array_filter($web, fn($r) => (int)$r['mes'] === $mes));
	        $mesPos = array_values(array_filter($pos, fn($r) => (int)$r['mes'] === $mes));

	        $data[] = [
	            'mes'        => $mes,
	            'total_web' => round($mesWeb[0]['total_web'] ?? 0, 2),
	            'total_pos' => round($mesPos[0]['total_pos'] ?? 0, 2),
	        ];
	    }

	    return $this->response->setJSON($data);
	}

        	//--------------------------------------------------------------------
}
