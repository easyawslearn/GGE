<?php
session_start();

if (isset($_SESSION['username']) && isset($_SESSION['password'])) {

  require_once("connection.php");

  $constituency_id = mysqli_real_escape_string($con, $_GET['constituency_id']);

  $query = $con->prepare("select c_name,r_id from constituency where c_id= ? and is_deleted = false");
  $query->bind_param("i", $constituency_id);
  $query->execute();

  $constituency = $query->get_result();
  $constituency = $constituency->fetch_assoc();

  $query = $con->prepare("select r_name from region where r_id= ? and is_deleted = false");
  $query->bind_param("i", $constituency['r_id']);
  $query->execute();

  $region_name = $query->get_result();
  $region_name = $region_name->fetch_assoc()['r_name'];

  $query = $con->prepare("select PS.ps_id,PS.ps_name,U.username from polling_station as PS left join user as U on PS.u_id=U.u_id where PS.c_id=? and PS.is_deleted=false");
  $query->bind_param("i", $constituency_id);
  $query->execute();
  $result = $query->get_result();
?>

  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>GGE | Admin panel</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css" />
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css" />
  </head>

  <body class="hold-transition sidebar-mini">
    <div class="wrapper">
      <!-- Navbar -->
      <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
          </li>
        </ul>
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a href="logout.php"><button class='btn btn-danger end' type="button" name="logout">Logout</button></a>
          </li>
        </ul>
      </nav>
      <!-- /.navbar -->

      <!-- Main Sidebar Container -->
      <aside class="main-sidebar sidebar-dark-primary elevation-4" style="position: fixed;">
        <!-- Brand Logo -->
        <a href="home.php" class="brand-link">
          <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: 0.8" />
          <span class="brand-text font-weight-light">GGE</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
          <!-- Sidebar user panel (optional) -->
          <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
              <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image" />
            </div>
            <div class="info">
              <a href="#" class="d-block">Admin</a>
            </div>
          </div>
          <!-- Sidebar Menu -->
          <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
              <li class="nav-item">
                <a href="user.php" class="nav-link">
                  <i class="nav-icon fas fa-user"></i>
                  <p>User</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="region.php" class="nav-link">
                  <i class="nav-icon fas fa-map"></i>
                  <p>Region</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="party.php" class="nav-link">
                  <i class="nav-icon fas fa-users"></i>
                  <p>Party</p>
                </a>
              </li>
            </ul>
          </nav>
          <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6"></div>
              <!-- /.col -->
              <div class="col-sm-5"></div>
              <div class="col-sm-1"></div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
          </div>
          <!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title p-2">Polling Station</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-default">
                <i class="fas fa-plus"></i>
                &nbsp;&nbsp;Add
              </button>
            </div>
          </div>

          <div class="modal fade" id="modal-default">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Add Polling Station</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form action="insert.php?call=polling_station" method="POST" class="user_form">
                    <div class="card-body">
                      <div class="form-group">
                        <label for="region">Region</label>
                        <input type="text" id="region" class="form-control" name='region' value=<?php echo $region_name; ?> readonly>
                      </div>
                      <div class="form-group">
                        <label for="constituency">Constituency</label>
                        <input type="hidden" name='c_id' value=<?php echo $constituency_id; ?>>
                        <input type="text" id="constituency" class="form-control" name='constituency' value=<?php echo $constituency['c_name']; ?> readonly>
                      </div>
                      <div class="form-group">
                        <label for="polling_station">Polling Station</label>
                        <input type="text" class="form-control" name='polling_station' id="polling_station" placeholder="Polling Station">
                      </div>
                      <div class="form-group">
                        <label for="polling_agent">Polling Agent</label>
                        <select name="polling_agent" class="custom-select" id="polling_agent" required>
                          <option value="" disabled selected>Select a polling agent</option>
                          <?php
                          $pa = mysqli_query($con, "SELECT U.u_id, U.username FROM user AS U WHERE U.u_id NOT IN (SELECT PS.u_id FROM polling_station AS PS) AND U.is_deleted = false AND U.user_role = 'polling_agent' ORDER BY U.username ASC");
                          while ($row = mysqli_fetch_assoc($pa)) {
                          ?>
                            <option value="<?php echo $row['u_id']; ?>">
                              <?php echo $row['username']; ?>
                            </option>

                          <?php
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <button id="submit" type="submit" name="submit" class="btn btn-primary">Submit</button>
                  </form>
                </div>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>
          <!-- /.modal -->

          <!-- Edit data modal -->

          <div class="modal fade" id="modal-edit">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Edit Polling Station</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form action="" method="POST" class="user_form">
                    <div class="card-body">
                      <div class="form-group">
                        <label for="Eregion">Region</label>
                        <input type="text" class="form-control" id="Eregion" name='Eregion' value=<?php echo $region_name; ?> readonly>
                      </div>
                      <div class="form-group">
                        <label for="Econstituency">Constituency</label>
                        <input type="text" id="Econstituency" class="form-control" name='Econstituency' value=<?php echo $constituency['c_name']; ?> readonly>
                      </div>
                      <div class="form-group">
                        <label for="Epolling_station">Polling Station</label>
                        <input type="text" class="form-control" name='Epolling_station' id="Epolling_station" placeholder="Polling Station">
                        <input type="hidden" id="Eps_id" name="Eps_id">
                      </div>
                      <div class="form-group">
                        <label for="Epolling_agent">Polling Agent</label>
                        <select name="Epolling_agent" class="custom-select" id="Epolling_agent" required>
                          <?php
                          $pa = mysqli_query($con, "SELECT U.u_id, U.username FROM user AS U WHERE U.u_id NOT IN (SELECT PS.u_id FROM polling_station AS PS) AND U.is_deleted = false AND U.user_role = 'polling_agent' ORDER BY U.username ASC");

                          while ($row = mysqli_fetch_assoc($pa)) {
                          ?>
                            <option value="<?php echo $row['u_id']; ?>">
                              <?php echo $row['username']; ?>
                            </option>

                          <?php
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <button type="submit" name="edit_submit" class="btn btn-primary">Update</button>
                  </form>
                  <?php
                  if (isset($_POST['edit_submit'])) {
                    $ps_id = $_POST['Eps_id'];
                    $ps_name = $_POST['Epolling_station'];
                    $ps_agent = $_POST['Epolling_agent'];

                    $ins = "UPDATE polling_station SET ps_name = '$ps_name',u_id = '$ps_agent' WHERE ps_id=$ps_id";

                    $run = mysqli_query($con, $ins);

                    echo "<meta http-equiv='refresh' content='0'>";
                  }
                  ?>
                </div>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>
          <!-- /.modal -->

          <div class="card-body" style="display: block">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Region: <?php echo $region_name; ?> &nbsp; Constituency: <?php echo $constituency['c_name']; ?></h3>
                <div class="card-tools">
                  <div class="input-group input-group-sm" style="width: 150px;">
                    <input type="text" id="search" onkeyup="search()" name="table_search" class="form-control float-right" placeholder="Search">
                    <div class="input-group-append btn">
                      <i class="fas fa-search"></i>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body table-responsive p-0">
                <table class="table table-hover table-head-fixed text-nowrap" id="main_table">
                  <thead>
                    <tr>
                      <th>Number</th>
                      <th>Polling Station</th>
                      <th>Polling Agent</th>
                      <th style="text-align: end;padding-right:100px;">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <?php
                      $number = 1;
                      while ($row = mysqli_fetch_assoc($result)) {
                      ?>
                        <td>
                          <?php echo $number; ?>
                        </td>
                        <td>
                          <?php echo $row['ps_name']; ?>
                        </td>
                        <td>
                          <?php echo $row['username']; ?>
                        </td>
                        <td style="text-align: end;">
                          <?php
                          $id = $row['ps_id'];
                          echo "<button type='button' class='btn btn-success edit-button' data-id=$id>
                            <i class='fas fa-pencil-alt'></i>
                          </button> &nbsp;&nbsp;";

                          echo "<button type='button' class='btn btn-danger delete-button' data-id=$id>
                            <i class='fas fa-trash'></i>
                          </button> &nbsp;&nbsp;";

                          echo "<a href='ps_party.php?polling_station_id=$id' class='btn btn-primary polling_station-button'>Party</a>";

                          $number++;
                          ?>
                        </td>
                    </tr>
                  <?php
                      }
                  ?>
                  </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /.content-wrapper-->


      <!-- Control Sidebar -->
      <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
        <div class="p-3">
          <h5>Title</h5>
          <p>Sidebar content</p>
        </div>
      </aside>
      <!--control-sidebar -->

      <!-- Main Footer -->
      <footer class="main-footer">
        <!-- To the right -->
        <!-- Default to the left -->
        <strong>Copyright &copy; 2024 GGE
          All rights reserved.
      </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- Stop from resubmission -->
    <script>
      if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
      }
    </script>

    <!-- Delete function -->
    <script>
      $(".delete-button").click(function() {
        var id = $(this).data("id");
        var confirmed = confirm("Are you sure you want to delete this polling station? \nAll the parties belonging to this polling station will also be deleted.");
        if (confirmed) {
          $.ajax({
            url: 'delete.php',
            type: 'POST',
            data: {
              table: 'ps',
              id: id
            }
          });
        }
        setTimeout(window.location.reload(true), 1000);

      });
    </script>

    <!-- Edit function -->
    <script>
      $(".edit-button").click(function() {
        var id = $(this).data("id");
        $.ajax({
          url: 'fetch_data.php',
          type: 'POST',
          data: {
            table: 'ps',
            id: id
          },
          success: function(response) {
            var data = JSON.parse(response);

            $("#Eps_id").val(data.ps_id);
            $("#Epolling_station").val(data.ps_name);

            // $("#Epolling_agent option").each(function() {
            //   if ($(this).val() == (data.u_id)) {
            //     $(this).prop('selected', true);
            //   } else {
            //     $(this).prop('selected', false);
            //   }
            // });

            $('#Epolling_agent').append(new Option(data.username, data.u_id, true, true))

            $('#modal-edit').modal('show');
          }
        });
      });
    </script>

    <!-- Search -->
    <script>
      function search() {
        // Declare variables
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("search");
        filter = input.value.toUpperCase();
        table = document.getElementById("main_table");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
          td = tr[i].getElementsByTagName("td")[1];
          if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
              tr[i].style.display = "";
            } else {
              tr[i].style.display = "none";
            }
          }
        }
      }
    </script>


  </body>

  </html>
<?php
} else {
  header('Location: index.php');
}
?>