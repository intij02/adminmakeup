<?php namespace App\Controllers;
use App\Models\ClientesModel;
use App\Models\ClientesDirModel;
use Config\Database;

class Clientes extends BaseController
{
	public function index(){
		if(check_permisos(service('uri')->getPath())){
			if(!session()->has('clientes')){
				get_clientes_data();
			}
			return view('clientes');
		}else{
			return redirect()->to('/control?url='.urlencode(current_url()));
		}
	}
	public function get_info($id = null){
		$mdClientesDir = new ClientesDirModel();
		$dirs = $mdClientesDir->where('id_cliente', $id)->first();
		if($dirs){
			echo json_encode($dirs);
		}else{
			echo json_encode(null);
		}
	}
	public function resetPass(){
		if(check_permisos('admin')){
			return view('restartPass');
		}
	}
	public function resetPassDo()
	{
		if(check_permisos('admin')){
			$tel = $this->request->getVar('tel');
			if(!$tel){
				return redirect()->back();
			}
			$mdclientes = new ClientesModel();
			$tel = '+52'.$tel;
			$cliente = $mdclientes->where('telefono', $tel)->first();
			if($cliente){
				$mdclientes->update($cliente['id'], ['passw' => '']);
				return redirect()->to('/clientes/resetPass?success');
			}
		}
	}

public function datatable()
{
    $db = \Config\Database::connect();

    $builder = $db->table('clientes c');

    $builder->select("
        c.id,
        c.nombre,
        c.telefono,

        COUNT(DISTINCT CASE 
            WHEN p.id_cot IS NOT NULL THEN co.id 
        END) AS pedidos_pagados,

        COUNT(DISTINCT CASE 
            WHEN p.id_cot IS NULL THEN co.id 
        END) AS pedidos_no_pagados
    ");

    $builder->join(
        'cotizaciones co',
        "co.id_cliente = c.id AND co.deleted_at = '0000-00-00 00:00:00'",
        'left'
    );

    // SUBQUERY SOLO DE IDS (ligero)
    $builder->join(
        '(SELECT DISTINCT id_cot FROM cot_pagos) p',
        'p.id_cot = co.id',
        'left'
    );

    $builder->where("c.deleted_at = '0000-00-00 00:00:00'");
    $builder->groupBy('c.id');

    $data = $builder->get()->getResultArray();

    return $this->response->setJSON(['data' => $data]);
}


public function pedidos($id)
{
    $db = \Config\Database::connect();

    $builder = $db->table('cotizaciones co');

    $builder->select("
        co.id,
        co.fecha,
        co.total,
        CASE 
            WHEN p.id_cot IS NOT NULL THEN 1
            ELSE 0
        END AS pagado
    ", false); // 👈 CLAVE

	$builder->join(
	    '(SELECT DISTINCT id_cot 
	      FROM cot_pagos 
	      WHERE recibo IS NOT NULL 
	        AND recibo != ""
	    ) p',
	    'p.id_cot = co.id',
	    'left'
	);

    // OJO: id_cliente usa CID
    $builder->where('co.id_cliente', $id);

    // Compatible con tu BD
    $builder->where("co.deleted_at = '0000-00-00 00:00:00'", null, false);

    $builder->orderBy('co.fecha', 'DESC');

    return $this->response->setJSON(
        $builder->get()->getResultArray()
    );
}
	//--------------------------------------------------------------------

}
