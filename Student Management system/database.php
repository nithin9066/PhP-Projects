<?php
function result($q,$ch){
$con=mysqli_connect("localhost","root","1234","studentData"); 
if($ch == 2)
{
    $r = mysqli_query($con,$q);
    return $d = mysqli_fetch_array($r);
}
else if($ch == 1)
{
return $r = mysqli_query($con,$q);
}

}
?>