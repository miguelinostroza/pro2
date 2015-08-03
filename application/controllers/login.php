<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('America/Mexico_City');
class login extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('login_model');
	}
	public function index()
	{
		if($this->session->userdata('is_logged_in')){
			$this->load->view('constant');
			$this->load->view('view_header');
			$this->load->view('view_home');
			$this->load->view('view_footer');
		}else{
			$this->load->view('constant');
			$this->load->view('view_login');
		}
	}
	 function CerrarSesion(){
          /*destrozamos la sesion activay nos vamos al login de nuevo*/
          if($this->session->userdata('is_logged_in')){
               $this->session->sess_destroy(); 
               redirect('login', 'refresh');
          }
    }
	public function ValidaAcceso(){
		session_start();
		$Login 		= json_decode($this->input->post('LoginPost'));
		$response = array (
				"campo"     => "",
	            "error_msg" => ""
	    );
	    
	    if($Login->UserName==""){
			$response["campo"]     = "email";
			$response["error_msg"]   = "<div class='alert alert-danger text-center' alert-dismissable> <button type='button' class='close' data-dismiss='alert'>&times;</button>La Correo es Obligatorio</div>";
		}else if($Login->Password==""){
			$response["campo"]     = "password";
			$response["error_msg"]   = "<div class='alert alert-danger text-center' alert-dismissable><button type='button' class='close' data-dismiss='alert'>&times;</button>La Contraseña es obligatorio</div>";
		}else{
			$user = $this->login_model->LoginBD($Login->UserName);  
			if(count($user) == 1){
				$crypt     = crypt($Login->Password, $user->Password);  
				if($user->Password==$crypt){
					 $tipoUser= "Administrador";
					 if($user->Tipo==2){$tipoUser="Vendedor";}
					 $session = array(
                         'ID'           => $user->id,
                         'NOMBRE'       => $user->Nombre,
                         'APELLIDOS'    => $user->Apellidos,
                         'EMAIL'        => $Login->UserName,
                         'TIPOUSUARIO'  => $user->Tipo,
                         'TIPOUSUARIOMS'=> $tipoUser,
                         'is_logged_in' => TRUE,                 
                         );
					$Menu = $this->login_model->CreaMenu($user->id);
					//$Menu = json_encode($Menu);
					$this->session->set_userdata($session);//Cargamos la sesion de datos del usuario logeado
	                $_SESSION['Menu'] = $Menu;//cargamos la sesion del menu de acuerdo a los permisos
	                $response["error_msg"]   = '<meta http-equiv="refresh" content="0">';
				}else{
					$response["error_msg"]   = "<div class='alert alert-danger text-center' alert-dismissable><button type='button' class='close' data-dismiss='alert'>&times;</button>La Contraseña Contraseña es Invalida  </div>";
				}
				
			}else{
				$response["error_msg"]   = "<div class='alert alert-danger text-center' alert-dismissable><button type='button' class='close' data-dismiss='alert'>&times;</button>El Email es Invalido </div>";
			}
		}
		echo json_encode($response);
	}
}
