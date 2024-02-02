<?php

$con=mysqli_connect("localhost","root","","testing");

// host then username then password then database
if(mysqli_connect_error()){
    echo" <script>alert('cannot connect to the database');</script>";
    exit();

    // this if is for if the it is unable to connect the database the it show the error in the script and will not execte aage ka code because of exit function 
}
?>


