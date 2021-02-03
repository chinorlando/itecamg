<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Centralizador extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Admin_model','Admin_model');
		$this->load->model('Centralizador_model','Centralizador_model');
	}

	public function salida($data)
    {
        $this->load->view('template/header');
        $this->load->view($data['vista'],$data);
        $this->load->view('template/footer');
    }

    public function index()
    {
        if (($_SESSION['is_type_user'] == 'is_admin') || ($_SESSION['is_type_user'] == 'is_secretaria') || ($_SESSION['is_type_user'] == 'is_rector') || $_SESSION['is_type_user'] == 'is_director'){
            $data['vista'] = 'v_centralizador';
            $this->salida($data);
        } else {
            redirect(base_url() . '', 'refresh');
        }
    }

    public function get_carreras()
    {
    	echo json_encode($this->Centralizador_model->get_carreras());
    }

    public function get_paralelos_by_carrera()
    {
    	$id_carrera = $this->input->post('id_carrera');

    	for ($i=1; $i < 4; $i++) { 
    		$paralelo[] = $this->Admin_model->query_get_paralelos($i, $id_carrera);
    	}

    	if (count($paralelo[0])==0) {
    		echo json_encode($no = 'vacio');
    	} else {
    		echo json_encode($paralelo);
    	}
    }

    public function get_plan()
    {
    	echo json_encode($this->Centralizador_model->get_planes());
    }

    public function get_year()
    {
    	echo json_encode($this->Centralizador_model->get_anios());
    }

    public function get_student_with_notes_and_carreras()
    {
    	$id_carrera = $this->input->post('carreras');
    	$id_paralelo = $this->input->post('paralelos');
    	$id_plan = $this->input->post('plan');
    	$bimestre = $this->input->post('bimestre');

    	$id_anio = $this->input->post('year');

    	$cent = '';

    	$materias = $this->Centralizador_model->get_materias($id_carrera, $id_paralelo, $id_plan);

		$cent .= '<table class="table table-xxs table-header-rotated">';
			$cent .= '<thead>';
				$cent .= '<tr>';
					$cent .= '<th class="text-center col-lg-1"><div>#</div></th>';
					$cent .= '<th class="text-center col-lg-4"><div>APELLIDOS Y NOMBRES</div></th>';
					foreach ($materias as $materia) {
						$cent .= '<th class="rotate col-lg-1"><div><h5 style="margin-top: 0px;margin-bottom: 0px;">'.$materia->nombre_materia.'</h5>'.$materia->nombre_completo.'</div></th>';
					}
					$cent .= '<th class="text-center col-lg-4"><div></div></th>';

				$cent .= '</tr>';
			$cent .= '</thead>';
			$cent .= '<tbody>';
				$alumnos = $this->Centralizador_model->get_alumnos_cent($id_carrera, $id_paralelo, $id_plan, $id_anio);
				$i = 1;
				foreach ($alumnos as $alumno) {
					$cent .= '<tr>';
						$cent .= '<td class="text-center col-lg-1">'.$i++.'</td>';
						$cent .= '<td class="col-lg-4">'.$alumno->nombre_completo.'</td>';
						foreach ($materias as $materia) {
							$notas = $this->Centralizador_model->get_notas_cent($id_plan, $id_carrera, $id_paralelo, $materia->id_materia, $alumno->id_alumno, $bimestre, $id_anio);

							if (empty($notas)) {
								$fondo = 'bg_default';
								$nota = "-";
							} else {
								if ($this->encrypt->decode($notas->$bimestre) == 0) {
									$fondo = 'bg-danger';
								} elseif ($this->encrypt->decode($notas->$bimestre) > 0 && $this->encrypt->decode($notas->$bimestre) < 60.50) {
									$fondo = 'bg-orange';
								} else {
									$fondo = 'bg-green';
								}
								$nota = $this->encrypt->decode($notas->$bimestre);
                            }
                            if ($bimestre === 'final') {
                                $cent .= '<td class="text-center col-lg-1 '.$fondo.'"><strong>'.round($nota,0).'</strong></td>';
                            } else {
                                $cent .= '<td class="text-center col-lg-1 '.$fondo.'"><strong>'.$nota.'</strong></td>';
                            }
						}
                        $cent .= '<td ><a target="_blank" href="'.base_url().'Admin/boletin_pdf/'.$alumno->id_alumno.'"> Boletin</a></td>';
					$cent .= '</tr>';
				}
			$cent .= '</tbody>';

			$cent .= '<tfoot class="bg-purple">';
				$cent .= '<tr>';
					$cent .= '<th colspan="1" style="text-align:right"></th>';
					$cent .= '<th colspan="1" style="text-align:right">Alumnos efectivos:</th>';
						foreach ($materias as $materia) {
							$notas = $this->Centralizador_model->get_estadistica_cent($id_plan, $id_carrera, $id_paralelo, $materia->id_materia, $bimestre, $id_anio);
							// $count_efe = 1;
							$efectivos = 0;
							foreach ($notas as $nota) {
								if ($this->encrypt->decode($nota->$bimestre) > 0) {
									$efectivos++;
								}
							}
							$cent .= '<th colspan="1" style="text-align:center">'.$efectivos.'</th>';
						}
				$cent .= '</tr>';
				$cent .= '<tr>';
					$cent .= '<th colspan="1" style="text-align:right"></th>';
					$cent .= '<th colspan="1" style="text-align:right">Alumnos aprobados:</th>';
						foreach ($materias as $materia) {
							$notas = $this->Centralizador_model->get_estadistica_cent($id_plan, $id_carrera, $id_paralelo, $materia->id_materia, $bimestre, $id_anio);
							// $count_efe = 1;
							$aprobados = 0;
							foreach ($notas as $nota) {
								if ($this->encrypt->decode($nota->$bimestre) >= 61) {
									$aprobados++;
								}
							}
							$cent .= '<th colspan="1" style="text-align:center">'.$aprobados.'</th>';
						}
				$cent .= '</tr>';
				$cent .= '<tr>';
					$cent .= '<th colspan="1" style="text-align:right"></th>';
					$cent .= '<th colspan="1" style="text-align:right">Alumnos reprobados:</th>';
						foreach ($materias as $materia) {
							$notas = $this->Centralizador_model->get_estadistica_cent($id_plan, $id_carrera, $id_paralelo, $materia->id_materia, $bimestre, $id_anio);
							$reprobados = 0;
							foreach ($notas as $nota) {
								if ($this->encrypt->decode($nota->$bimestre) < 61 && $this->encrypt->decode($nota->$bimestre) > 0) {
									$reprobados++;
								}
							}
							$cent .= '<th colspan="1" style="text-align:center">'.$reprobados.'</th>';
						}
				$cent .= '</tr>';
				$cent .= '<tr>';
					$cent .= '<th colspan="1" style="text-align:right"></th>';
					$cent .= '<th colspan="1" style="text-align:right">% de alumnos aprobados:</th>';
						foreach ($materias as $materia) {
							$notas = $this->Centralizador_model->get_estadistica_cent($id_plan, $id_carrera, $id_paralelo, $materia->id_materia, $bimestre, $id_anio);
							$aprobados = 0;
							$efectivos = 0;
							foreach ($notas as $nota) {
								if ($this->encrypt->decode($nota->$bimestre) > 0) {
									$efectivos++; //= $count_efe++;
								}

								if ($this->encrypt->decode($nota->$bimestre) >= 61) {
									$aprobados++; //= $count_apro++;
								}
							}
							if ($efectivos == 0) {
								$por_aproba = 0;
							} else {
								$por_aproba = $aprobados*100/$efectivos;
							}
							
							$cent .= '<th colspan="1" style="text-align:center">'.round($por_aproba, 0).'%</th>';
						}
				$cent .= '</tr>';
				$cent .= '<tr>';
					$cent .= '<th colspan="1" style="text-align:right"></th>';
					$cent .= '<th colspan="1" style="text-align:right">% de alumnos reprobados:</th>';
						foreach ($materias as $materia) {
							$notas = $this->Centralizador_model->get_estadistica_cent($id_plan, $id_carrera, $id_paralelo, $materia->id_materia, $bimestre, $id_anio);
							$reprobados = 0;
							$efectivos = 0;
							foreach ($notas as $nota) {
								if ($this->encrypt->decode($nota->$bimestre) > 0) {
									$efectivos++; //= $count_efe++;
								}

								if ($this->encrypt->decode($nota->$bimestre) < 61 && $this->encrypt->decode($nota->$bimestre) > 0) {
									$reprobados++; //= $count_apro++;
								}
							}
							if ($efectivos == 0) {
								$por_reproba = 0;
							} else {
								$por_reproba = $reprobados*100/$efectivos;
							}

							$cent .= '<th colspan="1" style="text-align:center">'.round($por_reproba, 0).'%</th>';
						}
				$cent .= '</tr>';
				$cent .= '<tr>';
					$cent .= '<th colspan="1" style="text-align:right"></th>';
					$cent .= '<th colspan="1" style="text-align:right">Alumnos retirados:</th>';
						foreach ($materias as $materia) {
							$notas = $this->Centralizador_model->get_estadistica_cent($id_plan, $id_carrera, $id_paralelo, $materia->id_materia, $bimestre, $id_anio);
							// $count_efe = 1;
							$retirados = 0;
							foreach ($notas as $nota) {
								if ($this->encrypt->decode($nota->$bimestre) == 0) {
									$retirados++;
								}
							}
							$cent .= '<th colspan="1" style="text-align:center">'.$retirados.'</th>';
						}
				$cent .= '</tr>';
				$cent .= '<tr>';
					$cent .= '<th colspan="1" style="text-align:right"></th>';
					$cent .= '<th colspan="1" style="text-align:right">Promedio:</th>';
						foreach ($materias as $materia) {
							$notas = $this->Centralizador_model->get_estadistica_cent($id_plan, $id_carrera, $id_paralelo, $materia->id_materia, $bimestre, $id_anio);
							$sumatoria = 0;
							$cont = 0;
							foreach ($notas as $nota) {
								$cont++;
								$sumatoria += $this->encrypt->decode($nota->$bimestre);
								// print_r($sumatoria.'-'.$this->encrypt->decode($nota->$bimestre));
								// print_r('<br>');
							}
							$promedio = $sumatoria/$cont;
							// print_r($sumatoria);
							// print_r($cont);
							// exit();
							$cent .= '<th colspan="1" style="text-align:center">'.round($promedio, 0).'</th>';
						}
				$cent .= '</tr>';
			$cent .= '</tfoot>';
		$cent .= '</table>';

		$centralizador = array('status'=>TRUE, 'cent' => $cent);
        echo json_encode($centralizador);
    }

