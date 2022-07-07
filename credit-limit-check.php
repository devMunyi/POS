<?php
session_start();
include_once('db/connect_db.inc');
include_once('php_functions/functions.php');
if ($_SESSION['username'] == "") {
    header('location:index.php');
} else {
    if ($_SESSION['role'] == "Admin") {
        include_once 'inc/header_all.php';
    } else {
        include_once 'inc/header_all_operator.php';
    }
}

//error_reporting(0);

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $delete = $pdo->prepare("DELETE FROM tbl_credit_limit WHERE credit_id=$id");

    if ($delete->execute()) {
        echo '<script type="text/javascript">
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
    <!-- <meta http-equiv="refresh" content="60"> -->
</head>

</html>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Credit Limit</h3>
            </div>
            <div class="box-body">
                <div id='pager_header' class='row'>
                    <div class='col-sm-6'><input type='text' class='form-control' id='cust_no_' placeholder='Enter phone number... format 01XXXXXXXX or 07XXXXXXXX' required></div>
                    <div class='col-sm-6'><button onclick="creditor_details()" title="Click to search" class="btn btn-success btn-sm">Run Search</button></div>
                </div>
                <div class="row" id="search-table">
                    <div style="overflow-x:auto;">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Customer Phone</th>
                                    <th>Limit Amount</th>
                                    <th>Date Created</th>
                                    <th>Date Updated</th>
                                </tr>
                            </thead>
                            <tbody id="creditor">
                                
                            </tbody>
                        </table>
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
        $('#myProduct').DataTable();
    });
</script>

<?php
include_once 'inc/footer_all.php';
?>