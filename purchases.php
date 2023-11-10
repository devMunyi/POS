<?php
session_start();
include_once ('db/connect_db.inc');
include_once ('php_functions/functions.php');
include_once ('php_functions/functions_v2.php');

if ($_SESSION['username'] == "") {
    header('location:index.php');
} else {
    if ($_SESSION['role'] == "Admin") {
        include_once 'inc/header_all.php';
    } else {
        include_once 'inc/header_all_operator.php';
    }
}

$purchase_statuses = [
    0 => ['name' => 'Deleted', 'color_code' => '#ed143d', 'status' => 1],
    1 => ['name' => 'Pending', 'color_code' => '#ff8c00', 'status' => 1],
    2 => ['name' => 'Approved', 'color_code' => '#1e90ff', 'status' => 1]
];

//error_reporting(0);

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $delete = $pdo->prepare("DELETE FROM tbl_purchases WHERE product_id=" . $id);

    if ($delete->execute()) {

        $user_id = $_SESSION['user_id'] ?? 0;
        $username = $_SESSION['username'] ?? '';
        $event_details = "Product was deleted by $username, user_id => $user_id";

        $fds = array('tbl', 'fld', 'event_details', 'event_date', 'event_by', 'status');
        $vals = array('tbl_purchases', $id, "$event_details", "$fulldate", $user_id, 1);
        addtodb_v2('o_events', $fds, $vals);


        ///---Begin updating tbl_stock_record table by current date
        $prod = fetchtable('tbl_purchases', "product_id > 0", "product_id", "ASC", "0,500", "sell_price, stock");
        $prod_net_stock_total = 0;
        $prod_net_val_total = 0;
        while ($p = mysqli_fetch_array($prod)) {
            $p_sellprice = $p['sell_price'];
            $p_stock = $p['stock'];
            $p_net_value_ = $p_sellprice * $p_stock;
            $p_net_value = "ksh. " . number_format($p_net_value_, 2);
            $prod_net_stock_total += $p_stock;
            $prod_net_val_total += $p_net_value_;
            $added_date = $date;
        }

        $select = $pdo->prepare("SELECT COUNT(id) AS records, stock_date FROM tbl_stock_record WHERE stock_date = '$date'");
        $select->execute();
        $row = $select->fetch(PDO::FETCH_OBJ);
        $stock_total_ = $row->records;
        $stock_date = $row->stock_date;

        if ($stock_total_ == 1) {
            // updatedb("tbl_stock_record", "net_stock=$prod_net_stock_total, stock_value=$prod_net_val_total", "stock_date = '$date'");
        } else {
            // $fds = array('net_stock', 'stock_value', 'stock_date');
            // $vals = array($prod_net_stock_total, $prod_net_val_total, "$added_date");
            // addtodb_v2()
            // addtodb("tbl_stock_record", $fds, $vals);
        }
        ///---End updating tbl_stock_record table by current date

        echo '<script type="text/javascript">
                jQuery(function validation(){
                swal("Info", "Product Has Been Deleted", "info", {
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
                <h3 class="box-title">Purchases List</h3>
                <a href="add_product" class="btn btn-success btn-sm pull-right">Add Purchase</a>
            </div>
            <div class="box-body">
                <div style="overflow-x:auto;">
                    <table class="table table-striped" id="myProduct">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Orginal Price</th>
                                <th>Selling Price</th>
                                <th>Expected Profit</th>
                                <th>Quantity</th>
                                <th>Recorded At</th>
                                <th>Recorded By</th>
                                <th>Action</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $select = $pdo->prepare("SELECT p.product_id, p.product_code, p.product_name, p.purchase_price, p.sell_price, p.product_profit, p.stock, p.min_stock, p.product_unit, u.username AS recorded_by, p.added_at_rm_time, p.status FROM tbl_purchases p LEFT JOIN tbl_user u ON u.user_id = p.added_by");
                            $select->execute();
                            while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $row->product_code; ?></td>
                                    <td><?php echo $row->product_name; ?></td>
                                    <td>ksh. <?php echo number_format($row->purchase_price, 2); ?></td>
                                    <td>ksh. <?php echo number_format($row->sell_price, 2); ?></td>
                                    <td>ksh. <?php echo number_format($row->product_profit, 2); ?></td>
                                    <td> <?php if ($row->stock == "0") { ?>
                                            <span class="label label-danger"><?php echo $row->stock; ?></span>
                                        <?php } elseif ($row->stock <= $row->min_stock) { ?>
                                            <span class="label label-warning"><?php echo $row->stock; ?></span>
                                        <?php } else { ?>
                                            <span class="label label-primary"><?php echo $row->stock; ?></span>
                                        <?php } ?>
                                        <span class="label label-default"><?php echo $row->product_unit; ?></span>
                                    </td>
                                    <td>
                                        <?php echo $row->added_at_rm_time . "<br/>" .fancydate($row->added_at_rm_time); ?>
                                    </td>
                                    <td>
                                        <?php echo $row->recorded_by; ?>
                                    </td>
                                    <td>
                                        <a href="view_product?id=<?php echo $row->product_id; ?>" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
                                        <a href="edit_product?id=<?php echo $row->product_id; ?>" class="btn btn-info btn-sm"><i class="fa fa-pencil"></i></a>
                                        <?php if ($_SESSION['role'] == "Admin") { ?>
                                            <a href="purchases?id=<?php echo $row->product_id; ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <label class='label' style='background-color: <?php echo $purchase_statuses[$row->status]['color_code']; ?>'><?php echo $purchase_statuses[$row->status]['name']; ?></label>
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
    $(document).ready(function() {
        $('#myProduct').DataTable();
    });
</script>

<?php
include_once 'inc/footer_all.php';
?>