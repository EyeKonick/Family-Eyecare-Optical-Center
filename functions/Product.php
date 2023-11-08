<?php

if(isset($_GET['see_more_glass_one']) || isset($_GET['see_more_glass_two']) || isset($_GET['see_more_glass_three']) ||
isset($_GET['see_more_glass_four']) || isset($_GET['see_more_glass_five']) || isset($_GET['see_more_glass_six']) ||
isset($_GET['see_more_glass_seven'])) {
    try {
        if(!$_SESSION['isLoggedIn']) {
            header('location: ../login.php');
        } else {
            header('location: ');
        }
    } catch(PDOException $exception) {
        echo $messageFailed = $exception->getMessage();
    }
}