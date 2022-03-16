<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">

    <!-- Sidebar user panel (optional) -->
    <div class="user-panel">
      <div class="pull-left image">
        <img src="<?=base_url().'uploads/nophoto.jpg';?>" class="img-circle" alt="User Image" />
      </div>
      <div class="pull-left info">
        <p><?= $_SESSION['nama'] ?></p>
        <!-- Status -->
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div>


    <!-- Sidebar Menu -->
    <ul class="sidebar-menu">
      <li class="header">MAIN NAVIGATION</li>

      <li><a href=""><i class="fa fa-dashboard"></i>Home</a></li>
  
      <!-- <li class="treeview"><a href="#" style="text-decoration:none"><i class="fa fa-users"></i><span>Presensi</span> <i class="fa fa-angle-left pull-right"></i> </a>
        <ul class="treeview-menu">
          <li>
            <a class="ayam" href="<?= base_url()?>presensi/jamkehadiran">Jam Kehadiran</a> 
          </li>
          <li>
            <a class="ayam" href="<?= base_url()?>presensi/presensikoreksi">Data Kehadiran</a>           
          </li>
          <li>          
          <a class="ayam" href="<?= base_url()?>presensi/rekapkehadiran">Rekap Kehadiran</a>                     
          </li>
        </ul>
      </li> -->

      <li><a href="<?=site_url('Absensi') ?>"><i class="fa fa-calendar-o"></i>Data Kehadiran</a></li>
      <li><a href="<?=site_url('Jamkehadiran') ?>"><i class="fa fa-clock-o"></i>Data Jam Kehadiran</a></li>
      <li><a href="<?=site_url('Rekapkehadiran') ?>"><i class="fa fa-book"></i>Data Rekap Kehadiran</a></li>
     

    </ul>
  </section>
  <!-- /.sidebar -->
</aside>

<script type="text/javascript">


  $(document).on('click','.ayam',function(){

   var href = $(this).attr('href');
   $('#haha').empty().load(href);
   //.fadeIn('slow');
   return false;

 });


</script>






<script type="text/javascript">

  $('.apam').removeClass('active');

</script>


<script>


  $(document).ready(function(){

    $( "body" ).on( "click", ".ayam", function() {

      $('.ayam').each(function(a){
       $( this ).removeClass('selectedclass')
     });
      $( this ).addClass('selectedclass');
    });

  })


</script>




<style type="text/css">


  li a.selectedclass
  {
    color: white !important;
    font-weight: bold;
  }

</style>