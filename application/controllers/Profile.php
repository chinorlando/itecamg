<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Profile_model', 'Profile_model');
        $this->load->model('Login_model', 'Login_model');
        $this->load->helper(array('profile_rules'));
    }

    public function salida($data)
    {
        $this->load->view('template/header');
        $this->load->view($data['vista'],$data);
        $this->load->view('template/footer');
    }

    //////////////////// añadir docentes al sistema ///////////////////////////
    public function index()
    {
        if ($_SESSION['is_type_user'] != 'is_admin')
            redirect(base_url() . '', 'refresh');
        if ($_SESSION['is_type_user'] == 'is_admin'){
            $data['vista'] = 'v_profile';
            $this->salida($data);
        }
    }

    public function add_teacher()
    {

        $this->validate();
        $tipo_personal = $this->db->get_where('rol', array('id_rol' => $this->input->post('rol')))->row()->nombre;
        $type_user_personal = 'is_'.strtolower(str_replace(' ', '_', $tipo_personal));

        $nombre_cargo = $this->db->get_where('cargo', array('id_cargo' => $this->input->post('cargo')))->row()->nombre_cargo;
        $type_user = 'is_'.strtolower(str_replace(' ', '_', $nombre_cargo));

        $dataCuenta = array(
            'username' => strtolower(substr($this->input->post('nombres'), 0, 1)).strtolower($this->input->post('apellido_paterno')).strtolower($this->input->post('apellido_materno')),
            'email' => $this->input->post('email'),
            'password' => $this->Login_model->hash_password($this->input->post('ci')),
            'avatar' => 'default.jpg',
            'created_at' => date("Y/m/d H:m:s"),
            'updated_at' => "",
            // 'is_type_user' => $type_user,
            'is_confirmed' => 'S',
            'is_deleted' => 'N',
        );

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

        $dataPersonal = array(
            'nombre' => '',
            'id_rol' => $this->input->post('rol'),
            'id_cargo' => $this->input->post('cargo'),
        );
        
        if ($this->Profile_model->save_person($type_user, $dataCuenta, $dataPersona, $dataPersonal, $type_user_personal)) {
            echo json_encode(array("status" => TRUE));
        } else{
            echo json_encode(array("status" => FALSE));
        }

    }

    public function list_teachers()
    {
        $list = $this->Profile_model->get_datatables_docente();
        // print_r($list);
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
            $row[] = $person->email_cuenta;

            // $row[] = $person->id_cuenta;
            // $row[] = $person->username;
            // $row[] = $person->password;
            // $row[] = $person->is_type_user;
            // $row[] = $person->email_cuenta;

            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar" onclick="edit_person('."'".$person->id_persona."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
                  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_person('."'".$person->id_persona."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
            $data[] = $row;
        }

        $output = array(
                // "draw" => $_POST['draw'],
                "recordsTotal" => $this->Profile_model->count_all_docente(),
                "recordsFiltered" => $this->Profile_model->count_filtered_docente(),
                "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function get_cargo()
    {
        $cargos = $this->Profile_model->get_all_cargos();
        echo json_encode($cargos);
    }

    public function get_rol()
    {
        $rol = $this->Profile_model->get_all_rol();
        echo json_encode($rol);
    }

    public function ajax_edit_docente($id)
    {
        $data = $this->Profile_model->get_docente_by_id($id);
        // print_r($data);
        // exit();
        $data->dob = ($data->fecha_nacimiento == "0000-00-00") ? '' : $data->fecha_nacimiento;
        echo json_encode($data);
    }

    public function update_teacher()
    {
        $id_persona = $this->input->post('id_persona');
        $id_cuenta = $this->input->post('id_cuenta');
        $dataCuenta = array();


        $data = array(
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

        $dataPersonal = array(
            'id_rol' => $this->input->post('rol'),
            'id_cargo' => $this->input->post('cargo'),
        );
        $guardar_usuario = $this->input->post('check-all');
        if ($guardar_usuario) {
            $this->validate($guardar_usuario);

            $dataCuenta = array(
                'username' => $this->input->post('username'),
                'email' => $this->input->post('email'),
                'password' => $this->Login_model->hash_password($this->input->post('password')),
            );
        }

        if ($this->Profile_model->update_person($guardar_usuario, $id_cuenta, $id_persona, $dataCuenta, $data, $dataPersonal)) {
            echo json_encode(array("status" => TRUE));
        } else {
            echo json_encode(array("status" => FALSE));
        }
    }

    public function ajax_delete_docente($id)
    {
        // $this->person->delete_docente_by_id($id);
        echo json_encode(array("status" => TRUE));
    }

    public function validate($value='')
    {
        $this->form_validation->set_error_delimiters('','');
        $rules = getProfileRules();
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() === FALSE) {
            $data = array();
            $data['error_string'] = array();
            $data['inputerror'] = array();
            $data['status'] = TRUE;

            if ($value == 'on') {
                if(form_error('username') != '')
                {
                    $data['inputerror'][] = 'username';
                    $data['error_string'][] = form_error('username');
                    $data['status'] = FALSE;
                }

                if(form_error('email') != '')
                {
                    $data['inputerror'][] = 'email';
                    $data['error_string'][] = form_error('email');
                    $data['status'] = FALSE;
                }

                if(form_error('password') != '')
                {
                    $data['inputerror'][] = 'password';
                    $data['error_string'][] = form_error('password');
                    $data['status'] = FALSE;
                }

                if(form_error('repeat_password') != '')
                {
                    $data['inputerror'][] = 'repeat_password';
                    $data['error_string'][] = form_error('repeat_password');
                    $data['status'] = FALSE;
                }
            }

            if(form_error('nombres') != '')
            {
                $data['inputerror'][] = 'nombres';
                $data['error_string'][] = form_error('nombres');
                $data['status'] = FALSE;
            }

            if(form_error('apellido_paterno') != '')
            {
                $data['inputerror'][] = 'apellido_paterno';
                $data['error_string'][] = form_error('apellido_paterno');
                $data['status'] = FALSE;
            }

            if(form_error('apellido_materno') != '')
            {
                $data['inputerror'][] = 'apellido_materno';
                $data['error_string'][] = form_error('apellido_materno');
                $data['status'] = FALSE;
            }

            // if(form_error('email') != '')
            // {
            //  $data['inputerror'][] = 'email';
            //  $data['error_string'][] = form_error('email');
            //  $data['status'] = FALSE;
            // }

            if(form_error('ci') != '')
            {
                $data['inputerror'][] = 'ci';
                $data['error_string'][] = form_error('ci');
                $data['status'] = FALSE;
            }

            if(form_error('expedido') != '')
            {
                $data['inputerror'][] = 'expedido';
                $data['error_string'][] = form_error('expedido');
                $data['status'] = FALSE;
            }

            if(form_error('sexo') != '')
            {
                $data['inputerror'][] = 'sexo';
                $data['error_string'][] = form_error('sexo');
                $data['status'] = FALSE;
            }

            if(form_error('estado_civil') != '')
            {
                $data['inputerror'][] = 'estado_civil';
                $data['error_string'][] = form_error('estado_civil');
                $data['status'] = FALSE;
            }

            if(form_error('fecha_nacimiento') != '')
            {
                $data['inputerror'][] = 'fecha_nacimiento';
                $data['error_string'][] = form_error('fecha_nacimiento');
                $data['status'] = FALSE;
            }

            // if(form_error('direccion') != '')
            // {
            //  $data['inputerror'][] = 'direccion';
            //  $data['error_string'][] = form_error('direccion');
            //  $data['status'] = FALSE;
            // }

            if(form_error('rol') != '')
            {
                $data['inputerror'][] = 'rol';
                $data['error_string'][] = form_error('rol');
                $data['status'] = FALSE;
            }

            if(form_error('cargo') != '')
            {
                $data['inputerror'][] = 'cargo';
                $data['error_string'][] = form_error('cargo');
                $data['status'] = FALSE;
            }

            if($data['status'] === FALSE)
            {
                echo json_encode($data);
                exit();
            }
        }
    }

    //////////////////// añadir docentes al sistema end ///////////////////////////


    //////////////////// actualizar contraseña begin ///////////////////////////
    public function person()
    {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {

        // if ($_SESSION['is_type_user'] != 'is_admin')
        //     redirect(base_url() . '', 'refresh');
        // if ($_SESSION['is_type_user'] == 'is_admin'){
            $data['person'] = $this->Profile_model->get_person($_SESSION['user_id']);

            // print_r($this->Profile_model->get_person($_SESSION['user_id']));
            // exit();
            $data['vista'] = 'v_profile_update';
            $this->salida($data);
        // }
        } else {
            redirect('/');
        }
    }

    public function ajax_update_pas()
    {
        $this->validate_pass();

        $id_cuenta = $_SESSION['user_id'];
        $password = $this->input->post('old_password');

        $correo = $this->db->get_where('cuenta', array('id_cuenta' => $id_cuenta))->row()->email;

        if ($this->Login_model->resolve_user_login($correo, $password)) {
            $dataCuenta = array(
                // 'username' => $this->input->post('username'),
                // 'email' => $this->input->post('email'),
                'password' => $this->Login_model->hash_password($this->input->post('password')),
            );
            if ($this->Profile_model->update_password($id_cuenta, $dataCuenta)) {
                echo json_encode(array("status" => TRUE));
            } else {
                echo json_encode(array("status" => FALSE));
            }
        } else{
            echo json_encode(array("error" => 'error'));
        }

    }

    public function validate_pass()
    {
        $this->form_validation->set_error_delimiters('','');
        $rules = getProfileRules();
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() === FALSE) {
            $data = array();
            $data['error_string'] = array();
            $data['inputerror'] = array();
            $data['status'] = TRUE;
            if(form_error('old_password') != '')
            {
                $data['inputerror'][] = 'old_password';
                $data['error_string'][] = form_error('old_password');
                $data['status'] = FALSE;
            }

            if(form_error('password') != '')
            {
                $data['inputerror'][] = 'password';
                $data['error_string'][] = form_error('password');
                $data['status'] = FALSE;
            }

            if(form_error('repeat_password') != '')
            {
                $data['inputerror'][] = 'repeat_password';
                $data['error_string'][] = form_error('repeat_password');
                $data['status'] = FALSE;
            }
            if($data['status'] === FALSE)
            {
                echo json_encode($data);
                exit();
            }
        }
    }
    //////////////////// actualizar contraseña end /////////////////////////////

}