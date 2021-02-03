<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Persona extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('persona_model');
		$this->load->model('Admin_model', 'Admin_model');
	}

	public function index()
	{
		$this->load->view('template/header');
        $this->load->view('admin/v_confirmar');
        $this->load->view('template/footer');
	}

	public function FunctionName($value='')
	{
		$list = $this->persona_model->esoasdfasdfa();
		print_r($list);
		exit();
		foreach ($list as $person) {
			$data = array('confirmado' => 'S', );
			$this->db->update('inscripcion', $data, array('id_persona' => $person->id_persona));
		}
	}

	public function ajax_list()
	{
		$list = $this->persona_model->get_datatables();
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
			$row[] = $person->email;
			$row[] = $person->fecha_nacimiento;

			//add html for action
			if ($this->confirmado($person->id_persona) == FALSE) {
				$row[] = '<ul class="icons-list">
							<li class="text-primary-600">
							<a href="javascript:void(0)" class="btn border-warning text-warning btn-flat btn-rounded btn-icon btn-xs" title="Confirmar" onclick="confirmar_inscripcion('."'".$person->id_persona."'".')"><i class="icon-user-plus"></i></a>
							</li>
						</ul>';
			} else {
				$row[] = '<ul class="icons-list">
							<li class="text-primary-600">
							<i class=" btn border-success text-success btn-flat btn-rounded btn-icon btn-xs icon-checkmark disabled"></i>
							</li>
						</ul>';
			}

		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->persona_model->count_all(),
						"recordsFiltered" => $this->persona_model->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->person->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$data = array(
				'firstName' => $this->input->post('firstName'),
				'lastName' => $this->input->post('lastName'),
				'gender' => $this->input->post('gender'),
				'address' => $this->input->post('address'),
				'dob' => $this->input->post('dob'),
			);
		$insert = $this->person->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$data = array(
				'firstName' => $this->input->post('firstName'),
				'lastName' => $this->input->post('lastName'),
				'gender' => $this->input->post('gender'),
				'address' => $this->input->post('address'),
				'dob' => $this->input->post('dob'),
			);
		$this->person->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->person->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function confirmado($id_persona){
		$this->load->model('Admin_model');
		// print_r($id_persona);
		if($this->Admin_model->esta_confirmado($id_persona)->confirmado == 'S'){
			return TRUE;
		} else {
			return FALSE;
		}
	}

}
