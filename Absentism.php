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
    <title>Absentism | Teacher</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="CSS/Dashboard.css">
    <link rel="stylesheet" href="CSS/style.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
    <div class="wrapper">
        <?php include 'Include/AdminSideBar.php'; ?>

        <main class="content px-3 py-2">
            <div class="container">
                <!-- Table Element -->
                <div class="card border-0">
                    <div class="card-header">
                        <h4 class="card-title">
                            Absentism
                        </h4>
                    </div>
                    <div class="card-body table-responsive">
                        <!-- Display absent teachers from the database -->
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Teacher ID</th>
                                    <th>Teacher Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                //database connection
                                include 'DBConnection/DBConnection.php';

                                // Check database connection
                                if (!$connection) {
                                    echo "<tr><td colspan='3'>Database connection failed. Please try again later.</td></tr>";
                                } else {
                                    //select absent teachers
                                    $query = "SELECT t.TeacherId, CONCAT(t.FirstName, ' ', t.LastName) AS TeacherName
                                              FROM teacher t
                                              INNER JOIN absenteeism a ON t.TeacherId = a.TeacherId WHERE a.AbsentStatus = 'no' AND a.AbsentDate = CURDATE()";

                                    //execute the query
                                    $result = mysqli_query($connection, $query);

                                    //check query is executed
                                    if ($result) {
                                        //check the absent teachers
                                        if (mysqli_num_rows($result) > 0) {
                                            //fetch the result
                                            while ($row = mysqli_fetch_array($result)) {
                                                //display the absent teacher
                                                echo "<tr>";
                                                echo "<td>" . $row['TeacherId'] . "</td>";
                                                echo "<td>" . $row['TeacherName'] . "</td>";
                                                //link button to ReliefTeacher.php with the teacher ID
                                                echo "<td><a href='ReliefTeacher.php?teacherId=" . $row['TeacherId'] . "'><button class='btnStyle1'>Relief</button></a></td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            //display message no absentism today
                                            echo "<tr><td colspan='3'>No absenteeism today.</td></tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='3'>Error: " . $query . "<br>" . mysqli_error($connection) . "</td></tr>";
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
