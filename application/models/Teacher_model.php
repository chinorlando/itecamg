<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teacher_model extends CI_Model {

	var $table = 'persons';
	var $column = array('firstname','lastname','gender','address','dob');
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	//////////////////////////////// listar carreras materias y grupos///////////////////////////
	public function get_iddocente($user_id)
	{
		$this->db->select('docente.id_docente');
		$this->db->from('cuenta');
		$this->db->join('personal', 'personal.id_cuenta = cuenta.id_cuenta');
		$this->db->join('docente', 'docente.id_personal = personal.id_personal');
		$this->db->where('cuenta.id_cuenta', $user_id);
		$query = $this->db->get();
		return $query->row()->id_docente;
	}

	public function get_carreras($id_docente)
	{
		$this->db->select('carrera.id_carrera, carrera.nombre_carrera');
		$this->db->from('carrera');
		$this->db->join('pensum', 'pensum.id_carrera = carrera.id_carrera');
		$this->db->join('asignaciondocente', 'asignaciondocente.id_pensum = pensum.id_pensum');
		$this->db->where('asignaciondocente.id_docente', $id_docente);
		$this->db->group_by('carrera.nombre_carrera');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_materias($id_docente, $id_carrera)
	{
		$this->db->select('materia.id_materia, materia.nombre, plan.nombre as nombre_plan');
		$this->db->from('asignaciondocente');
		$this->db->join('pensum', 'pensum.id_pensum = asignaciondocente.id_pensum');
		$this->db->join('materia_mension', 'materia_mension.id_materia_mension = pensum.id_materia_mension');
		$this->db->join('materia', 'materia.id_materia = materia_mension.id_materia');
		$this->db->join('plan', 'plan.id_plan = pensum.id_plan');
		$this->db->where('asignaciondocente.id_docente', $id_docente);
		$this->db->where('pensum.id_carrera', $id_carrera);
		$this->db->group_by('materia.id_materia');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_paralelos($id_docente, $id_carrera, $id_materia)
	{
		$this->db->select('paralelo.id_paralelo, paralelo.nombre');
		$this->db->from('paralelo');
		$this->db->join('asignaciondocente', 'asignaciondocente.id_paralelo = paralelo.id_paralelo');
		$this->db->join('pensum', 'pensum.id_pensum = asignaciondocente.id_pensum');
		$this->db->join('materia_mension', 'materia_mension.id_materia_mension = pensum.id_materia_mension');
		$this->db->join('materia', 'materia.id_materia = materia_mension.id_materia');
		$this->db->where('asignaciondocente.id_docente', $id_docente);
		$this->db->where('pensum.id_carrera', $id_carrera);
		$this->db->where('materia.id_materia', $id_materia);
		// $this->db->group_by('carrera.nombre_carrera');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_gestion_actual()
	{
		$this->db->select('id_gestionperiodo');
		$this->db->from('gestionperiodo');
		$this->db->where('estado', 'S');
		// $this->db->order_by('id_gestionperiodo', 'desc');
		// $this->db->limit(1);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_students($id_carrera, $id_materia, $id_paralelo, $id_gestionperiodo)
	{
		$this->db->select('alumno.id_alumno, persona.nombres, persona.apellido_paterno, persona.apellido_materno');
		$this->db->from('programacion');
		$this->db->join('alumno', 'alumno.id_alumno = programacion.id_alumno');
		$this->db->join('inscripcion', 'inscripcion.id_inscripcion = alumno.id_inscripcion');
		$this->db->join('personal', 'personal.id_personal = alumno.id_personal');
		$this->db->join('persona', 'persona.id_persona = personal.id_persona');
		$this->db->join('asignacionparalelo', 'asignacionparalelo.id_asignacionparalelo = programacion.id_asignacionparalelo');
		$this->db->where('inscripcion.id_carrera', $id_carrera);
		$this->db->where('asignacionparalelo.id_materia', $id_materia);
		$this->db->where('asignacionparalelo.id_paralelo', $id_paralelo);
		$this->db->where('programacion.id_gestionperiodo', $id_gestionperiodo);
		$this->db->order_by('persona.apellido_paterno', 'asc');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_note($id_alumno, $id_materia,$id_paralelo, $id_gestionperiodo)
	{
		$this->db->select('primer_bim, segundo_bim, tercer_bim, cuarto_bim, final, segundo_turno');
		$this->db->from('nota');
		$this->db->join('programacion', 'programacion.id_programacion = nota.id_programacion');
		$this->db->join('asignacionparalelo', 'asignacionparalelo.id_asignacionparalelo = programacion.id_asignacionparalelo');
		$this->db->where('id_alumno', $id_alumno);
		$this->db->where('id_materia', $id_materia);
		$this->db->where('id_paralelo', $id_paralelo);
		$this->db->where('id_gestionperiodo', $id_gestionperiodo);
		$query = $this->db->get();
		return $query->row();
	}

	public function notes_update($dataNote, $id_alumno, $id_materia, $id_paralelo)
	{
		$id_gestionperiodo = $this->get_gestion_actual()->id_gestionperiodo;
		$this->db->trans_start();
		$this->db->select('programacion.id_programacion');
		$this->db->from('nota');
		$this->db->join('programacion', 'programacion.id_programacion = nota.id_programacion');
		$this->db->join('asignacionparalelo', 'asignacionparalelo.id_asignacionparalelo = programacion.id_asignacionparalelo');
		$this->db->where('id_alumno', $id_alumno);
		$this->db->where('id_materia', $id_materia);
		$this->db->where('id_paralelo', $id_paralelo);
		$this->db->where('id_gestionperiodo', $id_gestionperiodo);
		$query = $this->db->get();
		$id_programacion = $query->row()->id_programacion;
		$where = array('id_programacion' => $id_programacion);
		$this->db->update('nota', $this->security->xss_clean($dataNote), $where);
		$this->db->trans_complete();

		return  ($this->db->trans_status() === FALSE) ? FALSE : TRUE;
	}

	public function bimestreactivado()
	{
		$this->db->select('estado');
		$this->db->from('activebim');
		$this->db->where('estado', 1);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_habilitar_bimestre($id_carrera, $id_materia, $id_paralelo)
	{
		$this->db->select('habilitarbimestre.bimestre');
		$this->db->from('materia_mension');
		$this->db->join('pensum', 'pensum.id_materia_mension = materia_mension.id_materia_mension');
		$this->db->join('carrera', 'carrera.id_carrera = pensum.id_carrera');
		$this->db->join('asignaciondocente', 'asignaciondocente.id_pensum = pensum.id_pensum');
		$this->db->join('habilitarbimestre', 'habilitarbimestre.id_asignaciondocente = asignaciondocente.id_asignaciondocente');
		$this->db->where('carrera.id_carrera', $id_carrera);
		$this->db->where('materia_mension.id_materia', $id_materia);
		$this->db->where('asignaciondocente.id_paralelo', $id_paralelo);
		$query = $this->db->get();
		return $query->row();
	}
	

	// public function save_note($dataNote, $dataAsignacionparalelo, $dataProgramacion)
	// {
	// 	$estado = $this->bimestreactivado()->estado;
	// 	$id_gestionperiodo = $this->get_gestion_actual()->id_gestionperiodo;
	// 	$data = array(
	// 		'id_persona' => $_SESSION['user_id'],
	// 		'pb' => $estado == 1 ? $estado : 0,
	// 		'sb' => $estado == 2 ? $estado : 0,
	// 		'tb' => $estado == 3 ? $estado : 0,
	// 		'cb' => $estado == 4 ? $estado : 0,
	// 		'fi' => $estado == 5 ? $estado : 0,
	// 		'st' => $estado == 6 ? $estado : 0,
	// 	);

	// 	$this->db->insert($this->table, $data);
		

	// 	// $this->db->trans_start();
	// 	// 	$id_gestionperiodo = $this->get_gestion_actual()->id_gestionperiodo;
	// 	// 	$this->db->insert('asignacionparalelo', $dataAsignacionparalelo);
	// 	// 	$id_asignacionparalelo = $this->db->insert_id();

	// 	// 	$dataProgramacion['id_gestionperiodo'] = $id_gestionperiodo;
	// 	// 	$dataProgramacion['id_asignacionparalelo'] = $id_asignacionparalelo;
	// 	// 	$this->db->insert('programacion', $dataProgramacion);
	// 	// 	$id_programacion = $this->db->insert_id();

	// 	// 	$dataNote['id_programacion'] = $id_programacion;
	// 	// 	$this->db->insert('nota', $dataNote);
	// 	// $this->db->trans_complete();
	// 	// return  ($this->db->trans_status() === FALSE) ? FALSE : TRUE;
	// }
	

	public function save_note($dataNote, $dataAsignacionparalelo, $dataProgramacion)
	{
		$this->db->trans_start();
			$id_gestionperiodo = $this->get_gestion_actual()->id_gestionperiodo;

			$query = $this->db->get_where(
				'asignacionparalelo', array(
					'id_paralelo' => $dataAsignacionparalelo['id_paralelo'], 
					'id_materia' => $dataAsignacionparalelo['id_materia'], 
					'estado' => 'S'))->row();

			$queryPro = $this->db->get_where(
				'programacion', array(
					'id_gestionperiodo' => $id_gestionperiodo, 
					'id_alumno' => $dataProgramacion['id_alumno'],
					'id_asignacionparalelo' => $query->id_asignacionparalelo))->row();

			$dataNote['id_programacion'] = $queryPro->id_programacion;
			$this->db->insert('nota', $dataNote);

		$this->db->trans_complete();
		return  ($this->db->trans_status() === FALSE) ? FALSE : TRUE;
	}

	public function update_notes($where, $data)
	{
		// print_r($data);
		$this->db->update('nota', $data, $where);
		// return $this->db->affected_rows();
	}

	// revisar mio
	// public function if_exist_notes($id_materia,$id_paralelo,$id_gestionperiodo)
	// {
	// 	$this->db->from('nota');
	// 	// $this->db->where('id_alumno',$id_alumno);
	// 	$this->db->join('programacion', 'programacion.id_programacion = nota.id_programacion');
	// 	$this->db->join('asignacionparalelo', 'asignacionparalelo.id_asignacionparalelo = programacion.id_asignacionparalelo');
	// 	$this->db->where('id_materia',$id_materia);
	// 	$this->db->where('id_paralelo',$id_paralelo);
	// 	$this->db->where('id_gestionperiodo',$id_gestionperiodo);
	// 	$query = $this->db->get();
	// 	return $query->num_rows();
	// }

	public function if_exist_notes($id_materia,$id_paralelo,$id_gestionperiodo, $id_alumno)
	{
		$this->db->from('nota');
		// $this->db->where('id_alumno',$id_alumno);
		$this->db->join('programacion', 'programacion.id_programacion = nota.id_programacion');
		$this->db->join('asignacionparalelo', 'asignacionparalelo.id_asignacionparalelo = programacion.id_asignacionparalelo');
		$this->db->where('id_materia',$id_materia);
		$this->db->where('id_paralelo',$id_paralelo);
		$this->db->where('id_gestionperiodo',$id_gestionperiodo);
		$this->db->where('id_alumno',$id_alumno);
		$query = $this->db->get();
		return $query->num_rows();
	}
	//////////////////////////////// listar carreras materias y grupos///////////////////////////

	// mostrar bimestres
	public function get_bimestre_by_bimestre()
	{
		$this->db->from('activebim');
		$this->db->order_by('id_activebim', 'asc');
		$query = $this->db->get();
		return $query->row();
	}
	// mostrar bimestres


	

	private function _get_datatables_query()
	{
		
		$this->db->from($this->table);

		$i = 0;
	
		foreach ($this->column as $item) 
		{
			if($_POST['search']['value'])
				($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
			$column[$i] = $item;
			$i++;
		}
		
		if(isset($_POST['order']))
		{
			$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
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

	public function get_by_id($id)
	{
		$this->db->from($this->table);
		$this->db->where('id',$id);
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

	//-----------------------------reporte lista de alumnos, carrera, materia, paralelo---------------------------///
    public function get_materia_nombre($id_materia)
	{
		$this->db->select('nombre, sigla');
		$this->db->from('materia');
		$this->db->where('id_materia', $id_materia);
		$query = $this->db->get();
		return $query->row();
	}

    public function get_paralelo_nombre($id_paralelo)
	{
		$this->db->select('nombre');
		$this->db->from('paralelo');
		$this->db->where('id_paralelo', $id_paralelo);
		$query = $this->db->get();
		return $query->row();
	}
    public function get_carrera_nombre($id_carrera)
	{
		$this->db->select('nombre_carrera');
		$this->db->from('carrera');
		$this->db->where('id_carrera', $id_carrera);
		$query = $this->db->get();
		return $query->row();
	}
    
	//-----------------------------reporte lista de alumnos  carrera, materia, paralelo---------------------------///
    

}
