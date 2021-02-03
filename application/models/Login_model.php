<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User_model class.
 * 
 * @extends CI_Model
 */
class Login_model extends CI_Model {

	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() {
		
		parent::__construct();
		$this->load->database();
		
	}
	
	/**
	 * create_user function.
	 * 
	 * @access public
	 * @param mixed $username
	 * @param mixed $email
	 * @param mixed $password
	 * @return bool true on success, false on failure
	 */
	public function create_user($username, $email, $password) {
		
		$data = array(
			'username'   => $username,
			'email'      => $email,
			'password'   => $this->hash_password($password),
			'created_at' => date('Y-m-j H:i:s'),
			'is_type_user' => 'is_secretary',
		);
		
		return $this->db->insert('cuenta', $data);
		
	}

	public function crear_usuario($persona)
	{
        $dataCuenta = array(
            'username' => strtolower(substr($persona->nombres, 0, 1)).strtolower($persona->apellido_paterno).strtolower($persona->apellido_materno),
            'email' => strtolower($persona->email),
            'password' => $this->hash_password($persona->ci),
            'avatar' => 'default.jpg',
            'created_at' => date('Y-m-j H:i:s'),
            'updated_at' => "2020-20-12",
            'is_type_user' => 'is_alumno',
            'is_confirmed' => 'S',
            'is_deleted' => 'N',
        );
        $this->db->insert('cuenta', $dataCuenta);
        return $this->db->insert_id();
	}

	public function crear_usuario_all($persona)
	{
        $dataCuenta = array(
            'username' => strtolower(substr($persona->nombres, 0, 1)).strtolower($persona->apellido_paterno).strtolower($persona->apellido_materno),
            'email' => strtolower($persona->email),
            'password' => $this->hash_password($persona->ci),
            'avatar' => 'default.jpg',
            'created_at' => "2020-20-12",
            'updated_at' => "2020-20-12",
            'is_type_user' => 'is_alumno',
            'is_confirmed' => 'S',
            'is_deleted' => 'N',
        );
        $this->db->insert('cuenta', $dataCuenta);
        return $this->db->insert_id();
	}
	
	/**
	 * resolve_user_login function.
	 * 
	 * @access public
	 * @param mixed $username
	 * @param mixed $password
	 * @return bool true on success, false on failure
	 */
	public function resolve_user_login($username, $password) {
		
		$this->db->select('password');
		$this->db->from('cuenta');
		$this->db->where('email', $username);
		$hash = $this->db->get()->row('password');
		
		return $this->verify_password_hash($password, $hash);
		
	}
	
	/**
	 * get_user_id_from_username function.
	 * 
	 * @access public
	 * @param mixed $username
	 * @return int the user id
	 */
	public function get_user_id_from_username($username) {
		
		$this->db->select('id_cuenta');
		$this->db->from('cuenta');
		$this->db->where('email', $username);

		return $this->db->get()->row('id_cuenta');
		
	}
	
	/**
	 * get_user function.
	 * 
	 * @access public
	 * @param mixed $user_id
	 * @return object the user object
	 */
	public function get_user($user_id) {
		
		$this->db->from('cuenta');
		$this->db->join('personal', 'personal.id_cuenta = cuenta.id_cuenta');
		$this->db->join('rol', 'rol.id_rol = personal.id_rol');
		$this->db->where('cuenta.id_cuenta', $user_id);
		return $this->db->get()->row();
		
	}
	
	/**
	 * hash_password function.
	 * 
	 * @access private
	 * @param mixed $password
	 * @return string|bool could be a string on success, or bool false on failure
	 */
	public function hash_password($password) {
		
		return password_hash($password, PASSWORD_BCRYPT);
		
	}
	
	/**
	 * verify_password_hash function.
	 * 
	 * @access private
	 * @param mixed $password
	 * @param mixed $hash
	 * @return bool
	 */
	private function verify_password_hash($password, $hash) {
		
		return password_verify($password, $hash);
		
	}

    //------------------get usuario para session------------------///
	public function get_user_id_from_cuenta($id) {
		
		$this->db->select('id_persona');
		$this->db->from('personal');
		$this->db->where('id_cuenta', $id);

		return $this->db->get()->row('id_persona');
		
	}
	public function get_user_nombre_completo($id_persona) {
		
		$this->db->select('id_persona, nombres, apellido_paterno, apellido_materno');
		$this->db->from('persona');
		$this->db->where('id_persona', $id_persona);
		return $this->db->get()->row();
		
	}
//------------------fin get usuario para session------------------///


	
}
