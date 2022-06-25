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
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Product
    </h1>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs nav-justified font-16">
            <li class="nav-item nav-100 active"><a href="#tab_1" data-toggle="tab" aria-expanded="false"><i class="fa fa-info"></i> Product Info</a></li>
            <li class="nav-item nav-100"><a href="#tab_2" data-toggle="tab" aria-expanded="false"><i class="fa fa-clock-o"></i> Events</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
              <div class="row">
                <?php
                $id = $_GET['id'];
                $select = $pdo->prepare("SELECT * FROM tbl_product WHERE product_id=$id");
                $select->execute();
                while ($row = $select->fetch(PDO::FETCH_OBJ)) { ?>
                  <div class="col-md-6">
                    <ul class="list-group">
                      <center>
                        <p class="list-group-item list-group-item-success">Product Deatails</p>
                      </center>
                      <li class="list-group-item"> <b>Product Code</b> :<span class="label badge pull-right"><?php echo $row->product_code; ?></span></li>
                      <li class="list-group-item"><b>Product Name</b> :<span class="label label-info pull-right"><?php echo $row->product_name; ?></span></li>
                      <li class="list-group-item"><b>Product Category</b> :<span class="label label-primary pull-right"><?php echo $row->product_category; ?></span></li>
                      <li class="list-group-item"><b>Original Price</b> :<span class="label label-warning pull-right">ksh. <?php echo number_format($row->purchase_price); ?></span></li>
                      <li class="list-group-item"><b>Selling Price</b> :<span class="label label-warning pull-right">ksh. <?php echo $row->sell_price; ?></span></li>
                      <li class="list-group-item"><b>Profit</b> :<span class="label label-success pull-right">ksh. <?php echo number_format(($row->sell_price - $row->purchase_price)); ?></span></li>
                      <li class="list-group-item"><b>Stock </b> :<span class="label label-default pull-right"><?php echo $row->stock; ?></span></li>
                      <li class="list-group-item"><b>Minimimum Stock </b> :<span class="label label-default pull-right"><?php echo $row->min_stock; ?></span></li>
                      <li class="list-group-item"><b>Unit</b> :<span class="label label-default pull-right"><?php echo $row->product_unit; ?></span></li>
                      <li class="list-group-item"><b>Product Description</b> :</li>
                      <li class="list-group-item col-md-12"><span class="text-muted"><?php echo $row->description ?></span></li>
                    </ul>
                  </div>
                  <div class="col-md-6">
                    <ul class="list-group">
                      <center>
                        <p class="list-group-item list-group-item-success">Product Image</p>
                      </center>
                      <img style="margin-left: 110px; padding-top: 5px; width: 400px; height: 400px" src="upload/<?php echo $row->img ?>" alt="Product Image" class="img-responsive" width: 250px>
                    </ul>
                  </div>
                <?php
                }
                ?>
                <div class="">
                  <a href="product" class="btn btn-warning">Return</a>
                </div>
              </div>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="tab_2">
              <div class="row">
                <div class="col-md-2">
                  <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
                </div>
                <div class="col-md-10">
                  <table class="table-bordered font-14 table table-hover">
                    <thead>
                      <tr>
                        <th>Event</th>
                        <th>Date</th>
                        <th>Net Stock</th>
                        <th>Stock Value</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $events_ = fetchtable('tbl_events', "tbl='tbl_product' AND fld=$id", "id", "desc", "0,100", "id, event_details ,event_date, stock, stock_value");
                      if (mysqli_num_rows($events_) > 0) {
                        while ($k = mysqli_fetch_array($events_)) {
                          $id = $k['id'];
                          $event_details = $k['event_details'];
                          $stock = $k['stock'];
                          $stock_value_ = $k['stock_value'];
                          $stock_value = "ksh. ".$stock_value_;
                          $event_date_ = $k['event_date'];
                          $event_date = fancydate($event_date_);
                          $htmlId = "tbl_product-".$id;

                          echo "<tr><td>$event_details</td><td id=\"$htmlId\">$event_date</td><td>$stock</td><td>$stock_value</td></tr>";
                        }
                      } else {
                        echo "<tr><td colspan='4'> <i>No Records Found</i></td> </tr>";
                      }

                      ?>
                    </tbody>
                  </table>
                </div>


              </div>
            </div>
            <!-- /.tab-pane -->
            <!-- /.tab-pane -->
          </div>
          <!-- /.tab-content -->
        </div>
      </div>
    </div>
  </section>

  <!-- Main content -->
  <!-- <section class="content container-fluid">
    <div class="box box-success">
      <div class="box-body">
        <?php
        $id = $_GET['id'];

        $select = $pdo->prepare("SELECT * FROM tbl_product WHERE product_id=$id");
        $select->execute();
        while ($row = $select->fetch(PDO::FETCH_OBJ)) { ?>

          <div class="col-md-6">
            <ul class="list-group">

              <center>
                <p class="list-group-item list-group-item-success">Product Deatails</p>
              </center>
              <li class="list-group-item"> <b>Product Code</b> :<span class="label badge pull-right"><?php echo $row->product_code; ?></span></li>
              <li class="list-group-item"><b>Product Name</b> :<span class="label label-info pull-right"><?php echo $row->product_name; ?></span></li>
              <li class="list-group-item"><b>Product Category</b> :<span class="label label-primary pull-right"><?php echo $row->product_category; ?></span></li>
              <li class="list-group-item"><b>Original Price</b> :<span class="label label-warning pull-right">ksh. <?php echo number_format($row->purchase_price); ?></span></li>
              <li class="list-group-item"><b>Selling Price</b> :<span class="label label-warning pull-right">ksh. <?php echo $row->sell_price; ?></span></li>
              <li class="list-group-item"><b>Profit</b> :<span class="label label-success pull-right">ksh. <?php echo number_format(($row->sell_price - $row->purchase_price)); ?></span></li>
              <li class="list-group-item"><b>Stock </b> :<span class="label label-default pull-right"><?php echo $row->stock; ?></span></li>
              <li class="list-group-item"><b>Minimimum Stock </b> :<span class="label label-default pull-right"><?php echo $row->min_stock; ?></span></li>
              <li class="list-group-item"><b>Unit</b> :<span class="label label-default pull-right"><?php echo $row->product_unit; ?></span></li>
              <li class="list-group-item"><b>Product Description</b> :</li>
              <li class="list-group-item col-md-12"><span class="text-muted"><?php echo $row->description ?></span></li>
            </ul>
          </div>
          <div class="col-md-6">
            <ul class="list-group">
              <center>
                <p class="list-group-item list-group-item-success">Product Image</p>
              </center>
              <img style="margin-left: 110px; padding-top: 5px; width: 400px; height: 400px" src="upload/<?php echo $row->img ?>" alt="Product Image" class="img-responsive" width: 250px>
            </ul>
          </div>
        <?php
        }
        ?>
      </div>
      <div class="box-footer">
        <a href="product" class="btn btn-warning">Return</a>
      </div>

    </div>
  </section> -->
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php
include_once 'inc/footer_all.php';
?>