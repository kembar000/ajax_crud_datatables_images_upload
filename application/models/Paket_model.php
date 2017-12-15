<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Paket_model extends CI_Model {

	var $table = 'paket';
	var $column_order = array('awb','id_user','pengirim','telp_pengirim','penerima_tertera','alamat_penerima','telp_penerima','jenis_barang','qty','width','length','height','kendaraan','deskripsi_barang','status_pengiriman','tgl_input','tgl_approve',null); //set column field database for datatable orderable
	var $column_search = array('pengirim','telp_pengirim','penerima_tertera','telp_penerima','jenis_barang','status_pengiriman','tgl_input','tgl_approve'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('id_paket' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		
		$this->db->from($this->table);		
		$i = 0;
	
		foreach ($this->column_search as $item) // loop column 
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{
				
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($this->column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables()
	{
		$this->_get_datatables_query();
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

	public function get_by_id($id_paket)
	{
		$this->db->from($this->table);
		$this->db->where('id_paket',$id_paket);
		$query = $this->db->get();

		return $query->row();
	}
	public function get_qr($awb)
	{
        $query = $this->db->query("SELECT awb FROM paket where awb='$awb'");
          
        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }
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
		$this->db->where('id_paket', $id);
		$this->db->delete($this->table);
	}
    public function get_chart_date(){
        $query = $this->db->query("SELECT count(id_paket) as Total , tgl_input FROM paket");
          
        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }
    }
    public function get_chart_date_spesifik($tanggal_input){
        $query = $this->db->query("SELECT count(id_paket) as Total , tgl_input FROM paket WHERE tgl_input='$tanggal_input'");
          
        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }
    }    


}
