<?php
    include_once'connect_db.php';
    session_start();
    if($_SESSION['role']!=="Operator"){
        header('location:index.php');
    }

    include_once'header-operator.php';
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Operator Dashboard
      </h1>
      <hr class="text-mute">
      <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol> -->
    </section>

    <!-- Main content -->
    <section class="content container-fluid">



    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

 <?php
    include_once'footer.php';
 ?>