


<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <script src="jquery.js"></script>
    <title></title>
</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link active" id='v' aria-current="page" href="#">Verify Request</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" id='at' aria-current="page" href="#">Add Teacher</a>
                    </li>

                    </li>
                </ul>
                <form class="d-flex">
                    <a href="index.php?z=0" class="btn btn-outline-success">logout</a>
                </form>
            </div>
        </div>
    </nav>
    <?php
include "database.php";
session_start();
if(isset($_SESSION['ausername']))
{
if(isset($_POST['add']))
{
  $email = $_POST["mail"];
  $name = $_POST['name'];
  $subject = $_POST['sub'];
  $sem = $_POST['sem'];
  $phone = $_POST['phone'];
  $username = $_POST['username'];
  $password = $_POST["pass"];
  $password2 = $_POST["pass2"];

  if ($password == $password2) { 
    $r = result("select * from teachers where email='$email';",1);
    $check = mysqli_num_rows($r);
    if ($check != 1) {
      result("INSERT INTO teachers(email, name, subject, sem, phone, username, password) VALUES('$email', '$name', '$subject', '$sem', '$phone', '$username', '$password')",1);
      echo "<div class='alert alert-success' role='alert'>successfully Registered</div>";
      include "sms.php";
      $m = "Hi Mam/Sir,\nYour Login Details of MSEC Teacher Portal are:\nusername: $username \npassword: $password \n\n Thank You....";
      send($phone,$m);
    } else if ($check == 1) {
      echo "<div class='alert alert-warning' role='alert'>Error! Email  Already Registered please Login</div>";
    }
  } else if ($password != $password2) {
    echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>Error! Password mismatch   <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
  }
}
elseif(isset($_POST['a']))
{
  $usn = $_POST['a'];
  result("update students set verify = 1 where usn = '$usn'",1);
}
elseif(isset($_POST['r']))
{
  $usn = $_POST['r'];
  result("update students set verify = 2 where usn = '$usn'",1);
}
?>
    <form action="Home.php" method="POST">
      <div class="table-responsive ">
      <?php 
      if(isset($_POST['a']) || isset($_POST['r']))
      { ?>
        <table id='t' class="table"> <?php 

      } else
            { ?>
              <table id='t' style="display: none;" class="table">

      <?php } ?>
            <thead>
                <tr>
                    <th scope="col">Email</th>
                    <th scope="col">Name</th>
                    <th scope="col">USN</th>
                    <th scope="col">SEM</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Parent Phone</th>
                    <th scope="col">Verify</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $r = result('select * from students where verify = 0',1);
                while ($d = mysqli_fetch_assoc($r)) {
                ?>
                    <tr>
                        <td><?php echo $d['email'] ?></td>
                        <td><?php echo $d['name'] ?></td>
                        <td><?php echo $d['usn'] ?></td>
                        <td><?php echo $d['sem'] ?></td>
                        <td><?php echo $d['phone'] ?></td>
                        <td><?php echo $d['parentphone'] ?></td>
                        <td><button class="btn btn-success m-1" name="a" value=<?php echo $d['usn'] ?>>Approve</button><button class="btn btn-danger" name="r" value=<?php echo $d['usn'] ?>>Reject</button></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
                </div>
    </form>

      <?php
      if(isset($_POST['add']))
      {
        ?>    <div style="padding-bottom: 10px;" class="b">

      <?php } else {
      ?>
    <div style="display: none; padding-bottom: 10px;" class="b">
      <?php } ?>
    <form class="container col-lg-5 col-offset-6" action="Home.php" method="POST">
      <input style="display: none;" name="reg" />
      <h1 style="margin-top: 0px;">Add Teacher</h1>
      <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Email address</label>
        <input type="email" class="form-control" name="mail" id="exampleInputEmail1" aria-describedby="emailHelp" required>
        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
      </div>
      <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" class="form-control" name="name" required>
      </div>
      <div class="row mb-3">
        <div class="col">
        <label class="form-label">Subject</label>
        <input type="text" class="form-control" name="sub" required>
        </div>
        <div class="col mb-3">
        <label class="form-label">SEM</label>
        <select class="form-control" name="sem">
          <option selected>Choose...</option>
          <option value="SEM 1">SEM 1</option>
          <option value="SEM 2">SEM 2</option>
          <option value="SEM 3">SEM 3</option>
          <option value="SEM 4">SEM 4</option>
          <option value="SEM 5">SEM 5</option>
          <option value="SEM 6">SEM 6</option>
          <option value="SEM 7">SEM 7</option>
          <option value="SEM 8">SEM 8</option>
        </select>
      </div>
      </div>
      
      <div class="mb-3">
          <label class="form-label">Phone_No</label>
          <input type="tel" class="form-control" name="phone" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" class="form-control" name="username" required>
      </div>
      <div class="row mb-3">
        <div class="col">
          <label for="exampleInputPassword1" class="form-label">Password</label>
          <input type="password" class="form-control" name="pass" id="exampleInputPassword1" required>
        </div>
        <div class="col">
          <label for="exampleInputPassword1" class="form-label">Confirm Password</label>
          <input type="password" class="form-control" name="pass2" id="exampleInputPassword1" required>
        </div>
      </div>
      <button type="submit" class="btn btn-primary" name="add" >Add</button>
    </form>
  </div>
    

<script>
    $('#v').click(function (){
        $('.b').fadeOut(500, function (){
            $('#t').fadeIn(500)
        });
    });
    $('#at').click(function (){
        $('#t').fadeOut(500, function (){
            $('.b').fadeToggle(500)
        });
    });
</script>
</body>

</html>
<?php
} else
    {
        header('Location: http://localhost:8081/Prance');
    } 
    ?>