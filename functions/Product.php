<?php
session_start();

// When user is not logged in and clicked the button reserve, the user will be redirected to login page
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

// Add reserve product
if(isset($_POST['btn_reserve'])) {
    try {
        require_once('../db_conn.php');

        function validate($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);

            return $data;
        }

        $productId = validate($_POST['btn_reserve']);
        $patientId = validate($_SESSION['pid']);

        $query = 'INSERT INTO tbl_reservations(product_id, patient_id)
                        VALUES(:product_id, :patient_id);';

        $statement = $connection->prepare($query);
        $statement->bindParam('product_id', $productId, PDO::PARAM_INT);
        $statement->bindParam('patient_id', $patientId, PDO::PARAM_INT);

        if($statement->execute()) {
            $_SESSION['message_success'] = 'Reservation successfully saved!';
            header('location: ../patient/products.php');
        }
    } catch(PDOException $exception) {
        echo $messageFailed = $exception->getMessage();
    }
}