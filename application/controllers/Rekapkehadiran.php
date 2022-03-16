<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekapkehadiran extends CI_Controller {

        public function __construct()
    {
        parent::__construct();        
        ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
        if($_SESSION['login']==false){
            redirect('login');
        }
        $this->load->model('Presensi_model','presensi');
    }

	public function index()
	{        
        $data = array('content'=>'absensi_rekap_kehadiran');
        $this->load->view('template',$data);
    }
    
    public function rekap_kehadiran_json()
    {
        
        $this->load->library('Tanggal');
        $tanggals = new Tanggal();
        $bulan = $tanggals->getNameMonth(date('n')-1);
        
        $reqPeriode = date('mY',strtotime(date('mY').'-1 month'));
        $reqCabang  = '01' ;
        $reqKontrak = '';
        if($this->input->get("reqPeriode")<>"")
        {
            $reqPeriode = $this->input->get("reqPeriode");            
        }
        if($this->input->get("reqCabang")<>"")
        {
            $reqCabang = $this->input->get("reqCabang");            
        }
        if($this->input->get("reqKontrak")<>"")
        {
            $reqKontrak = $this->input->get("reqKontrak");            
        }
        
        $list = $this->presensi->select_rekap_kehadiran($reqPeriode,$reqCabang,$reqKontrak)->result();
        $hitung = $this->presensi->getcount_rekap_kehadiran($reqPeriode,$reqCabang,$reqKontrak);
        
        $row = array();
        $data = array();
        $no = 0;
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->nrp;
            $row[] = $field->nama;            
            $row[] = $field->jabatan;                               
            $row[] = $field->jumlah_h;
            $row[] = $field->h;
            $row[] = $field->ht;
            $row[] = $field->kapc;
            $row[] = $field->jumlah_i;
            $row[] = $field->jumlah_s;
            $row[] = $field->jumlah_d;
            $row[] = $field->jumlah_c;
            $row[] = $field->jumlah_a;
            $data[] = $row;
        }

        $output = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $hitung,
            "recordsFiltered" => $hitung,
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    
}
