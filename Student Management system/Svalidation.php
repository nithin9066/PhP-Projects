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
                        <a class="nav-link active" aria-current="page" href="Shome.php">Home</a>
                    </li>

                    </li>
                </ul>
                <form class="d-flex">
                    <a href="index.php?z=0" class="btn btn-outline-success">logout</a>
                </form>
            </div>
        </div>
    </nav><?php
            include "database.php";
            session_start();
            if(isset($_SESSION['susername']))
            {
            $usn = $_SESSION['usn'];
     if (isset($_POST['view'])) { ?>
     <div class="table-responsive ">
        <table id='va' class="table-dark table mt-5 container">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">USN</th>
                    <th scope="col">SEM</th>
                    <th scope="col">Subject</th>
                    <th scope="col">Period</th>
                    <th scope="col">Attendance</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $date = $_POST['date'];
                $t = $_POST['sub'];
                $r = result("select * from attendance where date = '$date' and subject = '$t' and usn = '$usn[0]'", 1);
                while ($d = mysqli_fetch_assoc($r)) {
                ?>
                    <tr>

                        <td><?php echo $d['name'] ?></td>
                        <td><?php echo $d['usn'] ?></td>
                        <td><?php echo $d['sem'] ?></td>
                        <td><?php echo $d['subject'] ?></td>
                        <td><?php echo $d['period'] ?></td>
                        <td><?php echo $d['attendance'] ?></td>

                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <div>
    <?php } 
    } else
    {
        header('Location: http://localhost:8081/Prance');
    } 
    ?>