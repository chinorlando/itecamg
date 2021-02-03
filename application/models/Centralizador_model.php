<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Centralizador_model extends CI_Model {

	var $table = 'persons';
	var $column = array('firstname','lastname','gender','address','dob'); //set column field database for order and search
	var $order = array('id' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function get_planes()
	{
		$this->db->from('plan');
		$this->db->order_by('id_plan', 'desc');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_anios()
	{
		$this->db->from('gestionperiodo');
		$this->db->order_by('gestion', 'desc');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_carreras()
	{
		$this->db->from('carrera');
		// $this->db->join('pensum', 'pensum.id_carrera = carrera.id_carrera');
		// $this->db->where('pensum.id_plan', $id_plan);
		// $this->db->group_by('nombre_carrera');
		$query = $this->db->get();
		return $query->result();
	}

	public function query_get_paralelos($i, $id_carrera)
	{
		$this->db->select('paralelo.nombre, paralelo.id_paralelo');
		$this->db->from('paralelo');
		$this->db->join('asignaciondocente', 'asignaciondocente.id_paralelo = paralelo.id_paralelo');
		$this->db->join('pensum', 'pensum.id_pensum = asignaciondocente.id_pensum');
		$this->db->join('carrera', 'carrera.id_carrera = pensum.id_carrera');
		$this->db->where('carrera.id_carrera', $id_carrera);
		$this->db->group_by('paralelo.nombre');
		$this->db->like('nombre', $i, 'after');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_materias($id_carrera, $id_paralelo, $id_plan)
	{
		// $this->db->select('materia.id_materia, materia.nombre as nombre_materia');
		// $this->db->from('paralelo');
		// $this->db->join('asignacionparalelo', 'asignacionparalelo.id_paralelo = paralelo.id_paralelo');
		// $this->db->join('materia', 'materia.id_materia  = asignacionparalelo.id_materia');
		// $this->db->join('materia_mension', 'materia_mension.id_materia = materia.id_materia');
		// $this->db->join('pensum', 'pensum.id_materia_mension = materia_mension.id_materia_mension');
		// $this->db->where('paralelo.id_paralelo', $id_paralelo);
		// $this->db->where('pensum.id_carrera', $id_carrera);
		// $this->db->where('pensum.id_plan', $id_plan);

		// $this->db->select('id_carrera, materia.id_materia, materia.nombre as nombre_materia, materia.sigla, 
		// 	asignaciondocente.id_paralelo, 
		// 	concat(persona.apellido_paterno, " ", persona.apellido_materno, ",", persona.nombres) as nombre_completo');
		// $this->select('id_carrera, materia.id_materia, materia.nombre as nombre_materia, materia.sigla, asignaciondocente.id_paralelo, persona.nombres, persona.apellido_paterno, persona.apellido_materno');
		$this->db->select('materia.id_materia, materia.sigla, materia.nombre as nombre_materia, concat(persona.apellido_paterno, " ", persona.apellido_materno, ",", persona.nombres) as nombre_completo');
		$this->db->from('pensum');
		$this->db->join('materia_mension', 'materia_mension.id_materia_mension = pensum.id_materia_mension');
		$this->db->join('materia', 'materia.id_materia = materia_mension.id_materia');
		$this->db->join('asignaciondocente', 'asignaciondocente.id_pensum = pensum.id_pensum');
		$this->db->join('docente', 'docente.id_docente = asignaciondocente.id_docente');
		$this->db->join('personal', 'personal.id_personal = docente.id_personal');
		$this->db->join('persona', 'persona.id_persona = personal.id_persona');
		$this->db->where('id_carrera', $id_carrera);
		$this->db->where('asignaciondocente.id_paralelo', $id_paralelo);
		$this->db->where('pensum.id_plan', $id_plan);
		$this->db->order_by('materia.id_materia', 'asc');

		$query = $this->db->get();
		return $query->result();
	}

	public function ultimogestion()
	{
		$this->db->from('gestionperiodo');
		$this->db->where('estado', 'S');
		$query = $this->db->get();
		return $query->row();
	}

	public function get_alumnos_cent($id_carrera, $id_paralelo, $id_plan, $id_ultimo_gestionperiodo)
	{
		// $id_ultimo_gestionperiodo = $this->ultimogestion()->id_gestionperiodo;

		$this->db->select('alumno.id_alumno, concat(persona.apellido_paterno, " ",persona.apellido_materno, " ", persona.nombres) as nombre_completo, persona.ci');
		$this->db->from('alumno');
		$this->db->join('programacion', 'programacion.id_alumno = alumno.id_alumno');
		$this->db->join('asignacionparalelo', 'asignacionparalelo.id_asignacionparalelo = programacion.id_asignacionparalelo');
		$this->db->join('materia', 'materia.id_materia = asignacionparalelo.id_materia');
		$this->db->join('materia_mension', 'materia_mension.id_materia = materia.id_materia');
		$this->db->join('pensum', 'pensum.id_materia_mension = materia_mension.id_materia_mension');
		$this->db->join('personal', 'personal.id_personal = alumno.id_personal');
		$this->db->join('persona', 'persona.id_persona = personal.id_persona');
		$this->db->where('pensum.id_plan', $id_plan);
		$this->db->where('pensum.id_carrera', $id_carrera);
		$this->db->where('asignacionparalelo.id_paralelo', $id_paralelo);
		$this->db->where('programacion.id_gestionperiodo', $id_ultimo_gestionperiodo);
		$this->db->group_by('alumno.id_alumno');
		$this->db->order_by('nombre_completo', 'asc');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_notas_cent($id_plan, $id_carrera, $id_paralelo, $id_materia, $id_alumno, $bimestre, $id_ultimo_gestionperiodo)
	{
		// $id_ultimo_gestionperiodo = $this->ultimogestion()->id_gestionperiodo;
		$this->db->select('nota.'.$bimestre);
		$this->db->from('nota');
		$this->db->join('programacion', 'programacion.id_programacion = nota.id_programacion');
		$this->db->join('asignacionparalelo', 'asignacionparalelo.id_asignacionparalelo = programacion.id_asignacionparalelo');
		$this->db->join('materia', 'materia.id_materia = asignacionparalelo.id_materia');
		$this->db->join('materia_mension', 'materia_mension.id_materia = materia.id_materia');
		$this->db->join('pensum', 'pensum.id_materia_mension = materia_mension.id_materia_mension');
		$this->db->join('alumno', 'alumno.id_alumno = programacion.id_alumno');
		$this->db->where('pensum.id_plan', $id_plan);
		$this->db->where('pensum.id_carrera', $id_carrera);
		$this->db->where('asignacionparalelo.id_paralelo', $id_paralelo);
		$this->db->where('materia.id_materia', $id_materia);
		$this->db->where('programacion.id_gestionperiodo', $id_ultimo_gestionperiodo);
		$this->db->where('alumno.id_alumno', $id_alumno);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_estadistica_cent($id_plan, $id_carrera, $id_paralelo, $id_materia, $bimestre, $id_ultimo_gestionperiodo)
	{
		// $id_ultimo_gestionperiodo = $this->ultimogestion()->id_gestionperiodo;
		$this->db->select('nota.'.$bimestre);
		$this->db->from('nota');
		$this->db->join('programacion', 'programacion.id_programacion = nota.id_programacion');
		$this->db->join('asignacionparalelo', 'asignacionparalelo.id_asignacionparalelo = programacion.id_asignacionparalelo');
		$this->db->join('materia', 'materia.id_materia = asignacionparalelo.id_materia');
		$this->db->join('materia_mension', 'materia_mension.id_materia = materia.id_materia');
		$this->db->join('pensum', 'pensum.id_materia_mension = materia_mension.id_materia_mension');
		$this->db->join('alumno', 'alumno.id_alumno = programacion.id_alumno');
		$this->db->where('pensum.id_plan', $id_plan);
		$this->db->where('pensum.id_carrera', $id_carrera);
		$this->db->where('asignacionparalelo.id_paralelo', $id_paralelo);
		$this->db->where('materia.id_materia', $id_materia);
		$this->db->where('programacion.id_gestionperiodo', $id_ultimo_gestionperiodo);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_paralelo($id_paralelo)
	{
		$this->db->select('nombre');
		$this->db->from('paralelo');
		$this->db->where('id_paralelo', $id_paralelo);
		$query = $this->db->get();
		return $query->row();
	}
	public function get_carrera($id_carrera)
	{
		$this->db->select('nombre_carrera');
		$this->db->from('carrera');
		$this->db->where('id_carrera', $id_carrera);
		$query = $this->db->get();
		return $query->row();
	}
		
}
