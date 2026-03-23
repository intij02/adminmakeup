<?php 
use CodeIgniter\CodeIgniter;

use App\Models\UsuariosPermisosModel;

/*if (!function_exists('check_permisos')) {
    function check_permisos($url = null) {
        $mdUserPermisos = new UsuariosPermisosModel();
        if (session()->has('user')) {
            $id_user = session()->get('user')['id'];
            $permisos_URLs = $mdUserPermisos->find($id_user);
            $urls = explode(",", $permisos_URLs['permisos']);
            if (preg_match('/\/\d+$/', $url)) {
                $urlG = preg_replace('/\/\d+$/', '', $url);
            } else {
                $urlG = $url;
            }
            return in_array($urlG, $urls);
        }
        return false;
    }
}*/
function check_permisos($url = null) {
    $mdUserPermisos = new UsuariosPermisosModel();
    if (session()->has('user')) {
        $id_user = session()->get('user')['id'];
        $permisos_URLs = $mdUserPermisos->find($id_user);
        $urls = explode(",", $permisos_URLs['permisos']);
        $urlG = str_replace('/', '', $url);
                
        
        return in_array($urlG, $urls);
    }
    return false;
}

