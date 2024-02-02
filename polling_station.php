<?php
require_once("connection.php");

$query = "select PS.ps_id,PS.ps_name,C.c_name,R.r_name from polling_station as PS inner join constituencies as C on PS.c_id=C.c_id inner join region as R on C.r_id=R.r_id where PS.is_deleted=false";

$result = mysqli_query($con, $query);
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
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4" style="position: fixed;">
      <!-- Brand Logo -->
      <a href="home.html" class="brand-link">
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
                <p>Reigon</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="constituencies.php" class="nav-link">
                <i class="nav-icon fas fa-map-marker"></i>
                <p>Constituency</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="polling_station.php" class="nav-link active">
                <i class="nav-icon fas fa-building"></i>
                <p>Polling station</p>
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
          <h3 class="card-title p-2">Polling stations</h3>
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
                <h4 class="modal-title">Add new polling station</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="" method="POST" class="user_form">
                  <div class="card-body">

                    <div class="form-group">
                      <label for="region">Region</label>
                      <select name="region_select" id="region" class="custom-select" onchange="updateConstituencies('add')">
                        <?php
                        $stmt = $con->prepare("SELECT r_id, r_name FROM region WHERE is_deleted=false ORDER BY r_name ASC");
                        $stmt->execute();
                        $region = $stmt->get_result();
                        while ($row = $region->fetch_assoc()) {
                        ?>
                          <option value="<?php echo $row['r_id']; ?>">
                            <?php echo $row['r_name']; ?>
                          </option>
                        <?php
                        }
                        ?>
                      </select>
                    </div>

                    <div class="form-group">
                      <label for="constituency">Constituency</label>
                      <select name="constituency_select" id="constituency" class="custom-select">
                        <!-- Options will be populated by JavaScript -->
                      </select>
                    </div>

                    <div class="form-group">
                      <label for="PS">Polling station</label>
                      <input type="text" name="PS" class="form-control" id="PS" placeholder="Enter name of polling station" required>
                    </div>
                  </div>
                  <button id="submit-add" type="submit" name="submit" class="btn btn-primary">Submit</button>
                </form>
                <?php
                if (isset($_POST['submit'])) {
                  $ps_name = $_POST['PS'];
                  $con_id = $_POST['constituency_select'];

                  $ins = "INSERT INTO polling_station (ps_name,c_id) VALUES ('$ps_name',$con_id)";

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

        <!-- Edit data modal -->

        <div class="modal fade" id="modal-edit">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Edit polling station</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="" method="POST" class="user_form">
                  <div class="card-body">
                    <div class="form-group">
                      <label for="Eregion">Region</label>
                      <select name="Eregion_select" id="region-edit" class="custom-select" onchange="updateConstituencies('edit')">
                        <?php
                        $stmt = $con->prepare("SELECT r_id, r_name FROM region WHERE is_deleted=false ORDER BY r_name ASC");
                        $stmt->execute();
                        $region = $stmt->get_result();
                        while ($row = $region->fetch_assoc()) {
                        ?>
                          <option value="<?php echo $row['r_id']; ?>">
                            <?php echo $row['r_name']; ?>
                          </option>
                        <?php
                        }
                        ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="Econstituency">Constituency</label>
                      <select name="Econstituency_select" id="constituency-edit" class="custom-select">
                        <!-- Options will be populated by JavaScript -->
                      </select>
                    </div>
                    <div class="form-group">
                      <input type="hidden" name="Eps_id" id="Eps_id">
                      <label for="EPS">Polling Station</label>
                      <input type="text" name="EPS" class="form-control" id="EPS" placeholder="Enter name of polling station" required>
                    </div>
                  </div>
                  <button type="submit" name="edit_submit" id="submit-edit" class="btn btn-primary">Update</button>
                </form>
                <?php
                if (isset($_POST['edit_submit'])) {
                  $ps_id = $_POST['Eps_id'];
                  $ps_name = $_POST['EPS'];
                  $con_id = $_POST['Econstituency_select'];

                  $upd = "UPDATE polling_station SET ps_name='$ps_name',c_id='$con_id' WHERE ps_id = $ps_id";

                  $run = mysqli_query($con, $upd);

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
                    <th>Polling station</th>
                    <th>Constituency</th>
                    <th>Region</th>
                    <th style="text-align: end;padding-right:40px;">Actions</th>
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
                        <?php echo $row['c_name']; ?>
                      </td>
                      <td>
                        <?php echo $row['r_name']; ?>
                      </td>
                      <td style="text-align: end;">
                        <?php
                        $id = $row['ps_id'];
                        echo "<button type='button' class='btn btn-success edit-button' data-id=$id>
                      <i class='fas fa-pencil-alt'></i>
                    </button> &nbsp;&nbsp;";
                        echo "<button type='button' class='btn btn-danger delete-button' data-id=$id>
                      <i class='fas fa-trash'></i>
                    </button>";
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
      // window.location.reload(true);
      setTimeout(window.location.reload(true), 1000);

    });
  </script>

  <!-- Constituency fetcher -->
  <script>
    function updateConstituencies(call = 'add') {
      let regionId;
      if (call == "add") {
        regionId = document.getElementById('region').value;
      } else if (call == "edit") {
        regionId = document.getElementById('region-edit').value;
      }

      let xhr = new XMLHttpRequest();
      xhr.open('GET', 'select_menu_data.php?region_id=' + regionId, true);
      xhr.onload = function() {
        if (this.status == 200) {
          let selectId = (call == "add") ? 'constituency' : 'constituency-edit';
          if (this.responseText.trim() === '') {
            document.getElementById(selectId).innerHTML = '<option>No constituencies found</option>';
            document.getElementById('submit-' + call).disabled = true;
          } else {
            document.getElementById('submit-' + call).disabled = false;
            document.getElementById(selectId).innerHTML = this.responseText;
          }
        }
      }
      xhr.send();
    }
    window.onload = updateConstituencies('add');
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
          $("#EPS").val(data.ps_name);


          $("#region-edit option").each(function() {
            if ($(this).val() == data.r_id) {
              $(this).prop('selected', true);
            } else {
              $(this).prop('selected', false);
            }
          });

          updateConstituencies('edit');

          $("#Econstituency-edit option").each(function() {
            if ($(this).val() == data.c_id) {
              $(this).prop('selected', true);
            } else {
              $(this).prop('selected', false);
            }
          });

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