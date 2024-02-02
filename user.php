<?php
require_once("connection.php");

$query = "select u_id,username,user_type from user where is_deleted = false";
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
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
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
              <a href="user.php" class="nav-link active">
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
              <a href="polling_station.php" class="nav-link">
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
          <h3 class="card-title p-2">Users</h3>
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
                <h4 class="modal-title">Add new user</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="" method="POST" class="user_form">
                  <div class="card-body">
                    <div class="form-group">
                      <label for="username">Username</label>
                      <input type="text" name="username" class="form-control" id="username" placeholder="Enter username" required>
                    </div>
                    <div class="form-group">
                      <label for="password">Password</label>
                      <input type="password" name="passwd" class="form-control" id="password" placeholder="Enter password" onkeyup="validation()" required>
                      <p id="message"></p>

                    </div>
                    <div class="form-check">
                      <input type="checkbox" class="form-check-input" id="show_passwd" onclick="password_visibility()">
                      <label class="form-check-label" for="show_passwd">Show password</label>
                    </div>
                    <div class="form-group">
                      <label for="user_type">User type</label>
                      <select name="user_type" id="user_type" class="custom-select">
                        <option value="admin">Admin</option>
                        <option value="executive">Executive</option>
                        <option value="polling_agent">Polling agent</option>
                      </select>
                    </div>
                  </div>
                  <button id="submit" type="submit" name="submit" class="btn btn-primary">Submit</button>
                </form>
                <?php
                if (isset($_POST['submit'])) {
                  $username = $_POST['username'];
                  $password = $_POST['passwd'];
                  $user_type = $_POST['user_type'];

                  $ins_query = "INSERT INTO user (username,password,user_type)VALUES('$username','$password','$user_type')";

                  $ins = mysqli_query($con, $ins_query);
                  echo "<meta http-equiv='refresh' content='0'>";

                  if ($ins === false) {
                    echo "<script>alert('Could not create new user please try again.');</script>";
                  }
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
                <h4 class="modal-title">Edit user</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="" method="POST" class="user_form">
                  <div class="card-body">
                    <div class="form-group">
                      <input type="hidden" name="Eu_id" id="Eu_id">
                      <label for="Eusername">Username</label>
                      <input type="text" name="Eusername" class="form-control" id="Eusername" placeholder="Enter username" required>
                    </div>
                    <div class="form-group">
                      <label for="Epassword">Password</label>
                      <input type="password" name="Epasswd" class="form-control" id="Epassword" placeholder="Enter password" oninput="validation()" required>
                      <p id="Emessage"></p>
                    </div>
                    <div class="form-check">
                      <input type="checkbox" class="form-check-input" id="show_passwd" onclick="password_visibility()">
                      <label class="form-check-label" for="show_passwd">Show password</label>
                    </div>
                    <div class="form-group">
                      <label for="Euser_type">User type</label>
                      <select name="Euser_type" id="Euser_type" class="custom-select">
                        <option value="admin">Admin</option>
                        <option value="executive">Executive</option>
                        <option value="polling_agent">Polling agent</option>
                      </select>
                    </div>
                  </div>
                  <button type="submit" name="edit_submit" class="btn btn-primary">Update</button>
                </form>
                <?php
                if (isset($_POST['edit_submit'])) {
                  $u_id = $_POST['Eu_id'];
                  $username = $_POST['Eusername'];
                  $password = $_POST['Epasswd'];
                  $user_type = $_POST['Euser_type'];

                  $upd_query = "UPDATE user SET username='$username',password='$password',user_type='$user_type' WHERE u_id=$u_id";

                  $upd = mysqli_query($con, $upd_query);
                  echo "<meta http-equiv='refresh' content='0'>";

                  if ($upd === false) {
                    echo "<script>alert('Could not create new user please try again.');</script>";
                  }
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
                    <th>Username</th>
                    <th>User type</th>
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
                        <?php echo $row['username']; ?>
                      </td>
                      <td>
                        <?php echo $row['user_type']; ?>
                      </td>
                      <td style="text-align: end;">
                        <?php
                        $id = $row['u_id'];
                        echo "<button type='button' class='btn btn-success edit-button' data-id=$id>
                        <i class='fas fa-pencil-alt'></i>
                        </button> &nbsp;&nbsp;";

                        echo "<button type='button' class='btn btn-danger delete-button' data-id=$id><i class='fas fa-trash'></i></button>";
                        $number++;

                        if (isset($_POST['prep_del'])) {
                          $del_id = $_POST['del_id'];
                          $del_query = $con->prepare("delete from user where u_id = ?");
                          $del_query->bind_param('i', $del_id);
                        }
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
  <!-- Password visibility -->
  <script>
    function password_visibility() {
      var x = document.getElementById("password");
      if (x.type === "password") {
        x.type = "text";
      } else {
        x.type = "password";
      }

      var y = document.getElementById("Epassword");
      if (y.type === "password") {
        y.type = "text";
      } else {
        y.type = "password";
      }
    }
  </script>

  <!-- Delete function -->
  <script>
    $(".delete-button").click(function() {
      var id = $(this).data("id");
      var confirmed = confirm("Are you sure you want to delete this user?");
      if (confirmed) {
        $.ajax({
          url: 'delete.php',
          type: 'POST',
          data: {
            table: 'user',
            id: id
          }
        });
      }
      window.location.reload(true);
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
          table:'user',
          id: id
        },
        success: function(response) {
          var data = JSON.parse(response);

          $("#Eu_id").val(data.u_id);
          $("#Eusername").val(data.username);
          $("#Epassword").val(data.password);
          $("#Euser_type").val(data.user_type);

          $('#modal-edit').modal('show');
        }
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

<!-- password validation -->

<script>
  function validation() {
    let password = document.getElementById('password');
    let message = document.getElementById('message');
    let submitbtn = document.getElementById('submit');
    
    if(password.value.length >= 6) {
      message.style.color = "green";
      message.innerHTML = "Password is valid.";
      submitbtn.disabled = false;
    } else {
      message.style.color = "red";
      message.innerHTML = "Password should be 6 or more characters.";
      submitbtn.disabled = true;
    }
    
    let Epassword = document.getElementById('Epassword');
    let Emessage = document.getElementById('Emessage');
    let updatebtn = document.getElementById('Esubmit');

    if(Epassword.value.length >= 6) {
      Emessage.style.color = "green";
      Emessage.innerHTML = "Password is valid.";
      updatebtn.disabled = false;
    } else {
      Emessage.style.color = "red";
      Emessage.innerHTML = "Password should be 6 or more characters.";
      updatebtn.disabled = true;
    }
  }
</script>

</body>

</html>