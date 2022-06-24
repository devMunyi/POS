<?php
   include_once ('db/connect_db.inc');
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
      $amount_paid = $_POST['amount_paid'];
      $date_paid = $date;
      $creditor_no = $_POST['creditor_no'];
    
    
      if($creditor_no == ""){
        echo '<script type="text/javascript">
              jQuery(function validation(){
              swal("Warning", "Please Fill in the Creditor Number", "warning", {
              button: "Back",
                  });
              });
              </script>';

      }else{
        $crdtor_exists = checkrowexists("tbl_invoice", "sale_type = 'Credit' AND customer_no = '$creditor_no' AND credit_balance > 0 AND status != 'Paid'");
        if($crdtor_exists == 1){
          $row = fetchonerow("tbl_invoice", "customer_no = '$creditor_no' AND sale_type = 'Credit' AND credit_balance > 0 AND status != 'Paid'", "invoice_id, credit_balance, due_date");
          $credit_bal = $row['credit_balance'];
          $invoice_id = $row["invoice_id"];
          $due_date = $row['due_date'];

          //if($amount_paid >= $credit_bal){
            $new_bal_ = $amount_paid - $credit_bal;
            if($new_bal_ >= 0){
              $new_bal = 0;
              $pay_status = "Paid";
              $message = "Full Repayment recorded successfully";
            }else{
              $new_bal = abs($new_bal_);
              $pay_status = "Partially Paid";
              $message = "Partial repayment recorded successfully";
            }
              updatedb("tbl_invoice", "credit_balance = $new_bal, status = '$pay_status'", "invoice_id = $invoice_id");//update tbl_invoice table
              updatedb("tbl_invoice_detail", "status = '$pay_status'", "invoice_id = $invoice_id"); //update tbl_invoice_detail table
              
              $fds = array('cashier_name','invoice_id', 'creditor_no', 'amount_paid', 'date_paid', 'credit_balance', 'due_date', 'status');
              $vals = array("$cashier_name","$invoice_id", "$creditor_no", "$amount_paid", "$date_paid", "$new_bal", "$due_date", "$pay_status");
              $create = addtodb('tbl_repayments', $fds, $vals);

              if($create  == 1 && $message == "Full Repayment recorded successfully"){
                echo '<script type="text/javascript">
                jQuery(function validation(){
                swal("Success", "Full Repayment recorded successfully", "success", {
                button: "Okay",
                    });
                });
                </script>';
              } elseif ($create  == 1 && $message == "Partial repayment recorded successfully"){
                echo '<script type="text/javascript">
                jQuery(function validation(){
                swal("Success", "Partial repayment recorded successfully", "success", {
                button: "Okay",
                    });
                });
                </script>';
              }else{
                  echo '<script type="text/javascript">
                  jQuery(function validation(){
                  swal("Warning", "Unable to record credit repayment", "warning", {
                  button: "Back",
                      });
                  });
                  </script>';
              }
                      
          //}
          //else{
          //   echo '<script type="text/javascript">
          //     jQuery(function validation(){
          //     swal("Warning", "The customer credit balance is ksh '.$credit_bal.'", "warning", {
          //     button: "Back",
          //         });
          //     });
          //     </script>';
          // }
        }else {
          echo '<script type="text/javascript">
              jQuery(function validation(){
              swal("Warning", "No record found with that creditor number", "warning", {
              button: "Back",
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
                  <label>Transaction Time</label>
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
                          <th>Record Payment</th>
                      </tr>
                  </thead>
                </table>
              </div>
            </div>

            <div class="box-body">
              <div class="col-md-offset-1 col-md-10">
                <div class="form-group">
                  <label>Creditor Number</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-phone"></i>
                    </div>
                    <input type="text" class="form-control pull-right" name="creditor_no" id="creditor_no" placeholder="Enter customer number" required>
                  </div>
                  <!-- /.input group -->
                </div>
                <div class="form-group">
                  <label>Repay Amount</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <span>ksh</span>
                    </div>
                    <input type="text" class="form-control pull-right" name="amount_paid" id="amount_paid" required>
                  </div>
                  <!-- /.input group -->
                </div>
              </div>
            </div>

            <div class="box-footer" align="center">
              <input type="submit" name="save_order" value="Save Transaction" class="btn btn-success">
              <a href="credit_repayments" class="btn btn-warning">Return</a>
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
        html+='<td><input type="number" min="1" max="50" class="form-control quantity_product" style="width:100px;" name="quantity[]" required></td>';
        html+='<td><input type="text" class="form-control productsatuan" style="width:100px;" name="productsatuan[]" readonly></td>';
        html+='<td><input type="text" class="form-control producttotal" style="width:150px;" name="producttotal[]" readonly></td>';
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
              tr.find(".productsatuan").val(data["product_satuan"]);
              tr.find(".productprice").val(data["sell_price"]);
              tr.find(".quantity_product").val(0);
              tr.find(".producttotal").val(tr.find(".quantity_product").val() * tr.find(".productprice").val());
              calculate(0,0);
            }
          })
        })


        $('#creditor_no').on('change', function(e){
          var creditor_no = this.value;
          $.ajax({
            url:"getcreditbal.php",
            method:"get",
            data:{phone:creditor_no},
            success:function(data){
              $("#amount_paid").val(data["credit_balance"]);
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
          swal("Warning","Low available is low","warning");
          quantity.val(1);
          tr.find(".producttotal").val(quantity.val() * tr.find(".productprice").val());
          calculate(0,0);
        }else{
          tr.find(".producttotal").val(quantity.val() * tr.find(".productprice").val());
          calculate(0,0);
        }
      })

      function calculate(paid){
        var net_total = 0;
        var paid = paid;

        $(".producttotal").each(function(){
          net_total = net_total + ($(this).val()*1);
        })

        credit_balance = net_total - paid;

        $("#total").val(net_total);
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