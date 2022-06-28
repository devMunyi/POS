<?php
session_start();
include_once 'db/connect_db.inc';

if ($_SESSION['username'] == "") {
  header('location:index');
} else {
  if ($_SESSION['role'] == "Admin") {
    include_once 'inc/header_all.php';
  } else {
    include_once 'inc/header_all_operator.php';
  }
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Main content -->
  <section class="content container-fluid">
    <div class="row">
      <!-- get alert stock -->
      <?php
      $select = $pdo->prepare("SELECT count(product_code) as total FROM tbl_product WHERE 0 + stock <= 0 + min_stock");
      $select->execute();
      $row = $select->fetch(PDO::FETCH_OBJ);
      $total1 = $row->total;
      ?>
      <!-- get alert notification -->
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-aqua"><i class="fa fa-archive"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">Product(s) Low</span>
            <?php if ($total1 == true) { ?>
              <span class="info-box-number"><small><?php echo $row->total; ?></small></span>
            <?php } else { ?>
              <span class="info-box-text"><strong>There is no any</strong></span>
            <?php } ?>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>


      <!-- get total products-->
      <?php
      $select = $pdo->prepare("SELECT count(product_code) as t FROM tbl_product");
      $select->execute();
      $row = $select->fetch(PDO::FETCH_OBJ);
      $total = $row->t;
      ?>

      <!-- get total products notification -->
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-aqua"><i class="fa fa-cubes"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">Total Products</span>
            <span class="info-box-number"><small><?php echo $row->t ?></small></span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>

      <!-- get today transactions -->
      <?php
      $select = $pdo->prepare("SELECT count(invoice_id) as i FROM tbl_invoice WHERE order_date = '$date'");
      $select->execute();
      $row = $select->fetch(PDO::FETCH_OBJ);
      $invoice = $row->i;
      ?>
      <!-- get today transactions notification -->
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-aqua"><i class="fa fa-shopping-cart"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">Today's <br> Transaction</span>
            <span class="info-box-number"><small><?php echo $row->i ?></small></span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>


      <!-- get today income -->
      <?php
      $select = $pdo->prepare("SELECT sum(total) as total FROM tbl_invoice WHERE order_date = '$date'");
      $select->execute();
      $row = $select->fetch(PDO::FETCH_OBJ);
      $total = $row->total;
      ?>
      <!-- get today income -->
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-aqua"><i class="fa fa-money"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">Today's Sales <br> Amount</span>
            <span class="info-box-number"><small>ksh. <?php echo number_format($total, 2); ?></small></span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>

    </div>

    <div class="col-md-offset-1 col-md-10">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Sale Summary In Last 30 days</h3>
        </div>
        <div class="box-body">
          <div class="">
            <div style="overflow-x:auto;">
              <table class="table table-striped" id="myBestProduct">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Product Code</th>
                    <th>Product Name</th>
                    <th>Sold Quantity</th>
                    <th>Sale Total</th>
                    <?php if ($_SESSION['role'] == "Admin") { ?>
                      <th>Sale Profit</th>
                    <?php
                    }
                    ?>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $no = 1;
                  $select = $pdo->prepare("SELECT product_code,product_name,price,product_unit,sum(qty) as q, sum(qty*price) as total, sum(item_profit) as item_profit FROM
                          tbl_invoice_detail WHERE DATEDIFF(order_date, \"$date\") <= 30 AND status =\"Paid\" GROUP BY product_id ORDER BY sum(qty) DESC LIMIT 0,30");
                  $select->execute();
                  while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                  ?>
                    <tr>
                      <td><?php echo $no++; ?></td>
                      <td><?php echo $row->product_code; ?></td>
                      <td><?php echo $row->product_name; ?></td>
                      <td><?php echo $row->q; ?>
                        <span><?php echo $row->product_unit; ?></span>
                      </td>
                      <td>ksh <?php echo number_format($row->total, 2); ?></td>
                      <?php if ($_SESSION['role'] == "Admin") { ?>
                        <td>ksh <?php echo number_format($row->item_profit, 2); ?></td>
                      <?php
                      }
                      ?>

                    </tr>

                  <?php
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
  $(document).ready(function() {
    $('#myBestProduct').DataTable();
  });
</script>


<?php
include_once 'inc/footer_all.php';
?>