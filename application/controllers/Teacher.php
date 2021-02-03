<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teacher extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Teacher_model','Teacher_model');
		$this->load->model('Admin_model', 'Admin_model');
	}

	function salida($data)
    {
        $this->load->view('template/header');
        $this->load->view($data['vista'],$data);
        $this->load->view('template/footer');
    }

	public function index()
	{
		if ($_SESSION['is_type_user'] == 'is_teacher' || $_SESSION['is_type_user'] == 'is_docente' || $_SESSION['is_type_user'] == 'is_director' || $_SESSION['is_type_user'] == 'is_rector')
		{
			$data['vista'] = 'teacher/v_up_notes';
			$this->salida($data);
		}
        else {
			redirect(base_url() . '', 'refresh');
        }
	}

	////////////////////////////notas de alumnos////////////////////////////
	public function get_carreras()
	{
		$id_docente = $this->Teacher_model->get_iddocente($_SESSION['user_id']);
		$carrera = $this->Teacher_model->get_carreras($id_docente);
		echo json_encode($carrera);
	}

	public function get_materias()
	{
		$id_docente = $this->Teacher_model->get_iddocente($_SESSION['user_id']);
		$id_carrera = $this->input->post('id_carrera');
		$materias = $this->Teacher_model->get_materias($id_docente, $id_carrera);
		echo json_encode($materias);
	}

	public function get_paralelos()
	{
		$id_docente = $this->Teacher_model->get_iddocente($_SESSION['user_id']);
		$id_materia = $this->input->post('id_materia');
		$id_carrera = $this->input->post('id_carrera');
		$paralelos = $this->Teacher_model->get_paralelos($id_docente, $id_carrera, $id_materia);
		echo json_encode($paralelos);
	}

	// verifica si un determinado bimestre esta con 1
	public function vbh($id_carrera, $id_materia, $id_paralelo, $bim)
	{
		$bimestre = $this->Teacher_model->get_habilitar_bimestre($id_carrera, $id_materia, $id_paralelo)->bimestre;
		$deco = json_decode($bimestre, TRUE);
		foreach ($deco as $key) {
			$eso[] = $key;
		}
		if ($eso[$bim-1] == 1) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function get_studen_with_notes()
	{
		$id_carrera = $this->input->post('carreras');
		$id_materia = $this->input->post('materias');
		$id_paralelo = $this->input->post('paralelos');
		// print_r($id_carrera);
		// 1 12 9
		$note = '';
		$id_gestionperiodo = $this->Teacher_model->get_gestion_actual()->id_gestionperiodo;
		$students = $this->Teacher_model->get_students($id_carrera, $id_materia, $id_paralelo, $id_gestionperiodo);

		if (count($students) != 0) {
			$estados = $this->Admin_model->get_bimestre_by_bimestre();
			for ($i=0; $i < count($estados); $i++) { 
				switch ($estados[$i]->id_activebim) {
					case 1:
						$ppactive = $estados[$i]->estado == 1 || $this->vbh($id_carrera, $id_materia, $id_paralelo, $estados[$i]->id_activebim) ? 'enabled' : 'disabled';
						break;
					case 2:
						$spactive = $estados[$i]->estado == 1 || $this->vbh($id_carrera, $id_materia, $id_paralelo, $estados[$i]->id_activebim) ? 'enabled' : 'disabled';
						break;
					case 3:
						$tpactive = $estados[$i]->estado == 1 || $this->vbh($id_carrera, $id_materia, $id_paralelo, $estados[$i]->id_activebim) ? 'enabled' : 'disabled';
						break;
					case 4:
						$cpactive = $estados[$i]->estado == 1 || $this->vbh($id_carrera, $id_materia, $id_paralelo, $estados[$i]->id_activebim) ? 'enabled' : 'disabled';
						break;
					case 5:
						$factive = $estados[$i]->estado == 1 || $this->vbh($id_carrera, $id_materia, $id_paralelo, $estados[$i]->id_activebim) ? 'enabled' : 'disabled';
						break;
					default:
						$stactive = $estados[$i]->estado == 1 || $this->vbh($id_carrera, $id_materia, $id_paralelo, $estados[$i]->id_activebim) ? 'enabled' : 'disabled';
						break;
				}
			}
			foreach ($students as $student) {
				$notas = $this->Teacher_model->get_note($student->id_alumno, $id_materia, $id_paralelo, $id_gestionperiodo);
				// print_r($notas);
				// exit();
				if (empty($notas)){
					$background = 'bg-aqua';
				} else {
					if ($this->encrypt->decode($notas->final) <= 40) {
						$background = 'danger';
					} elseif ($this->encrypt->decode($notas->final) <= 60) {
						$background = 'warning';
					} else {
						$background = 'success';
					}
				}
				// $background = $this->encrypt->decode($notas->final) <= 40 ? 'danger' : '';
				$note .='<tr class="'.$background.'">';
				if (count($notas) != 0) {
					$note .='<td>'.$student->apellido_paterno.' '.$student->apellido_materno.' '.$student->nombres.'</td>';
					$note .='<td class="clase_id_alumno"><input name="alumno[]" id="alumno'.$student->id_alumno.'" type="text" value="'.$student->id_alumno.'"></td>';
					if ($this->encrypt->decode($notas->primer_bim) != 0 ) {
						$note .='<td class="primer_bim"><input name="primer_bim" id="primer_bim'.$student->id_alumno.'" type="text" class="form-control" style="text-align:right;" value="'.$this->encrypt->decode($notas->primer_bim).'" '.$ppactive.'></td>';
					} else {
						$note .='<td class="primer_bim"><input name="primer_bim" id="primer_bim'.$student->id_alumno.'" type="text" class="form-control" style="text-align:right;" value="0" '.$ppactive.'></td>';
					}
					if ($this->encrypt->decode($notas->segundo_bim) != 0 ) {
						$note .='<td class="segundo_bim"><input name="segundo_bim" id="segundo_bim'.$student->id_alumno.'" type="text" class="form-control" style="text-align:right;" value="'.$this->encrypt->decode($notas->segundo_bim).'" '.$spactive.'></td>';
					} else {
						$note .='<td class="segundo_bim"><input name="segundo_bim" id="segundo_bim'.$student->id_alumno.'" type="text" class="form-control" style="text-align:right;" value="0" '.$spactive.'></td>';
					}
					if ($this->encrypt->decode($notas->tercer_bim) != 0 ) {
						$note .='<td class="tercer_bim"><input name="tercer_bim" id="tercer_bim'.$student->id_alumno.'" type="text" class="form-control" style="text-align:right;" value="'.$this->encrypt->decode($notas->tercer_bim).'" '.$tpactive.'></td>';
					} else {
						$note .='<td class="tercer_bim"><input name="tercer_bim" id="tercer_bim'.$student->id_alumno.'" type="text" class="form-control" style="text-align:right;" value="0" '.$tpactive.'></td>';
					}
					if ($this->encrypt->decode($notas->cuarto_bim) != 0 ) {
						$note .='<td class="cuarto_bim"><input name="cuarto_bim" id="cuarto_bim'.$student->id_alumno.'" type="text" class="form-control" style="text-align:right;" value="'.$this->encrypt->decode($notas->cuarto_bim).'" '.$cpactive.'></td>';
					} else {
						$note .='<td class="cuarto_bim"><input name="cuarto_bim" id="cuarto_bim'.$student->id_alumno.'" type="text" class="form-control" style="text-align:right;" value="0" '.$cpactive.'></td>';
					}
					if ($this->encrypt->decode($notas->final) != 0 ) {
						$note .='<td class="final"><input name="final" id="final'.$student->id_alumno.'" type="text" class="form-control" style="text-align:right;" value="'.($this->encrypt->decode($notas->final)).'" '.$factive.'></td>';
					} else {
						$note .='<td class="final"><input name="final" id="final'.$student->id_alumno.'" type="text" class="form-control" style="text-align:right;" value="0"'.$factive.'></td>';
					}
					if ($this->encrypt->decode($notas->segundo_turno) != 0 ) {
						$note .='<td class="segundo_turno"><input name="segundo_turno" id="segundo_turno'.$student->id_alumno.'" type="text" class="form-control" style="text-align:right;" value="'.$this->encrypt->decode($notas->segundo_turno).'"'.$stactive.'></td>';
					} else {
						$note .='<td class="segundo_turno"><input name="segundo_turno" id="segundo_turno'.$student->id_alumno.'" type="text" class="form-control" style="text-align:right;" value="0"'.$stactive.'></td>';
					}
				} else {
					$note .='<td>'.$student->apellido_paterno.' '.$student->apellido_materno.' '.$student->nombres.'</td>';
					$note .='<td class="clase_id_alumno"><input name="alumno[]" type="text" id="'.$student->id_alumno.'" value="'.$student->id_alumno.'"></td>';
					$note .='<td class="primer_bim"><input name="primer_bim" id="primer_bim'.$student->id_alumno.'" type="text" class="form-control" style="text-align:right;" value="0"'.$ppactive.'></td>';
					$note .='<td class="segundo_bim"><input name="segundo_bim" id="segundo_bim'.$student->id_alumno.'" type="text" class="form-control" style="text-align:right;" value="0"'.$spactive.'></td>';
					$note .='<td class="tercer_bim"><input name="tercer_bim" id="tercer_bim'.$student->id_alumno.'" type="text" class="form-control" style="text-align:right;" value="0"'.$tpactive.'></td>';
					$note .='<td class="cuarto_bim"><input name="cuarto_bim" id="cuarto_bim'.$student->id_alumno.'" type="text" class="form-control" style="text-align:right;" value="0"'.$cpactive.'></td>';
					$note .='<td class="final"><input name="final" id="final'.$student->id_alumno.'" type="text" class="form-control" style="text-align:right;" value="0"'.$factive.'></td>';
					$note .='<td class="segundo_turno"><input name="segundo_turno" id="segundo_turno'.$student->id_alumno.'" type="text" class="form-control" style="text-align:right;" value="0"'.$stactive.'></td>';
				}
				$note .='<tr>';
			}
			$notes = array('note' => $note, 'status'=> TRUE);
		} else {
			$notes = array('status' => FALSE);
		}
		echo json_encode($notes);
	}

	public function save_notes()
	{
		if ( !empty($_POST["id_carrera"]) && isset($_POST["id_carrera"]) ) {
			if ( !empty($_POST["id_materia"]) && isset($_POST["id_materia"]) ) {
				if ( !empty($_POST["id_paralelo"]) && isset($_POST["id_paralelo"]) ) {
					if ( !empty($_POST['id_alumno']) && isset($_POST["id_alumno"]) ) {
						$dataAsignacionparalelo = array(
							'id_materia' => $_POST['id_materia'],
							'id_paralelo' => $_POST['id_paralelo'],
						);
						// $id_asignacionparalelo = $this->Teacher_model->save_asignacionparalelo($dataAsignacionparalelo);

						$dataProgramacion =array(
							'id_alumno' => $_POST['id_alumno'],
							// 'id_gestionperiodo' => $id_gestionperiodo,
							// 'id_asignacionparalelo' => $id_asignacionparalelo,
						);
						// $id_programacion = $this->Teacher_model->save_programacion($dataProgramacion);

						$dataNote = array(
							// 'id_programacion' => $id_programacion,
							'primer_bim' => $this->encrypt->encode(strip_tags(json_decode($_POST["notas"])->primer_bim)),
							'segundo_bim' => $this->encrypt->encode(strip_tags(json_decode($_POST["notas"])->segundo_bim)),
							'tercer_bim' => $this->encrypt->encode(strip_tags(json_decode($_POST["notas"])->tercer_bim)),
							'cuarto_bim' => $this->encrypt->encode(strip_tags(json_decode($_POST["notas"])->cuarto_bim)),
							'final' => $this->encrypt->encode(strip_tags(json_decode($_POST["notas"])->final)),
							'segundo_turno' => $this->encrypt->encode(strip_tags(json_decode($_POST["notas"])->segundo_turno)),
						);
						if ($this->Teacher_model->save_note($this->security->xss_clean($dataNote), $dataAsignacionparalelo, $dataProgramacion)){
							echo json_encode(array('status'=>TRUE));
						} else {
							echo json_encode(array('status'=>FALSE, 'error'=>'La nota no se guardo'));
						}
					} else {
						echo json_encode(array('status'=>FALSE, 'error'=>'Los datos de alumno se desconoce'));
					}
				} else {
					echo json_encode(array('status'=>FALSE, 'error'=>'Debe seleccionar el paralelo'));
				}
			} else {
				echo json_encode(array('status'=>FALSE, 'error'=>'No se ha seleccionado la materia'));
			}
		} else {
			echo json_encode(array('status'=>FALSE, 'error'=>'No se ha seleccionado la carrera'));
		}
	}

	// public function if_save_notes()
	// {
	// 	$id_gestionperiodo = $this->Teacher_model->get_gestion_actual()->id_gestionperiodo;
	// 	$id_materia = $_POST["id_materia"];
	// 	$id_paralelo = $_POST["id_paralelo"];
	// 	$id_alumno = $_POST["id_alumno"];
	// 	$num_registro = $this->Teacher_model->if_exist_notes($id_materia,$id_paralelo,$id_gestionperiodo, $id_alumno);
	// 	if ($num_registro != 0) {
	// 		$this->update_notes();
	// 	} else {
	// 		$this->save_notes();
	// 	}
	// }

	public function if_save_notes()
	{
		$id_gestionperiodo = $this->Teacher_model->get_gestion_actual()->id_gestionperiodo;
		$id_materia = $_POST["id_materia"];
		$id_paralelo = $_POST["id_paralelo"];
		$id_alumno = $_POST["id_alumno"];
		$num_registro = $this->Teacher_model->if_exist_notes($id_materia,$id_paralelo,$id_gestionperiodo,$id_alumno);
		if ($num_registro != 0) {
			$this->update_notes();
		} else {
			$this->save_notes();
		}
	}

	public function update_notes()
	{
		// $nota = json_decode($this->input->post('notas'));
		// print_r($_POST['id_alumno']);
		// print_r(json_decode($_POST["notas"])->primer_bim);
		// print_r($_POST["id_carrera"]);
		// print_r($_POST["id_materia"]);
		// print_r($nota);
		// exit();

		if ( !empty($_POST["id_carrera"]) && isset($_POST["id_carrera"]) ) {
			if ( !empty($_POST["id_materia"]) && isset($_POST["id_materia"]) ) {
				if ( !empty($_POST["id_paralelo"]) && isset($_POST["id_paralelo"]) ) {
					if ( !empty($_POST['id_alumno']) && isset($_POST["id_alumno"]) ) {
						
						$dataNote = array();
						$estados = $this->Admin_model->get_bimestre_by_bimestre();
						for ($i=0; $i < count($estados); $i++) { 
							switch ($estados[$i]->id_activebim) {
								case 1:
									$estados[$i]->estado == 1 || $this->vbh($_POST["id_carrera"], $_POST["id_materia"], $_POST["id_paralelo"], $estados[$i]->id_activebim) ? $dataNote['primer_bim'] = $this->encrypt->encode(strip_tags(json_decode($_POST["notas"])->primer_bim)) : '';
									break;
								case 2:
									$estados[$i]->estado == 1 || $this->vbh($_POST["id_carrera"], $_POST["id_materia"], $_POST["id_paralelo"], $estados[$i]->id_activebim) ? $dataNote['segundo_bim'] = $this->encrypt->encode(strip_tags(json_decode($_POST["notas"])->segundo_bim)) : '';
									break;
								case 3:
									$estados[$i]->estado == 1 || $this->vbh($_POST["id_carrera"], $_POST["id_materia"], $_POST["id_paralelo"], $estados[$i]->id_activebim) ? $dataNote['tercer_bim'] = $this->encrypt->encode(strip_tags(json_decode($_POST["notas"])->tercer_bim)) : '';
									break;
								case 4:
									$estados[$i]->estado == 1 || $this->vbh($_POST["id_carrera"], $_POST["id_materia"], $_POST["id_paralelo"], $estados[$i]->id_activebim) ? $dataNote['cuarto_bim'] = $this->encrypt->encode(strip_tags(json_decode($_POST["notas"])->cuarto_bim)) : '';
									break;
								case 5:
									$estados[$i]->estado == 0 ? $dataNote['final'] = $this->encrypt->encode(strip_tags(json_decode($_POST["notas"])->final)) : '';
									// $estados[$i]->estado == 0 || ($this->vbh($_POST["id_carrera"], $_POST["id_materia"], $_POST["id_paralelo"], $estados[$i]->id_activebim)) ? $dataNote['final'] = $this->encrypt->encode(strip_tags(json_decode($_POST["notas"])->final)) : '';
									break;
								default:
									$estados[$i]->estado == 1 || $this->vbh($_POST["id_carrera"], $_POST["id_materia"], $_POST["id_paralelo"], $estados[$i]->id_activebim) ? $dataNote['segundo_turno'] = $this->encrypt->encode(strip_tags(json_decode($_POST["notas"])->segundo_turno)) : '';
									break;
							}
						}

						if ($this->Teacher_model->notes_update($dataNote, $_POST['id_alumno'], $_POST['id_materia'], $_POST['id_paralelo'])){
							echo json_encode(array('status'=>TRUE));
						} else {
							echo json_encode(array('status'=>FALSE, 'error'=>'No se ha podido actualizar las notas.'));
						}
						
					} else {
						echo json_encode(array('status'=>FALSE, 'error'=>'Los datos de alumno se desconocoe'));
					}
				} else {
					echo json_encode(array('status'=>FALSE, 'error'=>'Debe seleccionar el paralelo'));
				}
			} else {
				echo json_encode(array('status'=>FALSE, 'error'=>'No se ha seleccionado la materia'));
			}
		} else {
			echo json_encode(array('status'=>FALSE, 'error'=>'No se ha seleccionado la carrera'));
		}
	}

	////////////////////////////notas de alumnos////////////////////////////


 

 //----------------------------PDF Inicio lista de estudiantes por materia, paralelo, carrera--------------

 	public function pdf_lista($id_carrera,$id_materia,$id_paralelo)
	{
		if (($_SESSION['is_type_user'] == 'is_director') || ($_SESSION['is_type_user'] == 'is_teacher')|| ($_SESSION['is_type_user'] == 'is_docente')){

		$this->load->library('pdf');
		$pdf = $this->pdf->load();
		$note = '';
		$id_gestionperiodo = $this->Teacher_model->get_gestion_actual()->id_gestionperiodo;
		$students = $this->Teacher_model->get_students($id_carrera, $id_materia, $id_paralelo, $id_gestionperiodo);
		$materia_nombre = $this->Teacher_model->get_materia_nombre($id_materia)->nombre;
		$sigla = $this->Teacher_model->get_materia_nombre($id_materia)->sigla;
		$paralelo_nombre = $this->Teacher_model->get_paralelo_nombre($id_paralelo)->nombre;
		$carrera_nombre = $this->Teacher_model->get_carrera_nombre($id_carrera)->nombre_carrera;

		$tz = 'America/La_Paz';
		$timestamp = time();
		$dt = new DateTime("now", new DateTimeZone($tz)); //first argument "must" be a string
		$dt->setTimestamp($timestamp); //adjust the object to correct timestamp

			
			
		$note .='
		<html>
		<title> '.$paralelo_nombre.'-'.$carrera_nombre.'-'.$sigla.'</title>
		<head>
		<style>


		.barcodecell {
		text-align: center;
		vertical-align: middle;
		padding: 0;
		}

		</style>
		</head>
		<body>
		<htmlpagefooter name="myfooter">
		<table width="100%" style="border-top: 1px solid #000000; vertical-align: top; font-family:
		serif; font-size: 9pt; color: #000;"><tr>
		<td width="50%" align="left"><div><span style="color:black; font-size:8pt; font-family:courier; font-weight: normal;
		font-style: normal;">Fecha impresion: '.$dt->format('d-m-Y, H:i:s').'</span></div></td>	 
		<td width="50%" align="right"><div><span style="color:black; font-size:8pt; font-family:courier; font-weight: normal;
		font-style: normal;">Pag. {PAGENO}</span></div></td>
		</tr></table>
		</htmlpagefooter>
		<sethtmlpagefooter name="myfooter" value="on" />
		';    
		$note .='<table border="1" >';    
		$note .='<thead>';    
		$note .='<tr  style="background: #e5e5e5;">';    
		$note .='<th>Nº</th>';    
		$note .='<th>Estudiante</th>';    
		$note .='<th>1º BI</th>';    
		$note .='<th>2º BI</th>';    
		$note .='<th>3º BI</th>';    
		$note .='<th>4º BI</th>';    
		$note .='<th>N. Final</th>';    
		$note .='<th>S. Turno</th>'; 
		$note .='</tr>';    
		$note .='</thead>';    
		$note .='<tbody>'; 


		$i=1; 
		if (count($students) != 0) {
			foreach ($students as $student) {
				$notas = $this->Teacher_model->get_note($student->id_alumno, $id_materia, $id_paralelo, $id_gestionperiodo);
				// print_r(($notas));
				// exit();
				$note .='<tr >';
				// $note .= form_open('#', array('id'=>'form_notes', "method"=>"POST"));
				if (count($notas) != 0) {
					$note .='<td>'.$i.'</td>';
					$note .='<td >'.$student->apellido_paterno.' '.$student->apellido_materno.' '.$student->nombres.'</td>';
					if ($this->encrypt->decode($notas->primer_bim) != 0 ) {
						$note .='<td align="center">'.$this->encrypt->decode($notas->primer_bim).'</td>';
					} else {
						$note .='<td align="center">0</td>';
					}
					if ($this->encrypt->decode($notas->segundo_bim) != 0 ) {
						$note .='<td align="center">'.$this->encrypt->decode($notas->segundo_bim).'</td>';
					
					} else {
						$note .='<td align="center">0</td>';
					}
					if ($this->encrypt->decode($notas->tercer_bim) != 0 ) {
						$note .='<td align="center">'.$this->encrypt->decode($notas->tercer_bim).'</td>';

					} else {
						$note .='<td align="center">0</td>';
					}
					if ($this->encrypt->decode($notas->cuarto_bim) != 0 ) {
						$note .='<td align="center">'.$this->encrypt->decode($notas->cuarto_bim).'</td>';
					} else {
						$note .='<td align="center">0</td>';
					}
					if ($this->encrypt->decode($notas->final) != 0 ) {
						$note .='<td align="center">'.round($this->encrypt->decode($notas->final)).'</td>';
					} else {
						$note .='<td align="center">0</td>';
					}
					if ($this->encrypt->decode($notas->segundo_turno) != 0 ) {
						$note .='<td align="center">'.$this->encrypt->decode($notas->segundo_turno).'</td>';
					} else {
						$note .='<td align="center">0</td>';
					}
					$i++;
				} 
				// $note .= form_close();
				
				$note .='</tr>';
			}
		}  
		$note .='</tbody></table>';


		$count_pb = 0;
		$count_pb_a = 0;
		$count_sb = 0;
		$count_sb_a = 0;
		$count_tb = 0;
		$count_tb_a = 0;
		$count_cb = 0;
		$count_cb_a = 0;
		$count_st = 0;
		$count_st_a = 0;

		foreach ($students as $student) {
			$notas = $this->Admin_model->get_note($student->id_alumno, $id_materia, $id_paralelo, $id_gestionperiodo);
			if ($this->encrypt->decode($notas->primer_bim) >= 61) {
				$count_pb++;
			} else {
				if (($this->encrypt->decode($notas->primer_bim) > 0) && ($this->encrypt->decode($notas->primer_bim) < 61))
				$count_pb_a++;
			}
			if ($this->encrypt->decode($notas->segundo_bim) >= 61) {
				$count_sb++;
			} else {
				if (($this->encrypt->decode($notas->segundo_bim) > 0) && ($this->encrypt->decode($notas->segundo_bim) < 61))
				$count_sb_a++;
			}
			if ($this->encrypt->decode($notas->tercer_bim) >= 61) {
				$count_tb++;
			} else {
				if (($this->encrypt->decode($notas->tercer_bim) > 0) && ($this->encrypt->decode($notas->tercer_bim) < 61))
				$count_tb_a++;
			}
			if ($this->encrypt->decode($notas->cuarto_bim) >= 61) {
				$count_cb++;
			} else {
				if (($this->encrypt->decode($notas->cuarto_bim) > 0) && ($this->encrypt->decode($notas->cuarto_bim) < 61))
				$count_cb_a++;
			}
			if ($this->encrypt->decode($notas->segundo_turno) >= 61) {
				$count_st++;
			} else {
				if (($this->encrypt->decode($notas->segundo_turno) > 0) && ($this->encrypt->decode($notas->segundo_turno) < 61))
				$count_st_a++;
			}
		}
		$note .='<br><br><br>';    
		$note .='<table  width="50%" align="center" >';    
		$note .='<thead>';    
		$note .='<tr  style="background: #e5e5e5;">';    
		$note .='<th></th>';    
		$note .='<th>1º BI</th>';    
		$note .='<th>2º BI</th>';    
		$note .='<th>3º BI</th>';    
		$note .='<th>4º BI</th>';    
		$note .='<th>S. Turno</th>'; 
		$note .='</tr>';    
		$note .='</thead>';    
		$note .='<tbody>'; 
		$note .='<tr style="background: #fcfafa;">';  
		$note .='<td align="center">Aprobados</td>';
		$note .='<td align="center">'.$count_pb.'</td>';
		$note .='<td align="center">'.$count_sb.'</td>';
		$note .='<td align="center">'.$count_tb.'</td>';
		$note .='<td align="center">'.$count_cb.'</td>';
		$note .='<td align="center">'.$count_st.'</td>';
		$note .='</tr>'; 
		$note .='<tr style="background: #b2afaf;">';
		$note .='<td align="center">Reprobados</td>';
		$note .='<td align="center">'.$count_pb_a.'</td>';
		$note .='<td align="center">'.$count_sb_a.'</td>';
		$note .='<td align="center">'.$count_tb_a.'</td>';
		$note .='<td align="center">'.$count_cb_a.'</td>';
		$note .='<td align="center">'.$count_st_a.'</td>';
		$note .='</tr>'; 
		$note .='</tbody></table>';


		$note .='</body>
				</html>';
		$pdf=new mPDF('c','LETTER','','',18,10,45,25,16,13);

		$pdf->SetDisplayMode('fullpage');
		$pdf->list_indent_first_level = 0; 
		$header = '
		<table width="100%" style="border-bottom: 1px solid #000000; vertical-align: bottom; font-family:
		serif; font-size: 9pt; color: #000;"><tr>
		<td width="20%" align="center"><img src="'.base_url().'/assets/images/image_header.png" width="120px" width="80px"/></td>
		<td width="50%" ><div><span style="color:black; font-size:8pt; font-family:courier; font-weight: normal;
		font-style: normal;"><b>Carrera: </b>'.$carrera_nombre.'</span></div><div><span style="color:black; font-size:8pt; font-family:courier; font-weight: normal;
		font-style: normal;"><b>Curso: </b>'.$paralelo_nombre.'</span></div><div><span style="color:black; font-size:8pt; font-family:courier; font-weight: normal;
		font-style: normal;"><b>Materia: </b>'.$materia_nombre.' <b>'.$sigla.'</b></span></div><div><span style="color:black; font-size:8pt; font-family:courier; font-weight: normal;
		font-style: normal;"><b>Docente: </b>'.$_SESSION["nombre_completo"].'</span></div></td>
		<td width="20%" class="barcodecell"><barcode code="'.$paralelo_nombre.'-'.$carrera_nombre.'-'.$sigla.'-'.$dt->format('d.m.Y, H:i:s').'" type="QR" class="barcode" size="0.8"
		error="M"/></td>
		</tr></table>
		';
		$pdf->SetHTMLHeader($header); 
			

		$pdf->WriteHTML($note);
		$pdf->Output(''.$paralelo_nombre.'-'.$carrera_nombre.'-'.$materia_nombre.'.pdf','I');
		exit;
	}else {
        redirect(base_url() . '', 'refresh');
    }

 	}

//---------------------------- PDF fin lista de estudiantes por materia, paralelo, carrera--------------



}
