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
            Laporan Rekapitulasi Kehadiran Karyawan PT Pelindo Daya Sejahtera <br/> Yang ditugaskan Pada : PT Terminal Petikemas Surabaya
        </p>
        <p class="susu">Periode Bulan : <?= $periode ?></p>
        <hr>
      <button class="btn btn-success" onclick="add_person()"><i class="glyphicon glyphicon-search"></i> Pencarian</button>
      <br />
      <br />
    
      <table id="tabel-data" class="table table-striped table-bordered" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>NRP</th>
                    <th>Hadir</th>                    
                    <th>Terlambat</th>
                    <th>K.Absen</th>
                    <th>Ijin</th>
                    <th>Sakit</th>
                    <th>Dinas</th>
                    <th>Cuti</th>
                    <th>Alpha</th>
                </tr>                        
            </thead>
            
            <tbody>
                <?php foreach ($isi as $row): ?>
                <tr>   
                    <td><?= $row->nama ?></td>                                     
                    <td><?= $row->nrp ?></td>
                    <td><?= $row->h ?></td>
                    <td><?= $row->ht ?></td>
                    <td><?= $row->kapc ?></td>
                    <td><?= $row->jumlah_i ?></td>
                    <td><?= $row->jumlah_s ?></td>
                    <td><?= $row->jumlah_d ?></td>
                    <td><?= $row->jumlah_c ?></td>
                    <td><?= $row->jumlah_a ?></td>
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
        "scrollX": true               

      });
    });

    function add_person()
    {
      save_method = 'add';
      $('#form')[0].reset(); // reset form on modals
      $('#modal_form').modal('show'); // show bootstrap modal
      $('.modal-title').text('Add New Teacher'); // Set Title to Bootstrap modal title
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
        var periode = document.getElementById("periode").value;                           
        var href = "<?= base_url() ?>Presensi/rekapkehadiran_filter/".concat(periode);                
        $('#haha').empty().load(href).fadeIn('slow');
        document.getElementById('#modal_form').modal('hide');
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
                <div class="col-md-9">
                    <select name="gender" class="form-control" id="periode">
                        <option value="012018">Januari</option>
                        <option value="022018">Februari</option>
                        <option value="032018">Maret</option>
                        <option value="042018">April</option>
                        <option value="052018">Mei</option>
                        <option value="062018">Juni</option>
                        <option value="072018">Juli</option>
                        <option value="082018">Agustuts</option>
                        <option value="092018">September</option>
                        <option value="102018">Oktober</option>
                        <option value="112018">November</option>
                        <option value="122018">Desember</option>                        
                    </select>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3">Divisi</label>
                <div class="col-md-9">
                    <select name="gender" class="form-control">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
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
</body>
</html>