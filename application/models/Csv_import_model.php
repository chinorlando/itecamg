<?php
class Csv_import_model extends CI_Model
{
	var $table = 'persona';
	var $column = array('nombres','apellido_paterno','apellido_materno','ci','expedido','email','sexo','estado_civil','fecha_nacimiento','direccion','celular','telefono_fijo','lugar_trabajo','direccion_trabajo','telefono_trabajo'); //set column field database for order and search
	var $order = array('id_persona' => 'desc'); // default order 

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	private function _get_datatables_query()
	{
		$this->db->from($this->table);
		$i = 0;
		foreach ($this->column as $item) // loop column 
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

				if(count($this->column) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$column[$i] = $item; // set column array variable to order processing
			$i++;
		}
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	function select()
	{
		$this->db->order_by('id_persona', 'DESC');
		$query = $this->db->get('persona');
		return $query;
	}

	public function save_new_person($dataPersona, $dataTutor, $dataInscripcion)
	{
		$this->db->trans_start();
			$this->db->insert('persona', $dataPersona);
			$id_persona = $this->db->insert_id();

			$this->db->insert('tutor', $dataTutor);
			$id_tutor = $this->db->insert_id();

			$dataInscripcion['id_persona'] = $id_persona;
			$dataInscripcion['id_tutor'] = $id_tutor;
			$this->db->insert('inscripcion', $dataInscripcion);

		$this->db->trans_complete();
		// return  ($this->db->trans_status() === FALSE) ? FALSE : TRUE;
	}

	// public function insertar_persona($data)
	// {
	// 	// $this->db->insert_batch('persona', $data);
	// 	$this->db->insert('persona', $data);
	// 	return $this->db->insert_id();
	// }

	// function insert_tutor($data)
	// {
	// 	// $this->db->insert_batch('tutor', $data);
	// 	$this->db->insert('tutor', $data);
	// 	return $this->db->insert_id();
	// }

	function insertar_alumno($data)
	{
		// $this->db->insert_batch('alumno', $data);
		$this->db->insert('alumno', $data);
		return $this->db->insert_id();
	}

	public function get_carrera_by_id($carrera)
	{
		$this->db->select('id_carrera');
		$this->db->from('carrera');
		$this->db->where('nombre_carrera',$carrera);

		$query = $this->db->get();

		return $query->row();
	}

	public function ci_existe($ci)
	{
		$this->db->from('persona');
		$this->db->where('ci',$ci);
		$query = $this->db->get();

		if ($query->row()) {
			return true;
		} else {
			return false;
		}
	}

	// public function insertar_inscripcion($data)
	// {
	// 	$this->db->insert('inscripcion', $data);
	// }
}
