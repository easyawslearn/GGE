<?php
session_start();

if (isset($_SESSION['username']) && isset($_SESSION['password'])) {

  require_once("connection.php");

  $ps_id = mysqli_real_escape_string($con, $_GET['polling_station_id']);

  $query = $con->prepare("select ps_name,c_id from polling_station where ps_id= ? and is_deleted = false");
  $query->bind_param("i", $ps_id);
  $query->execute();

  $polling_station = $query->get_result();
  $polling_station = $polling_station->fetch_assoc();

  $query = $con->prepare("select c_name,r_id from constituency where c_id= ? and is_deleted = false");
  $query->bind_param("i", $polling_station['c_id']);
  $query->execute();

  $constituency = $query->get_result();
  $constituency = $constituency->fetch_assoc();

  $query = $con->prepare("select r_name from region where r_id= ? and is_deleted = false");
  $query->bind_param("i", $constituency['r_id']);
  $query->execute();

  $region = $query->get_result();
  $region = $region->fetch_assoc();

  $query = $con->prepare("SELECT p_id,p_name FROM party WHERE is_deleted=false");
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
            <h3 class="card-title p-2">Party</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-default">
                <i class="fas fa-plus"></i>
                &nbsp;&nbsp;Add
              </button>
            </div>
          </div>
          <div class="card-body" style="display: block">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Region: <?php echo $region['r_name']; ?> &nbsp; Constituency: <?php echo $constituency['c_name']; ?> &nbsp; Polling Station: <?php echo $polling_station['ps_name'] ?></h3>
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
                      <th>Party</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <?php
                      $number = 1;
                      while ($row = mysqli_fetch_assoc($result)) {
                        $partyId = $row['p_id'];

                        // Check if the party is in the ps_party table with the polling station foreign key
                        $stmt = $con->prepare("SELECT * FROM ps_party WHERE p_id = ? AND ps_id = ? AND is_deleted = false");
                        $stmt->bind_param("ii", $partyId, $ps_id); // replace $polling_station_id with your polling station id
                        $stmt->execute();
                        $ps_party_result = $stmt->get_result();

                        // If the party is in the ps_party table, set the checkbox to be checked
                        $isChecked = $ps_party_result->num_rows > 0 ? "checked" : "";
                      ?>
                    <tr>
                      <td><?php echo $number; ?></td>
                      <td><?php echo $row['p_name']; ?></td>
                      <td>&nbsp;&nbsp;&nbsp;
                        <input type='checkbox' id='party' value='<?php echo $partyId; ?>' <?php echo $isChecked; ?>>
                        <input type="hidden" name="ps_id" id="ps_id" value=<?php echo $ps_id; ?>>
                      </td>
                    </tr>
                  <?php
                        $number++;
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


    <!-- Checkbox function -->
    <script>
      $(document).ready(function() {
        $('input[type="checkbox"]').change(function() {
          var isChecked = $(this).is(':checked');
          var partyId = $(this).val();
          var polling_station = $(ps_id).val();

          $.ajax({
            url: 'handle_checkbox.php',
            type: 'post',
            data: {
              'isChecked': isChecked,
              'partyId': partyId,
              'ps_id': polling_station
            },
            success: function(response) {
              
            }
          });
        });
      });
    </script>



    <!-- Search -->
    <script>
      function search() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("search");
        filter = input.value.toUpperCase();
        table = document.getElementById("main_table");
        tr = table.getElementsByTagName("tr");

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