<?php
include "database.php";
session_start();

if(isset($_SESSION['tusername']))
{
$username = $_SESSION['tusername'];
$t = result("select subject, sem from teachers where username = '$username'", 2);
$_SESSION['t'] = $t;
if (isset($_POST['attendance'])) {
    $date = date('d/m/y');
    $p = $_POST['p'];
    $c = result("select * from attendance where period = '$p' and date = '$date' and sem = '$t[1]'",1);
    $ch = mysqli_num_rows($c);
    if($ch == 0)
    {
        $r = result("select usn from students where verify = 1 and sem = '$t[1]'", 1);

        while ($d = mysqli_fetch_assoc($r)) {
            $usn = $d['usn'];
            $s = result("select name, usn, sem from students where usn = '$usn'", 2);
            if (isset($_POST[$usn])) {
                result("insert into attendance(name,usn,sem,subject,period,date,attendance) values('$s[0]','$s[1]','$s[2]','$t[0]','$p','$date','P')", 1);
            } elseif (!isset($_POST[$usn])) {
                result("insert into attendance(name,usn,sem,subject,period,date,attendance) values('$s[0]','$s[1]','$s[2]','$t[0]','$p','$date','A')", 1);
            }
        }
    }elseif($ch >=1)
    {
        echo "<script>alert('attendance already taken for this peroid')</script>";
    }
} elseif (isset($_POST['internals'])) {
    $in = $_POST['i'];
    $r = result("select usn from students where sem = '$t[1]'", 1);
    $c = result("select * from internals where internal = '$in' and sem = '$t[1]'",1);
    $ch = mysqli_num_rows($c);
    while ($d = mysqli_fetch_assoc($r)) {
        $usn = $d['usn'];
        $p = 0;
        if($ch == 0){
            $s = result("select name, usn, sem from students where usn = '$usn'", 2);
            if (isset($_POST[$usn])) {
                $marks = $_POST[$usn];
                result("insert into internals(name,usn,sem,subject,internal,marks) values('$s[0]','$s[1]','$s[2]','$t[0]','$in','$marks')", 1);
                $p = 1;
            }
        }
    }
    if($p == 1)
    {
        echo "<script>alert('Successfully Inserted')</script>";

    }
    if($ch>0)
    {
      
        echo "<script>alert('Internal marks already entered')</script>";

    }
} elseif (isset($_POST['atupdate'])) {
    $s = result("select * from students where sem = '$t[1]'", 1);
    while ($d = mysqli_fetch_assoc($s)) {
        $usn = $d['usn'];
        $date = $_SESSION['date'];
        $p = $_SESSION['p'];
        if (isset($_POST[$d['usn']])) {

            result("update attendance set attendance = 'P' where usn = '$usn' and date = '$date' and subject = '$t[0]' and period = '$p'", 1);
        } else if (!isset($_POST[$d['usn']])) {
            result("update attendance set attendance = 'A' where usn = '$usn' and date = '$date' and subject = '$t[0]' and period = '$p'", 1);
        }
    }
} elseif (isset($_POST['updateinternals'])) {
    $in = $_SESSION['in'];
    $s = result("select * from internals where internal = '$in' and subject = '$t[0]' and sem = '$t[1]'", 1);
    while ($d = mysqli_fetch_assoc($s)) {
        $usn = $d['usn'];
        $test = 0;
        if (isset($_POST[$d['usn']])) {
            $m = $_POST[$d['usn']];
            result("update internals set marks = '$m' where usn = '$usn' and subject = '$t[0]' and internal = '$in'", 1);
            $test = 1;
        }
    }
    if($test == 1)
    {
        echo "<script>alert('Successfully Updated')</script>";

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <title></title>
</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Teacher</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="a" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Attendance
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a id='at' class="dropdown-item" href="#">Take Attendance</a>
                            <a id='vt' class="dropdown-item" href="#">View Attendance</a>
                            <a id='ut' class="dropdown-item" href="#">Update Attendance</a>
                            <div class="dropdown-divider"></div>
                            <a id='ap' class="dropdown-item" href="#">Attendance Percent</a>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a id='in' class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Internals
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a id='v' class="dropdown-item" href="#">Add Internals Marks</a>
                            <a id='v2' class="dropdown-item" href="#">View Internals Marks</a>
                            <a id='v3' class="dropdown-item" href="#">Update Internals Marks</a>


                    </li>

                    </li>
                </ul>
                <form class="d-flex">
                    <a href="index.php?z=0" class="btn btn-outline-success">logout</a>
                </form>
            </div>
        </div>
    </nav>

    <!-- ta -->

    <div id='t' class="table-responsive" style="display: none;">
        <table class="table table-bordered">
            <form action="teacher.php" method="POST">
                <select class="form-control" name="p">
                    <option selected>Select Period...</option>
                    <option value="Period 1">Period 1</option>
                    <option value="Period 2">Period 2</option>
                    <option value="Period 3">Period 3</option>
                    <option value="Period 4">Period 4</option>
                    <option value="Period 5">Period 5</option>
                    <option value="Period 6">Period 6</option>
                    <option value="Period 7">Period 7</option>
                </select>
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">USN</th>
                        <th scope="col">SEM</th>
                        <th scope="col">Subject</th>
                        <th scope="col">Attendance</th>
                    </tr>
                </thead>

                <tbody>
                    <?php

                    $r = result("select * from students where verify = 1 and sem = '$t[1]'", 1);
                    while ($d = mysqli_fetch_assoc($r)) {
                    ?>
                        <tr>

                            <td><?php echo $d['name'] ?></td>
                            <td><?php echo $d['usn'] ?></td>
                            <td><?php echo $d['sem'] ?></td>
                            <td><?php echo $t[0] ?></td>

                            <td>
                                <div>
                                    <input class="form-check-input" type="checkbox" id="checkboxNoLabel" name=<?php echo $d['usn'] ?> value=<?php echo $d['usn'] ?> checked aria-label="">
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>

        </table>
        <button style=" margin-left: 40%;" class="btn btn-info col-md-2 text-center" name="attendance"><b>submit</b></button>
    </div>


    </form>





    <!-- va -->
    <div class="table-responsive">
        <form id='d' style="display: none;" action="viewAttendance.php" method="POST">
            <div style="margin-left: 33.5%; padding-top: 10%;">
                <label class="form-label">Enter Date</label>
                <input style="width: 400px;" placeholder="DD/MM/YY" type="text" name="date" class="form-control mb-2 text-center">
                <button style="display: block; margin-left: 13%" class="btn btn-info col-md-2 text-center" name="va"><b>submit</b></button>
            </div>
        </form>
    </div>

    <!--data to Update attendance -->
    <div class="table-responsive">
 <form id="uat" style="display: none; width: 600px; margin-top: 10%;" class="container" action="viewAttendance.php" method="POST">
 <label class="form-label">Enter Below</label>
    <div>
        <input required class="form-control" type="text" name='date' placeholder="Enter in DD/MM/YY format only....">
    </div>
    <select class="form-control mt-2 mb-2" name="p">
        <option selected>Select Period...</option>
        <option value="Period 1">Period 1</option>
        <option value="Period 2">Period 2</option>
        <option value="Period 3">Period 3</option>
        <option value="Period 4">Period 4</option>
        <option value="Period 5">Period 5</option>
        <option value="Period 6">Period 6</option>
        <option value="Period 7">Period 7</option>
    </select>
    <button style=" margin-left: 30%" class="btn btn-info col-md-4 text-center" name="aupdate"><b>next</b></button>

 </form>
    </div>

    <!-- Select internals to Update -->
    <?php if (!isset($_POST['next'])) { ?>
        <div class="table-responsive">
            <form id='up' style="display: none;" action="UpdateInternals.php" method="POST">
                <div style="margin-left: 33.5%; padding-top: 10%;">
                    <label class="form-label">Select internal</label>
                    <select style="width: 400px;" class="form-control mb-1" name='next'>
                        <option value='null' selected>Select internal...</option>
                        <option value="Internal 1">Internal 1</option>
                        <option value="Internal 2">Internal 2</option>
                        <option value="Internal 3">Internal 3</option>

                    </select>
                    <button style=" margin-left: 13%" class="btn btn-info col-md-2 text-center" name=""><b>next</b></button>
                </div>
            </form>
        </div>
    <?php } ?>

    <!-- add internals -->

    <div id='b' class="table-responsive" style="display: none;">
        <table class="table table-bordered">
            <form action="teacher.php" method="POST">
                <select class="form-control" name='i'>
                    <option value='null' selected>Select internal...</option>
                    <option value="Internal 1">Internal 1</option>
                    <option value="Internal 2">Internal 2</option>
                    <option value="Internal 3">Internal 3</option>

                </select>
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">USN</th>
                        <th scope="col">SEM</th>
                        <th scope="col">Subject</th>
                        <th scope="col">Internal Marks out of 30</th>
                    </tr>
                </thead>

                <tbody>
                    <?php

                    $r = result("select * from students where verify = 1 and sem = '$t[1]'", 1);
                    while ($d = mysqli_fetch_assoc($r)) {
                    ?>
                        <tr>

                            <td><?php echo $d['name'] ?></td>
                            <td><?php echo $d['usn'] ?></td>
                            <td><?php echo $d['sem'] ?></td>
                            <td><?php echo $t[0] ?></td>

                            <td>
                                <div>
                                    <input style="width: 120px;" required class="form-control" type="text" name=<?php echo $d['usn'] ?> placeholder="Enter marks">
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>

        </table>
        <button style=" margin-left: 40%;" class="btn btn-info col-md-2 text-center" name="internals"><b>submit</b></button>
    </div>



    <!-- View internals -->
    <div id="iv" style="display: none;" class="table-responsive">
        <?php
        $i = 1;
        while ($i <= 3) {
        ?>

            <table class="table table sm">
                <thead>
                    <tr>
                        <th style="text-align: center;" colspan="7"><?php echo 'INTERNAL ' . $i; ?></th>
                    </tr>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">USN</th>
                        <th scope="col">SEM</th>
                        <th scope="col">Subject</th>
                        <th scope="col">Internal</th>
                        <th scope="col">OBTAINED MARKS</th>
                        <th scope="col">TOTAL MARKS</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $a = 'Internal ' . $i;
                    $r = result("select * from internals where internal = '$a' and subject = '$t[0]'", 1);
                    while ($d = mysqli_fetch_assoc($r)) {
                    ?>
                        <tr>

                            <td><?php echo $d['name'] ?></td>
                            <td><?php echo $d['usn'] ?></td>
                            <td><?php echo $d['sem'] ?></td>
                            <td><?php echo $d['subject'] ?></td>
                            <td><?php echo $d['internal'] ?></td>
                            <td><?php echo $d['marks'] ?></td>
                            <td>30</td>

                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        <?php $i++;
        } ?>
    </div>


    <!-- Attendance percentage -->

    <div id='p' class="table-responsive" style="display: none;">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">USN</th>
                    <th scope="col">SEM</th>
                    <th scope="col">Subject</th>
                    <th scope="col">Attendance</th>
                </tr>
            </thead>

            <tbody>
                <?php


                $r = result("select distinct(name),usn,sem,subject from attendance where subject = '$t[0]' and sem = '$t[1]'", 1);
                while ($d = mysqli_fetch_assoc($r)) {
                    $usn = $d['usn'];
                    $r1 = result("select usn from attendance where subject = '$t[0]' and usn = '$usn'", 1);
                    $total = mysqli_num_rows($r1);
                    $r2 = result("select usn from attendance where subject = '$t[0]' and usn = '$usn' and attendance = 'P'", 1);
                    $presnt = mysqli_num_rows($r2);
                    $avg = ($presnt / $total) * 100;
                ?>
                    <tr>

                        <td><?php echo $d['name'] ?></td>
                        <td><?php echo $d['usn'] ?></td>
                        <td><?php echo $d['sem'] ?></td>
                        <td><?php echo $d['subject'] ?></td>
                        <td><?php echo $avg ?>%</td>

                    </tr>
                <?php } ?>
            </tbody>

        </table>
    </div>
    </form>

   
    <script>
        $('#at').click(function() {

            $('#t').fadeToggle(500)

        });
        $('#v').click(function() {

            $('#b').fadeToggle(500)

        });

        $('#v2').click(function() {

            $('#iv').fadeToggle(500)

        });

        $('#vt').click(function() {

            $('#d').fadeToggle(500)

        });
        $('#ap').click(function() {

            $('#p').fadeToggle(500)

        });
        $('#ut').click(function() {

            $('#uat').fadeToggle(500)

        });
        $('#v3').click(function() {

            $('#up').fadeToggle(500)

        });
        $('#in, #a').click(function() {
            $('#d, #t, #b, #iv, #p, #uat').fadeOut(500)
        });
    </script>
</body>

</html>

    <?php } else
    {
        header('Location: http://localhost:8081/Prance/');
    } 
    ?>