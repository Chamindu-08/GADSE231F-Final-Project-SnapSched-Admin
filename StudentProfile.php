<?php
// Database connection
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
$admission_date = "";
$address = "";
$email = "";
$dob = "";
$guardian_name = "";
$contact_no = "";
$emergency_contact_no = "";

if (isset($_POST['studentSelect'])) {
    $studentId = $_POST['studentSelect'];

    $sql = "SELECT student.StudentId, student.FirstName, student.LastName, student.SurName, student.StudentAddress, student.DOB, student.AdmissionDate, student.StudentEmail, student.StudentPassword, student.Grade, currentStudent.GuardianName, currentStudent.GuardianContactNo, currentStudent.EmergencyContactNo FROM student INNER JOIN currentStudent ON student.StudentId = currentStudent.StudentId WHERE student.StudentId = '$studentId'";

    $result = mysqli_query($connection, $sql);

    if ($result === false) {
        echo "<script>alert('Error executing the query: " . $connection->error . "');</script>";
    }

    if ($result->num_rows > 0) {
        // output data of the first row (assuming only one student is retrieved)
        $row = $result->fetch_assoc();
        $first_name = $row["FirstName"];
        $last_name = $row["LastName"];
        $sure_name = $row["SurName"];
        $admission_no = $row["StudentId"];
        $admission_date = $row["AdmissionDate"];
        $address = $row["StudentAddress"];
        $email = $row["StudentEmail"];
        $dob = $row["DOB"];
        $guardian_name = $row["GuardianName"];
        $contact_no = $row["GuardianContactNo"];
        $emergency_contact_no = $row["EmergencyContactNo"];
    } else {
        echo "0 results";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account | Student</title>

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
                <!-- select grade panel -->
                <div class="row">
                    <div class="card flex-fill border-0 illustration">
                        <div class="card-body p-0 d-flex flex-fill">
                            <div class="row g-0 w-100">
                                <div class="col">
                                    <div class="p-3 m-1">
                                        <h4>Student Profile</h4>
                                        <form id="formYearSearch" method="post" action="#">
                                            <table>
                                                <tr>
                                                    <td>Grade</td>
                                                    <td>
                                                        <?php
                                                        // Database connection
                                                        include 'DBConnection/DBConnection.php';

                                                        if (!$connection) {
                                                            echo "Connection failed";
                                                        }

                                                        //query to retrieve grade options
                                                        $sql = "SELECT * FROM grade";
                                                        $result = mysqli_query($connection, $sql);

                                                        if ($result) {
                                                            echo '<select id="gradeSelect" class="grade-select-dropdown" name="gradeSelect">';
                                                            
                                                            //display grade options
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
                                                    </td>
                                                    <td>
                                                        <button class="btnStyle1 mx-2" type="submit" name="submit">Search</button>
                                                </tr>
                                            </table>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="secondFilterBar" class="row">
                    <div class="card flex-fill border-0 illustration">
                        <div class="card-body p-0 d-flex flex-fill">
                            <div class="row g-0 w-100">
                                <div class="col">
                                    <div class="p-3 m-1">
                                        <form id="formStudentSearch" method="post" action="#">
                                            <table>
                                                <tr>
                                                    <td>Student</td>
                                                    <td>
                                                        <?php
                                                        //database connection
                                                        include 'DBConnection/DBConnection.php';

                                                        if (!$connection) {
                                                            echo "Connection failed";
                                                        }

                                                        //check if grade is selected
                                                        if (isset($_POST['gradeSelect'])) {
                                                            $selectedGrade = $_POST['gradeSelect'];
                                                            $selectedYear = isset($_POST['year']) ? $_POST['year'] : ''; //retrieve selected year from form
                                                            $sql = "SELECT * FROM student WHERE Grade=$selectedGrade";
                                                            $result = mysqli_query($connection, $sql);

                                                            if ($result) {
                                                                echo '<select id="studentSelect" class="grade-select-dropdown" name="studentSelect">';
                                                                
                                                                //display student options
                                                                while ($row = mysqli_fetch_assoc($result)) {
                                                                    echo '<option value="' . $row['StudentId'] . '">' . $row['StudentId'] . '</option>';
                                                                }
                                                                
                                                                echo '</select>';
                                                                
                                                                mysqli_free_result($result);
                                                            } else {
                                                                echo "Error: " . mysqli_error($connection);
                                                            }
                                                        } else {
                                                            echo '<select id="studentSelect" class="grade-select-dropdown" name="studentId">';
                                                            echo '</select>';
                                                        }

                                                        //close the database connection
                                                        mysqli_close($connection);
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <button id="secondSearchBtn" class="btnStyle1 mx-2" type="submit" name="submit">Search</button>
                                                    </td>
                                                </tr>
                                            </table>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


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
                                                    <h5>Admission Date :</h5>
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
                                                    <h5>Date of birth :</h5>
                                                    <label><?php echo $dob; ?></label>
                                                </td>
                                                <td>
                                                    <h5>Guardian Name :</h5>
                                                    <label><?php echo $guardian_name; ?></label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h5>Contact No :</h5>
                                                    <label><?php echo $contact_no; ?></label>
                                                </td>
                                                <td>
                                                    <h5>Emergency Contact No :</h5>
                                                    <label><?php echo $emergency_contact_no; ?></label>
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

    <script>
        //first search button click event after display second filter bar
        document.getElementById("secondSearchBtn").addEventListener("click", function() {
            document.getElementById("secondFilterBar").style.display = "block";
        });

        //second search button click event after display student profile
        document.getElementById("secondSearchBtn").addEventListener("click", function() {
            document.getElementById("secondFilterBar").style.display = "block";
        });
    </script>
        
    <script src="js/Dashboard.js"></script>
    <!-- link Bootstap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
