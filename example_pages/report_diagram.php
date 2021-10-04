<?php
     include_once'db/connect_db.php';
     session_start();
     if($_SESSION['username']==""){
         header('location:index.php');
     }else{
         if($_SESSION['role']=="Admin"){
           include_once'inc/header_all.php';
         }else{
             include_once'inc/header_all_operator.php';
         }
     }
     error_reporting(0);
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Grafik Penjualan
      </h1>
      <hr>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box box-success">
            <form action="" method="POST">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <div class="box-header with-border">
                            <h3 class="box-title">Dari Tanggal : <?php echo $_POST['date_1']?> --
                            Sampai Tanggal : <?php echo $_POST['date_2'] ?></h3>
                        </div>
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
                            <input type="submit" name="date_filter" value="Lihat" class="btn btn-success">
                        </div>
                        <br>
                    </div>

                    <?php
                        $select = $pdo->prepare("SELECT order_date, sum(total) as price FROM tbl_invoice WHERE order_date BETWEEN :fromdate AND :todate
                        GROUP BY order_date");
                        $select->bindParam(':fromdate', $_POST['date_1']);
                        $select->bindParam(':todate', $_POST['date_2']);
                        $select->execute();
                        $total=[];
                        $date=[];
                        while($row=$select->fetch(PDO::FETCH_ASSOC)){
                            extract($row);
                            $total[]=$price;
                            $date[]=$order_date;

                        }
                        // echo json_encode($total);
                    ?>
                    <div class="chart">
                        <canvas id="myChart" style="height:250px;">

                        </canvas>
                    </div>

                    <?php
                        $select = $pdo->prepare("SELECT product_name, sum(qty) as q FROM tbl_invoice_detail WHERE order_date BETWEEN :fromdate AND :todate
                        GROUP BY product_id");
                        $select->bindParam(':fromdate', $_POST['date_1']);
                        $select->bindParam(':todate', $_POST['date_2']);
                        $select->execute();
                        $pname=[];
                        $qty=[];
                        while($row=$select->fetch(PDO::FETCH_ASSOC)){
                            extract($row);
                            $pname[]=$product_name;
                            $qty[]=$q;

                        }
                        // echo json_encode($total);
                    ?>
                    <div class="chart">
                        <canvas id="myBestSellItem" style="height:250px;">
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
    var ctx = document.getElementById('myChart');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($date); ?>,
            datasets: [{
                label: 'Total Pendapatan',
                data: <?php echo json_encode($total); ?>,
                backgroundColor: 'rgb(13, 192, 58)',
                borderColor: 'rgb(32, 204, 75)',
                borderWidth: 1
            }]
        },
        options: {}
    });
</script>

<style>
    .color{
        backgroundColor: rgb(120,102,102);
    }
</style>


<script>
    var ctx = document.getElementById('myBestSellItem');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($pname); ?>,
            datasets: [{
                label: 'Total Produk Terjual',
                data: <?php echo json_encode($qty); ?>,
                backgroundColor: 'rgb(120,112,175)',
                borderColor: 'rgb(255,255,255)',
                borderWidth: 1
            }]
        },
        options: {}
    });
</script>


<script>
$('#datepicker_1').datepicker({
      autoclose: true
    });
    //Date picker
    $('#datepicker_2').datepicker({
      autoclose: true
    });
</script>

 <?php
    include_once'inc/footer_all.php';
 ?>