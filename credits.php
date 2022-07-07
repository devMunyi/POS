<?php
    session_start();
    include_once ('db/connect_db.inc');
    include_once('php_functions/functions.php');
    if($_SESSION['username']==""){
        header('location:index.php');
    }else{
        if($_SESSION['role']=="Admin"){
          include_once 'inc/header_all.php';
        }else{
            include_once 'inc/header_all_operator.php';
        }
    }

    //error_reporting(0);

    if(isset($_GET['id'])){
        $id = $_GET['id'];

        $delete = $pdo->prepare("DELETE FROM tbl_credit_limit WHERE credit_id=$id");

        if($delete->execute()){
            echo'<script type="text/javascript">
                jQuery(function validation(){
                swal("Info", "Entry has Been Deleted", "info", {
                button: "Okay",
                    });
                });
                </script>';
        }
    }
    

?>
<html>
<head>
<meta http-equiv="refresh" content="60">
</head>
</html>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Credits List</h3>
            </div>
            <div class="box-body">
                <div style="overflow-x:auto;">
                    <table class="table table-striped" id="myProduct">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Limit Amount</th>
                                <th>Customer Phone</th>
                                <th>Customer Name</th>
                                <th>Date Created</th>
                                <th>Date Updated</th>
                                <th>Action</th>
                            </tr>

                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $select = $pdo->prepare("SELECT * FROM  tbl_credit_limit");
                            $select->execute();
                            while($row=$select->fetch(PDO::FETCH_OBJ)){
                            ?>
                                <tr>
                                <td><?php echo $no++ ;?></td>
                                <td>ksh. <?php echo number_format($row->credit_amount, 2);?></td>
                                <td><?php echo $row->cust_no; ?></td>
                                <td><?php echo $row->cust_name; ?></td>
                                <td><?php echo $row->date_created; ?></td>
                                <td><?php echo $row->date_updated; ?></td>
                                <td>
                                    <?php if($_SESSION['role']=="Admin"){ ?>
                                    <a href="credits?id=<?php echo $row->credit_id; ?>"
                                    class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                    <?php
                                    }
                                    ?>        
                                </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <script>
  $(document).ready( function () {
      $('#myProduct').DataTable();
  } );
  </script>

 <?php
    include_once 'inc/footer_all.php';
 ?>