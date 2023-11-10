<?php
session_start();

if(!$_SESSION['isLoggedIn']) {
    header('location: ../login.php');
}

try {
    require_once('../db_conn.php');

    $query = 'SELECT *
                FROM tbl_products;';

    $statement = $connection->prepare($query);

    if($statement->execute()) {
        $products = $statement->fetchAll(PDO::FETCH_OBJ);
    }
} catch(PDOException $exception) {
    echo $messageFailed = $exception->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/products.css">

    <title>Sessions</title>
    <style>
        .popup {
            animation: transitionIn-Y-bottom 0.5s;
        }

        .sub-table {
            animation: transitionIn-Y-bottom 0.5s;
        }
    </style>
</head>

<body>
    <?php

    //learn from w3schools.com

    session_start();

    if (isset($_SESSION["user"])) {
        if (($_SESSION["user"]) == "" or $_SESSION['usertype'] != 'p') {
            header("location: ../login.php");
        } else {
            $useremail = $_SESSION["user"];
        }
    } else {
        header("location: ../login.php");
    }


    //import database
    include("../connection.php");
    $userrow = $database->query("select * from patient where pemail='$useremail'");
    $userfetch = $userrow->fetch_assoc();
    $userid = $userfetch["pid"];
    $username = $userfetch["pname"];


    //echo $userid;
    //echo $username;

    date_default_timezone_set('Asia/Kolkata');

    $today = date('Y-m-d');


    //echo $userid;
    ?>
    <div class="container">
        <div class="menu">
            <table class="menu-container" border="0">
                <tr>
                    <td style="padding:10px" colspan="2">
                        <table border="0" class="profile-container">
                            <tr>
                                <td width="30%" style="padding-left:20px">
                                    <img src="../img/user.png" alt="" width="100%" style="border-radius:50%">
                                </td>
                                <td style="padding:0px;margin:0px;">
                                    <p class="profile-title"><?php echo substr($username, 0, 13)  ?>..</p>
                                    <p class="profile-subtitle"><?php echo substr($useremail, 0, 22)  ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="../logout.php"><input type="button" value="Log out" class="logout-btn btn-primary-soft btn"></a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-home ">
                        <a href="index.php" class="non-style-link-menu ">
                            <div>
                                <p class="menu-text">Home</p>
                        </a>
        </div></a>
        </td>
        </tr>
        <tr class="menu-row">
            <td class="menu-btn menu-active" style="background-image: url('../img/icons/products.svg')">
                <a href="products.php" class="non-style-link-menu">
                    <div>
                        <p class="menu-text">Products</p>
                </a>
    </div>
    </td>
    </tr>
    <tr class="menu-row">
        <td class="menu-btn menu-icon-doctor">
            <a href="doctors.php" class="non-style-link-menu">
                <div>
                    <p class="menu-text">All Doctors</p>
            </a></div>
        </td>
    </tr>

    <tr class="menu-row">
        <td class="menu-btn menu-icon-session">
            <a href="schedule.php" class="non-style-link-menu">
                <div>
                    <p class="menu-text">Scheduled Sessions</p>
                </div>
            </a>
        </td>
    </tr>
    <tr class="menu-row">
        <td class="menu-btn menu-icon-appoinment">
            <a href="appointment.php" class="non-style-link-menu">
                <div>
                    <p class="menu-text">My Bookings</p>
            </a></div>
        </td>
    </tr>
    <tr class="menu-row">
        <td class="menu-btn menu-icon-settings">
            <a href="settings.php" class="non-style-link-menu">
                <div>
                    <p class="menu-text">Settings</p>
            </a></div>
        </td>
    </tr>

    </table>
    </div>
    <?php

    $sqlmain = "select * from schedule inner join doctor on schedule.docid=doctor.docid where schedule.scheduledate>='$today'  order by schedule.scheduledate asc";
    $sqlpt1 = "";
    $insertkey = "";
    $q = '';
    $searchtype = "All";
    if ($_POST) {
        //print_r($_POST);

        if (!empty($_POST["search"])) {

            $keyword = $_POST["search"];
            $sqlmain = "select * from schedule inner join doctor on schedule.docid=doctor.docid where schedule.scheduledate>='$today' and (doctor.docname='$keyword' or doctor.docname like '$keyword%' or doctor.docname like '%$keyword' or doctor.docname like '%$keyword%' or schedule.title='$keyword' or schedule.title like '$keyword%' or schedule.title like '%$keyword' or schedule.title like '%$keyword%' or schedule.scheduledate like '$keyword%' or schedule.scheduledate like '%$keyword' or schedule.scheduledate like '%$keyword%' or schedule.scheduledate='$keyword' )  order by schedule.scheduledate asc";
            //echo $sqlmain;
            $insertkey = $keyword;
            $searchtype = "Search Result : ";
            $q = '"';
        }
    }


    $result = $database->query($sqlmain)


    ?>

    <div class="dash-body">
        <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;margin-top:25px; ">
            <tr>
                <td width="13%">
                    <a href="schedule.php"><button class="login-btn btn-primary-soft btn btn-icon-back" style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px">
                            <font class="tn-in-text">Back</font>
                        </button></a>
                </td>
                <td>
                    <form action="" method="post" class="header-search">

                        <input type="search" name="search" class="input-text header-searchbar" placeholder="Search Doctor name or Email or Date (YYYY-MM-DD)" list="doctors" value="<?php echo $insertkey ?>">&nbsp;&nbsp;

                        <?php
                        echo '<datalist id="doctors">';
                        $list11 = $database->query("select DISTINCT * from  doctor;");
                        $list12 = $database->query("select DISTINCT * from  schedule GROUP BY title;");

                        for ($y = 0; $y < $list11->num_rows; $y++) {
                            $row00 = $list11->fetch_assoc();
                            $d = $row00["docname"];

                            echo "<option value='$d'><br/>";
                        };

                        for ($y = 0; $y < $list12->num_rows; $y++) {
                            $row00 = $list12->fetch_assoc();
                            $d = $row00["title"];

                            echo "<option value='$d'><br/>";
                        };

                        echo ' </datalist>';
                        ?>

                        <input type="Submit" value="Search" class="login-btn btn-primary btn" style="padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;">
                    </form>
                </td>
                <td width="15%">
                    <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">
                        Today's Date
                    </p>
                    <p class="heading-sub12" style="padding: 0;margin: 0;">
                        <?php
                            echo $today;
                        ?>
                    </p>
                </td>
                <td width="10%">
                    <button class="btn-label" style="display: flex;justify-content: center;align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                </td>


            </tr>


            <tr>
                <td colspan="4" style="padding-top:10px;width: 100%;">
                    <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)"><?php echo $searchtype . " Sessions" . "(" . $result->num_rows . ")"; ?> </p>
                    <p class="heading-main12" style="margin-left: 45px;font-size:22px;color:rgb(49, 49, 49)"><?php echo $q . $insertkey . $q; ?> </p>
                </td>

            </tr>

            <tr>
                <td colspan=4  style="width: 100%;" >
                    <div class="products-items products-content" style="display: flex; align-items: space-between; flex-wrap: wrap">
                        <?php
                            $sql = "SELECT * FROM eyecare.products";
                            $result = $database->query($sql);

                            for ($y = 0; $y < $result->num_rows; $y++) {
                                $row = $result->fetch_assoc();

                                $id = $row['id'];
                                $name = $row["name"];
                                $description = $row["description"];
                                $price = $row["price"];
                                $picture_data = $row["picture_data"];

                                echo '
                                <form class="product-form" --product-id="'. $id .'" --product-name="'. $name .'" method="POST" style="margin: 12px;">
                                    <div class="card">
                                        <img style="object-fit: cover; height: 200px; width: 200px;" src="data:image/jpeg;base64,' . base64_encode($picture_data) . '" alt="Denim Jeans" style="width:100%">
                                        <h1>'. $name.'</h1>
                                        <p class="price">$'. $price.'</p>
                                        <p>'. $description .'</p>
                                        <input style="border: none;
                                            outline: 0;
                                            padding: 12px;
                                            color: white;
                                            background-color: #000;
                                            text-align: center;
                                            cursor: pointer;
                                            width: 100%;
                                            font-size: 18px;" id="input-button" type="submit" value="RESERVE">
                                    </div>       
                                </form>

                            
                                ';
                            };

                            echo '<script>
                                const forms = document.querySelectorAll(".product-form");

                                for (let form of forms) {
                                    form.onsubmit = function(event) {
                                        alert("Item has been reserved. Please proceed to booking.");

                                        window.location.replace("http://localhost/Family-Eyecare-Optical-Center/patient/booking.php?id=10&item-id=" + form.getAttribute("--product-id") + "&item-name=" + form.getAttribute("--product-name"));
                                        event.preventDefault();
                                    }
                                }
                            </script>';
                        ?>

                        

                        <!-- <div class="card">
                            <img src="../img/eyeglass1.jpg" alt="Denim Jeans" style="width:100%">
                            <h1>EYEGLASS 1</h1>
                            <p class="price">$350</p>
                            <p>Some text about the glasses..</p>
                            <p><button>RESERVE</button></p>
                        </div>
                        <div class="card">
                            <img src="../img/eyeglass2.jpg" alt="Denim Jeans" style="width:100%">
                            <h1>EYEGLASS 1</h1>
                            <p class="price">$350</p>
                            <p>Some text about the glasses..</p>
                            <p><button>RESERVE</button></p>
                        </div>
                        <div class="card">
                            <img src="../img/eyeglass3.jpg" alt="Denim Jeans" style="width:100%">
                            <h1>EYEGLASS 1</h1>
                            <p class="price">$350</p>
                            <p>Some text about the glasses..</p>
                            <p><button>RESERVE</button></p>
                        </div>
                        <div class="card">
                            <img src="../img/eyeglass3.jpg" alt="Denim Jeans" style="width:100%">
                            <h1>EYEGLASS 1</h1>
                            <p class="price">$350</p>
                            <p>Some text about the glasses..</p>
                            <p><button>RESERVE</button></p>
                        </div> -->
                    </div>
                </td>
                <td colspan=4 class="products-content">
                    <!-- <div class="products-items">
                        <div class="card">
                            <img src="../img/vitamins1.jpg" alt="Denim Jeans" style="width:100%">
                            <h1>EYEGLASS 1</h1>
                            <p class="price">$350</p>
                            <p>Some text about the glasses..</p>
                            <p><button>RESERVE</button></p>
                        </div>
                        <div class="card">
                            <img src="../img/vitamins2.jpg" alt="Denim Jeans" style="width:100%">
                            <h1>EYEGLASS 1</h1>
                            <p class="price">$350</p>
                            <p>Some text about the glasses..</p>
                            <p><button>RESERVE</button></p>
                        </div> -->
                        <!-- <div class="card">
                            <img src="../img/vitamins3.jpg" alt="Denim Jeans" style="width:100%">
                            <h1>EYEGLASS 1</h1>
                            <p class="price">$350</p>
                            <p>Some text about the glasses..</p>
                            <p><button>RESERVE</button></p>
                        </div> -->
                        <!-- <div class="card">
                            <img src="../img/eyeglass3.jpg" alt="Denim Jeans" style="width:100%">
                            <h1>EYEGLASS 1</h1>
                            <p class="price">$350</p>
                            <p>Some text about the glasses..</p>
                            <p><button>RESERVE</button></p>
                        </div> -->
                    </div>
                </td>
                <td colspan=4 class="products-content">
                    <!-- <div class="products-items">
                        <div class="card">
                            <img src="../img/vitamins1.jpg" alt="Denim Jeans" style="width:100%">
                            <h1>EYEGLASS 1</h1>
                            <p class="price">$350</p>
                            <p>Some text about the glasses..</p>
                            <p><button>RESERVE</button></p>
                        </div>
                        <div class="card">
                            <img src="../img/vitamins2.jpg" alt="Denim Jeans" style="width:100%">
                            <h1>EYEGLASS 1</h1>
                            <p class="price">$350</p>
                            <p>Some text about the glasses..</p>
                            <p><button>RESERVE</button></p>
                        </div> -->
                        <!-- <div class="card">
                            <img src="../img/vitamins3.jpg" alt="Denim Jeans" style="width:100%">
                            <h1>EYEGLASS 1</h1>
                            <p class="price">$350</p>
                            <p>Some text about the glasses..</p>
                            <p><button>RESERVE</button></p>
                        </div> -->
                        <!-- <div class="card">
                            <img src="../img/eyeglass3.jpg" alt="Denim Jeans" style="width:100%">
                            <h1>EYEGLASS 1</h1>
                            <p class="price">$350</p>
                            <p>Some text about the glasses..</p>
                            <p><button>RESERVE</button></p>
                        </div> -->
                    </div>
                </td>
            </tr>



        </table>
    </div>
    </div>

    </div>

</body>

</html>