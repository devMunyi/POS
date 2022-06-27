<?php
session_start();
include_once 'db/connect_db.inc';
include_once 'php_functions/functions.php';
if ($_SESSION['username'] == "") {
  header('location:index');
} else {
  if ($_SESSION['role'] == "Admin") {
    include_once 'inc/header_all.php';
  } else {
    include_once 'inc/header_all_operator.php';
  }
}
//error_reporting(0);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Main content -->
  <section class="content container-fluid">
    <div class="box box-success">
      <form action="" method="POST" autocomplete="off">
        <div class="box-header with-border">
          <h3 class="box-title">From Date : <?php echo $_POST['date_1'] ?>
          </h3>
          <h3 class="box-title">Till Date : <?php echo $_POST['date_2'] ?>
          </h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-5">
              <div class="form-group">
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right" id="datepicker_1" name="date_1" data-date-format="yyyy-mm-dd">
                </div>
              </div>
            </div>
            <div class="col-md-5">
              <div class="form-group">
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right" id="datepicker_2" name="date_2" data-date-format="yyyy-mm-dd">
                </div>
              </div>
            </div>
            <div class="col-md-2">
              <input type="submit" name="date_filter" value="Run Search" class="btn btn-success btn-sm">
            </div>
            <br>
          </div>
          <?php
          $select = $pdo->prepare("SELECT sum(total) as total, sum(sale_profit) as profit, count(invoice_id) as invoice FROM tbl_invoice
                    WHERE order_date BETWEEN :fromdate AND :todate");
          $select->bindParam(':fromdate', $_POST['date_1']);
          $select->bindParam(':todate', $_POST['date_2']);
          $select->execute();

          $row = $select->fetch(PDO::FETCH_OBJ);

          $total = $row->total;
          $profit = $row->profit;
          $invoice = $row->invoice;


          ?>

          <div class="row">
            <div class="col-md-offset-1 col-md-3">
              <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-shopping-cart"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Total Transaction</span>
                  <span class="info-box-number"><?php echo $invoice; ?></span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->

            <div class="col-md-3">
              <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Total Sales</span>
                  <span class="info-box-number">ksh.<?php echo number_format($total, 2); ?></span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->

            <div class="col-md-3">
              <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Total Profit</span>
                  <span class="info-box-number">ksh.<?php echo number_format($profit, 2); ?></span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
          </div>

          <!--- Transaction Table -->
          <br><br>
          <div style="overflow-x:auto;">
            <table class="table table-striped" id="mySalesReport">
              <thead>
                <tr>
                  <th>Cashier</th>
                  <th>Sale Amount</th>
                  <th>Sale Profit</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $select = $pdo->prepare("SELECT cashier_name, sum(total) AS individual_s_total, sum(sale_profit) AS individual_s_profit FROM tbl_invoice WHERE order_date BETWEEN :fromdate AND :todate GROUP BY cashier_name");
                $select->bindParam(':fromdate', $_POST['date_1']);
                $select->bindParam(':todate', $_POST['date_2']);

                $select->execute();
                $p_total = 0;
                $s_total = 0;
                while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                  $p_total = ($p_total + $row->individual_s_profit);
                  $s_total = ($s_total + $row->individual_s_total);
                ?>
                  <tr>
                    <td class="text-uppercase"><?php echo $row->cashier_name; ?></td>
                    <td>ksh. <?php echo number_format($row->individual_s_total, 2); ?></td>
                    <td>ksh. <?php echo number_format($row->individual_s_profit, 2); ?></td>
                  </tr>
                <?php
                }
                if ($p_total || $s_total > 0) {
                  echo "<tr class ='bg-blue'>
                              <td colspan= '1'>Totals</td>
                              <td>ksh. " . number_format($s_total, 2) . " </td>
                              <td>ksh. " . number_format($p_total, 2) . " </td>
                              </tr>";
                }
                ?>
              </tbody>
            </table>
          </div>
          <br><br>
          <div style="overflow-x:auto;">
            <table class="table table-striped" id="myStockReport">
              <thead>
                <tr>
                  <th>Product ID</th>
                  <th>Product Code</th>
                  <th>Product Name</th>
                  <th>Product Category</th>
                  <th>Selling Price</th>
                  <th>Net Stock</th>
                  <th>Net Stock Value</th>
                </tr>
              </thead>
              <tbody>
                <?php

                $prod = fetchtable('tbl_product', "product_id > 0", "stock", "ASC", "0,100", "product_id, product_code ,product_name, product_category,sell_price, stock");
                $prod_net_stock_total = 0;
                $prod_net_val_total = 0;
                while ($p = mysqli_fetch_array($prod)) {
                  $p_id = $p['product_id'];
                  $p_code = $p['product_code'];
                  $p_name = $p['product_name'];
                  $p_cat = $p['product_category'];
                  $p_sellprice = $p['sell_price'];
                  $p_stock = $p['stock'];
                  
                  
                  $p_net_value_ = $p_sellprice * $p_stock;
                  $p_net_value = "ksh. " . number_format($p_net_value_, 2);
                  $prod_net_stock_total += $p_stock;
                  $prod_net_val_total += $p_net_value_;
                ?>
                  <tr>
                    <td><?php echo $p_id; ?></td>
                    <td><?php echo $p_code; ?></td>
                    <td><?php echo $p_name; ?></td>
                    <td><?php echo $p_cat; ?></td>
                    <td><?php echo $p_sellprice; ?></td>
                    <td><?php echo $p_stock; ?></td>
                    <td><?php echo $p_net_value; ?></td>
                  </tr>
                <?php
                }

                if ($prod_net_stock_total > 0 || $prod_net_val_total > 0) {
                  echo "<tr class ='bg-blue'>
                              <td colspan= '5'>Totals</td>
                              <td>".number_format($prod_net_stock_total, 2) . " </td>
                              <td>ksh. " . number_format($prod_net_val_total, 2) . " </td>
                              </tr>";
                }

                ?>

              </tbody>
            </table>
          </div>
          <br><br>
          <!-- Transaction Graphic -->
          <?php
          $select = $pdo->prepare("SELECT order_date, sum(total) as sales_total FROM tbl_invoice WHERE order_date BETWEEN :fromdate AND :todate
                  GROUP BY order_date");
          $select->bindParam(':fromdate', $_POST['date_1']);
          $select->bindParam(':todate', $_POST['date_2']);
          $select->execute();
          $total = [];
          $date = [];
          while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $total[] = $sales_total;
            $date[] = $order_date;
          }
          // echo json_encode($total);
          ?>
          <div class="chart">
            <canvas id="myChart" style="height:250px;">

            </canvas>
          </div>
          <?php

          $select = $pdo->prepare("SELECT order_date, sum(sale_profit) as sales_profit FROM tbl_invoice WHERE order_date BETWEEN :fromdate AND :todate
                  GROUP BY order_date");
          $select->bindParam(':fromdate', $_POST['date_1']);
          $select->bindParam(':todate', $_POST['date_2']);
          $select->execute();
          $prtotal = [];
          $prdate = [];
          while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $prtotal[] = $sales_profit;
            $prdate[] = $order_date;
          }
          // echo json_encode($total);
          ?>
          <div class="chart">
            <canvas id="myProfit" style="height:250px;">

            </canvas>
          </div>

          <?php
          $select = $pdo->prepare("SELECT product_name, sum(qty) as q FROM tbl_invoice_detail WHERE order_date BETWEEN :fromdate AND :todate
                  GROUP BY product_id");
          $select->bindParam(':fromdate', $_POST['date_1']);
          $select->bindParam(':todate', $_POST['date_2']);
          $select->execute();
          $pname = [];
          $qty = [];
          while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $pname[] = $product_name;
            $qty[] = $q;
          }
          // echo json_encode($total);
          ?>
          <div class="chart">
            <canvas id="myBestSellItem" style="height:250px;">
            </canvas>
          </div>

        </div>

        <?php
        $select = $pdo->prepare("SELECT product_name, sum(item_profit) as p FROM tbl_invoice_detail WHERE order_date BETWEEN :fromdate AND :todate
                  GROUP BY product_id");
        $select->bindParam(':fromdate', $_POST['date_1']);
        $select->bindParam(':todate', $_POST['date_2']);
        $select->execute();
        $prodName = [];
        $prodProf = [];
        while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
          extract($row);
          $prodName[] = $product_name;
          $prodProf[] = $p;
        }
        // echo json_encode($total);
        ?>
        <div class="chart">
          <canvas id="prodProfit" style="height:250px;">
          </canvas>
        </div>

    </div>

    </form>
