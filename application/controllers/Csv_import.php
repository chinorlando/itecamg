<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Csv_import extends CI_Controller {

 public function __construct()
 {
  parent::__construct();
  $this->load->model('csv_import_model');
}

function index()
{
  $this->load->view('template/header');
  $this->load->view('csv_import');
  $this->load->view('template/footer');
}
public function load_data()
{
  $list = $this->csv_import_model->get_datatables();
  $data = array();
  $no = $_POST['start'];
  foreach ($list as $person) {
    $no++;
    $row = array();
    $row[] = $person->nombres;
    $row[] = $person->apellido_paterno;
    $row[] = $person->apellido_materno;
    $row[] = $person->ci;
    $row[] = $person->expedido;
    $row[] = $person->email;
    $row[] = $person->sexo;
    // $row[] = $person->estado_civil;
    $row[] = $person->fecha_nacimiento;
    $row[] = $person->direccion;
    $row[] = $person->celular;
    // $row[] = $person->telefono_fijo;
    // $row[] = $person->lugar_trabajo;
    // $row[] = $person->direccion_trabajo;
    // $row[] = $person->telefono_trabajo;

      //add html for action
    // $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_person('."'".$person->id_persona."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
    // <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_person('."'".$person->id_persona."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
    
    $data[] = $row;
  }

  $output = array(
    "draw" => $_POST['draw'],
    "recordsTotal" => $this->csv_import_model->count_all(),
    "recordsFiltered" => $this->csv_import_model->count_filtered(),
    "data" => $data,
  );
    //output to json format
  echo json_encode($output);
}


function import()
{
  // var_dump(extension_loaded('mbstring'));
  // exit();
  $file_data = $this->csvimport->get_array($_FILES["csv_file"]["tmp_name"]);
  foreach($file_data as $row) {
    $query = $this->csv_import_model->ci_existe($row['Cedula de identidad']);    
    if ($query != 1) {
      $dataPersona = array(
        'nombres' => $row['Nombre(s)'],
        'apellido_paterno' => $row['Apellido Paterno'],
        'apellido_materno' => $row['Apellido Materno'],
        'ci' => $row['Cedula de identidad'],
        'expedido' => $row['Ci emitido en'],
        'email' => $row['Nombre de usuario'],
        'sexo' => $row['Sexo'],
        'estado_civil' => $row["Estado civil"],
        'fecha_nacimiento'   => $row["Fecha de Nacimiento"],
        'direccion' => $row['Direccion'],
        'celular' => $row['Celular'],
        'telefono_fijo' => $row['Telefono'],
        'lugar_trabajo' => $row['Lugar de trabajo'],
        'direccion_trabajo' => $row['Direccion de trabajo'],
        'telefono_trabajo' => $row['Telefono trabajo'],
      );
      // $id_persona = $this->csv_import_model->insertar_persona($dataPersona);

      $carrera = $this->csv_import_model->get_carrera_by_id($row['Carrera a la que postula']);
      $dataTutor = array(
        'nombre_padre' => $row['Nombre completo del padre'],
        'ocupacion_padre'  => $row["Ocupación del padre"],
        'celular_padre'   => $row["Celular del padre"],
        'nombre_madre' => $row['Nombre completo de la madre'],
        'ocupacion_madre'  => $row["Ocupación de la madre"],
        'celular_madre'   => $row["Celular de la madre"],
      );
      // $id_tutor = $this->csv_import_model->insert_tutor($dataTutor);

      $dataInscripcion = array(
        'fecha_preinscripcion' => date("Y-m-d H:i:s", strtotime($row['Marca temporal'])),
        'colegio_proviene' => $row["Colegio donde proviene"],
        // 'fecha_inscripcion' => "12-12-2018 08:23:21",
        // 'id_persona' => $id_persona,
        'id_carrera' => $carrera->id_carrera,
        // 'id_tutor' => $id_tutor,
      );
      // $this->csv_import_model->insertar_inscripcion($dataInscripcion);
      $this->csv_import_model->save_new_person($dataPersona, $dataTutor, $dataInscripcion);

      // if ($this->csv_import_model->save_new_person($dataPersona, $dataTutor, $dataInscripcion)) {
      //       echo json_encode(array("status" => TRUE));
      // } else{
      //       echo json_encode(array("status" => FALSE));
      // }
      
    } else {
      // echo json_encode(array("status" => "other"));
    }
  }
  // print_r($dataPersona);
  // print_r($dataTutor);
  // print_r($dataInscripcion);
  // $this->csv_import_model->save_new_person($dataPersona, $dataTutor, $dataInscripcion);
  echo json_encode(array("status" => TRUE));
}

  public function import_excel() {
    $config = array(
      'upload_path'   => FCPATH.'upload/',
      'allowed_types' => 'xls|csv'
    );
    $this->load->library('upload', $config);
    if ($this->upload->do_upload('file')) {
      $data = $this->upload->data();
      @chmod($data['full_path'], 0777);

      $this->load->library('Spreadsheet_Excel_Reader');
      $this->spreadsheet_excel_reader->setOutputEncoding('CP1251');

      $this->spreadsheet_excel_reader->read($data['full_path']);
      $sheets = $this->spreadsheet_excel_reader->sheets[0];
      error_reporting(0);

      $data_excel = array();
      for ($i = 2; $i <= $sheets['numRows']; $i++) {
        if ($sheets['cells'][$i][1] == '') break;

        $dataPersona = array(
          'nombres' => $row['Nombre(s)'],
          'apellido_paterno' => $row['Apellido Paterno'],
          'apellido_materno' => $row['Apellido Materno'],
          'ci' => $row['Cedula de identidad'],
          'expedido' => $row['Ci emitido en'],
          'email' => $row['Username'],
          'sexo' => $row['Sexo'],
          'estado_civil' => $row["Estado civil"],
          'fecha_nacimiento'   => $row["Fecha de Nacimiento"],
          'direccion' => $row['Direccion'],
          'celular' => $row['Celular'],
          'telefono_fijo' => $row['Telefono'],
          'lugar_trabajo' => $row['Lugar de trabajo'],
          'direccion_trabajo' => $row['Direccion de trabajo'],
          'telefono_trabajo' => $row['Telefono trabajo'],
        );
        // print_r($dataPersona);
        $id_persona = $this->csv_import_model->insertar_persona($dataPersona);

        $carrera = $this->csv_import_model->get_carrera_by_id($row['Carrera a la que postula']);
        $dataTutor = array(
          'nombre_padre' => $row['Nombre completo del padre'],
          'ocupacion_padre'  => $row["Ocupación del padre"],
          'celular_padre'   => $row["Celular del padre"],
          'nombre_madre' => $row['Nombre completo de la madre'],
          'ocupacion_madre'  => $row["Ocupación de la madre"],
          'celular_madre'   => $row["Celular de la madre"],
        );
        $id_tutor = $this->csv_import_model->insert_tutor($dataTutor);

        $date = date_create($row['Timestamp']);
        $fecha_inscripcion = date_format($date, 'Y-m-d H:i:s');
        $dataAlumno = array(
          'fecha_preinscripcion' => $fecha_inscripcion,
          'colegio_proviene' => 'santo domingo',
          'id_persona' => $id_persona,
          'id_carrera' => $carrera->id_carrera,
          'id_tutor' => $id_tutor,
        );
        $this->csv_import_model->insertar_alumno($dataAlumno);
      }

      // $this->db->insert_batch('tb_import', $data_excel);
      @unlink($data['full_path']);
      redirect('excel-import');
    }
  }

  

}

