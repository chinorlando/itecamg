<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Persona_model extends CI_Model {

	var $table = 'persona';
	var $column = array('nombres','apellido_paterno','apellido_materno','ci','expedido','email','sexo','estado_civil','fecha_nacimiento','direccion','celular','telefono_fijo','lugar_trabajo','direccion_trabajo','telefono_trabajo');
	var $order = array('id_persona' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function esoasdfasdfa()
	{
		$ultimo_gestionperiodo = $this->Admin_model->ultimogestion()->id_gestionperiodo;
		$this->db->select('persona.id_persona, persona.nombres, persona.apellido_paterno, persona.apellido_materno, persona.ci, persona.email, persona.fecha_nacimiento');
		$this->db->from('persona');
		$this->db->join('personal', 'personal.id_persona = persona.id_persona');
		$this->db->join('alumno', 'alumno.id_personal = personal.id_personal');
		$this->db->join('matricula', 'matricula.id_alumno = alumno.id_alumno');
		$this->db->join('gestionperiodo', 'gestionperiodo.id_gestionperiodo = matricula.id_gestionperiodo');
		// $this->db->join('inscripcion', 'inscripcion.id_persona = persona.id_persona');
		$this->db->where('gestionperiodo.id_gestionperiodo', $ultimo_gestionperiodo);
		// $this->db->where('inscripcion.confirmado', 'N');
		$query = $this->db->get();
		return $query->result();
		// $list = $query->result();

		// foreach ($list as $person) {
		// 	$data = array('confirmado' => 'S', );
		// 	$this->db->update('inscripcion', $data, array('id_persona' => $person->id_persona));
		// }
	}

	private function _get_datatables_query()
	{
		// $ultimo_gestionperiodo = $this->Admin_model->ultimogestion()->id_gestionperiodo;
		$gestion = $this->Admin_model->ultimogestion()->gestion;

		// $this->db->select('persona.id_persona, persona.nombres, persona.apellido_paterno, persona.apellido_materno, persona.ci, persona.email, persona.fecha_nacimiento');
		$this->db->from('inscripcion');
		$this->db->join('persona', 'persona.id_persona = inscripcion.id_persona');
		$this->db->join('tutor', 'tutor.id_tutor = inscripcion.id_tutor');
		$this->db->where('YEAR(fecha_preinscripcion)', $gestion);







		// $i = 0;
	
		// foreach ($this->column as $item) 
		// {
		// 	if($_POST['search']['value'])
		// 		($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
		// 	$column[$i] = $item;
		// 	$i++;
		// }
		
		// if(isset($_POST['order']))
		// {
		// 	$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		// } 
		// else if(isset($this->order))
		// {
		// 	$order = $this->order;
		// 	$this->db->order_by(key($order), $order[key($order)]);
		// }
	}

	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function get_persona_by_id($id_persona)
	{
		$this->db->from($this->table);
		$this->db->where('id_persona',$id_persona);
		$query = $this->db->get();

		return $query->row();
	}

	public function save($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$this->db->where('id', $id);
		$this->db->delete($this->table);
	}


}
