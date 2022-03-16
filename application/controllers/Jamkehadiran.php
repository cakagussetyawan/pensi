<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jamkehadiran extends CI_Controller {

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
        $data = array('content'=>'absensi_jamhadir');
        $this->load->view('template',$data);
    }
    
    public function jam_kehadiran_json()
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
        
        $list = $this->presensi->select_jam_kehadiran($reqPeriode,$reqCabang,$reqKontrak)->result();
        $hitung = $this->presensi->getcount_jam_kehadiran($reqPeriode,$reqCabang,$reqKontrak);
        
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
            $row[] = $field->in_1;$row[] = $field->out_1;$row[] = $field->in_2;$row[] = $field->out_2;$row[] = $field->in_3;$row[] = $field->out_3;$row[] = $field->in_4;$row[] = $field->out_4;$row[] = $field->in_5;$row[] = $field->out_5;
            $row[] = $field->in_6;$row[] = $field->out_6;$row[] = $field->in_7;$row[] = $field->out_7;$row[] = $field->in_8;$row[] = $field->out_8;$row[] = $field->in_9;$row[] = $field->out_9;$row[] = $field->in_10;$row[] = $field->out_10;
            $row[] = $field->in_11;$row[] = $field->out_11;$row[] = $field->in_12;$row[] = $field->out_12;$row[] = $field->in_13;$row[] = $field->out_13;$row[] = $field->in_14;$row[] = $field->out_14;$row[] = $field->in_15;$row[] = $field->out_15;
            $row[] = $field->in_16;$row[] = $field->out_16;$row[] = $field->in_17;$row[] = $field->out_17;$row[] = $field->in_18;$row[] = $field->out_18;$row[] = $field->in_19;$row[] = $field->out_19;$row[] = $field->in_20;$row[] = $field->out_20;
            $row[] = $field->in_21;$row[] = $field->out_21;$row[] = $field->in_22;$row[] = $field->out_22;$row[] = $field->in_23;$row[] = $field->out_23;$row[] = $field->in_24;$row[] = $field->out_24;$row[] = $field->in_25;$row[] = $field->out_25;
            $row[] = $field->in_26;$row[] = $field->out_26;$row[] = $field->in_27;$row[] = $field->out_27;$row[] = $field->in_28;$row[] = $field->out_28;$row[] = $field->in_29;$row[] = $field->out_29;$row[] = $field->in_30;$row[] = $field->out_30;
            $row[] = $field->in_31;$row[] = $field->out_31;
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
