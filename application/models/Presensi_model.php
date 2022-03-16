<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Presensi_model extends CI_Model {

    private $table = 'pds_simpeg.pegawai';
    private $column = array('nrp','nama','alamat');
    var $order = array('nama' => 'asc');
                
    
    function __construct(){
        parent::__construct();
        $this->db2 = $this->load->database('postgre',TRUE);    
        
    }


    function selectdivisi(){
        $perusahaan = $_SESSION['per_id'];
        $str = "select * from pds_simpeg.perusahaan_cabang where perusahaan_id = $perusahaan";
        return $this->db2->query($str);
    }

    function selectjabatan(){        
        $perusahaan = $_SESSION['per_id'];
        $str = "select perusahaan_jabatan_id kode,nama jabatan
                from pds_simpeg.perusahaan_jabatan j 
                where perusahaan_id = $perusahaan 
                    and exists (select 1 from pds_simpeg.pegawai_jabatan_terakhir t 
                                where perusahaan_jabatan_id = j.perusahaan_jabatan_id)
                order by jabatan";
        return $this->db->query($str);
    }
  
    
    function selectpresensikoreksi($periode,$cabang,$jabatan){
        $perusahaan = $_SESSION['per_id'];
        //$cabang = $_SESSION['cab_id'];
        $str = "
                SELECT 
                A.PEGAWAI_ID, A.NRP, A.NAMA ,PERIODE, B.KOREKSI_MANUAL_HARI, COALESCE(D.KELOMPOK, '5H') KELOMPOK, G.NAMA JABATAN, A.FINGER_ID,
                ";
        //for($i=1;$i<=cal_days_in_month(CAL_GREGORIAN, (int)substr($periode, 0, 2), substr($periode, 3, 4));$i++)
        for($i=1;$i<=31;$i++)
        {
            if($i < 10)
                $day = "0".$i;
            else
                $day = $i;
        $str .= "
                    CASE WHEN COALESCE(D.KELOMPOK, '5H') = '5H' AND TRIM(TO_CHAR(TO_DATE('".$day.$periode."','DDMMYYYY'), 'DAY')) = 'SUNDAY' THEN '' ELSE HARI_".$i." END HARI_".$i.", 		
                ";		
        }
        
        $str .= " 1         
                FROM pds_simpeg.PEGAWAI A 
                INNER JOIN pds_simpeg.PEGAWAI_KATEGORI_PEGAWAI  H ON A.PEGAWAI_ID = H.PEGAWAI_ID
                LEFT JOIN pds_absensi.ABSENSI_KOREKSI B
                ON A.PEGAWAI_ID = B.PEGAWAI_ID	AND PERIODE = '".$periode."'	
                LEFT JOIN pds_absensi.PEGAWAI_JAM_KERJA_JENIS C
                ON A.PEGAWAI_ID = C.PEGAWAI_ID		
                LEFT JOIN pds_absensi.JAM_KERJA_JENIS D
                ON C.JAM_KERJA_JENIS_ID = D.JAM_KERJA_JENIS_ID
                INNER JOIN pds_simpeg.PEGAWAI_JENIS_PEGAWAI_TERAKHIR E
                ON A.PEGAWAI_ID = E.PEGAWAI_ID
                LEFT JOIN pds_simpeg.PEGAWAI_CABANG_TERAKHIR F ON
                A.PEGAWAI_ID = F.PEGAWAI_ID
                LEFT JOIN pds_simpeg.PEGAWAI_JABATAN_TERAKHIR G ON 
                A.PEGAWAI_ID = G.PEGAWAI_ID
                WHERE 1 = 1  AND (
                        (A.STATUS_PEGAWAI_ID = 1 OR A.STATUS_PEGAWAI_ID = 5) 
                        OR 
                        (TANGGAL_PENSIUN > TO_DATE('".$periode."', 'MMYYYY') OR TANGGAL_MUTASI_KELUAR > TO_DATE('".$periode."', 'MMYYYY') OR TANGGAL_WAFAT > TO_DATE('".$periode."', 'MMYYYY')) 
                        ) 
                AND F.PERUSAHAAN_CABANG_ID LIKE '".$cabang."%' 
                AND F.PERUSAHAAN_ID = $perusahaan                  
                AND UPPER(A.NAMA) LIKE '%%' 
            ";  
        if($jabatan!=0){
            $str .= "And G.perusahaan_jabatan_id = $jabatan";
        }
        
        $str .= "ORDER BY A.NAMA ASC";
                     
        return $this->db2->query($str);      
    }


    function selectjamkehadiran($periode,$cabang,$jabatan){
        $perusahaan = $_SESSION['per_id'];

        if($jabatan ==0){        
        $str = "
                SELECT B.PEGAWAI_ID, B.DEPARTEMEN_ID, ABSENSI_REKAP_ID, B.NRP, B.NAMA, j.nama jabatan,
                PERIODE, IN_1, OUT_1, IN_2,
                OUT_2, IN_3, OUT_3, IN_4, OUT_4, IN_5,
                OUT_5, IN_6, OUT_6, IN_7, OUT_7, IN_8,
                OUT_8, IN_9, OUT_9, IN_10, OUT_10, IN_11,
                OUT_11, IN_12, OUT_12, IN_13, OUT_13, 
                IN_14, OUT_14, IN_15, OUT_15, IN_16, OUT_16,
                IN_17, OUT_17, IN_18, OUT_18, IN_19,
                OUT_19, IN_20, OUT_20, IN_21, OUT_21, 
                IN_22, OUT_22,IN_23, OUT_23, IN_24, OUT_24,
                IN_25, OUT_25, IN_26, OUT_26, IN_27,
                OUT_27, IN_28, OUT_28, IN_29, OUT_29, 
                IN_30, OUT_30, IN_31, OUT_31
                FROM PDS_SIMPEG.PEGAWAI B
                LEFT JOIN PDS_ABSENSI.ABSENSI_REKAP_ALIH_DAYA A ON B.PEGAWAI_ID = A.PEGAWAI_ID AND PERIODE = '$periode'
                LEFT JOIN pds_simpeg.PEGAWAI_CABANG_TERAKHIR I ON B.PEGAWAI_ID = I.PEGAWAI_ID
                left join pds_simpeg.pegawai_jabatan_terakhir j on b.pegawai_id = j.pegawai_id
                WHERE 1 = 1  AND COALESCE(B.STATUS_HAPUS, '0') =  ANY (VALUES ('0'))
                AND I.PERUSAHAAN_ID = $perusahaan 
                AND I.PERUSAHAAN_CABANG_ID LIKE '".$cabang."%'  
                AND B.STATUS_PEGAWAI_ID = 1  
                ORDER BY J.nama,B.NAMA Asc
        ";
        }else{
            $str = "
            SELECT B.PEGAWAI_ID, B.DEPARTEMEN_ID, ABSENSI_REKAP_ID, B.NRP, B.NAMA, j.nama jabatan,
            PERIODE, IN_1, OUT_1, IN_2,
            OUT_2, IN_3, OUT_3, IN_4, OUT_4, IN_5,
            OUT_5, IN_6, OUT_6, IN_7, OUT_7, IN_8,
            OUT_8, IN_9, OUT_9, IN_10, OUT_10, IN_11,
            OUT_11, IN_12, OUT_12, IN_13, OUT_13, 
            IN_14, OUT_14, IN_15, OUT_15, IN_16, OUT_16,
            IN_17, OUT_17, IN_18, OUT_18, IN_19,
            OUT_19, IN_20, OUT_20, IN_21, OUT_21, 
            IN_22, OUT_22,IN_23, OUT_23, IN_24, OUT_24,
            IN_25, OUT_25, IN_26, OUT_26, IN_27,
            OUT_27, IN_28, OUT_28, IN_29, OUT_29, 
            IN_30, OUT_30, IN_31, OUT_31
            FROM PDS_SIMPEG.PEGAWAI B
            LEFT JOIN PDS_ABSENSI.ABSENSI_REKAP_ALIH_DAYA A ON B.PEGAWAI_ID = A.PEGAWAI_ID AND PERIODE = '$periode'
            LEFT JOIN pds_simpeg.PEGAWAI_CABANG_TERAKHIR I ON B.PEGAWAI_ID = I.PEGAWAI_ID
            left join pds_simpeg.pegawai_jabatan_terakhir j on b.pegawai_id = j.pegawai_id
            WHERE 1 = 1  AND COALESCE(B.STATUS_HAPUS, '0') =  ANY (VALUES ('0'))
            AND I.PERUSAHAAN_ID = $perusahaan 
            AND I.PERUSAHAAN_CABANG_ID LIKE '".$cabang."%'  
            AND B.STATUS_PEGAWAI_ID = 1  
            AND j.perusahaan_jabatan_id = $jabatan
            ORDER BY J.nama,B.NAMA Asc
    ";  
        }
        return $this->db->query($str); 
    }


    function selectrekapkehadiran($periode,$cabang,$jabatan){
        $perusahaan = $_SESSION['per_id'];        
        $str = "
                    SELECT A.*,(HPC + HTPC + HTAD + HTAP) KAPC, B.JABATAN,B.PERUSAHAAN_JABATAN_ID, B.FINGER_ID, COALESCE(HARI_KERJA_SHIFT, KELOMPOK) JUMLAH_H_SHIFT FROM
                    (
                    SELECT   A.KELOMPOK_PEGAWAI, A.PEGAWAI_ID, A.STATUS_PEGAWAI_ID, TANGGAL_PENSIUN, TANGGAL_MUTASI_KELUAR, TANGGAL_WAFAT, E.KATEGORI KATEGORI_JABATAN, A.NRP, A.NAMA, A.DEPARTEMEN_ID, ((H + HT + HPC + HTPC + HTAD + HTAP) + (STK + SDK) + (ITK + IDK) + (CD + CM + CT + CAP + CS + CB) + (DL + DLK + P + A)) KELOMPOK,
                            (H + HT + HPC + HTPC + HTAD + HTAP) JUMLAH_H, H, HT, HPC, HTPC, HTAD, HTAP,
                            (STK + SDK) JUMLAH_S, STK, SDK, (ITK + IDK) JUMLAH_I, ITK, IDK,
                            (CD + CM + CT + CAP + CS + CB) JUMLAH_C, CT, CAP, CS, CB, CD, CM, DL, DLK, P, 
                            (DL + DLK + P) JUMLAH_D, A JUMLAH_A
                        FROM PDS_SIMPEG.PEGAWAI A
                            LEFT JOIN
                            (SELECT   PEGAWAI_ID,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'H'
                                                            THEN JUMLAH
                                                    END), 0) H,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'HT'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) HT,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'HPC'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) HPC,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'HTPC'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) HTPC,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'HTAD'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) HTAD,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'HTAP'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) HTAP,     
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'P'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) P,                        
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'STK'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) STK,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'SDK'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) SDK,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'ITK'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) ITK,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'IDK'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) IDK,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'CT'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) CT,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'CAP'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) CAP,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'CS'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) CS,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'CB'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) CB,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'CD'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) CD,  
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'CM'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) CM,                
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'DL'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) DL,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'DLK'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) DLK,         
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'A'
                                                            THEN JUMLAH
                                                    END), 0) A
                                FROM (SELECT   PEGAWAI_ID, KEHADIRAN, COUNT (KEHADIRAN) JUMLAH
                                            FROM PDS_ABSENSI.REKAP_ABSENSI_KOREKSI Y
                                        WHERE PERIODE = '$periode'
                                        GROUP BY PEGAWAI_ID, KEHADIRAN) X
                            GROUP BY PEGAWAI_ID) B ON A.PEGAWAI_ID = B.PEGAWAI_ID
                            INNER JOIN pds_simpeg.PEGAWAI_JENIS_PEGAWAI_TERAKHIR C
                            ON A.PEGAWAI_ID = C.PEGAWAI_ID
                            LEFT JOIN pds_simpeg.PEGAWAI_CABANG_TERAKHIR D 
                            ON A.PEGAWAI_ID = D.PEGAWAI_ID
                            LEFT JOIN pds_simpeg.PEGAWAI_JABATAN_TERAKHIR E 
                            ON A.PEGAWAI_ID = E.PEGAWAI_ID
                    WHERE 1 = 1 
                        AND EXISTS(SELECT 1 FROM PDS_ABSENSI.ABSENSI_REKAP X WHERE X.PEGAWAI_ID = A.PEGAWAI_ID AND PERIODE = '$periode')
                    UNION ALL
                    SELECT   A.KELOMPOK_PEGAWAI, A.PEGAWAI_ID, A.STATUS_PEGAWAI_ID, TANGGAL_PENSIUN, TANGGAL_MUTASI_KELUAR, TANGGAL_WAFAT, E.KATEGORI KATEGORI_JABATAN, A.NRP, A.NAMA, A.DEPARTEMEN_ID, F.JUMLAH KELOMPOK,
                            ((F.JUMLAH - (HT + HPC + HTPC + HTAD + HTAP) - (STK + SDK) - (ITK + IDK) - (CD + CM + CT + CAP + CS + CB) - (DL + DLK + P + A)) + HT + HPC + HTPC + HTAD + HTAP) JUMLAH_H, 
                            (F.JUMLAH - (HT + HPC + HTPC + HTAD + HTAP) - (STK + SDK) - (ITK + IDK) - (CD + CM + CT + CAP + CS + CB) - (DL + DLK + P + A)) H, HT, HPC, HTPC, HTAD, HTAP,
                            (STK + SDK) JUMLAH_S, STK, SDK, (ITK + IDK) JUMLAH_I, ITK, IDK,
                            (CD + CM + CT + CAP + CS + CB) JUMLAH_C, CT, CAP, CS, CB, CD, CM, DL, DLK, P, 
                            (DL + DLK + P) JUMLAH_D, A JUMLAH_A
                        FROM PDS_SIMPEG.PEGAWAI A
                            LEFT JOIN
                            (SELECT   PEGAWAI_ID,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'H'
                                                            THEN JUMLAH
                                                    END), 0) H,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'HT'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) HT,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'HPC'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) HPC,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'HTPC'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) HTPC,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'HTAD'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) HTAD,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'HTAP'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) HTAP,     
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'P'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) P,                        
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'STK'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) STK,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'SDK'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) SDK,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'ITK'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) ITK,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'IDK'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) IDK,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'CT'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) CT,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'CAP'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) CAP,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'CS'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) CS,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'CB'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) CB,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'CD'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) CD,  
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'CM'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) CM,                
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'DL'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) DL,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'DLK'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) DLK,         
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'A'
                                                            THEN JUMLAH
                                                    END), 0) A
                                FROM (SELECT   PEGAWAI_ID, KEHADIRAN, COUNT (KEHADIRAN) JUMLAH
                                            FROM PDS_ABSENSI.REKAP_ABSENSI_KOREKSI Y
                                        WHERE PERIODE = '$periode'
                                        GROUP BY PEGAWAI_ID, KEHADIRAN) X
                            GROUP BY PEGAWAI_ID) B ON A.PEGAWAI_ID = B.PEGAWAI_ID
                            INNER JOIN pds_simpeg.PEGAWAI_JENIS_PEGAWAI_TERAKHIR C
                            ON A.PEGAWAI_ID = C.PEGAWAI_ID
                            LEFT JOIN pds_simpeg.PEGAWAI_CABANG_TERAKHIR D 
                            ON A.PEGAWAI_ID = D.PEGAWAI_ID
                            LEFT JOIN pds_simpeg.PEGAWAI_JABATAN_TERAKHIR E 
                            ON A.PEGAWAI_ID = E.PEGAWAI_ID
                            LEFT JOIN PDS_ABSENSI.PEGAWAI_HARI_KERJA F 
                            ON A.PEGAWAI_ID = F.PEGAWAI_ID AND PERIODE = '".$periode."'
                    WHERE 1 = 1
                        AND NOT EXISTS(SELECT 1 FROM PDS_ABSENSI.ABSENSI_REKAP X WHERE X.PEGAWAI_ID = A.PEGAWAI_ID AND PERIODE = '$periode')
                    ) A 
                    LEFT JOIN(
                        SELECT PEGAWAI_ID, PERIODE, CASE WHEN MAX(TO_TIMESTAMP((CASE WHEN JAM_PULANG < JAM_MASUK OR JAM_PULANG = JAM_MASUK THEN '02012014' || JAM_PULANG ELSE '01012014' || JAM_PULANG END), 'DDMMYYYYHH24:MI') - 
                                            TO_TIMESTAMP('01012014' || JAM_MASUK, 'DDMMYYYYHH24:MI')) >= '12:00:00'::INTERVAL THEN 
                                            (CASE WHEN MAX(TOLERANSI_PULANG - TOLERANSI_MASUK) + INTERVAL '6 MINUTE' >= '12:00:00'::INTERVAL OR MAX(TOLERANSI_MASUK) IS NULL THEN
                                                25::INTEGER ELSE NULL END) 
                                            ELSE NULL END HARI_KERJA_SHIFT FROM PDS_ABSENSI.PEGAWAI_JADWAL 
                                            WHERE NOT JAM_MASUK = 'OFF'
                        GROUP BY PEGAWAI_ID, PERIODE) C ON A.PEGAWAI_ID = C.PEGAWAI_ID AND C.PERIODE = '".$periode."'
                    LEFT JOIN (
                    SELECT A.PEGAWAI_ID, A.FINGER_ID, B.NAMA JABATAN,B.PERUSAHAAN_JABATAN_ID FROM PDS_SIMPEG.PEGAWAI A INNER JOIN pds_simpeg.PEGAWAI_JABATAN_TERAKHIR B ON A.PEGAWAI_ID = B.PEGAWAI_ID
                    ) B ON A.PEGAWAI_ID = B.PEGAWAI_ID
                    WHERE 1 = 1 
                        AND (   (A.STATUS_PEGAWAI_ID = 1 OR A.STATUS_PEGAWAI_ID = 5)
                            OR (   TANGGAL_PENSIUN > TO_DATE ('".$periode."', 'MMYYYY')
                                OR TANGGAL_MUTASI_KELUAR > TO_DATE ('".$periode."', 'MMYYYY')
                                OR TANGGAL_WAFAT > TO_DATE ('".$periode."', 'MMYYYY')
                                )
                            )							
                AND EXISTS(SELECT 1 FROM pds_simpeg.PEGAWAI_CABANG_TERAKHIR_SIMPLE X WHERE A.PEGAWAI_ID = X.PEGAWAI_ID AND X.PERUSAHAAN_ID = $perusahaan AND X.PERUSAHAAN_CABANG_ID LIKE '".$cabang."%') 
                AND UPPER(A.NAMA) LIKE '%%'  
                
        ";
        if($jabatan!=0){
            $str .= "AND B.PERUSAHAAN_JABATAN_ID = $jabatan";
        }
        $str .= "ORDER BY A.KATEGORI_JABATAN, A.NAMA";

        return $this->db->query($str); 
    }



    // -------------------------- MULAI DARI SINI YA...-----------------------------------------------------//

    function getcount_presensi_koreksi($periode,$cabang,$kontrak)
    {
        //$periode = '042020';
        $perusahaan = $_SESSION['per_id'];
        
        
        $str = "
                SELECT 
                A.PEGAWAI_ID, A.NRP, A.NAMA ,PERIODE, B.KOREKSI_MANUAL_HARI, COALESCE(D.KELOMPOK, '5H') KELOMPOK, G.NAMA JABATAN, A.FINGER_ID,
                ";        
        for($i=1;$i<=31;$i++)
        {
            if($i < 10)
                $day = "0".$i;
            else
                $day = $i;
        $str .= "
                    CASE WHEN COALESCE(D.KELOMPOK, '5H') = '5H' AND TRIM(TO_CHAR(TO_DATE('".$day.$periode."','DDMMYYYY'), 'DAY')) = 'SUNDAY' THEN '' ELSE HARI_".$i." END HARI_".$i.", 		
                ";		
        }
        
        $str .= " 1         
            FROM pds_simpeg.PEGAWAI A 
            INNER JOIN pds_simpeg.PEGAWAI_KATEGORI_PEGAWAI  H ON A.PEGAWAI_ID = H.PEGAWAI_ID
            LEFT JOIN pds_absensi.ABSENSI_KOREKSI B
            ON A.PEGAWAI_ID = B.PEGAWAI_ID	AND PERIODE = '".$periode."'	
            LEFT JOIN pds_absensi.PEGAWAI_JAM_KERJA_JENIS C
            ON A.PEGAWAI_ID = C.PEGAWAI_ID		
            LEFT JOIN pds_absensi.JAM_KERJA_JENIS D
            ON C.JAM_KERJA_JENIS_ID = D.JAM_KERJA_JENIS_ID
            INNER JOIN pds_simpeg.PEGAWAI_JENIS_PEGAWAI_TERAKHIR E
            ON A.PEGAWAI_ID = E.PEGAWAI_ID
            LEFT JOIN pds_simpeg.PEGAWAI_CABANG_TERAKHIR F ON
            A.PEGAWAI_ID = F.PEGAWAI_ID
            LEFT JOIN pds_simpeg.PEGAWAI_JABATAN_TERAKHIR G ON 
            A.PEGAWAI_ID = G.PEGAWAI_ID
            WHERE 1 = 1  AND (
                    (A.STATUS_PEGAWAI_ID = 1 OR A.STATUS_PEGAWAI_ID = 5) 
                    OR 
                    (TANGGAL_PENSIUN > TO_DATE('".$periode."', 'MMYYYY') OR TANGGAL_MUTASI_KELUAR > TO_DATE('".$periode."', 'MMYYYY') OR TANGGAL_WAFAT > TO_DATE('".$periode."', 'MMYYYY')) 
                    ) 
            AND F.PERUSAHAAN_CABANG_ID LIKE '".$cabang."%' 
            AND F.PERUSAHAAN_ID = $perusahaan                  
             
        ";  

        if($kontrak!='')
        {
            $str .= "
                AND exists (select 1 from pds_project.project_kontrak_pegawai x where x.pegawai_id = a.pegawai_id and x.project_kontrak_id = ".$kontrak.")
            " ;
        }

        if(isset($_GET['search']['value']))
        {
            $str .= " AND UPPER(A.NAMA) LIKE"."'%".strtoupper($_GET['search']['value'])."%'";
        }
        
        $query = "select count (q.*) as rowcount from (".$str.") q";
        $hitung = $this->db->query($query)->row();
        return $hitung->rowcount;
    }

    function select_presensi_koreksi($periode,$cabang,$kontrak)
    {
        //$periode = '042020';
        $perusahaan = $_SESSION['per_id'];
        

        $str = "
                SELECT 
                A.PEGAWAI_ID, A.NRP, A.NAMA ,PERIODE, B.KOREKSI_MANUAL_HARI, COALESCE(D.KELOMPOK, '5H') KELOMPOK, G.NAMA JABATAN, A.FINGER_ID,
                ";
        //for($i=1;$i<=cal_days_in_month(CAL_GREGORIAN, (int)substr($periode, 0, 2), substr($periode, 3, 4));$i++)
        for($i=1;$i<=31;$i++)
        {
            if($i < 10)
                $day = "0".$i;
            else
                $day = $i;
        $str .= "
                    CASE WHEN COALESCE(D.KELOMPOK, '5H') = '5H' AND TRIM(TO_CHAR(TO_DATE('".$day.$periode."','DDMMYYYY'), 'DAY')) = 'SUNDAY' THEN '' ELSE HARI_".$i." END HARI_".$i.", 		
                ";		
        }
        
        $str .= " 1         
                FROM pds_simpeg.PEGAWAI A 
                INNER JOIN pds_simpeg.PEGAWAI_KATEGORI_PEGAWAI  H ON A.PEGAWAI_ID = H.PEGAWAI_ID
                LEFT JOIN pds_absensi.ABSENSI_KOREKSI B
                ON A.PEGAWAI_ID = B.PEGAWAI_ID	AND PERIODE = '".$periode."'	
                LEFT JOIN pds_absensi.PEGAWAI_JAM_KERJA_JENIS C
                ON A.PEGAWAI_ID = C.PEGAWAI_ID		
                LEFT JOIN pds_absensi.JAM_KERJA_JENIS D
                ON C.JAM_KERJA_JENIS_ID = D.JAM_KERJA_JENIS_ID
                INNER JOIN pds_simpeg.PEGAWAI_JENIS_PEGAWAI_TERAKHIR E
                ON A.PEGAWAI_ID = E.PEGAWAI_ID
                LEFT JOIN pds_simpeg.PEGAWAI_CABANG_TERAKHIR F ON
                A.PEGAWAI_ID = F.PEGAWAI_ID
                LEFT JOIN pds_simpeg.PEGAWAI_JABATAN_TERAKHIR G ON 
                A.PEGAWAI_ID = G.PEGAWAI_ID
                WHERE 1 = 1  AND (
                        (A.STATUS_PEGAWAI_ID = 1 OR A.STATUS_PEGAWAI_ID = 5) 
                        OR 
                        (TANGGAL_PENSIUN > TO_DATE('".$periode."', 'MMYYYY') OR TANGGAL_MUTASI_KELUAR > TO_DATE('".$periode."', 'MMYYYY') OR TANGGAL_WAFAT > TO_DATE('".$periode."', 'MMYYYY')) 
                        ) 
                AND F.PERUSAHAAN_CABANG_ID LIKE '".$cabang."%' 
                AND F.PERUSAHAAN_ID = $perusahaan                                  
            ";  
        
        if($kontrak!='')
        {
            $str .= "
                AND exists (select 1 from pds_project.project_kontrak_pegawai x where x.pegawai_id = a.pegawai_id and x.project_kontrak_id = ".$kontrak.")
            " ;
        }
        
        if(isset($_GET['search']['value']))
        {
            $str .= " AND UPPER(A.NAMA) LIKE"."'%".strtoupper($_GET['search']['value'])."%'";
        }
        
        $str .= " ORDER BY G.Nama,A.NAMA ASC ";
        //echo $str;exit;
        if($_GET["length"] != -1)  
        {  
            $str .= "limit ".$_GET['length']." offset ".$_GET['start'];
        }          

        return $this->db->query($str);
    }

    function getcount_jam_kehadiran($periode,$cabang,$kontrak)
    {
        $perusahaan = $_SESSION['per_id'];
        $str = "
        SELECT B.PEGAWAI_ID, B.DEPARTEMEN_ID, ABSENSI_REKAP_ID, B.NRP, B.NAMA, j.nama jabatan,
                PERIODE, IN_1, OUT_1, IN_2,
                OUT_2, IN_3, OUT_3, IN_4, OUT_4, IN_5,
                OUT_5, IN_6, OUT_6, IN_7, OUT_7, IN_8,
                OUT_8, IN_9, OUT_9, IN_10, OUT_10, IN_11,
                OUT_11, IN_12, OUT_12, IN_13, OUT_13, 
                IN_14, OUT_14, IN_15, OUT_15, IN_16, OUT_16,
                IN_17, OUT_17, IN_18, OUT_18, IN_19,
                OUT_19, IN_20, OUT_20, IN_21, OUT_21, 
                IN_22, OUT_22,IN_23, OUT_23, IN_24, OUT_24,
                IN_25, OUT_25, IN_26, OUT_26, IN_27,
                OUT_27, IN_28, OUT_28, IN_29, OUT_29, 
                IN_30, OUT_30, IN_31, OUT_31
                FROM PDS_SIMPEG.PEGAWAI B
                LEFT JOIN PDS_ABSENSI.ABSENSI_REKAP_ALIH_DAYA A ON B.PEGAWAI_ID = A.PEGAWAI_ID AND PERIODE = '$periode'
                LEFT JOIN pds_simpeg.PEGAWAI_CABANG_TERAKHIR I ON B.PEGAWAI_ID = I.PEGAWAI_ID
                left join pds_simpeg.pegawai_jabatan_terakhir j on b.pegawai_id = j.pegawai_id
                WHERE 1 = 1  AND COALESCE(B.STATUS_HAPUS, '0') =  ANY (VALUES ('0'))
                AND I.PERUSAHAAN_ID = $perusahaan 
                AND I.PERUSAHAAN_CABANG_ID LIKE '".$cabang."%'  
                AND (
                    (A.STATUS_PEGAWAI_ID = 1 OR A.STATUS_PEGAWAI_ID = 5) 
                    OR 
                    (TANGGAL_PENSIUN > TO_DATE('".$periode."', 'MMYYYY') OR TANGGAL_MUTASI_KELUAR > TO_DATE('".$periode."', 'MMYYYY') OR TANGGAL_WAFAT > TO_DATE('".$periode."', 'MMYYYY')) 
                    )
                
        ";
        if($kontrak!='')
        {
            $str .= "
                AND exists (select 1 from pds_project.project_kontrak_pegawai x where x.pegawai_id = b.pegawai_id and x.project_kontrak_id = ".$kontrak.")
            " ;
        }
        
        if(isset($_POST['search']['value']))
        {
            $str .= " AND UPPER(A.NAMA) LIKE"."'%".strtoupper($_POST['search']['value'])."%'";
        }

        $query = "select count (q.*) as rowcount from (".$str.") q";
        $hitung = $this->db->query($query)->row();
        return $hitung->rowcount;
    }


    function select_jam_kehadiran($periode,$cabang,$kontrak)
    {
        $perusahaan = $_SESSION['per_id'];
        $str = "
        SELECT B.PEGAWAI_ID, B.DEPARTEMEN_ID, ABSENSI_REKAP_ID, B.NRP, B.NAMA, j.nama jabatan,
                PERIODE, IN_1, OUT_1, IN_2,
                OUT_2, IN_3, OUT_3, IN_4, OUT_4, IN_5,
                OUT_5, IN_6, OUT_6, IN_7, OUT_7, IN_8,
                OUT_8, IN_9, OUT_9, IN_10, OUT_10, IN_11,
                OUT_11, IN_12, OUT_12, IN_13, OUT_13, 
                IN_14, OUT_14, IN_15, OUT_15, IN_16, OUT_16,
                IN_17, OUT_17, IN_18, OUT_18, IN_19,
                OUT_19, IN_20, OUT_20, IN_21, OUT_21, 
                IN_22, OUT_22,IN_23, OUT_23, IN_24, OUT_24,
                IN_25, OUT_25, IN_26, OUT_26, IN_27,
                OUT_27, IN_28, OUT_28, IN_29, OUT_29, 
                IN_30, OUT_30, IN_31, OUT_31
                FROM PDS_SIMPEG.PEGAWAI B
                LEFT JOIN PDS_ABSENSI.ABSENSI_REKAP_ALIH_DAYA A ON B.PEGAWAI_ID = A.PEGAWAI_ID AND PERIODE = '$periode'
                LEFT JOIN pds_simpeg.PEGAWAI_CABANG_TERAKHIR I ON B.PEGAWAI_ID = I.PEGAWAI_ID
                left join pds_simpeg.pegawai_jabatan_terakhir j on b.pegawai_id = j.pegawai_id
                WHERE 1 = 1  AND COALESCE(B.STATUS_HAPUS, '0') =  ANY (VALUES ('0'))
                AND I.PERUSAHAAN_ID = $perusahaan 
                AND I.PERUSAHAAN_CABANG_ID LIKE '".$cabang."%'  
                AND (
                    (A.STATUS_PEGAWAI_ID = 1 OR A.STATUS_PEGAWAI_ID = 5) 
                    OR 
                    (TANGGAL_PENSIUN > TO_DATE('".$periode."', 'MMYYYY') OR TANGGAL_MUTASI_KELUAR > TO_DATE('".$periode."', 'MMYYYY') OR TANGGAL_WAFAT > TO_DATE('".$periode."', 'MMYYYY')) 
                    )
                
        ";
        if($kontrak!='')
        {
            $str .= "
                AND exists (select 1 from pds_project.project_kontrak_pegawai x where x.pegawai_id = b.pegawai_id and x.project_kontrak_id = ".$kontrak.")
            " ;
        }
        
        if(isset($_POST['search']['value']))
        {
            $str .= " AND UPPER(A.NAMA) LIKE"."'%".strtoupper($_POST['search']['value'])."%'";
        }
        $str .= "ORDER BY J.nama,B.NAMA Asc ";

        if($_POST["length"] != -1)  
        {  
            $str .= " limit ".$_POST['length']." offset ".$_POST['start'];
        } 

        return $this->db->query($str);
    }


    function getcount_rekap_kehadiran($periode,$cabang,$kontrak)
    {
        $perusahaan = $_SESSION['per_id'];
        $str = "
                    SELECT A.*,(HPC + HTPC + HTAD + HTAP) KAPC, B.JABATAN,B.PERUSAHAAN_JABATAN_ID, B.FINGER_ID, COALESCE(HARI_KERJA_SHIFT, KELOMPOK) JUMLAH_H_SHIFT FROM
                    (
                    SELECT   A.KELOMPOK_PEGAWAI, A.PEGAWAI_ID, A.STATUS_PEGAWAI_ID, TANGGAL_PENSIUN, TANGGAL_MUTASI_KELUAR, TANGGAL_WAFAT, E.KATEGORI KATEGORI_JABATAN, A.NRP, A.NAMA, A.DEPARTEMEN_ID, ((H + HT + HPC + HTPC + HTAD + HTAP) + (STK + SDK) + (ITK + IDK) + (CD + CM + CT + CAP + CS + CB) + (DL + DLK + P + A)) KELOMPOK,
                            (H + HT + HPC + HTPC + HTAD + HTAP) JUMLAH_H, H, HT, HPC, HTPC, HTAD, HTAP,
                            (STK + SDK) JUMLAH_S, STK, SDK, (ITK + IDK) JUMLAH_I, ITK, IDK,
                            (CD + CM + CT + CAP + CS + CB) JUMLAH_C, CT, CAP, CS, CB, CD, CM, DL, DLK, P, 
                            (DL + DLK + P) JUMLAH_D, A JUMLAH_A
                        FROM PDS_SIMPEG.PEGAWAI A
                            LEFT JOIN
                            (SELECT   PEGAWAI_ID,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'H'
                                                            THEN JUMLAH
                                                    END), 0) H,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'HT'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) HT,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'HPC'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) HPC,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'HTPC'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) HTPC,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'HTAD'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) HTAD,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'HTAP'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) HTAP,     
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'P'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) P,                        
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'STK'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) STK,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'SDK'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) SDK,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'ITK'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) ITK,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'IDK'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) IDK,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'CT'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) CT,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'CAP'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) CAP,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'CS'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) CS,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'CB'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) CB,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'CD'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) CD,  
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'CM'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) CM,                
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'DL'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) DL,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'DLK'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) DLK,         
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'A'
                                                            THEN JUMLAH
                                                    END), 0) A
                                FROM (SELECT   PEGAWAI_ID, KEHADIRAN, COUNT (KEHADIRAN) JUMLAH
                                            FROM PDS_ABSENSI.REKAP_ABSENSI_KOREKSI Y
                                        WHERE PERIODE = '$periode'
                                        GROUP BY PEGAWAI_ID, KEHADIRAN) X
                            GROUP BY PEGAWAI_ID) B ON A.PEGAWAI_ID = B.PEGAWAI_ID
                            INNER JOIN pds_simpeg.PEGAWAI_JENIS_PEGAWAI_TERAKHIR C
                            ON A.PEGAWAI_ID = C.PEGAWAI_ID
                            LEFT JOIN pds_simpeg.PEGAWAI_CABANG_TERAKHIR D 
                            ON A.PEGAWAI_ID = D.PEGAWAI_ID
                            LEFT JOIN pds_simpeg.PEGAWAI_JABATAN_TERAKHIR E 
                            ON A.PEGAWAI_ID = E.PEGAWAI_ID
                    WHERE 1 = 1 
                        AND EXISTS(SELECT 1 FROM PDS_ABSENSI.ABSENSI_REKAP X WHERE X.PEGAWAI_ID = A.PEGAWAI_ID AND PERIODE = '$periode')
                    UNION ALL
                    SELECT   A.KELOMPOK_PEGAWAI, A.PEGAWAI_ID, A.STATUS_PEGAWAI_ID, TANGGAL_PENSIUN, TANGGAL_MUTASI_KELUAR, TANGGAL_WAFAT, E.KATEGORI KATEGORI_JABATAN, A.NRP, A.NAMA, A.DEPARTEMEN_ID, F.JUMLAH KELOMPOK,
                            ((F.JUMLAH - (HT + HPC + HTPC + HTAD + HTAP) - (STK + SDK) - (ITK + IDK) - (CD + CM + CT + CAP + CS + CB) - (DL + DLK + P + A)) + HT + HPC + HTPC + HTAD + HTAP) JUMLAH_H, 
                            (F.JUMLAH - (HT + HPC + HTPC + HTAD + HTAP) - (STK + SDK) - (ITK + IDK) - (CD + CM + CT + CAP + CS + CB) - (DL + DLK + P + A)) H, HT, HPC, HTPC, HTAD, HTAP,
                            (STK + SDK) JUMLAH_S, STK, SDK, (ITK + IDK) JUMLAH_I, ITK, IDK,
                            (CD + CM + CT + CAP + CS + CB) JUMLAH_C, CT, CAP, CS, CB, CD, CM, DL, DLK, P, 
                            (DL + DLK + P) JUMLAH_D, A JUMLAH_A
                        FROM PDS_SIMPEG.PEGAWAI A
                            LEFT JOIN
                            (SELECT   PEGAWAI_ID,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'H'
                                                            THEN JUMLAH
                                                    END), 0) H,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'HT'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) HT,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'HPC'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) HPC,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'HTPC'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) HTPC,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'HTAD'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) HTAD,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'HTAP'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) HTAP,     
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'P'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) P,                        
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'STK'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) STK,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'SDK'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) SDK,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'ITK'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) ITK,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'IDK'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) IDK,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'CT'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) CT,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'CAP'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) CAP,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'CS'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) CS,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'CB'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) CB,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'CD'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) CD,  
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'CM'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) CM,                
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'DL'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) DL,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'DLK'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) DLK,         
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'A'
                                                            THEN JUMLAH
                                                    END), 0) A
                                FROM (SELECT   PEGAWAI_ID, KEHADIRAN, COUNT (KEHADIRAN) JUMLAH
                                            FROM PDS_ABSENSI.REKAP_ABSENSI_KOREKSI Y
                                        WHERE PERIODE = '$periode'
                                        GROUP BY PEGAWAI_ID, KEHADIRAN) X
                            GROUP BY PEGAWAI_ID) B ON A.PEGAWAI_ID = B.PEGAWAI_ID
                            INNER JOIN pds_simpeg.PEGAWAI_JENIS_PEGAWAI_TERAKHIR C
                            ON A.PEGAWAI_ID = C.PEGAWAI_ID
                            LEFT JOIN pds_simpeg.PEGAWAI_CABANG_TERAKHIR D 
                            ON A.PEGAWAI_ID = D.PEGAWAI_ID
                            LEFT JOIN pds_simpeg.PEGAWAI_JABATAN_TERAKHIR E 
                            ON A.PEGAWAI_ID = E.PEGAWAI_ID
                            LEFT JOIN PDS_ABSENSI.PEGAWAI_HARI_KERJA F 
                            ON A.PEGAWAI_ID = F.PEGAWAI_ID AND PERIODE = '".$periode."'
                    WHERE 1 = 1
                        AND NOT EXISTS(SELECT 1 FROM PDS_ABSENSI.ABSENSI_REKAP X WHERE X.PEGAWAI_ID = A.PEGAWAI_ID AND PERIODE = '$periode')
                    ) A 
                    LEFT JOIN(
                        SELECT PEGAWAI_ID, PERIODE, CASE WHEN MAX(TO_TIMESTAMP((CASE WHEN JAM_PULANG < JAM_MASUK OR JAM_PULANG = JAM_MASUK THEN '02012014' || JAM_PULANG ELSE '01012014' || JAM_PULANG END), 'DDMMYYYYHH24:MI') - 
                                            TO_TIMESTAMP('01012014' || JAM_MASUK, 'DDMMYYYYHH24:MI')) >= '12:00:00'::INTERVAL THEN 
                                            (CASE WHEN MAX(TOLERANSI_PULANG - TOLERANSI_MASUK) + INTERVAL '6 MINUTE' >= '12:00:00'::INTERVAL OR MAX(TOLERANSI_MASUK) IS NULL THEN
                                                25::INTEGER ELSE NULL END) 
                                            ELSE NULL END HARI_KERJA_SHIFT FROM PDS_ABSENSI.PEGAWAI_JADWAL 
                                            WHERE NOT JAM_MASUK = 'OFF'
                        GROUP BY PEGAWAI_ID, PERIODE) C ON A.PEGAWAI_ID = C.PEGAWAI_ID AND C.PERIODE = '".$periode."'
                    LEFT JOIN (
                    SELECT A.PEGAWAI_ID, A.FINGER_ID, B.NAMA JABATAN,B.PERUSAHAAN_JABATAN_ID FROM PDS_SIMPEG.PEGAWAI A INNER JOIN pds_simpeg.PEGAWAI_JABATAN_TERAKHIR B ON A.PEGAWAI_ID = B.PEGAWAI_ID
                    ) B ON A.PEGAWAI_ID = B.PEGAWAI_ID
                    WHERE 1 = 1 
                        AND (   (A.STATUS_PEGAWAI_ID = 1 OR A.STATUS_PEGAWAI_ID = 5)
                            OR (   TANGGAL_PENSIUN > TO_DATE ('".$periode."', 'MMYYYY')
                                OR TANGGAL_MUTASI_KELUAR > TO_DATE ('".$periode."', 'MMYYYY')
                                OR TANGGAL_WAFAT > TO_DATE ('".$periode."', 'MMYYYY')
                                )
                            )							
                AND EXISTS(SELECT 1 FROM pds_simpeg.PEGAWAI_CABANG_TERAKHIR_SIMPLE X WHERE A.PEGAWAI_ID = X.PEGAWAI_ID AND X.PERUSAHAAN_ID = $perusahaan AND X.PERUSAHAAN_CABANG_ID LIKE '".$cabang."%')                   
                
        ";
        
        if($kontrak!='')
        {
            $str .= "
                AND exists (select 1 from pds_project.project_kontrak_pegawai x where x.pegawai_id = a.pegawai_id and x.project_kontrak_id = ".$kontrak.")
            " ;
        }
        
        if(isset($_POST['search']['value']))
        {
            $str .= " AND UPPER(A.NAMA) LIKE"."'%".strtoupper($_POST['search']['value'])."%'";
        }

        $query = "select count (q.*) as rowcount from (".$str.") q";
        $hitung = $this->db->query($query)->row();
        return $hitung->rowcount;
    }


    function select_rekap_kehadiran($periode,$cabang,$kontrak)
    {
        $perusahaan = $_SESSION['per_id'];        
        $str = "
                    SELECT A.*,(HPC + HTPC + HTAD + HTAP) KAPC, B.JABATAN,B.PERUSAHAAN_JABATAN_ID, B.FINGER_ID, COALESCE(HARI_KERJA_SHIFT, KELOMPOK) JUMLAH_H_SHIFT FROM
                    (
                    SELECT   A.KELOMPOK_PEGAWAI, A.PEGAWAI_ID, A.STATUS_PEGAWAI_ID, TANGGAL_PENSIUN, TANGGAL_MUTASI_KELUAR, TANGGAL_WAFAT, E.KATEGORI KATEGORI_JABATAN, A.NRP, A.NAMA, A.DEPARTEMEN_ID, ((H + HT + HPC + HTPC + HTAD + HTAP) + (STK + SDK) + (ITK + IDK) + (CD + CM + CT + CAP + CS + CB) + (DL + DLK + P + A)) KELOMPOK,
                            (H + HT + HPC + HTPC + HTAD + HTAP) JUMLAH_H, H, HT, HPC, HTPC, HTAD, HTAP,
                            (STK + SDK) JUMLAH_S, STK, SDK, (ITK + IDK) JUMLAH_I, ITK, IDK,
                            (CD + CM + CT + CAP + CS + CB) JUMLAH_C, CT, CAP, CS, CB, CD, CM, DL, DLK, P, 
                            (DL + DLK + P) JUMLAH_D, A JUMLAH_A
                        FROM PDS_SIMPEG.PEGAWAI A
                            LEFT JOIN
                            (SELECT   PEGAWAI_ID,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'H'
                                                            THEN JUMLAH
                                                    END), 0) H,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'HT'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) HT,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'HPC'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) HPC,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'HTPC'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) HTPC,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'HTAD'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) HTAD,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'HTAP'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) HTAP,     
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'P'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) P,                        
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'STK'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) STK,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'SDK'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) SDK,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'ITK'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) ITK,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'IDK'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) IDK,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'CT'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) CT,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'CAP'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) CAP,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'CS'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) CS,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'CB'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) CB,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'CD'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) CD,  
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'CM'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) CM,                
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'DL'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) DL,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'DLK'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) DLK,         
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'A'
                                                            THEN JUMLAH
                                                    END), 0) A
                                FROM (SELECT   PEGAWAI_ID, KEHADIRAN, COUNT (KEHADIRAN) JUMLAH
                                            FROM PDS_ABSENSI.REKAP_ABSENSI_KOREKSI Y
                                        WHERE PERIODE = '$periode'
                                        GROUP BY PEGAWAI_ID, KEHADIRAN) X
                            GROUP BY PEGAWAI_ID) B ON A.PEGAWAI_ID = B.PEGAWAI_ID
                            INNER JOIN pds_simpeg.PEGAWAI_JENIS_PEGAWAI_TERAKHIR C
                            ON A.PEGAWAI_ID = C.PEGAWAI_ID
                            LEFT JOIN pds_simpeg.PEGAWAI_CABANG_TERAKHIR D 
                            ON A.PEGAWAI_ID = D.PEGAWAI_ID
                            LEFT JOIN pds_simpeg.PEGAWAI_JABATAN_TERAKHIR E 
                            ON A.PEGAWAI_ID = E.PEGAWAI_ID
                    WHERE 1 = 1 
                        AND EXISTS(SELECT 1 FROM PDS_ABSENSI.ABSENSI_REKAP X WHERE X.PEGAWAI_ID = A.PEGAWAI_ID AND PERIODE = '$periode')
                    UNION ALL
                    SELECT   A.KELOMPOK_PEGAWAI, A.PEGAWAI_ID, A.STATUS_PEGAWAI_ID, TANGGAL_PENSIUN, TANGGAL_MUTASI_KELUAR, TANGGAL_WAFAT, E.KATEGORI KATEGORI_JABATAN, A.NRP, A.NAMA, A.DEPARTEMEN_ID, F.JUMLAH KELOMPOK,
                            ((F.JUMLAH - (HT + HPC + HTPC + HTAD + HTAP) - (STK + SDK) - (ITK + IDK) - (CD + CM + CT + CAP + CS + CB) - (DL + DLK + P + A)) + HT + HPC + HTPC + HTAD + HTAP) JUMLAH_H, 
                            (F.JUMLAH - (HT + HPC + HTPC + HTAD + HTAP) - (STK + SDK) - (ITK + IDK) - (CD + CM + CT + CAP + CS + CB) - (DL + DLK + P + A)) H, HT, HPC, HTPC, HTAD, HTAP,
                            (STK + SDK) JUMLAH_S, STK, SDK, (ITK + IDK) JUMLAH_I, ITK, IDK,
                            (CD + CM + CT + CAP + CS + CB) JUMLAH_C, CT, CAP, CS, CB, CD, CM, DL, DLK, P, 
                            (DL + DLK + P) JUMLAH_D, A JUMLAH_A
                        FROM PDS_SIMPEG.PEGAWAI A
                            LEFT JOIN
                            (SELECT   PEGAWAI_ID,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'H'
                                                            THEN JUMLAH
                                                    END), 0) H,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'HT'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) HT,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'HPC'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) HPC,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'HTPC'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) HTPC,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'HTAD'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) HTAD,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'HTAP'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) HTAP,     
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'P'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) P,                        
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'STK'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) STK,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'SDK'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) SDK,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'ITK'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) ITK,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'IDK'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) IDK,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'CT'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) CT,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'CAP'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) CAP,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'CS'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) CS,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'CB'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) CB,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'CD'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) CD,  
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'CM'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) CM,                
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'DL'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) DL,
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'DLK'
                                                            THEN JUMLAH
                                                    END),
                                                0
                                                ) DLK,         
                                    COALESCE (MAX (CASE
                                                        WHEN KEHADIRAN = 'A'
                                                            THEN JUMLAH
                                                    END), 0) A
                                FROM (SELECT   PEGAWAI_ID, KEHADIRAN, COUNT (KEHADIRAN) JUMLAH
                                            FROM PDS_ABSENSI.REKAP_ABSENSI_KOREKSI Y
                                        WHERE PERIODE = '$periode'
                                        GROUP BY PEGAWAI_ID, KEHADIRAN) X
                            GROUP BY PEGAWAI_ID) B ON A.PEGAWAI_ID = B.PEGAWAI_ID
                            INNER JOIN pds_simpeg.PEGAWAI_JENIS_PEGAWAI_TERAKHIR C
                            ON A.PEGAWAI_ID = C.PEGAWAI_ID
                            LEFT JOIN pds_simpeg.PEGAWAI_CABANG_TERAKHIR D 
                            ON A.PEGAWAI_ID = D.PEGAWAI_ID
                            LEFT JOIN pds_simpeg.PEGAWAI_JABATAN_TERAKHIR E 
                            ON A.PEGAWAI_ID = E.PEGAWAI_ID
                            LEFT JOIN PDS_ABSENSI.PEGAWAI_HARI_KERJA F 
                            ON A.PEGAWAI_ID = F.PEGAWAI_ID AND PERIODE = '".$periode."'
                    WHERE 1 = 1
                        AND NOT EXISTS(SELECT 1 FROM PDS_ABSENSI.ABSENSI_REKAP X WHERE X.PEGAWAI_ID = A.PEGAWAI_ID AND PERIODE = '$periode')
                    ) A 
                    LEFT JOIN(
                        SELECT PEGAWAI_ID, PERIODE, CASE WHEN MAX(TO_TIMESTAMP((CASE WHEN JAM_PULANG < JAM_MASUK OR JAM_PULANG = JAM_MASUK THEN '02012014' || JAM_PULANG ELSE '01012014' || JAM_PULANG END), 'DDMMYYYYHH24:MI') - 
                                            TO_TIMESTAMP('01012014' || JAM_MASUK, 'DDMMYYYYHH24:MI')) >= '12:00:00'::INTERVAL THEN 
                                            (CASE WHEN MAX(TOLERANSI_PULANG - TOLERANSI_MASUK) + INTERVAL '6 MINUTE' >= '12:00:00'::INTERVAL OR MAX(TOLERANSI_MASUK) IS NULL THEN
                                                25::INTEGER ELSE NULL END) 
                                            ELSE NULL END HARI_KERJA_SHIFT FROM PDS_ABSENSI.PEGAWAI_JADWAL 
                                            WHERE NOT JAM_MASUK = 'OFF'
                        GROUP BY PEGAWAI_ID, PERIODE) C ON A.PEGAWAI_ID = C.PEGAWAI_ID AND C.PERIODE = '".$periode."'
                    LEFT JOIN (
                    SELECT A.PEGAWAI_ID, A.FINGER_ID, B.NAMA JABATAN,B.PERUSAHAAN_JABATAN_ID FROM PDS_SIMPEG.PEGAWAI A INNER JOIN pds_simpeg.PEGAWAI_JABATAN_TERAKHIR B ON A.PEGAWAI_ID = B.PEGAWAI_ID
                    ) B ON A.PEGAWAI_ID = B.PEGAWAI_ID
                    WHERE 1 = 1 
                        AND (   (A.STATUS_PEGAWAI_ID = 1 OR A.STATUS_PEGAWAI_ID = 5)
                            OR (   TANGGAL_PENSIUN > TO_DATE ('".$periode."', 'MMYYYY')
                                OR TANGGAL_MUTASI_KELUAR > TO_DATE ('".$periode."', 'MMYYYY')
                                OR TANGGAL_WAFAT > TO_DATE ('".$periode."', 'MMYYYY')
                                )
                            )							
                AND EXISTS(SELECT 1 FROM pds_simpeg.PEGAWAI_CABANG_TERAKHIR_SIMPLE X WHERE A.PEGAWAI_ID = X.PEGAWAI_ID AND X.PERUSAHAAN_ID = $perusahaan AND X.PERUSAHAAN_CABANG_ID LIKE '".$cabang."%')                   
                
        ";
        
        if($kontrak!='')
        {
            $str .= "
                AND exists (select 1 from pds_project.project_kontrak_pegawai x where x.pegawai_id = a.pegawai_id and x.project_kontrak_id = ".$kontrak.")
            " ;
        }
        
        if(isset($_POST['search']['value']))
        {
            $str .= " AND UPPER(A.NAMA) LIKE"."'%".strtoupper($_POST['search']['value'])."%'";
        }
        
        $str .= "ORDER BY A.KATEGORI_JABATAN, A.NAMA ";
        //echo $str;;exit;

        if($_POST["length"] != -1)  
        {  
            $str .= " limit ".$_POST['length']." offset ".$_POST['start'];
        }

        

        return $this->db->query($str); 
    }


    function select_periode_absen()
    {
        $str = "
        select isi.periode,right(isi.periode,4) tahun, 
        case
            when left(isi.periode,2)='01' then 'Januari '||right(isi.periode,4)
            when left(isi.periode,2)='02' then 'Februari '||right(isi.periode,4)
            when left(isi.periode,2)='03' then 'Maret '||right(isi.periode,4)
            when left(isi.periode,2)='04' then 'April '||right(isi.periode,4)
            when left(isi.periode,2)='05' then 'Mei '||right(isi.periode,4)
            when left(isi.periode,2)='06' then 'Juni '||right(isi.periode,4)
            when left(isi.periode,2)='07' then 'Juli '||right(isi.periode,4)
            when left(isi.periode,2)='08' then 'Agustus '||right(isi.periode,4)
            when left(isi.periode,2)='09' then 'September '||right(isi.periode,4)
            when left(isi.periode,2)='10' then 'Oktober '||right(isi.periode,4)
            when left(isi.periode,2)='11' then 'November '||right(isi.periode,4)
            when left(isi.periode,2)='12' then 'Desember '||right(isi.periode,4)
        end periodee
        from (
        select distinct(periode) from pds_absensi.absensi_koreksi where periode not in ('','011900','121900') order by 1 asc
        ) isi
        order by tahun,periode asc
        ";

        return $this->db->query($str);
    }

    function select_perusahaan_cabang()
    {
        $str = "
            select perusahaan_cabang_id,nama from pds_simpeg.perusahaan_cabang where perusahaan_id =".$_SESSION['per_id']."  order by 1 asc;
        ";

        return $this->db->query($str);

    }

    function select_kontrak_project()
    {
        $str = "
        select * from pds_project.project_kontrak_terakhir k
        where exists(select 1 from pds_project.project p where p.project_id = k.project_id and p.perusahaan_id = ".$_SESSION['per_id']." );
        ";

        return $this->db->query($str);
    }


}