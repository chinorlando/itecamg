<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin_model', 'Admin_model');
        $this->load->model('Persona_model');
        $this->load->model('Login_model');
        $this->load->model('Alumno_model','Alumno_model');
        $this->load->model('Profile_model','Profile_model');
        $this->load->model('Centralizador_model','Centralizador_model');
    }

    //////////////////////
    public function salida($data)
    {
        $this->load->view('template/header');
        $this->load->view($data['vista'],$data);
        $this->load->view('template/footer');
    }
    //////////////////////

    public function index()
    {
        $data['vista'] = 'alumno/v_principal';
        $this->salida($data);
    }

    public function regalumno()
    {
        $data['vista'] = 'csv_import';
        $this->salida($data);
    }

    public function lista()
    {
        $data['vista'] = 'v_alumno';
        $this->salida($data);
    }

    public function add_alumno_form()
    {
        $data['vista'] = 'v_add_alumno';
        $this->salida($data);
    }

    public function alumnoci()
    {
        $dato  = strtoupper($this->input->post('ci'));
        // $datos = $this->Admin_model->get_alumno_by_ci($dato);
        $datos = $this->Admin_model->v_get_alumno($dato);
        echo json_encode($datos);
    }

    public function ajax_list()
    {
        $list = $this->Admin_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $person) {
            $no++;
            $row = array();
            $row[] = $person->nombres;
            $row[] = $person->apellido_paterno;
            $row[] = $person->apellido_materno;
            $row[] = $person->ci.' '.$person->expedido;
            $row[] = $person->email;
            $row[] = $person->fecha_nacimiento;
            $row[] = $person->direccion;
            $row[] = $person->celular;
            $row[] = $person->lugar_trabajo;

            //add html for action
            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar" onclick="edit_alumno('."'".$person->id_alumno."'".')"><i class="glyphicon glyphicon-pencil"></i> Editar</a>
            <a class="btn btn-sm btn-default" href="javascript:void(0)" title="Matricular" onclick="add_matricula('."'".$person->id_alumno."'".')"><i class="glyphicon glyphicon-pencil"></i> Matricular</a>';
        
            $data[] = $row;
        }

        $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->Admin_model->count_all(),
                "recordsFiltered" => $this->Admin_model->count_filtered(),
                "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_edit($id)
    {
        $data = $this->Admin_model->get_by_id($id);
        // $data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
        // print_r($data);
        echo json_encode($data);
    }

    public function ajax_add_form()
    {
        $query = $this->Admin_model->ci_existe($this->input->post('ci'));
        if ($query != 1) {
            $dataPersona = array(
                'nombres' => $this->input->post('nombres'),
                'apellido_paterno' => $this->input->post('apellido_paterno'),
                'apellido_materno' => $this->input->post('apellido_materno'),
                'ci' => $this->input->post('ci'),
                'expedido' => $this->input->post('expedido'),
                'estado_civil' => $this->input->post('estado_civil'),
                'email' => $this->input->post('email'),
                'sexo' => $this->input->post('sexo'),
                'fecha_nacimiento' => $this->input->post('fecha_nacimiento'),
                'direccion' => $this->input->post('direccion'),
                'celular' => $this->input->post('celular'),
                'telefono_fijo' => $this->input->post('telefono_fijo'),
                'lugar_trabajo' => $this->input->post('lugar_trabajo'),
                'direccion_trabajo' => $this->input->post('direccion_trabajo'),
                'telefono_trabajo' => $this->input->post('telefono_trabajo'),
            );
            $id_persona = $this->Admin_model->insertar_persona($dataPersona);

            $dataTutor = array(
                'nombre_padre' => $this->input->post('nombre_padre'),
                'ocupacion_padre' => $this->input->post('ocupacion_padre'),
                'celular_padre' => $this->input->post('celular_padre'),
                'nombre_madre' => $this->input->post('nombre_madre'),
                'ocupacion_madre' => $this->input->post('ocupacion_madre'),
                'celular_madre' => $this->input->post('celular_madre'),
            );
            $id_tutor = $this->Admin_model->insertar_tutor($dataTutor);

            $dataAlumno = array(
                'fecha_preinscripcion' => $this->input->post('fecha_preinscripcion'),
                'id_carrera' => $this->input->post('carrera'),
                'colegio_proviene' => 'San Andres',
                'id_persona' => $id_persona,
                'id_tutor' => $id_tutor,
                // 'colegio_proviene' => $this->input->post('colegio_proviene'),
            );
            $this->Admin_model->insertar_alumno($dataAlumno);
            echo json_encode(array("status" => TRUE));
        } else{
            echo json_encode(array("status" => FALSE));
        }
    }


    public function ajax_update()
    {
        // $this->_validate();
        $dataPersona = array(
            'nombres' => $this->input->post('nombres'),
            'apellido_paterno' => $this->input->post('apellido_paterno'),
            'apellido_materno' => $this->input->post('apellido_materno'),
            'ci' => $this->input->post('ci'),
            'expedido' => $this->input->post('expedido'),
            'estado_civil' => $this->input->post('estado_civil'),
            'email' => $this->input->post('email'),
            'sexo' => $this->input->post('sexo'),
            'fecha_nacimiento' => $this->input->post('fecha_nacimiento'),
            'direccion' => $this->input->post('direccion'),
            'celular' => $this->input->post('celular'),
            'telefono_fijo' => $this->input->post('telefono_fijo'),
            'lugar_trabajo' => $this->input->post('lugar_trabajo'),
            'direccion_trabajo' => $this->input->post('direccion_trabajo'),
            'telefono_trabajo' => $this->input->post('telefono_trabajo'),
        );
        $dataTutor = array(
            'nombre_padre' => $this->input->post('nombre_padre'),
            'ocupacion_padre' => $this->input->post('ocupacion_padre'),
            'celular_padre' => $this->input->post('celular_padre'),
            'nombre_madre' => $this->input->post('nombre_madre'),
            'ocupacion_madre' => $this->input->post('ocupacion_madre'),
            'celular_madre' => $this->input->post('celular_madre'),
        );
        $dataAlumno = array(
            'id_alumno' => $this->input->post('id_alumno'),
            'fecha_preinscripcion' => $this->input->post('fecha_preinscripcion'),
            'id_carrera' => $this->input->post('id_carrera'),
            'colegio_proviene' => $this->input->post('colegio_proviene'),
        );
        // print_r($dataPersona);
        $this->Admin_model->updatePersona(array('id_persona' => $this->input->post('id_persona')), $dataPersona);
        $this->Admin_model->updateTutor(array('id_tutor' => $this->input->post('id_tutor')), $dataTutor);
        $this->Admin_model->updateAlumno(array('id_alumno' => $this->input->post('id_alumno')), $dataAlumno);
        echo json_encode(array("status" => TRUE));
    }


    public function get_carrera()
    {
        $list = $this->Admin_model->get_carrera();
        echo json_encode($list);
    }

    public function ajax_delete($id)
    {
        $this->person->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }

    public function confirmar()
    {
        // -- personal, alumno, matricula, update inscripcion
        $persona = $this->Persona_model->get_persona_by_id($this->input->post('id'));
        // print_r($persona);
        // exit();
        $id_cuenta = $this->Login_model->crear_usuario($persona);
        
        $dataPersonal = array(
            'id_rol' => 2,
            'id_cargo' => 1,
            'id_cuenta' => $id_cuenta,
            'id_persona' => $this->input->post('id')
        );

        $id_personal = $this->Admin_model->insertar_personal($dataPersonal);

        $prealumno = $this->Admin_model->get_alumno_preinscrito($this->input->post('id'));
        $dataAlumno = array(
            'id_inscripcion' => $prealumno->id_inscripcion,
            'id_personal' => $id_personal,
        );
        $this->Admin_model->insertar_alumno($dataAlumno);

        $ultimo_curso = $this->Admin_model->ultimo_curso();
        $ultimo_gestionperiodo = $this->Admin_model->ultimogestion();
        $alumno = $this->Admin_model->get_alumno_by_id($this->input->post('id'));

        $dataMatricula = array(
            // aqui podemos poner por defecto 1 ya que se programara el primer curso
            'id_curso' => $ultimo_curso->id_curso,
            'id_gestionperiodo' => $ultimo_gestionperiodo->id_gestionperiodo,
            'id_alumno' => $alumno->id_alumno,
        );
        $this->Admin_model->insertar_matricula($dataMatricula);

        $datainscripcion = array(
            // 'fecha_inscripcion' => now(); // falta poner la fecha actual
            'confirmado' => 'S',
        );
        $todo = $this->Admin_model->updateinscripcion(array('id_inscripcion' => $prealumno->id_inscripcion), $datainscripcion);

        echo json_encode(array("status" => TRUE));
    }

    // funcion para prograrme - alumnos nuevos
    public function list_alumnos_nuevos()
    {
        // $this->Admin_model->programado($_SESSION['user_id']);
        $list = $this->Admin_model->get_all_new_alumno();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $person) {
            $no++;
            $row = array();
            $row[] = '<input type="checkbox" class="data-check switchery" value="'.$person->id_alumno.'">';
            $row[] = $person->nombres;
            $row[] = $person->apellido_paterno.' '.$person->apellido_materno;
            $row[] = $person->ci.' '.$person->expedido;
            $row[] = $person->fecha_nacimiento;
            $row[] = $person->email;

            //add html for action
            $row[] = '<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Guardar" onclick="programar_alumno('."'".$person->id_alumno."'".')"><i class="icon icon-add"></i> Programar</a>';
        
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Admin_model->count_all_alumnos(),
                        "recordsFiltered" => $this->Admin_model->count_filtered_alumnos(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function programar_al()
    {
        $id_alumnos = $this->input->post('id');

        $paralelo = $this->Alumno_model->get_paralelo(1);
        $ultimo_gestionperiodo = $this->Admin_model->ultimogestion();

        $ultimo_plan = $this->Admin_model->plan_ultimo()->id_plan;
        // $materias_primer_anio = $this->Admin_model->materias($id_alumno, $ultimo_plan);

        if (is_array($id_alumnos)) {
            foreach ($id_alumnos as $id_alumno) {
                $this->programar_alumno($ultimo_plan, $paralelo, $ultimo_gestionperiodo, $id_alumno);
            }
        } else {
            $this->programar_alumno($ultimo_plan, $paralelo, $ultimo_gestionperiodo, $id_alumnos);
        }
    }

    public function programar_alumno($ultimo_plan, $paralelo, $ultimo_gestionperiodo, $id_alumno)
    {
        // print_r($this->Admin_model->cant_alumnos_programados_paralelo(4, 2, 7));

        // $paralelo = $this->Alumno_model->get_paralelo(1);
        // $ultimo_gestionperiodo = $this->Admin_model->ultimogestion();
        // print_r($id_alumno);
        // exit();

        foreach ($paralelo as $p) {
            if ($this->Admin_model->cant_alumnos_programados_paralelo($id_alumno, $ultimo_gestionperiodo->id_gestionperiodo, $p->id_paralelo) <= 60) {
                $id_p = $p->id_paralelo;
                break;
            }
        }
        // $ultimo_plan = $this->Admin_model->plan_ultimo()->id_plan;
        $materias_primer_anio = $this->Admin_model->materias($id_alumno, $ultimo_plan);

        foreach ($materias_primer_anio as $materia) {
            $dataAsignacionParalelo = [
                'id_paralelo' => $id_p,
                'id_materia' => $materia->id_materia,
                'estado' => 'N',
            ];
            $dataProgramacionMateria = array(
                'id_gestionperiodo' => $ultimo_gestionperiodo->id_gestionperiodo,
                'id_alumno' => $id_alumno,
            );
            $this->Admin_model->save_programacion($dataAsignacionParalelo, $dataProgramacionMateria);
        }
        echo json_encode(array("status" => TRUE));
    }

    // para programar a muchos alumnos
    // public function programar_alumnos()
    // {
    //     $list_id = $this->input->post('id');
    //     foreach ($list_id as $value) {
    //         $this->programar_alumno($value);
    //     }


    //     // $list_id = $this->input->post('id');
    //     // $ultimo_gestionperiodo = $this->Admin_model->ultimogestion();
    //     // foreach ($list_id as $id_alumno) {
    //     //     $materias_primer_anio = $this->Admin_model->materias_bulk($id_alumno);
    //     //     foreach ($materias_primer_anio as $materia) {
    //     //         $dataProgramacionMateria = array(
    //     //             'id_materia' => $materia->id_materia,
    //     //             'id_gestionperiodo' => $ultimo_gestionperiodo->id_gestionperiodo,
    //     //             'id_alumno' => $id_alumno,
    //     //         );
    //     //         $this->Admin_model->save_programacion($dataProgramacionMateria);
    //     //     }
    //     // }
    //     // echo json_encode(array("status" => TRUE));
    // }

    public function programaciones()
    {
        $data['vista'] = 'admin/v_programaciones';
        $this->salida($data);
    }

    

    //////////////////// añadir menus a docentes begin /////////////////////////////////
    public function menu()
    {
        if ($_SESSION['is_type_user'] != 'is_admin')
            redirect(base_url() . '', 'refresh');
        if ($_SESSION['is_type_user'] == 'is_admin'){
            $data['vista'] = 'admin/v_menu_usuarios';
            $this->salida($data);
        }
    }

    

    public function list_menu()
    {
        $datos = $this->Admin_model->get_menu();
        $id_persona = $this->input->post('id_persona');
        $id_personal = $this->Admin_model->get_personal($id_persona)->id_personal;
        // print_r($id_personal);
        // exit();
        $num   = count($datos);
        $cad = "";
        $i = 1;
        foreach($datos as $d){
            $principal = $d->principal;
            $id_menu = $d->id_menu;
            $nombre  = $d->nombre;

            $item = $this->Admin_model->get_menu_item($id_menu,$id_personal);

            if($item>0){
                $cad .= "<label><input type='checkbox' class='minimal' name='id_menu[]' id='id_menu".$i."' value='$id_menu' checked> $principal - $nombre</label> <br>" ;
            }else{
                $cad .= "<label><input type='checkbox' class='minimal' name='id_menu[]' id='id_menu".$i."' value='$id_menu'> $principal - $nombre</label> <br>";
            }
            $i++;
        }
        echo json_encode($cad);
    }

    public function add_menu(){
        $id_persona = $this->input->post('id_persona');
        $id_personal = $this->Admin_model->get_personal($id_persona)->id_personal;
        $this->Admin_model->delete_menu_usuario($id_personal);

        foreach($_POST['id_menu'] as $id_menu) {
            $datos = array(
                'id_menu'=>$id_menu,
                'id_personal'=>$id_personal,
            );
            $this->Admin_model->insertar_menu_usuario($datos); 
        }
        echo json_encode(array("status" => TRUE));
    }

    public function list_teachers_menu()
    {
        $list = $this->Profile_model->get_datatables_docente();
        // print_r($list);
        // exit();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $person) {
            $no++;
            $row = array();
            $row[] = $person->nombres;
            $row[] = $person->apellido_paterno.' '.$person->apellido_materno;
            $row[] = $person->ci;
            $row[] = $person->expedido;
            $row[] = $person->nombre_cargo;
            $row[] = $person->email;
            // $row[] = $person->fecha_nacimiento;

            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar" onclick="edit_person('."'".$person->id_persona."'".')"><i class="glyphicon glyphicon-pencil"></i> Editar menú</a>';
            $data[] = $row;
        }

        $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->Profile_model->count_all_docente(),
                "recordsFiltered" => $this->Profile_model->count_filtered_docente(),
                "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    //////////////////// añadir menus a docentes end ///////////////////////////////////

    //////////////////// asignacion de paralelos begin /////////////////////////////////
    public function paralelo($value='')
    {
        if ($_SESSION['is_type_user'] != 'is_admin')
            redirect(base_url() . '', 'refresh');
        if ($_SESSION['is_type_user'] == 'is_admin'){
            $data['vista'] = 'admin/v_asignacion_paralelos';
            $this->salida($data);
        }
    }

    public function list_subjects()
    {
        $list = $this->Admin_model->get_datatables_subjects();
        $data = array();
        $no = $_POST['start'];
        
        $cad = "";

        foreach ($list as $person) {
            // $no++;
            $row = array();
            $row[] = $person->nombre_plan;
            $row[] = $person->nombre_carrera;
            $row[] = $person->sigla;
            $row[] = $person->nivel_curso;
            $row[] = $person->nombre_materia;

            $nivel_curso = $this->db->get_where('materia', array('id_materia' => $person->id_materia))->row()->nivel_curso;
            $datos_nivel = $this->Admin_model->get_parallels($nivel_curso);
            $chk = "";
            foreach($datos_nivel as $d){
                if(!$this->verificar_asignacion_paralelo($d->id_paralelo, $person->id_materia))
                    $chk = "checked";
                $cad .= "<label><input disabled='disabled' type='checkbox' class='minimal' name='id_menu[]' value='".$d->id_paralelo."' ".$chk."> $d->nombre</label>" ;
                $chk = "";
            }
            $row[] = $cad;

            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar" onclick="edit_parallel('.$person->id_materia.')"><i class="glyphicon glyphicon-pencil"></i> Editar</a>';
            $data[] = $row;
            $cad = '';
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Admin_model->count_all_subjects(),
            "recordsFiltered" => $this->Admin_model->count_filtered_subjects(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function verificar_asignacion_paralelo($id_paralelo, $id_materia)
    {
        $id_paralelo = $this->db->get_where('asignacionparalelo', array(
            'id_paralelo' => $id_paralelo,
            'id_materia' => $id_materia,
            'estado' => 'S',
        ))->row();

        if (empty($id_paralelo)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function list_parallels()
    {
        $id_materia = $this->input->post('id_materia');
        $nivel_curso = $this->db->get_where('materia', array('id_materia' => $id_materia))->row()->nivel_curso;
        $datos_nivel = $this->Admin_model->get_parallels($nivel_curso);
        $cad = "";
        $chk = "";
        foreach($datos_nivel as $d){
            if(!$this->verificar_asignacion_paralelo($d->id_paralelo, $id_materia))
                $chk = "checked";
            $cad .= "<label><input type='checkbox' class='minimal check_paralelo' name='id_menu[]' value='".$d->id_paralelo."' ".$chk."> $d->nombre</label> <br>" ;
            $chk = "";
        }
        echo json_encode($cad);
    }

    public function add_parallels()
    {
        $id_materia = $this->input->post('id_materia');
        $this->Admin_model->delete_parallels($id_materia);
        if (!empty($_POST['id_menu'])) {
            foreach($_POST['id_menu'] as $id_menu) {
                $datos = array(
                    'id_paralelo'=>$id_menu,
                    'id_materia'=>$id_materia,
                );
                $this->Admin_model->insertar_parallels($datos); 
            }
        }
        echo json_encode(array("status" => TRUE));
    }

    // // para actualizar los estados de los paralelos
    // public function add_parallels()
    // {
    //     $id_materia = $this->input->post('id_materia');
    //     foreach($_POST['id_menu'] as $id_menu) {
    //         $data = array(
    //             'estado' => 'N',
    //         );
    //         $this->Admin_model->update_paralelos(array(
    //             'id_paralelo'=>$id_menu,
    //             'id_materia'=>$id_materia,
    //         ), $data);
    //     }
    //     echo json_encode(array("status" => TRUE));
    // }
    //////////////////// asignacion de paralelos end ///////////////////////////////////

    //////////////////// asignacion de docentes begin /////////////////////////////////
    public function docente()
    {
        if ($_SESSION['is_type_user'] != 'is_admin')
            redirect(base_url() . '', 'refresh');
        if ($_SESSION['is_type_user'] == 'is_admin'){
            $data['vista'] = 'admin/v_asignacion_docentes';
            $this->salida($data);
        }
    }

    public function list_teacher_subjects()
    {
        $list = $this->Admin_model->get_datatables_teacher_subjects();
        $data = array();
        $no = $_POST['start'];
        
        $cad = "";
        $i=0;

        foreach ($list as $person) {
            // $no++;
            $row = array();
            $row[] = $person->nombre_plan;
            $row[] = $person->nombre_carrera;
            $row[] = $person->sigla;
            $row[] = $person->nombre_materia;
            $row[] = $person->nivel_curso;
            $row[] = $person->nombre_paralelo;

            $asignacion = $this->Admin_model->list_docentes_asign($person->id_materia, $person->id_paralelo);
            $id_docente = -1;
            $etiquetaButton = 'Guardar';
            $existe = 0;
            $color = 'btn-success';
            $icon = ' glyphicon-floppy-disk';

            if (count($asignacion)>0) {
                $id_docente = $asignacion[0]->id_docente;
                $etiquetaButton = "Actualizar";
                $existe = 1;
                $color = 'btn-primary';
                $icon = 'glyphicon-refresh';
            }
            
            $list_docentes = $this->Admin_model->list_all_docentes();
            $cad_doc = '<select id="docente'.$i.'" name="docente" class="form-control">
                            <option value="-1">Selecciona docente...</option>';
                foreach ($list_docentes as $docente) {
                    $sel = "";
                    if ($id_docente == $docente->id_docente)
                        $sel="selected";
                    $cad_doc .= '<option '.$sel.' value="'.$docente->id_docente.'">'.$docente->apellido_paterno.' '.$docente->apellido_materno.', '.$docente->nombres.'</option>';
                }
            $cad_doc .= '</select>';

            $row[] = $cad_doc;

            $row[] = '<a class="btn btn-sm '.$color.'" href="javascript:void(0)" title="Guardar" onclick="edit_parallel('.$i.', '.$person->id_pensum.','.$id_docente.','.$person->id_paralelo.','.$existe.')"><i class="glyphicon '.$icon.'"></i> '.$etiquetaButton.'</a>';
            $data[] = $row;
            $i++;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Admin_model->count_all_teacher_subjects(),
            "recordsFiltered" => $this->Admin_model->count_filtered_teacher_subjects(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function add_asignacion_docente()
    {
        $id_pensum = $_POST['id_pensum'];
        $id_docente = $_POST['id_docente'];
        $id_paralelo = $_POST['id_paralelo'];
        if ($id_docente == -1) {
            echo json_encode(array("status" => FALSE));
            exit();
        }
        $dataAsignacionDocente  = array(
            'fecha_asignacion' => date("Y-m-d"),
            'fecha_dejo' => "",
            'id_docente' => $id_docente,
            'id_pensum' => $id_pensum,
            'id_paralelo' => $id_paralelo,
        );
        $insert = $this->Admin_model->save_asignacion_docente($dataAsignacionDocente);
        echo json_encode(array("status" => TRUE));
    }

    public function update_asignacion_docente()
    {
        $id_pensum = $_POST['id_pensum'];
        $id_docente = $_POST['id_docente'];
        $id_paralelo = $_POST['id_paralelo'];

        $dataAsignacionDocente  = array(
            // 'fecha_asignacion' => date("Y-m-d"),
            'fecha_dejo' => date("Y-m-d"),
            'id_docente' => $id_docente,
            // 'id_pensum' => $id_pensum,
            // 'id_paralelo' => $id_paralelo,
        );
        // 
        $this->Admin_model->update_asignacion_docente(array(
            'id_pensum' => $id_pensum,
            'id_paralelo' => $id_paralelo,
        ), $dataAsignacionDocente);
        echo json_encode(array("status" => TRUE));
    }
    //////////////////// asignacion de docentes end ///////////////////////////////////

    //////////////////// asignacion gestionperido begin /////////////////////////////////
    public function gestionperiodo()
    {
        if ($_SESSION['is_type_user'] != 'is_admin')
            redirect(base_url() . '', 'refresh');
        if ($_SESSION['is_type_user'] == 'is_admin'){
            $data['vista'] = 'admin/v_gestionperiodo';
            $this->salida($data);
        }
    }

    public function list_gestionperiodo()
    {
        $list = $this->Admin_model->get_datatables_gestionperiodo();
        $data = array();
        $no = $_POST['start'];
        
        $cad = "";

        foreach ($list as $person) {
            // $no++;
            $row = array();
            $row[] = $person->gestion;
            $row[] = $person->periodo;
            $row[] = $person->nombre;
            // $row[] = $person->estado;
            $checked = ($person->estado == "S") ? "checked" : "" ;
            // print_r($person->gestion .'-'. $checked);
            // exit();

            $row[] = '<input type="radio" name="gestion_periodo" value="'.$person->id_gestionperiodo.'" '.$checked.' onclick="ShowHideDiv('.$person->id_gestionperiodo.')">';

            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_gestionperiodo('."'".$person->id_gestionperiodo."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
            // $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_gestionperiodo('."'".$person->id_gestionperiodo."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
            //       <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_person('."'".$person->id_gestionperiodo."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

            $data[] = $row;
            $cad = '';
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Admin_model->count_all_gestionperiodo(),
            "recordsFiltered" => $this->Admin_model->count_filtered_gestionperiodo(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function add_gestionperiodo()
    {
        $data = array(
            'gestion' => $this->input->post('gestion'),
            'periodo' => $this->input->post('periodo'),
            'estado' => $this->input->post('estado') == "on"  ? 'S' : "N",
            'id_tipogestion' => $this->input->post('tipogestion'),
        );
        $insert = $this->Admin_model->save_gestionperiodo($data);
        echo json_encode(array("status" => TRUE));
    }

    public function get_tipogestion()
    {
        $all_tipogestion = $this->Admin_model->tipogestion();
        echo json_encode($all_tipogestion);
    }

    public function ajax_edit_update($id)
    {
        $data = $this->Admin_model->get_by_id_gestionperiodo($id);
        $data->estado = $data->estado == 'S' ? TRUE : FALSE;
        echo json_encode($data);

        // $all_tipogestion = $this->Admin_model->get_by_id_gestionperiodo($id);
        // echo json_encode($all_tipogestion);
    }

    public function update_gestionperiodo()
    {
        $data = array(
            'gestion' => $this->input->post('gestion'),
            'periodo' => $this->input->post('periodo'),
            'estado' => $this->input->post('estado') == "on"  ? 'S' : "N",
            'id_tipogestion' => $this->input->post('tipogestion')
        );
        $this->Admin_model->update_gestionperiodo(array('id_gestionperiodo' => $this->input->post('id_gestionperiodo')), $data);
        echo json_encode(array("status" => TRUE));
    }


    //////////////////// asignacion gestionperido end ///////////////////////////////////

    //////////////////// importar docentes begin /////////////////////////////////
    public function update_state()
    {
        $data_all = array(
                'estado' => 'N',
            );
        $this->Admin_model->update_all($data_all);

        $data = array(
            'estado' => 'S',
        );
        $this->Admin_model->update_one(array('id_gestionperiodo' => $this->input->get('id_gestionperiodo')), $data);
        echo json_encode(array("status" => TRUE));
    }
    //////////////////// importar docentes end ///////////////////////////////////


    //////////// activar desactivar las columnas de las notas begin //////////////////
    public function bimestre()
    {
        if ($_SESSION['is_type_user'] != 'is_admin')
            redirect(base_url() . '', 'refresh');
        if ($_SESSION['is_type_user'] == 'is_admin'){
            $data['vista'] = 'admin/v_activar_bimestres';
            $this->salida($data);
        }
    }

     public function list_bimestres()
    {
        $list = $this->Admin_model->get_bimestre_by_bimestre();
        $lib = '';
        $num = 1;
        foreach ($list as $dato) {
            if ($dato->estado == 0) {
                $ocultarhide = 'disabled';
                $mostrarshow = 'enabled';
            } else {
                $ocultarhide = 'enabled';
                $mostrarshow = 'disabled';
            }

            $acciones = '<a '.$ocultarhide.' class="btn btn-sm bg-pink " href="javascript:void(0)" title="Ocultar" onclick="hide('."'".$dato->id_activebim."'".')"><i class="glyphicon glyphicon-eye-close"></i> Ocultar</a>
            <a '.$mostrarshow.' class="btn btn-sm bg-green-700" href="javascript:void(0)" title="Mostrar" onclick="show('."'".$dato->id_activebim."'".')"><i class="glyphicon glyphicon-eye-open"></i> Mostrar</a>';
            $lib .='<tr>';
                $lib .='<td>'.$num++.'</td>';
                $lib .='<td>'.$dato->nombre.'</td>';
                $lib .='<td> <input type="text" class="form-control daterange-basic" value="'.$dato->fecha_inicio.'-'.$dato->fecha_fin.'"></td>';
                if ($dato->estado == 1) {
                    $lib .='<td><span class="label bg-green-600">Habilitado</span></td>';
                } else {
                    $lib .='<td><span class="label bg-orange-600">Deshabilidato</span></td>';
                }
                $lib .='<td>'.$acciones.'</td>';
            $lib .='</tr>';
        }

        $libreta = array('lib' => $lib);
        echo json_encode($libreta);
    }

    public function hide_bimestre()
    {
        $data = array(
            'estado' => '0',
        );
        $this->Admin_model->update(array('id_activebim' => $this->input->post('id_activebim')), $data);
        echo json_encode(array("status" => TRUE));
    }
    public function show_bimestre()
    {
        $data = array(
            'estado' => '1',
        );
        $this->Admin_model->update(array('id_activebim' => $this->input->post('id_activebim')), $data);
        echo json_encode(array("status" => TRUE));
    }
    //////////// activar desactivar las columnas de las notas end ////////////////////


    //////////// para entrega de boletines begin ////////////////////
    public function boletin()
    {
        if (($_SESSION['is_type_user'] == 'is_admin') || ($_SESSION['is_type_user'] == 'is_secretaria') || ($_SESSION['is_type_user'] == 'is_rector') || ($_SESSION['is_type_user'] == 'is_director') ){
            $data['vista'] = 'admin/v_boletin';
            $this->salida($data);
        } else {
            redirect(base_url() . '', 'refresh');
        }
    }

    public function get_students()
    {
        $list = $this->Admin_model->get_datatables_students();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $person) {
            $no++;
            $row = array();
            $row[] = $person->apellido_paterno; //.' '.$person->apellido_materno;
            $row[] = $person->apellido_materno;
            $row[] = $person->nombres;
            $row[] = $person->ci;
            $row[] = $person->expedido;
            // $row[] = $person->fecha_nacimiento;

            $row[] = '<ul class="icons-list">
                        <!-- <li class="text-primary-600">
                        <button type="button" class="btn btn-info btn-rounded btn-xs"><i class="icon-printer position-center"></i><a target="_blank" href="'.base_url().'Admin/boletin_pdf/'.$person->id_alumno.'" </a> Boletin</button> -->
                        </li>
                        <li class="text-primary-600">
                            <a href="javascript:void(0)" class="btn btn-info btn-rounded btn-xs" title="Confirmar" onclick="confirmar_boletin('.$person->id_alumno.')"><i class="icon-printer"></i> Boletin</a>
                            </li>
                        <li class="text-primary-600">
                        <button type="button" class="btn btn-default btn-rounded btn-xs"><i class="icon-user position-center"></i><a target="_blank" href="'.base_url().'Admin/carnet_pdf/'.$person->id_alumno.'" </a> Carnet</button>
                        </li>
                    </ul>';
            // $row[] = '<ul class="icons-list">
            //                 <li class="text-primary-600">
            //                 <a href="javascript:void(0)" class="btn border-warning text-warning btn-flat btn-rounded btn-icon btn-xs" title="Confirmar" onclick="confirmar_boletin('."'".$person->id_alumno."'".')"><i class="icon-user-plus"></i></a>
            //                 </li>
            //             </ul>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Admin_model->count_all_students(),
            "recordsFiltered" => $this->Admin_model->count_filtered_students(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function boletin_alumno()
    {
        $id_alumno = $this->input->post('id_alumno');
        $ultimo_gestionperiodo = $this->Admin_model->ultimogestion()->id_gestionperiodo;
        $list = $this->Admin_model->get_boletin_alumno($ultimo_gestionperiodo, 266);
        $data = array();
        foreach ($list as $person) {
            $row = array();
            $row[] = $person->sigla;
            $row[] = $person->nombre;
            $row[] = $person->nivel_curso;
            $row[] = $this->encrypt->decode($person->primer_bim);
            $row[] = $this->encrypt->decode($person->segundo_bim);
            $row[] = $this->encrypt->decode($person->tercer_bim);
            $row[] = $this->encrypt->decode($person->cuarto_bim);
            $row[] = $this->encrypt->decode($person->final);
            $row[] = $this->encrypt->decode($person->segundo_turno);
            $row[] = $person->nombre_paralelo;
            $row[] = $person->nombre_carrera;

            $data[] = $row;
        }

        $output = array(
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function all_gestion_alumno($id_alumno)
    {
        $all_gestion = $this->Admin_model->gestion_perido_all($id_alumno);
        echo json_encode($all_gestion);
    }
    
    
    //---------------boletin por alumno---//////////   
    public function boletin_pdf($id_alumno, $ultimo_gestionperiodo)
    {
        // $id_alumno = $this->input->post('id_alumno');
        // $ultimo_gestionperiodo = $this->input->post('id_gestionperiodo');
        if (($_SESSION['is_type_user'] == 'is_admin')||($_SESSION['is_type_user'] == 'is_secretaria') || ($_SESSION['is_type_user'] == 'is_director') || ($_SESSION['is_type_user'] == 'is_rector')){
            //$id_alumno = $this->input->post('id_alumno');
            // $ultimo_gestionperiodo = $this->Admin_model->ultimogestion()->id_gestionperiodo;
            // $ultimo_gestionperiodo = 2;
            // print_r($id_alumno."-----".$ultimo_gestionperiodo);
            // exit();
            $list = $this->Admin_model->get_boletin_alumno($ultimo_gestionperiodo, $id_alumno);
            //
            // print_r($list);
            // exit();
    
            $this->load->library('pdf');
            $pdf = $this->pdf->load();
            $tz = 'America/La_Paz';
            $timestamp = time();
            $dt = new DateTime("now", new DateTimeZone($tz)); //first argument "must" be a string
            $dt->setTimestamp($timestamp); //adjust the object to correct timestamp
            $note = '';
    
            $d='';
            foreach ($list as $personqr) {
                $d .= ''.$personqr->sigla.' '.$this->encrypt->decode($personqr->primer_bim).' '.$this->encrypt->decode($personqr->segundo_bim).' ';
                $d .= ''.$this->encrypt->decode($personqr->tercer_bim).' '.$this->encrypt->decode($personqr->cuarto_bim).' '.$this->encrypt->decode($personqr->final).' ';
            }
            
            $note .='
            <html>
            <title>'.(string)$list[0]->nombres.' '.(string)$list[0]->apellido_paterno.' '.(string)$list[0]->apellido_materno.'</title>
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
            ';
            
            $note .='
            <table width="100%"><tr>
            <td width="20%" ><div><span style="color:black; font-size:11pt; font-family:courier; font-weight: normal;
            font-style: normal;"><b>GESTION: </b>'.$this->Admin_model->get_gestion($ultimo_gestionperiodo).'</span></div></td>  
            <td width="60%" align="center"><div><span style="color:black; font-size:11pt; font-family:courier; font-weight: normal;
            font-style: normal;"><span style="color:black; font-size:15pt; "><b>BOLETIN DE CALIFICACIONES</b></span></div></td>           
            <td width="20%" align="right" class="barcodecell"><barcode code="'.$list[0]->ci.' '.$d.'" type="QR" class="barcode" size="0.8"
            error="M"/></td>
            </tr></table>
            <table width="100%" style="font-family:    
            serif; font-size: 9pt; color: #000;"><tr>
            <td width="50%" ><div><span style="color:black; font-size:11pt; font-family:courier; font-weight: normal;
            font-style: normal;"><b>'.(string)$list[0]->apellido_paterno.' '.(string)$list[0]->apellido_materno.' '.(string)$list[0]->nombres.'</b></span></div></td>
            <td width="20%" ><div><span style="color:black; font-size:11pt; font-family:courier; font-weight: normal;
            font-style: normal;"><b>C.I. </b>'.(string)$list[0]->ci.'</span></div></td>            
            <td width="30%" align="center"><div><span style="color:black; font-size:10pt; font-family:courier; font-weight: normal;
            font-style: normal;"><b>'.(string)$list[0]->nombre_carrera.'</b></span></div></td>
            </tr></table>       
            ';
            
            $note .='<table width="70%">';    
            $note .='<thead>';    
            $note .='<tr>';    
            $note .='<th><span style="color:black; font-size:10pt; font-family:courier; font-weight: normal;
            font-style: normal;"><b>Sigla</b></span></th>';    
            $note .='<th><span style="color:black; font-size:10pt; font-family:courier; font-weight: normal;
            font-style: normal;"><b>Nivel</b></span></th>';  
            $note .='<th width="50%"><span style="color:black; font-size:10pt; font-family:courier; font-weight: normal;
            font-style: normal;"><b>Asignaturas</b></span></th>';    
            $note .='<th width="8%"><span style="color:black; font-size:10pt; font-family:courier; font-weight: normal;
            font-style: normal;"><b>1ºBI</b></span></th>';    
            $note .='<th width="8%"><span style="color:black; font-size:10pt; font-family:courier; font-weight: normal;
            font-style: normal;"><b>2ºBI</b></span></th>';    
            $note .='<th width="8%"><span style="color:black; font-size:10pt; font-family:courier; font-weight: normal;
            font-style: normal;"><b>3ºBI</b></span></th>';    
            $note .='<th width="8%"><span style="color:black; font-size:10pt; font-family:courier; font-weight: normal;
            font-style: normal;"><b>4ºBI</b></span></th>';    
            $note .='<th><span style="color:black; font-size:10pt; font-family:courier; font-weight: normal;
            font-style: normal;"><b>NF</b></span></th>';    
            $note .='<th><span style="color:black; font-size:10pt; font-family:courier; font-weight: normal;
            font-style: normal;"><b>ST</b></span></th>'; 
            $note .='</tr>';    
            $note .='</thead>';    
            $note .='<tbody>'; 
    
    
                
            foreach ($list as $person) {
                $note .='<tr >'; 
                $note .='<td><span style="color:black; font-size:10pt; font-family:courier; font-weight: normal;
            font-style: normal;">'.$person->sigla.'</span></td>';
                $note .='<td align="center"><span style="color:black; font-size:10pt; font-family:courier; font-weight: normal;
            font-style: normal;">'.$person->nombre_paralelo.'</span></td>';
                $note .='<td font-size:5pt; font-family:courier;><span style="color:black; font-size:10pt; font-family:courier; font-weight: normal;
            font-style: normal;">'.$person->nombre_materia.'</span><br><span style="color:black; font-size:4pt; font-family:courier; font-weight: normal;
            font-style: normal;">'.$this->nombre_docente($person->id_carrera,$person->id_paralelo,$person->id_plan,$person->id_materia).'</span></td>';
            if ($this->encrypt->decode($person->primer_bim) != 0 ) {
                $note .='<td align="center"><span style="color:black; font-size:10pt; font-family:courier; font-weight: normal;
            font-style: normal;">'.$this->encrypt->decode($person->primer_bim).'</span></td>';
            }else{
                $note .='<td align="center"><span style="color:black; font-size:10pt; font-family:courier; font-weight: normal;
                font-style: normal;">0</span></td>';
            }
            if ($this->encrypt->decode($person->segundo_bim) != 0 ) {
                $note .='<td align="center"><span style="color:black; font-size:10pt; font-family:courier; font-weight: normal;
            font-style: normal;">'.$this->encrypt->decode($person->segundo_bim).'</span></td>';
            }else{
                $note .='<td align="center"><span style="color:black; font-size:10pt; font-family:courier; font-weight: normal;
                font-style: normal;">0</span></td>';
            }
            if ($this->encrypt->decode($person->tercer_bim) != 0 ) {
                $note .='<td align="center"><span style="color:black; font-size:10pt; font-family:courier; font-weight: normal;
            font-style: normal;">'.$this->encrypt->decode($person->tercer_bim).'</span></td>';
            }else{
                $note .='<td align="center"><span style="color:black; font-size:10pt; font-family:courier; font-weight: normal;
                font-style: normal;">0</span></td>';
            }
            if ($this->encrypt->decode($person->cuarto_bim) != 0 ) {
                $note .='<td align="center"><span style="color:black; font-size:10pt; font-family:courier; font-weight: normal;
            font-style: normal;">'.$this->encrypt->decode($person->cuarto_bim).'</span></td>';
            }else{
                $note .='<td align="center"><span style="color:black; font-size:10pt; font-family:courier; font-weight: normal;
                font-style: normal;">0</span></td>';
            } 
            if ($this->encrypt->decode($person->final) != 0 ) {
                $note .='<td align="center"><span style="color:black; font-size:10pt; font-family:courier; font-weight: normal;
            font-style: normal;">'.round($this->encrypt->decode($person->final)).'</span></td>';
            }else{
                $note .='<td align="center"><span style="color:black; font-size:10pt; font-family:courier; font-weight: normal;
                font-style: normal;">0</span></td>';
            }   
            if ($this->encrypt->decode($person->segundo_turno) != 0 ) {
                $note .='<td align="center"><span style="color:black; font-size:10pt; font-family:courier; font-weight: normal;
            font-style: normal;">'.$this->encrypt->decode($person->segundo_turno).'</span></td>';
            }else{
                $note .='<td align="center"><span style="color:black; font-size:10pt; font-family:courier; font-weight: normal;
                font-style: normal;">0</span></td>';
            } 
    
            }
            
        $note .='</tr>';
        $note .='</tbody></table>';
        $note .='<table width="100%" style="font-family:    
        serif; font-size: 7pt; color: #000;"><tr>
        <td width="50%" align="right"><div><span style="color:black; font-size:7pt; font-family:courier; font-weight: normal;
        font-style: normal;"><b>Fecha: </b>'.$dt->format('d-m-Y, H:i:s').'</span>  -  <span style="color:black; font-size:7pt; font-family:courier; font-weight: normal;
        font-style: normal;"><b>Usuario: </b>'.$_SESSION["username"].'</span></div></td>
        </tr>
        </table>';
        $note .='</body>
                </html>';
        $pdf=new mPDF('c','LETTER','','',10,10,10,25,16,13);
    
        $pdf->WriteHTML($note);
        $pdf->Output(''.(string)$list[0]->apellido_paterno.' '.(string)$list[0]->apellido_materno.' '.(string)$list[0]->nombres.'.pdf','I');
        exit;
        }else {
            redirect(base_url() . '', 'refresh');
        }  
    }
    
    public function nombre_docente($id_carrera, $id_paralelo, $id_plan,$id_materia)
    {
        $docente=$this->Admin_model->get_materias($id_carrera, $id_paralelo, $id_plan,$id_materia);
        return $docente;
    }
  //---------------boletin por alumno---////////// 
    
    
    //////////// para entrega de boletines end //////////////////////

    ////////////////////////// centralizador begin /////////////////////////////////
    public function estadistica()
    {
        if (($_SESSION['is_type_user'] == 'is_admin') || ($_SESSION['is_type_user'] == 'is_secretaria') || ($_SESSION['is_type_user'] == 'is_rector') || $_SESSION['is_type_user'] == 'is_director'){
            $data['vista'] = 'admin/v_centralizador';
            $this->salida($data);
        } else {
            redirect(base_url() . '', 'refresh');
        }
    }

    public function get_paralelos()
    {
        $lib = '';
        $carreras = $this->Admin_model->get_carreras();
        foreach ($carreras as $carrera) {
        //     $no = $_POST['start'];
            
                $lib .= '<div class="panel panel-flat">';
                    $lib .= '<div class="panel-heading">';
                        $lib .= '<h5 class="panel-title">CARRERA DE: '.$carrera->nombre_carrera.'<a class="heading-elements-toggle"><i class="icon-more"></i></a></h5>';
                        $lib .= '<div class="heading-elements">';
                            $lib .= '<ul class="icons-list">';
                                $lib .= '<li><a data-action="collapse"></a></li>';
                                $lib .= '<li><a data-action="reload"></a></li>';
                            $lib .= '</ul>';
                        $lib .= '</div>';
                    $lib .= '</div>';

                    $lib .= '<div class="panel-body">';
                        // $lib .= '<p class="content-group"><a href="http://prismjs.com/download.html">this page</a>.</p>';

                        $lib .= '<div class="row">';
                        for ($i=1; $i < 4; $i++) { 
                            $lib .= '<div class="col-md-4">';
                                $lib .= '<div class="panel panel-flat">';
                                    $lib .= '<div class="panel-heading">';
                                        $lib .= '<h5 class="panel-title">AÑO : '.$i.'<a class="heading-elements-toggle"><i class="icon-more"></i></a></h5>';
                                        $lib .= '<div class="heading-elements">';
                                            $lib .= '<ul class="icons-list">';
                                                $lib .= '<li><a data-action="collapse"></a></li>';
                                                $lib .= '<li><a data-action="close"></a></li>';
                                            $lib .= '</ul>';
                                        $lib .= '</div>';
                                    $lib .= '</div>';

                                    // $lib .= '<div class="panel-body"> primer año';
                                    // $lib .= '</div>';
                                    $lib .= '<div class="table-responsive">';
                                        $lib .= '<table class="table">';
                                            $lib .= '<thead>';
                                                $lib .= '<tr>';
                                                    $lib .= '<th>#</th>';
                                                    $lib .= '<th>Paralelo</th>';
                                                    $lib .= '<th>Enlace</th>';
                                                    // $lib .= '<th>Username</th>';
                                                $lib .= '</tr>';
                                            $lib .= '</thead>';
                                            $lib .= '<tbody>';
                                            $list = $this->Admin_model->query_get_paralelos($i, $carrera->id_carrera);
                                            $no = 1;
                                            foreach ($list as $paralelo) {
                                                $lib .= '<tr>';
                                                    $lib .= '<td>'.$no++.'</td>';
                                                    $lib .= '<td>'.$paralelo->nombre.'</td>';
                                                    $lib .= '<td><a href="'.base_url()."admin/grafica/".$carrera->id_carrera.'/'.$i.'/'.$paralelo->id_paralelo.'">entrar</a></td>';
                                                    // $lib .= '<td>@Kopyov</td>';
                                                $lib .= '</tr>';
                                            }
                                            $lib .= '</tbody>';
                                        $lib .= '</table>';
                                    $lib .= '</div>';
                                $lib .= '</div>';
                            $lib .= '</div>';
                        }
                        $lib .= '</div>';
                    $lib .= '</div>';
                $lib .= '</div>';
        }
        $libreta = array('lib' => $lib);
        echo json_encode($libreta);
    }

    public function grafica($id_carrera, $nivel_curso, $id_paralelo)
    {
        if (($_SESSION['is_type_user'] == 'is_admin') || ($_SESSION['is_type_user'] == 'is_secretaria') || ($_SESSION['is_type_user'] == 'is_rector') || ($_SESSION['is_type_user'] == 'is_director')){
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

            $lero = 1;

            $materias_carrera_nivelcurso = $this->Admin_model->get_materia_by_all($id_carrera, $nivel_curso, $id_paralelo);
            // print_r($materias_carrera_nivelcurso);
            // exit();
            if (empty($materias_carrera_nivelcurso)) {
                $data['vacio'] = TRUE;
                $data['mensajevacio'] = "Los alumnos aún no tienen materias aprobadas";
            } else {
                foreach ($materias_carrera_nivelcurso as $materia) {
                    $students = $this->Admin_model->get_students($id_carrera, $materia->id_materia, $id_paralelo);
                    // En una gestion nueva las materias aun no tienen materias programadas por se esta comentando la siguiente
                    // if (count($students) == 0) {
                    //     continue;
                    // }

                    foreach ($students as $student) {
                        $notas = $this->Admin_model->get_note($student->id_alumno, $materia->id_materia, $id_paralelo);
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

                    if (count($students) == $count_pb_a) {
                        $count_pb_a = 0;
                    }
                    if (count($students) == $count_sb_a) {
                        $count_sb_a = 0;
                    }
                    if (count($students) == $count_tb_a) {
                        $count_tb_a = 0;
                    }
                    if (count($students) == $count_cb_a) {
                        $count_cb_a = 0;
                    }
                    if (count($students) == $count_st_a) {
                        $count_st_a = 0;
                    }
                    $aprobados[$materia->id_materia] = array(
                        array("label"=> "1 Bim", "y"=> $count_pb, "x"=>1, "a_r"=>"a"),
                        array("label"=> "2 Bim", "y"=> $count_sb, "x"=>2, "a_r"=>"a"),
                        array("label"=> "3 Bim", "y"=> $count_tb, "x"=>3, "a_r"=>"a"),
                        array("label"=> "4 Bim", "y"=> $count_cb, "x"=>4, "a_r"=>"a"),
                        array("label"=> "S. T.", "y"=> $count_st, "x"=>5, "a_r"=>"a"),
                    );

                    $reprobados[$materia->id_materia] = array(
                        array("label"=> "1 Bim", "y"=> $count_pb_a, "x"=>1, "a_r"=>"r"),
                        array("label"=> "2 Bim", "y"=> $count_sb_a, "x"=>2, "a_r"=>"r"),
                        array("label"=> "3 Bim", "y"=> $count_tb_a, "x"=>3, "a_r"=>"r"),
                        array("label"=> "4 Bim", "y"=> $count_cb_a, "x"=>4, "a_r"=>"r"),
                        array("label"=> "S. T.", "y"=> $count_st_a, "x"=>5, "a_r"=>"r"),
                    );

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

                    $una_materia['id_materia'] = $materia->id_materia;
                    $una_materia['nombre_materia'] = $materia->nombre_materia;
                    $una_materia['sigla'] = $materia->sigla;
                    $una_materia['docente'] = $materia->nombre_completo;

                    $materias_a[] = $una_materia;
                }
                //------------

                // print_r($materias_a);
                // print_r('<br>');
                // print_r($materias_carrera_nivelcurso);


                $nombre_carrera = $this->db->get_where('carrera', array('id_carrera' => $id_carrera))->row()->nombre_carrera;
                $nombre_paralelo = $this->db->get_where('paralelo', array('id_paralelo' => $id_paralelo))->row()->nombre;
                $data['dataPoints1'] = $aprobados;
                $data['dataPoints2'] = $reprobados;
                
                $data['materias'] = $materias_a;

                $data['nombre_carrera'] = $nombre_carrera;
                $data['nombre_paralelo'] = $nombre_paralelo;
                $data['vacio'] = FALSE;
            }
            $data['vista'] = 'admin/v_cake';
            $this->salida($data);
        } else {
            redirect(base_url() . '', 'refresh');
        }
    }

    public function get_reporte_bimestral()
    {

    }
    ////////////////////////// centralizador end ///////////////////////////////////

    ////////////////////////// backup begin ///////////////////////////////////
    public function backup()
    {
        $this->Admin_model->create_backup('all');
    }
    ////////////////////////// backup end   ///////////////////////////////////

    ////////////////////////// secretaria begin ///////////////////////////////////
    public function secretaria()
    {
        // if ($_SESSION['is_type_user'] != 'is_secretaria')
            
        if ($_SESSION['is_type_user'] == 'is_secretaria' || $_SESSION['is_type_user'] == 'is_rector'){
            $data['vista'] = 'v_principal';
            $this->salida($data);
        } else {
            redirect(base_url() . '', 'refresh');
        }
    }
    ////////////////////////// secretaria end ///////////////////////////////////

    public function rector()
    {
        // if ($_SESSION['is_type_user'] != 'is_secretaria')
            
        if ($_SESSION['is_type_user'] == 'is_rector'){
            $data['vista'] = 'v_principal';
            $this->salida($data);
        } else {
            redirect(base_url() . '', 'refresh');
        }
    }

    public function director()
    {
        // if ($_SESSION['is_type_user'] != 'is_secretaria')
            
        if ($_SESSION['is_type_user'] == 'is_director'){
            $data['vista'] = 'v_principal';
            $this->salida($data);
        } else {
            redirect(base_url() . '', 'refresh');
        }
    }


    ////////////////////////// list de alumno aprobados y reprobados begin /////////////////////////////////
    // public function list_alumnos_aprobados_reprobados($id_materia, $paralelo, $bim, $a_r)
    public function list_alumnos_aprobados_reprobados()
    {
        $id_materia = $this->input->post('id_materia');
        $nombre_paralelo = $this->input->post('paralelo');
        $id_paralelo = $this->db->get_where('paralelo', array('nombre' => $nombre_paralelo))->row()->id_paralelo;
        $num_bim = $this->input->post('bim');
        $a_r = $this->input->post('a_r');

        // print_r($id_materia);
        // // print_r($nombre_paralelo);
        // print_r($id_paralelo);
        // print_r($num_bim);
        // print_r($a_r);
        // exit();

        // $students = $this->Teacher_model->get_students($id_carrera, $id_materia, $id_paralelo, $id_gestionperiodo);
        $students = $this->Admin_model->get_students_a_r($id_materia, $id_paralelo);

        $i=1;
        $note = '';
        if ($num_bim == 1) {
            if ($a_r == 'a') {
                foreach ($students as $student) {
                    $note .='<tr>';
                    $notas = $this->Admin_model->get_note($student->id_alumno, $id_materia, $id_paralelo);
                    if ($this->encrypt->decode($notas->primer_bim) >= 61) {
                        $note .='<td>'.$i++.'</td>';
                        $note .='<td>'.$student->apellido_paterno.' '.$student->apellido_materno.' '.$student->nombres.'</td>';
                        $note .='<td class="primer_bim">'.$this->encrypt->decode($notas->primer_bim).'</td>';
                    }
                    $note .='</tr>';
                }
                $estado = "aprobados";
            } else {
                foreach ($students as $student) {
                    $note .='<tr>';
                    $notas = $this->Admin_model->get_note($student->id_alumno, $id_materia, $id_paralelo);
                    if ($this->encrypt->decode($notas->primer_bim) < 61) {
                        $note .='<td>'.$i++.'</td>';
                        $note .='<td>'.$student->apellido_paterno.' '.$student->apellido_materno.' '.$student->nombres.'</td>';
                        $note .='<td class="primer_bim">'.$this->encrypt->decode($notas->primer_bim).'</td>';
                    }
                    $note .='</tr>';
                }
                $estado = "reprobados";
            }
        } elseif ($num_bim == 2) {
            if ($a_r == 'a') {
                foreach ($students as $student) {
                    $note .='<tr>';
                    $notas = $this->Admin_model->get_note($student->id_alumno, $id_materia, $id_paralelo);
                    if ($this->encrypt->decode($notas->segundo_bim) >= 61) {
                        $note .='<td>'.$i++.'</td>';
                        $note .='<td>'.$student->apellido_paterno.' '.$student->apellido_materno.' '.$student->nombres.'</td>';
                        $note .='<td class="segundo_bim">'.$this->encrypt->decode($notas->segundo_bim).'</td>';
                    }
                    $note .='</tr>';
                }
                $estado = "aprobados";
            } else {
                foreach ($students as $student) {
                    $note .='<tr>';
                    $notas = $this->Admin_model->get_note($student->id_alumno, $id_materia, $id_paralelo);
                    if ($this->encrypt->decode($notas->segundo_bim) < 61) {
                        $note .='<td>'.$i++.'</td>';
                        $note .='<td>'.$student->apellido_paterno.' '.$student->apellido_materno.' '.$student->nombres.'</td>';
                        $note .='<td class="segundo_bim">'.$this->encrypt->decode($notas->segundo_bim).'</td>';
                    }
                    $note .='</tr>';
                }
                $estado = "reprobados";
            }
        } elseif ($num_bim == 3) {
            if ($a_r == 'a') {
                foreach ($students as $student) {
                    $note .='<tr>';
                    $notas = $this->Admin_model->get_note($student->id_alumno, $id_materia, $id_paralelo);
                    if ($this->encrypt->decode($notas->tercer_bim) >= 61) {
                        $note .='<td>'.$i++.'</td>';
                        $note .='<td>'.$student->apellido_paterno.' '.$student->apellido_materno.' '.$student->nombres.'</td>';
                        $note .='<td class="tercer_bim">'.$this->encrypt->decode($notas->tercer_bim).'</td>';
                    }
                    $note .='</tr>';
                }
                $estado = "aprobados";
            } else {
                foreach ($students as $student) {
                    $note .='<tr>';
                    $notas = $this->Admin_model->get_note($student->id_alumno, $id_materia, $id_paralelo);
                    if ($this->encrypt->decode($notas->tercer_bim) < 61) {
                        $note .='<td>'.$i++.'</td>';
                        $note .='<td>'.$student->apellido_paterno.' '.$student->apellido_materno.' '.$student->nombres.'</td>';
                        $note .='<td class="tercer_bim">'.$this->encrypt->decode($notas->tercer_bim).'</td>';
                    }
                    $note .='</tr>';
                }
                $estado = "reprobados";
            }
        } elseif ($num_bim == 4) {
            if ($a_r == 'a') {
                foreach ($students as $student) {
                    $note .='<tr>';
                    $notas = $this->Admin_model->get_note($student->id_alumno, $id_materia, $id_paralelo);
                    if ($this->encrypt->decode($notas->cuarto_bim) >= 61) {
                        $note .='<td>'.$i++.'</td>';
                        $note .='<td>'.$student->apellido_paterno.' '.$student->apellido_materno.' '.$student->nombres.'</td>';
                        $note .='<td class="cuarto_bim">'.$this->encrypt->decode($notas->cuarto_bim).'</td>';
                    }
                    $note .='</tr>';
                }
                $estado = "aprobados";
            } else {
                foreach ($students as $student) {
                    $note .='<tr>';
                    $notas = $this->Admin_model->get_note($student->id_alumno, $id_materia, $id_paralelo);
                    if ($this->encrypt->decode($notas->cuarto_bim) < 61) {
                        $note .='<td>'.$i++.'</td>';
                        $note .='<td>'.$student->apellido_paterno.' '.$student->apellido_materno.' '.$student->nombres.'</td>';
                        $note .='<td class="cuarto_bim">'.$this->encrypt->decode($notas->cuarto_bim).'</td>';
                    }
                    $note .='</tr>';
                }
                $estado = "reprobados";
            }
        } elseif ($num_bim == 5) {
            if ($a_r == 'a') {
                foreach ($students as $student) {
                    $note .='<tr>';
                    $notas = $this->Admin_model->get_note($student->id_alumno, $id_materia, $id_paralelo);
                    if ($this->encrypt->decode($notas->segundo_turno) >= 61) {
                        $note .='<td>'.$i++.'</td>';
                        $note .='<td>'.$student->apellido_paterno.' '.$student->apellido_materno.' '.$student->nombres.'</td>';
                        $note .='<td class="segundo_turno">'.$this->encrypt->decode($notas->segundo_turno).'</td>';
                    }
                    $note .='</tr>';
                }
                $estado = "aprobados";
            } else {
                foreach ($students as $student) {
                    $note .='<tr>';
                    $notas = $this->Admin_model->get_note($student->id_alumno, $id_materia, $id_paralelo);
                    if ($this->encrypt->decode($notas->segundo_turno) < 61) {
                        $note .='<td>'.$i++.'</td>';
                        $note .='<td>'.$student->apellido_paterno.' '.$student->apellido_materno.' '.$student->nombres.'</td>';
                        $note .='<td class="segundo_turno">'.$this->encrypt->decode($notas->segundo_turno).'</td>';
                    }
                    $note .='</tr>';
                }
                $estado = "reprobados";
            }
        }
        
        $materia = $this->db->get_where('materia', array('id_materia' => $id_materia))->row()->nombre;
        $notes = array('note' => $note, 'status'=> TRUE, 'estado' => $estado, 'materia'=>$materia, 'paralelo'=>$nombre_paralelo, 'bim'=> $num_bim);
        echo json_encode($notes);
    }
    ////////////////////////// list de alumno aprobados y reprobados end ///////////////////////////////////

    ////////////////////////// retirados begin ///////////////////////////////////
    public function retirados()
    {
        $sexos = ['Masculino', 'Femenino'];
        $bimestre = ['primer_bim', 'segundo_bim', 'tercer_bim', 'cuarto_bim'];
        // $masculino = 0;
        // $femenino = 0;
        $efectivo = FALSE;
        foreach ($sexos as $sexo) {
            $alumnos = $this->Admin_model->get_alumnos_r($sexo, $efectivo);
            $retirado = 0;
            foreach ($alumnos as $alumno) {
                $count_cero_bimestre = 0;
                foreach ($bimestre as $bim) {
                    $notas = $this->Admin_model->notas_retirados($alumno->id_alumno, $bim);
                    $count_cer_materia = 0;
                    foreach ($notas as $nota) {
                        if ($this->encrypt->decode($nota->$bim) == 0) {
                            $count_cer_materia++;
                            continue;
                        }
                        break;
                    }
                    if ($count_cer_materia == count($notas)) {
                        $count_cero_bimestre++;
                        continue;
                    }
                    break;
                }
                if ($count_cero_bimestre == count($bimestre)) {
                    $retirado++;
                }
            }
            if ($sexo == $sexos[0]) {
                $masculino = $retirado;
            } else {
                $femenino = $retirado;
            }
        }

        $efectivo = TRUE;
        foreach ($sexos as $sexo) {
            if ($sexo == $sexos[0]) {
                $masculino_efe = $this->Admin_model->get_alumnos_r($sexo, $efectivo);
            } else {
                $femenino_efe = $this->Admin_model->get_alumnos_r($sexo, $efectivo);
            }
        }
        echo json_encode(array('hombres' => $masculino, 'femenino'=>$femenino, 'total'=>($masculino+$femenino), 'hombres_efe' => $masculino_efe, 'femenino_efe'=>$femenino_efe, 'total_efe'=>($masculino_efe+$femenino_efe)));
    }
    ////////////////////////// retirados end   ///////////////////////////////////

    ////////////////////////// alumnos efectivos begin /////////////////////////////////
    public function efectivo($value='')
    {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {

        // if ($_SESSION['is_type_user'] != 'is_admin')
        //     redirect(base_url() . '', 'refresh');
        // if ($_SESSION['is_type_user'] == 'is_admin'){
            // $data['person'] = $this->Profile_model->get_person($_SESSION['user_id']);
            $data['carreras'] = $this->Admin_model->get_carreras();

            // print_r('<pre>');
            // print_r($data['carreras']);


            // print_r($this->Profile_model->get_person($_SESSION['user_id']));
            // exit();
            $data['vista'] = 'admin/v_alumnos_efectivos';
            $this->salida($data);
        // }
        } else {
            redirect('/');
        }
    }

    public function FunctionName($value='')
    {
        $materias = $this->Centralizador_model->get_materias($id_carrera, $id_paralelo, $id_plan);
        foreach ($materias as $materia) {
            // devuelve notas con su respecitvo sexo
            $notas = $this->Admin_model->get_nota_with_sexo($id_plan, $id_carrera, $id_paralelo, $materia->id_materia, $bimestre);

            $efectivos_hombres = 0;
            $efectivos_mujeres = 0;
            foreach ($notas as $nota) {
                if ($nota->sexo == 'Masculino') {
                    if ($this->encrypt->decode($nota->$bimestre) > 0) {
                        $efectivos_hombres++;
                    }
                } else {
                    if ($this->encrypt->decode($nota->$bimestre) > 0) {
                        $efectivos_mujeres++;
                    }
                }
            }
            // $cent .= '<th style="text-align:center">'.$efectivos.'</th>';
            $cent .= '<td>'.$efectivos_hombres.'</td>';
            // $cent .= '<tr>';
            // $cent .= '<td>'.$efectivos_mujeres.'</td>';
            // $cent .= '</tr>';
        }
    }


    public function get_alumnos_efectivos($value='')
    {

        $id_carrera = $this->input->post('carreras');
        $id_paralelo = $this->input->post('paralelos');
        $id_plan = $this->input->post('plan');
        $bimestre = $this->input->post('bimestre');


        $cent = '';

        $materias = $this->Centralizador_model->get_materias($id_carrera, $id_paralelo, $id_plan);


        $cent .= '<table class="table table-xxs table-header-rotated">';
            $cent .= '<thead>';
                $cent .= '<tr>';
                    // $cent .= '<th class="text-center col-lg-1"><div> </div></th>';
                    $cent .= '<th class="text-center"><div> </div></th>';
                    foreach ($materias as $materia) {
                        $cent .= '<th class="rotate"><div><h5 style="margin-top: 0px;margin-bottom: 0px;">'.$materia->nombre_materia.'</h5>'.$materia->nombre_completo.'</div></th>';
                    }
                $cent .= '</tr>';
            $cent .= '</thead>';
            $cent .= '<tbody>';
                $cent .= '<tr>';
                    // $cent .= '<th style="text-align:right">Alumnos efectivos:</th>';
                    $cent .= '<td>Alumnos efectivos:</td>';
                    foreach ($materias as $materia) {
                        // devuelve notas con su respecitvo sexo
                        $notas = $this->Admin_model->get_nota_with_sexo($id_plan, $id_carrera, $id_paralelo, $materia->id_materia, $bimestre);

                        $efectivos_hombres = 0;
                        foreach ($notas as $nota) {
                            if ($nota->sexo == 'Masculino') {
                                if ($this->encrypt->decode($nota->$bimestre) > 0) {
                                    $efectivos_hombres++;
                                }
                            }
                        }
                        // $cent .= '<th style="text-align:center">'.$efectivos.'</th>';
                        $cent .= '<td>'.$efectivos_hombres.'</td>';
                    }
                $cent .= '</tr>';
                $cent .= '<tr>';
                    // $cent .= '<th style="text-align:right">Alumnos efectivos:</th>';
                    $cent .= '<td>Alumnas efectivas:</td>';
                    foreach ($materias as $materia) {
                        // devuelve notas con su respecitvo sexo
                        $notas = $this->Admin_model->get_nota_with_sexo($id_plan, $id_carrera, $id_paralelo, $materia->id_materia, $bimestre);
                        $efectivos_mujeres = 0;
                        foreach ($notas as $nota) {
                            if ($nota->sexo == 'Femenino') {
                                if ($this->encrypt->decode($nota->$bimestre) > 0) {
                                    $efectivos_mujeres++;
                                }
                            } 
                        }
                        // $cent .= '<th style="text-align:center">'.$efectivos.'</th>';
                        $cent .= '<td>'.$efectivos_mujeres.'</td>';
                    }
                $cent .= '</tr>';
                $cent .= '<tr>';
                    $cent .= '<td style="text-align:right">Total:</td>';
                    foreach ($materias as $materia) {
                        // devuelve notas con su respecitvo sexo
                        $notas = $this->Admin_model->get_nota_with_sexo($id_plan, $id_carrera, $id_paralelo, $materia->id_materia, $bimestre);
                        $i=0;
                        foreach ($notas as $nota) {
                            if ($this->encrypt->decode($nota->$bimestre) > 0) {
                                $i++;
                            }
                        }
                        // $cent .= '<th style="text-align:center">'.$efectivos.'</th>';
                        $cent .= '<td>'.$i.'</td>';
                    }
                $cent .= '</tr>';

                // $cent .= '<tr>';
                //     $cent .= '<td>Alumnos efectivos:</td>';
                //     $cent .= '<td>'.$efectivos_hombres.'</td>';
                //     $cent .= '<td>'.$efectivos_mujeres.'</td>';
                // $cent .= '</tr>';
                // $cent .= '<tr>';
                //     $cent .= '<td>Alumnos efectivos:</td>';
                //     $cent .= '<td>'.$efectivos_hombres.'</td>';
                //     $cent .= '<td>'.$efectivos_mujeres.'</td>';
                // $cent .= '</tr>';

                $cent .= '<tr>';
                    $cent .= '<td>Alumnos aprobados: </td>';
                        foreach ($materias as $materia) {
                            $notas = $this->Admin_model->get_nota_with_sexo($id_plan, $id_carrera, $id_paralelo, $materia->id_materia, $bimestre);
                            // $count_efe = 1;
                            $aprobados = 0;
                            foreach ($notas as $nota) {
                                if ($nota->sexo == 'Masculino') {
                                    if ($this->encrypt->decode($nota->$bimestre) >= 61) {
                                        $aprobados++;
                                    }
                                }
                            }
                            // $cent .= '<th style="text-align:center">'.$aprobados.'</th>';
                            $cent .= '<td>'.$aprobados.'</td>';
                        }
                $cent .= '</tr>';
                $cent .= '<tr>';
                    $cent .= '<td>Alumnas aprobadas: </td>';
                        foreach ($materias as $materia) {
                            $notas = $this->Admin_model->get_nota_with_sexo($id_plan, $id_carrera, $id_paralelo, $materia->id_materia, $bimestre);
                            // $count_efe = 1;
                            $aprobados = 0;
                            foreach ($notas as $nota) {
                                if ($nota->sexo == 'Femenino') {
                                    if ($this->encrypt->decode($nota->$bimestre) >= 61) {
                                        $aprobados++;
                                    }
                                }
                            }
                            // $cent .= '<th style="text-align:center">'.$aprobados.'</th>';
                            $cent .= '<td>'.$aprobados.'</td>';
                        }
                $cent .= '</tr>';
                $cent .= '<tr>';
                    $cent .= '<td style="text-align:right">Total:</td>';
                    foreach ($materias as $materia) {
                        // devuelve notas con su respecitvo sexo
                        $notas = $this->Admin_model->get_nota_with_sexo($id_plan, $id_carrera, $id_paralelo, $materia->id_materia, $bimestre);
                        $i=0;
                        foreach ($notas as $nota) {
                            if ($this->encrypt->decode($nota->$bimestre) >= 61) {
                                $i++;
                            }
                        }
                        // $cent .= '<th style="text-align:center">'.$efectivos.'</th>';
                        $cent .= '<td>'.$i.'</td>';
                    }
                $cent .= '</tr>';


                $cent .= '<tr>';
                    $cent .= '<td>Alumnos reprobados: </td>';
                        foreach ($materias as $materia) {
                            $notas = $this->Admin_model->get_nota_with_sexo($id_plan, $id_carrera, $id_paralelo, $materia->id_materia, $bimestre);
                            $reprobados = 0;
                            foreach ($notas as $nota) {
                                if ($nota->sexo == 'Masculino') {
                                    if ($this->encrypt->decode($nota->$bimestre) < 61 && $this->encrypt->decode($nota->$bimestre) > 0) {
                                        $reprobados++;
                                    }
                                }
                            }
                            // $cent .= '<th style="text-align:center">'.$reprobados.'</th>';
                            $cent .= '<td>'.$reprobados.'</td>';
                        }
                $cent .= '</tr>';
                $cent .= '<tr>';
                    $cent .= '<td>Alumnas reprobadas: </td>';
                        foreach ($materias as $materia) {
                            $notas = $this->Admin_model->get_nota_with_sexo($id_plan, $id_carrera, $id_paralelo, $materia->id_materia, $bimestre);
                            $reprobados = 0;
                            foreach ($notas as $nota) {
                                if ($nota->sexo == 'Femenino') {
                                    if ($this->encrypt->decode($nota->$bimestre) < 61 && $this->encrypt->decode($nota->$bimestre) > 0) {
                                        $reprobados++;
                                    }
                                }
                            }
                            // $cent .= '<th style="text-align:center">'.$reprobados.'</th>';
                            $cent .= '<td>'.$reprobados.'</td>';
                        }
                $cent .= '</tr>';
                $cent .= '<tr>';
                    $cent .= '<td style="text-align:right">Total:</td>';
                    foreach ($materias as $materia) {
                        // devuelve notas con su respecitvo sexo
                        $notas = $this->Admin_model->get_nota_with_sexo($id_plan, $id_carrera, $id_paralelo, $materia->id_materia, $bimestre);
                        $i=0;
                        foreach ($notas as $nota) {
                            if ($this->encrypt->decode($nota->$bimestre) < 61 && $this->encrypt->decode($nota->$bimestre) > 0) {
                                $i++;
                            }
                        }
                        // $cent .= '<th style="text-align:center">'.$efectivos.'</th>';
                        $cent .= '<td>'.$i.'</td>';
                    }
                $cent .= '</tr>';


                $cent .= '<tr>';
                    $cent .= '<td>% de alumos aprobados </td>';
                        foreach ($materias as $materia) {
                            $notas = $this->Centralizador_model->get_estadistica_cent($id_plan, $id_carrera, $id_paralelo, $materia->id_materia, $bimestre);
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
                            
                            // $cent .= '<th style="text-align:center">'.round($por_aproba, 0).'%</th>';
                            $cent .= '<td>'.round($por_aproba, 0).'</td>';
                        }
                $cent .= '</tr>';
                $cent .= '<tr>';
                    $cent .= '<td>% de alumnos reprobados</td>';
                        foreach ($materias as $materia) {
                            $notas = $this->Centralizador_model->get_estadistica_cent($id_plan, $id_carrera, $id_paralelo, $materia->id_materia, $bimestre);
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

                            // $cent .= '<th style="text-align:center">'.round($por_reproba, 0).'%</th>';
                            $cent .= '<td>'.round($por_reproba, 0).'</td>';
                        }
                $cent .= '</tr>';
                $cent .= '<tr>';
                    $cent .= '<td>Alumnos retirados: </td>';
                        foreach ($materias as $materia) {
                            $notas = $this->Admin_model->get_nota_with_sexo($id_plan, $id_carrera, $id_paralelo, $materia->id_materia, $bimestre);
                            // $count_efe = 1;
                            $retirados = 0;
                            foreach ($notas as $nota) {
                                if ($nota->sexo == 'Masculino') {
                                    if ($this->encrypt->decode($nota->$bimestre) == 0) {
                                        $retirados++;
                                    }
                                }
                            }
                            // $cent .= '<th style="text-align:center">'.$retirados.'</th>';
                            $cent .= '<td>'.$retirados.'</td>';
                        }
                $cent .= '</tr>';
                $cent .= '<tr>';
                    $cent .= '<td>Alumnas retiradas: </td>';
                        foreach ($materias as $materia) {
                            $notas = $this->Admin_model->get_nota_with_sexo($id_plan, $id_carrera, $id_paralelo, $materia->id_materia, $bimestre);
                            // $count_efe = 1;
                            $retirados = 0;
                            foreach ($notas as $nota) {
                                if ($nota->sexo == 'Femenino') {
                                    if ($this->encrypt->decode($nota->$bimestre) == 0) {
                                        $retirados++;
                                    }
                                }
                            }
                            // $cent .= '<th style="text-align:center">'.$retirados.'</th>';
                            $cent .= '<td>'.$retirados.'</td>';
                        }
                $cent .= '</tr>';
                $cent .= '<tr>';
                    $cent .= '<td style="text-align:right">Total:</td>';
                    foreach ($materias as $materia) {
                        // devuelve notas con su respecitvo sexo
                        $notas = $this->Admin_model->get_nota_with_sexo($id_plan, $id_carrera, $id_paralelo, $materia->id_materia, $bimestre);
                        $i=0;
                        foreach ($notas as $nota) {
                            if ($this->encrypt->decode($nota->$bimestre) == 0) {
                                $i++;
                            }
                        }
                        // $cent .= '<th style="text-align:center">'.$efectivos.'</th>';
                        $cent .= '<td>'.$i.'</td>';
                    }
                $cent .= '</tr>';


                $cent .= '<tr>';
                    $cent .= '<td>Promedio (Nota): </td>';
                        foreach ($materias as $materia) {
                            $notas = $this->Centralizador_model->get_estadistica_cent($id_plan, $id_carrera, $id_paralelo, $materia->id_materia, $bimestre);
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
                            // $cent .= '<th style="text-align:center">'.round($promedio, 0).'</th>';
                            $cent .= '<td>'.round($promedio, 0).'</td>';
                        }
                $cent .= '</tr>';

                $cent .= '<tr>';
                    $cent .= '<td style="text-align:right">Total alumnos inscritos:</td>';
                    foreach ($materias as $materia) {
                        // devuelve notas con su respecitvo sexo
                        $notas = $this->Admin_model->get_nota_with_sexo($id_plan, $id_carrera, $id_paralelo, $materia->id_materia, $bimestre);
                        // $i=0;
                        // foreach ($notas as $nota) {
                        //     if ($this->encrypt->decode($nota->$bimestre) == 0) {
                        //         $i++;
                        //     }
                        // }
                        // $cent .= '<th style="text-align:center">'.$efectivos.'</th>';
                        $cent .= '<td>'.count($notas).'</td>';
                    }
                $cent .= '</tr>';
            $cent .= '</tbody>';
        $cent .= '</table>';

        $centralizador = array('status'=>TRUE, 'cent' => $cent);
        echo json_encode($centralizador);
    }
    ////////////////////////// alumnos efectivos end ///////////////////////////////////


    ////////////////////////// ver que docente ha subido alguna nota begin   ///////////////////////////////////

    public function uploadnotes()
    {
        if (($_SESSION['is_type_user'] == 'is_admin') || ($_SESSION['is_type_user'] == 'is_secretaria') || ($_SESSION['is_type_user'] == 'is_rector') || $_SESSION['is_type_user'] == 'is_director'){
            $data['vista'] = 'admin/v_upload_note_teacher';
            $this->salida($data);
        } else {
            redirect(base_url() . '', 'refresh');
        }
    }

    public function list_uploadnotes()
    {
        $list = $this->Admin_model->get_datatables_upload_notes();
        // print_r('<pre>');
        // print_r($list);
        // exit();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $person) {
            // $eso = $this->Admin_model->get_cantidad($person->id_materia, $person->id_paralelo);
            // print_r('<pre>');
            // print_r($eso);
            // exit();
            $no++;
            $row = array();
            $row[] = $person->apellido_paterno.' '.$person->apellido_materno.', '.$person->nombres;
            $row[] = $person->nombre_materia;
            $row[] = $person->sigla;
            $row[] = $person->nombre_paralelo;
            $row[] = 'si';
            $row[] = 'no';
            $row[] = 'no';
            $row[] = 'no';
            $row[] = 'no';
            $row[] = 'no';
            // $row[] = $person->sigla;

            // $row[] = '<ul class="icons-list">
            //             <li class="text-primary-600">
            //             <a href="javascript:void(0)" class="btn border-warning text-warning btn-flat btn-rounded btn-icon btn-xs" title="Confirmar" onclick="confirmar_inscripcion('."'".$person->id_persona."'".')"><i class="icon-spam"></i></a>
            //             </li>
            //         </ul>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Admin_model->count_filtered_upload_notes(),
            "recordsFiltered" => $this->Admin_model->count_all_upload_notes(),
            "data" => $data,
        );
        echo json_encode($output);
    }
    ////////////////////////// ver que docente ha subido alguna nota end   /////////////////////////////////////

    /////////////// activar materia por docente begin  /////////////////////////
    public function list_teacher_subjects_active_bimestre()
    {
        $list = $this->Admin_model->get_datatables_list_teacher_subjects_active_bimestre();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $person) {
            $no++;
            $row = array();
            $row[] = $person->sigla;
            $row[] = $person->nombre_materia;
            $row[] = $person->nombre_carrera;
            $row[] = $person->nombre_paralelo;
            $row[] = $person->apellido_paterno.' '.$person->apellido_materno.', '.$person->nombres;

            // $bimestres = $this->Admin_model->get_activebim();

            $this->verificar_bimestres($person->id_asignaciondocente);

            $bimestres_docente = $this->db->get_where('habilitarbimestre', array('id_asignaciondocente' => $person->id_asignaciondocente,
            ))->row()->bimestre;
            $deco = json_decode($bimestres_docente, TRUE);

            $cad = "";
            $chk = "";
            foreach ($deco as $key => $value) {
                if ($value != 0)
                    $chk = "checked";
                $cad .= "<label><input disabled='disabled' type='checkbox' onclick='ShowHideDiv(".$person->id_asignaciondocente.")' class='minimal' id='id_menu".$person->id_asignaciondocente."' name='id_menu[]' value='".$key."' ".$chk."> $key</label>" ;
                $chk = "";
            }

            // $id_docente ='<td type="hidden" class="clase_id_alumno"><input name="alumno[]" id="alumno'.$person->id_asignaciondocente.'" type="hidden" value="'.$person->id_asignaciondocente.'"></td>';

            // $row[] = $id_docente;
            $row[] = $cad;

            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar" onclick="edit_activebimestre('.$person->id_asignaciondocente.')"><i class="glyphicon glyphicon-pencil"></i> Editar</a>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Admin_model->count_filtered_active_bimestre(),
            "recordsFiltered" => $this->Admin_model->count_all_active_bimestre(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function verificar_bimestres($id_asignaciondocente)
    {
        $asignaciondocente = $this->db->get_where('habilitarbimestre', array(
            'id_asignaciondocente' => $id_asignaciondocente,
        ))->row();

        if (empty($asignaciondocente)) {
            $data = array(
                'bimestre' => '{"1B":"0", "2B":"0", "3B":"0", "4B":"0", "NF":"0", "ST":"0"}',
                'id_asignaciondocente' => $id_asignaciondocente,
                'estado' => 'N',
            );
            $this->Admin_model->save_habilitarbimestre($data);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function list_activebimestre()
    {
        $id_materia = $this->input->post('id_materia');
        $bimestres_docente = $this->db->get_where('habilitarbimestre', array('id_asignaciondocente' => $id_materia))->row()->bimestre;

        $deco = json_decode($bimestres_docente, TRUE);

        $cad = "";
        $chk = "";
        $no = 1;
        foreach ($deco as $key => $value) {

            if ($value != 0)
                $chk = "checked";
            $cad .= "<label><input type='checkbox' class='minimal' name='id_menu[]' value='".$no++."' ".$chk."> $key</label> <br>" ;
            $chk = "";
        }
        echo json_encode($cad);
    }

    public function add_activebimestre()
    {
        // if (!empty($_POST["id_menu"]) && isset($_POST["id_menu"]) ) {
            $id_asignaciondocente = $this->input->post('id_materia');
            $arraylleno = array(1, 2, 3, 4, 5, 6);
            if (empty($_POST['id_menu'])) {
                $arrayUnos = $arraylleno;
            } else {
                $arrayUnos = $_POST['id_menu'];
            }
            
            $arrayCeros = array_diff($arraylleno, $arrayUnos);
            $losdos= array($arrayCeros, $arrayUnos);
            $no = 0;
            foreach ($losdos as $value) {
                foreach ($value as $key) {
                    switch ($key) {
                        case 1:
                            $pb = $no == 0 || empty($_POST['id_menu']) ? 0 : 1;
                            break;
                        case 2:
                            $sb = $no == 0 || empty($_POST['id_menu']) ? 0 : 1;
                            break;
                        case 3:
                            $tb = $no == 0 || empty($_POST['id_menu']) ? 0 : 1;
                            break;
                        case 4:
                            $cb = $no == 0 || empty($_POST['id_menu']) ? 0 : 1;
                            break;
                        case 5:
                            $nf = $no == 0 || empty($_POST['id_menu']) ? 0 : 1;
                            break;
                        default:
                            $st = $no == 0 || empty($_POST['id_menu']) ? 0 : 1;
                            break;
                    }
                }
                $no++;
            }

            $data = array(
                'bimestre' => '{"1B":"'.$pb.'", "2B":"'.$sb.'", "3B":"'.$tb.'", "4B":"'.$cb.'", "NF":"'.$nf.'", "ST":"'.$st.'"}',
                'estado' => 'S',
            );
            $this->Admin_model->update_habilitarbimestre(array('id_asignaciondocente' => $id_asignaciondocente),$data);
            echo json_encode(array("status" => TRUE));
        // } else {
        //     echo json_encode(array("status" => FALSE));
        // }

    }

    /////////////// activar materia por docente  end   /////////////////////////

    /////////////// programar materias a alumno  begin /////////////////////////
    public function program_students()
    {
        $data['vista'] = 'admin/v_programar_alumnos';
        $this->salida($data);
    }

    public function list_de_alumnos_a_programar()
    {
        $list = $this->Admin_model->lista_alumnos_antiguos();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $person) {
            $no++;
            $row = array();
            $row[] = $person->nombres;
            $row[] = $person->apellido_paterno;
            $row[] = $person->apellido_materno;
            $row[] = $person->ci.' '.$person->expedido;
            $row[] = $person->email;
            $row[] = $person->fecha_nacimiento;
            // $row[] = $person->direccion;
            // $row[] = $person->celular;
            // $row[] = $person->lugar_trabajo;

            //add html for action
            $btnProgramar = '';
            $btnMatricula = '';
            if ($this->Admin_model->verifica_matriculacion($person->id_alumno)) {
                $btnProgramar = '<a class="btn btn-sm btn-default" href="'.base_url().'alumno/programar_a_alumno/'.$person->id_alumno.'" title="Matricular"><i class="glyphicon glyphicon-pencil"></i> Programar</a>';
            } else {
                $btnMatricula = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar" onclick="confirmar_matriculacion('."'".$person->id_alumno."'".')"><i class="glyphicon glyphicon-pencil"></i> Matricular</a>';
            }
            $row[] = $btnMatricula.$btnProgramar;
        
            $data[] = $row;
        }

        $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->Admin_model->count_filtered_antiguos_alumnos(),
                "recordsFiltered" => $this->Admin_model->count_all_antiguos_alumnos(),
                "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function confirmar_matriculacion()
    {
        $id_alumno = $this->input->post('id_alumno');
        $ultimo_curso = $this->Admin_model->ultimo_curso_revisar($id_alumno);
        $ultimo_gestionperiodo = $this->Admin_model->ultimogestion();
        // $alumno = $this->Admin_model->get_alumno_by_id($this->input->post('id'));

        $dataMatricula = array(
            // aqui podemos poner por defecto 1 ya que se programara el primer curso
            'id_curso' => $ultimo_curso->id_curso + 1,
            // 'id_curso' => 0,
            'id_gestionperiodo' => $ultimo_gestionperiodo->id_gestionperiodo,
            'id_alumno' => $id_alumno,
        );
        $this->Admin_model->insertar_matricula($dataMatricula);

        echo json_encode(array("status" => TRUE));
    }

    /////////////// programar materias a alumno  end   /////////////////////////

























  
    //---------------carnet por alumno---//////////   
    public function carnet_pdf($id_alumno)
    {
       if (($_SESSION['is_type_user'] == 'is_admin')||($_SESSION['is_type_user'] == 'is_secretaria') || ($_SESSION['is_type_user'] == 'is_director') || ($_SESSION['is_type_user'] == 'is_rector')){
        //$id_alumno = $this->input->post('id_alumno');
        $ultimo_gestionperiodo = $this->Admin_model->ultimogestion()->id_gestionperiodo;
        $list = $this->Admin_model->get_boletin_alumno($ultimo_gestionperiodo, $id_alumno);
        

        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $tz = 'America/La_Paz';
        $timestamp = time();
        $dt = new DateTime("now", new DateTimeZone($tz)); //first argument "must" be a string
        $dt->setTimestamp($timestamp); //adjust the object to correct timestamp
        $note = '';

        $d='';
        foreach ($list as $personqr) {
            $d .= ''.$personqr->sigla.' '.$this->encrypt->decode($personqr->primer_bim).' '.$this->encrypt->decode($personqr->segundo_bim).' ';
            $d .= ''.$this->encrypt->decode($personqr->tercer_bim).' '.$this->encrypt->decode($personqr->cuarto_bim).' '.$this->encrypt->decode($personqr->final).' ';
        }
        
        $note .='
        <html>
        <title>'.(string)$list[0]->nombres.' '.(string)$list[0]->apellido_paterno.' '.(string)$list[0]->apellido_materno.'</title>
        <head>
        <style>

        body{
            background-image: url('.base_url().'/assets/images/carnet.png);
            background-position: top left;
            background-repeat: no-repeat;
            background-image-resize: 3;
        }

        </style>
        </head>
        <body>
        ';
        
        $note .='

        <br>
        <br>
        <br>
        <br>
        ';  
        
        $note .='<table width="50%">';    
        $note .='<thead>'; 
           
        $note .='<tr>';    
        $note .='<th width="48%"><span style="color:white; font-size:8pt; font-family:courier; font-weight: normal;
        font-style: normal;"><b>n</b></span></th>';    
        $note .='<th align="left"><span style="color:black; font-size:8pt; font-family:courier; font-weight: normal;
        font-style: normal;"><b>Apellidos y nombres: </b> '.(string)$list[0]->apellido_paterno.' '.(string)$list[0]->apellido_materno.' '.(string)$list[0]->nombres.'</span></th>';
        $note .='</tr>';   
        $note .='<tr>';    
        $note .='<th width="48%"><span style="color:white; font-size:8pt; font-family:courier; font-weight: normal;
        font-style: normal;"><b>n</b></span></th>';    
        $note .='<th align="left"><span style="color:black; font-size:8pt; font-family:courier; font-weight: normal;
        font-style: normal;"><b>Carrera: </b>'.(string)$list[0]->nombre_carrera.'</span></th>';
        $note .='</tr>'; 
        $note .='<tr>';    
        $note .='<th width="48%"><span style="color:white; font-size:8pt; font-family:courier; font-weight: normal;
        font-style: normal;"><b>n</b></span></th>';    
        $note .='<th align="left"><span style="color:black; font-size:8pt; font-family:courier; font-weight: normal;
        font-style: normal;"><b>CI: </b>'.(string)$list[0]->ci.'</span></th>';
        $note .='</tr>'; 
        $note .='<tr>';    
        $note .='<th width="48%"><span style="color:white; font-size:8pt; font-family:courier; font-weight: normal;
        font-style: normal;"><b>n</b></span></th>';    
        $note .='<th align="left"><span style="color:black; font-size:8pt; font-family:courier; font-weight: normal;
        font-style: normal;"><b>Fecha  : </b>'.$dt->format('d-m-Y').'</span></th>';
        $note .='</tr>'; 
        $note .='</thead>';    
    $note .='</table>';

    $note .='</body>
            </html>';
    $pdf=new mPDF('c','A4','','',10,10,10,25,16,13);

    $pdf->WriteHTML($note);
    $pdf->Output(''.(string)$list[0]->apellido_paterno.' '.(string)$list[0]->apellido_materno.' '.(string)$list[0]->nombres.'.pdf','I');
    exit;

    }else {
        redirect(base_url() . '', 'refresh');
    }
  //---------------CARNET por alumno---////////// 
    }


    //---------------matricula por alumno---//////////   
    public function matricula_pdf($id_alumno)
    {
       if (($_SESSION['is_type_user'] == 'is_admin')||($_SESSION['is_type_user'] == 'is_secretaria') || ($_SESSION['is_type_user'] == 'is_director') || ($_SESSION['is_type_user'] == 'is_rector')){
        //$id_alumno = $this->input->post('id_alumno');
        $ultimo_gestionperiodo = $this->Admin_model->ultimogestion()->id_gestionperiodo;
        $list = $this->Admin_model->get_boletin_alumno($ultimo_gestionperiodo, $id_alumno);
        

        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $tz = 'America/La_Paz';
        $timestamp = time();
        $dt = new DateTime("now", new DateTimeZone($tz)); //first argument "must" be a string
        $dt->setTimestamp($timestamp); //adjust the object to correct timestamp
        $note = '';

        $note .='
        <html>
        <title>'.(string)$list[0]->nombres.' '.(string)$list[0]->apellido_paterno.' '.(string)$list[0]->apellido_materno.'</title>
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
        <table width="50%">
        <thead>
        <tr>
            <th width="43%"><span style="color:white; font-size:8pt; font-family:courier; font-weight: normal;
            font-style: normal;"><b>n</b></span></th>
            <th width="45%" align="left"><span style="color:black; font-size:8pt; font-family:courier; font-weight: normal;
            font-style: normal;"><b>Apellidos y nombres: </b> '.(string)$list[0]->apellido_paterno.' '.(string)$list[0]->apellido_materno.' '.(string)$list[0]->nombres.'</span></th>
            <th width="12%"><span style="color:white; font-size:8pt; font-family:courier; font-weight: normal;
            font-style: normal;"><b>n</b></span></th>
        </tr>
        <tr>
            <th width="43%"><span style="color:white; font-size:8pt; font-family:courier; font-weight: normal;
            font-style: normal;"><b>n</b></span></th>
            <th width="45%" align="left"><span style="color:black; font-size:8pt; font-family:courier; font-weight: normal;
            font-style: normal;"><b>Carrera: </b>'.(string)$list[0]->nombre_carrera.'</span></th>
            <th width="12%"><span style="color:white; font-size:8pt; font-family:courier; font-weight: normal;
            font-style: normal;"><b>n</b></span></th>
        </tr>
        <tr>
            <th width="43%"><span style="color:white; font-size:8pt; font-family:courier; font-weight: normal;
            font-style: normal;"><b>n</b></span></th>
            <th width="45%" align="left"><span style="color:black; font-size:8pt; font-family:courier; font-weight: normal;
            font-style: normal;"><b>CI: </b>'.(string)$list[0]->ci.'</span></th>
            <th width="12%"><span style="color:white; font-size:8pt; font-family:courier; font-weight: normal;
            font-style: normal;"><b>n</b></span></th>
        </tr>
        <tr>
            <th width="43%"><span style="color:white; font-size:8pt; font-family:courier; font-weight: normal;
            font-style: normal;"><b>n</b></span></th>
            <th width="45%" align="left"><span style="color:black; font-size:8pt; font-family:courier; font-weight: normal;
            font-style: normal;"><b>Fecha  : </b>'.$dt->format('d-m-Y').'</span></th>
            <th width="12%"><span style="color:white; font-size:8pt; font-family:courier; font-weight: normal;
            font-style: normal;"><b>n</b></span></th>
        </tr>
        </thead>
        </table>
        <br>
        <br>
        </htmlpagefooter>
        <sethtmlpagefooter name="myfooter" value="on" />
        ';
        $note .='
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        '; 
        $note .='
        <table width="100%"><tr>
        <td width="100%"><div><span style="color:black; font-size:11pt; "><b>I. DATOS PERSONALES DEL ESTUDIENTE</b></span></div></td>
        </tr></table>
        <table width="100%" style="font-family:    
        serif; font-size: 10pt; color: #000;">
        <tr><td width="50%" ><div><span style="color:black; font-size:10pt;"><b>Apellidos y Nombres</b></span></div></td>
        <td width="30%"><div><span style="color:black; font-size:10pt;"><b>Cedula de Identidad</b></span></div></td>
        <td width="20%"><div><span style="color:black; font-size:10pt;"><b>Expedido </b></span></div></td></tr>
        <tr><td width="50%" ><div><span style="color:black; font-size:10pt;">'.(string)$list[0]->apellido_paterno.' '.(string)$list[0]->apellido_materno.' '.(string)$list[0]->nombres.'</span></div></td>
        <td width="30%"><div><span style="color:black; font-size:10pt;">'.$list[0]->ci.'</span></div></td>
        <td width="20%"><div><span style="color:black; font-size:10pt;">'.$list[0]->expedido.'</span></div></td></tr>
        </table>
        <table width="100%" style="font-family:    
        serif; font-size: 10pt; color: #000;">
        <tr><td width="20%" ><div><span style="color:black; font-size:10pt;"><b>Estado civil</b></span></div></td>
        <td width="20%"><div><span style="color:black; font-size:10pt;"><b>Sexo</b></span></div></td>
        <td width="20%"><div><span style="color:black; font-size:10pt;"><b>Fecha nacimiento </b></span></div></td>
        <td width="20%"><div><span style="color:black; font-size:10pt;"><b>Celular </b></span></div></td>
        <td width="20%"><div><span style="color:black; font-size:10pt;"><b>Telefono </b></span></div></td></tr>
        <tr><td width="20%" ><div><span style="color:black; font-size:10pt;">'.$list[0]->estado_civil.'</span></div></td>
        <td width="20%"><div><span style="color:black; font-size:10pt;">'.$list[0]->sexo.'</span></div></td>
        <td width="20%"><div><span style="color:black; font-size:10pt;">'.$list[0]->fecha_nacimiento.'</span></div></td>
        <td width="20%"><div><span style="color:black; font-size:10pt;">'.$list[0]->celular.'</span></div></td>
        <td width="20%"><div><span style="color:black; font-size:10pt;">'.$list[0]->telefono_fijo.'</span></div></td></tr>
        </table>
        <table width="100%" style="font-family:    
        serif; font-size: 10pt; color: #000;">
        <tr><td width="100%"><div><span style="color:black; font-size:10pt; "><b>Direccion</b></span></div></td></tr>
        <tr><td width="100%"><div><span style="color:black; font-size:10pt; ">'.$list[0]->direccion.'</span></div></td></tr>
        </table>
        <table width="100%" style="font-family:    
        serif; font-size: 10pt; color: #000;">
        <tr><td width="50%"><div><span style="color:black; font-size:10pt; "><b>Correo</b></span></div></td></tr>
        <tr><td width="50%"><div><span style="color:black; font-size:10pt; ">'.$list[0]->email.'</span></div></td></tr>
        </table>
        ';  
        $note .='
        <br>
        <table width="100%"><tr>
        <td width="100%"><div><span style="color:black; font-size:11pt; "><b>II. DATOS SOCIOECONOMICOS</b></span></div></td>
        </tr></table>
        <table width="100%">
        <tr><td width="50%"><div><span style="color:black; font-size:10pt; "><b>Direccion de trabajo</b></span></div></td>
        <td width="35%"><div><span style="color:black; font-size:10pt; "><b>Lugar de trabajo</b></span></div></td>
        <td width="15%"><div><span style="color:black; font-size:10pt; "><b>Telf. de trabajo</b></span></div></td></tr>
        <tr><td width="50%"><div><span style="color:black; font-size:10pt; ">'.$list[0]->direccion_trabajo.'</span></div></td>
        <td width="35%"><div><span style="color:black; font-size:10pt; ">'.$list[0]->lugar_trabajo.'</span></div></td>
        <td width="15%"><div><span style="color:black; font-size:10pt; ">'.$list[0]->telefono_trabajo.'</span></div></td></tr>
        </table>
        ';  
        $note .='<table width="100%"><tr>
        <td width="100%"><div><span style="color:black; font-size:11pt; "><b>III. MATERIAS PROGRAMADAS</b></span></div></td>
        </tr></table>';
        $note .='<table width="70%">';    
        $note .='<thead>';    
        $note .='<tr>';    
        $note .='<th width="10%" align="left"><span style="color:black; font-size:8pt; font-family:courier; font-weight: normal;
        font-style: normal;"><b>Sigla</b></span></th>';    
        $note .='<th width="10%" align="left"><span style="color:black; font-size:8pt; font-family:courier; font-weight: normal;
        font-style: normal;"><b>Nivel</b></span></th>';  
        $note .='<th width="50%" align="left"><span style="color:black; font-size:8pt; font-family:courier; font-weight: normal;
        font-style: normal;"><b>Asignaturas</b></span></th>'; 
        $note .='</tr>';    
        $note .='</thead>';    
        $note .='<tbody>'; 


            
        foreach ($list as $person) {
            $note .='<tr >'; 
            $note .='<td width="10%"><span style="color:black; font-size:8pt; font-family:courier; font-weight: normal;
        font-style: normal;">'.$person->sigla.'</span></td>';
            $note .='<td width="10%" align="left"><span style="color:black; font-size:8pt; font-family:courier; font-weight: normal;
        font-style: normal;">'.$person->nombre_paralelo.'</span></td>';
            $note .='<td width="50%"><span style="color:black; font-size:8pt; font-family:courier; font-weight: normal;
        font-style: normal;">'.$person->nombre_materia.'</span><br></td>';
        }
        
    $note .='</tr>';
    $note .='</tbody></table>';

    $note .='</body>
            </html>';
    $pdf=new mPDF('c','letter','','',17,10,10,10,16,13);
    $pdf->SetDisplayMode('fullpage');
    $pdf->list_indent_first_level = 0; 
    $header = '
    <table width="100%"><tr>
    <td align="left" class="barcodecell"><barcode code="'.$list[0]->ci.' '.(string)$list[0]->apellido_paterno.' '.(string)$list[0]->apellido_materno.' '.(string)$list[0]->nombres.' '.(string)$list[0]->nombre_carrera.' '.$dt->format('d-m-Y').'" type="QR" class="barcode" size="0.9"
    error="M"/></td>
    </tr></table>
    <table width="100%"><tr>
    <td width="50%" align="left"><div><span style="color:black; font-size:8pt; font-family:courier; font-weight: normal;
    font-style: normal;"><b>Fecha impresion:</b></span></div></td>
    </tr>
    <tr>
    <td width="50%" align="left"><div><span style="color:black; font-size:8pt; font-family:courier; font-weight: normal;
    font-style: normal;">'.$dt->format('d-m-Y').'</span></div></td>
    </tr></table>
    ';
    $pdf->SetHTMLHeader($header); 
    $pdf->WriteHTML($note);
    $pdf->Output(''.(string)$list[0]->apellido_paterno.' '.(string)$list[0]->apellido_materno.' '.(string)$list[0]->nombres.'.pdf','I');
    exit;

    }else {
        redirect(base_url() . '', 'refresh');
    }
  //---------------matricula por alumno---////////// 
    }

}
