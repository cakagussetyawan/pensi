<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
    
    function __construct()
    {
            parent::__construct();
            $this->load->model('login_model','login');
            // Your own constructor code                         
    }
             
    function index()
    {        
        $this->load->view('login');        
    }

    function proseslogin()
    {
        $username = $this->input->post('username');
        $password = md5($this->input->post('pass'));
        $cek = $this->login->cekuser($username,$password);
        
        
        if ($cek->num_rows() == 1)
        {
            $isi = $cek->row_array();
            $sesi = array(  
            'nama'                  => $isi['nama'],
            'perusahaan'            => $isi['perusahaan'],          
            'cabang'                => $isi['cabang'],  
            'per_id'                 => $isi['per_id'],
            'cab_id'                 => $isi['cab_id'],
            'login' => TRUE
            );
            $this->session->set_userdata($sesi);
            redirect ('welcome');
        }
        else {
            $this->session->set_flashdata('item', '<div class="alert alert-danger">Username atau password Anda salah</div>');            
			redirect('login', 'refresh');
        }
    }

    function logout(){
        $this->session->sess_destroy();
        $url=base_url('Login');
        redirect($url);
    }
}