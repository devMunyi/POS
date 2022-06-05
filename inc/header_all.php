<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title>IPOS | Dashboard</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="shortcut icon" href="img/logo1.jpg">
  <!--Sweetalert Plugin --->
  <script src="bower_components/sweetalert/sweetalert.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>
  <!-- jQuery 3 -->
  <script src="bower_components/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap 3.3.7 -->
  <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- Bootstrap -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- bootstrap timepicker -->
  <link rel="stylesheet" href="plugins/timepicker/bootstrap-timepicker.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">

  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="dist/css/skins/skin-green.min.css">
  <link rel="stylesheet" href="dist/css/skins/skin-green-light.min.css">
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="dist/css/skins/skin-blue.min.css">
  <link rel="stylesheet" href="dist/css/skins/skin-blue-light.min.css">
  <!--Trial-->
   <!--Bootsrap style sheet-->
   <link rel="stylesheet" href="bower_components2/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <!-- <link rel="stylesheet" href="bower_components2/font-awesome/css/font-awesome.min.css"> -->
  <!-- Ionicons -->
  <!-- <link rel="stylesheet" href="bower_components2/Ionicons/css/ionicons.min.css"> -->
  <!-- Morris chart -->
  <!-- <link rel="stylesheet" href="bower_components2/morris.js/morris.css"> -->
  <!-- jvectormap -->
  <!-- <link rel="stylesheet" href="bower_components2/jvectormap/jquery-jvectormap.css"> -->
  <!--End of trial-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <!-- DataTables -->
  <link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <!-- datepicker js -->
  <script src="bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
  <!-- bootstrap datepicker -->
  <script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="plugins/iCheck/all.css">
  <!-- iCheck 1.0.1 -->
  <script src="plugins/iCheck/icheck.min.js"></script>
  <!-- bootstrap time picker -->
  <script src="plugins/timepicker/bootstrap-timepicker.min.js"></script>

  <!-- chart Js -->
  <script src="chartjs/dist/Chart.min.js"></script>
</head>

<body class="hold-transition skin-green sidebar-mini">
  <div class="wrapper">
    <!-- Main Header -->
    <header class="main-header">
      <!-- Logo -->

      <a href="dashboard" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>I</b>POS</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Inventory|</b>POS</span>
      </a>

      <!-- Header Navbar -->
      <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <!-- User Account Menu -->
            <li class="dropdown user user-menu">
              <!-- Menu Toggle Button -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <!-- The user image in the navbar-->
                <img src="img/logo3.png" class="user-image" alt="User Image">
                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                <span class="hidden-xs text-lowercase"><?php echo $_SESSION['username']; ?></span>
              </a>
              <ul class="dropdown-menu">
                <!-- The user image in the menu -->
                <li class="user-header">
                  <img src="img/logo3.png" class="img-circle" alt="User Image">

                  <p class="text-lowercase">
                    <?php echo $_SESSION['username']; ?> - <?php echo $_SESSION['role']; ?>
                    <small class="text-capitalize"><?php echo $_SESSION['fullname']; ?></small>
                  </p>
                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-left">
                    <a href="profile" class="btn btn-default btn-flat">Profile</a>
                  </div>
                  <div class="pull-right">
                    <a href="misc/logout" class="btn btn-default btn-flat" onclick="return confirm('Are you sure ?')" class="btn btn-danger">Sign out</a>
                  </div>
                </li>
              </ul>
            </li>
            <!-- Control Sidebar Toggle Button -->
          </ul>
        </div>
      </nav>
    </header>

    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">
          <li style="margin-left: 3px;" class="header font-20">NAVIGATION</li>
          <li><a href="./dashboard"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
          <li><a href="./category"><i class="fa fa-list-alt"></i> <span>Category</span></a></li>
          <li><a href="./unit"><i class="fa fa-balance-scale"></i> <span>Units</span></a></li>
          <li><a href="./register"><i class="fa fa-users"></i> <span>Manage Users</span></a></li>
          <!-- <li><a href="./credits"><i class="fa fa-users"></i> <span>Credits</span></a></li> -->
          <li><a href="./product"><i class="fa fa-archive"></i> <span>Products</span></a></li>

          <li><a href="./cash_sales"><i class="fa fa-shopping-cart"></i> <span>Cash Transactions</span></a></li>
          <!-- <li class="treeview">
                <a href="#">
                    <i class="fa fa-shopping-cart"></i>
                    <span>Cash Transactions</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="cash_sales"class=""><i class="fa fa-circle-o"></i> All Cash Sales</a></li>
                </ul>
          </li> -->
          <li class="treeview">
            <a href="#">
              <i class="fa fa-shopping-cart"></i>
              <span>Credit Transactions</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="credit_sales" class=""><i class="fa fa-circle-o"></i> All Credit Sales</a></li>
              <li><a href="credits" class=""><i class="fa fa-circle-o"></i> Creditors Limits</a></li>
              <li><a href="credit_repayments" class=""><i class="fa fa-circle-o"></i> Credit Repayments</a></li>
              <li><a href="credit_defaulters" class=""><i class="fa fa-circle-o"></i> Credit Defaulters</a></li>
            </ul>
          </li>
          <li><a href="./sale"><i class="fa fa-shopping-cart"></i> <span>All Transactions</span></a></li>
          <!-- <li class="treeview">
                <a href="#">
                    <i class="fa fa-shopping-cart"></i>
                    <span>All Transactions</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="sale"class=""><i class="fa fa-circle-o"></i> All Sales</a></li>
                </ul>
          </li> -->
          <li><a href="report_sales"><i class="fa fa-newspaper-o"></i> <span>Reports</span></a></li>
        </ul>
        <!-- /.sidebar-menu -->
      </section>
      <!-- /.sidebar -->
    </aside>