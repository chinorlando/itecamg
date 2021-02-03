<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alumno extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Alumno_model','Alumno_model');
		$this->load->model('Admin_model','Admin_model');

		$this->load->helper(array('validate/boletin_rules'));
	}
	function salida($data)
    {
        $this->load->view('template/header');
        $this->load->view($data['vista'],$data);
        $this->load->view('template/footer');
    }

	public function index()
	{
		// if ($_SESSION['is_type_user'] != 'is_alumno')
            
        if ($_SESSION['is_type_user'] == 'is_alumno' || $_SESSION['is_type_user'] == 'is_admin'){
        	$data['vista'] = 'alumno/v_principal';
        	$this->salida($data);
        } else {
        	redirect(base_url() . '', 'refresh');
        }
	}

    public function kardex()
	{
		if ($_SESSION['is_type_user'] != 'is_alumno')
            redirect(base_url() . '', 'refresh');
        if ($_SESSION['is_type_user'] == 'is_alumno'){
        	$data['vista'] = 'alumno/v_kardex';
        	$this->salida($data);
        }
	}

	///////////////////////libreta////////////////////////////////////////
	public function libreta()
	{
		if ($_SESSION['is_type_user'] != 'is_alumno')
            redirect(base_url() . '', 'refresh');
        if ($_SESSION['is_type_user'] == 'is_alumno'){
        	$data['vista'] = 'alumno/v_libreta';
        	$this->salida($data);
        }
	}

	public function get_gestionperiodo()
	{
		$all_gestion = $this->Alumno_model->gestion_perido();
		echo json_encode($all_gestion);
	}

	public function notas()
	{
		$id_gestionperiodo = $this->input->post('gestion');
		$id_alumno = $this->Alumno_model->get_idalumno($_SESSION['user_id']);
		$materias = $this->Alumno_model->materias($id_alumno, $id_gestionperiodo);
		$lib = '';
		foreach ($materias as $materia) {
			$notas_por_materia = $this->Alumno_model->notas_por_materia($id_alumno, $materia->id_materia, $id_gestionperiodo);
			$lib .='<tr>';
				$lib .='<td>'.$materia->sigla.'</td>';
				$lib .='<td>'.$materia->nombre.'</td>';
				$lib .='<td>'.$materia->nivel_curso.'</td>';
				$lib .='<td>'.$materia->nombre_paralelo.'</td>';
				if (count($notas_por_materia) == 0) {
					for ($i=0; $i < 6; $i++) { 
						$lib .='<td>0</td>';
					}
				} else {
					foreach ($notas_por_materia as $nota) {
						$lib .='<td>'.$nota.'</td>';
					}
				}
			$lib .='</tr>';
		}

		$libreta = array('lib' => $lib);
		echo json_encode($libreta);
	}
	///////////////////////libreta////////////////////////////////////////

	///////////////////////boletin////////////////////////////////////////
	public function boletin()
	{
        // $data['vista'] = 'alumno/v_login_boletin';
        // $this->salida($data);
        // $this->load->view('template/header');
        $this->load->view('alumno/v_login_boletin');
        // $this->load->view('template/footer');
	}

	public function busqueda()
	{
		$ci = $this->input->post('ci');
		$fecha = $this->input->post('dob');
		$dob = date("Y-m-d", strtotime($fecha));
		// print_r($dob);
		// exit();

		$this->validateboletin();
		$gestionperiodo = $this->Admin_model->ultimogestion();
		$alumno = $this->Alumno_model->get_idalumno_by_ci_dob($ci, $dob);
		if (!$alumno) {
			echo json_encode(array('error' => TRUE, 'msg'=> 'No se pudo encontrar al alumno'));
			exit();
		}
		$materias = $this->Alumno_model->materias($alumno->id_alumno, $gestionperiodo->id_gestionperiodo);
		// print_r($alumno);
		// exit();
		if (!$materias) {
			echo json_encode(array('error' => TRUE, 'msg'=> 'El alumno no tiene materias programadas.'));
			exit();
		} else {
		$lib = '';
		$lib .= '<div class="panel panel-flat">';
			$lib .= '<div class="panel-body">';
				$lib .= '<div class="form-group">';
					$lib .= '<div class="col-md-12 col-md-offset-1">';
						$lib .= '<div class="row">';
							$lib .= '<h6><label class="control-label col-lg-3 text-right">Nombre completo: </label></h6>';
							$lib .= '<h5><b><label class="control-label col-lg-9 text-left">'.trim($alumno->apellido_paterno).' '.trim($alumno->apellido_materno).', '.trim($alumno->nombres).'</label></b></h5>';
                        $lib .= '</div>';
					$lib .= '</div>';
				$lib .= '</div>';
				$lib .= '<div class="form-group">';
					$lib .= '<div class="col-md-12 col-md-offset-1">';
						$lib .= '<div class="row">';
							$lib .= '<h6><label class="control-label col-lg-3 text-right">Carrera: </label></h6>';
							$lib .= '<h5><b><label class="control-label col-lg-9 text-left">'.trim($alumno->nombre_carrera).'</label></b></h5>';
                        $lib .= '</div>';
					$lib .= '</div>';
				$lib .= '</div>';
			$lib .= '</div>';
		$lib .= '</div>';

		$lib .= '<div class="panel panel-flat">';
			$lib .= '<div class="table-responsive">';
				$lib .= '<table class="table">';
					$lib .= '<thead>';
						$lib .= '<tr class="bg-orange-700">';
							$lib .= '<th>Sigla</th>';
							$lib .= '<th>Materia</th>';
							$lib .= '<th>Curso</th>';
							$lib .= '<th>Paralelo</th>';
							$lib .= '<th>1B</th>';
							$lib .= '<th>2B</th>';
							$lib .= '<th>3B</th>';
							$lib .= '<th>4B</th>';
							$lib .= '<th>NF</th>';
							$lib .= '<th>SI</th>';
						$lib .= '</tr>';
					$lib .= '</thead>';
					$lib .= '<tbody id="libreta_tbody">';
		foreach ($materias as $materia) {
			$notas_por_materia = $this->Alumno_model->notas_por_materia($alumno->id_alumno, $materia->id_materia, $gestionperiodo->id_gestionperiodo);
			$lib .='<tr>';
				$lib .='<td>'.$materia->sigla.'</td>';
				$lib .='<td>'.$materia->nombre.'</td>';
				$lib .='<td>'.$materia->nivel_curso.'</td>';
				$lib .='<td>'.$materia->nombre_paralelo.'</td>';
				if (count($notas_por_materia) == 0) {
					for ($i=0; $i < 6; $i++) { 
						$lib .='<td>0</td>';
					}
				} else {
					foreach ($notas_por_materia as $nota) {
						$lib .='<td>'.$this->encrypt->decode($nota).'</td>';
					}
				}
			$lib .='</tr>';
		}
					$lib .= '</tbody>';
				$lib .= '</table>';
			$lib .= '</div>';
		$lib .= '</div>';
		echo json_encode(array('status' => TRUE, 'lib'=> $lib));
		}

		// $lib .= '<div class="panel panel-flat">';
		// 	$lib .= '<div class="panel-body">';
		// 		$lib .= '<div class="form-group">';
		// 			$lib .= '<h5 class="panel-title">Seleccione año<a class="heading-elements-toggle"><i class="icon-more"></i></a></h5>';
		// 			$lib .= '<select id="gestionperiodo" name="gestionperiodo" class="form-control">';
		// 				$lib .= '<option value="">Seleccione...</option>';
		//             $lib .= '</select>';
		// 		$lib .= '</div>';
		// 	$lib .= '</div>';
		// $lib .= '</div>';


	}

	public function misnotas_a()
	{
		
		$this->load->view('alumno/v_boletin');
	}

	///////////////////////boletin////////////////////////////////////////

	///////////////////////programarme////////////////////////////////////////
	public function programarme()
	{
		// if ($_SESSION['is_type_user'] != 'is_alumno')
        if ($_SESSION['is_type_user'] == 'is_alumno' || $_SESSION['is_type_user'] == 'is_admin'){
        	$data['vista'] = 'alumno/v_programarme';
        	$this->salida($data);
        }else {
            redirect(base_url() . '', 'refresh');
        }
	}

	public function programado_noprogramado()
	{
		$id_alumno = $this->Alumno_model->get_idalumno($_SESSION['user_id']);
		$id_gestionperiodo = $this->Alumno_model->get_gestion_matricula($id_alumno)->id_gestionperiodo;
		$pro_nopro = $this->Alumno_model->programado_noprogramado($id_alumno, $id_gestionperiodo);
		// print_r($pro_nopro);
		if ($pro_nopro>0) {
			echo json_encode(array('status' => TRUE));
		} else {
			echo json_encode(array('status' => FALSE));
		}

	}

	// public function materias_a_programarme()
	// {
	// 	$id_alumno = $this->Alumno_model->get_idalumno($_SESSION['user_id']);
	// 	$gestion = $this->Alumno_model->get_gestion_matricula($id_alumno)->gestion;
	// 	// print_r($gestion);
	// 	// exit();
	// 	// $gestion = 2019;
	// 	$row[] = array();
	// 	$mapro = '';
	// 	$no=1;
	// 	$parl_id = null;
	// 	$parl_nombre = null;

	// 	$materias_anterior_gestion = $this->Alumno_model->materias_anterior_gestion($id_alumno, $gestion);
	// 	// print_r($materias_anterior_gestion);
	// 	// exit();
	// 	foreach ($materias_anterior_gestion as $materia) {
	// 		// $mapro .='<tr>';
	// 		$nota_final_materia = $this->Alumno_model->nota_materia_anterior_gestion($materia->id_materia, $gestion, $id_alumno);
	// 		// print_r(explode(",", $this->encrypt->decode($nota_final_materia))[4]);
	// 		$nota_final = explode(",", $this->encrypt->decode($nota_final_materia))[4];
	// 		if ($nota_final > 50) {
	// 			$materias_a_programarme = $this->Alumno_model->materias_a_programarme($materia->id_materia);
	// 			foreach ($materias_a_programarme as $materia_a_programarme) {
	// 				$id_materia_programarme = $materia_a_programarme->id_materia;
	// 				$datos_materia = $this->Alumno_model->get_materia($id_materia_programarme);
	// 				// numero de alumnos programados en cada paralelo
	// 				$paralelos = $this->Alumno_model->get_paralelo($datos_materia->nivel_curso);
	// 				$ultimo_gestionperiodo = $this->Admin_model->ultimogestion();
	// 				foreach ($paralelos as $p) {
	// 				    if ($this->Alumno_model->cant_alumnos_programados_paralelo($id_alumno, $ultimo_gestionperiodo->id_gestionperiodo, $p->id_paralelo) <= 60) {
	// 				    	$parl_id[] = $p->id_paralelo;
	// 				    	$parl_nombre[] = $p->nombre;
	// 				    	// entra un break
	// 				    }
	// 				}
	// 				// numero de alumnos programados en cada paralelo^^^^^^^
	// 				$mapro .='<tr>';
	// 					$mapro .='<td>'.$no.'</td>';
	// 					$mapro .='<td>'.$datos_materia->sigla.'</td>';
	// 					$mapro .='<td>'.$datos_materia->nombre.'</td>';
	// 					$mapro .='<td>'.$datos_materia->nivel_curso.'</td>';
	// 					$mapro .='<td><select id="paralelo" name="paralelo[]" class="form-control">';
	// 					$mapro .='<option value="-1">-</option>';
	// 					for ($i=0; $i < count($parl_id); $i++) {
	// 						$mapro .='<option value="'.$parl_id[$i].'">'.$parl_nombre[$i].'</option>';
	// 					}
	// 					$mapro .='</select></td>';
	// 					$mapro .='<td><input class="switchery asignaturas" type="checkbox" id="asignaturas" name="asignaturas[]" value="'.$datos_materia->id_materia.'"></td>';
	// 					$mapro .='<td><input class="switchery confirm" type="checkbox" id="confirmar" name="confirmar[]" value="'.$datos_materia->id_materia.'"></td>';
	// 				$mapro .='</tr>';
	// 				$no++;

	// 				$parl_id = null;
	// 				$parl_nombre = null;
	// 			}
	// 		} else {
	// 			$datos_materia = $this->Alumno_model->get_materia($materia->id_materia);
	// 			// numero de alumnos programados en cada paralelo
	// 			$paralelos = $this->Alumno_model->get_paralelo($datos_materia->nivel_curso);
	// 			$ultimo_gestionperiodo = $this->Admin_model->ultimogestion();
	// 			foreach ($paralelos as $p) {
	// 			    if ($this->Alumno_model->cant_alumnos_programados_paralelo($id_alumno, $ultimo_gestionperiodo->id_gestionperiodo, $p->id_paralelo) <= 60) {
	// 			    	$parl_id[] = $p->id_paralelo;
	// 			    	$parl_nombre[] = $p->nombre;
	// 			    }
	// 			}
	// 			// numero de alumnos programados en cada paralelo^^^^^^^
	// 			$mapro .='<tr>';
	// 				$mapro .='<td>'.$no.'</td>';
	// 				$mapro .='<td>'.$datos_materia->sigla.'</td>';
	// 				$mapro .='<td>'.$datos_materia->nombre.'</td>';
	// 				$mapro .='<td>'.$datos_materia->nivel_curso.'</td>';
	// 				$mapro .='<td><select id="paralelo" name="paralelo[]" class="form-control">';
	// 				$mapro .='<option value="-1">-</option>';
	// 				for ($i=0; $i < count($parl_id); $i++) { 
	// 					$mapro .='<option value="'.$parl_id[$i].'">'.$parl_nombre[$i].'</option>';
	// 				}
	// 				$mapro .='</select></td>';
	// 				$mapro .='<td><input class="switchery asignaturas" type="checkbox" id="asignaturas" name="asignaturas[]" value="'.$datos_materia->id_materia.'"></td>';
	// 				$mapro .='<td><input class="switchery confirm" type="checkbox" id="confirmar" name="confirmar[]" value="'.$datos_materia->id_materia.'"></td>';
	// 			$mapro .='</tr>';
	// 			$no++;

	// 			$parl_id = null;
	// 			$parl_nombre = null;
	// 		}
	// 		// $mapro .='</tr>';
	// 	}

	// 	$maproreta = array('mapro' => $mapro);
	// 	echo json_encode($maproreta);
	// }

	// public function materias_a_reprogramarme()
	// {
	// 	$id_alumno = $this->Alumno_model->get_idalumno($_SESSION['user_id']);
	// 	$gestion = $this->Alumno_model->get_gestion_matricula($id_alumno)->gestion;
	// 	$ultimo_gestionperiodo = $this->Admin_model->ultimogestion();
	// 	// print_r($gestion);
	// 	// $gestion = 2019;
	// 	$row[] = array();
	// 	$mapro = '';
	// 	$no=1;
	// 	$parl_id = null;
	// 	$parl_nombre = null;

	// 	$materias_anterior_gestion = $this->Alumno_model->materias_anterior_gestion($id_alumno, $gestion);
	// 	foreach ($materias_anterior_gestion as $materia) {
	// 		// $mapro .='<tr>';
	// 		$nota_final_materia = $this->Alumno_model->nota_materia_anterior_gestion($materia->id_materia, $gestion, $id_alumno);
	// 		// print_r($nota_final_materia);
	// 		// exit();
	// 		if ($nota_final_materia > 50) {
	// 			$materias_a_programarme = $this->Alumno_model->materias_a_programarme($materia->id_materia);
	// 			foreach ($materias_a_programarme as $materia_a_programarme) {
	// 				$id_materia_programarme = $materia_a_programarme->id_materia;
	// 				$datos_materia = $this->Alumno_model->get_materia($id_materia_programarme);
	// 				// numero de alumnos programados en cada paralelo
	// 				$paralelos = $this->Alumno_model->get_paralelo($datos_materia->nivel_curso);
					
	// 				foreach ($paralelos as $p) {
	// 				    if ($this->Alumno_model->cant_alumnos_programados_paralelo($id_alumno, $ultimo_gestionperiodo->id_gestionperiodo, $p->id_paralelo) <= 60) {
	// 				    	$parl_id[] = $p->id_paralelo;
	// 				    	$parl_nombre[] = $p->nombre;
	// 				    }
	// 				}
	// 				// numero de alumnos programados en cada paralelo^^^^^^^
	// 				$paralelos_programado = $this->Alumno_model->list_paralelos_programacion($ultimo_gestionperiodo->id_gestionperiodo, $id_alumno, $id_materia_programarme);

	// 				$mapro .='<tr>';
	// 					$mapro .='<td>'.$no.'</td>';
	// 					$mapro .='<td>'.$datos_materia->sigla.'</td>';
	// 					$mapro .='<td>'.$datos_materia->nombre.'</td>';
	// 					$mapro .='<td>'.$datos_materia->nivel_curso.'</td>';
	// 					$mapro .='<td><select id="paralelo" name="paralelo[]" class="form-control">';
	// 					$mapro .='<option value="-1">Elige paralelo...</option>';
	// 					for ($i=0; $i < count($parl_id); $i++) {
	// 						if (count($paralelos_programado) != 0) {
	// 							foreach ($paralelos_programado as $paralelo) {
	// 								$sel = "";
	// 								if ($parl_id[$i] == $paralelo->id_paralelo) {
	// 									$sel="selected";
	// 								}
	// 								$mapro .='<option '.$sel.' value="'.$parl_id[$i].'">'.$parl_nombre[$i].'</option>';
	// 							}
	// 						} else {
	// 							$mapro .='<option value="'.$parl_id[$i].'">'.$parl_nombre[$i].'</option>';
	// 						}
	// 					}
	// 					$mapro .='</select></td>';
	// 					$mapro .='<td><input class="switchery asignaturas" type="checkbox" id="asignaturas" name="asignaturas[]" value="'.$datos_materia->id_materia.'"></td>';
	// 					$mapro .='<td><input class="switchery confirm" type="checkbox" id="confirmar" name="confirmar[]" value="'.$datos_materia->id_materia.'"></td>';
	// 				$mapro .='</tr>';
	// 				$no++;

	// 				$parl_id = null;
	// 				$parl_nombre = null;
	// 			}
	// 		} else {
	// 			$datos_materia = $this->Alumno_model->get_materia($materia->id_materia);
	// 			// numero de alumnos programados en cada paralelo
	// 			$paralelos = $this->Alumno_model->get_paralelo($datos_materia->nivel_curso);
	// 			$ultimo_gestionperiodo = $this->Admin_model->ultimogestion();
	// 			foreach ($paralelos as $p) {
	// 			    if ($this->Alumno_model->cant_alumnos_programados_paralelo($id_alumno, $ultimo_gestionperiodo->id_gestionperiodo, $p->id_paralelo) <= 60) {
	// 			    	$parl_id[] = $p->id_paralelo;
	// 			    	$parl_nombre[] = $p->nombre;
	// 			    }
	// 			}
	// 			// numero de alumnos programados en cada paralelo^^^^^^^
	// 			$paralelos_programado = $this->Alumno_model->list_paralelos_programacion($ultimo_gestionperiodo->id_gestionperiodo, $id_alumno, $materia->id_materia);
	// 			$mapro .='<tr>';
	// 				$mapro .='<td>'.$no.'</td>';
	// 				$mapro .='<td>'.$datos_materia->sigla.'</td>';
	// 				$mapro .='<td>'.$datos_materia->nombre.'</td>';
	// 				$mapro .='<td>'.$datos_materia->nivel_curso.'</td>';
	// 				$mapro .='<td><select id="paralelo" name="paralelo[]" class="form-control">';
	// 				$mapro .='<option value="-1">Elige paralelo...</option>';
	// 				for ($i=0; $i < count($parl_id); $i++) {
	// 					if (count($paralelos_programado) != 0) {
	// 						$sel = "";
	// 						foreach ($paralelos_programado as $paralelo) {
	// 							if ($parl_id[$i] == $paralelo->id_paralelo) {
	// 								$sel="selected";
	// 							}
	// 							$mapro .='<option '.$sel.' value="'.$parl_id[$i].'">'.$parl_nombre[$i].'</option>';
	// 						}
	// 					} else {
	// 						$mapro .='<option value="'.$parl_id[$i].'">'.$parl_nombre[$i].'</option>';
	// 					}
	// 				}
	// 				$mapro .='</select></td>';
	// 				$mapro .='<td><input class="switchery asignaturas" type="checkbox" id="asignaturas" name="asignaturas[]" value="'.$datos_materia->id_materia.'"></td>';
	// 				$mapro .='<td><input class="switchery confirm" type="checkbox" id="confirmar" name="confirmar[]" value="'.$datos_materia->id_materia.'"></td>';
	// 			$mapro .='</tr>';
	// 			$no++;

	// 			$parl_id = null;
	// 			$parl_nombre = null;
	// 		}
	// 		// $mapro .='</tr>';
	// 	}

	// 	$maproreta = array('mapro' => $mapro);
	// 	echo json_encode($maproreta);
	// }

	public function get_paralelo($id_alumno)
	{
		// $id_alumno = $this->Alumno_model->get_idalumno($_SESSION['user_id']);
		$gestion = $this->Alumno_model->get_gestion_matricula($id_alumno)->gestion;
		$materias = $this->Alumno_model->get_materias_paralelo($id_alumno, $gestion);
		echo json_encode(array('paralelo'=>$materias));
	}

	public function ajax_add_programacion()
	{
		// print_r($_POST["asignaturas"]);
		// print_r($_POST["confirmar"]);
		// print_r($_POST["paralelo"]);
		$i=0;
		foreach ($_POST["paralelo"] as $paralelo) {
			if ($paralelo !== '-1') {
				$mateprog[] = $paralelo;
			}
		}

		$id_alumno = $this->Alumno_model->get_idalumno($_SESSION['user_id']);
		$id_gestionperiodo = $this->Alumno_model->get_gestion_matricula($id_alumno)->id_gestionperiodo;
		if ( !empty($_POST["asignaturas"]) && is_array($_POST["asignaturas"]) ) {
			if ( !empty($_POST["confirmar"]) && is_array($_POST["confirmar"]) ) { 
				if (!empty($_POST["paralelo"]) && is_array($_POST["paralelo"])) {
					foreach ($_POST["asignaturas"] as $asignatura) {
						$dataAsignaturaProgramacion = array(
							'id_materia' => $asignatura,
							'id_gestionperiodo' => $id_gestionperiodo,
							'id_alumno' => $id_alumno,
							'id_paralelo' => $mateprog[$i],
						);
						$this->Alumno_model->save_programation($dataAsignaturaProgramacion);
						// print_r($dataAsignaturaProgramacion);
						$i++;
					}
					echo json_encode(array('status'=>TRUE));
				} else {
					echo json_encode(array('status'=>FALSE, 'error'=>'Debe seleccionar el paralelo'));
				}
			} else {
				echo json_encode(array('status'=>FALSE, 'error'=>'Debe confirmar las asignaturas.'));
			}
		} else {
			echo json_encode(array('status'=>FALSE,'error'=>'Debe seleccionar por lo menos una asignatura'));
		}
	}
	// editar programacion
	public function a_edit_programation()
	{
		$id_alumno = $this->Alumno_model->get_idalumno($_SESSION['user_id']);
		$id_gestionperiodo = $this->Alumno_model->get_gestion_matricula($id_alumno)->id_gestionperiodo;
		$asignatura_programada = $this->Alumno_model->get_materiasprogramadas($id_alumno, $id_gestionperiodo);
		// echo json_encode(array('data'=>$asignatura_programada));
		echo json_encode($asignatura_programada);
	}

	public function ajax_update_programacion()
	{
		$i=0;
		foreach ($_POST["paralelo"] as $paralelo) {
			if ($paralelo !== '-1') {
				$mateprog[] = $paralelo;
			}
		}

		$id_alumno = $this->Alumno_model->get_idalumno($_SESSION['user_id']);
		$id_gestionperiodo = $this->Alumno_model->get_gestion_matricula($id_alumno)->id_gestionperiodo;
		$asignaturas_programadas = $this->Alumno_model->get_materiasprogramadas($id_alumno, $id_gestionperiodo);
		
		// array $si donde estan las materias que debemos borrar
		$si = array();
		// array donde esta todos las materias que nos hemos programado
		$no = array();
		if ( !empty($_POST["asignaturas"]) && is_array($_POST["asignaturas"]) ) {
			if ( !empty($_POST["confirmar"]) && is_array($_POST["confirmar"]) ) { 
				if (!empty($_POST["paralelo"]) && is_array($_POST["paralelo"])) {
					foreach ($asignaturas_programadas as $asig) {
						$no[] = $asig->id_materia;
					}
					if (count($_POST["asignaturas"]) == count($no)) {
						echo json_encode(array('status'=> 'no'));
						exit;
					} elseif (count($_POST["asignaturas"]) > count($no)) {
						$si = array_diff($_POST["asignaturas"], $no);
					} else{
						$si = array_diff($no, $_POST["asignaturas"]);
					}

					foreach ($si as $id_materia) {;
						$materia_programada = $this->Alumno_model->comprobar_si_materiestaprogramada($id_alumno, $id_gestionperiodo, $id_materia);
						if ($materia_programada) {
							// la materia ya ha sido programada asi que eliminar
							$this->Alumno_model->eliminar_materia_programada_por_idmateria($id_alumno, $id_gestionperiodo, $id_materia);
						} else {
							// la materia no existe, entonces programar materia
							$dataAsignaturaProgramacion = array(
								'id_materia' => $id_materia,
								'id_gestionperiodo' => $id_gestionperiodo,
								'id_alumno' => $id_alumno,
								'id_paralelo' => $mateprog[$i],
							);
							$this->Alumno_model->save_programation($dataAsignaturaProgramacion);
							// print_r($dataAsignaturaProgramacion);
						}
						$i++;
					}
					echo json_encode(array('status'=>TRUE));
				} else {
					echo json_encode(array('status'=>FALSE, 'error'=>'Debe seleccionar el paralelo'));
				}
			} else {
				echo json_encode(array('status'=>FALSE, 'error'=>'Debe confirmar las asignaturas.'));
			}
		} else {
			echo json_encode(array('status'=>FALSE,'error'=>'Debe seleccionar por lo menos una asignatura'));
		}
	}
	//editar programacion

	///////////////////////programarme////////////////////////////////////////
	////////////////kardex////////////////////////////////
	
	////////////////kardex////////////////////////////////

	public function list_kardex()
	{
		$id_alumno = $this->Alumno_model->get_idalumno($_SESSION['user_id']);
		$id_gestionperiodo = $this->Alumno_model->get_gestion_matricula($id_alumno)->id_gestionperiodo;
		$list = $this->Alumno_model->kardex($id_alumno, $id_gestionperiodo);
		// print_r($list[0]->id_materia);
		// exit;
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $person) {
			// $notas_por_materia = $this->Alumno_model->notas_por_materia($id_alumno, $person->id_materia, $id_gestionperiodo);
			// print_r($notas_por_materia);
			// exit;
			// $nota_v = explode(',', $this->encrypt->decode($person->nota)) ;
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $person->gestion;
			$row[] = $person->nombre;
			$row[] = $person->nivel_curso;
			$row[] = $person->sigla;
			$row[] = $person->materia_nombre;
			$row[] = $person->final;
			$row[] = $person->segundo_turno;
			$row[] = ($person->final <= 60) ? "Reprobado" : "Aprobado" ;

			$data[] = $row;
		}

		$output = array(
			"data" => $data,
		);
		echo json_encode($output);
	}


	///////////////////////////////////////////////////////////////////////////////////////////////

	// funcion llamada desde el controlador admin para programar al alumno
	// public function programarme()
	// {
	// 	// if ($_SESSION['is_type_user'] != 'is_alumno')
 //        if ($_SESSION['is_type_user'] == 'is_alumno' || $_SESSION['is_type_user'] == 'is_admin'){
 //        	$data['vista'] = 'alumno/v_programarme';
 //        	$this->salida($data);
 //        }else {
 //            redirect(base_url() . '', 'refresh');
 //        }
	// }

	public function programar_a_alumno($id_alumno)
	{
		if ($_SESSION['is_type_user'] == 'is_admin'){
        	$data['vista'] = 'alumno/v_programar';
        	$data['id_alumno'] = $id_alumno;
        	$this->salida($data);
        } else {
        	redirect(base_url() . '', 'refresh');
        }
	}

	public function estado_programacion_alumno($id_alumno)
	{
		// $id_alumno = $this->Alumno_model->get_idalumno($_SESSION['user_id']);
		$id_gestionperiodo = $this->Alumno_model->get_gestion_matricula($id_alumno)->id_gestionperiodo;
		$pro_nopro = $this->Alumno_model->programado_noprogramado($id_alumno, $id_gestionperiodo);
		// print_r($pro_nopro);
		if ($pro_nopro>0) {
			echo json_encode(array('status' => TRUE));
		} else {
			echo json_encode(array('status' => FALSE));
		}

	}

	public function materias_a_programar($id_alumno)
	{
		// $gestion = $this->Alumno_model->get_gestion_matricula($id_alumno)->gestion;
		// $gestion = $this->Admin_model->ultimogestion();
		// revisar este codigo ya que no esta usando id del alumno
		$curso_nivel_matr = $this->Alumno_model->get_gestion_matricula($id_alumno)->id_curso;

		// en programacion usar esta
		// 3686->4746225, 3715->4371529, 3199
		$gestionmatricula = $this->Alumno_model->gestioactualnmatricula($id_alumno)->gestion;
		// print_r($gestionmatricula);
		// exit();
		// $gestion = 2020;
		$row[] = array();
		$mapro = '';
		$no=1;
		$parl_id = null;
		$parl_nombre = null;

		// $materias_anterior_gestion = $this->Alumno_model->materias_anterior_gestion($id_alumno, $gestionmatricula);

		$materias_anterior_gestion = $this->Alumno_model->materias_anterior_gestion_oficial($id_alumno, $gestionmatricula);

		// print_r($materias_anterior_gestion);
		// exit();
		
		// $arrayobj = new stdClass();
		// foreach ($materias_anterior_gestion as $m) {
		// 	if($m->troncal = 'N'){
		// 		$notas = $this->Alumno_model->nota_materia_anterior_gestion_oficial($m->id_materia_mension, $gestionmatricula, $id_alumno);
		// 		if ($this->encrypt->decode($notas->final) >= 60.51) {
		// 			// print_r($this->encrypt->decode($notas->final));
		// 			$matpro[] = $mater->id_materia_mension;
		// 		} else {
		// 			if ($this->encrypt->decode($notas->segundo_turno) >= 60.51) {
		// 				// print_r($this->encrypt->decode($notas->final));
		// 				$matpro[] = $mater->id_materia_mension;
		// 			}
		// 		}
		// 	} else {

		// 	}
		// }
		
		// $arrayobj->id_materia_mension = 'asdfasdfasd';
		// $materias_anterior_gestion[] = $arrayobj;

		// print_r($materias_anterior_gestion);
		// exit();
		foreach ($materias_anterior_gestion as $materia) {
			if($materia->troncal == 'N'){

				$notas = $this->Alumno_model->nota_materia_anterior_gestion_oficial($materia->id_materia_mension, $gestionmatricula, $id_alumno);
				if ($this->encrypt->decode($notas->final) >= 60.51) {
					// $matpro[] = $mater->id_materia_mension;
				} else {
					if ($this->encrypt->decode($notas->segundo_turno) >= 60.51) {
						// $matpro[] = $mater->id_materia_mension;
					} else {
						$matpro[] = $materia->id_materia_mension;
					}
				}
			} else {
				$mat_pre = $this->Alumno_model->obtenertodoslossprerequisitos($materia->id_materia_mension);
				// print_r(($mat_pre));
				// exit();
				foreach ($mat_pre as $mat_aprorepro) {
					$materias = $this->Alumno_model->obternemateriatroncalconprerequisito($mat_aprorepro->id_materia_mension);
					// print_r(($materias));
					// exit();
					foreach ($materias as $mater) {
						// print_r($mater->id_materia_mension_prerequisito);
						// exit();
						$notas = $this->Alumno_model->nota_materia_anterior_gestion($mater->id_materia_mension_prerequisito, $gestionmatricula, $id_alumno);
							// print_r('<pre>');
							// print_r($this->encrypt->decode($notas->final).'<br>');
							// print_r($this->encrypt->decode($notas->segundo_turno).'<br>');
						
						// exit();
						if ($this->encrypt->decode($notas->final) >= 60.51) {
							// print_r($this->encrypt->decode($notas->final));
							$matpro[] = $mater->id_materia_mension;
						} else {
							if ($this->encrypt->decode($notas->segundo_turno) >= 60.51) {
								// print_r($this->encrypt->decode($notas->final));
								$matpro[] = $mater->id_materia_mension;
							} else {
								$matpro[] = $mater->id_materia_mension_prerequisito;
							}
						}
					}
				}
			}
			
		}

		// print_r($matpro);
		// exit();

		$listamat = array_values(array_unique($matpro));
		sort($listamat);
		// print_r($listamat);
		// exit();

		foreach ($listamat as $materia_a_programarme) {
			// print_r($materia_a_programarme);
			// exit();
			// $id_materia_programarme = $materia_a_programarme->id_materia_mension;
			$id_materia_programarme = $materia_a_programarme;
			$datos_materia = $this->Alumno_model->get_materia($id_materia_programarme);
			// print_r($datos_materia);
			// exit();
			// numero de alumnos programados en cada materia
			$paralelos = $this->Alumno_model->get_paralelo($datos_materia->nivel_curso);
			$ultimo_gestionperiodo = $this->Admin_model->ultimogestion();
			// print_r($datos_materia->nombre.'<br>');
			// exit();
			foreach ($paralelos as $p) {
			    if ($this->Alumno_model->cant_alumnos_programados_paralelo($id_alumno, $ultimo_gestionperiodo->id_gestionperiodo, $p->id_paralelo) <= 60) {
			    	$parl_id[] = $p->id_paralelo;
			    	$parl_nombre[] = $p->nombre;
			    	// entra un break
			    }
			}

			// numero de alumnos programados en cada paralelo^^^^^^^
			$mapro .='<tr>';
				$mapro .='<td>'.$no.'</td>';
				$mapro .='<td>'.$datos_materia->sigla.'</td>';
				$mapro .='<td>'.$datos_materia->nombre.'</td>';
				$mapro .='<td>'.$datos_materia->nivel_curso.'</td>';
				$mapro .='<td><select id="paralelo" name="paralelo[]" class="form-control">';
				$mapro .='<option value="-1">-</option>';
				for ($i=0; $i < count($parl_id); $i++) {
					$mapro .='<option value="'.$parl_id[$i].'">'.$parl_nombre[$i].'</option>';
				}
				$mapro .='</select></td>';
				$mapro .='<td><input class="switchery asignaturas" type="checkbox" id="asignaturas" name="asignaturas[]" value="'.$datos_materia->id_materia.'"></td>';
				$mapro .='<td><input class="switchery confirm" type="checkbox" id="confirmar" name="confirmar[]" value="'.$datos_materia->id_materia.'"></td>';
			$mapro .='</tr>';
			$no++;

			$parl_id = null;
			$parl_nombre = null;
		}


		$maproreta = array('mapro' => $mapro);
		echo json_encode($maproreta);
	}

	public function a_edit_programation_admin_modificado($id_alumno)
	{
		$curso_nivel_matr = $this->Alumno_model->get_gestion_matricula($id_alumno)->id_curso;

		// en programacion usar esta
		// 3686->4746225, 3715->4371529
		$gestionmatricula = $this->Alumno_model->gestioactualnmatricula($id_alumno)->gestion;
		// print_r($gestionmatricula);
		// exit();
		// $gestion = 2020;
		$row[] = array();
		$mapro = '';
		$no=1;
		$parl_id = null;
		$parl_nombre = null;

		$materias_anterior_gestion = $this->Alumno_model->materias_anterior_gestion($id_alumno, $gestionmatricula);
		// print_r($materias_anterior_gestion);
		// exit();
		foreach ($materias_anterior_gestion as $materia) {
			if($materia->troncal == 'N'){

				$notas = $this->Alumno_model->nota_materia_anterior_gestion_oficial($materia->id_materia_mension, $gestionmatricula, $id_alumno);
				if ($this->encrypt->decode($notas->final) >= 60.51) {
					// $matpro[] = $mater->id_materia_mension;
				} else {
					if ($this->encrypt->decode($notas->segundo_turno) >= 60.51) {
						// $matpro[] = $mater->id_materia_mension;
					} else {
						$matpro[] = $materia->id_materia_mension;
					}
				}
			} else {
				$mat_pre = $this->Alumno_model->obtenertodoslossprerequisitos($materia->id_materia_mension);
				// print_r(($mat_pre));
				// exit();
				foreach ($mat_pre as $mat_aprorepro) {
					$materias = $this->Alumno_model->obternemateriatroncalconprerequisito($mat_aprorepro->id_materia_mension);
					// print_r(($materias));
					// exit();
					foreach ($materias as $mater) {
						// print_r($mater->id_materia_mension_prerequisito);
						// exit();
						$notas = $this->Alumno_model->nota_materia_anterior_gestion($mater->id_materia_mension_prerequisito, $gestionmatricula, $id_alumno);
							// print_r('<pre>');
							// print_r($this->encrypt->decode($notas->final).'<br>');
							// print_r($this->encrypt->decode($notas->segundo_turno).'<br>');
						
						// exit();
						if ($this->encrypt->decode($notas->final) >= 60.51) {
							// print_r($this->encrypt->decode($notas->final));
							$matpro[] = $mater->id_materia_mension;
						} else {
							if ($this->encrypt->decode($notas->segundo_turno) >= 60.51) {
								// print_r($this->encrypt->decode($notas->final));
								$matpro[] = $mater->id_materia_mension;
							} else {
								$matpro[] = $mater->id_materia_mension_prerequisito;
							}
						}
					}
				}
			}
		}

		$listamat = array_values(array_unique($matpro));
		sort($listamat);
		// print_r($listamat);

		foreach ($listamat as $materia_a_programarme) {
			// print_r($materia_a_programarme);
			// exit();
			// $id_materia_programarme = $materia_a_programarme->id_materia_mension;
			$id_materia_programarme = $materia_a_programarme;
			$datos_materia = $this->Alumno_model->get_materia($id_materia_programarme);
			// numero de alumnos programados en cada materia
			$paralelos = $this->Alumno_model->get_paralelo($datos_materia->nivel_curso);
			$ultimo_gestionperiodo = $this->Admin_model->ultimogestion();
			// print_r($datos_materia->nombre.'<br>');
			// exit();
			foreach ($paralelos as $p) {
			    if ($this->Alumno_model->cant_alumnos_programados_paralelo($id_alumno, $ultimo_gestionperiodo->id_gestionperiodo, $p->id_paralelo) <= 60) {
			    	$parl_id[] = $p->id_paralelo;
			    	$parl_nombre[] = $p->nombre;
			    	// entra un break
			    }
			}


			// numero de alumnos programados en cada paralelo^^^^^^^
			$mapro .='<tr>';
				$mapro .='<td>'.$no.'</td>';
				$mapro .='<td>'.$datos_materia->sigla.'</td>';
				$mapro .='<td>'.$datos_materia->nombre.'</td>';
				$mapro .='<td>'.$datos_materia->nivel_curso.'</td>';
				$mapro .='<td><select id="paralelo" name="paralelo[]" class="form-control paralelo">';
				$mapro .='<option value="-1">-</option>';
				
				for ($i=0; $i < count($parl_id); $i++) {
					$asignatura_programada = $this->Alumno_model->get_materiasprogramadas_admin($id_alumno, $gestionmatricula);
					foreach ($asignatura_programada as $asig_pro) {
						if ($asig_pro->id_materia == $datos_materia->id_materia) {
							if ($asig_pro->id_paralelo == $parl_id[$i]) {
								$selected = 'selected';
								break;
							} else {
								$selected = '';
							}
							
						} else {
							$selected = '';
						}
					}
					$mapro .='<option value="'.$parl_id[$i].'" '.$selected.'>'.$parl_nombre[$i].'</option>';


					// print_r($asignatura_programada);
					// exit();
					// if (empty($asignatura_programada)) {
					// 	$mapro .='<option value="'.$parl_id[$i].'" >'.$parl_nombre[$i].'</option>';
					// } else {
					// 	foreach ($asignatura_programada as $asig_pro) {
					// 		if ($asig_pro->id_paralelo == $parl_id[$i] && $asig_pro->id_materia == $datos_materia->id_materia) {
					// 			$selected = 'selected';
					// 			// $mapro .='<option value="'.$parl_id[$i].'" '.$selected.'>'.$parl_nombre[$i].'</option>';
					// 			break;
					// 		} else {
					// 			$selected = '';
					// 			// $mapro .='<option value="'.$parl_id[$i].'" '.$selected.'>'.$parl_nombre[$i].'</option>';
					// 			break;
					// 		}
					// 		// $mapro .='<option value="'.$parl_id[$i].'" '.$selected.'>'.$parl_nombre[$i].'</option>';
					// 	}
					// }

					// $mapro .='<option value="'.$parl_id[$i].'" >'.$parl_nombre[$i].'</option>';
					
				}
				$mapro .='</select></td>';
				$mapro .='<td>';
					// if (empty($asignatura_programada)) {
					// 	$mapro .= '<input class="switchery asignaturas" type="checkbox" id="asignaturas" name="asignaturas[]" value="'.$datos_materia->id_materia.'" >';
					// } else {
					// 	foreach ($asignatura_programada as $asig_pro) {
					// 		if ($asig_pro->id_materia == $datos_materia->id_materia) {
					// 			$checked = 'checked';
					// 			$mapro .= '<input class="switchery asignaturas" type="checkbox" id="asignaturas" name="asignaturas[]" value="'.$datos_materia->id_materia.'" '.$checked.'>';
					// 			// break;
					// 		} else {
					// 			$checked = '';
					// 			$mapro .= '<input class="switchery asignaturas" type="checkbox" id="asignaturas" name="asignaturas[]" value="'.$datos_materia->id_materia.'" '.$checked.'>';
					// 			// break;
					// 		}
					// 		// $checked = ($asig_pro->id_materia == $datos_materia->id_materia) ? 'checked' : '' ;
					// 		// $mapro .= '<input class="switchery asignaturas" type="checkbox" id="asignaturas" name="asignaturas[]" value="'.$datos_materia->id_materia.'" '.$checked.'>';
					// 	}
					// }
					$mapro .= '<input class="switchery asignaturas" type="checkbox" id="asignaturas" name="asignaturas[]" value="'.$datos_materia->id_materia.'" >';
					
				$mapro .= '</td>';
				$mapro .='<td>';
					// if (empty($asignatura_programada)) {
					// 	$mapro .= '<input class="switchery confirm" type="checkbox" id="confirmar" name="confirmar[]" value="'.$datos_materia->id_materia.'" >';
					// } else {
					// 	foreach ($asignatura_programada as $asig_pro) {
					// 		if ($asig_pro->id_materia == $datos_materia->id_materia) {
					// 			$checked = 'checked';
					// 			$mapro .= '<input class="switchery confirm" type="checkbox" id="confirmar" name="confirmar[]" value="'.$datos_materia->id_materia.'" '.$checked.'>';
					// 			// break;
					// 		} else {
					// 			$checked = '';
					// 			$mapro .= '<input class="switchery confirm" type="checkbox" id="confirmar" name="confirmar[]" value="'.$datos_materia->id_materia.'" '.$checked.'>';
					// 			// break;
					// 		}
					// 		// $mapro .= '<input class="switchery confirm" type="checkbox" id="confirmar" name="confirmar[]" value="'.$datos_materia->id_materia.'" '.$checked.'>';
					// 	}
					// }
					$mapro .= '<input class="switchery confirm" type="checkbox" id="confirmar" name="confirmar[]" value="'.$datos_materia->id_materia.'" >';
				$mapro .= '</td>';
			$mapro .='</tr>';
			$no++;

			$parl_id = null;
			$parl_nombre = null;
		}

		// $asignatura_programada = $this->Alumno_model->get_materiasprogramadas_admin($id_alumno, $gestionmatricula);
		// print_r($asignatura_programada);
		// exit();
		if (empty($asignatura_programada)) {
			$maproreta = array('mapro' => $mapro);
			echo json_encode($maproreta);
		} else {
			$maproreta = array('mapro' => $mapro, 'rep_materia' => $asignatura_programada);
			echo json_encode($maproreta);
		}
		// $maproreta = array('mapro' => $mapro);
		// 	echo json_encode($maproreta);


		
	}

	// esta funcion estamos modificando
	public function materias_a_reprogramar($id_alumno)
	{
		// $id_alumno = $this->Alumno_model->get_idalumno($_SESSION['user_id']);
		$gestion = $this->Alumno_model->get_gestion_matricula($id_alumno)->gestion;
		$ultimo_gestionperiodo = $this->Admin_model->ultimogestion();
		// $gestion = 2019;
		$row[] = array();
		$mapro = '';
		$no=1;
		$parl_id = null;
		$parl_nombre = null;

		$materias_anterior_gestion = $this->Alumno_model->materias_anterior_gestion($id_alumno, $gestion);
		foreach ($materias_anterior_gestion as $materia) {
			// $mapro .='<tr>';
			$nota_final_materia = $this->Alumno_model->nota_materia_anterior_gestion($materia->id_materia, $gestion, $id_alumno);
			// print_r($nota_final_materia);
			// exit();
			$nota_final = $this->encrypt->decode($nota_final_materia);
			if ($nota_final >= 60.51) {
		// print_r($nota_final);
		// exit();
				$materias_a_programarme = $this->Alumno_model->materias_a_programarme($materia->id_materia);
				foreach ($materias_a_programarme as $materia_a_programarme) {
					$id_materia_programarme = $materia_a_programarme->id_materia;
					$datos_materia = $this->Alumno_model->get_materia($id_materia_programarme);
					// numero de alumnos programados en cada paralelo
					$paralelos = $this->Alumno_model->get_paralelo($datos_materia->nivel_curso);
					
					foreach ($paralelos as $p) {
					    if ($this->Alumno_model->cant_alumnos_programados_paralelo($id_alumno, $ultimo_gestionperiodo->id_gestionperiodo, $p->id_paralelo) <= 60) {
					    	$parl_id[] = $p->id_paralelo;
					    	$parl_nombre[] = $p->nombre;
					    }
					}
					// numero de alumnos programados en cada paralelo^^^^^^^
					$paralelos_programado = $this->Alumno_model->list_paralelos_programacion($ultimo_gestionperiodo->id_gestionperiodo, $id_alumno, $id_materia_programarme);

					$mapro .='<tr>';
						$mapro .='<td>'.$no.'</td>';
						$mapro .='<td>'.$datos_materia->sigla.'</td>';
						$mapro .='<td>'.$datos_materia->nombre.'</td>';
						$mapro .='<td>'.$datos_materia->nivel_curso.'</td>';
						$mapro .='<td><select id="paralelo" name="paralelo[]" class="form-control">';
						$mapro .='<option value="-1"> -</option>';
						for ($i=0; $i < count($parl_id); $i++) {
							if (count($paralelos_programado) != 0) {
								foreach ($paralelos_programado as $paralelo) {
									$sel = "";
									if ($parl_id[$i] == $paralelo->id_paralelo) {
										$sel="selected";
									}
									$mapro .='<option '.$sel.' value="'.$parl_id[$i].'">'.$parl_nombre[$i].'</option>';
								}
							} else {
								$mapro .='<option value="'.$parl_id[$i].'">'.$parl_nombre[$i].'</option>';
							}
						}
						$mapro .='</select></td>';
						$mapro .='<td><input class="switchery asignaturas" type="checkbox" id="asignaturas" name="asignaturas[]" value="'.$datos_materia->id_materia.'"></td>';
						$mapro .='<td><input class="switchery confirm" type="checkbox" id="confirmar" name="confirmar[]" value="'.$datos_materia->id_materia.'"></td>';
					$mapro .='</tr>';
					$no++;

					$parl_id = null;
					$parl_nombre = null;
				}
			} else {
				$datos_materia = $this->Alumno_model->get_materia($materia->id_materia);
				// numero de alumnos programados en cada paralelo
				$paralelos = $this->Alumno_model->get_paralelo($datos_materia->nivel_curso);
				$ultimo_gestionperiodo = $this->Admin_model->ultimogestion();
				foreach ($paralelos as $p) {
				    if ($this->Alumno_model->cant_alumnos_programados_paralelo($id_alumno, $ultimo_gestionperiodo->id_gestionperiodo, $p->id_paralelo) <= 60) {
				    	$parl_id[] = $p->id_paralelo;
				    	$parl_nombre[] = $p->nombre;
				    }
				}
				// numero de alumnos programados en cada paralelo^^^^^^^
				$paralelos_programado = $this->Alumno_model->list_paralelos_programacion($ultimo_gestionperiodo->id_gestionperiodo, $id_alumno, $materia->id_materia);
				$mapro .='<tr>';
					$mapro .='<td>'.$no.'</td>';
					$mapro .='<td>'.$datos_materia->sigla.'</td>';
					$mapro .='<td>'.$datos_materia->nombre.'</td>';
					$mapro .='<td>'.$datos_materia->nivel_curso.'</td>';
					$mapro .='<td><select id="paralelo" name="paralelo[]" class="form-control">';
					$mapro .='<option value="-1"> - </option>';
					for ($i=0; $i < count($parl_id); $i++) {
						if (count($paralelos_programado) != 0) {
							$sel = "";
							foreach ($paralelos_programado as $paralelo) {
								if ($parl_id[$i] == $paralelo->id_paralelo) {
									$sel="selected";
								}
								$mapro .='<option '.$sel.' value="'.$parl_id[$i].'">'.$parl_nombre[$i].'</option>';
							}
						} else {
							$mapro .='<option value="'.$parl_id[$i].'">'.$parl_nombre[$i].'</option>';
						}
					}
					$mapro .='</select></td>';
					$mapro .='<td><input class="switchery asignaturas" type="checkbox" id="asignaturas" name="asignaturas[]" value="'.$datos_materia->id_materia.'"></td>';
					$mapro .='<td><input class="switchery confirm" type="checkbox" id="confirmar" name="confirmar[]" value="'.$datos_materia->id_materia.'"></td>';
				$mapro .='</tr>';
				$no++;

				$parl_id = null;
				$parl_nombre = null;
			}
			// $mapro .='</tr>';
		}

		$maproreta = array('mapro' => $mapro);
		echo json_encode($maproreta);
	}

	// continuar haciendo esto // funcion sin tocar
	// public function materias_a_reprogramar($id_alumno)
	// {
	// 	// $id_alumno = $this->Alumno_model->get_idalumno($_SESSION['user_id']);
	// 	$gestion = $this->Alumno_model->get_gestion_matricula($id_alumno)->gestion;
	// 	$ultimo_gestionperiodo = $this->Admin_model->ultimogestion();
	// 	// $gestion = 2019;
	// 	$row[] = array();
	// 	$mapro = '';
	// 	$no=1;
	// 	$parl_id = null;
	// 	$parl_nombre = null;

	// 	$materias_anterior_gestion = $this->Alumno_model->materias_anterior_gestion($id_alumno, $gestion);
	// 	foreach ($materias_anterior_gestion as $materia) {
	// 		// $mapro .='<tr>';
	// 		$nota_final_materia = $this->Alumno_model->nota_materia_anterior_gestion($materia->id_materia, $gestion, $id_alumno);
	// 		// print_r($nota_final_materia);
	// 		// exit();
	// 		$nota_final = $this->encrypt->decode($nota_final_materia);
	// 		if ($nota_final >= 60.51) {
	// 	// print_r($nota_final);
	// 	// exit();
	// 			$materias_a_programarme = $this->Alumno_model->materias_a_programarme($materia->id_materia);
	// 			foreach ($materias_a_programarme as $materia_a_programarme) {
	// 				$id_materia_programarme = $materia_a_programarme->id_materia;
	// 				$datos_materia = $this->Alumno_model->get_materia($id_materia_programarme);
	// 				// numero de alumnos programados en cada paralelo
	// 				$paralelos = $this->Alumno_model->get_paralelo($datos_materia->nivel_curso);
					
	// 				foreach ($paralelos as $p) {
	// 				    if ($this->Alumno_model->cant_alumnos_programados_paralelo($id_alumno, $ultimo_gestionperiodo->id_gestionperiodo, $p->id_paralelo) <= 60) {
	// 				    	$parl_id[] = $p->id_paralelo;
	// 				    	$parl_nombre[] = $p->nombre;
	// 				    }
	// 				}
	// 				// numero de alumnos programados en cada paralelo^^^^^^^
	// 				$paralelos_programado = $this->Alumno_model->list_paralelos_programacion($ultimo_gestionperiodo->id_gestionperiodo, $id_alumno, $id_materia_programarme);

	// 				$mapro .='<tr>';
	// 					$mapro .='<td>'.$no.'</td>';
	// 					$mapro .='<td>'.$datos_materia->sigla.'</td>';
	// 					$mapro .='<td>'.$datos_materia->nombre.'</td>';
	// 					$mapro .='<td>'.$datos_materia->nivel_curso.'</td>';
	// 					$mapro .='<td><select id="paralelo" name="paralelo[]" class="form-control">';
	// 					$mapro .='<option value="-1"> -</option>';
	// 					for ($i=0; $i < count($parl_id); $i++) {
	// 						if (count($paralelos_programado) != 0) {
	// 							foreach ($paralelos_programado as $paralelo) {
	// 								$sel = "";
	// 								if ($parl_id[$i] == $paralelo->id_paralelo) {
	// 									$sel="selected";
	// 								}
	// 								$mapro .='<option '.$sel.' value="'.$parl_id[$i].'">'.$parl_nombre[$i].'</option>';
	// 							}
	// 						} else {
	// 							$mapro .='<option value="'.$parl_id[$i].'">'.$parl_nombre[$i].'</option>';
	// 						}
	// 					}
	// 					$mapro .='</select></td>';
	// 					$mapro .='<td><input class="switchery asignaturas" type="checkbox" id="asignaturas" name="asignaturas[]" value="'.$datos_materia->id_materia.'"></td>';
	// 					$mapro .='<td><input class="switchery confirm" type="checkbox" id="confirmar" name="confirmar[]" value="'.$datos_materia->id_materia.'"></td>';
	// 				$mapro .='</tr>';
	// 				$no++;

	// 				$parl_id = null;
	// 				$parl_nombre = null;
	// 			}
	// 		} else {
	// 			$datos_materia = $this->Alumno_model->get_materia($materia->id_materia);
	// 			// numero de alumnos programados en cada paralelo
	// 			$paralelos = $this->Alumno_model->get_paralelo($datos_materia->nivel_curso);
	// 			$ultimo_gestionperiodo = $this->Admin_model->ultimogestion();
	// 			foreach ($paralelos as $p) {
	// 			    if ($this->Alumno_model->cant_alumnos_programados_paralelo($id_alumno, $ultimo_gestionperiodo->id_gestionperiodo, $p->id_paralelo) <= 60) {
	// 			    	$parl_id[] = $p->id_paralelo;
	// 			    	$parl_nombre[] = $p->nombre;
	// 			    }
	// 			}
	// 			// numero de alumnos programados en cada paralelo^^^^^^^
	// 			$paralelos_programado = $this->Alumno_model->list_paralelos_programacion($ultimo_gestionperiodo->id_gestionperiodo, $id_alumno, $materia->id_materia);
	// 			$mapro .='<tr>';
	// 				$mapro .='<td>'.$no.'</td>';
	// 				$mapro .='<td>'.$datos_materia->sigla.'</td>';
	// 				$mapro .='<td>'.$datos_materia->nombre.'</td>';
	// 				$mapro .='<td>'.$datos_materia->nivel_curso.'</td>';
	// 				$mapro .='<td><select id="paralelo" name="paralelo[]" class="form-control">';
	// 				$mapro .='<option value="-1"> - </option>';
	// 				for ($i=0; $i < count($parl_id); $i++) {
	// 					if (count($paralelos_programado) != 0) {
	// 						$sel = "";
	// 						foreach ($paralelos_programado as $paralelo) {
	// 							if ($parl_id[$i] == $paralelo->id_paralelo) {
	// 								$sel="selected";
	// 							}
	// 							$mapro .='<option '.$sel.' value="'.$parl_id[$i].'">'.$parl_nombre[$i].'</option>';
	// 						}
	// 					} else {
	// 						$mapro .='<option value="'.$parl_id[$i].'">'.$parl_nombre[$i].'</option>';
	// 					}
	// 				}
	// 				$mapro .='</select></td>';
	// 				$mapro .='<td><input class="switchery asignaturas" type="checkbox" id="asignaturas" name="asignaturas[]" value="'.$datos_materia->id_materia.'"></td>';
	// 				$mapro .='<td><input class="switchery confirm" type="checkbox" id="confirmar" name="confirmar[]" value="'.$datos_materia->id_materia.'"></td>';
	// 			$mapro .='</tr>';
	// 			$no++;

	// 			$parl_id = null;
	// 			$parl_nombre = null;
	// 		}
	// 		// $mapro .='</tr>';
	// 	}

	// 	$maproreta = array('mapro' => $mapro);
	// 	echo json_encode($maproreta);
	// }

	public function ajax_add_programacion_admin($id_alumno)
	{
		// print_r($_POST["asignaturas"]);
		// print_r($_POST["confirmar"]);
		// print_r($_POST["paralelo"]);
		// exit();
		$i=0;
		foreach ($_POST["paralelo"] as $paralelo) {
			if ($paralelo !== '-1') {
				$mateprog[] = $paralelo;
			}
		}

		// $id_alumno = $this->Alumno_model->get_idalumno($_SESSION['user_id']);
		$id_gestionperiodo = $this->Alumno_model->get_gestion_matricula($id_alumno)->id_gestionperiodo;
		if ( !empty($_POST["asignaturas"]) && is_array($_POST["asignaturas"]) ) {
			if ( !empty($_POST["confirmar"]) && is_array($_POST["confirmar"]) ) { 
				if (!empty($mateprog) && is_array($mateprog)) {
					foreach ($_POST["asignaturas"] as $asignatura) {
						$dataAsignacionParalelo = array(
							'id_paralelo' => $mateprog[$i],
							'id_materia' => $asignatura,
							'estado' => 'S'
						);
						$dataProgramacion = array(
							'id_gestionperiodo' => $id_gestionperiodo,
							'id_alumno' => $id_alumno,
							// 'id_asignacionparalelo' => $mateprog[$i],
						);

						// $dataAsignaturaProgramacion = array(
						// 	'id_materia' => $asignatura,
						// 	'id_gestionperiodo' => $id_gestionperiodo,
						// 	'id_alumno' => $id_alumno,
						// 	'id_paralelo' => $mateprog[$i],
						// );

						$this->Alumno_model->save_programation($dataProgramacion, $dataAsignacionParalelo);
						// print_r($dataAsignaturaProgramacion);
						$i++;
					}
					echo json_encode(array('status'=>TRUE));
				} else {
					echo json_encode(array('status'=>FALSE, 'error'=>'Debe seleccionar el paralelo'));
				}
			} else {
				echo json_encode(array('status'=>FALSE, 'error'=>'Debe confirmar las asignaturas.'));
			}
		} else {
			echo json_encode(array('status'=>FALSE,'error'=>'Debe seleccionar por lo menos una asignatura'));
		}
	}
	// editar programacion
	public function a_edit_programation_admin($id_alumno)
	{
		// $id_alumno = $this->Alumno_model->get_idalumno($_SESSION['user_id']);
		$id_gestionperiodo = $this->Alumno_model->get_gestion_matricula($id_alumno)->id_gestionperiodo;
		$asignatura_programada = $this->Alumno_model->get_materiasprogramadas_admin($id_alumno, $id_gestionperiodo);
		// echo json_encode(array('data'=>$asignatura_programada));
		echo json_encode($asignatura_programada);
	}

	// esta funcion es la que debemos modifcar para reprogramar estudiantes
	public function ajax_update_programacion_admin($id_alumno)
	{
		$i=0;
		foreach ($_POST["paralelo"] as $paralelo) {
			if ($paralelo !== '-1') {
				$mateprog[] = $paralelo;
			}
		}

		// print_r($mateprog);
		// exit();

		// $id_alumno = $this->Alumno_model->get_idalumno($_SESSION['user_id']);
		// $id_gestionperiodo = $this->Alumno_model->get_gestion_matricula($id_alumno)->id_gestionperiodo;
		// $asignaturas_programadas = $this->Alumno_model->get_materiasprogramadas($id_alumno, $id_gestionperiodo);

		$ultimo_gestionperiodo = $this->Admin_model->ultimogestion();
		$asignaturas_programadas = $this->Alumno_model->get_materiasprogramadas_admin($id_alumno, $ultimo_gestionperiodo->gestion);
		
		// array $si donde estan las materias que debemos borrar
		$si = array();
		// array $no donde esta todos las materias que nos hemos programado
		$no = array();
		if ( !empty($_POST["asignaturas"]) && is_array($_POST["asignaturas"]) ) {
			if ( !empty($_POST["confirmar"]) && is_array($_POST["confirmar"]) ) { 
				if (!empty($mateprog) && is_array($mateprog)) {

					$this->Alumno_model->eliminar_materia_programada_por_idmateria_revisar($id_alumno, $ultimo_gestionperiodo->id_gestionperiodo);

					foreach ($_POST["asignaturas"] as $asignatura) {
						$mate_prog = $this->Alumno_model->get_materiasprogramadas_admin($id_alumno, $ultimo_gestionperiodo->gestion);

						$dataAsignacionParalelo = array(
							'id_paralelo' => $mateprog[$i],
							'id_materia' => $asignatura,
							'estado' => 'S'
						);
						$dataProgramacion = array(
							'id_gestionperiodo' => $ultimo_gestionperiodo->id_gestionperiodo,
							'id_alumno' => $id_alumno,
							// 'id_asignacionparalelo' => $mateprog[$i],
						);

						$this->Alumno_model->save_programation($dataProgramacion, $dataAsignacionParalelo);
						$i++;
					}





					// foreach ($asignaturas_programadas as $asig) {
					// 	$no[] = $asig->id_materia;
					// }

					// if (count($_POST["asignaturas"]) == count($no)) {
					// 	if ($_POST["asignaturas"][0] == $no[0]) {
					// 		echo json_encode(array('status'=> 'no'));
					// 		exit;
					// 	} else {
					// 		$mate_prog = $this->Alumno_model->get_materiasprogramadas_admin($id_alumno, $ultimo_gestionperiodo->gestion);
					// 		print_r($mate_prog);
					// 		exit();

					// 		$this->Alumno_model->eliminar_materia_programada_por_idmateria($mate_prog[0]->id_asignacionparalelo, $id_alumno, $ultimo_gestionperiodo->id_gestionperiodo, $mate_prog[0]->id_materia);

					// 		$dataAsignacionParalelo = array(
					// 			'id_paralelo' => $mateprog[$i],
					// 			'id_materia' => $_POST["asignaturas"][0],
					// 			'estado' => 'S'
					// 		);
					// 		$dataProgramacion = array(
					// 			'id_gestionperiodo' => $ultimo_gestionperiodo->id_gestionperiodo,
					// 			'id_alumno' => $id_alumno,
					// 			// 'id_asignacionparalelo' => $mateprog[$i],
					// 		);

					// 		$this->Alumno_model->save_programation($dataProgramacion, $dataAsignacionParalelo);
					// 	}
					// } elseif (count($_POST["asignaturas"]) > count($no)) {
					// 	$si = array_diff($_POST["asignaturas"], $no);
					// } else{
					// 	$si = array_diff($no, $_POST["asignaturas"]);
					// }

					// // if (count($_POST["asignaturas"]) == count($no)) {
					// // 	echo json_encode(array('status'=> 'no'));
					// // 	exit;
					// // } elseif (count($_POST["asignaturas"]) > count($no)) {
					// // 	$si = array_diff($_POST["asignaturas"], $no);
					// // } else{
					// // 	$si = array_diff($no, $_POST["asignaturas"]);
					// // }

					// foreach ($si as $id_materia) {;
					// 	$materia_programada = $this->Alumno_model->comprobar_si_materiestaprogramada($id_alumno, $ultimo_gestionperiodo->id_gestionperiodo, $id_materia);

					// 	if ($materia_programada) {
					// 		// la materia ya ha sido programada asi que eliminar
					// 		$asignacionParalelo = $this->Alumno_model->programacionasignacionparalelo($id_alumno, $ultimo_gestionperiodo->id_gestionperiodo, $id_materia);
					// 		foreach ($asignacionParalelo as $value) {
					// 			$this->Alumno_model->eliminar_materia_programada_por_idmateria($value->id_asignacionparalelo, $id_alumno, $ultimo_gestionperiodo->id_gestionperiodo, $id_materia);
					// 		}
					// 	} else {
					// 		// la materia no existe, entonces programar materia
					// 		$dataAsignacionParalelo = array(
					// 			'id_paralelo' => $mateprog[$i],
					// 			'id_materia' => $id_materia,
					// 			'estado' => 'S'
					// 		);
					// 		$dataProgramacion = array(
					// 			'id_gestionperiodo' => $ultimo_gestionperiodo->id_gestionperiodo,
					// 			'id_alumno' => $id_alumno,
					// 			// 'id_asignacionparalelo' => $mateprog[$i],
					// 		);
					// 		// $dataAsignaturaProgramacion = array(
					// 		// 	'id_materia' => $id_materia,
					// 		// 	'id_gestionperiodo' => $id_gestionperiodo,
					// 		// 	'id_alumno' => $id_alumno,
					// 		// 	'id_paralelo' => $mateprog[$i],
					// 		// );
					// 		$this->Alumno_model->save_programation($dataProgramacion, $dataAsignacionParalelo);
					// 		// print_r($dataAsignacionParalelo);
					// 	}
					// 	$i++;
					// }


					echo json_encode(array('status'=>TRUE));
				} else {
					echo json_encode(array('status'=>FALSE, 'error'=>'Debe seleccionar el paralelo'));
				}
			} else {
				echo json_encode(array('status'=>FALSE, 'error'=>'Debe confirmar las asignaturas.'));
			}
		} else {
			echo json_encode(array('status'=>FALSE,'error'=>'Debe seleccionar por lo menos una asignatura'));
		}
	}


	// copia funcionando bien 
	// esta funcion es la que debemos modifcar para reprogramar estudiantes
	// public function ajax_update_programacion_admin($id_alumno)
	// {
	// 	$i=0;
	// 	foreach ($_POST["paralelo"] as $paralelo) {
	// 		if ($paralelo !== '-1') {
	// 			$mateprog[] = $paralelo;
	// 		}
	// 	}

	// 	// print_r($mateprog);
	// 	// exit();

	// 	// $id_alumno = $this->Alumno_model->get_idalumno($_SESSION['user_id']);
	// 	// $id_gestionperiodo = $this->Alumno_model->get_gestion_matricula($id_alumno)->id_gestionperiodo;
	// 	// $asignaturas_programadas = $this->Alumno_model->get_materiasprogramadas($id_alumno, $id_gestionperiodo);

	// 	$ultimo_gestionperiodo = $this->Admin_model->ultimogestion();
	// 	$asignaturas_programadas = $this->Alumno_model->get_materiasprogramadas_admin($id_alumno, $ultimo_gestionperiodo->gestion);
		
	// 	// array $si donde estan las materias que debemos borrar
	// 	$si = array();
	// 	// array $no donde esta todos las materias que nos hemos programado
	// 	$no = array();
	// 	if ( !empty($_POST["asignaturas"]) && is_array($_POST["asignaturas"]) ) {
	// 		if ( !empty($_POST["confirmar"]) && is_array($_POST["confirmar"]) ) { 
	// 			if (!empty($mateprog) && is_array($mateprog)) {
	// 				foreach ($asignaturas_programadas as $asig) {
	// 					$no[] = $asig->id_materia;
	// 				}

	// 				if (count($_POST["asignaturas"]) == count($no)) {
	// 					if ($_POST["asignaturas"][0] == $no[0]) {
	// 						echo json_encode(array('status'=> 'no'));
	// 						exit;
	// 					} else {
	// 						$mate_prog = $this->Alumno_model->get_materiasprogramadas_admin($id_alumno, $ultimo_gestionperiodo->gestion);
	// 						print_r($mate_prog);
	// 						exit();

	// 						$this->Alumno_model->eliminar_materia_programada_por_idmateria($mate_prog[0]->id_asignacionparalelo, $id_alumno, $ultimo_gestionperiodo->id_gestionperiodo, $mate_prog[0]->id_materia);

	// 						$dataAsignacionParalelo = array(
	// 							'id_paralelo' => $mateprog[$i],
	// 							'id_materia' => $_POST["asignaturas"][0],
	// 							'estado' => 'S'
	// 						);
	// 						$dataProgramacion = array(
	// 							'id_gestionperiodo' => $ultimo_gestionperiodo->id_gestionperiodo,
	// 							'id_alumno' => $id_alumno,
	// 							// 'id_asignacionparalelo' => $mateprog[$i],
	// 						);

	// 						$this->Alumno_model->save_programation($dataProgramacion, $dataAsignacionParalelo);
	// 					}
	// 				} elseif (count($_POST["asignaturas"]) > count($no)) {
	// 					$si = array_diff($_POST["asignaturas"], $no);
	// 				} else{
	// 					$si = array_diff($no, $_POST["asignaturas"]);
	// 				}

	// 				// if (count($_POST["asignaturas"]) == count($no)) {
	// 				// 	echo json_encode(array('status'=> 'no'));
	// 				// 	exit;
	// 				// } elseif (count($_POST["asignaturas"]) > count($no)) {
	// 				// 	$si = array_diff($_POST["asignaturas"], $no);
	// 				// } else{
	// 				// 	$si = array_diff($no, $_POST["asignaturas"]);
	// 				// }

	// 				foreach ($si as $id_materia) {;
	// 					$materia_programada = $this->Alumno_model->comprobar_si_materiestaprogramada($id_alumno, $ultimo_gestionperiodo->id_gestionperiodo, $id_materia);

	// 					if ($materia_programada) {
	// 						// la materia ya ha sido programada asi que eliminar
	// 						$asignacionParalelo = $this->Alumno_model->programacionasignacionparalelo($id_alumno, $ultimo_gestionperiodo->id_gestionperiodo, $id_materia);
	// 						foreach ($asignacionParalelo as $value) {
	// 							$this->Alumno_model->eliminar_materia_programada_por_idmateria($value->id_asignacionparalelo, $id_alumno, $ultimo_gestionperiodo->id_gestionperiodo, $id_materia);
	// 						}
	// 					} else {
	// 						// la materia no existe, entonces programar materia
	// 						$dataAsignacionParalelo = array(
	// 							'id_paralelo' => $mateprog[$i],
	// 							'id_materia' => $id_materia,
	// 							'estado' => 'S'
	// 						);
	// 						$dataProgramacion = array(
	// 							'id_gestionperiodo' => $ultimo_gestionperiodo->id_gestionperiodo,
	// 							'id_alumno' => $id_alumno,
	// 							// 'id_asignacionparalelo' => $mateprog[$i],
	// 						);
	// 						// $dataAsignaturaProgramacion = array(
	// 						// 	'id_materia' => $id_materia,
	// 						// 	'id_gestionperiodo' => $id_gestionperiodo,
	// 						// 	'id_alumno' => $id_alumno,
	// 						// 	'id_paralelo' => $mateprog[$i],
	// 						// );
	// 						$this->Alumno_model->save_programation($dataProgramacion, $dataAsignacionParalelo);
	// 						// print_r($dataAsignacionParalelo);
	// 					}
	// 					$i++;
	// 				}
	// 				echo json_encode(array('status'=>TRUE));
	// 			} else {
	// 				echo json_encode(array('status'=>FALSE, 'error'=>'Debe seleccionar el paralelo'));
	// 			}
	// 		} else {
	// 			echo json_encode(array('status'=>FALSE, 'error'=>'Debe confirmar las asignaturas.'));
	// 		}
	// 	} else {
	// 		echo json_encode(array('status'=>FALSE,'error'=>'Debe seleccionar por lo menos una asignatura'));
	// 	}
	// }

	// public function ajax_update_programacion_admin($id_alumno)
	// {
	// 	$i=0;
	// 	foreach ($_POST["paralelo"] as $paralelo) {
	// 		if ($paralelo !== '-1') {
	// 			$mateprog[] = $paralelo;
	// 		}
	// 	}

	// 	// $id_alumno = $this->Alumno_model->get_idalumno($_SESSION['user_id']);
	// 	$id_gestionperiodo = $this->Alumno_model->get_gestion_matricula($id_alumno)->id_gestionperiodo;
	// 	$asignaturas_programadas = $this->Alumno_model->get_materiasprogramadas($id_alumno, $id_gestionperiodo);
		
	// 	// array $si donde estan las materias que debemos borrar
	// 	$si = array();
	// 	// array donde esta todos las materias que nos hemos programado
	// 	$no = array();
	// 	if ( !empty($_POST["asignaturas"]) && is_array($_POST["asignaturas"]) ) {
	// 		if ( !empty($_POST["confirmar"]) && is_array($_POST["confirmar"]) ) { 
	// 			if (!empty($mateprog) && is_array($mateprog)) {
	// 				foreach ($asignaturas_programadas as $asig) {
	// 					$no[] = $asig->id_materia;
	// 				}

	// 				if (count($_POST["asignaturas"]) == count($no)) {
	// 					if ($_POST["asignaturas"][0] == $no[0]) {
	// 						echo json_encode(array('status'=> 'no'));
	// 						exit;
	// 					} else {
	// 						$mate_prog = $this->Alumno_model->get_materiasprogramadas_admin($id_alumno, $id_gestionperiodo);

	// 						$this->Alumno_model->eliminar_materia_programada_por_idmateria($mate_prog[0]->id_asignacionparalelo, $id_alumno, $id_gestionperiodo, $mate_prog[0]->id_materia);

	// 						$dataAsignacionParalelo = array(
	// 							'id_paralelo' => $mateprog[$i],
	// 							'id_materia' => $_POST["asignaturas"][0],
	// 							'estado' => 'S'
	// 						);
	// 						$dataProgramacion = array(
	// 							'id_gestionperiodo' => $id_gestionperiodo,
	// 							'id_alumno' => $id_alumno,
	// 							// 'id_asignacionparalelo' => $mateprog[$i],
	// 						);

	// 						$this->Alumno_model->save_programation($dataProgramacion, $dataAsignacionParalelo);
	// 					}
	// 				} elseif (count($_POST["asignaturas"]) > count($no)) {
	// 					$si = array_diff($_POST["asignaturas"], $no);
	// 				} else{
	// 					$si = array_diff($no, $_POST["asignaturas"]);
	// 				}

	// 				// if (count($_POST["asignaturas"]) == count($no)) {
	// 				// 	echo json_encode(array('status'=> 'no'));
	// 				// 	exit;
	// 				// } elseif (count($_POST["asignaturas"]) > count($no)) {
	// 				// 	$si = array_diff($_POST["asignaturas"], $no);
	// 				// } else{
	// 				// 	$si = array_diff($no, $_POST["asignaturas"]);
	// 				// }

	// 				foreach ($si as $id_materia) {;
	// 					$materia_programada = $this->Alumno_model->comprobar_si_materiestaprogramada($id_alumno, $id_gestionperiodo, $id_materia);

	// 					if ($materia_programada) {
	// 						// la materia ya ha sido programada asi que eliminar
	// 						$asignacionParalelo = $this->Alumno_model->programacionasignacionparalelo($id_alumno, $id_gestionperiodo, $id_materia);
	// 						foreach ($asignacionParalelo as $value) {
	// 							$this->Alumno_model->eliminar_materia_programada_por_idmateria($value->id_asignacionparalelo, $id_alumno, $id_gestionperiodo, $id_materia);
	// 						}
	// 					} else {
	// 						// la materia no existe, entonces programar materia
	// 						$dataAsignacionParalelo = array(
	// 							'id_paralelo' => $mateprog[$i],
	// 							'id_materia' => $id_materia,
	// 							'estado' => 'S'
	// 						);
	// 						$dataProgramacion = array(
	// 							'id_gestionperiodo' => $id_gestionperiodo,
	// 							'id_alumno' => $id_alumno,
	// 							// 'id_asignacionparalelo' => $mateprog[$i],
	// 						);
	// 						// $dataAsignaturaProgramacion = array(
	// 						// 	'id_materia' => $id_materia,
	// 						// 	'id_gestionperiodo' => $id_gestionperiodo,
	// 						// 	'id_alumno' => $id_alumno,
	// 						// 	'id_paralelo' => $mateprog[$i],
	// 						// );
	// 						$this->Alumno_model->save_programation($dataProgramacion, $dataAsignacionParalelo);
	// 						// print_r($dataAsignacionParalelo);
	// 					}
	// 					$i++;
	// 				}
	// 				echo json_encode(array('status'=>TRUE));
	// 			} else {
	// 				echo json_encode(array('status'=>FALSE, 'error'=>'Debe seleccionar el paralelo'));
	// 			}
	// 		} else {
	// 			echo json_encode(array('status'=>FALSE, 'error'=>'Debe confirmar las asignaturas.'));
	// 		}
	// 	} else {
	// 		echo json_encode(array('status'=>FALSE,'error'=>'Debe seleccionar por lo menos una asignatura'));
	// 	}
	// }
	///////////////////////////////////////////////////////////////////////////////////////////////

	public function validateboletin()
    {
    	$this->form_validation->set_error_delimiters('','');
    	$rules = getBoletinRules();
    	$this->form_validation->set_rules($rules);
    	if ($this->form_validation->run() === FALSE) {
    		$data = array();
			$data['error_string'] = array();
			$data['inputerror'] = array();
			$data['status'] = TRUE;

			if(form_error('ci') != '')
			{
				$data['inputerror'][] = 'ci';
				$data['error_string'][] = form_error('ci');
				$data['status'] = FALSE;
			}

			if(form_error('dob') != '')
			{
				$data['inputerror'][] = 'dob';
				$data['error_string'][] = form_error('dob');
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
