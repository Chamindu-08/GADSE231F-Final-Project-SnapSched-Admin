<?php
//get database connection
include 'DBConnection/DBConnection.php';

// Check connection
if (!$connection) {
    echo "Connection failed";
}

// Initialize variables to store retrieved values
$first_name = "";
$last_name = "";
$sure_name = "";
$admission_no = "";
$Assume_date = "";
$address = "";
$email = "";
$nic = "";
$designation = "";
$contact_no = "";
$password = "";

//check if the cookie is set
if(isset($_COOKIE['adminEmail'])){
    $adminEmail = $_COOKIE['adminEmail'];
} else {
    //redirect to login page
    echo '<script>
            var confirmMsg = confirm("Your session has timed out. Please log in again.");
            if (confirmMsg) {
                window.location.href = "AdminLoginRegister.php";
            }
        </script>';
    exit();
}

// SQL query to retrieve student information
$sql = "SELECT * FROM otherstaff WHERE OSEmail = '$adminEmail'";

$result = mysqli_query($connection,$sql);

if ($result === false) {
    die("Error executing the query: " . $connection->error);
}

if ($result->num_rows > 0) {
    // output data of the first row (assuming only one student is retrieved)
    $row = $result->fetch_assoc();
    $first_name = $row["FirstName"];
    $last_name = $row["LastName"];
    $sure_name = $row["SurName"];
    $admission_no = $row["OSId"];
    $admission_date = $row["AssumeDate"];
    $nic = $row["NIC"];
    $address = $row["OSAddress"];
    $email = $row["OSEmail"];

    $contact_no = $row["OSContactNo"];
    $designation = $row["Designation"];
    $password = $row["OSPassword"];
} else {
    echo "0 results";
}

// Close the database connection
mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account | Admin</title>

    <!-- stylesheet -->
    <link rel="stylesheet" href="CSS/Dashboard.css" />
    <link rel="stylesheet" href="CSS/style.css" />
    <!-- Bootstap link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<script>
    function adminProUpdate(){
      window.location.href = "AdminProfileEdit.php";
    }
  </script>

    <div class="wrapper">
        <?php include 'Include/AdminSideBar.php'; ?>
            
            <main class="content px-3 py-2">
                <div class="container-fluid">
                    <!-- Table Element -->
                    <div class="card border-0">
                        <div class="card-header">
                            <h4 class="card-title">
                                Account
                            </h4>
                        </div>
                        <div class="row" style="padding-top: 20px; background-color: lightgray;">
                        <div class="col-12 col-md-3 d-flex">
                            <div class="card flex-fill border-0">
                                <div class="card-body p-0 d-flex flex-fill">
                                    <div class="row g-0 w-100 center-content">
                                        <img src="Images/user1.png">
                                        <h4><?php echo $first_name . ' ' . $last_name; ?></h4>
                                        <h6><?php echo $sure_name; ?></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-9 d-flex">
                            <div class="card flex-fill border-0">
                                <div class="card-body py-4">
                                    <div class="row d-flex align-items-start">
                                        <table class="profileStyle">
                                            <tr>
                                                <td>
                                                    <h5>Admission No :</h5>
                                                    <label><?php echo $admission_no; ?></label>
                                                </td>
                                                <td>
                                                    <h5>Assume Date :</h5>
                                                    <label><?php echo $admission_date; ?></label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h5>Address :</h5>
                                                    <label><?php echo $address; ?></label>
                                                </td>
                                                <td>
                                                    <h5>Email :</h5>
                                                    <label><?php echo $email; ?></label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h5>Contact No :</h5>
                                                    <label><?php echo $contact_no; ?></label>
                                                </td>
                                                <td>
                                                    <h5>NIC :</h5>
                                                    <label><?php echo $nic; ?></label>
                                                </td>
                                            </tr>
                                            <tr>
                                            <td>
                                                    <h5>Designation :</h5>
                                                    <label><?php echo $designation; ?></label>
                                                </td>
                                                <td>
                                                    <h5>Password :</h5>
                                                    <label><?php echo $password; ?></label>
                                                </td>
                                            <tr>
                                                <td colspan="2">
                                                    <button type="button" onclick="adminProUpdate()" class="btnStyle1" >UPDATE</button>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>              
                </div>
                </div>
            </main>
        </div>
    </div>


    <script src="js/Dashboard.js"></script>
    <!-- link Bootstap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
