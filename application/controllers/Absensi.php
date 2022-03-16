<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absensi extends CI_Controller {

        public function __construct()
    {
        parent::__construct();        
        if($_SESSION['login']==false){
            redirect('login');
        }
        $this->load->model('Presensi_model','presensi');
    }

	public function index()
	{        
        $data = array('content'=>'absensi_koreksi');
        $this->load->view('template',$data);
    }
    
    public function absensi_koreksi_json()
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
        //echo $reqCabang;exit;
        $list = $this->presensi->select_presensi_koreksi($reqPeriode,$reqCabang,$reqKontrak)->result();
        $hitung = $this->presensi->getcount_presensi_koreksi($reqPeriode,$reqCabang,$reqKontrak);
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
            $row[] = $field->kelompok;            
            $row[] = $field->hari_1;$row[] = $field->hari_2;$row[] = $field->hari_3;$row[] = $field->hari_4;$row[] = $field->hari_5;
            $row[] = $field->hari_6;$row[] = $field->hari_7;$row[] = $field->hari_8;$row[] = $field->hari_9;$row[] = $field->hari_10;
            $row[] = $field->hari_11;$row[] = $field->hari_12;$row[] = $field->hari_13;$row[] = $field->hari_14;$row[] = $field->hari_15;
            $row[] = $field->hari_16;$row[] = $field->hari_17;$row[] = $field->hari_18;$row[] = $field->hari_19;$row[] = $field->hari_20;
            $row[] = $field->hari_21;$row[] = $field->hari_22;$row[] = $field->hari_23;$row[] = $field->hari_24;$row[] = $field->hari_25;
            $row[] = $field->hari_26;$row[] = $field->hari_27;$row[] = $field->hari_28;$row[] = $field->hari_29;$row[] = $field->hari_30;
            $row[] = $field->hari_31;
            $data[] = $row;
        }

        $output = array(
            "draw" => intval($_GET["draw"]),
            "recordsTotal" => $hitung,
            "recordsFiltered" => $hitung,
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    
}
