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

if (isset($_POST['add_product'])) {
    $code = $_POST['product_code'];
    $product = $_POST['product_name'];
    $category = $_POST['category'];
    $purchase = $_POST['purchase_price'];
    $sell = $_POST['sell_price'];
    $product_profit = $sell - $purchase;
    $stock = $_POST['stock'];
    $min_stock = $_POST['min_stock'];
    $unit = $_POST['unit'];
    $desc = $_POST['description'];
    $product_img = "";

    if (isset($_POST['product_code'])) {
        $select = $pdo->prepare("SELECT product_code FROM tbl_product WHERE product_code='$code'");
        $select->execute();

        if ($select->rowCount() > 0) {
            echo '<script type="text/javascript">
                    jQuery(function validation(){
                    swal("Warning", "Product Code Already Registered", "warning", {
                    button: "Continue",
                        });
                    });
                    </script>';
        } elseif (strlen($code) < 3) {
            echo '<script type="text/javascript">
                    jQuery(function validation(){
                    swal("Warning", "Product Code must be atleast 3 characters", {
                    button: "Continue",
                        });
                    });
                    </script>';
        } else {
            $img = $_FILES['product_img']['name'];
            $img_tmp = $_FILES['product_img']['tmp_name'];
            $img_size = $_FILES['product_img']['size'];
            $img_ext = explode('.', $img);
            $img_ext = strtolower(end($img_ext));

            $img_new = uniqid() . '.' . $img_ext;
            if (($img_size) > 0) {
                $store = "upload/" . $img_new;
                if ($img_ext == 'jpg' || $img_ext == 'jpeg' || $img_ext == 'png' || $img_ext == 'gif' || $img_ext == 'jfif') {
                    if ($img_size >= 1000000) {
                        $error = '<script type="text/javascript">
                                    jQuery(function validation(){
                                    swal("Error", "File Should Be 1MB", "error", {
                                    });
                                    button: "Continue",
                                    });
                                    </script>';
                        echo $error;
                    } else {
                        if (move_uploaded_file($img_tmp, $store)) {
                            $product_img = $img_new;
                            if (!isset($error)) {

                                $insert = $pdo->prepare("INSERT INTO tbl_product(product_code,product_name,product_category,purchase_price,sell_price,product_profit,stock,min_stock,product_unit,description,img)
                                    values(:product_code,:product_name,:product_category,:purchase_price,:sell_price,:product_profit,:stock,:min_stock,:unit,:desc,:img)");

                                $insert->bindParam(':product_code', $code);
                                $insert->bindParam(':product_name', $product);
                                $insert->bindParam(':product_category', $category);
                                $insert->bindParam(':purchase_price', $purchase);
                                $insert->bindParam(':sell_price', $sell);
                                $insert->bindParam(':product_profit', $product_profit);
                                $insert->bindParam(':stock', $stock);
                                $insert->bindParam(':min_stock', $min_stock);
                                $insert->bindParam(':unit', $unit);
                                $insert->bindParam(':desc', $desc);
                                $insert->bindParam(':img', $product_img);

                                if ($insert->execute()) {
                                    ///---Begin updating tbl_stock_record table by current date
                                    $prod = fetchtable('tbl_product', "product_id > 0", "product_id", "ASC", "0,500", "sell_price, stock");
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
                                        updatedb("tbl_stock_record", "net_stock=$prod_net_stock_total, stock_value=$prod_net_val_total", "stock_date = '$date'");
                                    } else {
                                        $fds = array('net_stock', 'stock_value', 'stock_date');
                                        $vals = array($prod_net_stock_total, $prod_net_val_total, "$added_date");
                                        addtodb("tbl_stock_record", $fds, $vals);
                                    }
                                    ///---End updating tbl_stock_record table by current date

                                    echo '<script type="text/javascript">
                                                jQuery(function validation(){
                                                swal("Success", "Product Saved Successfully", "success", {
                                                button: "Continue",
                                                    });
                                                });
                                                </script>';
                                } else {
                                    echo '<script type="text/javascript">
                                                jQuery(function validation(){
                                                swal("Error", "Product could not be saved", "error", {
                                                button: "Continue",
                                                    });
                                                });
                                                </script>';;
                                }
                            } else {
                                echo '<script type="text/javascript">
                                                jQuery(function validation(){
                                                swal("Error", "Error occured in uploading the file", "error", {
                                                button: "Continue",
                                                    });
                                                });
                                                </script>';;;
                            }
                        }
                    }
                } else {
                    $error = '<script type="text/javascript">
                        jQuery(function validation(){
                        swal("Error", "Please Upload Image With Format : jpg, jpeg, png, gif or jfif", "error", {
                        button: "Continue",
                            });
                        });
                        </script>';
                    echo $error;
                }
            } else {
                $insert = $pdo->prepare("INSERT INTO tbl_product(product_code,product_name,product_category,purchase_price,sell_price,product_profit,stock,min_stock,product_unit,description,img)
                      values(:product_code,:product_name,:product_category,:purchase_price,:sell_price,:product_profit,:stock,:min_stock,:unit,:desc,:img)");

                $insert->bindParam(':product_code', $code);
                $insert->bindParam(':product_name', $product);
                $insert->bindParam(':product_category', $category);
                $insert->bindParam(':purchase_price', $purchase);
                $insert->bindParam(':sell_price', $sell);
                $insert->bindParam(':product_profit', $product_profit);
                $insert->bindParam(':stock', $stock);
                $insert->bindParam(':min_stock', $min_stock);
                $insert->bindParam(':unit', $unit);
                $insert->bindParam(':desc', $desc);
                $insert->bindParam(':img', $product_img);

                if ($insert->execute()) {
                    ///---Begin updating tbl_stock_record table by current date
                    $prod = fetchtable('tbl_product', "product_id > 0", "product_id", "ASC", "0,500", "sell_price, stock");
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
                        updatedb("tbl_stock_record", "net_stock=$prod_net_stock_total, stock_value=$prod_net_val_total", "stock_date = '$date'");
                    } else {
                        $fds = array('net_stock', 'stock_value', 'stock_date');
                        $vals = array($prod_net_stock_total, $prod_net_val_total, "$added_date");
                        addtodb("tbl_stock_record", $fds, $vals);
                    }
                    ///---End updating tbl_stock_record table by current date

                    echo '<script type="text/javascript">
                                jQuery(function validation(){
                                swal("Success", "Product Saved Successfully", "success", {
                                button: "Continue",
                                 });
                            });
                        </script>';
                } else {
                    echo '<script type="text/javascript">
                                jQuery(function validation(){
                                swal("Error", "Product could not be saved", "error", {
                                button: "Continue",
                                });
                            });
                        </script>';;
                }
            }
        }
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
        <hr>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Enter New Product</h3>
            </div>
            <form action="" method="POST" name="form_product" enctype="multipart/form-data" autocomplete="off">
                <div class="box-body">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Product Code</label><br>
                            <input type="text" class="form-control" name="product_code" maxlength="6">
                        </div>
                        <div class="form-group">
                            <label for="">Product Name</label>
                            <input type="text" class="form-control" name="product_name">
                        </div>
                        <div class="form-group">
                            <label for="">Category</label>
                            <select class="form-control" name="category" required>
                                <?php
                                $select = $pdo->prepare("SELECT * FROM tbl_category");
                                $select->execute();
                                while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                                    extract($row)
                                ?>
                                    <option><?php echo $row['cat_name']; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Original Price</label>
                            <input type="number" min="1" step="1" class="form-control" name="purchase_price" required>
                        </div>
                        <div class="form-group">
                            <label for="">Selling Price</label>
                            <input type="number" class="form-control" name="sell_price" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Stock</label><br>
                            <input type="text" class="form-control" name="stock" required>
                        </div>
                        <div class="form-group">
                            <label for="">Minimum Stock</label><br>
                            <input type="text" class="form-control" name="min_stock" required>
                        </div>
                        <div class="form-group">
                            <label for="">Unit</label>
                            <select class="form-control" name="unit" required>
                                <?php
                                $select = $pdo->prepare("SELECT * FROM tbl_unit");
                                $select->execute();
                                while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                                    extract($row)
                                ?>
                                    <option><?php echo $row['unit_name']; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Product Description</label>
                            <textarea name="description" id="description" cols="30" rows="10" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Product Image</label><br>
                            <br>
                            <input type="file" class="input-group" name="product_img" onchange="readURL(this);"> <br>
                            <img id="img_preview" src="upload/<?php echo $row['img']; ?>" alt="Preview" class="img-responsive" />
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary" name="add_product">Save</button>
                    <a href="product.php" class="btn btn-warning">Return</a>
                </div>
            </form>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script type="text/javascript">
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#img_preview').attr('src', e.target.result)
                    .width(250)
                    .height(200);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<?php
include_once 'inc/footer_all.php';
?>