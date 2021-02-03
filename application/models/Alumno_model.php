<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alumno_model extends CI_Model {

	// var $table = 'persons';
	var $column = array('firstname','lastname','gender','address','dob');
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function kardex_alumno($user_id)
	{
		print_r("expression");
	}

	private function _get_datatables_query($user_id)
	{
		$this->kardex_alumno($user_id);
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

	function get_datatables($user_id)
	{
		$this->_get_datatables_query($user_id);
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

	public function get_idalumno($user_id)
	{
		$this->db->select('alumno.id_alumno');
		$this->db->from('cuenta');
		$this->db->join('personal', 'personal.id_cuenta = cuenta.id_cuenta');
		$this->db->join('alumno', 'alumno.id_personal = personal.id_personal');
        $this->db->where('cuenta.id_cuenta', $user_id);
        $query = $this->db->get();
		return $query->row()->id_alumno;
	}

	//////////////////////////////////libreta///////////////////////
	public function gestion_perido()
	{
		$this->db->select('gestionperiodo.id_gestionperiodo, gestion');
		$this->db->from('gestionperiodo');
		$this->db->join('matricula', 'matricula.id_gestionperiodo = gestionperiodo.id_gestionperiodo');
		$this->db->order_by('gestion', 'desc');
		$this->db->group_by('gestion');
        $query = $this->db->get();
		return $query->result();
	}

	public function materias($id_alumno, $id_gestionperiodo)
	{
		$this->db->select('materia.sigla, materia.nombre, materia.nivel_curso, paralelo.nombre as nombre_paralelo, materia.id_materia');
		$this->db->from('programacion');
		$this->db->join('gestionperiodo', 'gestionperiodo.id_gestionperiodo = programacion.id_gestionperiodo');
		$this->db->join('asignacionparalelo', 'asignacionparalelo.id_asignacionparalelo = programacion.id_asignacionparalelo');
		$this->db->join('materia', 'materia.id_materia = asignacionparalelo.id_materia');
		$this->db->join('paralelo', 'paralelo.id_paralelo = asignacionparalelo.id_paralelo');
        $this->db->where('gestionperiodo.id_gestionperiodo', $id_gestionperiodo);
        $this->db->where('id_alumno', $id_alumno);
        $query = $this->db->get();
		return $query->result();
	}

	// aqui añadir la relacion materia_mension
	public function get_materias_paralelo($id_alumno, $gestion)
	{
		$this->db->select('a.id_paralelo, paralelo.nombre');
		$this->db->from('materia');
		$this->db->join('asignacionparalelo a', 'a.id_materia = materia.id_materia');
		$this->db->join('programacion', 'programacion.id_asignacionparalelo = a.id_asignacionparalelo');
		$this->db->join('gestionperiodo', 'gestionperiodo.id_gestionperiodo = programacion.id_gestionperiodo');
		$this->db->join('paralelo', 'paralelo.id_paralelo = a.id_paralelo');
        $this->db->where('programacion.id_alumno', $id_alumno);
        $this->db->where('gestionperiodo.gestion', $gestion);
        $query = $this->db->get();
		return $query->result();
	}

	// aqui añadir la relacion materia_mension
	public function notas_por_materia($id_alumno, $id_materia, $id_gestionperiodo)
	{
		$this->db->select('primer_bim, segundo_bim, tercer_bim, cuarto_bim, final, segundo_turno');
		$this->db->from('nota');
		$this->db->join('programacion', 'programacion.id_programacion = nota.id_programacion');
		$this->db->join('asignacionparalelo ap', 'ap.id_asignacionparalelo = programacion.id_asignacionparalelo');
		$this->db->where('id_alumno', $id_alumno);
		$this->db->where('id_materia', $id_materia);
		$this->db->where('id_gestionperiodo', $id_gestionperiodo);
		$query = $this->db->get();
		return $query->row();

	}
	//////////////////////////////////materias por cada curso//////////////////////

	//////////////////////////////////boletin//////////////////////
	public function get_idalumno_by_ci_dob($ci, $dob)
	{
		// $this->db->select();
		$this->db->from('persona p');
		$this->db->join('inscripcion i', 'i.id_persona = p.id_persona');
		$this->db->join('alumno a', 'a.id_inscripcion = i.id_inscripcion');
		$this->db->join('carrera c', 'c.id_carrera = i.id_carrera');
		$this->db->where('p.ci', $ci);
		$this->db->where('p.fecha_nacimiento', $dob);
		$query = $this->db->get();
		return $query->row();

	}
	//////////////////////////////////boletin//////////////////////

	//////////////////////////////////programarme//////////////////////////////////
	public function get_gestion_matricula($id_alumno)
	{
		$this->db->from('matricula');
		$this->db->join('gestionperiodo', 'gestionperiodo.id_gestionperiodo = matricula.id_gestionperiodo');
		$this->db->order_by('matricula.id_gestionperiodo', 'desc');
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->row();
	}

	public function gestioactualnmatricula($id_alumno)
	{
		$this->db->from('matricula');
		$this->db->join('gestionperiodo', 'gestionperiodo.id_gestionperiodo = matricula.id_gestionperiodo');
		$this->db->where('matricula.id_alumno', $id_alumno);
		$this->db->order_by('matricula.id_gestionperiodo', 'desc');
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->row();
	}

	public function programado_noprogramado($id_alumno, $gestion)
	{
		// print_r($id_alumno.'-'.$gestion);
		// exit();
		$this->db->from('programacion');
		$this->db->where('programacion.id_gestionperiodo', $gestion);
		$this->db->where('id_alumno', $id_alumno);
		return $this->db->count_all_results();
	}

	public function get_all_gestion_matricula($id_alumno)
	{
		$sqlconsult = 'select gestion from matricula
			join gestionperiodo on gestionperiodo.id_gestionperiodo = matricula.id_gestionperiodo
			where id_alumno = ?
			and gestion <> (select gestion from matricula
			join gestionperiodo on gestionperiodo.id_gestionperiodo = matricula.id_gestionperiodo
			where id_alumno = ?
			order by id_matricula desc
			limit 1)
			group by gestion
			order by gestion asc';
		$query = $this->db->query($sqlconsult, array($id_alumno, $id_alumno)); 
		return $query->result();
	}

	public function materias_gestion($id_alumno, $gestion)
	{
		$this->db->from('programacion');
		$this->db->join('gestionperiodo','gestionperiodo.id_gestionperiodo = programacion.id_gestionperiodo');
		$this->db->where('id_alumno', $id_alumno);
		$this->db->where('gestionperiodo.gestion', $gestion);
		$query = $this->db->get();
		return $query->result();
	}

	public function nota_materia_gestion($id_materia, $gestion, $id_alumno)
	{
		$this->db->select('sum(nota_obtenida)/count(nota_obtenida) as nota_final');
		$this->db->from('nota');
		$this->db->join('gestionperiodo','gestionperiodo.id_gestionperiodo = nota.id_gestionperiodo');
		$this->db->where('id_materia', $id_materia);
		$this->db->where('id_alumno', $id_alumno);
		$this->db->where('gestionperiodo.gestion', $gestion);
		$query = $this->db->get();
		return $query->row()->nota_final;
	}

	public function materias_anterior_gestion_oficial($id_alumno, $gestion)
	{
		
		$this->db->from('programacion');
		$this->db->join('gestionperiodo','gestionperiodo.id_gestionperiodo = programacion.id_gestionperiodo');
		$this->db->join('asignacionparalelo','asignacionparalelo.id_asignacionparalelo = programacion.id_asignacionparalelo');
		$this->db->join('materia', 'materia.id_materia = asignacionparalelo.id_materia');
		$this->db->join('materia_mension mm', 'mm.id_materia = materia.id_materia');
		$this->db->where('programacion.id_alumno', $id_alumno);
		$this->db->where('gestionperiodo.gestion', $gestion-1);
		$query = $this->db->get();
		return $query->result();
	}

	// esta funcion ya no usaremos ya que usa directamente id_de materia
	public function materias_anterior_gestion($id_alumno, $gestion)
	{
		$this->db->from('programacion');
		$this->db->join('gestionperiodo','gestionperiodo.id_gestionperiodo = programacion.id_gestionperiodo');
		$this->db->join('asignacionparalelo','asignacionparalelo.id_asignacionparalelo = programacion.id_asignacionparalelo');
		$this->db->join('materia', 'materia.id_materia = asignacionparalelo.id_materia');
		$this->db->join('materia_mension mm', 'mm.id_materia = materia.id_materia');
		$this->db->where('programacion.id_alumno', $id_alumno);
		$this->db->where('gestionperiodo.gestion', $gestion-1);
		$query = $this->db->get();
		return $query->result();
	}

	public function obtenertodoslossprerequisitos($id_materia_mension)
	{
		$this->db->from('prerequisito');	
		$this->db->where('id_materia_mension_prerequisito', $id_materia_mension);
		$query = $this->db->get();
		return $query->result();
	}

	public function obternemateriatroncalconprerequisito($id_materia_prerequisito)
	{
		$this->db->from('prerequisito');	
		$this->db->where('id_materia_mension', $id_materia_prerequisito);
		$query = $this->db->get();
		return $query->result();
	}

	// revisar este metodo --> esta sera el metodo funcional // borrar: nota_materia_anterior_gestion()
	public function nota_materia_anterior_gestion_oficial($id_materia, $gestion, $id_alumno)
	{
		$sql = 'SELECT primer_bim, segundo_bim, tercer_bim, cuarto_bim, final, segundo_turno from materia_mension mm 
			join materia m on m.id_materia = mm.id_materia 
			join asignacionparalelo a on a.id_materia = m.id_materia 
			join programacion p on p.id_asignacionparalelo = a.id_asignacionparalelo 
			join gestionperiodo gp on gp.id_gestionperiodo = p.id_gestionperiodo
			join nota n on n.id_programacion = p.id_programacion
			where mm.id_materia_mension = ? and p.id_alumno = ? and gp.gestion = ?';
		$query = $this->db->query($sql, array($id_materia, $id_alumno, $gestion-1));
		return $query->row();
	}

	// revisar este metedo --> se debe eliminar que usa directamente id_materia
	public function nota_materia_anterior_gestion($id_materia, $gestion, $id_alumno)
	{
		// $this->db->select('primer_bim, segundo_bim, tercer_bim, cuarto_bim, final, segundo_turno');
		// $this->db->from('nota');
		// $this->db->join('programacion','programacion.id_programacion = nota.id_programacion');
		// $this->db->join('asignacionparalelo','asignacionparalelo.id_asignacionparalelo = programacion.id_asignacionparalelo');
		// $this->db->join('gestionperiodo','gestionperiodo.id_gestionperiodo = programacion.id_gestionperiodo');
		// $this->db->where('asignacionparalelo.id_materia', $id_materia);
		// $this->db->where('programacion.id_alumno', $id_alumno);
		// $this->db->where('gestionperiodo.gestion', $gestion-1);
		// $query = $this->db->get();
		// return $query->row();

		$sql = 'SELECT primer_bim, segundo_bim, tercer_bim, cuarto_bim, final, segundo_turno from materia_mension mm 
			join materia m on m.id_materia = mm.id_materia 
			join asignacionparalelo a on a.id_materia = m.id_materia 
			join programacion p on p.id_asignacionparalelo = a.id_asignacionparalelo 
			join gestionperiodo gp on gp.id_gestionperiodo = p.id_gestionperiodo
			join nota n on n.id_programacion = p.id_programacion 
			where mm.id_materia_mension = ? and p.id_alumno = ? and gp.gestion = ?';
		$query = $this->db->query($sql, array($id_materia, $id_alumno, $gestion-1));
		return $query->row();
	}

	public function get_materia($id_materia)
	{
		$this->db->from('materia');
		$this->db->where('id_materia', $id_materia);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_paralelo($count_curso)
	{
		$this->db->from('paralelo');
		$this->db->like('nombre', $count_curso, 'after');
		$query = $this->db->get();
		return $query->result();
	}

	public function eliminar_materia_programada_por_idmateria_revisar($id_alumno, $id_gestionperiodo)
	{
		$this->db->trans_start();
			$this->db->where('id_gestionperiodo', $id_gestionperiodo);
			$this->db->where('id_alumno', $id_alumno);
			// $this->db->where('id_asignacionparalelo', $id_asignacionparalelo);
			$this->db->delete('programacion');
		$this->db->trans_complete();
		return  ($this->db->trans_status() === FALSE) ? FALSE : TRUE;
	}

	public function save_programation($dataProgramacion, $dataAsignacionParalelo)
	{
		$this->db->trans_start();
			// $this->db->insert('asignacionparalelo', $dataAsignacionParalelo);
			// $id_asignacionparalelo = $this->db->insert_id();

			// $dataProgramacion['id_asignacionparalelo'] = $id_asignacionparalelo;
			// $this->db->insert('programacion', $dataProgramacion);

			$query = $this->db->get_where(
				'asignacionparalelo', array(
					'id_paralelo' => $dataAsignacionParalelo['id_paralelo'], 
					'id_materia' => $dataAsignacionParalelo['id_materia'], 
					'estado' => 'S'))->row();

			if (empty($query)) {
                $this->db->insert('asignacionparalelo', $dataAsignacionParalelo);
				$id_asignacionparalelo = $this->db->insert_id();

				// $dataProgramacion['id_asignacionparalelo'] = $id_asignacionparalelo;
				// $this->db->insert('programacion', $dataProgramacion);
            } else {
                $id_asignacionparalelo = $query->id_asignacionparalelo;
                
				// $dataProgramacion['id_asignacionparalelo'] = $id_asignacionparalelo;
				// $this->db->insert('programacion', $dataProgramacion);
            }
            $dataProgramacion['id_asignacionparalelo'] = $id_asignacionparalelo;
				$this->db->insert('programacion', $dataProgramacion);

		$this->db->trans_complete();
		return  ($this->db->trans_status() === FALSE) ? FALSE : TRUE;
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

	///////////////////////////programarme end////////////
	////////////////update programation///////////////////
	public function get_materiasprogramadas($id_alumno, $id_gestionperiodo)
	{
		$this->db->from('programacion');
		$this->db->join('asignacionparalelo', 'asignacionparalelo.id_asignacionparalelo = programacion.id_asignacionparalelo');
		$this->db->join('materia', 'materia.id_materia = asignacionparalelo.id_materia');
		$this->db->where('programacion.id_alumno', $id_alumno);
		$this->db->where('programacion.id_gestionperiodo', $id_gestionperiodo);
		$query = $this->db->get();
		return $query->result();
	}

	public function comprobar_si_materiestaprogramada($id_alumno, $id_gestionperiodo, $id_materia)
	{
		$this->db->from('programacion');
		$this->db->join('asignacionparalelo', 'asignacionparalelo.id_asignacionparalelo = programacion.id_asignacionparalelo');
		$this->db->join('materia', 'materia.id_materia = asignacionparalelo.id_materia');
		$this->db->where('programacion.id_alumno', $id_alumno);
		$this->db->where('programacion.id_gestionperiodo', $id_gestionperiodo);
		$this->db->where('materia.id_materia', $id_materia);
		// $this->db->count_all_results();
		if ($this->db->count_all_results()>0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function eliminar_materia_programada_por_idmateria($id_asignacionparalelo, $id_alumno, $id_gestionperiodo, $id_materia)
	{
		$this->db->trans_start();
			$this->db->where('id_gestionperiodo', $id_gestionperiodo);
			$this->db->where('id_alumno', $id_alumno);
			$this->db->where('id_asignacionparalelo', $id_asignacionparalelo);
			$this->db->delete('programacion');

			$this->db->where('id_asignacionparalelo', $id_asignacionparalelo);
			$this->db->where('id_materia', $id_materia);
			$this->db->delete('asignacionparalelo');
		$this->db->trans_complete();
		return  ($this->db->trans_status() === FALSE) ? FALSE : TRUE;
	}

	public function programacionasignacionparalelo($id_alumno, $id_gestionperiodo, $id_materia)
	{
		$this->db->from('programacion');
		$this->db->join('asignacionparalelo', 'asignacionparalelo.id_asignacionparalelo = programacion.id_asignacionparalelo');
		$this->db->join('materia', 'materia.id_materia = asignacionparalelo.id_materia');
		$this->db->where('programacion.id_alumno', $id_alumno);
		$this->db->where('programacion.id_gestionperiodo', $id_gestionperiodo);
		$this->db->where('materia.id_materia', $id_materia);
		$query = $this->db->get();
		return $query->result();
	}

	public function list_paralelos_programacion($id_gestionperiodo, $id_alumno, $id_materia)
	{
		$this->db->from('programacion');
		$this->db->join('asignacionparalelo', 'asignacionparalelo.id_asignacionparalelo = programacion.id_asignacionparalelo');
		$this->db->join('materia', 'materia.id_materia = asignacionparalelo.id_materia');
		$this->db->where('programacion.id_alumno', $id_alumno);
		$this->db->where('programacion.id_gestionperiodo', $id_gestionperiodo);
		$this->db->where('materia.id_materia', $id_materia);
		$query = $this->db->get();
		return $query->result();
	}
	////////////////update programation///////////////////
	////////////////kardex////////////////////////////////
	// public function kardex($id_alumno, $id_gestionperiodo)
	// {
	// 	$this->db->select('gestionperiodo.gestion, plan.nombre, materia.nivel_curso, materia.sigla, materia.nombre as materia_nombre, materia.id_materia, nota.primer_bim, nota.segundo_bim, nota.tercer_bim, nota.cuarto_bim, nota.final, nota.segundo_turno');
	// 	$this->db->from('programacion');
	// 	$this->db->join('materia', 'materia.id_materia = programacion.id_materia');
	// 	$this->db->join('gestionperiodo', 'gestionperiodo.id_gestionperiodo = programacion.id_gestionperiodo');
	// 	$this->db->join('tipogestion', 'tipogestion.id_tipogestion = gestionperiodo.id_tipogestion');
	// 	$this->db->join('nota', 'nota.id_materia = materia.id_materia');
	// 	$this->db->join('pensum', 'pensum.id_materia = materia.id_materia');
	// 	$this->db->join('plan', 'plan.id_plan = pensum.id_plan');
	// 	$this->db->where('programacion.id_alumno',$id_alumno);
	// 	$this->db->where('nota.id_alumno',$id_alumno);
	// 	$this->db->where('gestionperiodo.id_gestionperiodo <>',$id_gestionperiodo);
	// 	$query = $this->db->get();
	// 	return $query->result();
	// }

	public function kardex($id_alumno, $id_gestionperiodo)
	{
		$this->db->select('gp.gestion, plan.nombre, materia.nivel_curso, materia.sigla, materia.nombre as materia_nombre, materia.id_materia, nota.primer_bim, nota.segundo_bim, nota.tercer_bim, nota.cuarto_bim, nota.final, nota.segundo_turno');
		$this->db->from('programacion');
		$this->db->join('alumno', 'alumno.id_alumno = programacion.id_alumno');
		$this->db->join('nota', 'nota.id_programacion = programacion.id_programacion');
		$this->db->join('asignacionparalelo ap', 'ap.id_asignacionparalelo = programacion.id_asignacionparalelo');
		$this->db->join('materia', 'materia.id_materia = ap.id_materia');
		$this->db->join('materia_mension mm', 'mm.id_materia = materia.id_materia');
		$this->db->join('pensum', 'pensum.id_materia_mension = mm.id_materia_mension');
		$this->db->join('plan', 'plan.id_plan = pensum.id_plan');
		$this->db->join('gestionperiodo gp', 'gp.id_gestionperiodo = programacion.id_gestionperiodo');
		$this->db->join('tipogestion tg', 'tg.id_tipogestion = gp.id_tipogestion');
		$this->db->where('programacion.id_alumno',$id_alumno);
		// $this->db->where('nota.id_programacion',$id_alumno);
		$this->db->where('gp.id_gestionperiodo <>',$id_gestionperiodo);
		$query = $this->db->get();
		return $query->result();
	}



// 	from programacion
// join alumno alumno.id_alumno = programacion.id_alumno
// join nota nota.id_programacion = programacion.id_programacion
// join asignacionparalelo ap = ap.id_asignacionparalelo = programacion.id_asignacionparalelo
// join materia materia.id_materia = ap.id_materia
// join materia_mension mm.id_materia = materia_id_materia
// join pensum pensum.id_materia_mension = materia_mension.id_materia_mension
// join plan plan.id_plan = pensum.id_plan
// join gestionperiodo gp.id_gestionperiodo = programacion.id_gestionperiodo
// join tipogestion tg.id_tipogestion = gp.id_tipogestion
	////////////////kardex////////////////////////////////

	////////////////programar alumno admin begin /////////////////////////
	public function get_materiasprogramadas_admin($id_alumno, $gestion)
	{
		$this->db->from('programacion');
		$this->db->join('asignacionparalelo', 'asignacionparalelo.id_asignacionparalelo = programacion.id_asignacionparalelo');
		$this->db->join('materia', 'materia.id_materia = asignacionparalelo.id_materia');
		$this->db->join('gestionperiodo', 'gestionperiodo.id_gestionperiodo = programacion.id_gestionperiodo');
		$this->db->where('programacion.id_alumno', $id_alumno);
		$this->db->where('gestionperiodo.gestion', $gestion);
		$query = $this->db->get();
		return $query->result();
	}

	public function mat_a_progr_previo($id_materia_prerequisito, $eso)
	{
		// $this->db->select('mpre.id_materia_mension');
		// $this->db->from('materia_mension');
		// $this->db->join('prerequisito mpre','mpre.id_materia_mension_prerequisito = materia_mension.id_materia_mension');
		// $this->db->where('mpre.id_materia_mension_prerequisito', $id_materia_prerequisito);
		// $query = $this->db->get();
		// return $query->result();

		$sql = "SELECT mpre.id_materia_mension FROM materia_mension
			JOIN prerequisito mpre on mpre.id_materia_mension_prerequisito = materia_mension.id_materia_mension
			WHERE mpre.id_materia_mension_prerequisito = ? 
			and (SELECT COUNT(*)
				FROM prerequisito p
				WHERE p.id_materia_mension = mpre.id_materia_mension )" .$eso. "1";
		$query = $this->db->query($sql, array($id_materia_prerequisito));
		return $query->result();
	}

	public function verifsimateritienevariospre($id_materia)
	{
		// $this->db->select('id_materia_mension');
		$this->db->from('prerequisito');
		$this->db->where('id_materia_mension', $id_materia);
		$query = $this->db->get();
		// return $query->num_rows();
		if ($query->num_rows()>1) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function obtenertodoslosiddelosprerequisitos($id_materia)
	{
		// $this->db->select('id_materia_mension_prerequisito');
		$this->db->from('prerequisito');
		$this->db->where('id_materia_mension', $id_materia);
		$query = $this->db->get();
		return $query->result();
	}

	////////////////programar alumno admin end ///////////////////////////



















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


}
