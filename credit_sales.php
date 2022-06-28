<?php
    session_start();
    include_once 'db/connect_db.inc';
    include_once 'php_functions/functions.php';
    
    if($_SESSION['username']==""){
        header('location:index');
    }else{
        if($_SESSION['role']=="Admin"){
          include_once'inc/header_all.php';
        }else{
            include_once'inc/header_all_operator.php';
        }
    }

    //error_reporting(0);

    if(isset($_GET['id'])){
        $id = $_GET['id'];

        $delete_query = "DELETE tbl_invoice , tbl_invoice_detail FROM tbl_invoice INNER JOIN tbl_invoice_detail ON tbl_invoice.invoice_id =
        tbl_invoice_detail.invoice_id WHERE tbl_invoice.invoice_id=$id";
        $delete = $pdo->prepare($delete_query);
        if($delete->execute()){
            echo'<script type="text/javascript">
                jQuery(function validation(){
                swal("Info", "Transaction Has been deleted", "info", {
                button: "Okay",
                    });
                });
                </script>';
        }
    }
    
?>

<html>
<head>
<meta http-equiv="" content="">
</head>
</html>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Transaction
      </h1>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Credit Sales List</h3>
                <a href="create_credit_sale" class="btn btn-success btn-sm pull-right">Add Credit Sale</a>
            </div>
            <div class="box-body">
                <div style="overflow-x:auto;">
                    <table class="table table-striped" id="myOrder">
                        <thead>
                            <tr>
                                <th style="width:20px;">No</th>
                                <th style="width:100px;">Creditor Number</th>
                                <th style="width:100px;">Sale Date</th>
                                <th style="width:100px;">Sale Amount</th>
                                <th style="width:100px">Sale Type</th>
                                <th style="width:100px">Credit Balance</th>
                                <th style="width:100px">Status</th>
                                <th style="width: 100px">Due Date</th>
                                <th style="width:50px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $select = $pdo->prepare("SELECT * FROM tbl_invoice WHERE sale_type = 'Credit' ORDER BY invoice_id DESC");
                            $select->execute();
                            while($row=$select->fetch(PDO::FETCH_OBJ)){
                            ?>
                                <tr>
                                <td><?php echo $no++ ; ?></td>
                                <td class="text-uppercase"><?php echo $row->customer_no; ?></td>
                                <td><?php echo $row->order_date; ?></td>
                                <td>ksh. <?php echo number_format($row->total,2); ?></td>
                                <td><?php echo $row->sale_type ?></td>
                                <td>ksh. <?php echo number_format($row->credit_balance, 2); ?></td>
                                <td><?php echo $row->status; ?></td>
                                <td><?php echo fancydate($row->due_date); ?></td>
                                <td>
                                    <?php if($_SESSION['role']=="Admin"){ ?>
                                    <a href="credit_sales?id=<?php echo $row->invoice_id; ?>" onclick="return confirm('Delete Transaction ?')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                    <?php } ?>
                                    <a href="misc/nota?id=<?php echo $row->invoice_id; ?>" target="_blank" class="btn btn-info btn-sm"><i class="fa fa-print"></i></a>
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
      $('#myOrder').DataTable();
  } );
  </script>

 <?php
    include_once'inc/footer_all.php';
 ?>