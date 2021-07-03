<?php 
include "database.php";
session_start();
if(isset($_SESSION['susername']))
{
$user = $_SESSION['susername'];
$usn = result("select usn,sem from students where username = '$user'",2);
$_SESSION['usn'] = $usn;
$us =  $usn[0];

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
            <a class="navbar-brand" href="#">Student</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="Shome.php">Home</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a id="a" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Attendance
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a id='vt' class="dropdown-item" href="#">View Attendance</a>
                            <div class="dropdown-divider"></div>
                            <a id='ap' class="dropdown-item" href="#">Attendance Percent</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a id="in" class="nav-link active" aria-current="page" href="#">Internals</a>
                    </li>
                      
                </ul>
                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>
 <!-- va -->
  <div class="table-responsive">
        <form id='d' style="display: none;" action="Svalidation.php" method="POST">
            <div style="margin-left: 33.5%; padding-top: 10%;">
                <label class="form-label">Enter Date</label>
                <input style="width: 400px;" placeholder="DD/MM/YY" type="text" name="date" class="form-control mb-2 text-center">
                <select style="width: 400px;" class="form-control mb-1" name='sub'>
                <option value='null' selected>Select Subject...</option>
                <?php 
                
                    $t = result("select subject from teachers where sem = '$usn[1]'", 1);
                    while($d = mysqli_fetch_assoc($t))
                    {
                ?>
                    <option value=<?php echo $d['subject'] ?>><?php echo $d['subject'] ?></option>
                   
                    <?php } ?>
                </select>                                
                <button style="display: block; margin-left: 13%" class="btn btn-info col-md-2 text-center" name="view"><b>submit</b></button>
            </div>
        </form>
    </div>

     <!-- Attendance percentage -->

     <div id='p' class="table-responsive" style="display: none;">
        <table class="table-dark table mt-5 container">
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
                
                $s = result("select subject from teachers where sem = '$usn[1]'",1);
                $zz = mysqli_num_rows($s);
                if($zz != 0){
                
                while ($d = mysqli_fetch_assoc($s)) {
                    $sub = $d['subject'];
                    
                    
                    $r = result("select distinct(name),usn,sem from students where usn = '$us'", 2);
                    $usn = $r[1];
                    $r1 = result("select usn from attendance where subject = '$sub' and usn = '$usn'", 1);
                    $total = mysqli_num_rows($r1);
                    $r2 = result("select usn from attendance where subject = '$sub' and usn = '$usn' and attendance = 'P'", 1);
                    $presnt = mysqli_num_rows($r2);
                    if($total>0){
                    $avg = (($presnt / $total) * 100);
                    }
                    elseif($total == 0){
                        $avg = "Attendance not yet taken";
                    }
                    $r3 = result("select usn from attendance where usn = '$usn'", 1);
                    $alltotal = mysqli_num_rows($r3);
                    $r4 = result("select usn from attendance where usn = '$usn' and attendance = 'P'", 1);
                    $allpresnt = mysqli_num_rows($r4);
                    if($alltotal>0)
                    {
                    $tavg = (($allpresnt / $alltotal) * 100);
                    }
                    elseif($alltotal == 0){
                        $tavg = "Attendance not yet taken";
                    }
                ?>
                    <tr>

                        <td><?php echo $r[0] ?></td>
                        <td><?php echo $r[1] ?></td>
                        <td><?php echo $r[2] ?></td>
                        <td><?php echo $sub ?></td>
                    
                        <td><?php if($total>0){ echo $avg,"%"; }else { echo $avg; } ?></td>

                    </tr>
                   
                <?php } ?>
                <tr class="text-center">
                    <th style="font-size: large;" colspan="5">Overall <?php echo $tavg ?>%</th>
                </tr>
                <?php }
                else{
                    ?> 
                    <div id='p' class="table-responsive" style="display: none;">
                    <tr>
                    <th class="text-center" colspan="5">NO DATA FOUND</th>
                    </tr>
                    </div>
               <?php } ?>
            </tbody>

        </table>
    </div>
    </form>

 
    <div id="iv" style="display: none;" class="table-responsive">
        <?php
        $i = 1;
        while ($i <= 3) {
        ?>
            <table class="table-dark table mt-5 container">
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
                    $r = result("select * from internals where internal = '$a' and usn = '$us'", 1);
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
   

    
                <script>
                    $('#vt').click(function () {
                        $('#d').fadeToggle(500)
                    })
                    $('#ap').click(function () {
                        $('#p').fadeToggle(500)
                    })
                    $('#in').click(function () {
                        $('#iv').slideToggle(1000)
                    })
                    $('#a').click(function () {
                        $('#d , #va, #p, #iv').fadeOut(500)
                    })
                    $('#in').click(function () {
                        $('#d , #va, #p').fadeOut()
                    })
                </script>
</body>
</html>
<?php } else
    {
        header('Location: http://localhost:8081/Prance');
    } 
    ?>