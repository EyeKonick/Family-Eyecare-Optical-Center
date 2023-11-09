<?php
session_start();

if(isset($_POST['btn_login'])) {
    try {
        require_once('../db_conn.php');

        function validate($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            
            return $data;
        }

        $email = validate($_POST['useremail']); 
        $password = validate($_POST['userpassword']);

        $query = 'SELECT *
                    FROM patient
                    WHERE pemail = :email AND ppassword = :password;';

        // $query = 'SELECT *
        //             FROM `admin`
        //             WHERE aemail = :email AND apassword = :password;';

        $statement = $connection->prepare($query);
        $statement->bindParam('email', $email, PDO::PARAM_STR);
        $statement->bindParam('password', $password, PDO::PARAM_STR);

        if($statement->execute()) {
            $user = $statement->fetch(PDO::FETCH_OBJ);

            if($statement->RowCount() > 0) {
                $_SESSION['pid'] = $user->pid;
                $_SESSION['pemail'] = $user->pemail;
                $_SESSION['pname'] = $user->pname;
                $_SESSION['ppassword'] = $user->ppassword;
                $_SESSION['paddress'] = $user->paddress;
                $_SESSION['pnic'] = $user->pnic;
                $_SESSION['pdob'] = $user->pdob;
                $_SESSION['ptel'] = $user->ptel;

                // $_SESSION['aemail'] = $user->pemail;
                // $_SESSION['apassword'] = $user->ppassword;

                $_SESSION['isLoggedIn'] = true;

                $_SESSION['message_success'] = '';

                header('location: ../patient/index.php');
                // header('location: ../admin/index.php');
            } else {
                echo $messageFailed = 'Email or password is incorrect!';
            }
        }
    } catch(PDOException $exception) {
        echo $messageFailed = $exception->getMessage();
    }
}

if(isset($_POST['btn_logout'])) {
    try {
        session_destroy();
        header('location: ../login.php');
    } catch(PDOException $exception) {
        echo $messageFailed = $exception->getMessage();
    }
}