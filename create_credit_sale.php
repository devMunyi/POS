<?php
   include_once ('db/connect_db.php');
   include_once ('php_functions/functions.php');
   session_start();
   if($_SESSION['username']==""){
     header('location:index');
   }else{
     if($_SESSION['role']=="Admin"){
       include_once ('inc/header_all.php');
     }else{
         include_once ('inc/header_all_operator.php');
     }
   }


    error_reporting(0);
    date_default_timezone_set('Africa/Nairobi');

    function fill_product($pdo){
      $output= '';

      $select = $pdo->prepare("SELECT * FROM tbl_product");
      $select->execute();
      $result = $select->fetchAll();

      foreach($result as $row){
        $output.='<option value="'.$row['product_id'].'">'.$row["product_code"].'</option>';
      }

      return $output;
    }

    if(isset($_POST['save_order'])){
      $cashier_name = $_POST['cashier_name'];
      $order_date = date("Y-m-d",strtotime($_POST['orderdate']));
      $order_time = date("H:i", strtotime($_POST['timeorder']));
      $total = $_POST['total'];
      $sale_profit = $_POST['net_profit'];
      $paid = $_POST['paid'];
      $credit_balance = $_POST['credit_balance'];
      $due_date = $_POST['due_date'];
      $sale_type = "Credit";
      $customer_no = $_POST['customer_no'];
      $status = "Unpaid";


      $arr_product_id =  $_POST['productid'];
      $arr_product_code = $_POST['productcode'];
      $arr_product_name = $_POST['productname'];
      $arr_product_stock = $_POST['productstock'];
      $arr_product_qty = $_POST['quantity'];
      $arr_product_unit = $_POST['productunit'];
      $arr_product_price = $_POST['productprice'];
      $arr_product_total =  $_POST['producttotal'];
      $arr_item_profit = $_POST['item_profit'];

      if($arr_product_code == ""){
        echo '<script type="text/javascript">
              jQuery(function validation(){
              swal("Warning", "Please Fill in the Transaction Form", "warning", {
              button: "Back",
                  });
              });
              </script>';

      }else{
          $select = $pdo->prepare("SELECT * FROM tbl_invoice WHERE credit_balance > 0 AND customer_no = '$customer_no' AND status = '$status'");
          $select->execute();

          $active_cust = $pdo->prepare("SELECT * FROM tbl_invoice WHERE total > 0 AND customer_no = '$customer_no' AND (status = 'Cleared' || status = 'Paid') AND DATEDIFF(order_date, \"$date\") <= 30");
          $active_cust->execute();

          $credit_limit_ = $pdo->prepare("SELECT sum(sale_profit) as credit_limit FROM tbl_invoice WHERE total > 0 AND customer_no = '$customer_no' AND (status = 'Cleared' || status = 'Paid') AND DATEDIFF(order_date, \"$date\") <= 30");
          $credit_limit_->execute();
          $row=$credit_limit_->fetch(PDO::FETCH_OBJ);
          $credit_limit = $row->credit_limit;

          if($active_cust->rowCount() < 1 ){
            echo '<script type="text/javascript">
              jQuery(function validation(){
              swal("Warning", "The customer does not qualify for a credit", "warning", {
              button: "Back",
                  });
              });
              </script>';
          }elseif($credit_balance > $credit_limit){
            echo '<script type="text/javascript">
              jQuery(function validation(){
              swal("Warning", "The customer credit limit is ksh '.$credit_limit.'", "warning", {
              button: "Back",
                  });
              });
              </script>';
            }elseif($select->rowCount() > 0){
            echo '<script type="text/javascript">
              jQuery(function validation(){
              swal("Warning", "The customer has an existing credit", "warning", {
              button: "Back",
                  });
              });
              </script>';
            }elseif (datediff($date, $due_date) < 0) {
              echo '<script type="text/javascript">
              jQuery(function validation(){
              swal("Warning", "Due date must be a future date/time", "warning", {
              button: "Back",
                  });
              });
              </script>';
            }else{
            $insert = $pdo->prepare("INSERT INTO tbl_invoice(`cashier_name`, `order_date`, `time_order`, `total`,sale_profit, `paid`, `credit_balance`, `due_date`, `sale_type`, `customer_no`, `status`)
            values(:name, :orderdate, :timeorder, :total, :sale_profit, :paid, :credit_balance, :due_date, :sale_type, :customer_no, :status)");

            $insert->bindParam(':name', $cashier_name);
            $insert->bindParam(':orderdate',  $order_date);
            $insert->bindParam(':timeorder',  $order_time);
            $insert->bindParam(':total', $total);
            $insert->bindParam(':sale_profit', $sale_profit);
            $insert->bindParam(':paid', $paid);
            $insert->bindParam(':credit_balance', $credit_balance);
            $insert->bindParam(':due_date', $due_date);
            $insert->bindParam(':sale_type', $sale_type);
            $insert->bindParam(':customer_no', $customer_no);
            $insert->bindParam(':status', $status);

            $insert->execute();


            $invoice_id = $pdo->lastInsertId();
            if($invoice_id!=null){
              for($i=0; $i<count($arr_product_id); $i++){

                $rem_qty = $arr_product_stock[$i] - $arr_product_qty[$i];

                if($rem_qty<0){
                  echo '<script type="text/javascript">
                        jQuery(function validation(){
                        swal("Warning", "Pleas Enter Product Quantity", "warning", {
                        button: "Okay",
                            });
                        });
                        </script>';
                }else{
                  $update = $pdo->prepare("UPDATE tbl_product SET stock = '$rem_qty' WHERE product_id='".$arr_product_id[$i]."'");
                  $update->execute();
                }


                $insert = $pdo->prepare("INSERT INTO tbl_invoice_detail(invoice_id, product_id, product_code, product_name, qty, product_unit, price, total, item_profit, order_date)
                values(:invid, :productid, :productcode, :productname, :qty, :productunit, :price, :total, :item_profit, :orderdate)");

                $insert->bindParam(':invid',  $invoice_id);
                $insert->bindParam(':productid',   $arr_product_id[$i]);
                $insert->bindParam(':productcode',   $arr_product_code[$i]);
                $insert->bindParam(':productname', $arr_product_name[$i]);
                $insert->bindParam(':qty', $arr_product_qty[$i]);
                $insert->bindParam(':productunit', $arr_product_unit[$i]);
                $insert->bindParam(':price',  $arr_product_price[$i]);
                $insert->bindParam(':item_profit',   $arr_item_profit[$i]);
                $insert->bindParam(':total',   $arr_product_total[$i]);
                $insert->bindParam(':orderdate',  $order_date);

                $insert->execute();

              }
            echo '<script>location.href="credit_sales";</script>';

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
        Transaction
      </h1>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box box-success">
          <form action="" method="POST">
            <div class="box-body">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Cashier Name</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-user"></i>
                    </div>
                    <input type="text" class="form-control pull-right" name="cashier_name" value="<?php echo $_SESSION['username']; ?>" readonly>
                  </div>
                  <!-- /.input group -->
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Transaction Date</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" class="form-control pull-right" name="orderdate" value="<?php echo date("d-m-Y");?>" readonly
                    data-date-format="yyyy-mm-dd">
                    <!-- /.input group -->
                  </div>
                </div>
              </div>
              <div class="form-group">
                  <div class="col-md-4">
                  <label>Trnsaction Time</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                    <input type="text" class="form-control pull-right" name="timeorder" value="<?php echo date('H:i') ?>" readonly>
                  </div>
                  <!-- /.input group -->
                </div>
              </div
            </div>

            <div class="box-body">
              <div class="col-md-12" style="overflow-x:auto;">
                <table class="table table-border" id="myOrder">
                  <thead>
                      <tr>
                          <th></th>
                          <th>Code</th>
                          <th>Name</th>
                          <th>Stock</th>
                          <th>Price</th>
                          <th></th>
                          <th>Quantity</th>
                          <th>Unit</th>
                          <th>Total</th>
                          <th>Item Profit</th>
                          <th>
                            <button type="button" name="addOrder" class="btn btn-success btn-sm btn_addOrder" required><span>
                              <i class="fa fa-plus"></i>
                            </span></button>
                          </th>
                      </tr>

                  </thead>
                  <tbody>

                  </tbody>
                </table>
              </div>
            </div>

            <div class="box-body">
              <div class="col-md-offset-1 col-md-10">
                <div class="form-group">
                  <div class="row">
                    <div class="col-md-5">
                      <label>Credit Total</label>
                      <div class="input-group">
                        <div class="input-group-addon">
                          <span>ksh</span>
                        </div>
                        <input type="text" class="form-control pull-right" name="total" id="total" required readonly>
                      </div>
                      
                    </div>
                    <div class="col-md-5">
                      <label>Profit Total</label>
                      <div class="input-group">
                        <div class="input-group-addon">
                          <span>ksh</span>
                        </div>
                        <input type="text" class="form-control pull-right" name="net_profit" id="net_profit" required readonly>
                      </div>
                    </div>
                  </div>
                  <!-- /.input group -->
                </div>
                <div class="form-group">
                  <label>Money Received</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <span>ksh</span>
                    </div>
                    <input type="text" class="form-control pull-right" name="paid" id="paid" required>
                  </div>
                  <!-- /.input group -->
                </div>
                <div class="form-group">
                  <label>Credit Balance</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <span>ksh <?php echo $_SESSION['invoice_id']; ?></span>
                    </div>
                    <input type="text" class="form-control pull-right" name="credit_balance" id="credit_balance" required readonly>
                  </div>
                  <!-- /.input group -->
                </div>

                <div class="form-group">
                  <label>Phone Number</label> *<span class="text-muted font-italic">Required</span>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-phone"></i>
                    </div>
                    <input type="text" class="form-control pull-right" name="customer_no" id="customer_no" placeholder="Enter customer number" required>
                  </div>
                  <!-- /.input group -->
                </div>

                <div class="form-group">
                  <label>Due Date</label> *<span class="text-muted font-italic">Required</span>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" class="form-control pull-right" name="due_date" id="due_date" placeholder="Enter Repayment Date" 
                    required data-date-format="yyyy-mm-dd">
                  </div>
                  <!-- /.input group -->
                </div>
              </div>
            </div>

            <div class="box-footer" align="center">
              <input type="submit" name="save_order" value="Save Transaction" class="btn btn-success">
              <a href="sale" class="btn btn-warning">Return</a>
            </div>
          </form>
        </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <script>
  //Date picker
    $('#datepicker').datepicker({
      autoclose: true
    })

    //Timepicker
    $('.timepicker').timepicker({
      showInputs: false
    })

    $('#due_date').datepicker({
      autoclose: true
    })

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    })

    $(document).ready(function(){
      $(document).on('click','.btn_addOrder', function(){
        var html='';
        html+='<tr>';
        html+='<td><input type="hidden" class="form-control productcode" name="productcode[]" readonly></td>';
        html+='<td><select class="form-control productid" name="productid[]" style="width:100px;" required><option value="">--Select Product--</option><?php
        echo fill_product($pdo)?></select></td>';
        html+='<td><input type="text" class="form-control productname" style="width:200px;" name="productname[]" readonly></td>';
        html+='<td><input type="text" class="form-control productstock" style="width:50px;" name="productstock[]" readonly></td>';
        html+='<td><input type="text" class="form-control productprice" style="width:100px;" name="productprice[]" readonly></td>';
        html+='<td><input type="hidden" class="form-control productprofit" style="width:150px;" name="productprofit[]" readonly></td>';
        html+='<td><input type="number" min="1" max="50" class="form-control quantity_product" style="width:100px;" name="quantity[]" required></td>';
        html+='<td><input type="text" class="form-control productunit" style="width:100px;" name="productunit[]" readonly></td>';
        html+='<td><input type="text" class="form-control producttotal" style="width:150px;" name="producttotal[]" readonly></td>';
        html+='<td><input type="text" class="form-control profit_" style="width:150px;" name="item_profit[]" id="profit_" readonly></td>';
        html+='<td><button type="button" name="remove" class="btn btn-danger btn-sm btn-remove"><i class="fa fa-remove"></i></button></td>'

        $('#myOrder').append(html);

        $('.productid').on('change', function(e){
          var productid = this.value;
          var tr=$(this).parent().parent();
          $.ajax({
            url:"getproduct.php",
            method:"get",
            data:{id:productid},
            success:function(data){
              //console.log(data);
              tr.find(".productcode").val(data["product_code"]);
              tr.find(".productname").val(data["product_name"]);
              tr.find(".productstock").val(data["stock"]);
              tr.find(".productunit").val(data["product_unit"]);
              tr.find(".productprice").val(data["sell_price"]);
              tr.find(".productprofit").val(data["product_profit"]);
              tr.find(".quantity_product").val(0);
              tr.find(".producttotal").val(tr.find(".quantity_product").val() * tr.find(".productprice").val());
              tr.find(".profit_").val(tr.find(".quantity_product").val() * tr.find(".productprofit").val());
              calculate(0,0);
            }
          })
        })

      })

      $(document).on('click','.btn-remove', function(){
        $(this).closest('tr').remove();
        calculate(0,0);
        $("#paid").val(0);
      })

      $("#myOrder").delegate(".quantity_product","keyup change", function(){
        var quantity = $(this);
        var tr=$(this).parent().parent();
        if((quantity.val()-0)>(tr.find(".productstock").val()-0)){
          swal("Warning","Stock available is low","warning");
          quantity.val(1);
          tr.find(".producttotal").val(quantity.val() * tr.find(".productprice").val());
          tr.find(".profit_").val(quantity.val() * tr.find(".productprofit").val());
          calculate(0,0);
        }else{
          tr.find(".producttotal").val(quantity.val() * tr.find(".productprice").val());
          tr.find(".profit_").val(quantity.val() * tr.find(".productprofit").val());
          calculate(0,0);
        }
      })

      function calculate(paid){
        var net_total = 0;
        var net_profit = 0;
        var paid = paid;

        $(".producttotal").each(function(){
          net_total = net_total + ($(this).val()*1);
        })

        $(".profit_").each(function(){
          net_profit = net_profit + ($(this).val()*1);
        })

        credit_balance = net_total - paid;

        $("#total").val(net_total);
        $("#net_profit").val(net_profit);
        $("#credit_balance").val(credit_balance);
      }


      $("#paid").keyup(function(){
        var paid = $(this).val();
        calculate(paid);
      })

    });
  </script>


 <?php
    include_once'inc/footer_all.php';
 ?>