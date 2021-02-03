<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile_model extends CI_Model {

	var $columnDocente = array('nombres', 'apellido_paterno', 'ci', 'expedido', 'nombre_cargo', 'email');
	var $orderDocente = array('nombres' => 'asc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	///////////////////////////////// INSERTAR DOCENTE ////////////////////////////////
	// public function insertar_cuenta($data)
	// {
	// 	$this->db->insert('cuenta', $data);
	// 	return $this->db->insert_id();
	// }
	// public function insertar_docente($data)
	// {
	// 	$this->db->insert('docente', $data);
	// 	// return $this->db->insert_id();
	// }

	// public function insertar_estudiante($data)
	// {
	// 	$this->db->insert('alumno', $data);
	// 	// return $this->db->insert_id();
	// }

	// public function insertar_administrativo($data)
	// {
	// 	$this->db->insert('administrativo', $data);
	// 	// return $this->db->insert_id();
	// }

	// public function insertar_persona($data)
	// {
	// 	$this->db->insert('persona', $data);
	// 	return $this->db->insert_id();
	// }


	public function save_person($type_user, $dataCuenta, $dataPersona, $dataPersonal, $type_user_personal)
	{
		$this->db->trans_start();
			$dataCuenta['is_type_user'] = $type_user;
			$this->db->insert('cuenta', $dataCuenta);
			$id_cuenta = $this->db->insert_id();

			$this->db->insert('persona', $dataPersona);
			$id_persona = $this->db->insert_id();

			$dataPersonal['id_cuenta'] = $id_cuenta;
			$dataPersonal['id_persona'] = $id_persona;
			$this->db->insert('personal', $dataPersonal);
			$id_personal = $this->db->insert_id();

			$dataTypePersonal = array(
	            'id_personal' => $id_personal,
	        );
	        if ($type_user_personal == 'is_administrativo') {
            	$this->db->insert('administrativo', $dataTypePersonal);
	        } elseif ($type_user_personal == 'is_estudiantil') {
	            $this->db->insert('alumno', $dataTypePersonal);
	        } elseif ($type_user_personal == 'is_docente') {
	            $this->db->insert('docente', $dataTypePersonal);
	        }
		$this->db->trans_complete();
		return  ($this->db->trans_status() === FALSE) ? FALSE : TRUE;
	}

	public function update_person($guardar_usuario, $id_cuenta, $id_persona, $dataCuenta, $data, $dataPersonal)
	{
		$this->db->trans_start();
			if ($guardar_usuario) {
				$this->db->update('cuenta', $dataCuenta, array('id_cuenta' => $id_cuenta ));
			}
			$this->db->update('persona', $data, array('id_persona' => $id_persona ));
			$this->db->update('personal', $dataPersonal, array('id_cuenta' => $id_cuenta, 'id_persona' => $id_persona));
		$this->db->trans_complete();
		return  ($this->db->trans_status() === FALSE) ? FALSE : TRUE;
	}

	private function _get_datatables_query_docente()
	{
		$this->db->from('v_personal');
		// $this->list_docentes();
		// exit();
		$i = 0;
		foreach ($this->columnDocente as $item) // loop columnDocente 
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND. 
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}
				if(count($this->columnDocente) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$columnDocente[$i] = $item; // set columnDocente array variable to order processing
			$i++;
		}
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($columnDocente[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->orderDocente))
		{
			$orderDocente = $this->orderDocente;
			$this->db->order_by(key($orderDocente), $orderDocente[key($orderDocente)]);
		}
	}

	public function count_all_docente()
	{
		$this->db->from('v_personal');
		// $this->list_docentes();
		return $this->db->count_all_results();
	}

	function get_datatables_docente()
	{
		$this->_get_datatables_query_docente();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered_docente()
	{
		$this->_get_datatables_query_docente();
		$query = $this->db->get();
		return $query->num_rows();
	}

	// edit //
	public function get_docente_by_id($id_persona)
	{

		$this->db->from('v_personal');
		// $this->db->join()
		$this->db->where('id_persona',$id_persona);
		$query = $this->db->get();
		return $query->row();
	}

	public function delete_docente_by_id($id)
	{
		$this->db->where('id_docente', $id);
		$this->db->delete('docente');
	}

	public function get_all_cargos()
	{
		$this->db->from('cargo');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_all_rol()
	{
		$this->db->from('rol');
		$query = $this->db->get();
		return $query->result();
	}
	///////////////////////////////// INSERTAR DOCENTE END ////////////////////////////////

	//////////////////// actualizar contraseña bging ///////////////////////////

	public function get_person($id_cuenta)
	{
		$this->db->select('cuenta.id_cuenta, persona.nombres, persona.apellido_paterno, persona.apellido_materno, persona.ci, persona.expedido, persona.fecha_nacimiento, persona.direccion, persona.celular, cuenta.id_cuenta, cuenta.email, cuenta.email, cargo.nombre_cargo, rol.nombre');
		$this->db->from('personal');
		$this->db->join('cuenta', 'cuenta.id_cuenta = personal.id_cuenta');
		$this->db->join('persona', 'persona.id_persona = personal.id_persona');
		$this->db->join('cargo', 'cargo.id_cargo = personal.id_cargo');
		$this->db->join('rol', 'rol.id_rol = personal.id_rol');
		$this->db->where('cuenta.id_cuenta', $id_cuenta);
		// $this->db->where('asignaciondocente.id_paralelo', $id_paralelo);
		// $this->db->where('pensum.id_plan', $id_plan);

		$query = $this->db->get();
		return $query->row();
	}

	public function update_password($id_cuenta, $dataCuenta)
	{
		$this->db->trans_start(TRUE);
			$this->db->update('cuenta', $dataCuenta, array('id_cuenta' => $id_cuenta ));
		$this->db->trans_complete();
		return  ($this->db->trans_status() === FALSE) ? FALSE : TRUE;
	}

	//////////////////// actualizar contraseña end /////////////////////////////
}