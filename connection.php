<?php

    $database= new mysqli("localhost","root","","git_edoc");
    if ($database->connect_error){
        die("Connection failed:  ".$database->connect_error);
    }

?>