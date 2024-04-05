<?php
//database connection
include 'DBConnection/DBConnection.php';

//check database connection
if (!$connection) {
    echo "<script>alert('Database connection failed.');</script>";
}

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

//get the teacher ID from the query parameter
$teacherId = $_GET['teacherId'] ?? '';

//get teacher name from the database
$query = "SELECT CONCAT(FirstName, ' ', LastName) AS TeacherName FROM teacher WHERE TeacherId = '$teacherId'";
$result = mysqli_query($connection, $query);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $teacherName = $row['TeacherName'];
} else {
    echo "<script>alert('Error: " . $query . "<br>" . mysqli_error($connection) . "');</script>";
}

//initialize error flag
$error = false;

//check if the form is submitted
if (isset($_POST['submit'])) {
    //validate the form fields
    if (empty($_POST['relief_teacher'])) {
        echo "<script>alert('Please select a relief teacher for each period.');</script>";
        exit();
    }

    //array to store all query strings
    $queries = [];

    $reliefTeachers = $_POST['relief_teacher'];
    foreach ($reliefTeachers as $period => $selectedTeacherId) {
        // Extract teacher id from the selected option
        $teachingId = $_POST['teaching_id'][$period];
        $reliefDate = date('Y-m-d');

        // Insert relief teacher into the database
        $query = "INSERT INTO relief (TeachingId, TeacherId, ReliefDate) VALUES ('$teachingId', '$selectedTeacherId', '$reliefDate')";
        $queries[] = $query; // Store the query for potential error checking later
    }

    // Execute all queries
    foreach ($queries as $query) {
        $result = mysqli_query($connection, $query);
        if (!$result) {
            // Set error flag if any query fails
            $error = true;
            break; // Exit loop if an error occurs
        }
    }

    // Check if any errors occurred
    if (!$error) {
        // Update absenteeism table
        $query = "UPDATE absenteeism SET AbsentStatus = 'yes' WHERE TeacherId = '$teacherId' AND AbsentDate = CURDATE()";
        $result = mysqli_query($connection, $query);

        if (!$result) {
            // Set error flag if the query fails
            $error = true;
        }
    }

    // If no errors occurred, display success message
    if (!$error) {
        echo "<script>alert('All relief teachers assigned successfully.');</script>";
        // Redirect to Absentism.php
        echo "<script>window.location.href = 'Absentism.php';</script>";
    } else {
        // If errors occurred, display error message
        echo "<script>alert('Error: One or more relief teachers could not be assigned.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relief Teacher</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="CSS/Dashboard.css">
    <link rel="stylesheet" href="CSS/style.css">
    <!-- Bootstrap link -->
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
                            Relief Teacher
                        </h4>
                    </div>
                    <!-- Display absent teacher's information -->
                    <div class="card-body table-responsive">
                        <?php
                        //check connection
                        if (!$connection) {
                            echo "<script>alert('Database connection failed.');</script>";
                        }

                        //retrieve absent teacher's information
                        $absentDate = date('Y-m-d');
                        $weekdayName  = date('l', strtotime($absentDate));

                        echo "<h5>Absent Teacher Information</h5>";
                        echo "<p>Teacher ID: $teacherId</p>";
                        echo "<p>Teacher Name: $teacherName</p>";
                        echo "<p>Date: $weekdayName </p>";

                        //fetch periods the teacher is absent for on the specified date
                        $query = "SELECT DISTINCT Period, TeachingId FROM teaching WHERE TeacherId = '$teacherId' AND DayOfWeek = '$weekdayName'";
                        $result = mysqli_query($connection, $query);

                        if (mysqli_num_rows($result) > 0) {
                            //display a form to assign relief teacher for each period
                            echo "<form name='assignRelief' action='#' method='post'>";
                            echo "<table class='profileStyle'>";
                            echo "<tbody>";
                            while ($row = mysqli_fetch_assoc($result)) {
                                $teachingId = $row['TeachingId'];
                                $period = $row['Period'];
                                echo "<tr>";
                                echo "<td>Period $period Relief Teacher:</td>";
                                echo "<td>";
                                echo "<input type='hidden' name='teaching_id[$period]' value='$teachingId'>";
                                echo "<select class='form-control' name='relief_teacher[$period]'>";
                                //fetch available relief teachers
                                $query = "SELECT TeacherId, CONCAT(FirstName, ' ', LastName) AS TeacherName 
                                            FROM teacher 
                                            WHERE TeacherId  NOT IN (
                                                SELECT TeacherId 
                                                FROM teaching 
                                                WHERE Period = '$period' AND DayOfWeek = '$weekdayName'
                                            ) AND TeacherStatus = 'Active'";

                                //execute the query
                                $reliefResult = mysqli_query($connection, $query);
                                if ($reliefResult) {
                                    //display relief teachers
                                    while ($reliefRow = mysqli_fetch_assoc($reliefResult)) {
                                        echo "<option value='" . $reliefRow['TeacherId'] . "'>" . $reliefRow['TeacherName'] . "</option>";
                                    }
                                }
                                else {
                                    echo "<option value=''>No relief teachers available</option>";
                                }
                                echo "</select>";
                                echo "</td>";
                                echo "</tr>";
                            }
                            echo "<tr>";
                            echo "<td colspan='2'><input type='submit' class='btnStyle1' name='submit' value='Assign'></td>";
                            echo "</tr>";
                            echo "</tbody>";
                            echo "</table>";
                            echo "</form>";
                        } else {
                            echo "<p>No periods found for the absent teacher on this date.</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>                  
        </main>
    </div>
</body>
</html>
