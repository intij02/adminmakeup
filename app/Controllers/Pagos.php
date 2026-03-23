<?php namespace App\Controllers;
use App\Models\PagosModel;
use App\Models\CuentasPagosModel;
use App\Models\PagosPosModel;

class Pagos extends BaseController
{
	public function index(){
		if(check_permisos(service('uri')->getPath())){
			$this->get_cuentas();
			$data['cuentas'] = session()->get('cuentas_pagos');
			date_default_timezone_set('America/Mexico_City');
			$fechaActual = new \DateTime();
			$fechaActual->modify('-1 hour');
			$fecha = $fechaConUnaHoraMenos = $fechaActual->format('Y-m-d');
			$data['date'] = $fecha;
			$data['pagos'] = $this->getDataPagos($fecha);
        	return view('pagos', $data);
		}else{
			return redirect()->to('/control?url='.urlencode(current_url()));
		}
	}
	public function tienda(){
		if(check_permisos(service('uri')->getPath())){
			$this->get_cuentas();
			$data['cuentas'] = session()->get('cuentas_pagos');
			date_default_timezone_set('America/Mexico_City');
			$fechaActual = new \DateTime();
			$fechaActual->modify('-1 hour');
			$fecha = $fechaConUnaHoraMenos = $fechaActual->format('Y-m-d');
			$data['date'] = $fecha;
			$data['pagos'] = $this->getDataPagosTienda($fecha);
        	return view('pagos_tienda', $data);
		}else{
			return redirect()->to('/control?url='.urlencode(current_url()));
		}
	}
	public function fecha($fecha = null){
		if(check_permisos('pagos')){
			$this->get_cuentas();
			$data['cuentas'] = session()->get('cuentas_pagos');
			$data['date'] = $fecha;
			$data['pagos'] = $this->getDataPagos($fecha);
        	return view('pagos', $data);
		}else{
			return redirect()->to('/control?url='.urlencode(current_url()));
		}
	}
	public function fecha_tienda($fecha = null){
		if(check_permisos('pagos')){
			$this->get_cuentas();
			$data['cuentas'] = session()->get('cuentas_pagos');
			$data['date'] = $fecha;
			$data['pagos'] = $this->getDataPagosTienda($fecha);
        	return view('pagos_tienda', $data);
		}else{
			return redirect()->to('/control?url='.urlencode(current_url()));
		}
	}
	public function print_pagos($fecha = null){
		if(check_permisos('admin') || check_permisos('super')){
			$data['date'] = $fecha;
			$data['pagos'] = $this->getDataPagos($fecha);
        	return view('print_pagos', $data);
		}else{
			return redirect()->to('/control?url='.urlencode(current_url()));
		}
	}
	public function get_cuentas(){
		if(!session()->has('cuentas_pagos')){
			$mdCuentas = new CuentasPagosModel();
			$cuentas = $mdCuentas->findAll();
			session()->set('cuentas_pagos', $cuentas);
		}		
	}
	private function getDataPagos($fecha){
		$db = \Config\Database::connect();
		$builder = $db->table('cot_pagos cp');
		$builder->select('cp.id AS pago_id, cp.fecha AS fecha_pago, cp.recibo AS recibo, cp.hora AS hora, cp.cta AS cta, cp.tienda AS tienda, cp.visto AS visto, cp.guia AS guia, cp.verificado AS verificado, cp.atencion AS atencion, cp.notas AS notas, c.id AS id_cot, c.total AS total_cotizacion, cl.nombre AS nombre_cliente, cl.telefono AS telefono');
		$builder->join('cotizaciones c', 'cp.id_cot = c.id');
		$builder->join('clientes cl', 'c.id_cliente = cl.id');
		$builder->where('cp.fecha', $fecha);
		$builder->orderBy('cp.id', 'DESC');
		$query = $builder->get();
    	$result = $query->getResultArray();
		return $result;
	}
	private function getDataPagosTienda($fecha){
		$db = \Config\Database::connect();
		$builder = $db->table('pagos_pos cp');
		$builder->select('cp.id AS pago_id, cp.id_cot AS folio, cp.fecha AS fecha_pago, cp.recibo AS recibo, cp.hora AS hora, cp.cta AS cta, cp.tienda AS tienda, cp.visto AS visto, cp.guia AS guia, cp.verificado AS verificado, cp.atencion AS atencion, cp.notas AS notas');
		$builder->where('cp.fecha', $fecha);
		$builder->where('cp.deleted_at IS NULL');
		$builder->orderBy('cp.id', 'DESC');
		$query = $builder->get();
    	$result = $query->getResultArray();
		return $result;
	}
	public function upd_verifP($id=null, $fecha=null){
		if(session()->has('user')){
			$mdPagosModel = new PagosModel();
			$pago = $mdPagosModel->find($id);
			if($pago){
				$dataU = [
					'verificado' => 1
				];
				$mdPagosModel->update($pago['id'], $dataU);
				return redirect()->to('/pagos/fecha/'.$fecha);
			}
		}
	}
	public function upd_verifPTienda($id=null, $fecha=null){
		if(session()->has('user')){
			$mdPagosModel = new PagosPosModel();
			$pago = $mdPagosModel->find($id);
			if($pago){
				$dataU = [
					'verificado' => 1
				];
				$mdPagosModel->update($pago['id'], $dataU);
				return redirect()->to('/pagos/fecha_tienda/'.$fecha);
			}
		}
	}
	public function upd_impreso($id=null, $fecha=null){
		if(session()->has('user')){
			$mdPagosModel = new PagosModel();
			$pago = $mdPagosModel->find($id);
			if($pago){
				$dataU = [
					'visto' => 1
				];
				$mdPagosModel->update($pago['id'], $dataU);
				return redirect()->to('/pagos/fecha/'.$fecha);
			}
		}
	}
	public function upd_impreso_tienda($id=null, $fecha=null){
		if(session()->has('user')){
			$mdPagosModel = new PagosPosModel();
			$pago = $mdPagosModel->find($id);
			if($pago){
				$dataU = [
					'visto' => 1
				];
				$mdPagosModel->update($pago['id'], $dataU);
				return redirect()->to('/pagos/fecha_tienda/'.$fecha);
			}
		}
	}
	public function upd_guia($id=null, $fecha=null){
		if(session()->has('user')){
			$mdPagosModel = new PagosModel();
			$pago = $mdPagosModel->find($id);
			if($pago){
				$dataU = [
					'guia' => 1
				];
				$mdPagosModel->update($pago['id'], $dataU);
				return redirect()->to('/pagos/fecha/'.$fecha);
			}
		}
	}
	public function upd_guia_tienda($id=null, $fecha=null){
		if(session()->has('user')){
			$mdPagosModel = new PagosPosModel();
			$pago = $mdPagosModel->find($id);
			if($pago){
				$dataU = [
					'guia' => 1
				];
				$mdPagosModel->update($pago['id'], $dataU);
				return redirect()->to('/pagos/fecha_tienda/'.$fecha);
			}
		}
	}
	public function upd_atencion($id=null, $fecha=null){
		if(session()->has('user')){
			$mdPagosModel = new PagosModel();
			$pago = $mdPagosModel->find($id);
			if($pago){
				$dataU = [
					'atencion' => 1
				];
				$mdPagosModel->update($pago['id'], $dataU);
				return redirect()->to('/pagos/fecha/'.$fecha);
			}
		}
	}
	public function upd_atencion_tienda($id=null, $fecha=null){
		if(session()->has('user')){
			$mdPagosModel = new PagosPosModel();
			$pago = $mdPagosModel->find($id);
			if($pago){
				$dataU = [
					'atencion' => 1
				];
				$mdPagosModel->update($pago['id'], $dataU);
				return redirect()->to('/pagos/fecha_tienda/'.$fecha);
			}
		}
	}
	public function upd_atencion_q($id=null, $fecha=null){
		if(session()->has('user')){
			$mdPagosModel = new PagosModel();
			$pago = $mdPagosModel->find($id);
			if($pago){
				$dataU = [
					'atencion' => 0
				];
				$mdPagosModel->update($pago['id'], $dataU);
				return redirect()->to('/pagos/fecha/'.$fecha);
			}
		}
	}
	public function upd_atencion_q_tienda($id=null, $fecha=null){
		if(session()->has('user')){
			$mdPagosModel = new PagosPosModel();
			$pago = $mdPagosModel->find($id);
			if($pago){
				$dataU = [
					'atencion' => 0
				];
				$mdPagosModel->update($pago['id'], $dataU);
				return redirect()->to('/pagos/fecha_tienda/'.$fecha);
			}
		}
	}
	public function borrar_pago($id = null, $fecha = null){
		if(session()->has('user')){
			$mdPagosModel = new PagosModel();
			$pago = $mdPagosModel->find($id);
			if($pago){
				$mdPagosModel->delete($pago['id']);
                @unlink('../../apmkup-v2/public/pagos/'.$pago['recibo']);
                @unlink('../scol/pagos/'.$pago['recibo']);
				return redirect()->to('/pagos/fecha/'.$fecha);
			}
		}
	}
	public function borrar_pago_tienda($id = null, $fecha = null){
		if(session()->has('user')){
			$mdPagosModel = new PagosPosModel();
			$id = intval($id);
			$pago = $mdPagosModel->find($id);
			if($pago){
				$mdPagosModel->delete($pago['id']);
                @unlink('../../apmkup-v2/public/pagos/'.$pago['recibo']);
                @unlink('../scol/pagos/'.$pago['recibo']);
				return redirect()->to('/pagos/fecha_tienda/'.$fecha);
			}
		}
	}
	public function atencion(){
		if($_SERVER['REQUEST_METHOD'] === 'POST'){
			$mdPagos = new PagosModel();
			$id = $this->request->getVar('id_pago');
			$fecha = $this->request->getVar('fecha');
			$pago = $mdPagos->find($id);
			if($pago){
				$data = ['notas' => $this->request->getVar('notas')];
				$mdPagos->update($pago['id'], $data);
				return redirect()->to('/pagos/fecha/'.$fecha);
			}
		}
	}
	//--------------------------------------------------------------------

	public function ver($archivo)
	{
	    if(!session()->has('user')){
	        return redirect()->to('/');
	    }

	    $archivo = basename($archivo);

	    $ruta = '/home/b8f6c7j1rt0f/public_html/cm_v1/public/pagos/' . $archivo;

	    if (!file_exists($ruta)) {
	        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
	    }

	    $mime = mime_content_type($ruta);

	    $permitidos = ['image/jpeg','image/png','image/webp'];

	    if (!in_array($mime, $permitidos)) {
	        throw \CodeIgniter\Exceptions\PageForbiddenException::forPageForbidden();
	    }

	    return $this->response
	        ->setHeader('Content-Type', $mime)
	        ->setHeader('Content-Disposition', 'inline; filename="' . $archivo . '"')
	        ->setBody(file_get_contents($ruta));
	}

}