</div>


</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
  //Date picker
  $('#datepicker_1').datepicker({
    autoclose: true
  });
  //Date picker
  $('#datepicker_2').datepicker({
    autoclose: true
  });

  $(document).ready(function() {
    $('#mySalesReport').DataTable();
  });

  $(document).ready(function() {
    $('#myStockReport').DataTable();
  });
</script>
<script src="dist/js/chart.js"></script>
<script>
  var ctx = document.getElementById('myChart');
  var myChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: <?php echo json_encode($date); ?>,
      datasets: [{
        label: 'Sales Total',
        data: <?php echo json_encode($total); ?>,
        backgroundColor: '',
        borderColor: 'green',
        borderWidth: 2,
        fill: true
      }]
    },
    options: {
      legend: {
        display: true
      }
    }
  });
</script>

<script>
  var ctx = document.getElementById('myProfit');
  var myProfit = new Chart(ctx, {
    type: 'line',
    data: {
      labels: <?php echo json_encode($prdate); ?>,
      datasets: [{
        label: 'Sales Profit',
        data: <?php echo json_encode($prtotal); ?>,
        backgroundColor: '',
        borderColor: 'chartreuse',
        borderWidth: 2,
        fill: true
      }]
    },
    options: {
      legend: {
        display: true
      }
    }
  });
</script>

<style>
  .color {
    backgroundColor: rgb(120, 102, 102);
  }
</style>
<script>
  var ctx = document.getElementById('myBestSellItem');
  var myChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: <?php echo json_encode($pname); ?>,
      datasets: [{
        label: 'Total Products Sold',
        data: <?php echo json_encode($qty); ?>,
        backgroundColor: '',
        borderColor: 'aquamarine',
        borderWidth: 2,
        fill: true
      }]
    },
    options: {
      legend: {
        display: true
      }
    }
  });
</script>
<script>
  var ctx = document.getElementById('prodProfit');
  var prodP = new Chart(ctx, {
    type: 'line',
    data: {
      labels: <?php echo json_encode($prodName); ?>,
      datasets: [{
        label: 'Product Profit',
        data: <?php echo json_encode($prodProf); ?>,
        backgroundColor: '',
        borderColor: 'aqua',
        borderWidth: 2,
        fill: true
      }]
    },
    options: {
      legend: {
        display: true
      }
    }
  });
</script>

<?php
include_once 'inc/footer_all.php';
?>