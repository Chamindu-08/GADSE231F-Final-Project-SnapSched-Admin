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
                        <table>
                            <tr>
                                <td>
                                    <label for="grade">Select Grade:</label>
                                </td>
                                <td>
                                    <select id="gradeSelect" class="grade-select-dropdown" name="gradeSelect">
                                        <option value="6">Grade 6</option>
                                        <option value="7">Grade 7</option>
                                        <option value="8">Grade 8</option>
                                        <option value="9">Grade 9</option>
                                        <option value="10">Grade 10</option>
                                        <option value="11">Grade 11</option>
                                    </select>
                                </td>
                                <td>
                                    <button class="btnStyle1 mx-2" type="submit" name="submit">Search</button>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>

            <!-- After search, display the form to select the teacher for each subject -->
            <?php
            // Check if the form is submitted
            if (isset($_POST['submit'])) {
                $grade = $_POST['gradeSelect'];
                $subjects = []; // Initialize $subjects array

                if ($grade >= 6 && $grade <= 9) {
                    $subjects = [
                        "Buddhism" => 2,
                        "Sinhala" => 5,
                        "English" => 5,
                        "Mathematics" => 5,
                        "Science" => 5,
                        "History" => 2,
                        "Geography" => 2,
                        "Civic Education" => 2,
                        "Aestetic" => 3,
                        "Tamil" => 2,
                        "Health & Physical Education" => 2,
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
                        "Optianal 01" => 3,
                        "Optianal 02" => 3,
                        "Optianal 03" => 3,
                        "Library" => 1
                    ];
                }

                // Display the form to select teacher for each subject
                echo '<div class="row">';
                echo '<div class="card flex-fill border-0">';
                echo '<div class="card-body p-0 d-flex flex-fill">';
                echo '<div class="row g-0 w-100">';
                echo '<div class="col">';
                echo '<div class="p-3 m-1">';
                echo '<h4>Teacher Selection</h4>';
                echo '<form id="formTeacherSelect" method="post" action="#">';
                echo '<table>';
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

                    // Get teachers from the database
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
