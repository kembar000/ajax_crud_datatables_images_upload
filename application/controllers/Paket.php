<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Paket extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Paket_model','paket');
		$this->cekLogin();
	}

	public function index()
	{
		$data ['data']= $this->paket->get_chart_date();
		$this->load->helper('url');
		$this->load->view('templateadmin/header');
		$this->load->view('paket_view',$data);
		$this->load->view('templateadmin/footer');
		return json_encode($data);
	}
	public function init_chart()
	{		
		$data ['data']= $this->paket->get_chart_date();
		header('Access-Control-Allow-Origin: *');
		header("Content-Type: application/json");
    	echo json_encode($data, JSON_NUMERIC_CHECK);
	}
	public function get_chart_spesifik($tanggal_input)
	{		
		$data ['data']= $this->paket->get_chart_date_spesifik($tanggal_input);
		header('Access-Control-Allow-Origin: *');
		header("Content-Type: application/json");
    	echo json_encode($data);
	}	
	public function ajax_list()
	{
		$this->load->helper('url');

		$list = $this->paket->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $paket) {
			$no++;
			$row = array();			
			$row[] = $paket->pengirim;
			$row[] = $paket->telp_pengirim;
			$row[] = $paket->penerima_tertera;
			$row[] = $paket->telp_penerima;
			$row[] = $paket->jenis_barang;
			$row[] = $paket->status_pengiriman;
			$row[] = $paket->tgl_input;
			$row[] = $paket->tgl_approve;
			if($paket->photo)
				$row[] = '<a href="'.base_url('uploadpaket/'.$paket->photo).'" target="_blank"><img src="'.base_url('uploadpaket/'.$paket->photo).'" class="img-responsive" width="100" height="100"/></a>';
			else
				$row[] = '<a href="'.base_url('uploadpaket/'.$paket->photo).'" target="_blank"><img src="'.base_url('uploadpaket/'.$paket->photo).'" class="img-responsive" /></a>';

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_paket('."'".$paket->id_paket."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_user('."'".$paket->id_paket."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>
				  <a href="'.site_url('paket/get_qr/'.$paket->awb).'">Get QR</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Get QR" onclick="get_qr('."'".$paket->awb."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->paket->count_all(),
						"recordsFiltered" => $this->paket->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id_paket)
	{
		$data = $this->paket->get_by_id($id_paket);		
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		
		$data = array(
				'awb' => $this->input->post('awb'),
				'id_user' => $this->session->userdata('id_user'),
				'pengirim' => $this->input->post('pengirim'),
				'telp_pengirim' => $this->input->post('telp_pengirim'),
				'penerima_tertera' => $this->input->post('penerima_tertera'),
				'alamat_penerima' => $this->input->post('alamat_penerima'),
				'telp_penerima' => $this->input->post('telp_penerima'),
				'jenis_barang' => $this->input->post('jenis_barang'),
				'qty' => $this->input->post('qty'),
				'width' => $this->input->post('width'),
				'length' => $this->input->post('length'),
				'height' => $this->input->post('height'),
				'kendaraan' => $this->input->post('kendaraan'),
				'deskripsi_barang' => $this->input->post('deskripsi_barang'),
				'status_pengiriman' => $this->input->post('status_pengiriman'),
				'tgl_input' => date('Y-m-d'),
				'tgl_approve' => $this->input->post('tgl_approve'),
				
			);

		if(!empty($_FILES['photo']['name']))
		{
			$upload = $this->_do_upload();
			$data['photo'] = $upload;
		}

		$insert = $this->paket->save($data);

		echo json_encode($data);
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'awb' => $this->input->post('awb'),
				'id_user' => $this->session->userdata('id_user'),
				'pengirim' => $this->input->post('pengirim'),
				'telp_pengirim' => $this->input->post('telp_pengirim'),
				'penerima_tertera' => $this->input->post('penerima_tertera'),
				'alamat_penerima' => $this->input->post('alamat_penerima'),
				'telp_penerima' => $this->input->post('telp_penerima'),
				'jenis_barang' => $this->input->post('jenis_barang'),
				'qty' => $this->input->post('qty'),
				'width' => $this->input->post('width'),
				'length' => $this->input->post('length'),
				'height' => $this->input->post('height'),
				'kendaraan' => $this->input->post('kendaraan'),
				'deskripsi_barang' => $this->input->post('deskripsi_barang'),
				'status_pengiriman' => $this->input->post('status_pengiriman'),				
				'tgl_approve' => $this->input->post('tgl_approve'),
			);

		if($this->input->post('remove_photo')) // if remove photo checked
		{
			if(file_exists('uploadpaket/'.$this->input->post('remove_photo')) && $this->input->post('remove_photo'))
				unlink('uploadpaket/'.$this->input->post('remove_photo'));
			$data['photo'] = '';
		}

		if(!empty($_FILES['photo']['name']))
		{
			$upload = $this->_do_upload();
			
			//delete file
			$paket = $this->paket->get_by_id($this->input->post('id_paket'));
			if(file_exists('uploadpaket/'.$paket->photo) && $paket->photo)
				unlink('uploadpaket/'.$paket->photo);

			$data['photo'] = $upload;
		}

		$this->paket->update(array('id_paket' => $this->input->post('id_paket')), $data);
		echo json_encode($data);
	}

	public function ajax_delete($id_paket)
	{
		//delete file
		$paket = $this->paket->get_by_id($id_paket);
		if(file_exists('uploadpaket/'.$paket->photo) && $paket->photo)
			unlink('uploadpaket/'.$paket->photo);
		
		$this->paket->delete_by_id($id_paket);
		echo json_encode(array("status" => TRUE));
	}

	private function _do_upload()
	{
		$config['upload_path']          = 'uploadpaket/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 3000; //set max size allowed in Kilobyte
        $config['max_width']            = 3000; // set max width image allowed
        $config['max_height']           = 3000; // set max height allowed
        $config['file_name']            = round(microtime(true) * 1000); //just milisecond timestamp fot unique name

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if(!$this->upload->do_upload('photo')) //upload and validate
        {
            $data['inputerror'][] = 'photo';
			$data['error_string'][] = 'Upload error: '.$this->upload->display_errors('',''); //show ajax error
			$data['status'] = FALSE;
			echo json_encode($data);
			exit();
		}
		return $this->upload->data('file_name');
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('awb') == '')
		{
			$data['inputerror'][] = 'awb';
			$data['error_string'][] = 'awb is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('pengirim') == '')
		{
			$data['inputerror'][] = 'pengirim';
			$data['error_string'][] = 'Pengirim is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('telp_pengirim') == '')
		{
			$data['inputerror'][] = 'telp_pengirim';
			$data['error_string'][] = 'Telpon Pengirim is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('penerima_tertera') == '')
		{
			$data['inputerror'][] = 'penerima_tertera';
			$data['error_string'][] = 'Penerima is required';
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
    public function get_qr($awb)
    {
		$this->load->library('ciqrcode');
		$list = $this->paket->get_qr($awb);
		foreach ($list as $paket) {
			$params['data'] = $paket->awb;
			$params['level'] = 'H';
			$params['size'] = 10;
			$params['savename'] = FCPATH.'tes.png';
		}
		

		$this->ciqrcode->generate($params);
		echo json_encode($params);
		echo '<img src="'.base_url().'tes.png" />';    	
    }
}
