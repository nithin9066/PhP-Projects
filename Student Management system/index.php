<?php
include "database.php";
include "sms.php";
session_start();
if(isset($_GET['z']))
{
  session_destroy();
}
if (isset($_POST['recover'])) {
  $n = $_POST['phone'];
  $tvalid = result("select * from teachers where phone = '$n'",1);
  $tv = mysqli_num_rows($tvalid);
  $svalid = result("select * from students where phone = '$n'",1);
  $sv = mysqli_num_rows($svalid);
  if($tv != 0)
  {
    while($d = mysqli_fetch_assoc($tvalid))
    {
      $u = $d['username'];
      $p = $d['password'];
      $nn= $d['phone'];
      $m = "Hi mam/sir,\nYour username: $u\n password: $p\n\n Thank You.....";
      send($nn,$m);
    }
  }
  elseif($sv != 0)
  {
    while($d = mysqli_fetch_assoc($svalid))
    {
      $u = $d['username'];
      $p = $d['password'];
      $name = $d['name'];
      $m = "Hi $name,\nYour username: $u\n password: $p\n\n Thank You.....";
      send($n,$m);
    }
  }
  else
  {
    echo "<script>alert('Invalid Phone Number')</script>";
  }
}
elseif (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['pass'];
  $rr = result("select * from students where username='$username' and password = '$password' and verify = 0",1);
  $ch = mysqli_num_rows($rr);
  $rr2 = result("select * from students where username='$username' and password = '$password' and verify = 2",1);
  $ch2 = mysqli_num_rows($rr2);
  $t = result("select * from teachers where username='$username' and password = '$password'",1);
  $tch = mysqli_num_rows($t);
  $admin = result("select * from admin where username='$username' and password = '$password'",1);
  $ad = mysqli_num_rows($admin);
  if($ad == 1)
  {
    $_SESSION['ausername'] = $username;
    header('Location: http://localhost:8081/Prance/Home.php');

  }
  elseif($ch == 1){
    echo "<div class='alert alert-warning' role='alert'>Aproval Pending</div>";
  }
  else if($ch2 == 1)
  {
    echo "<div class='alert alert-danger' role='alert'>Your Request Rejected Please re-register with valid details</div>";

  }
  else {
  $r = result("select * from students where username='$username' and password = '$password';",1);
  $check = mysqli_num_rows($r);
  if ($check == 1) {
    $_SESSION['susername'] = $username;
    header('Location: http://localhost:8081/Prance/Shome.php');
  }
  elseif ($tch == 1)
  {
    $_SESSION['tusername'] = $username;
    header('Location: http://localhost:8081/Prance/teacher.php');

  }
  else {
    echo "<div class='alert alert-warning' role='alert'>Error! Invalid Username or Password!</div>";
  }
}
} else if (isset($_POST['reg'])) {


  $email = $_POST["mail"];
  $password = $_POST["pass"];
  $password2 = $_POST["pass2"];
  $phone = $_POST['phone'];
  $parentPhone = $_POST['pphone'];
  $name = $_POST['name'];
  $usn = $_POST['usn'];
  $sem = $_POST['sem'];
  $username = $_POST['username'];
  $v = '0';
  $a = result("select usn from students where email = '$email'",2);
   if(count($a) > 0){
  $ch = result("select usn from students where usn = '$a[0]' and verify = 2",1);
  $c = mysqli_num_rows($ch);
  if($c == 1){
  while($z = mysqli_fetch_assoc($ch))
  {   
      $u = $z['usn'];
      result("delete from students where usn = '$u'",1);
  }
  }
}
  if ($password == $password2) {
    $r = result("select * from students where usn='$usn';",1);
    $check = mysqli_num_rows($r);
    if ($check == 0) {
    result("INSERT INTO students(email, name, usn, sem, phone, parentphone, username, password, verify) VALUES('$email', '$name', '$usn', '$sem', '$phone', '$parentPhone', '$username', '$password', '$v')",1);
    echo "<div class='alert alert-success' role='alert'>successfully Registered You can't login until admin will approve....</div>";
    } else if ($check == 1) {
      echo "<div class='alert alert-warning' role='alert'>Error! Email  Already Registered please Login</div>";
    }
  } else if ($password != $password2) {
    echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>Error! Password mismatch   <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
  }
}

?>
<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
  <script src="jquery.js"></script>
  <title>Student managment system</title>
</head>

<body>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
<!--- Login -->
  <div class="a">
    <form class="container col-lg-5 col-offset-6" action="index.php" method="POST">
      <h1 style="margin-top: 60px;">Login</h1>
      <input style="display: none;" name="login" />
      <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label"></label>
        <input type="text" class="form-control" required name="username" id="exampleInputEmail1" aria-describedby="emailHelp">
        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
      </div>
      <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">Password</label>
        <input type="password" class="form-control" required name="pass" id="exampleInputPassword1">
      </div>
      <button type="submit" class="btn btn-primary">Login</button><label id="fp" class="m-2">Forgotten Password? ClickHere</label><br><br>
      <label id="new">New User? ClickHere</label>
    </form>
  </div>

<!--- Forgotten Password -->

<div style="display: none; margin-top: 10%;" class="c">
    <form  class="container col-lg-4 col-offset-6" action="index.php" method="POST">
      <h1 style="margin-top: 60px;">Recover Password</h1>
      <div class="mb-3">
        <input type="tel" class="form-control" placeholder="Enter Phone Number" required name="phone">
      </div>
      
      <button type="submit" class="btn btn-primary" name="recover">Recover</button>
    </form>
  </div>

<!--- Register -->

  <div style="display: none; padding-bottom: 10px;" class="b">
    <form class="container col-lg-5 col-offset-6" action="index.php" method="POST">
      <input style="display: none;" name="reg" />
      <h1 style="margin-top: 0px;">Register</h1>
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
        <label class="form-label">USN</label>
        <input type="text" class="form-control" name="usn" required>
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
      
      <div class=" row mb-3">
        <div class="col">
          <label class="form-label">Phone_No</label>
          <input type="tel" class="form-control" name="phone" required>
        </div>
        <div class="col">
          <label class="form-label">Parent Phone_No</label>
          <input type="tel" class="form-control" name="pphone" required>
        </div>
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
      <button type="submit" class="btn btn-primary">Register</button>
    </form>
  </div>
  <script>
    $("#new").click(function() {
      $('.a, .c').fadeOut(500, function() {
        $('.b').fadeIn(500);
      });
    })
    $("#fp").click(function() {
      $('.a, .b').fadeOut(500, function() {
        $('.c').fadeIn(500);
      });
    })
  </script>
</body>

</html>
