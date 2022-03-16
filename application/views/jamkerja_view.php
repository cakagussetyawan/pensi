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
            Laporan Kehadiran Karyawan PT Pelindo Daya Sejahtera <br/> Yang ditugaskan Pada : PT Terminal Petikemas Surabaya
        </p>
        <p class="susu">Periode Bulan : September 2018</p>
        <hr>
      <button class="btn btn-success" onclick="add_person()"><i class="glyphicon glyphicon-search"></i> Pencarian</button>
      <br />
      <br />
        <table id="tabel-data" class="table table-striped table-bordered" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>NRP</th>
                    <th>Jabatan</th>
                    <th>1</th> <th>2</th> <th>3</th><th>4</th> <th>5</th> <th>6</th> <th>7</th> <th>8</th> <th>9</th> <th>10</th>
                    <th>11</th> <th>12</th> <th>13</th><th>14</th> <th>15</th> <th>16</th> <th>17</th> <th>18</th> <th>19</th> <th>20</th>
                    <th>21</th> <th>22</th> <th>23</th><th>24</th> <th>25</th> <th>26</th> <th>27</th> <th>28</th> <th>29</th> <th>30</th> <th>31</th>            
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Nama</th>
                    <th>NRP</th>
                    <th>Jabatan</th>
                    <th>1</th> <th>2</th> <th>3</th><th>4</th> <th>5</th> <th>6</th> <th>7</th> <th>8</th> <th>9</th> <th>10</th>
                    <th>11</th> <th>12</th> <th>13</th><th>14</th> <th>15</th> <th>16</th> <th>17</th> <th>18</th> <th>19</th> <th>20</th>
                    <th>21</th> <th>22</th> <th>23</th><th>24</th> <th>25</th> <th>26</th> <th>27</th> <th>28</th> <th>29</th> <th>30</th> <th>31</th>            
                </tr>
            </tfoot>
            <tbody>
                <?php $no = 1; 
                    foreach ($isi as $row):
                ?>    
                    <tr>                
                        <td><?php echo $row->nama?></td>
                        <td><?php echo $row->nrp?></td>
                        <td><?php echo $row->hari_1 ?></td> <td><?php echo $row->hari_2 ?></td> <td><?php echo $row->hari_3 ?></td> <td><?php echo $row->hari_4 ?></td> <td><?php echo $row->hari_5 ?></td>
                        <td><?php echo $row->hari_6 ?></td> <td><?php echo $row->hari_7 ?></td> <td><?php echo $row->hari_8 ?></td> <td><?php echo $row->hari_9 ?></td> <td><?php echo $row->hari_10 ?></td>
                        <td><?php echo $row->hari_11 ?></td> <td><?php echo $row->hari_12 ?></td> <td><?php echo $row->hari_13 ?></td> <td><?php echo $row->hari_14 ?></td> <td><?php echo $row->hari_15 ?></td>
                        <td><?php echo $row->hari_16 ?></td> <td><?php echo $row->hari_17 ?></td> <td><?php echo $row->hari_18 ?></td> <td><?php echo $row->hari_19 ?></td> <td><?php echo $row->hari_20 ?></td>
                        <td><?php echo $row->hari_21 ?></td> <td><?php echo $row->hari_22 ?></td> <td><?php echo $row->hari_23 ?></td> <td><?php echo $row->hari_24?></td> <td><?php echo $row->hari_25 ?></td>
                        <td><?php echo $row->hari_26 ?></td> <td><?php echo $row->hari_27 ?></td> <td><?php echo $row->hari_28 ?></td> <td><?php echo $row->hari_29 ?></td> <td><?php echo $row->hari_30 ?></td>  <td><?php echo $row->hari_31 ?></td>
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
        
        // Load data for the table's content from an Ajax source
       /*  "ajax": {
          "url": "<?php echo site_url('welcome/ajax_list')?>",
          "type": "POST"
        }, */

       /*  //Set column definition initialisation properties.
        "columnDefs": [
        { 
          "targets": [ -1 ], //last column
          "orderable": false, //set not orderable
        },
        ], */

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
  <div class="modal fade" id="modal_form" role="dialog">
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
                    <select name="gender" class="form-control">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
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
              <div class="form-group">
                <label class="control-label col-md-3">Gender</label>
                <div class="col-md-9">
                  <select name="gender" class="form-control">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3">Address</label>
                <div class="col-md-9">
                  <textarea name="address" placeholder="Address"class="form-control"></textarea>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3">Date of Birth</label>
                <div class="col-md-9">
                  <input name="dob" placeholder="yyyy-mm-dd" class="form-control datepicker" type="text">
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  <!-- End Bootstrap modal -->
</body>
</html>