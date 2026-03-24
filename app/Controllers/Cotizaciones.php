<?php namespace App\Controllers;
use App\Models\CotizacionesModel;
use App\Models\Cotizaciones_prodsModel;
use App\Models\ClientesDirModel;
use App\Models\ClientesModel;
use App\Models\ProductosModel;
use App\Models\CotGuiasModel;
use App\Models\CotSeguimientoModel;
use App\Models\TicketsModel;
use App\Models\TicketsTextosModel;
use App\Models\ImagenesProdModel;
use App\Models\SaldosModel;
use App\Models\AppTextosModel;
use App\Models\PagosModel;


class Cotizaciones extends BaseController
{
	public function index(){
		if(check_permisos('cotizaciones')){
			date_default_timezone_set('America/Mexico_City');
			//$fechaActual = new \DateTime();
			//$fechaActual->modify('-1 hour');
			//$fecha = $fechaConUnaHoraMenos = $fechaActual->format('Y-m-d');
			$fecha = date('Y-m-d');
			$data['date'] = $fecha;
			$data['cotizaciones'] = $this->getDataCotizaciones($fecha);
			return view('cotizaciones_2025', $data);
		}else{
			return redirect()->to('/control?url='.urlencode(current_url()));
		}
	}
public function borrar_lista()
{
    if (!check_permisos('admin') && !check_permisos('super')) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Sin permisos para esta acción.'
        ]);
    }

    $db              = \Config\Database::connect();
    $mdCotizaciones  = new CotizacionesModel();
    $mdCotProds      = new Cotizaciones_prodsModel();
    $mdProductos     = new ProductosModel();
    $mdPagos         = new PagosModel(); // <-- modelo de pagos

    $data    = $this->request->getJSON(true);
    $ids     = $data['ids'] ?? [];
    $reporte = [];
    $omitidas = [];

    if (empty($ids)) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'No se enviaron IDs.'
        ]);
    }

    $db->transBegin();

    try {

        foreach ($ids as $cot_id) {

            $cotizacion = $mdCotizaciones->find($cot_id);

            if (!$cotizacion) {
                continue; // si no existe, saltar
            }

            // Verificar si tiene pagos registrados
            $existePago = $mdPagos
                ->where('id_cot', $cot_id) // <-- cambiar si tu campo es distinto
                ->where('deleted_at', null)
                ->countAllResults();

            if ($existePago > 0) {
                $omitidas[] = $cot_id;
                continue; 
            }

            // Obtener productos
            $prods = $mdCotProds->where('id_cot', $cot_id)->findAll();

            foreach ($prods as $prod) {

                $producto = $mdProductos->find($prod['id_prod']);

                if ($producto) {

                    $nuevaExistencia = $producto['existencia'] + $prod['cantidad'];
                    $agotado = ($nuevaExistencia <= 0) ? 1 : 0;

                    $dataNueva = [
                        'existencia' => $nuevaExistencia,
                        'agotado'    => $agotado
                    ];

                    $reporte[] = [
                        'id_producto' => $producto['id'],
                        'cantidad'    => $prod['cantidad'],
                        'nombre'      => $producto['descripcion'],
                        'antes'       => $producto['existencia'],
                        'ahora'       => $nuevaExistencia,
                    ];

                    if (!$mdProductos->update($producto['id'], $dataNueva)) {
                        throw new \Exception("Error al actualizar producto {$producto['id']}");
                    }
                }

                if (!$mdCotProds->delete($prod['id'])) {
                    throw new \Exception("Error al eliminar producto de cotización (ID: {$prod['id']}).");
                }
            }

            // Eliminar cotización
            if (!$mdCotizaciones->delete($cot_id)) {
                throw new \Exception("Error al eliminar cotización ID {$cot_id}.");
            }
        }

        if (!$db->transStatus()) {
            throw new \Exception("La transacción falló.");
        }

        $db->transCommit();

        return $this->response->setJSON([
            'success'   => true,
            'message'   => 'Proceso completado.',
            'reporte'   => $reporte,
            'omitidas'  => $omitidas
        ]);

    } catch (\Exception $e) {

        $db->transRollback();

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}
	public function borrar($id=null, $fecha=null){
		// Modelos necesarios
	    $mdCotizaciones = new CotizacionesModel();
	    $mdCotProds = new Cotizaciones_prodsModel();
	    $mdProductos = new ProductosModel();

	    // Validar el ID de cotización
	    if (!$id || !$mdCotizaciones->find($id)) {
	        return redirect()->back()->with('error', 'La cotización no existe.');
	    }

	    // Eliminar la cotización
	    $mdCotizaciones->delete($id);

	    // Obtener los productos asociados a la cotización
	    $prods = $mdCotProds->where('id_cot', $id)->findAll();

	    // Procesar cada producto asociado
	    foreach ($prods as $prod) {
	        $producto = $mdProductos->find($prod['id_prod']);
	        if ($producto) {
	            // Actualizar la existencia del producto
	            $nuevaExistencia = $producto['existencia'] + $prod['cantidad'];
	            $mdProductos->update($producto['id'], ['existencia' => $nuevaExistencia]);
	        }
	        // Eliminar el producto de la tabla de cotizaciones
	        $mdCotProds->delete($prod['id']);
	    }
	    $productos = get_productos();
	    // Redirigir con mensaje de éxito
	    return redirect()->to('/cotizaciones/fecha/' . $fecha)->with('success', 'Cotización eliminada correctamente.');
	}
	public function fecha($fecha = null){
		if(check_permisos('cotizaciones')){
			$data['date'] = $fecha;
			$data['cotizaciones'] = $this->getDataCotizaciones($fecha);
			return view('cotizaciones_2025', $data);
		}else{
			return redirect()->to('/control?url='.urlencode(current_url()));
		}
	}
	public function editar_dir(){
		if($_SERVER['REQUEST_METHOD'] === 'POST'){
			$mdDirecs = new ClientesDirModel();
			$id_cot = $this->request->getVar('id_cot');
			$id = $this->request->getVar('id_dir');
			$data = [
				'recibe' => $this->limpiarTextoUnicode($this->request->getVar('recibe')),
				'calle' => $this->limpiarTextoUnicode($this->request->getVar('calle')),
				'numExt' => $this->request->getVar('numExt'),
				'numInt' => $this->request->getVar('numInt'),
				'col' => $this->limpiarTextoUnicode($this->request->getVar('col')),
				'del_mun' => $this->limpiarTextoUnicode($this->request->getVar('del_mun')),
				'estado' => $this->limpiarTextoUnicode($this->request->getVar('estado')),
				'cp' => $this->request->getVar('cp'),
			];
			if($id>0){
				$mdDirecs->update($id, $data);
			}
			return redirect()->to('/cotizaciones/editar_cot/'.$id_cot);
		}
	}
	public function editar_cte_data($id = null){
		if($_SERVER['REQUEST_METHOD'] === 'POST'){
			$mdClientes = new ClientesModel();
			$cliente = $mdClientes->find($id);
			$id_cot = $this->request->getVar('id_cot');
			if($cliente){
				$mdClientes->update($cliente['id'], ['nombre' => $this->request->getVar('nombre')]);
				return redirect()->to('/cotizaciones/editar_cot/'.$id_cot);
			}
		}
	}
	private function limpiarTextoUnicode($texto) {
    	// Normaliza caracteres Unicode especiales a su forma ASCII
    	$texto = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $texto);
    	return $texto;
	}
	/*private function getDataCotizaciones($fecha){
		$db = \Config\Database::connect();
		$builder = $db->table('cotizaciones');
		$builder->select('cotizaciones.*, clientes.nombre as cliente_nombre, clientes.telefono as cliente_tel, cot_pagos.recibo as pago_recibo');
		$builder->join('clientes', 'clientes.id = cotizaciones.id_cliente', 'left');
		$builder->join('cot_pagos', 'cot_pagos.id_cot = cotizaciones.id', 'left');
		$builder->where('cotizaciones.fecha', $fecha);
		$builder->where('cotizaciones.deleted_at IS NULL');
		$query = $builder->get();
    	$result = $query->getResultArray();
		return $result;
	}*/
	private function getDataCotizaciones($fecha)	{
	    $db = \Config\Database::connect();
	    $builder = $db->table('cotizaciones');

	    // Usar select() para solo traer columnas necesarias
	    $builder->select([
	        'cotizaciones.id',
	        'cotizaciones.cid',
	        'cotizaciones.fecha',
	        'cotizaciones.hora',
	        'cotizaciones.total',
	        'cotizaciones.entienda',
	        'cotizaciones.observaciones',
	        'clientes.nombre AS cliente_nombre',
	        'clientes.telefono AS cliente_tel',
	        'cot_pagos.recibo AS pago_recibo'
	    ]);

	    // JOINs optimizados
	    $builder->join('clientes', 'clientes.id = cotizaciones.id_cliente', 'left');
	    $builder->join('cot_pagos', 'cot_pagos.id_cot = cotizaciones.id', 'left');

	    // Índice en fecha para mejorar rendimiento en búsquedas
	    $builder->where('cotizaciones.fecha', $fecha);

	    // Verificar si deleted_at está vacío o NULL
	    $builder->where('cotizaciones.deleted_at', null);

	    // Limitar columnas para optimizar la carga de datos
	    $query = $builder->get();
	    $result = $query->getResultArray();

	    return $result;
	}

	public function editar_cot($id=null){
		if(check_permisos('cotizaciones')){
			$mdCotizaciones = new CotizacionesModel();
			$cotizacion = $mdCotizaciones->find($id);
			if($cotizacion){
				$mdCotGias = new CotGuiasModel();
				$mdClientes = new ClientesModel();
				$mdDirecs = new ClientesDirModel();
				$this->getProductosAppEdit();
				$this->getProductosApp();
				$desGuia = $mdCotGias->where('id_cot', $cotizacion['id'])->first();
				$cliente = $mdClientes->find($cotizacion['id_cliente']);
				$clienteDir = $mdDirecs->where('id_cliente', $cliente['id'])->first();

				$data['descGuia'] = $desGuia;
				$data['cotizacion'] = $cotizacion;
				$data['cliente'] = $cliente;
				$data['clienteDir'] = $clienteDir;
				return view('editar_cot', $data);
			}
		}else{
			return redirect()->to('/control?url='.urlencode(current_url()));
		}
	}
	public function getDataProds($id =null){
		if(check_permisos('cotizaciones')){
			$prodsCot = $this->getProductosCot($id);
			$data['prodsCot'] = $prodsCot;
			$data['id_cot'] = $id;
			return view('data_prodsCot', $data);
		}
	}
	public function add_row(){
		if($_SERVER['REQUEST_METHOD'] === 'POST'){
			$mdCotsProductos = new Cotizaciones_prodsModel();
			$id_cot = $this->request->getVar('id_cot');
			$id_prod = $this->request->getVar('id_prod');
			if($this->request->getVar('cantidad')>1){
				$cantidad = $this->request->getVar('cantidad');
			}else{
				$cantidad = 1;
			}
			$productoData = array_filter(session()->get('productosApp'), function($producto) use ($id_prod) {
				return $producto['id'] == $id_prod;
			});
			$productoData = array_values($productoData);
			$data = [
				'id_prod' => $id_prod,
				'id_cot' => $id_cot,
				'cantidad' => $cantidad,
				'precio' => $productoData[0]['precio_p'],
			];
			$mdCotsProductos->save($data);
			$this->updateTotal($id_cot);
		}
	}
	public function update_row(){
		if($_SERVER['REQUEST_METHOD'] === 'POST'){
			$mdCotsProductos = new Cotizaciones_prodsModel();
			$id = $this->request->getVar('id');
			$id_cot = $this->request->getVar('id_cot');
			$data = [
				'cantidad' => $this->request->getVar('cantidad'),
				'precio' => $this->request->getVar('precio'),
			];
			if($id>0){
				$mdCotsProductos->update($id, $data);
			}
			$this->updateTotal($id_cot);
		}
	}
	public function borrar_row(){
		if($_SERVER['REQUEST_METHOD'] === 'POST'){
			$mdCotsProductos = new Cotizaciones_prodsModel();
			$id_row = $this->request->getVar('id');
			$id_cot = $this->request->getVar('id_cot');
			if($id_row>0){
				$mdCotsProductos->delete($id_row);
			}
			$this->updateTotal($id_cot);
		}
	}
	public function upd_num(){
		if($_SERVER['REQUEST_METHOD'] === 'POST'){
			$mdCotizaciones = new CotizacionesModel();
			$num = $this->request->getVar('num');
			$id_cot = $this->request->getVar('id_cot');
			$data = ['num' => $num];
			if($id_cot>0){
				$mdCotizaciones->update($id_cot, $data);
			}
			echo $num;
		}
	}
	public function add_seguimiento(){
		if($_SERVER['REQUEST_METHOD'] === 'POST'){
			$mdCotSeg = new CotSeguimientoModel();
			$data = [
				'id_cot' => $this->request->getVar('id_cot'),
				'id_user' => session()->get('user')['id'],
				'fecha' => date('Y-m-d'),
				'hora' => date('h:i:s'),
				'texto' =>  $this->request->getVar('texto')
			];
			$mdCotSeg->save($data);
			return redirect()->to('/'.$this->request->getVar('url'));
		}		
	}
	public function get_seguimiento($id_cot = null){
		if(check_permisos('cotizaciones')){
			$mdCotSeg = new CotSeguimientoModel();
			$seguimiento = $mdCotSeg->where('id_cot', $id_cot)->orderBy('id', 'desc')->findAll();
			echo '<div class="mb-2">';
			foreach ($seguimiento as $seg) {
				echo '<div class="py-2 border-bottom">';
				echo '<div><small>'.date('d/m/y', strtotime($seg['fecha'])).'-'.date('h:i A', strtotime($seg['hora'])).'</small></div>';
				echo '<p>'.$seg['texto'].'</p>';
				echo '</div>';
			}
			echo "</div>";
		}
	}
	public function nuevo_mensaje(){
		if($_SERVER['REQUEST_METHOD'] === 'POST'){
			$id_cot = $this->request->getVar('id_cot');
			$mdTickets = new TicketsModel();
			$mdTicketsTexto = new TicketsTextosModel();
			$ticket = $mdTickets->where('id_cot', $id_cot)->first();
			$fecha = date('Y-m-d');
			$hora = date('h:i:s');
			if($ticket){
				$datatk = [
					'id_ticket' => $ticket['id'],
					'cid_ticket' => $ticket['cid'],
					'texto' => $this->request->getVar('texto'),
					'fecha' => $fecha,
					'hora' => $hora,
					'respuesta' => 1
				];
				$mdTicketsTexto->save($datatk);
				$dataTkUpd = [
					'estatus' => 1
				];
				$mdTickets->update($ticket['id'], $dataTkUpd);
			}else{
				$cid = md5(uniqid(rand(),true));
				$data = [
					'id_cot' => $id_cot,
					'cid' => $cid,
					'id_cliente' => $this->request->getVar('id_cte'),
					'fecha' => $fecha,
					'hora' => $hora
				];
				$mdTickets->save($data);
				$idTk = $mdTickets->insertID();
				$data = [
					'id_ticket' => $idTk,
					'cid_ticket' => $cid,
					'texto' => $this->request->getVar('texto'),
					'fecha' => $fecha,
					'hora' => $hora,
					'respuesta' => 1,
					'id_user' => session()->get('user')['id']
				];
				$mdTicketsTexto->save($data);
			}
			return redirect()->to('/'.$this->request->getVar('url'));
		}
	}
	public function print($id = null){
		if(check_permisos('cotizaciones')){
			$mdCotizaciones = new CotizacionesModel();
			$cotizacion = $mdCotizaciones->find($id);
			if($cotizacion){
				$mdCotSeg = new CotSeguimientoModel();
				$mdCotGias = new CotGuiasModel();
				$mdClientes = new ClientesModel();
				$mdDirecs = new ClientesDirModel();
				$mdImagenes = new ImagenesProdModel();
				$mdTextos = new AppTextosModel();
				$textoM = $mdTextos->where('seccion', 'pos_tienda')->first();
				$this->getProductosApp();
				$cliente = $mdClientes->find($cotizacion['id_cliente']);
				$clienteDir = $mdDirecs->where('id_cliente', $cliente['id'])->first();
				$seguimiento = $mdCotSeg->where('id_cot', $cotizacion['id'])->orderBy('id', 'desc')->findAll();
				$data['seguimiento'] = $seguimiento;
				$data['imagenes'] = $mdImagenes->findAll();
				$data['prodsCot'] = $this->getProductosCot($cotizacion['id']);
				$data['cotizacion'] = $cotizacion;
				$data['cliente'] = $cliente;
				$data['clienteDir'] = $clienteDir;
				$data['texto_post'] = $textoM;
				return view('print_cot', $data);
			}
		}else{
			return redirect()->to('/control?url='.urlencode(current_url()));
		}
	}
	public function prod($archivo)
	{
	    if(!session()->has('user')){
	        return redirect()->to('/');
	    }

	    $archivo = basename($archivo);

	    $ruta = '/home/b8f6c7j1rt0f/public_html/cm_v1/public/img/productos/' . $archivo;

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
	private function updateTotal($id_cot){
		$mdCotizaciones = new CotizacionesModel();
		$productos = $this->getProductosCot($id_cot);
		$monto = 0;
		$total = 0;
		foreach ($productos as $prod) {
			$monto = $prod['cantidad']*$prod['precio'];
			$total = $monto + $total;
		}
		if($id_cot>0){
			$datatotal = ['total' => $total];
			$mdCotizaciones->update($id_cot, $datatotal);
		}		
	}
	public function upd_prods(){
		$mdProductos = new ProductosModel();
		$productos = $mdProductos->withDeleted()->findAll();
		//$productos = $mdProductos->where('app', 1)->findAll();
		session()->set('productosAppEdit', $productos);
		session()->set('productosApp', $productos);
	}
	private function getProductosApp(){
		$mdProductos = new ProductosModel();
		$productos = $mdProductos->withDeleted()->findAll();
		session()->set('productosApp', $productos);
	}
	private function getProductosAppEdit(){
		if(!session()->has('productosAppEdit')){
			$mdProductos = new ProductosModel();
			$productos = $mdProductos->where('app', 1)->findAll();
			session()->set('productosAppEdit', $productos);
		}
	}
	private function getProductosCot($cot_id){
		$mdCotsProductos = new Cotizaciones_prodsModel();
		$productos = $mdCotsProductos->where('id_cot', $cot_id)->orderBy('id', 'desc')->findAll();
		return $productos;
	}
	public function saldo($folio = null){
		if (session()->has('user')) {
			$mdSaldos = new SaldosModel();
			$saldo = $mdSaldos->where('folio', $folio)->first();
			if($saldo){
				$data = [
					'comprobante' => $saldo['comprobante'],
					'mensaje' => $saldo['mensaje'],
					'saldo' => 1
				];
			}else{
				$data = [
					'saldo' => 0
				];
			}
			echo json_encode($data);
		}else{
			return redirect()->to('/control?url='.urlencode(current_url()));
		}
	}
public function revivir($id = null)
{
    if (!session()->has('user')) {
        return;
    }
    if (!$id) {
        return;
    }

    $cotModel  = new CotizacionesModel();
    $prodModel = new Cotizaciones_prodsModel();

    // Verifica que la cotización exista (aunque esté eliminada)
    $cot = $cotModel->withDeleted()->find($id);

    if (!$cot) {
        return;
    }

    // Revivir cotización
    $cotModel->update($id, ['deleted_at' => null]);

    // Revivir todos los productos de esa cotización
    $prodModel->where('id_cot', $id)
              ->withDeleted()
              ->set(['deleted_at' => null])
              ->update();

    echo "listo";
}
	//--------------------------------------------------------------------

}