//----------------------------PDF Inicio lista de estudiantes por materia, paralelo, carrera--------------

 	public function pdf_centralizador($id_carrera,$id_paralelo,$bimestre,$id_plan, $id_year)
     {
        // if (($_SESSION['is_type_user'] == 'is_director') || ($_SESSION['is_type_user'] == 'is_teacher')|| ($_SESSION['is_type_user'] == 'is_docente')){
 
         $this->load->library('pdf');
         $pdf = $this->pdf->load();

         $tz = 'America/La_Paz';
         $timestamp = time();
         $dt = new DateTime("now", new DateTimeZone($tz)); //first argument "must" be a string
         $dt->setTimestamp($timestamp); //adjust the object to correct timestamp
         $curso=$this->Centralizador_model->get_paralelo($id_paralelo);
         $carrera_cursa=$this->Centralizador_model->get_carrera($id_carrera);
         $cent = '';

         $materias = $this->Centralizador_model->get_materias($id_carrera, $id_paralelo,$id_plan);   
         $cent .='
         <html>
         <title>'.$carrera_cursa->nombre_carrera.' - '.$curso->nombre.'</title>
         <head>
         <style>
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
         $cent .='<table  border="1" >';    
         $cent .='<thead>';    
         $cent .='<tr>';    
         $cent .='<th>#</th>';    
         $cent .='<th>APELLIDOS Y NOMBRES</th>';
         $cent .='<th>C.I.</th>';
            foreach ($materias as $materia) {
                $cent .='<th style="text-rotate: 90"><div><span style="color:black; font-size:8pt; font-family:courier; font-weight: normal;
                font-style: normal; ">'.$materia->nombre_materia.' - '.$materia->sigla.'</span></div></th>';    
            }                 
         $cent .='</tr>';    
         $cent .='</thead>';  
         $cent .='<tbody>'; 
            $alumnos = $this->Centralizador_model->get_alumnos_cent($id_carrera, $id_paralelo, $id_plan, $id_year);
            $i = 1;
             foreach ($alumnos as $alumno) {
                 $cent .= '<tr>';
                     $cent .= '<td>'.$i++.'</td>';
                     $cent .= '<td>'.$alumno->nombre_completo.'</td>';
                     $cent .= '<td>'.$alumno->ci.'</td>';
                     foreach ($materias as $materia) {
                         $notas = $this->Centralizador_model->get_notas_cent($id_plan, $id_carrera, $id_paralelo, $materia->id_materia, $alumno->id_alumno, $bimestre, $id_year, $id_year);

                         if (empty($notas)) {
                             $fondo = 'style="color:black"';
                             $nota = "-";
                         } else {

                             $nota = $this->encrypt->decode($notas->$bimestre);
                         }
                         if ($bimestre === 'final' && $this->encrypt->decode($notas->$bimestre) > 60.40) {
                             $cent .= '<td '.$fondo.'><b>'.round($nota,0).'</b></td>';
                         } else {
                            $bim_2t='segundo_turno'; 
                            $notas_2t = $this->Centralizador_model->get_notas_cent($id_plan, $id_carrera, $id_paralelo, $materia->id_materia, $alumno->id_alumno,$bim_2t, $id_year);
                            $notas_2t = $this->encrypt->decode($notas_2t->$bim_2t);
                            if ($this->encrypt->decode($notas->$bimestre) > 39.50 && $this->encrypt->decode($notas->$bimestre) < 60.50 && $notas_2t>=61) {
                                $cent .= '<td>'.round($nota,0).'- <span><b>'.$notas_2t.'</b><span></td>';
                            }elseif($this->encrypt->decode($notas->$bimestre) >= 39.50 && $this->encrypt->decode($notas->$bimestre) < 60.50 && $notas_2t>0){
                                $cent .= '<td>'.round($nota,0).'- <span>'.$notas_2t.'<span></td>';        
                            }else{
                                if($nota=='-'){
                                $cent .= '<td><b>'.$nota.'</b></td>';
                            }else{
                                $cent .= '<td style="background: #e5e5e5;">'.round($nota,0).'</td>';
                            }
                            }
                        }
                     }
                 $cent .= '</tr>';
             }
         $cent .= '</tbody>';

         $cent .= '</table>';

         $cent .='<br>';    
         $cent .='<table  width="100%" align="center" >';    
         $cent .='<thead>';    
         $cent .='<tr  style="background: #e5e5e5;">';    
         $cent .='<th>Sigla</th>';    
         $cent .='<th>Materia</th>';    
         $cent .='<th>Docente</th>';    
         $cent .='</tr>';    
         $cent .='</thead>';    
         $cent .='<tbody>'; 
         
         foreach ($materias as $materia) {
            $cent .='<tr>';   
            $cent .='<td><span style="color:black; font-size:8pt; font-family:courier; font-weight: normal;
            font-style: normal; "><b>'.$materia->sigla.'</b></span></td>';               
            $cent .='<td><span style="color:black; font-size:8pt; font-family:courier; font-weight: normal;
            font-style: normal; ">'.$materia->nombre_materia.'</span></td>';  
            $cent .='<td><span style="color:black; font-size:8pt; font-family:courier; font-weight: normal;
            font-style: normal; ">'.$materia->nombre_completo.'</span></td>';  
            $cent .='</tr>'; 
         } 
          
         $cent .='</tbody></table>';
         $cent .='</body>
                 </html>';
         $pdf=new mPDF('c','LETTER','','',18,10,27,25,16,13);
 
         //$pdf->SetDisplayMode('fullpage');
         //$pdf->list_indent_first_level = 0; 

         $header = '<table width="100%" style="border-bottom: 1px solid #000000; vertical-align: bottom; font-family:
         serif; font-size: 9pt; color: #000;"><tr>
         <td width="50%" ><div><span style="color:black; font-size:8pt; font-family:courier; font-weight: normal;
         font-style: normal;"><b>Carrera: </b>'.$carrera_cursa->nombre_carrera.'</span></div><div><span style="color:black; font-size:8pt; font-family:courier; font-weight: normal;
         font-style: normal;"><b>Curso: </b>'.$curso->nombre.'</span></div></td>
         <td width="50%" ><div><span style="color:black; font-size:8pt; font-family:courier; font-weight: normal;
         font-style: normal;"><b>Carrera: </b>'.$bimestre.'</span></div><div><span style="color:black; font-size:8pt; font-family:courier; font-weight: normal;
         font-style: normal;"><b>Plan: </b>'.$id_plan.'</span></div></td>
         </tr></table>
         ';
         $pdf->SetHTMLHeader($header); 
        
 
         $pdf->WriteHTML($cent);
		 $pdf->Output(''.$carrera_cursa->nombre_carrera.'-'.$curso->nombre.'.pdf','I');
         exit;

 
      }
 
 //---------------------------- PDF fin lista de estudiantes por materia, paralelo, carrera--------------
 

  //   public function get_paralelos()
  //   {
  //       $lib = '';
  //       $planes = $this->Centralizador_model->get_planes();
  //       foreach ($planes as $plan) {
  //       	$carreras = $this->Admin_model->get_carreras($plan->id_plan);
	 //        foreach ($carreras as $carrera) {
  //               $lib .= '<div class="panel panel-flat">';
  //                   $lib .= '<div class="panel-heading">';
  //                       $lib .= '<h5 class="panel-title">CARRERA DE: '.$carrera->nombre_carrera.'<a class="heading-elements-toggle"><i class="icon-more"></i></a></h5>';
  //                       // $lib .= '<div class="heading-elements">';
  //                       //     $lib .= '<ul class="icons-list">';
  //                       //         $lib .= '<li><a data-action="collapse"></a></li>';
  //                       //         $lib .= '<li><a data-action="reload"></a></li>';
  //                       //     $lib .= '</ul>';
  //                       // $lib .= '</div>';
  //                   $lib .= '</div>';

  //                   $lib .= '<div class="panel-body">';
  //                       // $lib .= '<p class="content-group"><a href="http://prismjs.com/download.html">this page</a>.</p>';

  //                       $lib .= '<div class="row">';
  //                       for ($i=1; $i < 4; $i++) { 
  //                           $lib .= '<div class="col-md-4">';
  //                               $lib .= '<div class="panel panel-flat">';
  //                                   $lib .= '<div class="panel-heading">';
  //                                       $lib .= '<h5 class="panel-title">AÑO : '.$i.'<a class="heading-elements-toggle"><i class="icon-more"></i></a></h5>';
  //                                       // $lib .= '<div class="heading-elements">';
  //                                       //     $lib .= '<ul class="icons-list">';
  //                                       //         $lib .= '<li><a data-action="collapse"></a></li>';
  //                                       //         $lib .= '<li><a data-action="close"></a></li>';
  //                                       //     $lib .= '</ul>';
  //                                       // $lib .= '</div>';
  //                                   $lib .= '</div>';

  //                                   // $lib .= '<div class="panel-body"> primer año';
  //                                   // $lib .= '</div>';
  //                                   $lib .= '<div class="table-responsive">';
  //                                       $lib .= '<table class="table">';
  //                                           $lib .= '<thead>';
  //                                               $lib .= '<tr>';
  //                                                   $lib .= '<th>#</th>';
  //                                                   $lib .= '<th>Paralelo</th>';
  //                                                   $lib .= '<th>Enlace</th>';
  //                                                   // $lib .= '<th>Username</th>';
  //                                               $lib .= '</tr>';
  //                                           $lib .= '</thead>';
  //                                           $lib .= '<tbody>';
  //                                           $list = $this->Admin_model->query_get_paralelos($i, $carrera->id_carrera);
  //                                           $no = 1;
  //                                           foreach ($list as $paralelo) {
  //                                               $lib .= '<tr>';
  //                                                   $lib .= '<td>'.$no++.'</td>';
  //                                                   $lib .= '<td>'.$paralelo->nombre.'</td>';
  //                                                   $lib .= '<td><a href="'.base_url()."centralizador/center/".$plan->id_plan.'/'.$carrera->id_carrera.'/'.$i.'/'.$paralelo->id_paralelo.'">entrar</a></td>';
  //                                               $lib .= '</tr>';
  //                                           }
  //                                           $lib .= '</tbody>';
  //                                       $lib .= '</table>';
  //                                   $lib .= '</div>';
  //                               $lib .= '</div>';
  //                           $lib .= '</div>';
  //                       }
  //                       $lib .= '</div>';
  //                   $lib .= '</div>';
  //               $lib .= '</div>';
	 //        }
		// }
  //       $libreta = array('lib' => $lib);
  //       echo json_encode($libreta);
  //   }

  //   public function center($plan, $id_carrera, $nivel_curso, $id_paralelo)
  //   {
  //       if (($_SESSION['is_type_user'] == 'is_admin') || ($_SESSION['is_type_user'] == 'is_secretaria') || ($_SESSION['is_type_user'] == 'is_rector') || ($_SESSION['is_type_user'] == 'is_director')){



		// 	$data['vista'] = 'v_cetralizador_cake';
		// 	$this->salida($data);







  //       	// --------------------------
  //           // $count_pb = 0;
  //           // $count_pb_a = 0;
  //           // $count_sb = 0;
  //           // $count_sb_a = 0;
  //           // $count_tb = 0;
  //           // $count_tb_a = 0;
  //           // $count_cb = 0;
  //           // $count_cb_a = 0;
  //           // $count_st = 0;
  //           // $count_st_a = 0;

  //           // $lero = 1;

  //           // $materias_carrera_nivelcurso = $this->Admin_model->get_materia_by_all($id_carrera, $nivel_curso, $id_paralelo);
  //           // // print_r($materias_carrera_nivelcurso);
  //           // // exit();
  //           // foreach ($materias_carrera_nivelcurso as $materia) {
  //           //     $students = $this->Admin_model->get_students($id_carrera, $materia->id_materia, $id_paralelo);

  //           //     if (count($students) == 0) {
  //           //         continue;
  //           //     }

  //           //     foreach ($students as $student) {
  //           //         $notas = $this->Admin_model->get_note($student->id_alumno, $materia->id_materia, $id_paralelo);
  //           //         if ($this->encrypt->decode($notas->primer_bim) >= 61) {
  //           //             $count_pb++;
  //           //         } else {
  //           //             $count_pb_a++;
  //           //         }
  //           //         if ($this->encrypt->decode($notas->segundo_bim) >= 61) {
  //           //             $count_sb++;
  //           //         } else {
  //           //             $count_sb_a++;
  //           //         }
  //           //         if ($this->encrypt->decode($notas->tercer_bim) >= 61) {
  //           //             $count_tb++;
  //           //         } else {
  //           //             $count_tb_a++;
  //           //         }
  //           //         if ($this->encrypt->decode($notas->cuarto_bim) >= 61) {
  //           //             $count_cb++;
  //           //         } else {
  //           //             $count_cb_a++;
  //           //         }
  //           //         if ($this->encrypt->decode($notas->segundo_turno) >= 61) {
  //           //             $count_st++;
  //           //         } else {
  //           //             $count_st_a++;
  //           //         }
  //           //     }

  //           //     if (count($students) == $count_pb_a) {
  //           //         $count_pb_a = 0;
  //           //     }
  //           //     if (count($students) == $count_sb_a) {
  //           //         $count_sb_a = 0;
  //           //     }
  //           //     if (count($students) == $count_tb_a) {
  //           //         $count_tb_a = 0;
  //           //     }
  //           //     if (count($students) == $count_cb_a) {
  //           //         $count_cb_a = 0;
  //           //     }
  //           //     if (count($students) == $count_st_a) {
  //           //         $count_st_a = 0;
  //           //     }
                
  //           //     $aprobados[$materia->id_materia] = array(
  //           //         array("label"=> "1 Bim", "y"=> $count_pb, "x"=>1, "a_r"=>"a"),
  //           //         array("label"=> "2 Bim", "y"=> $count_sb, "x"=>2, "a_r"=>"a"),
  //           //         array("label"=> "3 Bim", "y"=> $count_tb, "x"=>3, "a_r"=>"a"),
  //           //         array("label"=> "4 Bim", "y"=> $count_cb, "x"=>4, "a_r"=>"a"),
  //           //         array("label"=> "S. T.", "y"=> $count_st, "x"=>5, "a_r"=>"a"),
  //           //     );

  //           //     $reprobados[$materia->id_materia] = array(
  //           //         array("label"=> "1 Bim", "y"=> $count_pb_a, "x"=>1, "a_r"=>"r"),
  //           //         array("label"=> "2 Bim", "y"=> $count_sb_a, "x"=>2, "a_r"=>"r"),
  //           //         array("label"=> "3 Bim", "y"=> $count_tb_a, "x"=>3, "a_r"=>"r"),
  //           //         array("label"=> "4 Bim", "y"=> $count_cb_a, "x"=>4, "a_r"=>"r"),
  //           //         array("label"=> "S. T.", "y"=> $count_st_a, "x"=>5, "a_r"=>"r"),
  //           //     );

  //           //     $count_pb = 0;
  //           //     $count_pb_a = 0;
  //           //     $count_sb = 0;
  //           //     $count_sb_a = 0;
  //           //     $count_tb = 0;
  //           //     $count_tb_a = 0;
  //           //     $count_cb = 0;
  //           //     $count_cb_a = 0;
  //           //     $count_st = 0;
  //           //     $count_st_a = 0;

  //           //     $una_materia['id_materia'] = $materia->id_materia;
  //           //     $una_materia['nombre_materia'] = $materia->nombre_materia;
  //           //     $una_materia['sigla'] = $materia->sigla;

  //           //     $materias_a[] = $una_materia;
  //           // }

  //           // $nombre_carrera = $this->db->get_where('carrera', array('id_carrera' => $id_carrera))->row()->nombre_carrera;
  //           // $nombre_paralelo = $this->db->get_where('paralelo', array('id_paralelo' => $id_paralelo))->row()->nombre;
  //           // $data['dataPoints1'] = $aprobados;
  //           // $data['dataPoints2'] = $reprobados;
            
  //           // $data['materias'] = $materias_a;

  //           // $data['nombre_carrera'] = $nombre_carrera;
  //           // $data['nombre_paralelo'] = $nombre_paralelo;
  //           // $data['vista'] = 'v_cetralizador_cake';
  //           // $this->salida($data);
  //       } else {
  //           redirect(base_url() . '', 'refresh');
  //       }
  //   }
}