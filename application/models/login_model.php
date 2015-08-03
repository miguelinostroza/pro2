<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class login_model extends CI_Model {
	function __construct()
     {
          parent::__construct();
     }
     public function CreaMenu($idUser){
     	$sql ="select U.id, U.Nombre, U.Apellidos, AU.Usuario, AU.Proteccion, AU.Estatus, M.id as idMenu, M.Linea, ";
     	$sql = $sql." M.Descripcion, M.URL From usuario as U inner join accesoUsuario as AU on U.id=AU.Usuario ";
		$sql = $sql." inner join menu as M on AU.Proteccion = M.id WHERE AU.Usuario='".$idUser."' AND AU.Estatus=1 ORDER BY M.id ASC";
		$query=$this->db->query($sql);
		return $query->result();
     }
      function LoginBD($username)
     {
          $this->db->where('Email', $username);
          //$this->db->where('PASSWORD', $password);
          return $this->db->get('usuario')->row();
     }
}