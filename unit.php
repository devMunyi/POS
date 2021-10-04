<?php
include_once'db/connect_db.php';
session_start();
if($_SESSION['role']!=="Admin"){
header('location:index');
}
include_once'inc/header_all.php';

if(isset($_POST['submit'])){
    $unit = $_POST['unit'];
    if(isset($_POST['unit'])){

            $select = $pdo->prepare("SELECT unit_name FROM tbl_unit WHERE unit_name='$unit'");
            $select->execute();

            if($select->rowCount() > 0 ){
                echo'<script type="text/javascript">
                    jQuery(function validation(){
                    swal("Warning", "Unit already exists", "warning", {
                    button: "Continue",
                        });
                    });
                    </script>';
                }else{
                    $insert = $pdo->prepare("INSERT INTO tbl_unit(unit_name) VALUES(:unit)");

                    $insert->bindParam(':unit', $unit);

                    if($insert->execute()){
                        echo '<script type="text/javascript">
                        jQuery(function validation(){
                        swal("Success", "New unit has been added", "success", {
                        button: "Continue",
                            });
                        });
                        </script>';
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
        Product Units
      </h1>
      <hr>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
       <!-- Category Form-->
      <div class="col-md-4">
            <div class="box box-success">
                <!-- /.box-header -->
                <!-- form start -->
                <form action="" method="POST">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="category">Unit Name</label>
                      <input type="text" class="form-control" name="unit" placeholder="Enter unit name">
                    </div>
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                      <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                  </div>
                </form>
            </div>
      </div>
        <!-- Category Table -->
      <div class="col-md-8">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Unit List</h3>
          </div>
          <!-- /.box-header -->
           <div class="box-body" style="overflow-x:auto;">
            <table class="table table-striped" id="myUnits">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Unit Name</th>
                      </tr>
                        <th>Action</th>
                </thead>
                <tbody>
                <?php
                $no = 1;
                $select = $pdo->prepare('SELECT * FROM tbl_unit');
                $select->execute();
                while($row=$select->fetch(PDO::FETCH_OBJ)){ ?>
                  <tr>
                    <td><?php echo $no ++ ?></td>
                    <td><?php echo $row->unit_name; ?></td>
                    <td>
                        <a href="edit_unit?id=<?php echo $row->unit_id; ?>"
                        class="btn btn-info btn-sm" name="btn_edit"><i class="fa fa-pencil"></i></a>
                        <a href="delete_unit?id=<?php echo $row->unit_id; ?>"
                        onclick="return confirm('Delete this unit?')"
                        class="btn btn-danger btn-sm" name="btn_delete"><i class="fa fa-trash"></i></a>
                    </td>
                  </tr>
                <?php
                }
                ?>

                </tbody>
            </table>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>


    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- DataTables Function -->
  <script>
  $(document).ready( function () {
      $('#myUnits').DataTable();
  } );
  </script>

<?php
  include_once'inc/footer_all.php';
?>