<?php
//database connection
include 'DBConnection/DBConnection.php';

//check connection
if (!$connection) {
    echo "Connection failed";
}

//check if the cookie is set
if (!isset($_COOKIE['adminEmail'])) {
    //redirect to login page if the cookie is not set
    echo '<script>
            var confirmMsg = confirm("Your session has timed out. Please log in again.");
            if (confirmMsg) {
                window.location.href = "AdminLoginRegister.php";
            }
          </script>';
    exit();
}

//initialize variables
$grade = $year = $weekday = $timetable = $subjectTeachers = $message = $errorMessages = "" ;

//check if the form is submitted
if (isset($_POST['generate'])) {
    //initialize variables
    $grade = $_POST['grade'];
    $year = $_POST['year'];
    $weekday = $_POST['weekday'];

    //initialize timetable and subjectTeachers arrays and store subjectId and teacherId
    $timetable = $subjectTeachers = [];
    $timetable1 = $subjectTeachers1 = [];

    //loop through the submitted data to populate timetable and subjectTeachers arrays
    for ($period = 1; $period <= 8; $period++) {
        $subject = $_POST['subject' . $period];

        //get subjectId from the database
        $sql = "SELECT SubjectId FROM subjects WHERE SubjectName = ?";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "s", $subject);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        $subjectId = $row['SubjectId'];

        $teacherId = $_POST['teacher'][$subject];

        //store subject and teacher in the timetable and subjectTeachers arrays
        $timetable[$period] = $subject;
        $subjectTeachers[$subject] = $teacherId;

        //store subjectId and teacherId in the timetable1 and subjectTeachers1 arrays
        $timetable1[$period] = $subjectId;
        $subjectTeachers1[$subjectId] = $teacherId;

    }

    //check if the timetable is generated
    if (isset($timetable1) && !empty($timetable1)) {
        //check if the timetable already exists
        $sql = "SELECT * FROM teaching WHERE Grade = ? AND TeachingYear = ? AND DayOfWeek = ?";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "iss", $grade, $year, $weekday);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            //timetable already exists
            $errorMessages = "Timetable already exists for Grade $grade - Year $year - $weekday";
        } else {
            //insert records into the database
            $sql = "INSERT INTO teaching (TeachingId, Grade, TeacherId, SubjectId, TeachingYear, Period, DayOfWeek) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($connection, $sql);

            //check if the statement is prepared
            if ($stmt) {
                //loop through the timetable1 and subjectTeachers1 arrays to insert records into the database
                foreach ($timetable1 as $period => $subjectId) {
                    $teacherId = $subjectTeachers1[$subjectId];

                    //get maximum teachingId from the database
                    $sql = "SELECT MAX(TeachingId) AS MaxTeachingId FROM teaching";
                    $result = mysqli_query($connection, $sql);
                    $row = mysqli_fetch_assoc($result);
                    $teachingId = $row['MaxTeachingId'];

                    //increment teachingId by 1
                    if ($teachingId == NULL) {
                        $teachingId = 1;
                    } else {
                        $teachingId++;
                    }

                    //insert records into the database
                    mysqli_stmt_bind_param($stmt, "iisssis", $teacherId, $grade, $teacherId, $subjectId, $year, $period, $weekday);
                    mysqli_stmt_execute($stmt);

                    //check if the records are inserted
                    if (mysqli_stmt_affected_rows($stmt) <= 0) {
                        $errorMessages = "Error inserting records: " . mysqli_error($connection);
                    }
                }

                //all records inserted successfully
                $message = "Timetable generated successfully for Grade $grade - Year $year - $weekday";

                //close the statement
                mysqli_stmt_close($stmt);
            } else {
                //statement is not prepared
                $errorMessages = "Error preparing statement: " . mysqli_error($connection);
            }
        }
    } else {
        //timetable is not generated
        $errorMessages = "Error generating timetable";
    }
}

