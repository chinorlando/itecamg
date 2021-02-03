<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('Login_model');
		$this->load->helper(array('auth/login_rules'));
	}

	// public function index()
	// {
	// 	$this->load->view('welcome_message');
	// }

	public function index()
	{
		// $this->load->view('template/header');

		$this->load->view('login/v_login');
		// $this->load->view('template/footer');
	}

	public function error()
	{
		$this->load->view('errors/html/error_404');
	}

	public function login() {
		$this->validate();
		$data = new stdClass();
		$username = $this->input->post('email');
		$password = $this->input->post('password');

		if ($this->Login_model->resolve_user_login($username, $password)) {
			
			$user_id = $this->Login_model->get_user_id_from_username($username);
			$user    = $this->Login_model->get_user($user_id);

			// nombre de usuario en la session
			$nombre_completo_id= $this->Login_model->get_user_id_from_cuenta($user->id_cuenta);				
			$nombre_completo= $this->Login_model->get_user_nombre_completo($nombre_completo_id);	

			// menu start-------------------------------------------------------
			$cadmenu = "";
            $sqlmp = "SELECT * FROM menu WHERE padre=0";
            $querymp = $this->db->query($sqlmp);
            foreach ($querymp->result() as $row){
                $padre = $row->id_menu;
                $nombrep = $row->nombre;
                $enlacep = $row->enlace;
                $iconop  = $row->icono;

                $sqlm = "SELECT menu.nombre, menu.enlace, menu.icono, menu.padre from menu
							join menu_personal on menu_personal.id_menu = menu.id_menu
							join personal on personal.id_personal = menu_personal.id_personal
							WHERE menu.padre = $padre
						     AND personal.id_cuenta = $user->id_cuenta";

                $querym = $this->db->query($sqlm);
                $cont  = count($querym->result());
                if($cont>0){
                	$cadmenu .= "<li>";
                    $cadmenu .= "<a href='".$enlacep."'><i class='".$iconop."'></i> <span>".$nombrep."</span></a>";
                    $cadmenu .= "<ul>";
                    
                        foreach ($querym->result() as $r){
                            $nombre = $r->nombre;
                            $enlace = $r->enlace;
                            $icono  = $r->icono;
                            
                            $cadmenu .= "<li class='' ><a href=".base_url().$enlace."><i class=".$iconop."></i> <span>".$nombre."</span></a></li>";
                        }
                    $cadmenu .= "</ul>";
                    $cadmenu .= "</li>";
                } 
            } 
			// menu end -------------------------------------------------------

			// set session user datas
			$_SESSION['user_id']      = (int)$user->id_cuenta;
			$_SESSION['nombre_completo'] = (string)$nombre_completo->apellido_paterno.' '.(string)$nombre_completo->apellido_materno .' '.(string)$nombre_completo->nombres;
			$_SESSION['username']     = (string)$user->username;
			$_SESSION['email']        = (string)$user->email;
			$_SESSION['logged_in']    = (bool)true;
			$_SESSION['is_confirmed'] = (bool)$user->is_confirmed;
			$_SESSION['is_type_user'] = (string)$user->is_type_user;
			$_SESSION['menu'] 		  = (string)$cadmenu;

			$users = array(
				'user_id'		=> (int)$user->id_cuenta,
				'username'      => (string)$user->username,
				'email'         => (string)$user->email,
				'logged_in'     => (bool)true,
				'is_confirmed'  => (bool)$user->is_confirmed,
				'is_type_user'  => (string)$user->is_type_user,
				'menu' 		    => (string)$cadmenu,
				'id_rol'		=> (string)$user->id_rol,
			);

			// $this->inicio();
			if ($_SESSION['is_type_user'] == 'is_admin') {
				echo json_encode(array('status' => TRUE, 'user'=> $_SESSION['is_type_user'], 'url'=> 'admin/programaciones'));
			}
			if ($_SESSION['is_type_user'] == 'is_alumno') {
				echo json_encode(array('status' => TRUE, 'user'=> $_SESSION['is_type_user'], 'url'=> 'alumno'));
			}
			if ($_SESSION['is_type_user'] == 'is_teacher') {
				echo json_encode(array('status' => TRUE, 'user'=> $_SESSION['is_type_user'], 'url'=> 'teacher'));
			}
			if ($_SESSION['is_type_user'] == 'is_docente') {
				echo json_encode(array('status' => TRUE, 'user'=> $_SESSION['is_type_user'], 'url'=> 'teacher'));
			}
			if ($_SESSION['is_type_user'] == 'is_secretaria') {
				echo json_encode(array('status' => TRUE, 'user'=> $_SESSION['is_type_user'], 'url'=> 'admin/secretaria'));
			}
			if ($_SESSION['is_type_user'] == 'is_rector') {
				echo json_encode(array('status' => TRUE, 'user'=> $_SESSION['is_type_user'], 'url'=> 'admin/rector'));
			}
			if ($_SESSION['is_type_user'] == 'is_director') {
				echo json_encode(array('status' => TRUE, 'user'=> $_SESSION['is_type_user'], 'url'=> 'admin/director'));
			}
			// echo json_encode(array('status' => TRUE, 'user'=> $_SESSION['is_type_user']));
		} else {
			$this->session->set_flashdata('login_flash', 'Correo electónico incorrecto o contraseña incorrecta.');
			echo json_encode(array('error' => TRUE));
		}
	}

	public function logout() {
		
		// create the data object
		$data = new stdClass();
		$miurl = base_url();
		
		if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
			
			// remove session datas
			foreach ($_SESSION as $key => $value) {
				unset($_SESSION[$key]);
			}
			
			// user logout ok
			$this->load->view('template/header');
			$this->load->view('login/v_login');
			// $this->load->view('login/v_login', $data);
			$this->load->view('template/footer');
			redirect('/');
			// echo json_encode(array('miurl' => $miurl));
			
		} else {
			
			// there user was not logged in, we cannot logged him out,
			// redirect him to site root
			redirect('/');
			// echo json_encode(array('miurl' => $miurl));
		}
	}


	public function register() {
		
		// create the data object
		$data = new stdClass();
		
		// load form helper and validation library
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		// set validation rules
		$this->form_validation->set_rules('username', 'Username', 'trim|required|alpha_numeric|min_length[4]|is_unique[cuenta.username]', array('is_unique' => 'Este nombre de usuario ya existe. Por favor elige otro.', 'min_length' => 'El campo Nombre Usuario debe tener 4 o mas caracteres', 'required' => 'El campo Nombre Usuario es obligatorio.'));


		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[cuenta.email]', array('is_unique' => 'El campo correo electrónico ya existe. Por favor elige otro.', 'required' => 'El campo Correo Electrónico es obligatorio.'));
		$this->form_validation->set_rules('email_confirm', 'Confirmar Email', 'trim|required|valid_email|matches[email]', array('required' => 'El campo Confirmar Correo Electrónico no coincide con el campo Correo Electrónico.'));


		$this->form_validation->set_rules('password', 'Contraseña', 'trim|required|min_length[6]', array('min_length' => 'El campo Contraseña debe tener seis o más caracteres.', 'required' => 'El campo Contraseña es obligatorio.'));
		$this->form_validation->set_rules('password_confirm', 'Confirm Password', 'trim|required|matches[password]', array('matches' => 'El campo Confirmar Contraseña no coincide con la Contraseña.', 'required' => 'El campo Confirmar Contraseña es obligatorio'));
		
		if ($this->form_validation->run() === false) {
			
			// validation not ok, send validation errors to the view
			$this->load->view('template/header');
			$this->load->view('admin/v_register', $data);
			$this->load->view('template/footer');
			
		} else {
			
			// set variables from the form
			$username = $this->input->post('username');
			$email    = $this->input->post('email');
			$password = $this->input->post('password');
			
			if ($this->Login_model->create_user($username, $email, $password)) {
				$this->session->set_flashdata('flash', 'La cuenta ha sido creada con éxito');
				redirect('login/register','refresh');

				// $data->success = 'La cuenta ha sido creada con éxito.';				
				// $this->load->view('template/header');
				// $this->load->view('admin/v_register', $data);
				// $this->load->view('template/footer');
				
			} else {
				
				// user creation failed, this should never happen
				$data->error = 'Hubo un problema al crear la cuenta nueva. Por favor intenta otra vez.';
				
				// send error to the view
				$this->load->view('template/header');
				$this->load->view('admin/v_register', $data);
				$this->load->view('template/footer');
				
			}
			
		}
		
	}

	// public function inicio()
	// {
	// 	if ($_SESSION['is_type_user'] == 'is_admin') {
			
	// 		// $this->load->view('template/header');
	// 		// $this->load->view('v_principal');
	// 		// $this->load->view('template/footer');
	// 		redirect(base_url() . 'alumno', 'refresh');
	// 	}
	// 	if ($_SESSION['is_type_user'] == 'is_alumno') {
	// 		redirect(base_url() . 'alumno', 'refresh');
	// 	}
	// 	if ($_SESSION['is_type_user'] == 'is_teacher') {
	// 		redirect(base_url() . 'teacher', 'refresh');
	// 	}
	// 	if ($_SESSION['is_type_user'] == 'is_docente') {
	// 		redirect(base_url() . 'teacher', 'refresh');
	// 	}
	// 	if ($_SESSION['is_type_user'] == 'is_secretaria') {
	// 		redirect(base_url() . 'admin/secretaria', 'refresh');
	// 	}

	// }


	public function validate()
    {
    	$this->form_validation->set_error_delimiters('','');
    	$rules = getLoginRules();
    	$this->form_validation->set_rules($rules);
    	if ($this->form_validation->run() === FALSE) {
    		$data = array();
			$data['error_string'] = array();
			$data['inputerror'] = array();
			$data['status'] = TRUE;

			if(form_error('email') != '')
			{
				$data['inputerror'][] = 'email';
				$data['error_string'][] = form_error('email');
				$data['status'] = FALSE;
			}

			if(form_error('password') != '')
			{
				$data['inputerror'][] = 'password';
				$data['error_string'][] = form_error('password');
				$data['status'] = FALSE;
			}

			if($data['status'] === FALSE)
			{
				echo json_encode($data);
				exit();
			}
    	}
    }
}
