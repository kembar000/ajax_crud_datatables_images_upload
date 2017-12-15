<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model','user');
		$this->cekLogin();
	}

	public function index()
	{
		$data ['data']= $this->user->get_chart_date();
		$this->load->helper('url');
		$this->load->view('templateadmin/header');
		$this->load->view('user_view',$data);
		$this->load->view('templateadmin/footer');
		return json_encode($data);
	}
	public function init_chart()
	{		
		$data ['data']= $this->user->get_chart_date();
		header('Access-Control-Allow-Origin: *');
		header("Content-Type: application/json");
    	echo json_encode($data, JSON_NUMERIC_CHECK);
	}
	public function get_chart_spesifik($tanggal_input)
	{		
		$data ['data']= $this->user->get_chart_date_spesifik($tanggal_input);
		header('Access-Control-Allow-Origin: *');
		header("Content-Type: application/json");
    	echo json_encode($data);
	}	

	public function ajax_list()
	{
		$this->load->helper('url');

		$list = $this->user->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $user) {
			$no++;
			$row = array();
			$row[] = $user->email;
			$row[] = $user->nama;
			$row[] = $user->type;
			$row[] = $user->status;			
			if($user->foto_profil)
				$row[] = '<a href="'.base_url('uploaduser/'.$user->foto_profil).'" target="_blank"><img src="'.base_url('uploaduser/'.$user->foto_profil).'" class="img-responsive" width="100" height="100"/></a>';
			else
				$row[] = '<a href="'.base_url('foto_default/'.$user->foto_profil).'" target="_blank"><img src="'.base_url('foto_default/'.$user->foto_profil).'" class="img-responsive" /></a>';

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_user('."'".$user->id_user."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_user('."'".$user->id_user."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->user->count_all(),
						"recordsFiltered" => $this->user->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id_user)
	{
		$data = $this->user->get_by_id($id_user);
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate_tambah();
		
		$data = array(
				'email' => $this->input->post('email'),
				'pass' => md5($this->input->post('pass')),
				'nama' => $this->input->post('nama'),
				'type' => $this->input->post('type'),
				'alamat' => $this->input->post('alamat'),
				'telp' => $this->input->post('telp'),
				'status' => $this->input->post('status'),
				'tanggal_input' => date('Y-m-d'),
			);

		if(!empty($_FILES['foto_profil']['name']))
		{
			$upload = $this->_do_upload();
			$data['foto_profil'] = $upload;
		}

		$insert = $this->user->save($data);

		echo json_encode($data);
	}

	public function ajax_update()
	{
		$this->_validate_update();
		$data = array(
				'email' => $this->input->post('email'),				
				'nama' => $this->input->post('nama'),
				'type' => $this->input->post('type'),
				'alamat' => $this->input->post('alamat'),
				'telp' => $this->input->post('telp'),
				'status' => $this->input->post('status'),
			);

		if($this->input->post('remove_photo')) // if remove photo checked
		{
			if(file_exists('uploaduser/'.$this->input->post('remove_photo')) && $this->input->post('remove_photo'))
				unlink('uploaduser/'.$this->input->post('remove_photo'));
			$data['foto_profil'] = '';
		}

		if(!empty($_FILES['foto_profil']['name']))
		{
			$upload = $this->_do_upload();
			
			//delete file
			$user = $this->user->get_by_id($this->input->post('id_user'));
			if(file_exists('uploaduser/'.$user->foto_profil) && $user->foto_profil)
				unlink('upload/'.$user->foto_profil);

			$data['foto_profil'] = $upload;
		}

		$this->user->update(array('id_user' => $this->input->post('id_user')), $data);
		echo json_encode($data);
	}

	public function ajax_delete($id_user)
	{
		//delete file
		$user = $this->user->get_by_id($id_user);
		if(file_exists('uploaduser/'.$user->foto_profil) && $user->foto_profil)
			unlink('uploaduser/'.$user->foto_profil);
		
		$this->user->delete_by_id($id_user);
		echo json_encode(array("status" => TRUE));
	}

	private function _do_upload()
	{
		$config['upload_path']          = 'uploaduser/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 3000; //set max size allowed in Kilobyte
        $config['max_width']            = 3000; // set max width image allowed
        $config['max_height']           = 3000; // set max height allowed
        $config['file_name']            = round(microtime(true) * 1000); //just milisecond timestamp fot unique name

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if(!$this->upload->do_upload('foto_profil')) //upload and validate
        {
            $data['inputerror'][] = 'foto_profil';
			$data['error_string'][] = 'Upload error: '.$this->upload->display_errors('',''); //show ajax error
			$data['status'] = FALSE;
			echo json_encode($data);
			exit();
		}
		return $this->upload->data('file_name');
	}

	private function _validate_tambah()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('email') == '')
		{
			$data['inputerror'][] = 'email';
			$data['error_string'][] = 'Email is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('pass') == '')
		{
			$data['inputerror'][] = 'pass';
			$data['error_string'][] = 'Password is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('type') == '')
		{
			$data['inputerror'][] = 'type';
			$data['error_string'][] = 'Type is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('status') == '')
		{
			$data['inputerror'][] = 'status';
			$data['error_string'][] = 'Status is required';
			$data['status'] = FALSE;
		}
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	private function _validate_update()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('email') == '')
		{
			$data['inputerror'][] = 'email';
			$data['error_string'][] = 'Email is required';
			$data['status'] = FALSE;
		}
		if($this->input->post('type') == '')
		{
			$data['inputerror'][] = 'type';
			$data['error_string'][] = 'Type is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('status') == '')
		{
			$data['inputerror'][] = 'status';
			$data['error_string'][] = 'Status is required';
			$data['status'] = FALSE;
		}
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}	
    public function cekLogin()
    {
        if (!$this->session->userdata('email')) {
          redirect('login/index');
        }
    }	

}
