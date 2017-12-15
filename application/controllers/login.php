<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Login extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('m_user');
    }

    function index()
    {
        $this->load->view('indexlogin');
    }

    function proses() {

        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('pass', 'Password', 'required');
        
        if ($this->form_validation->run() == FALSE) {
             $this->session->set_flashdata('result_login', '<br>Nama atau Password belum diisi.');
             redirect();
        } else {
            $usr = $this->input->post('email');
            $psw = $this->input->post('pass');
            $u = ($usr);
            $p = md5($psw);
            $cek = $this->m_user->cek($u, $p);
            if ($cek->num_rows() > 0) {
                //login berhasil, buat session
                foreach ($cek->result() as $qad) {
                    $sess_data['id_user'] = $qad->id_user;
                    $sess_data['email'] = $qad->email;
                    $sess_data['nama'] = $qad->nama;
                    $sess_data['type'] = $qad->type;
                    $sess_data['foto_profil'] = $qad->foto_profil;
                    $sess_data['alamat'] = $qad->alamat;
                    $sess_data['telp'] = $qad->telp;
                    $this->session->set_userdata($sess_data);
                }
                redirect('user');
            } else {
                $this->session->set_flashdata('result_login', '<br>Username atau Password yang anda masukkan salah.');
                redirect('login');
            }
        }
    }

    function logout() {
        $this->session->sess_destroy();        
        redirect('login');
    }

}