<?php namespace App\Controllers;
use App\Models\UsuariosModel;
use App\Models\UsuariosPermisosModel;

class Control extends BaseController
{
	public function index(){
		if(session()->has('user')){
			return redirect()->to('/');
		}
		if ($this->request->getMethod() === 'POST') {
			$mdUsuarios = new UsuariosModel();
			$user = $mdUsuarios->where('user', $this->request->getVar('user'))->first();
			$url = session()->get('intended_url');
			if($user){
				if($user['pass'] == md5($this->request->getVar('pass'))){
					session()->set('user', $user);
					$mdUserPermisos = new UsuariosPermisosModel();
					$permisos = $mdUserPermisos->find($user['id']);
					session()->set('permisos', $permisos);
					return redirect()->to($url);
				}
			}
		}
		return view('login');
	}
	public function exit(){
		session()->destroy();
		return redirect()->to('/control');
	}
	//--------------------------------------------------------------------

}
