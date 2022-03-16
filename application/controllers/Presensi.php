<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Presensi extends CI_Controller {

    public function __construct(){
        parent::__construct();        
        $this->load->model('presensi_model','presensi');
        $this->load->library(array('PHPExcel','PHPExcel/IOFactory'));

        ini_set("memory_limit","500M");
        ini_set('max_execution_time', 520);
        
    }

    function index(){
        $this->load->view('welcome_message');
    }

    function presensikoreksi(){
        $periode = $this->input->get('reqPeriode');        
        $divisi = $this->input->get('reqDivisi');
        $jabatan = $this->input->get('reqJabatan');
        if(!isset($periode)){            
            $periode = date("mY");                
        }
        
        if(!isset($divisi)){
            $divisi = $_SESSION['cab_id'];
        }

        if(!isset($jabatan)){
            $jabatan = 0;
        }

        
        $datapresensi = $this->presensi->selectpresensikoreksi($periode,$divisi,$jabatan);
        $datadivisi = $this->presensi->selectdivisi();
        $datajabatan = $this->presensi->selectjabatan();

        $data = array(
            'isi' => $datapresensi->result(),
            'record' => $datapresensi->num_rows(),
            'divisi' => $datadivisi->result(),            
            'divisii' => $datadivisi->result(),
            'jabatan'=> $datajabatan->result(),
            'jabatann'=> $datajabatan->result(),
            'periode' => $this->_ambilbulan(substr($periode,0,2))." - ".substr($periode,2,4)
        );
        
        $this->load->view('datakehadiran_view',$data);
    }


    


    function jamkehadiran(){        
        
        $periode = $this->input->get('reqPeriode');        
        $divisi = $this->input->get('reqDivisi');
        $jabatan = $this->input->get('reqJabatan');
        if(!isset($periode)){            
            $periode = date("mY");                
        }
        
        if(!isset($divisi)){
            $divisi = $_SESSION['cab_id'];
        }

        if(!isset($jabatan)){
            $jabatan = 0;
        }

        $datapresensi = $this->presensi->selectjamkehadiran($periode,$divisi,$jabatan);
        $jumlah = $datapresensi->num_rows();
        $datadivisi = $this->presensi->selectdivisi();
        $datajabatan = $this->presensi->selectjabatan();
        $data = array(
            'isi' => $datapresensi->result(),
            'record' => $datapresensi->num_rows(),
            'divisi' => $datadivisi->result(),            
            'divisii' => $datadivisi->result(),
            'jabatan'=> $datajabatan->result(),
            'jabatann'=> $datajabatan->result(),
            'periode' => $this->_ambilbulan(substr($periode,0,2))." - ".substr($periode,2,4)
        );        
        
        $this->load->view('jamkehadiran_view',$data);        
        
    }

    


    function rekapkehadiran(){        
        $periode = $this->input->get('reqPeriode');        
        $divisi = $this->input->get('reqDivisi');
        $jabatan = $this->input->get('reqJabatan');      
        if(!isset($periode)){$periode = date("mY"); }
        if(!isset($divisi)){$divisi = $_SESSION['cab_id']; }
        if(!isset($jabatan)){$jabatan = 0;}        
        $datadivisi = $this->presensi->selectdivisi();
        $datajabatan = $this->presensi->selectjabatan();
        $datapresensi = $this->presensi->selectrekapkehadiran($periode,$divisi,$jabatan);
        $data = array(
            'isi' => $datapresensi->result(),
            'record' => $datapresensi->num_rows(),
            'divisi' => $datadivisi->result(),            
            'divisii' => $datadivisi->result(),
            'jabatan'=> $datajabatan->result(),
            'jabatann'=> $datajabatan->result(),
            'periode' => $this->_ambilbulan(substr($periode,0,2))." - ".substr($periode,2,4)
        );        
        
        $this->load->view('rekapkehadiran_view',$data);
    }

    function rekapkehadiran_filter($bulan){
        
        $datapresensi = $this->presensi->selectrekapkehadiran($bulan);
        $data = array(
            'isi' => $datapresensi->result(),
            'record' => $datapresensi->num_rows(),
            'periode' => $this->_ambilbulan(substr($bulan,0,2))." - ".substr($bulan,2,4)
        );
        
        $this->load->view('rekapkehadiran_view',$data);
    }

    function presensikoreksi_excel(){
        $periode    = $this->input->get('reqPeriode');        
        $divisi     = $this->input->get('reqDivisi');
        $jabatan    = $this->input->get('reqJabatan');
        if(!isset($periode)){            
            $periode = date("mY");                
        }
        $bultah = $this->_ambilbulan(substr($periode,0,2))." - ".substr($periode,2,4);                
        if(!isset($divisi)){
            $divisi = $_SESSION['cab_id'];
        }

        if (!isset($jabatan)){
            $jabatan=0;
        }
        $datapresensi = $this->presensi->selectpresensikoreksi($periode,$divisi,$jabatan);
        $bultah = $this->_ambilbulan(substr($periode,0,2))." - ".substr($periode,2,4);

        if($datapresensi->num_rows()>0){
            $objPHPExcel = new PHPExcel();
            // Set properties
            $objPHPExcel->getProperties()
                        ->setCreator("SAMSUL ARIFIN") //creator
                        ->setTitle("Programmer - Regional Planning and Monitoring, XL AXIATA");  //file title
 
            $objset = $objPHPExcel->setActiveSheetIndex(0); //inisiasi set object
            $objget = $objPHPExcel->getActiveSheet();  //inisiasi get object
            
            $objget->setTitle('Sample Sheet'); //sheet title
            //Warna header tabel
            $current_col = 0;
            $current_row = 1;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,"Rekap Data Kehadiran - Periode : ".$bultah);$current_row++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,"No");$current_col++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,"NRP");$current_col++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,"NAMA");$current_col++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,"JABATAN");$current_col++;
            for ($i=1;$i<32;$i++){
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$i);$current_col++;
            }
            $current_col = 0;
            $current_row++;
            $no = 1;

            foreach($datapresensi->result() as $row){
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$no); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->nrp); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->nama); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->jabatan); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->hari_1); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->hari_2); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->hari_3); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->hari_4); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->hari_5); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->hari_6); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->hari_7); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->hari_8); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->hari_9); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->hari_10); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->hari_11); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->hari_12); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->hari_13); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->hari_14); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->hari_15); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->hari_16); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->hari_17); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->hari_18); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->hari_19); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->hari_20); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->hari_21); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->hari_22); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->hari_23); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->hari_24); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->hari_25); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->hari_26); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->hari_27); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->hari_28); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->hari_29); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->hari_30); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->hari_31); $current_col++;                
                $current_col = 0;
                $no++;$current_row++;
            }
            $objPHPExcel->getActiveSheet()->setTitle('Data Export');
 
            $objPHPExcel->setActiveSheetIndex(0);  
            $filename = urlencode("presensikoreksi.xls");
               
              header('Content-Type: application/vnd.ms-excel'); //mime type
              header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
              header('Cache-Control: max-age=0'); //no cache
 
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');                
            $objWriter->save('php://output');
        }
    }


    function jamkehadiran_excel(){
        
        $periode = $this->input->get('reqPeriode');        
        $divisi = $this->input->get('reqDivisi');
        if(!isset($periode)){            
            $periode = date("mY");                
        }
        $bultah = $this->_ambilbulan(substr($periode,0,2))." - ".substr($periode,2,4);                
        if(!isset($divisi)){
            $divisi = $_SESSION['cab_id'];
        }        
        $jabatan = $this->input->get('reqJabatan');
        if(!isset($jabatan)){
            $jabatan = 0;
        }        
        $datapresensi = $this->presensi->selectjamkehadiran($periode,$divisi,$jabatan);
        $jumlah = $datapresensi->num_rows();                
        if ($jumlah>0){
            
            $objPHPExcel = new PHPExcel();
            // Set properties
            $objPHPExcel->getProperties()
                        ->setCreator("SAMSUL ARIFIN") //creator
                        ->setTitle("Programmer - Regional Planning and Monitoring, XL AXIATA");  //file title
 
            $objset = $objPHPExcel->setActiveSheetIndex(0); //inisiasi set object
            $objget = $objPHPExcel->getActiveSheet();  //inisiasi get object
            
            $objget->setTitle('Sample Sheet'); //sheet title
            //Warna header tabel
            $current_col = 0;
            $current_row = 1;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,"Rekap Jam Kehadiran - Periode : ".$bultah);$current_col=0;$current_row++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,"No");$current_col++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,"NRP");$current_col++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,"NAMA");$current_col++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,"JABATAN");$current_col++;
            $tgl = 1;
            for ($i=$current_col+1;$i<=$current_col+62;$i++){                                                                                
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $current_row,$tgl."_IN");
                if(($i%2)==0){
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $current_row,$tgl."_OUT");
                    $tgl++;
                }                                
            }
            
            $current_col = 0;
            $current_row++;
            $no = 1;
            
            foreach($datapresensi->result() as $row){
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$no); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->nrp); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->nama); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->jabatan); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->in_1); $current_col++;   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->out_1); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->in_2); $current_col++;   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->out_2); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->in_3); $current_col++;   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->out_3); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->in_4); $current_col++;   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->out_4); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->in_5); $current_col++;   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->out_5); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->in_6); $current_col++;   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->out_6); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->in_7); $current_col++;   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->out_7); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->in_8); $current_col++;   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->out_8); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->in_9); $current_col++;   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->out_9); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->in_10); $current_col++;   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->out_10); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->in_11); $current_col++;   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->out_11); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->in_12); $current_col++;   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->out_12); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->in_13); $current_col++;   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->out_13); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->in_14); $current_col++;   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->out_14); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->in_15); $current_col++;   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->out_15); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->in_16); $current_col++;   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->out_16); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->in_17); $current_col++;   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->out_17); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->in_18); $current_col++;   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->out_18); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->in_19); $current_col++;   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->out_19); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->in_20); $current_col++;   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->out_20); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->in_21); $current_col++;   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->out_21); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->in_22); $current_col++;   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->out_22); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->in_23); $current_col++;   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->out_23); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->in_24); $current_col++;   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->out_24); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->in_25); $current_col++;   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->out_25); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->in_26); $current_col++;   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->out_26); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->in_27); $current_col++;   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->out_27); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->in_28); $current_col++;   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->out_28); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->in_29); $current_col++;   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->out_29); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->in_30); $current_col++;   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->out_30); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->in_31); $current_col++;   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->out_31);    
                $current_col = 0;
                $no++;$current_row++;
            }


            
            $objPHPExcel->getActiveSheet()->setTitle('Data Export');
 
            $objPHPExcel->setActiveSheetIndex(0);  
            $filename = urlencode("jamkehadiran.xls");
               
              header('Content-Type: application/vnd.ms-excel'); //mime type
              header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
              header('Cache-Control: max-age=0'); //no cache
 
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');                
            $objWriter->save('php://output');
        }
    }


    function rekapkehadiran_excel(){
        $periode = $this->input->get('reqPeriode');        
        $divisi = $this->input->get('reqDivisi');
        if(!isset($periode)){            
            $periode = date("mY");                
        }
        $bultah = $this->_ambilbulan(substr($periode,0,2))." - ".substr($periode,2,4);                
        if(!isset($divisi)){
            $divisi = $_SESSION['cab_id'];
        }        
        $jabatan = $this->input->get('reqJabatan');
        if(!isset($jabatan)){
            $jabatan = 0;
        }        
        $datapresensi = $this->presensi->selectrekapkehadiran($periode,$divisi,$jabatan);

        if($datapresensi->num_rows()>0){
            $objPHPExcel = new PHPExcel();
            // Set properties
            $objPHPExcel->getProperties()
                        ->setCreator("SAMSUL ARIFIN") //creator
                        ->setTitle("Programmer - Regional Planning and Monitoring, XL AXIATA");  //file title
 
            $objset = $objPHPExcel->setActiveSheetIndex(0); //inisiasi set object
            $objget = $objPHPExcel->getActiveSheet();  //inisiasi get object
            
            $objget->setTitle('Sample Sheet'); //sheet title
            //Warna header tabel
            $current_col = 0;
            $current_row = 1;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,"Rekap Data Kehadiran - Periode : ".$bultah);$current_row++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,"No");$current_col++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,"NRP");$current_col++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,"NAMA");$current_col++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,"JABATAN");$current_col++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,"HADIR");$current_col++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,"TERLAMBAT");$current_col++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,"KURANG ABSEN");$current_col++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,"IJIN");$current_col++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,"SAKIT");$current_col++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,"DINAS");$current_col++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,"CUTI");$current_col++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,"ALPHA");$current_col++;
            $current_col = 0;
            $current_row++;
            $no = 1;

            foreach($datapresensi->result() as $row){
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$no); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->nrp); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->nama); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->jabatan); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->h); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->ht); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->kapc); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->jumlah_i); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->jumlah_s); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->jumlah_d); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->jumlah_c); $current_col++;   
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($current_col, $current_row,$row->jumlah_a); $current_col++;   
                $current_col = 0;
                $no++;$current_row++;
            }

            $objPHPExcel->getActiveSheet()->setTitle('Data Export');
 
            $objPHPExcel->setActiveSheetIndex(0);  
            $filename = urlencode("rekappresensi.xls");
               
              header('Content-Type: application/vnd.ms-excel'); //mime type
              header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
              header('Cache-Control: max-age=0'); //no cache
 
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');                
            $objWriter->save('php://output');
        }
    }


    function _ambilbulan($bulan){
        switch ($bulan) {
            case '01':
                return 'JANUARI';
                break;
            case '02':
                return 'FEBRUARI';
                break;
            case '03':
                return 'MARET';
                break;
            case '04':
                return 'APRIL';
                break;
            case '05':
                return 'MEI';
                break;
            case '06':
                return 'JUNI';
                break;
            case '07':
                return 'JULI';
                break;
            case '08':
                return 'AGUSTUS';
                break;
            case '09':
                return 'SEPTEMBER';
                break;
            case '10':
                return 'OKTOBER';
                break;
            case '11':
                return 'NOVEMBER';
                break;
            case '12':
                return 'DESEMBER';
                break;                      
        }
    }

    
}