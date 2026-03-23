<?php namespace App\Controllers;
use App\Models\UsuariosModel;
use App\Models\UsuariosPermisosModel;

class Admin extends BaseController
{
	public function nuevo_usuario(){
		if(check_permisos('admin')){
			if($_SERVER['REQUEST_METHOD'] === 'POST'){
				$mdUsers = new UsuariosModel();
				$mdUsersPerm = new UsuariosPermisosModel();
				$dataUser = [
					'nombre' => $this->request->getVar('nombre'),
					'user' => $this->request->getVar('user'),
					'pass' => md5($this->request->getVar('pass')),
				];
				$mdUsers->save($dataUser);
				$idUser = $mdUsers->insertID();
				$dataPerm = [
					'id_user' => $idUser,
					'permisos' => $this->request->getVar('permisos')
				];
				$mdUsersPerm->save($dataPerm);
				return redirect()->to('/admin/usuarios');
			}
			return view('nuevo_usuario');
		}else{
			return redirect()->to('/control?url='.urlencode(current_url()));
		}
	}
	public function editar_usuario($id = null){
		if(check_permisos('admin')){
			$mdUsers = new UsuariosModel();
			$mdUsersPerm = new UsuariosPermisosModel();
			$user = $mdUsers->find($id);
			$permisos = $mdUsersPerm->where('id_user', $id)->first();
			$data['user'] = $user;
			$data['permisos'] = $permisos;
			if($_SERVER['REQUEST_METHOD'] === 'POST'){
				if($this->request->getVar('pass') == ""){
					$pass = $user['pass'];
				}else{
					$pass = md5($this->request->getVar('pass'));
				}
				$dataUser = [
					'nombre' => $this->request->getVar('nombre'),
					'user' => $this->request->getVar('user'),
					'pass' => $pass,
				];
				$mdUsers->update($user['id'], $dataUser);
				$dataPerm = [
					'permisos' => $this->request->getVar('permisos')
				];
				$mdUsersPerm->update($permisos['id'], $dataPerm);
				return redirect()->to('/admin/usuarios');
			}
			return view('editar_usuario', $data);
		}else{
			return redirect()->to('/control?url='.urlencode(current_url()));
		}
	}
	public function usuarios(){
		if(check_permisos('admin')){
			$mdUsers = new UsuariosModel();
			$users = $mdUsers->findAll();
			$data['users'] = $users;
			return view('usuarios', $data);
		}else{
			return redirect()->to('/control?url='.urlencode(current_url()));
		}
	}
	//--------------------------------------------------------------------

}

