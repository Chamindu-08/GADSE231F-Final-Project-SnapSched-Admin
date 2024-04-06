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
    <title>Year Timetable</title>

    <!-- Bootstrap link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="CSS/Dashboard.css">
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>

<!-- Sidebar -->
<div class="wrapper">
    <?php include 'Include/AdminSideBar.php'; ?>
    <main class="content px-3 py-2">
        <div class="container-fluid">
            <!-- Select grade panel -->
            <div class="card border-0">
                <div class="card-header">
                    <h4 class="card-title">
                        Year Timetable Generation
                    </h4>
                </div>
                <div class="card-body">
                    <form method="post" action="#">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="gradeSelect" class="form-label">Select Grade</label>
                                <?php
                                //database connection
                                include 'DBConnection/DBConnection.php';

                                //check connection
                                if (!$connection) {
                                    echo "Connection failed";
                                }

                                //get grades from the database
                                $sql = "SELECT * FROM grade";
                                $result = mysqli_query($connection, $sql);

                                if ($result) {
                                    //display grades in a dropdown list
                                    echo '<select id="gradeSelect" class="form-select" name="gradeSelect">';
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<option value="' . $row['Grade'] . '">' . $row['Grade'] . '</option>';
                                    }
                                    echo '</select>';
                                    mysqli_free_result($result);
                                } else {
                                    echo "Error: " . mysqli_error($connection);
                                }

                                //close the database connection
                                mysqli_close($connection);
                                ?>
                            </div>
                            <div class="col-md-6">
                                <button class="btnStyle1 mt-4" type="submit" name="submit">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- After search, display the form to select the teacher for each subject -->
            <?php
            //check if the form is submitted
            if (isset($_POST['submit'])) {
                $grade = $_POST['gradeSelect'];

                //subjects for each grade
                $subjects = [];

                //get subjects for each grade
                if ($grade >= 6 && $grade <= 9) {
                    $subjects = [
                        "Buddhism" => 2,
                        "Sinhala" => 5,
                        "English" => 5,
                        "Mathematics" => 5,
                        "Science" => 5,
                        "History" => 2,
                        "Geography" => 2,
                        "CivicEducation" => 2,
                        "Aestetic" => 3,
                        "Tamil" => 2,
                        "HealthAndPhysicalEducation" => 2,
                        "PTS" => 3,
                        "ICT" => 1,
                        "Library" => 1
                    ];
                } else if ($grade == 10 || $grade == 11) {
                    $subjects = [
                        "Buddhism" => 3,
                        "Sinhala" => 6,
                        "English" => 5,
                        "Mathematics" => 7,
                        "Science" => 6,
                        "History" => 3,
                        "Optianal01" => 3,
                        "Optianal02" => 3,
                        "Optianal03" => 3,
                        "Library" => 1
                    ];
                }

                //display the form to select teacher for each subject
                echo '<div class="row">';
                echo '<div class="card flex-fill border-0">';
                echo '<div class="card-body p-0 d-flex flex-fill">';
                echo '<div class="row g-0 w-100">';
                echo '<div class="col">';
                echo '<div class="p-3 m-1">';
                echo '<h4>Teacher Selection</h4>';
                echo '<form id="formTeacherSelect" method="post" action="#">';
                echo '<table>';

                //display subjects and dropdown list to select teacher
                foreach ($subjects as $subject => $periods) {
                    echo "<tr>";
                    echo "<td>$subject</td>";
                    echo "<td>";
                    echo "<select class='teacher-select-dropdown' name='$subject'>";

                    //get database connection
                    include 'DBConnection/DBConnection.php';

                    //check connection
                    if (!$connection) {
                        echo "Connection failed";
                    }

                    //get teachers from the database
                    //join teacher and subject and optional subject table
                    $sql = "SELECT teacher.TeacherId, CONCAT(teacher.FirstName, ' ', teacher.LastName) AS TeacherName
                            FROM teachSubject
                            JOIN subjects ON teachSubject.SubjectId = subjects.SubjectId
                            JOIN teacher ON teachSubject.TeacherId = teacher.TeacherId
                            WHERE subjects.SubjectName = '$subject'";

                    $result = mysqli_query($connection, $sql);

                    if (!$result) {
                        echo "Error: " . mysqli_error($connection);
                    }

                    //display teachers in a dropdown list
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row['TeacherId'] . "'>" . $row['TeacherName'] . "</option>";
                    }

                    echo "</select>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo '</table>';
                echo '<button class="btnStyle1 mx-2" type="submit" name="generate">Generate Timetable</button>';
                echo '</form>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </main>
</div>
</body>
</html>
