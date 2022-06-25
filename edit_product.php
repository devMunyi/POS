<?php
session_start();
include_once 'misc/plugin.php';
include_once 'db/connect_db.inc';
include_once 'php_functions/functions.php';

if ($_SESSION['username'] == "") {
    header('location:index');
} else {
    if ($_SESSION['role'] == "Admin") {
        include_once('inc/header_all.php');
    } else {
        include_once('inc/header_all_operator.php');
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $select = $pdo->prepare("SELECT * FROM tbl_product WHERE product_id=$id");
    $select->execute();
    $row = $select->fetch(PDO::FETCH_ASSOC);

    $productCode_db = $row['product_code'];
    $productName_db = $row['product_name'];
    $category_db = $row['product_category'];
    $purchase_db = $row['purchase_price'];
    $sell_db = $row['sell_price'];
    $stock_db = $row['stock'];
    $min_stock_db = $row['min_stock'];
    $unit_db = $row['product_unit'];
    $desc_db = $row['description'];
    $product_img = $row['img'];
} else {
    header('location:product');
}

if (isset($_POST['update_product'])) {
    $code_req = $_POST['product_code'];
    $product_req = $_POST['product_name'];
    $category_req = $_POST['category'];
    $purchase_req = $_POST['purchase_price'];
    $sell_req = $_POST['prod_'];
    $product_profit = $sell_req - $purchase_req;
    $stock_req = $_POST['stock'];
    $min_stock_req = $_POST['min_stock'];
    $unit_req = $_POST['unit'];
    $desc_req = $_POST['description'];
    $img = $_FILES['product_img']['name'];
    if (!empty($img)) {
        $img_tmp = $_FILES['product_img']['tmp_name'];
        $img_size = $_FILES['product_img']['size'];
        $img_ext = explode('.', $img);
        $img_ext = strtolower(end($img_ext));

        $img_new = uniqid() . '.' . $img_ext;

        $store = "upload/" . $img_new;

        if ($img_ext == 'jpg' || $img_ext == 'jpeg' || $img_ext == 'png' || $img_ext == 'gif' || $img_ext == 'jfif') {
            if ($img_size >= 1000000) {
                $error = '<script type="text/javascript">
                                jQuery(function validation(){
                                swal("Error", "File Should Be 1MB", "error", {
                                button: "Continue",
                                    });
                                });
                                </script>';
                echo $error;
            } else {
                if (move_uploaded_file($img_tmp, $store)) {
                    $img_new;
                    if (!isset($error)) {
                        $update = $pdo->prepare("UPDATE tbl_product SET product_code=:product_code,product_name=:product_name,
                                product_category=:product_category, purchase_price=:purchase_price, prod_=:sell_price, product_profit=:product_profit,
                                stock=:stock,min_stock=:min_stock,product_unit=:product_unit ,description=:description, img=:img WHERE product_id=$id");

                        $update->bindParam('product_code', $code_req);
                        $update->bindParam('product_name', $product_req);
                        $update->bindParam('product_category', $category_req);
                        $update->bindParam('purchase_price', $purchase_req);
                        $update->bindParam('sell_price', $sell_req);
                        $update->bindParam('product_profit', $product_profit);
                        $update->bindParam('stock', $stock_req);
                        $update->bindParam('min_stock', $min_stock_req);
                        $update->bindParam('product_unit', $satuan_req);
                        $update->bindParam('description', $desc_req);
                        $update->bindParam('img',  $img_new);

                        if ($update->execute()) {
                            //get the current stock and sell_price from tbl_product table
                            $prod_id = $id;
                            $prod_ = fetchonerow("tbl_product", "product_id = $prod_id", "sell_price, stock, product_unit");
                            $current_stock = $prod_["stock"];
                            $prod_sell_price =  $prod_["sell_price"];
                            $prod_unit =  $prod_["product_unit"];
                            $current_stock_with_units = $current_stock . " " . $prod_unit; //stock with units
                            $current_stock_value_ = $current_stock * $prod_sell_price;
                            $current_stock_value = number_format($current_stock_value_, 2); //net stock value

                            //store product update event
                            $user_id = $_SESSION['user_id'];
                            $events = "Product updated at [$fulldate] by [" . $_SESSION['username'] . " - " . $_SESSION['role'] . "]";
                            store_event('tbl_product', $prod_id, "$events", $user_id, $current_stock_with_units, $current_stock_value);

                            header('location:view_product?id=' . urlencode($id));
                        } else {
                            echo 'Something is Wrong';
                        }

                        echo 'Image upload failed';
                    } else {
                    }
                }
            }
        } else {
            $error = '<script type="text/javascript">
                    jQuery(function validation(){
                    swal("Error", "Please Upload Image With Format : jpg, jpeg, png, gif : jpg, jpeg, png, gif or jfif", "error", {
                    button: "Continue",
                        });
                    });
                    </script>';
            echo $error;
        }
    } else {
        $update = $pdo->prepare("UPDATE tbl_product SET product_code=:product_code,product_name=:product_name,
                product_category=:product_category, purchase_price=:purchase_price, sell_price=:sell_price, product_profit=:product_profit,
                stock=:stock,min_stock=:min_stock, product_unit=:product_unit ,description=:description, img=:img WHERE product_id=$id");

        $update->bindParam('product_code', $code_req);
        $update->bindParam('product_name', $product_req);
        $update->bindParam('product_category', $category_req);
        $update->bindParam('purchase_price', $purchase_req);
        $update->bindParam('sell_price', $sell_req);
        $update->bindParam('product_profit', $product_profit);
        $update->bindParam('stock', $stock_req);
        $update->bindParam('min_stock', $min_stock_req);
        $update->bindParam('product_unit', $unit_req);
        $update->bindParam('description', $desc_req);
        $update->bindParam('img',  $product_img);

        if ($update->execute()) {
            //get the current stock and sell_price from tbl_product table
            $prod_id = $id;
            $prod_ = fetchonerow("tbl_product", "product_id = $prod_id", "sell_price, stock, product_unit");
            $current_stock = $prod_["stock"];
            $prod_sell_price =  $prod_["sell_price"];
            $prod_unit =  $prod_["product_unit"];
            $current_stock_with_units = $current_stock . " " . $prod_unit; //stock with units
            $current_stock_value_ = $current_stock * $prod_sell_price;
            $current_stock_value = number_format($current_stock_value_, 2); //net stock value

            //store product update event
            $user_id = $_SESSION['user_id'];
            $events = "Product updated at [$fulldate] by [" . $_SESSION['username'] . " - " . $_SESSION['role'] . "]";
            store_event('tbl_product', $prod_id, "$events", $user_id, $current_stock_with_units, $current_stock_value);


            //redirect to product view
            echo '<script> location.replace("view_product?id=' . $id . '"); </script>';
            //header("location:view_product?id=$id");
        } else {
            echo '<script type="text/javascript">
                        jQuery(function validation(){
                        swal("Error", "There is an error", "error", {
                        button: "Continue",
                            });
                        });
                        </script>';
        }
    }
}

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>

        </h1>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Edit Product</h3>
            </div>
            <form action="" method="POST" name="form_product" enctype="multipart/form-data" autocomplete="off">
                <div class="box-body">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Product Code</label>
                            <input type="text" class="form-control" name="product_code" value="<?php echo $productCode_db; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="">Product Name</label>
                            <input type="text" class="form-control" name="product_name" value="<?php echo $productName_db; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="">Category</label>
                            <select class="form-control" name="category" required>
                                <?php
                                $select = $pdo->prepare("SELECT * FROM tbl_category");
                                $select->execute();
                                while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                                    extract($row);
                                ?>
                                    <option <?php if ($row['cat_name'] == $category_db) { ?> selected="selected" <?php } ?>>
                                        <?php echo $row['cat_name']; ?></option>

                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Original Price</label>
                            <input type="number" min="1" step="" class="form-control" name="purchase_price" value="<?php echo $purchase_db; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="">Selling Price</label>
                            <input type="number" min="1" step="" class="form-control" name="prod_" value="<?php echo $sell_db; ?>" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Stock</label>
                            <input type="text" class="form-control" name="stock" value="<?php echo $stock_db; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="">Minimum Stock</label>
                            <input type="text" class="form-control" name="min_stock" value="<?php echo $min_stock_db; ?>" required>
                        </div>
                        <div class="form-group">
                            <select class="form-control" name="unit" required>
                                <label for="">Unit</label>
                                <?php
                                $select = $pdo->prepare("SELECT * FROM tbl_unit");
                                $select->execute();
                                while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                                    extract($row);
                                ?>
                                    <option <?php if ($row['unit_name'] == $unit_db) { ?> selected="selected" <?php } ?>>
                                        <?php echo $row['unit_name']; ?></option>

                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Product Description</label>
                            <textarea name="description" id="description" cols="30" rows="10" class="form-control"><?php echo $desc_db; ?></textarea>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Product Image</label>
                            <input type="file" class="input-group" name="product_img">
                            <img src="upload/<?php echo $product_img ?>" alt="Preview" class="img-responsive" />
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary" name="update_product">Update</button>
                    <a href="product" class="btn btn-warning">Return</a>
                </div>
            </form>

        </div>


    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php
include_once 'inc/footer_all.php';
?>