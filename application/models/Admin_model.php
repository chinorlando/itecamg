<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {

	var $table = 'v_alumnos';
	var $column = array('nombres', 'apellido_paterno', 'apellido_materno', 'ci', 'expedido', 'email', 'sexo', 'fecha_nacimiento', 'direccion', 'celular', 'lugar_trabajo'); //set column field database for order and search
	var $order = array('id_alumno' => 'desc'); // default order 

	

	// datos de la tabla paralelo
	var $columnSubjects = array('plan.nombre','carrera.nombre_carrera','materia.sigla', 'materia.nivel_curso', 'materia.nombre');
	var $orderSubjects = array('materia.nombre' => 'desc');

	// datos de la tabla docente
	var $columnTeacherSubjects = array('plan.nombre', 'carrera.nombre_carrera', 'materia.sigla', 'materia.nombre', 'materia.nivel_curso', 'paralelo.nombre', 'plan.nombre');
	var $orderTeacherSubjects = array('plan.nombre' => 'desc');

	// gestion periodo
	var $columnGestionPeriodo = array('gestion', 'periodo', 'nombre', 'estado');
	var $orderGestionPeriodo = array('gestion' => 'desc');

	// gestion periodo
	var $columnStudents = array('alumno.id_alumno', 'persona.nombres', 'persona.apellido_paterno', 'persona.apellido_materno', 'persona.ci', 'persona.expedido', 'carrera.nombre_carrera');
	var $orderStudents = array('persona.apellido_paterno' => 'asc');

	// docentes que han subido sus notas
	var $columnUploadnotes = array('persona.nombres', 'persona.apellido_paterno', 'persona.apellido_materno', 'materia.nombre as nombre_materia', 'materia.sigla', 'paralelo.nombre as nombre_paralelo');
	var $orderUploadnotes = array('persona.apellido_paterno' => 'asc');

	// lista de materias con docentes para habilitar bimestres
	var $columnActiveBimestre = array('materia.sigla', 'materia.nombre', 'carrera.nombre_carrera', 'persona.nombres', 'persona.apellido_paterno', 'persona.apellido_materno', 'nivel_curso', 'paralelo.nombre', 'paralelo.id_paralelo');
	// materia.id_materia, pensum.id_pensum, 'materia.sigla', 'materia.nombre', 'carrera.nombre_carrera', 'persona.nombres', 'persona.apellido_paterno', 'persona.apellido_materno', 'nivel_curso', 'paralelo.nombre', 'paralelo.id_paralelo'

	var $orderActiveBimestre = array('materia.sigla' => 'asc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
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

	//////////////////////////
	public function get_alumno_by_ci($ci)
	{
		$this->db->from('persona');
		$this->db->where('ci',$ci);
		$query = $this->db->get();
		return $query->row();
	}
	public function v_get_alumno($id)
	{
		$this->db->from('v_alumno_all');
		$this->db->where('ci',$id);
		$query = $this->db->get();

		return $query->row();
	}
	//////////////////////////

	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->from('v_alumno_all');
		$this->db->where('id_alumno',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function insertar_persona($data)
	{
		$this->db->insert('persona', $data);
		return $this->db->insert_id();
	}

	function insertar_tutor($data)
	{
		$this->db->insert('tutor', $data);
		return $this->db->insert_id();
	}

	function insertar_alumno($data)
	{
		// $this->db->insert_batch('alumno', $data);
		$this->db->insert('alumno', $data);
		return $this->db->insert_id();
	}

	public function updatePersona($where, $data)
	{
		$this->db->update('persona', $data, $where);
		return $this->db->affected_rows();
	}

	public function updateTutor($where, $data)
	{
		$this->db->update('tutor', $data, $where);
		return $this->db->affected_rows();
	}
	public function updateAlumno($where, $data)
	{
		$this->db->update('alumno', $data, $where);
		return $this->db->affected_rows();
	}

	public function get_carrera()
    {
    	$this->db->from('carrera');
    	$query = $this->db->get();
        return $query->result();
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

	public function get_alumno_preinscrito($id_persona)
	{
		$this->db->from('inscripcion');
		$this->db->where('id_persona',$id_persona);
    	$query = $this->db->get();
		return $query->row();
	}

	public function insertar_personal($data)
	{
		$this->db->insert('personal', $data);
		return $this->db->insert_id();
	}

	// public function insertar_alumno($data)
	// {
	// 	$this->db->insert('alumno', $data);
	// }

	public function updateinscripcion($where, $data)
	{
		$this->db->update('inscripcion', $data, $where);
		return $this->db->affected_rows();
	}

	public function esta_confirmado($id_persona)
	{
		$this->db->from('inscripcion');
		// $this->db->join('alumno', 'alumno.id_inscripcion = inscripcion.id_inscripcion');
		$this->db->where('id_persona',$id_persona);
		$query = $this->db->get();
		return $query->row();
	}

	public function alumnos_sin_programarse()
	{
		$id_ultimo_gestionperiodo = $this->ultimogestion()->id_gestionperiodo;
		$this->db->select('persona.id_persona, alumno.id_alumno, persona.nombres, persona.apellido_paterno, 
			persona.apellido_materno, persona.ci, persona.expedido, persona.fecha_nacimiento, persona.email');
		$this->db->from('alumno');
		$this->db->join('personal', 'personal.id_personal = alumno.id_personal');
		$this->db->join('persona', 'persona.id_persona = personal.id_persona');
		$this->db->join('inscripcion', 'inscripcion.id_persona = persona.id_persona');
		$this->db->join('programacion', 'programacion.id_alumno = alumno.id_alumno', 'left');
		$this->db->join('matricula', 'matricula.id_alumno = alumno.id_alumno');
		$this->db->join('gestionperiodo', 'gestionperiodo.id_gestionperiodo = matricula.id_gestionperiodo');
		$this->db->where('inscripcion.confirmado', 'S');
		$this->db->where('gestionperiodo.id_gestionperiodo', $id_ultimo_gestionperiodo);
        $this->db->where('programacion.id_alumno', NULL);
	}

	public function get_all_new_alumno()
	{
		$this->_get_datatables_query_alumnos();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	public function count_all_alumnos()
	{
		$this->alumnos_sin_programarse();
		return $this->db->count_all_results();
	}

	function count_filtered_alumnos()
	{
		$this->_get_datatables_query_alumnos();
		$query = $this->db->get();
		return $query->num_rows();
	}
	private function _get_datatables_query_alumnos()
	{
        $this->alumnos_sin_programarse();
		// $this->db->from($this->table);
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

	public function ultimo_curso()
	{
		$this->db->from('curso');
		$this->db->order_by('id_curso', 'desc');
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->row();
	}

	public function ultimogestion()
	{
		$this->db->from('gestionperiodo');
		$this->db->where('estado', 'S');
		$query = $this->db->get();
		return $query->row();
	}

	public function materias($id_alumno, $id_plan)
	{

		$this->db->select('materia.id_materia');
		$this->db->from('inscripcion');
		$this->db->join('alumno', 'alumno.id_inscripcion = inscripcion.id_inscripcion');
		$this->db->join('pensum', 'pensum.id_carrera = inscripcion.id_carrera');
		$this->db->join('materia_mension', 'materia_mension.id_materia_mension = pensum.id_materia_mension');
		$this->db->join('materia', 'materia.id_materia = materia_mension.id_materia');
		$this->db->where('alumno.id_alumno', $id_alumno);
		$this->db->where('pensum.id_plan', $id_plan);
		$this->db->where('materia.nivel_curso', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function plan_ultimo()
	{
		$this->db->from('plan');
		$this->db->order_by('id_plan', 'desc');
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->row();
	}

	public function save_programacion($dataAsignacionParalelo, $dataProgramacionMateria)
	{
		$this->db->trans_start();
			$query = $this->db->get_where(
				'asignacionparalelo', array(
					'id_paralelo' => $dataAsignacionParalelo['id_paralelo'], 
					'id_materia' => $dataAsignacionParalelo['id_materia'], 
					'estado' => 'S'))->row();

			if (empty($query)) {
                $this->db->insert('asignacionparalelo', $dataAsignacionParalelo);
				$id_asignacionparalelo = $this->db->insert_id();

				$dataProgramacionMateria['id_asignacionparalelo'] = $id_asignacionparalelo;
				$this->db->insert('programacion', $dataProgramacionMateria);
            } else {
                $id_asignacionparalelo = $query->id_asignacionparalelo;
                
                $dataProgramacionMateria['id_asignacionparalelo'] = $id_asignacionparalelo;
				$this->db->insert('programacion', $dataProgramacionMateria);
            }

		$this->db->trans_complete();
		return  ($this->db->trans_status() === FALSE) ? FALSE : TRUE;
	}

	public function get_alumno_by_id($id)
	{
		$this->db->select('id_alumno');
		$this->db->from('alumno');
		$this->db->join('personal', 'personal.id_personal = alumno.id_personal');
		$this->db->join('persona', 'persona.id_persona = personal.id_persona');
		$this->db->where('persona.id_persona',$id);
		$query = $this->db->get();
		return $query->row();
	}

	public function insertar_matricula($data)
	{
		$this->db->insert('matricula', $data);
	}

	public function cant_alumnos_programados_paralelo($id_alumno, $id_gestionperiodo ,$id_paralelo)
	{
		$sql = 'select (count(distinct(programacion.id_alumno))) as cantidad from alumno 
		join inscripcion on inscripcion.id_inscripcion = alumno.id_inscripcion
		join carrera on carrera.id_carrera = inscripcion.id_carrera
		join pensum on pensum.id_carrera = carrera.id_carrera
		JOIN materia_mension on materia_mension.id_materia_mension = pensum.id_materia_mension
		join materia on materia.id_materia = materia_mension.id_materia
		JOIN asignacionparalelo on asignacionparalelo.id_materia = materia.id_materia
		join programacion on programacion.id_asignacionparalelo = asignacionparalelo.id_materia
		where alumno.id_alumno = ? and programacion.id_gestionperiodo = ? and asignacionparalelo.id_paralelo = ?';
		$query = $this->db->query($sql, array($id_alumno, $id_gestionperiodo, $id_paralelo)); 
		return $query->row()->cantidad;
	}

	

	//////////////////// añadir menus a docentes begin /////////////////////////////////
	public function get_menu(){
        $this->db->select("p.nombre as principal,m.id_menu,m.nombre,m.enlace,m.icono,m.padre");
		$this->db->from("menu m,menu p");
		$this->db->where("m.padre = p.id_menu");
		$this->db->order_by('p.nombre');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_menu_item($id_menu,$id_persona_cargo){
        $this->db->from('menu_personal');
        $this->db->where('id_menu',$id_menu);
        $this->db->where('id_personal',$id_persona_cargo);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function get_personal($id_persona)
    {
    	$this->db->select('id_personal');
    	$this->db->from('personal');
		$this->db->where('id_persona',$id_persona);
		$query = $this->db->get();
		return $query->row();
    }

    public function delete_menu_usuario($id_persona){
        $this->db->where('id_personal',$id_persona);
        $this->db->delete('menu_personal');
    }
    public function insertar_menu_usuario($datos){
        $this->db->insert('menu_personal',$datos);
    }
    //////////////////// añadir menus a docentes end /////////////////////////////////

    //////////////////// asignacion de paralelos end /////////////////////////////////
    public function get_datatables_subjects()
    {
    	$this->_get_datatables_query_subjects();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
    }

    private function _get_datatables_query_subjects()
	{
		// $this->db->from($this->table);
		$this->list_subjects();
		$i = 0;
		foreach ($this->columnSubjects as $item) // loop columnSubjects 
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
				if(count($this->columnSubjects) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$columnSubjects[$i] = $item; // set columnSubjects array variable to order processing
			$i++;
		}
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($columnSubjects[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->orderSubjects))
		{
			$orderSubjects = $this->orderSubjects;
			$this->db->order_by(key($orderSubjects), $orderSubjects[key($orderSubjects)]);
		}
	}

	public function count_all_subjects()
	{
		// $this->db->from($this->table);
		$this->list_subjects();
		return $this->db->count_all_results();
	}
	function count_filtered_subjects()
	{
		$this->_get_datatables_query_subjects();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function list_subjects()
	{
		$this->db->select('plan.nombre as nombre_plan, materia.id_materia, nombre_carrera, sigla, nivel_curso, materia.nombre as nombre_materia');
    	$this->db->from('materia');
    	$this->db->join('materia_mension', 'materia_mension.id_materia = materia.id_materia');
    	$this->db->join('pensum', 'pensum.id_materia_mension = materia_mension.id_materia_mension');
    	$this->db->join('carrera', 'carrera.id_carrera = pensum.id_carrera');
    	$this->db->join('plan', 'plan.id_plan = pensum.id_plan');
		// $this->db->where('plan.nombre', 2);
		// $this->db->where('carrera.id_carrera', 3);
    	// $this->db->order_by('nombre_carrera');
    	// $this->db->order_by('nivel_curso');
	}

	public function get_parallels($nivel_curso)
	{
		$this->db->select('nombre, id_paralelo');
		$this->db->from('paralelo');
		$this->db->like('nombre', $nivel_curso, 'after');
		$query = $this->db->get();
		return $query->result();
	}

	public function delete_parallels($id_materia){
        $this->db->where('id_materia',$id_materia);
        $this->db->delete('asignacionparalelo');
    }
    public function insertar_parallels($datos){
        $this->db->insert('asignacionparalelo',$datos);
    }

    // // para actualizar los estados de los paralelos
    // public function update_paralelos($where, $data)
    // {
    // 	$this->db->update('asignacionparalelo', $data, $where);
    // }
    //////////////////// asignacion de paralelos end /////////////////////////////////

    //////////////////// asignacion de docentes begin ///////////////////////////////
    public function get_datatables_teacher_subjects()
    {
    	$this->_get_datatables_query_teacher_subjects();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
    }

    private function _get_datatables_query_teacher_subjects()
	{
		// $this->db->from($this->table);
		$this->list_teacher_subjects();
		$i = 0;
		foreach ($this->columnTeacherSubjects as $item) // loop columnTeacherSubjects 
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
				if(count($this->columnTeacherSubjects) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$columnTeacherSubjects[$i] = $item; // set columnTeacherSubjects array variable to order processing
			$i++;
		}
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($columnTeacherSubjects[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->orderTeacherSubjects))
		{
			$orderTeacherSubjects = $this->orderTeacherSubjects;
			$this->db->order_by(key($orderTeacherSubjects), $orderTeacherSubjects[key($orderTeacherSubjects)]);
		}
	}

	public function count_all_teacher_subjects()
	{
		$this->list_teacher_subjects();
		return $this->db->count_all_results();
	}
	function count_filtered_teacher_subjects()
	{
		$this->_get_datatables_query_teacher_subjects();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function list_teacher_subjects()
	{
		$this->db->select('materia.id_materia, plan.nombre as nombre_plan, pensum.id_pensum, carrera.nombre_carrera, materia.sigla, materia.nombre as nombre_materia, nivel_curso, paralelo.nombre as nombre_paralelo, paralelo.id_paralelo');
		$this->db->from('pensum');
		$this->db->join('materia_mension', 'materia_mension.id_materia_mension = pensum.id_materia_mension');
		$this->db->join('carrera', 'carrera.id_carrera = pensum.id_carrera');
		$this->db->join('materia', 'materia.id_materia = materia_mension.id_materia');
		$this->db->join('asignacionparalelo','asignacionparalelo.id_materia = materia.id_materia');
		$this->db->join('paralelo','paralelo.id_paralelo = asignacionparalelo.id_paralelo');
		$this->db->join('plan', 'plan.id_plan = pensum.id_plan');
		$this->db->where('asignacionparalelo.estado', 'S');
		// $this->db->where('plan.nombre', 1);
		// $this->db->where('carrera.id_carrera', 3);
		// $this->db->order_by('carrera.nombre_carrera');
		// $this->db->order_by('materia.nivel_curso');
		// $this->db->order_by('paralelo.id_paralelo');
	}

	public function list_docentes_asign($id_materia, $id_paralelo)
	{
		$this->db->from('pensum');
		$this->db->join('materia_mension', 'materia_mension.id_materia_mension = pensum.id_materia_mension');
		$this->db->join('materia', 'materia.id_materia = materia_mension.id_materia');
		$this->db->join('asignaciondocente', 'asignaciondocente.id_pensum = pensum.id_pensum');
		$this->db->join('docente', 'docente.id_docente = asignaciondocente.id_docente');
		$this->db->join('personal', 'personal.id_personal = docente.id_personal');
		$this->db->join('persona', 'persona.id_persona = personal.id_persona');
		$this->db->where('materia.id_materia', $id_materia);
		$this->db->where('asignaciondocente.id_paralelo', $id_paralelo);
		$query = $this->db->get();
		return $query->result();
		// where materia.id_materia = 25 and asignaciondocente.id_paralelo = 1;
	}

	public function list_all_docentes()
	{
		$this->db->select('docente.id_docente, persona.id_persona, apellido_materno, apellido_paterno, nombres');
		$this->db->from('docente');
		$this->db->join('personal','personal.id_personal = docente.id_personal');
		$this->db->join('persona', 'persona.id_persona = personal.id_persona');
		$query = $this->db->get();
		return $query->result();
	}
	public function save_asignacion_docente($data)
	{
		$this->db->insert('asignaciondocente', $data);
		return $this->db->insert_id();
	}

	public function update_asignacion_docente($where, $data)
	{
		$this->db->update('asignaciondocente', $data, $where);
		return $this->db->affected_rows();
	}

    //////////////////// asignacion de docentes end /////////////////////////////////

	//////////////////// asignacion gestionperido begin ///////////////////////////////
	public function get_datatables_gestionperiodo()
    {
    	$this->_get_datatables_query_gestionperiodo();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
    }

    private function _get_datatables_query_gestionperiodo()
	{
		// $this->db->from($this->table);
		$this->list_gestionperido();
		$i = 0;
		foreach ($this->columnGestionPeriodo as $item) // loop columnGestionPeriodo 
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
				if(count($this->columnGestionPeriodo) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$columnGestionPeriodo[$i] = $item; // set columnGestionPeriodo array variable to order processing
			$i++;
		}
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($columnGestionPeriodo[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->orderGestionPeriodo))
		{
			$orderGestionPeriodo = $this->orderGestionPeriodo;
			$this->db->order_by(key($orderGestionPeriodo), $orderGestionPeriodo[key($orderGestionPeriodo)]);
		}
	}

	public function count_all_gestionperiodo()
	{
		$this->list_gestionperido();
		return $this->db->count_all_results();
	}
	function count_filtered_gestionperiodo()
	{
		$this->_get_datatables_query_gestionperiodo();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function list_gestionperido()
	{
		// $this->db->select('materia.id_materia, plan.nombre as nombre_plan, pensum.id_pensum, carrera.nombre_carrera, materia.sigla, materia.nombre as nombre_materia, nivel_curso, paralelo.nombre as nombre_paralelo, paralelo.id_paralelo');
		$this->db->from('gestionperiodo');
		$this->db->join('tipogestion', 'tipogestion.id_tipogestion = gestionperiodo.id_tipogestion');
		// $this->db->join('materia', 'materia.id_materia = pensum.id_materia');
		// $this->db->join('asignacionparalelo','asignacionparalelo.id_materia = materia.id_materia');
		// $this->db->join('paralelo','paralelo.id_paralelo = asignacionparalelo.id_paralelo');
		// $this->db->join('plan', 'plan.id_plan = pensum.id_plan');
		// $this->db->where('asignacionparalelo.estado', 'S');
		// $this->db->where('plan.nombre', 1);
		// $this->db->where('carrera.id_carrera', 3);
		// $this->db->order_by('carrera.nombre_carrera');
		// $this->db->order_by('materia.nivel_curso');
		// $this->db->order_by('paralelo.id_paralelo');
	}

	public function update_all($data)
	{
		$this->db->update('gestionperiodo', $data);
	}

	public function update_one($where, $data)
	{
		$this->db->update('gestionperiodo', $data, $where);
	}

	public function save_gestionperiodo($data)
	{
		$this->db->insert('gestionperiodo', $data);
	}

	public function tipogestion()
	{
		$this->db->from('tipogestion');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_by_id_gestionperiodo($id_gestionperiodo)
	{
		$this->db->from('gestionperiodo');
		$this->db->where('id_gestionperiodo',$id_gestionperiodo);
		$query = $this->db->get();

		return $query->row();
	}

	public function update_gestionperiodo($where, $data)
	{
		$this->db->update('gestionperiodo', $data, $where);
	}

	//////////////////// asignacion gestionperido end /////////////////////////////////




	//////////// activar desactivar las columnas de las notas begin //////////////////
	public function get_bimestre_by_bimestre()
	{
		$this->db->from('activebim');
		$this->db->order_by('id_activebim', 'asc');
		$query = $this->db->get();
		return $query->result();
	}

	public function update($where, $data)
	{
		$this->db->update('activebim', $data, $where);
		return $this->db->affected_rows();
	}
	//////////// activar desactivar las columnas de las notas end ////////////////////

	/////////////////////////////// boletines begin //////////////////////////////////
	public function query_get_alumnos_notes()
	{
		$ultimo_gestionperiodo = $this->ultimogestion()->id_gestionperiodo;
		$this->db->select('alumno.id_alumno, persona.nombres, persona.apellido_paterno, persona.apellido_materno, persona.ci, persona.expedido, carrera.nombre_carrera');
		$this->db->from('alumno');
		$this->db->join('personal', 'personal.id_personal = alumno.id_personal');
		$this->db->join('persona', 'persona.id_persona = personal.id_persona');
		$this->db->join('inscripcion', 'inscripcion.id_inscripcion = alumno.id_inscripcion');
		$this->db->join('carrera', 'carrera.id_carrera = inscripcion.id_carrera');
	}

	function get_datatables_students()
	{
		$this->_get_datatables_query_students();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}
	private function _get_datatables_query_students()
	{
		$this->query_get_alumnos_notes();

		$i = 0;
	
		foreach ($this->columnStudents as $item) 
		{
			if($_POST['search']['value'])
				($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
			$columnStudents[$i] = $item;
			$i++;
		}
		
		if(isset($_POST['order']))
		{
			$this->db->order_by($columnStudents[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->orderStudents))
		{
			$orderStudents = $this->orderStudents;
			$this->db->order_by(key($orderStudents), $orderStudents[key($orderStudents)]);
		}
	}

	function count_filtered_students()
	{
		$this->_get_datatables_query_students();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all_students()
	{
		$this->query_get_alumnos_notes();
		// $this->db->from($this->table);
		return $this->db->count_all_results();
	}

	// public function get_boletin_alumno($id_gestionperiodo, $id_alumno)
	// {
	// 	$this->db->select('materia.sigla, materia.id_materia,materia.nombre as nombre_materia, materia.nivel_curso, nota.primer_bim, nota.segundo_bim, nota.tercer_bim, nota.cuarto_bim, nota.final, nota.segundo_turno, paralelo.nombre as nombre_paralelo,paralelo.id_paralelo, carrera.nombre_carrera,carrera.id_carrera, persona.ci,persona.nombres, persona.apellido_paterno, persona.apellido_materno,pensum.id_plan');
	// 	$this->db->from('nota');
	// 	$this->db->join('programacion', 'programacion.id_programacion = nota.id_programacion');
	// 	$this->db->join('alumno', 'alumno.id_alumno = programacion.id_alumno');
	// 	$this->db->join('personal', 'personal.id_personal = alumno.id_personal');
	// 	$this->db->join('persona', 'persona.id_persona = personal.id_persona');
	// 	$this->db->join('asignacionparalelo', 'asignacionparalelo.id_asignacionparalelo = programacion.id_asignacionparalelo');
	// 	$this->db->join('materia', 'materia.id_materia = asignacionparalelo.id_materia');
	// 	$this->db->join('paralelo', 'paralelo.id_paralelo = asignacionparalelo.id_paralelo')
	// 	;
	// 	$this->db->join('materia_mension', 'materia_mension.id_materia = materia.id_materia');
	// 	$this->db->join('pensum', 'pensum.id_materia_mension = materia_mension.id_materia_mension');
	// 	$this->db->join('carrera', 'carrera.id_carrera = pensum.id_carrera');
	// 	$this->db->where('alumno.id_alumno', $id_alumno);
	// 	$this->db->where('programacion.id_gestionperiodo', $id_gestionperiodo);
	// 	$this->db->group_by('materia.id_materia');
	// 	$this->db->order_by('materia.id_materia', 'asc');
	// 	$query = $this->db->get();
	// 	return $query->result();
	// }
	
	public function get_materias($id_carrera, $id_paralelo, $id_plan,$id_materia)
	{
		$this->db->select('concat(persona.apellido_paterno, " ", persona.apellido_materno, ",", persona.nombres) as nombre_completo');
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
		$this->db->where('materia.id_materia', $id_materia);
		$query = $this->db->get();
		return $query->row()->nombre_completo;
	}
	/////////////////////////////// boletines end ////////////////////////////////////

	////////////////////////// centralizador begin /////////////////////////////////
	public function query_get_paralelos($i, $id_carrera)
	{
		// $this->db->from('paralelo');
		// $this->db->like('nombre', $count_curso, 'after');


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

	public function get_carreras()
	{
		// $this->db->select('materia.sigla, materia.nombre, materia.nivel_curso, nota.primer_bim, nota.segundo_bim, nota.tercer_bim, nota.cuarto_bim, nota.final, nota.segundo_turno, paralelo.nombre as nombre_paralelo, carrera.nombre_carrera');
		$this->db->from('carrera');
		$query = $this->db->get();
		return $query->result();
	}

	///////////////////// estadistica begin ////////////////

	public function get_materia_by_all($id_carrera, $nivel_curso, $id_paralelo)
	{
		$this->db->select('id_carrera, materia.id_materia, materia.nombre as nombre_materia, materia.sigla, 
			asignaciondocente.id_paralelo, 
			concat(persona.apellido_paterno, " ", persona.apellido_materno, ",", persona.nombres) as nombre_completo');
		$this->db->from('pensum');
		$this->db->join('materia_mension', 'materia_mension.id_materia_mension = pensum.id_materia_mension');
		$this->db->join('materia', 'materia.id_materia = materia_mension.id_materia');
		$this->db->join('asignaciondocente', 'asignaciondocente.id_pensum = pensum.id_pensum');
		$this->db->join('docente', 'docente.id_docente = asignaciondocente.id_docente');
		$this->db->join('personal', 'personal.id_personal = docente.id_personal');
		$this->db->join('persona', 'persona.id_persona = personal.id_persona');
		$this->db->where('id_carrera', $id_carrera);
		$this->db->where('materia.nivel_curso', $nivel_curso);
		$this->db->where('asignaciondocente.id_paralelo', $id_paralelo);
		$this->db->order_by('id_materia',"desc");
		$query = $this->db->get();
		return $query->result();
	}

	public function get_students($id_carrera, $id_materia, $id_paralelo)
	{
		$ultimo_gestionperiodo = $this->ultimogestion()->id_gestionperiodo;
		$this->db->select('alumno.id_alumno, persona.nombres, persona.apellido_paterno, persona.apellido_materno');
		$this->db->from('programacion');
		$this->db->join('asignacionparalelo', 'asignacionparalelo.id_asignacionparalelo = programacion.id_asignacionparalelo');
		$this->db->join('alumno', 'alumno.id_alumno = programacion.id_alumno');
		$this->db->join('inscripcion', 'inscripcion.id_inscripcion = alumno.id_inscripcion');
		$this->db->join('personal', 'personal.id_personal = alumno.id_personal');
		$this->db->join('persona', 'persona.id_persona = personal.id_persona');
		$this->db->where('inscripcion.id_carrera', $id_carrera);
		$this->db->where('asignacionparalelo.id_materia', $id_materia);
		$this->db->where('asignacionparalelo.id_paralelo', $id_paralelo);
		$this->db->where('programacion.id_gestionperiodo', $ultimo_gestionperiodo);
		$this->db->order_by('persona.apellido_paterno', 'asc');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_note($id_alumno, $id_materia,$id_paralelo)
	{
		$ultimo_gestionperiodo = $this->ultimogestion()->id_gestionperiodo;
		$this->db->select('primer_bim, segundo_bim, tercer_bim, cuarto_bim, final, segundo_turno');
		$this->db->from('nota');
		$this->db->join('programacion', 'programacion.id_programacion = nota.id_programacion');
		$this->db->join('asignacionparalelo', 'asignacionparalelo.id_asignacionparalelo = programacion.id_asignacionparalelo');
		$this->db->where('id_alumno', $id_alumno);
		$this->db->where('asignacionparalelo.id_materia', $id_materia);
		$this->db->where('asignacionparalelo.id_paralelo', $id_paralelo);
		$this->db->where('id_gestionperiodo', $ultimo_gestionperiodo);
		$query = $this->db->get();
		return $query->row();
	}

	// public function get_alumnos_by_materias($id_carrera, $id_paralelo, $id_materia)
	// {
	// 	$ultimo_gestionperiodo = $this->ultimogestion()->id_gestionperiodo;
	// 	$this->db->select('alumno.id_alumno');
	// 	$this->db->from(' programacion');
	// 	$this->db->join('materia', 'materia.id_materia = programacion.id_materia');
	// 	$this->db->join('alumno', 'alumno.id_alumno = programacion.id_alumno');
	// 	$this->db->join('pensum', 'pensum.id_materia = materia.id_materia');
	// 	$this->db->join('carrera', 'carrera.id_carrera = pensum.id_carrera');
	// 	$this->db->join('paralelo', 'paralelo.id_paralelo = programacion.id_paralelo');
	// 	$this->db->where('carrera.id_carrera', $id_carrera);
	// 	$this->db->where('paralelo.id_paralelo', $id_paralelo);
	// 	$this->db->where('programacion.id_gestionperiodo', $ultimo_gestionperiodo);
	// 	$this->db->where('materia.id_materia', $id_materia);
	// 	// $this->db->where('materia.nivel_curso', $nivel_curso);
	// 	$this->db->group_by('alumno.id_alumno');
	// 	$query = $this->db->get();
	// 	return $query->result();
	// }

	// public function get_nota_by_materia($id_alumno, $id_paralelo, $id_materia)
	// {
	// 	$ultimo_gestionperiodo = $this->ultimogestion()->id_gestionperiodo;
	// 	// $this->db->select('alumno.id_alumno');
	// 	$this->db->from('nota');
	// 	$this->db->where('id_alumno', $id_alumno);
	// 	$this->db->where('id_gestionperiodo', $ultimo_gestionperiodo);
	// 	$this->db->where('id_paralelo', $id_paralelo);
	// 	$this->db->where('id_materia', $id_materia);
	// 	// $this->db->group_by('alumno.id_alumno');
	// 	$query = $this->db->get();
	// 	return $query->result();
	// }

	////////////////////// estadistica end //////////////

    ////////////////////////// centralizador end ///////////////////////////////////
	
	////////////////////////// backup bigin ///////////////////////////////////
	public function create_backup($type)
	{
		date_default_timezone_set('America/La_Paz');
        $this->load->dbutil();
        $prefs = array(     
            'format'      => 'zip',             
            'filename'    => 'my_db_backup.sql'
        );
        $backup =& $this->dbutil->backup($prefs); 
        $db_name = 'backup-on-'. date("Y-m-d-H-i-s") .'.zip';
            $save = FCPATH.'db_backups/'.$db_name;
        $this->load->helper('file');
            write_file($save, $backup); 
        $this->load->helper('download');
            force_download($db_name, $backup); 

		// $this->load->dbutil();

  //       $options = array(
  //           'format' => 'txt', // gzip, zip, txt
  //           'add_drop' => TRUE, // Whether to add DROP TABLE statements to backup file
  //           'add_insert' => TRUE, // Whether to add INSERT data to backup file
  //           'newline' => "\n"               // Newline character used in backup file
  //       );


  //       if ($type == 'all') {
  //           $tables = array('');
  //           $file_name = 'db_iteca_'.date("Y-m-d-H-i-s");
  //       } else {
  //           $tables = array('tables' => array($type));
  //           $file_name = 'backup_' . $type;
  //       }

  //       $backup = & $this->dbutil->backup(array_merge($options, $tables));
  //       write_file(FCPATH.'db_backups/'.$file_name . '.sql', $backup);

        // $this->load->helper('download');
        // force_download($file_name . '.sql', $backup);
	}

	// public function create_backup($fileName='db_backup.zip')
	// {
	// 	$this->load->dbutil();

	//     // Backup your entire database and assign it to a variable
	//     $backup =& $this->dbutil->backup();

	//     // Load the file helper and write the file to your server
	//     $this->load->helper('file');
	//     write_file(FCPATH.'/db_backups/'.$fileName, $backup);

	//     // Load the download helper and send the file to your desktop
	//     $this->load->helper('download');
	//     force_download($fileName, $backup);
	// }
	////////////////////////// backup end ///////////////////////////////////


	public function get_students_a_r($id_materia, $id_paralelo)
	{
		$ultimo_gestionperiodo = $this->ultimogestion()->id_gestionperiodo;
		$this->db->select('alumno.id_alumno, persona.nombres, persona.apellido_paterno, persona.apellido_materno');
		$this->db->from('programacion');
		$this->db->join('alumno', 'alumno.id_alumno = programacion.id_alumno');
		$this->db->join('inscripcion', 'inscripcion.id_inscripcion = alumno.id_inscripcion');
		$this->db->join('personal', 'personal.id_personal = alumno.id_personal');
		$this->db->join('persona', 'persona.id_persona = personal.id_persona');
		$this->db->join('asignacionparalelo', 'asignacionparalelo.id_asignacionparalelo = programacion.id_asignacionparalelo');
		// $this->db->where('inscripcion.id_carrera', $id_carrera);
		$this->db->where('asignacionparalelo.id_materia', $id_materia);
		$this->db->where('asignacionparalelo.id_paralelo', $id_paralelo);
		$this->db->where('programacion.id_gestionperiodo', $ultimo_gestionperiodo);
		$this->db->order_by('persona.apellido_paterno', 'asc');
		$query = $this->db->get();
		return $query->result();
	}

	////////////////////////// retirados begin ///////////////////////////////////
	public function get_alumnos_r($sexo, $efectivo)
	{
		$ultimo_gestionperiodo = $this->ultimogestion()->id_gestionperiodo;
		$this->db->select('alumno.id_alumno');
		$this->db->from('programacion');
		$this->db->join('alumno', 'alumno.id_alumno = programacion.id_alumno');
		$this->db->join('personal', 'personal.id_personal = alumno.id_personal');
		$this->db->join('persona', 'persona.id_persona = personal.id_persona');
		$this->db->where('programacion.id_gestionperiodo', $ultimo_gestionperiodo);
		$this->db->where('persona.sexo', $sexo);
		$this->db->order_by('alumno.id_alumno', 'asc');
		$this->db->group_by('persona.id_persona');
		$query = $this->db->get();
		if ($efectivo) {
			return $query->num_rows();
		} else {
			return $query->result();
		}
	}

	public function notas_retirados($id_alumno, $bimestre)
	{
		$ultimo_gestionperiodo = $this->ultimogestion()->id_gestionperiodo;
		$this->db->select('programacion.id_alumno, nota.'.$bimestre);
		$this->db->from('programacion');
		$this->db->join('nota', 'nota.id_programacion = programacion.id_programacion');
		$this->db->where('programacion.id_alumno', $id_alumno);
		$this->db->where('programacion.id_gestionperiodo', $ultimo_gestionperiodo);
		$query = $this->db->get();
		return $query->result();
	}
	////////////////////////// retirados end /////////////////////////////////////

	////////////////////////// alumnos efectivos begin /////////////////////////////////
	public function get_alumnos_all()
	{
		$id_ultimo_gestionperiodo = $this->ultimogestion()->id_gestionperiodo;
		$this->db->select('alumno.id_alumno, persona.sexo');
		$this->db->from('programacion');
		$this->db->join('alumno', 'alumno.id_alumno = programacion.id_alumno');
		$this->db->join('personal', 'personal.id_personal = alumno.id_personal');
		$this->db->join('persona', 'persona.id_persona = personal.id_persona');
		$this->db->where('programacion.id_gestionperiodo', $id_ultimo_gestionperiodo);
		// $this->db->where('persona.sexo', $sexo);
		$this->db->order_by('alumno.id_alumno', 'asc');
		$this->db->group_by('persona.id_persona');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_nota_with_sexo($id_plan, $id_carrera, $id_paralelo, $id_materia, $bimestre)
	{
		$id_ultimo_gestionperiodo = $this->ultimogestion()->id_gestionperiodo;
		$this->db->select('persona.sexo, nota.'.$bimestre);
		$this->db->from('nota');
		$this->db->join('programacion', 'programacion.id_programacion = nota.id_programacion');
		$this->db->join('asignacionparalelo', 'asignacionparalelo.id_asignacionparalelo = programacion.id_asignacionparalelo');
		$this->db->join('materia', 'materia.id_materia = asignacionparalelo.id_materia');
		$this->db->join('materia_mension', 'materia_mension.id_materia = materia.id_materia');
		$this->db->join('pensum', 'pensum.id_materia_mension = materia_mension.id_materia_mension');
		$this->db->join('alumno', 'alumno.id_alumno = programacion.id_alumno');
		$this->db->join('personal', 'personal.id_personal = alumno.id_personal');
		$this->db->join('persona', 'persona.id_persona = personal.id_persona');
		$this->db->where('pensum.id_plan', $id_plan);
		$this->db->where('pensum.id_carrera', $id_carrera);
		$this->db->where('asignacionparalelo.id_paralelo', $id_paralelo);
		$this->db->where('materia.id_materia', $id_materia);
		$this->db->where('programacion.id_gestionperiodo', $id_ultimo_gestionperiodo);
		$query = $this->db->get();
		return $query->result();
	}
	////////////////////////// alumnos efectivos end ///////////////////////////////////

	////////////////////////// ver que docente ha subido alguna nota begin   ///////////////////////////////////
    public function get_teacher_upload_note()
    {
    	$this->db->select('materia.id_materia, asignaciondocente.id_paralelo, persona.nombres, persona.apellido_paterno, persona.apellido_materno, materia.nombre as nombre_materia, materia.sigla, paralelo.nombre as nombre_paralelo');
    	// $this->db->select('persona.nombres, persona.apellido_paterno, persona.apellido_materno, materia.nombre as nombre_materia, materia.sigla, paralelo.nombre as nombre_paralelo');
		$this->db->from('docente');
		$this->db->join('personal', 'personal.id_personal = docente.id_personal');
		$this->db->join('persona', 'persona.id_persona = personal.id_persona');
		$this->db->join('asignaciondocente', 'asignaciondocente.id_docente = docente.id_docente');
		$this->db->join('pensum', 'pensum.id_pensum = asignaciondocente.id_pensum');
		$this->db->join('materia_mension', 'materia_mension.id_materia_mension = pensum.id_materia_mension');
		$this->db->join('materia', 'materia.id_materia = materia_mension.id_materia');
		$this->db->join('paralelo', 'paralelo.id_paralelo = asignaciondocente.id_paralelo');
		$this->db->order_by('persona.apellido_paterno', 'asc');
		$this->db->order_by('persona.apellido_materno', 'asc');
		$this->db->order_by('nombre_materia', 'asc');
		$this->db->order_by('nombre_paralelo', 'asc');
		// $this->db->join('asignacionparalelo', 'asignacionparalelo.id_materia = materia.id_materia');
		// $query = $this->db->get();
		// return $query->result();
		// $this->db->join('programacion', 'programacion.id_programacion = nota.id_programacion');
		// $this->db->join('alumno', 'alumno.id_alumno = programacion.id_alumno');
		// $this->db->join('carrera', 'carrera.id_carrera = pensum.id_carrera');
    }

    function get_datatables_upload_notes()
	{
		$this->_get_datatables_query_upload_notes();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}
	private function _get_datatables_query_upload_notes()
	{
		$this->get_teacher_upload_note();

		$i = 0;
	
		foreach ($this->columnUploadnotes as $item) 
		{
			if($_POST['search']['value'])
				($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
			$columnUploadnotes[$i] = $item;
			$i++;
		}
		
		if(isset($_POST['order']))
		{
			$this->db->order_by($columnUploadnotes[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->orderUploadnotes))
		{
			$orderUploadnotes = $this->orderUploadnotes;
			$this->db->order_by(key($orderUploadnotes), $orderUploadnotes[key($orderUploadnotes)]);
		}
	}

	function count_filtered_upload_notes()
	{
		$this->_get_datatables_query_upload_notes();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all_upload_notes()
	{
		$this->get_teacher_upload_note();
		// $this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function get_cantidad($id_materia, $id_paralelo)
	{
		$id_ultimo_gestionperiodo = $this->ultimogestion()->id_gestionperiodo;
		// $this->db->select('materia.id_materia, asignaciondocente.id_paralelo, persona.nombres, persona.apellido_paterno, persona.apellido_materno, materia.nombre as nombre_materia, materia.sigla, paralelo.nombre as nombre_paralelo');
		$this->db->from('asignacionparalelo');
		$this->db->join('programacion', 'programacion.id_asignacionparalelo = asignacionparalelo.id_asignacionparalelo');
		$this->db->join('nota', 'nota.id_programacion = programacion.id_programacion');
		$this->db->where('programacion.id_gestionperiodo', $id_ultimo_gestionperiodo);
		$this->db->where('id_materia', $id_materia);
		$this->db->where('id_paralelo', $id_paralelo);
		$query = $this->db->get();
		return $query->result();
	}
    ////////////////////////// ver que docente ha subido alguna nota end   /////////////////////////////////////

    /////////////// activar materia por docente begin  /////////////////////////
    public function get_teacher_list_teacher_subjets_active_bimestre()
    {
    	$this->db->select('asignaciondocente.id_asignaciondocente, materia.id_materia, pensum.id_pensum, materia.sigla, materia.nombre as nombre_materia, carrera.nombre_carrera, persona.nombres, persona.apellido_paterno, persona.apellido_materno, nivel_curso, paralelo.nombre as nombre_paralelo, paralelo.id_paralelo');
    	$this->db->from('asignaciondocente');
		$this->db->join('pensum', 'pensum.id_materia_mension = asignaciondocente.id_pensum');
		$this->db->join('materia_mension', 'materia_mension.id_materia_mension = pensum.id_materia_mension');
		$this->db->join('materia', 'materia.id_materia = materia_mension.id_materia');
		$this->db->join('paralelo', 'paralelo.id_paralelo = asignaciondocente.id_paralelo');
		$this->db->join('carrera', 'carrera.id_carrera = pensum.id_carrera');
		$this->db->join('docente', 'docente.id_docente = asignaciondocente.id_docente');
		$this->db->join('personal', 'personal.id_personal = docente.id_personal');
		$this->db->join('persona', 'persona.id_persona = personal.id_persona');
		$this->db->join('habilitarbimestre', 'habilitarbimestre.id_asignaciondocente = asignaciondocente.id_asignaciondocente');
		$this->db->order_by('habilitarbimestre.estado', 'desc');
    }

    function get_datatables_list_teacher_subjects_active_bimestre()
	{
		$this->_get_datatables_query_list_teacher_subjects_active_bimestre();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}
	private function _get_datatables_query_list_teacher_subjects_active_bimestre()
	{
		$this->get_teacher_list_teacher_subjets_active_bimestre();

		$i = 0;
	
		foreach ($this->columnActiveBimestre as $item) 
		{
			if($_POST['search']['value'])
				($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
			$columnActiveBimestre[$i] = $item;
			$i++;
		}
		
		if(isset($_POST['order']))
		{
			$this->db->order_by($columnActiveBimestre[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->orderActiveBimestre))
		{
			$orderActiveBimestre = $this->orderActiveBimestre;
			$this->db->order_by(key($orderActiveBimestre), $orderActiveBimestre[key($orderActiveBimestre)]);
		}
	}

	function count_filtered_active_bimestre()
	{
		$this->_get_datatables_query_list_teacher_subjects_active_bimestre();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all_active_bimestre()
	{
		$this->get_teacher_list_teacher_subjets_active_bimestre();
		// $this->db->from($this->table);
		return $this->db->count_all_results();
	}


	public function get_activebim()
	{
		// $this->db->select('alumno.id_alumno');
		$this->db->from('activebim');
		// $this->db->join('personal', 'personal.id_cuenta = cuenta.id_cuenta');
		// $this->db->join('alumno', 'alumno.id_personal = personal.id_personal');
        // $this->db->where('cuenta.id_cuenta', $user_id);
        $query = $this->db->get();
		return $query->result();
	}

	public function save_habilitarbimestre($data)
	{
		$this->db->insert('habilitarbimestre', $data);
	}

	public function update_habilitarbimestre($where, $data)
    {
    	$this->db->update('habilitarbimestre', $data, $where);
    }

	/////////////// activar materia por docente  end   /////////////////////////

	/////////////// programar materias a alumno  begin   /////////////////////////
	var $columnAntiAlumn = array('persona.id_persona', 'alumno.id_alumno', 'persona.nombres', 'persona.apellido_paterno', 'persona.apellido_materno', 'persona.ci', 'persona.expedido', 'persona.fecha_nacimiento', 'persona.email');

	var $orderAntiAlumn = array('persona.id_persona' => 'asc');

	public function get_lista_de_alumnos_antiguos()
    {
    	$id_ultimo_gestionperiodo = $this->ultimogestion()->id_gestionperiodo;
		$this->db->select('DISTINCT(persona.id_persona), alumno.id_alumno, persona.nombres, persona.apellido_paterno, 
			persona.apellido_materno, persona.ci, persona.expedido, persona.fecha_nacimiento, persona.email');
		$this->db->from('alumno');
		$this->db->join('personal', 'personal.id_personal = alumno.id_personal');
		$this->db->join('persona', 'persona.id_persona = personal.id_persona');
		$this->db->join('inscripcion', 'inscripcion.id_persona = persona.id_persona');
		$this->db->join('programacion', 'programacion.id_alumno = alumno.id_alumno', 'left');
		$this->db->join('matricula', 'matricula.id_alumno = alumno.id_alumno');
		$this->db->join('gestionperiodo', 'gestionperiodo.id_gestionperiodo = matricula.id_gestionperiodo');
		$this->db->where('inscripcion.confirmado', 'S');
		$this->db->where('gestionperiodo.id_gestionperiodo', 2);
        // $this->db->where('programacion.id_alumno', NULL);
    }

    function lista_alumnos_antiguos()
	{
		$this->_get_lista_alumnos_antiguos();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}
	private function _get_lista_alumnos_antiguos()
	{
		$this->get_lista_de_alumnos_antiguos();

		$i = 0;
	
		foreach ($this->columnAntiAlumn as $item) 
		{
			if($_POST['search']['value'])
				($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
			$columnAntiAlumn[$i] = $item;
			$i++;
		}
		
		if(isset($_POST['order']))
		{
			$this->db->order_by($columnAntiAlumn[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->orderAntiAlumn))
		{
			$orderAntiAlumn = $this->orderAntiAlumn;
			$this->db->order_by(key($orderAntiAlumn), $orderAntiAlumn[key($orderAntiAlumn)]);
		}
	}

	function count_all_antiguos_alumnos()
	{
		$this->_get_lista_alumnos_antiguos();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_filtered_antiguos_alumnos()
	{
		$this->get_teacher_list_teacher_subjets_active_bimestre();
		// $this->db->from($this->table);
		return $this->db->count_all_results();
	}
	/////////////// programar materias a alumno  end   /////////////////////////

	/////////////// generar_matricula callata  begin /////////////////////////
	public function get_boletin_alumno($id_gestionperiodo, $id_alumno)
	{
		$this->db->select('materia.sigla, materia.id_materia,materia.nombre as nombre_materia, materia.nivel_curso, nota.primer_bim, nota.segundo_bim, nota.tercer_bim, nota.cuarto_bim, nota.final, nota.segundo_turno, paralelo.nombre as nombre_paralelo,paralelo.id_paralelo,
		carrera.nombre_carrera,carrera.id_carrera, persona.ci,persona.nombres, persona.apellido_paterno, persona.apellido_materno,persona.expedido,persona.sexo,persona.email,
		persona.estado_civil,persona.fecha_nacimiento,persona.direccion,persona.celular,persona.telefono_fijo,persona.lugar_trabajo,persona.direccion_trabajo,persona.telefono_trabajo,pensum.id_plan');
		$this->db->from('nota');
		$this->db->join('programacion', 'programacion.id_programacion = nota.id_programacion');
		$this->db->join('alumno', 'alumno.id_alumno = programacion.id_alumno');
		$this->db->join('personal', 'personal.id_personal = alumno.id_personal');
		$this->db->join('persona', 'persona.id_persona = personal.id_persona');
		$this->db->join('asignacionparalelo', 'asignacionparalelo.id_asignacionparalelo = programacion.id_asignacionparalelo');
		$this->db->join('materia', 'materia.id_materia = asignacionparalelo.id_materia');
		$this->db->join('paralelo', 'paralelo.id_paralelo = asignacionparalelo.id_paralelo')
		;
		$this->db->join('materia_mension', 'materia_mension.id_materia = materia.id_materia');
		$this->db->join('pensum', 'pensum.id_materia_mension = materia_mension.id_materia_mension');
		$this->db->join('carrera', 'carrera.id_carrera = pensum.id_carrera');
		$this->db->where('alumno.id_alumno', $id_alumno);
		$this->db->where('programacion.id_gestionperiodo', $id_gestionperiodo);
		$this->db->group_by('materia.id_materia');
		$this->db->order_by('materia.id_materia', 'asc');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_gestion($id_gestionperiodo)
	{
		$this->db->select('gestion');
		$this->db->from('gestionperiodo');
		$this->db->where('id_gestionperiodo', $id_gestionperiodo);
		$query = $this->db->get();
		return $query->row()->gestion;
	}
	/////////////// generar_matricula callata  end   /////////////////////////

	///////////////////////////////revisar 
	// para matriculacion de alumno
	public function ultimo_curso_revisar($id_alumno)
	{
		$this->db->from('matricula');
		$this->db->where('id_alumno', $id_alumno);
		$this->db->order_by('id_curso', 'desc');
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->row();
	}

	public function verifica_matriculacion($id_alumno)
	{
		$id_ultimo_gestionperiodo = $this->ultimogestion()->id_gestionperiodo;
		$this->db->from('matricula');
		$this->db->where('id_alumno',$id_alumno);
		$this->db->where('id_gestionperiodo',$id_ultimo_gestionperiodo);
		$query = $this->db->get();

		if ($query->row()) {
			return true;
		} else {
			return false;
		}
	}

	///////////////////////////////revisar 

	///// boletin de alumno //////
	public function gestion_perido_all($id_alumno)
	{
		$this->db->select('gestionperiodo.id_gestionperiodo, gestion');
		$this->db->from('gestionperiodo');
		$this->db->join('matricula', 'matricula.id_gestionperiodo = gestionperiodo.id_gestionperiodo');
		$this->db->where('matricula.id_alumno', $id_alumno);
		$this->db->order_by('gestion', 'desc');
		$this->db->group_by('gestion');
        $query = $this->db->get();
		return $query->result();
	}
	///// boletin de alumno //////

}


// SELECT persona.nombres, persona.apellido_paterno, persona.apellido_materno, materia.nombre as nombre_materia, materia.sigla, paralelo.nombre as nombre_paralelo from docente
// join personal on personal.id_personal  = docente.id_personal
// join persona on persona.id_persona = personal.id_persona
// JOIN asignaciondocente on asignaciondocente.id_docente = docente.id_docente
// join pensum on pensum.id_pensum = asignaciondocente.id_pensum
// join materia_mension on materia_mension.id_materia_mension = pensum.id_materia_mension
// join materia on materia.id_materia = materia_mension.id_materia
// JOIN asignacionparalelo on asignacionparalelo.id_materia = materia.id_materia
// JOIN paralelo on paralelo.id_paralelo = asignacionparalelo.id_paralelo


// SELECT * from asignacionparalelo
// join programacion on programacion.id_asignacionparalelo = asignacionparalelo.id_asignacionparalelo
// join nota on nota.id_programacion = programacion.id_programacion
// WHERE programacion.id_gestionperiodo = 2 and id_materia = 102 and id_paralelo = 9 ORDER BY `id_paralelo` ASC