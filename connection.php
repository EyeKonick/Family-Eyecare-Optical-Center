<?php

    $database= new mysqli("localhost","root","","eyecare");
    if ($database->connect_error){
        die("Connection failed:  ".$database->connect_error);
    }

?>