//close the database connection
mysqli_close($connection);
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

    <!-- Stylesheets -->
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .card {
            margin-bottom: 20px;
        }
        @media (max-width: 768px) {
            .card-body {
                padding: 10px;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="wrapper">
    <?php include 'Include/AdminSideBar.php'; ?>
    <main class="content px-3 py-2">
        <div class="container-fluid">
            <!-- First form to select grade, year, and weekday -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        Year Timetable Generation Manual
                    </h4>
                </div>
                <div class="card-body">
                    <form id="formFirst" method="post" action="#">
                        <div class="row mb-3">
                            <div class="col">
                                <label for="grade" class="form-label">Select Grade:</label>
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
                                    echo '<select id="gradeSelect" class="form-select" name="grade">';
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
                            <div class="col">
                                <!-- Select the current year and the next year -->
                                <label for="year" class="form-label">Select Year:</label>
                                <select id="yearSelect" class="form-select" name="year">
                                    <option value="<?php echo date("Y"); ?>"><?php echo date("Y"); ?></option>
                                    <option value="<?php echo date("Y") + 1; ?>"><?php echo date("Y") + 1; ?></option>
                                </select>
                            </div>
                            <div class="col">
                                <!-- Select the weekday -->
                                <label for="weekdays" class="form-label">Select Weekday:</label>
                                <select id="weekdaySelect" class="form-select" name="weekday">
                                    <option value="Monday">Monday</option>
                                    <option value="Tuesday">Tuesday</option>
                                    <option value="Wednesday">Wednesday</option>
                                    <option value="Thursday">Thursday</option>
                                    <option value="Friday">Friday</option>
                                </select>
                            </div>
                        </div>
                        <button id="nextButton" class="btnStyle1 mt-3" name="next">Next</button>
                    </form>
                </div>
            </div>

            <!-- Second form to select subjects and teachers -->
            <div class="row justify-content-center text-center">
                <div class="col">
                    <div class="card border-0">
                        <div class="card-header">
                            <h4 class="card-title">
                                Generate Timetable
                            </h4>
                        </div>
                        <div class="justify-content-center">
                            <div class="card-body">
                                <form id="formTeacherSelect" method="post" action="#">
                                    <table>
                                        <?php
                                        //get the grade, year, and weekday from the first form
                                        if (isset($_POST['next'])) {
                                            $grade = $_POST['grade'];
                                            $year = $_POST['year'];
                                            $weekday = $_POST['weekday'];

                                            //database connection
                                            include 'DBConnection/DBConnection.php';

                                            //check connection
                                            if (!$connection) {
                                                echo "Connection failed";
                                            }

                                            //initialize subjects array
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
                                            echo '<h4>Teacher Selection</h4>';
                                            echo '<form id="formTeacherSelect" method="post" action="#">';
                                            echo '<table class="justify-content-center">';
                                            foreach ($subjects as $subject => $periods) {
                                                echo "<tr>";
                                                echo "<td>$subject</td>";
                                                echo "<td>";
                                                echo "<select class='form-select' name='teacher[$subject]'>";

                                                //get database connection
                                                include 'DBConnection/DBConnection.php';

                                                //check connection
                                                if (!$connection) {
                                                    echo "Connection failed";
                                                }

                                                // Get teachers from the database
                                                //join teacher and subject and optional subject table
                                                $sql = "SELECT teacher.TeacherId, CONCAT(teacher.FirstName, ' ', teacher.LastName) AS TeacherName
                                                        FROM teachSubject
                                                        JOIN subjects ON teachSubject.SubjectId = subjects.SubjectId
                                                        JOIN teacher ON teachSubject.TeacherId = teacher.TeacherId
                                                        WHERE subjects.SubjectName = ?";

                                                //prepare the statement
                                                $stmt = mysqli_prepare($connection, $sql);
                                                mysqli_stmt_bind_param($stmt, "s", $subject);
                                                mysqli_stmt_execute($stmt);
                                                $result = mysqli_stmt_get_result($stmt);

                                                //check if the result is returned
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

                                            //after display 8 periods, and each period have a subject selection dropdown
                                            echo '<div class="row">';
                                            echo '<div class="col">';
                                            echo '<div class="p-3 m-1">';
                                            echo '<h4>Subject Selection</h4>';
                                            echo '<div class="text-center">';
                                            echo '<table>';
                                            echo '<thead>';
                                            echo '<tr>';
                                            echo '<th>Period</th>';
                                            echo '<th>Subject</th>';
                                            echo '</tr>';
                                            echo '</thead>';
                                            echo '<tbody>';

                                            //start the loop to display the subjects
                                            for ($period = 1; $period <= 8; $period++) :
                                                
                                                echo '<tr>';
                                                echo '<td>';
                                                echo '<label for="period' . $period . '">Period ' . $period . '</label>';
                                                echo '</td>';
                                                echo '<td>';

                                                // Display the subjects in a dropdown list
                                                echo '<select class="form-select" name="subject' . $period . '">';
                                                foreach ($subjects as $subject => $periods) {
                                                    echo '<option value="' . $subject . '">' . $subject . '</option>';
                                                }
                                                echo '</select>';
                                                echo '</td>';
                                                echo '</tr>';

                                            //end the loop
                                            endfor;

                                            echo '</tbody>';
                                            echo '</table>';

                                            //hidden inputs to store the grade, year, and weekday
                                            echo '<input type="hidden" name="grade" value="' . $grade . '">';
                                            echo '<input type="hidden" name="year" value="' . $year . '">';
                                            echo '<input type="hidden" name="weekday" value="' . $weekday . '">';

                                            echo '</div>';
                                            echo '</div>';
                                            echo '</div>';
                                            echo '</div>';

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
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Display the timetable here -->
            <div class="row justify-content-center text-center">
                <div class="col">
                    <div class="card border-0">
                        <div class="card-header">
                            <h4 class="card-title">
                                <?php echo 'Timetable for Grade ' . $grade . ' - Year ' . $year . ' - ' . $weekday; ?>
                            </h4>
                        </div>
                        <div class="card-body">
                            <?php
                            //check if the timetable is generated
                            if ((isset($timetable) && !empty($timetable)) || (isset($message) && !empty($message)) || (isset($errorMessages) && !empty($errorMessages))){
                                echo '<table class="table table-bordered">';
                                echo '<thead>';
                                echo '<tr>';
                                echo '<th>Period</th>';
                                echo '<th>Subject</th>';
                                echo '<th>Teacher</th>';
                                echo '</tr>';
                                echo '</thead>';
                                echo '<tbody>';
                                foreach ($timetable as $period => $subject) {
                                    echo '<tr>';
                                    echo '<td>' . $period . '</td>';
                                    echo '<td>' . $subject . '</td>';
                                    //check if the subject has a corresponding teacher
                                    if (isset($subjectTeachers[$subject])) {
                                        echo '<td>' . $subjectTeachers[$subject] . '</td>';
                                    } else {
                                        echo '<td>No teacher assigned</td>';
                                    }
                                    echo '</tr>';
                                }
                                echo '</tbody>';
                                echo '</table>';

                                //display the message
                                if (isset($message) && !empty($message)) {
                                    echo '<div class="alert alert-success">' . $message . '</div>';
                                }

                                //display the error message
                                if (isset($errorMessages) && !empty($errorMessages)) {
                                    echo '<div class="alert alert-danger">' . $errorMessages . '</div>';
                                }
                            } else {
                                echo '<p>No timetable generated yet.</p>';
                            }
                            ?>
                            <a href="YearTimeTableManual.php" class="btnStyle1">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

</body>
</html>
