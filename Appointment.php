<?php
//check if the cookie is set
if (isset($_COOKIE['adminEmail'])) {
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appoinment | Admin</title>

    <!-- Bootstrap link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- stylesheet -->
    <link rel="stylesheet" href="CSS/Dashboard.css" />
    <link rel="stylesheet" href="CSS/style.css" />

</head>
<body>
    
<div class="wrapper">
    <?php include 'Include/AdminSideBar.php'; ?>

    <main class="content px-3 py-2">
        <div class="container-fluid">
            <div class="card border-0">
                <div class="card-header">
                    <h4 class="card-title">
                        Appoinment
                    </h4>

                    <!-- display the appoinment form database -->

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                //get database connection
                                include 'DBConnection/DBConnection.php';

                                //check connection
                                if (!$connection) {
                                    echo "Connection failed";
                                }

                                //get appoinment details
                                $sql = "SELECT * FROM appointment WHERE AppointmentDate >= CURDATE() ORDER BY AppointmentDate ASC";
                                $result = mysqli_query($connection, $sql);

                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<div class='card border-0'>";
                                        echo "<div class='card-body'>";
                                        echo "<table>";
                                        echo "<tr>";
                                        echo "<td colspan='2'><h5>Appoinment Details</h5></td>";
                                        echo "</tr>";
                                        echo "<tr>";
                                        echo "<td><h6>Sender Name : </h6>" . $row['SenderName'] . "</td>";
                                        echo "<td><h6>Date : </h6>" . $row['AppointmentDate'] . "</td>";
                                        echo "</tr>";
                                        echo "<tr>";
                                        echo "<td><h6>Sender Email : </h6>" . $row['SenderEmail'] . "</td>";
                                        echo "<td><h6>Contact Number : </h6>" . $row['SenderContactNo'] . "</td>";
                                        echo "</tr>";
                                        echo "<tr>";
                                        echo "<td colspan='2'><h6>Message : </h6>" . $row['Message'] . "</td>";
                                        echo "</tr>";
                                        echo "</table>";
                                        echo "</div>";
                                        echo "</div>";
                                    }
                                } else {
                                    echo "<p>No appoinments found</p>";
                                }

                                // Close the database connection
                                mysqli_close($connection);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Bootstrap script -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
