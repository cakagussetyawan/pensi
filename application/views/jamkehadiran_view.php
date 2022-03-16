<link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css')?>" rel="stylesheet">
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">
<link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">

<script src="<?php echo base_url('assets/jquery/jquery-2.1.4.min.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>


<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/sweetalert2/1.3.3/sweetalert2.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/sweetalert2/0.4.5/sweetalert2.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/sweetalert2/1.3.3/sweetalert2.min.js"></script>
<style type="text/css">
    .susu{  font-size: 16px;                 
            text-align: center; 
        }        
</style>
    <div class = "row">
        <p class="susu">
            Laporan Jam Kerja Karyawan PT Pelindo Daya Sejahtera <br/> Yang ditugaskan Pada : PT Terminal Petikemas Surabaya
        </p>
        <p class="susu">Periode Bulan : <?= $periode ?></p>
        <hr>
      <button class="btn btn-success"  onclick="add_person()"><i class="glyphicon glyphicon-search"></i> Pencarian</button>
      <button class="btn btn-info"  onclick="cetak()"><i class="glyphicon glyphicon-print"></i> Cetak</button>
      <br />
      <br />
    
      <table id="tabel-data" class="table table-striped table-bordered" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>NRP</th>
                    <th>Jabatan</th>
                    <?php
                    for ($i=1; $i<=31 ; $i++ ){
                        echo '<th colspan="2"style="text-align:center">'.$i.'</th>';
                    }    
                    ?>        
                </tr>
                <tr>
                    <th colspan="3"></th>
                    <?php
                    for ($i=1; $i<=31 ; $i++ ){
                        echo "<th>In</th>
                              <th>Out</th>";
                    }    
                    ?>        
                </tr>
            </thead>
            
            <tbody>
                <?php foreach ($isi as $row): ?>
                <tr>
                    <td><?=$row->nama ?></td>
                    <td><?=$row->nrp ?></td>
                    <td><?=$row->jabatan ?></td>
                    <td><?=$row->in_1 ?></td> <td><?=$row->out_1 ?></td>
                    <td><?=$row->in_2 ?></td> <td><?=$row->out_2 ?></td>
                    <td><?=$row->in_3 ?></td> <td><?=$row->out_3 ?></td>
                    <td><?=$row->in_4 ?></td> <td><?=$row->out_4 ?></td>
                    <td><?=$row->in_5 ?></td> <td><?=$row->out_5 ?></td>
                    <td><?=$row->in_6 ?></td> <td><?=$row->out_6 ?></td>
                    <td><?=$row->in_7 ?></td> <td><?=$row->out_7 ?></td>
                    <td><?=$row->in_8 ?></td> <td><?=$row->out_8 ?></td>
                    <td><?=$row->in_9 ?></td> <td><?=$row->out_9 ?></td>
                    <td><?=$row->in_10 ?></td> <td><?=$row->out_10 ?></td>
                    <td><?=$row->in_11 ?></td> <td><?=$row->out_11 ?></td>
                    <td><?=$row->in_12 ?></td> <td><?=$row->out_12 ?></td>
                    <td><?=$row->in_13 ?></td> <td><?=$row->out_13 ?></td>
                    <td><?=$row->in_14 ?></td> <td><?=$row->out_14 ?></td>
                    <td><?=$row->in_15 ?></td> <td><?=$row->out_15 ?></td>
                    <td><?=$row->in_16 ?></td> <td><?=$row->out_16 ?></td>
                    <td><?=$row->in_17 ?></td> <td><?=$row->out_17 ?></td>
                    <td><?=$row->in_18 ?></td> <td><?=$row->out_18 ?></td>
                    <td><?=$row->in_19 ?></td> <td><?=$row->out_19 ?></td>
                    <td><?=$row->in_20 ?></td> <td><?=$row->out_20 ?></td>
                    <td><?=$row->in_21 ?></td> <td><?=$row->out_21 ?></td>
                    <td><?=$row->in_22 ?></td> <td><?=$row->out_22 ?></td>
                    <td><?=$row->in_23 ?></td> <td><?=$row->out_23 ?></td>
                    <td><?=$row->in_24 ?></td> <td><?=$row->out_24 ?></td>
                    <td><?=$row->in_25 ?></td> <td><?=$row->out_25 ?></td>
                    <td><?=$row->in_26 ?></td> <td><?=$row->out_26 ?></td>
                    <td><?=$row->in_27 ?></td> <td><?=$row->out_27 ?></td>
                    <td><?=$row->in_28 ?></td> <td><?=$row->out_28 ?></td>
                    <td><?=$row->in_29 ?></td> <td><?=$row->out_29 ?></td>
                    <td><?=$row->in_30 ?></td> <td><?=$row->out_30 ?></td>
                    <td><?=$row->in_31 ?></td> <td><?=$row->out_31 ?></td>                    
                </tr>                        
                <?php endforeach ?>        
            </tbody>
        </table>
      

    </div>


  </div>


  <script type="text/javascript">

    var save_method; //for save method string
    var table;
    $(document).ready(function() {
      table = $('#tabel-data').DataTable({         
        /* "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode. */
        "scrollX": true,      
        "order": [[2,'desc'],[0,'asc']]

      });
    });

    function add_person()
    {
      save_method = 'add';
      $('#form')[0].reset(); // reset form on modals
      $('#modal_form').modal('show'); // show bootstrap modal
      //$('#modal_form').modal('toggle'); // show bootstrap modal
      $('.modal-title').text('Pencarian'); // Set Title to Bootstrap modal title
    }

    function cetak()
    {
      save_method = 'add';
      $('#form')[0].reset(); // reset form on modals
      $('#modal_formcetak').modal('show'); // show bootstrap modal
      //$('#modal_form').modal('toggle'); // show bootstrap modal
      $('.modal-title').text('Cetak'); // Set Title to Bootstrap modal title
    }

    

    function edit_person(id)
    {
      save_method = 'update';
      $('#form')[0].reset(); // reset form on modals

      //Ajax Load data from ajax
      $.ajax({
        url : "<?php echo site_url('welcome/ajax_edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
         
          $('[name="id"]').val(data.id);
          $('[name="firstName"]').val(data.firstName);
          $('[name="lastName"]').val(data.lastName);
          $('[name="gender"]').val(data.gender);
          $('[name="address"]').val(data.address);
          $('[name="dob"]').val(data.dob);
          
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Teacher'); // Set title to Bootstrap modal title
            
          },
          error: function (jqXHR, textStatus, errorThrown)
          {
            alert('Error get data from ajax');
          }
        });
    }

    function reload_table()
    {
      table.ajax.reload(null,false); //reload datatable ajax 
    }

    function save()
    {
      var url;
      if(save_method == 'add') 
      {
        url = "<?php echo site_url('welcome/ajax_add')?>";
      }
      else
      {
        url = "<?php echo site_url('welcome/ajax_update')?>";
      }

       // ajax adding data to database
       $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {
               //if success close modal and reload ajax table
               $('#modal_form').modal('hide');
               reload_table();
               swal(
                'Good job!',
                'Data has been save!',
                'success'
                )
             },
             error: function (jqXHR, textStatus, errorThrown)
             {
              alert('Error adding / update data');
            }
          });
     }

     function delete_person(id)
     {

      swal({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        closeOnConfirm: false
      }).then(function(isConfirm) {
        if (isConfirm) {

     // ajax delete data to database
     $.ajax({
      url : "<?php echo site_url('welcome/ajax_delete')?>/"+id,
      type: "POST",
      dataType: "JSON",
      success: function(data)
      {
               //if success reload ajax table
               $('#modal_form').modal('hide');
               reload_table();
               swal(
                'Deleted!',
                'Your file has been deleted.',
                'success'
                );
             },
             error: function (jqXHR, textStatus, errorThrown)
             {
              alert('Error adding / update data');
            }
          });

     
   }
 })
      
    }

    function view_person(id)
    {
        $.ajax({
            url : "<?php echo site_url('welcome/list_by_id')?>/" + id,
            type: "GET",
            success: function(result)
            {
                $('#haha').empty().html(result).fadeIn('slow');
            },
            error: function (jqXHR, textStatus, errorThrown)
            {

            }
        });
    }

    function filter(){              
        $('#modal_form').modal('toggle');
        var divisi=$("#divisi").val();        
        var periode = $("#bulan").val()+$("#tahun").val();                          
        var jabatan = $("#jabatan").val();                          
        
        var href = "<?= base_url() ?>Presensi/jamkehadiran?reqDivisi="+divisi+"&reqPeriode="+periode+"&reqJabatan="+jabatan;                
        $('#haha').empty().load(href).fadeIn('slow');
        $('div.modal-backrop').remove();
    }

    function cetakexcel(){              
        $('#modal_formcetak').modal('toggle');
        var divisi=$("#cdivisi").val();        
        var periode = $("#cbulan").val()+$("#ctahun").val();           
        var jabatan = $("#cjabatan").val();
        var href = "<?= base_url() ?>Presensi/jamkehadiran_excel?reqDivisi="+divisi+"&reqPeriode="+periode+"&reqJabatan="+jabatan;                
        newWindow = window.open(href);
        newWindow.focus();
        //$('#haha').empty().load(href).fadeIn('slow');
        $('div.modal-backrop').remove();
    }

    function cetakpdf(){
      alert ("Mohon maaf, menu ini belum bisa digunakan");
    }

     //datepicker
    $('.datepicker').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        orientation: "top auto",
        todayBtn: true,
        todayHighlight: true,  
    });


  </script>

  <!-- Bootstrap modal -->
  <div class="modal" id="modal_form" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h3 class="modal-title">Pilih Periode Absen</h3>
        </div>
        <div class="modal-body form">
          <form action="#" id="form" class="form-horizontal">
            <input type="hidden" value="" name="id"/> 
            <div class="form-body">
              <div class="form-group">
                <label class="control-label col-md-3">Periode</label>
                <div class="col-md-6">
                    <select name="gender" class="form-control" id="bulan">
                        <option value="01">Januari</option>
                        <option value="02">Februari</option>
                        <option value="03">Maret</option>
                        <option value="04">April</option>
                        <option value="05">Mei</option>
                        <option value="06">Juni</option>
                        <option value="07">Juli</option>
                        <option value="08">Agustuts</option>
                        <option value="09">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>                        
                    </select>
                </div>
                <div class="col-md-3">
                  <select name="tahun" id="tahun" class="form-control">
                    <option value="2017">2017</option>
                    <option value="2018">2018</option>
                    <option value="2019">2019</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3">Divisi / Group</label>
                <div class="col-md-9">
                    <select name="gender" class="form-control" id="divisi">                    
                        <?php foreach($divisi as $divisi): ?>
                          <option value="<?= $divisi->perusahaan_cabang_id ?>"><?= $divisi->nama?></option>  
                        <?php endforeach ?>                        
                    </select>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3">Kelompok Jabatan</label>
                <div class="col-md-9">
                    <select name="gender" class="form-control" id="jabatan">                    
                        <option value="0">Semua</option>
                        <?php foreach($jabatan as $jabatan): ?>
                          <option value="<?= $jabatan->kode ?>"><?= $jabatan->jabatan?></option>  
                        <?php endforeach ?>                        
                    </select>
                </div>
              </div>                                          
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" id="btnSave" onclick="filter()" class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  <!-- End Bootstrap modal -->



   <!-- Bootstrap modal Cetak -->
  <div class="modal" id="modal_formcetak" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h3 class="modal-title">Cetak</h3>
        </div>
        <div class="modal-body form">
          <form action="#" id="form" class="form-horizontal">
            <input type="hidden" value="" name="id"/> 
            <div class="form-body">
              <div class="form-group">
                <label class="control-label col-md-3">Periode</label>
                <div class="col-md-6">
                    <select name="gender" class="form-control" id="cbulan">
                        <option value="01">Januari</option>
                        <option value="02">Februari</option>
                        <option value="03">Maret</option>
                        <option value="04">April</option>
                        <option value="05">Mei</option>
                        <option value="06">Juni</option>
                        <option value="07">Juli</option>
                        <option value="08">Agustuts</option>
                        <option value="09">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>                        
                    </select>
                </div>
                <div class="col-md-3">
                  <select name="tahun" id="ctahun" class="form-control">
                    <option value="2017">2017</option>
                    <option value="2018">2018</option>
                    <option value="2019">2019</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3">Divisi / Group</label>
                <div class="col-md-9">
                    <select name="gender" class="form-control" id="cdivisi">                    
                        <?php foreach($divisii as $div): ?>
                          <option value="<?= $div->perusahaan_cabang_id ?>"><?= $div->nama?></option>  
                        <?php endforeach ?>                        
                    </select>
                </div>
              </div> 
              <div class="form-group">
                <label class="control-label col-md-3">Kelompok Jabatan</label>
                <div class="col-md-9">
                    <select name="gender" class="form-control" id="cjabatan">                    
                        <option value="0">Semua</option>
                        <?php foreach($jabatann as $jabatan): ?>
                          <option value="<?= $jabatan->kode ?>"><?= $jabatan->jabatan?></option>  
                        <?php endforeach ?>                        
                    </select>
                </div>
              </div>                                            
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" id="btnpdf" onclick="cetakpdf()" class="btn btn-primary">Print PDF</button>
          <button type="button" id="btnexcel" onclick="cetakexcel()" class="btn btn-success">Excel</button>
          
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  <!-- End Bootstrap modal --> 

</body>
</html>