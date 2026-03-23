<?php namespace App\Controllers;
use App\Models\ProductosModel;

class Productos extends BaseController
{

	public function index(){
		if(check_permisos('productos')){
			$productos = session()->has('productos') ? session()->get('productos') : get_productos();

			$data['productos'] = $productos;
			return view('productos', $data);
		}else{
			return redirect()->to('/control?url='.urlencode(current_url()));
		}
	}
	public function editar_producto($id=null){
		if(check_permisos('admin')){
			$productos = session()->has('productos') ? session()->get('productos') : get_productos();
			$producto = array_filter($productos, function($producto) use ($id) {
                return $producto['id'] == $id;
            });
            $producto = array_values($producto)[0];
            if($_SERVER['REQUEST_METHOD'] === 'POST'){
            	$dataU = [
            		'descripcion' => $this->request->getVar('desc'),
            		'precio_p' => $this->request->getVar('precio_p'),
            		'precio_1' => $this->request->getVar('precio_1'),
            		'precio_2' => $this->request->getVar('precio_2'),
            		'precio_3' => $this->request->getVar('precio_3'),
            		'precio_4' => $this->request->getVar('precio_4'),
            		'precio_5' => $this->request->getVar('precio_5'),
            		'peso' => $this->request->getVar('peso'),
            		'existencia' => $this->request->getVar('existencia'),
            		'limite_num' => $this->request->getVar('limite_num'),
            		'minimo' => $this->request->getVar('minimo'),
            		'peso' => $this->request->getVar('peso'),
            	];
            	//echo json_encode($dataU);
            	$mdProductos = new ProductosModel();
            	if($id>0){
            		$mdProductos->update($id, $dataU);
            		$productos = get_productos();
            		return redirect()->to('/productos/editar_producto/'.$id.'?save=true');
            	}
            }
            $data['producto'] = $producto;
            $data['id'] = $id;
            return view('editar_producto', $data);
		}else{
			return redirect()->to('/control?url='.urlencode(current_url()));
		}
	}
	//--------------------------------------------------------------------

}
