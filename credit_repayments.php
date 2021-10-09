<?php
    include_once('db/connect_db.php');
    include_once('php_functions/functions.php');

    session_start();
    if($_SESSION['username']==""){
        header('location:index');
    }else{
        if($_SESSION['role']=="Admin"){
          include_once'inc/header_all.php';
        }else{
            include_once ('inc/header_all_operator.php');
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
                button: "Continue",
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
                <h3 class="box-title">Credit Repayment List</h3>
                <a href="record_credit_repayment.php" class="btn btn-success btn-sm pull-right">Add Repayment</a>
            </div>
            <div class="box-body">
                <div style="overflow-x:auto;">
                    <table class="table table-striped" id="myOrder">
                        <thead>
                            <tr>
                                <th style="width: 20px;">No</th>
                                <th style="width: 100px;">Creditor Number</th>
                                <th style="width: 100px;">Amount</th>
                                <th style="width: 100px;">Paid Date</th>
                                <th style="width: 100px;">Credit Balance</th>
                                <th style="width: 100px;">Due Date</th>
                                <th style="width: 100px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $select = $pdo->prepare("SELECT * FROM tbl_repayments ORDER BY id");
                            $select->execute();
                            while($row=$select->fetch(PDO::FETCH_OBJ)){
                            ?>
                                <tr>
                                <td><?php echo $no++ ; ?></td>
                                <td class="text-uppercase"><?php echo $row->creditor_no; ?></td>
                                <td>ksh. <?php echo number_format($row->amount_paid); ?></td>
                                <td><?php echo fancydate($row->date_paid); ?></td>
                                <td>ksh. <?php echo number_format($row->credit_balance); ?></td>
                                <td><?php echo fancydate($row->due_date); ?></td>
                                <td>
                                    <?php if($_SESSION['role']=="Admin"){ ?>
                                    <a href="credit_repayments?id=<?php echo $row->invoice_id; ?>" onclick="return confirm('Delete Transaction ?')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                    <?php } ?>
                                    
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