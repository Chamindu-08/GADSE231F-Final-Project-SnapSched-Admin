<?php
//get database connection
include 'DBConnection/DBConnection.php';

//check connection
if (!$connection) {
    echo "Connection failed";
}

//check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $surName = $_POST['surName'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $nic = $_POST['nic'];
    $designation = $_POST['designation'];
    $assumeDate = $_POST['assumeDate'];
    $contactNo = $_POST['contactNo'];
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    
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

    //check if newPassword or confirmPassword is empty and currentPassword is not empty
    if (!empty($newPassword) || !empty($confirmPassword)) {
        if (empty($currentPassword)) {
            echo "<script>alert('Current Password is required.');</script>";
        } else {
            //verify current password
            $sqlVerifyPassword = "SELECT OSPassword FROM otherStaff WHERE OSEmail='$adminEmail'";
            $resultVerifyPassword = mysqli_query($connection, $sqlVerifyPassword);

            if (mysqli_num_rows($resultVerifyPassword) > 0) {
                $rowPassword = mysqli_fetch_assoc($resultVerifyPassword);
                $storedPassword = $rowPassword['OSPassword'];

                if ($currentPassword != $storedPassword) {
                    echo "<script>alert('Incorrect current password.');</script>";
                } else {
                    //check if newPassword and confirmPassword match
                    if ($newPassword != $confirmPassword) {
                        echo "<script>alert('Password and Confirm Password do not match. Please enter again.');</script>";
                    } else {
                        //validate new password and confirm password 8 characters long
                        if (strlen($newPassword) < 8 || strlen($confirmPassword) < 8) {
                            echo "<script>alert('Password must be at least 8 characters long.');</script>";
                            exit();
                        }

                        //validate email
                        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            echo "<script>alert('Invalid email format.');</script>";
                            exit();
                        }

                        //validate contact number
                        if (!preg_match("/^[0-9]{10}$/", $contactNo)) {
                            echo "<script>alert('Invalid contact number.');</script>";
                            exit();
                        }

                        //validate NIC 12 or 10 characters long
                        if (strlen($nic) != 12 && strlen($nic) != 10) {
                            echo "<script>alert('Invalid NIC.');</script>";
                            exit();
                        }

                        //update teacher table with password change
                        $sqlAdmin = "UPDATE otherStaff SET FirstName='$firstName', LastName='$lastName', SurName='$surName', OSAddress='$address', OSEmail='$email', NIC='$nic', OSContactNo='$contactNo', AssumeDate='$assumeDate', Designation='$designation', OSPassword='$newPassword' WHERE OSEmail='$adminEmail'";

                        if (mysqli_query($connection, $sqlAdmin)) {
                            echo "<script>alert('Record updated successfully');</script>";

                        } else {
                            echo "<script>alert('Error updating record: " . mysqli_error($connection) . "');</script>";
                        }
                    }
                }
            } else {
                echo "<script>alert('Error verifying current password: " . mysqli_error($connection) . "');</script>";
            }
        }
    } else {
        //update teacher table without password change
        $sqlAdmin = "UPDATE otherStaff SET FirstName='$firstName', LastName='$lastName', SurName='$surName', OSAddress='$address', OSEmail='$email', NIC='$nic', OSContactNo='$contactNo', AssumeDate='$assumeDate', Designation='$designation' WHERE OSEmail='$adminEmail'";
        
        if (mysqli_query($connection, $sqlAdmin)) {
            echo "<script>alert('Record updated successfully');</script>";
        } else {
            echo "<script>alert('Error updating record: " . mysqli_error($connection) . "');</script>";
        }
    }
}

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

$sql = "SELECT * FROM otherStaff WHERE OSEmail='$adminEmail'";

$result = mysqli_query($connection, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $firstName = $row['FirstName'];
    $lastName = $row['LastName'];
    $surName = $row['SurName'];
    $address = $row['OSAddress'];
    $email = $row['OSEmail'];
    $nic = $row['NIC'];
    $designation = $row['Designation'];
    $assumeDate = $row['AssumeDate'];
    $contactNo = $row['OSContactNo'];
} else {
    echo "<script>alert('Teacher record not found.');</script>";
}

// Close the database connection
mysqli_close($connection);
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Update | Admin</title>

    <!-- stylesheet -->
    <link rel="stylesheet" href="CSS/Dashboard.css" />
    <link rel="stylesheet" href="CSS/style.css" />
    <!-- Bootstap link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
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
                        <div class="card-body">
                            <form name="teacherProUp" action="#" method="post">
                                <table class="puTable">
                                    <tr>
                                        <th colspan="2">Personal Details</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            First Name :<br>
                                            <input type="text" name="firstName" value="<?php echo $firstName; ?>" required>
                                        </td>
                                        <td>
                                            Last Name :<br>
                                            <input type="text" name="lastName" value="<?php echo $lastName; ?>" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Sure Name :<br>
                                            <input type="text" name="surName" value="<?php echo $surName; ?>" required>
                                        </td>
                                        <td>
                                            Address :<br>
                                            <input type="text" name="address" value="<?php echo $address; ?>" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            NIC :<br>
                                            <input type="text" name="nic" value="<?php echo $nic; ?>" required>
                                        </td>
                                        <td>
                                            Assume Date :<br>
                                            <input type="date" name="assumeDate" value="<?php echo $assumeDate; ?>" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Contact no :<br>
                                            <input type="text" name="contactNo" value="<?php echo $contactNo; ?>" required>
                                        </td>
                                        <td>
                                            Email :<br>
                                            <input type="email" name="email" value="<?php echo $email; ?>" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Designation :<br>
                                            <input type="text" name="designation" value="<?php echo $designation; ?>" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Password</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            Current Password :<br>
                                            <input type="password" name="current_password">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            New Password :<br>
                                            <input type="password" name="new_password">
                                        </td>
                                        <td>
                                            Confirm Password :<br>
                                            <input type="password" name="confirm_password">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><button type="submit" class="btnStyle1 mx-2">Save</button></td>
                                    </tr>
                                    
                                  </table>
                            </form>
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
