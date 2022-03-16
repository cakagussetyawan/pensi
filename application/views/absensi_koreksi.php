<?php 
$this->load->library('Tanggal');
$tanggals = new Tanggal();
$bulan = $tanggals->getNameMonth(date('n')-1);

$this->load->model('Presensi_model','presensi');
$periode    = $this->presensi->select_periode_absen()->result();
$cabang     = $this->presensi->select_perusahaan_cabang()->result();
$kontrak    = $this->presensi->select_kontrak_project()->result();
$bln = date('n')-1;
if (strlen($bln)==1)$bln='0'.$bln;
$bln = $bln.date('Y');
?>
<div class="box box-solid box-primary">    
    <div class="box-header">
        <div class="text-center">
            <h3>Data Kehadiran Karyawan </h3>
            <h4>PT Pelindo Daya Sejahtera - Penugasan <?=$_SESSION['perusahaan'] ?></h4>
            <h4 id="headerperiode">Periode Bulan :  <?=$bulan ?> Tahun : <?=date('Y') ?> </h4>
        </div>        
    </div>
    <div class="box-body">
    <table class="table table-bordered" width="100%">
        <tr>
            <td>Periode : </td>
            <td>
                <div class="form-group"> 
                    <select name="periode" id="periode" class="form-control">
                    <?php 
                        foreach($periode as $row)
                        {?>
                        <? if($row->periode == $bln) {?>
                        <option value="<?=$row->periode?>" selected><?=$row->periodee ?></option>
                        <? }else {?>
                        <option value="<?=$row->periode?>" ><?=$row->periodee ?></option> <? }?>
                    <?php  }
                    ?>                    
                    </select>
                </div>
            </td>
            <td>Cabang : </td>
            <td>
                <div class="form-group"> 
                    <select name="cabang" id="cabang" class="form-control">
                    <?php 
                        foreach($cabang as $row)
                        {?>
                        <option value="<?=$row->perusahaan_cabang_id?>"><?=$row->nama ?></option>
                    <?php  }
                    ?>                    
                    </select>
                </div>
            </td>
            <td>Kontrak : </td>
            <td>
                <div class="form-group"> 
                    <select name="kontrak" id="kontrak" class="form-control">
                    <?php 
                        foreach($kontrak as $row)
                        {?>
                        <option value="<?=$row->project_kontrak_id?>"><?=$row->nama ?></option>
                    <?php  }
                    ?>                    
                    </select>
                </div>
            </td>
        </tr>
    </table>           
    <hr>     
    <table id="tabelabsensi" class="table table-striped table-bordered" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>NRP</th>
            <th>Nama</th>
            <th>Jabatan</th>
            <th>Kelompok</th>
            <th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th>
            <th>11</th><th>12</th><th>13</th><th>14</th><th>15</th><th>16</th><th>17</th><th>18</th><th>19</th><th>20</th>
            <th>21</th><th>22</th><th>23</th><th>24</th><th>25</th><th>26</th><th>27</th><th>28</th><th>29</th><th>30</th><th>31</th>
        </tr>
    </thead>
    </table>

    </div>
    
</div>

<!-- DATATABLE -->
<script type = "text/javascript" src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js')?>"></script>

<script type="text/javascript" language="javascript" class="init">
    $(document).ready(function(){        
        table = $('#tabelabsensi').DataTable({ 
            "processing": true,
            "serverSide": true, 
            "ordering": false,
            "sScrollX": "100%",            
            "ajax": {
                url     : "<?php echo site_url('absensi/absensi_koreksi_json')?>",                            
            }
        });

        function getbulan(bln){
            
            var bulan = 'Default';
            switch (bln) {
                case '01': bulan = 'Januari'; break;
                case '02': bulan = 'Februari'; break;
                case '03': bulan = 'Maret'; break;
                case '04': bulan = 'April'; break;
                case '05': bulan = 'Mei'; break;
                case '06': bulan = 'Juni'; break;
                case '07': bulan = 'Juli'; break;
                case '08': bulan = 'Agustus'; break;
                case '09': bulan = 'September'; break;
                case '10': bulan = 'Oktober'; break;
                case '11': bulan = 'November'; break;
                case '12': bulan = 'Desember'; break;            
                default:
                    bulan = 'Default';
            }
            
            return bulan;
        }

        $("#periode").change(function(){ 
            var periode = $('#periode').val();
            var periodee    = periode.substring(0,2);
            var bulan   = getbulan(periodee);            
            var tahun   = periode.substring(2,6);

            
            $("#headerperiode").html('<h4 id="headerperiode">Periode Bulan : '+bulan+ ' Tahun : '+tahun+' </h4>');
            $('#tabelabsensi').DataTable().ajax.url("<?php echo site_url('absensi/absensi_koreksi_json?reqPeriode=')?>"+periode).load();
        });

        $("#cabang").change(function(){ 
            var periode = $('#periode').val();
            var cabang  = $('#cabang').val();
            //console.log(periode);           
            $('#tabelabsensi').DataTable().ajax.url("<?php echo site_url('absensi/absensi_koreksi_json?reqPeriode=')?>"+periode+'&reqCabang='+cabang).load();
        });
        
        $("#kontrak").change(function(){ 
            var periode = $('#periode').val();
            var cabang  = $('#cabang').val();
            var kontrak = $('#kontrak').val();
            //console.log(periode);           
            $('#tabelabsensi').DataTable().ajax.url("<?php echo site_url('absensi/absensi_koreksi_json?reqPeriode=')?>"+periode+'&reqCabang='+cabang+'&reqKontrak='+kontrak).load();
        });



    });
</script>