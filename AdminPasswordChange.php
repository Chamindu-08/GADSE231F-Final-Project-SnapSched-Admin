<?php
// Include database connection
include 'DBConnection/DBConnection.php';

// Check database connection
if (!$connection) {
    echo "<script>alert('Connection failed');</script>";
}

// Error message variable
$errorMessage = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];
    
    // Check if newPassword or confirmPassword is empty and currentPassword is not empty
    if (!empty($newPassword) || !empty($confirmPassword)) {
        if (empty($currentPassword)) {
            $errorMessage = "Please enter current password.";
        } else {
            // Check if the cookie is set
            if (isset($_COOKIE['adminEmail'])) {
                $adminEmail = $_COOKIE['adminEmail'];
            } else {
                // Redirect to login page
                echo '<script>
                        var confirmMsg = confirm("Your session has timed out. Please log in again.");
                        if (confirmMsg) {
                            window.location.href = "AdminLoginRegister.php";
                        }
                    </script>';
                exit();
            }

            // Validate new password and confirm password (minimum 8 characters)
            if (strlen($newPassword) < 8 || strlen($confirmPassword) < 8) {
                $errorMessage = "Password must be at least 8 characters long.";
            } else {
                // Verify current password
                $sqlVerifyPassword = "SELECT OSPassword FROM otherStaff WHERE OSEmail='$adminEmail'";
                $resultVerifyPassword = mysqli_query($connection, $sqlVerifyPassword);

                if ($resultVerifyPassword) {
                    $rowPassword = mysqli_fetch_assoc($resultVerifyPassword);
                    $storedPassword = $rowPassword['OSPassword'];

                    // Check if current password matches stored password
                    if ($currentPassword != $storedPassword) {
                        $errorMessage = "Current password is incorrect. Please enter again.";
                    } else {
                        // Check if newPassword and confirmPassword match
                        if ($newPassword != $confirmPassword) {
                            $errorMessage = "New password and confirm password do not match. Please enter again.";
                        } else {
                            // Update otherstaff table with new password
                            $sqlUpdatePassword = "UPDATE otherstaff SET OSPassword='$newPassword' WHERE OSEmail='$adminEmail'";
                
                            $result = mysqli_query($connection, $sqlUpdatePassword);

                            if ($result) {
                                echo "<script>alert('Password updated successfully');</script>";
                            } else {
                                $errorMessage = "Error updating student password: " . mysqli_error($connection);
                            }
                        }
                    }
                } else {
                    $errorMessage = "Error verifying current password: " . mysqli_error($connection);
                }
            }
        }
    } else {
        // No password update requested
        $errorMessage = "No changes made to password.";
    }
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

    <!-- Stylesheets -->
    <link rel="stylesheet" href="CSS/Dashboard.css">
    <link rel="stylesheet" href="CSS/style.css">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="wrapper">
        <?php include 'Include/AdminSideBar.php'; ?>
            
        <main class="content px-3 py-2">
            <div class="container-fluid">
                <!-- Form -->
                <div class="card border-0">
                    <div class="card-header">
                        <h4 class="card-title">
                            Account
                        </h4>
                    </div>
                    <div class="card-body">
                        <form name="studentProUp" action="#" method="post">
                            <table class="cpTable">
                                <tr>
                                    <th colspan="2">Password</th>
                                </tr>
                                <!-- Error Message -->
                                <?php if (!empty($errorMessage)) : ?>
                                    <div class="alert alert-danger" role="alert">
                                        <?php echo $errorMessage; ?>
                                    </div>
                                <?php endif; ?>
                                <tr>
                                    <td>
                                        Current Password :<br>
                                        <input type="password" name="currentPassword">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        New Password :<br>
                                        <input type="password" name="newPassword">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Confirm Password :<br>
                                        <input type="password" name="confirmPassword">
                                    </td>
                                </tr>
                                <tr>
                                    <td><button class="btnStyle1 mx-2" type="submit">Save</button></td>
                                </tr>
                            </table>
                        </form>

                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- JavaScript -->
    <script src="js/Dashboard.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
