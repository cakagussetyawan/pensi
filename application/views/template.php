<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>





<!DOCTYPE html>


<html>
<head>
  <style type="text/css">

  .un {text-decoration: none; }


  </style>


  <script src="<?php echo base_url();?>assets/js/jquery-1.11.2.min.js"></script> 

  <!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 2.1.3 -->
<script src="<?php echo base_url(); ?>assets/js/jQuery-2.1.3.min.js"></script>
<!-- Bootstrap 3.3.2 JS -->
<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js" type="text/javascript"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url(); ?>assets/js/app.min.js" type="text/javascript"></script>


  <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">

  <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/AdminLTE.min.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/skin-red.min.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/bootstrap.min.css" />

  <meta charset="UTF-8">
  <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
  <!-- Bootstrap 3.3.2 -->
 
  <!-- Font Awesome Icons -->
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
  <!-- Ionicons -->
  <link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" />
  
</head>

<body class="skin-red">
  <div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

      
      <!-- Logo -->
      <a href="" style="text-decoration:none"class="un logo">P E N S I</a>

      <!-- Header Navbar -->
      <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">


            <!-- User Account Menu -->
            <li class="">
              <!-- Menu Toggle Button -->
              <a href="<?php echo site_url('login/logout') ?>"> <i class="fa fa-sign-out"></i>Log out </a>              
            </li>
          </ul>
        </div>
      </nav>
    </header>

    <?php $this->load->view('navigation_bar');?>      

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->

      <!-- <section class="content-header">
        <h4> PT PDS <span><small>PENGECEKAN DATA PRESENSI</small></span></h4>
      </section> -->



        <section class="content">
        <div class="row">
            <div class="col-xs-12">                        
                <?php $this->load->view($content); ?>                  
            </div><!-- /.col -->
        </div><!-- /.row -->
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">

    </div>
    <!-- Default to the left --> 
    <strong>Copyright &copy; <?= date('Y') ?> <a href="#">PT.PDS</a>.</strong> All rights reserved.
  </footer>

</div><!-- ./wrapper -->





</body>
</html>