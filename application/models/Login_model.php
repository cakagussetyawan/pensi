<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {

    function __construct(){
        parent::__construct();
        $this->db2 = $this->load->database('postgre',TRUE);

    }


    function cekuser($username,$password){
        $str = "
        select u.user_login_id,u.nama,u.user_login,u.user_pass,p.nama perusahaan, c.nama cabang, u.perusahaan_id per_id, u.perusahaan_cabang_id cab_id
        from user_login u
        left join pds_simpeg.perusahaan p on p.perusahaan_id = u.perusahaan_id
        left join pds_simpeg.perusahaan_cabang c on c.perusahaan_cabang_Id = u.perusahaan_cabang_id and c.perusahaan_id = u.perusahaan_id 
        where user_login = '".$username."' 
        and user_pass = '".$password."'
        and status = 1
        ";

        return $this->db2->query($str);

    }
}