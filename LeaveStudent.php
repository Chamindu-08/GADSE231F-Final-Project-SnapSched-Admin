<?php
// Database connection
include 'DBConnection/DBConnection.php';

// Check connection
if (!$connection) {
    echo "Connection failed";
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

// Initialize variables to store retrieved values
$studentId = ""; 
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
        $studentId = $row["StudentId"];
        $first_name = $row["FirstName"];
        $last_name = $row["LastName"];
        $sure_name = $row["SurName"];
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

if (isset($_POST['leaveStudent'])) {
    $studentId = $_POST['studentId'];

    //update student status to no
    $sql = "UPDATE student SET StudentStatus = 'no' Grade = NULL WHERE StudentId = '$studentId'";

    //insert student details to old student table
    $sqlInsertOldStudent = "INSERT INTO oldStudent (StudentId, LeavingYear) VALUES ('$studentId', YEAR(CURDATE()))";

    //execute the queries
    $result = mysqli_query($connection, $sql);
    $resultInsertOldStudent = mysqli_query($connection, $sqlInsertOldStudent);

    if ($result && $resultInsertOldStudent) {
        echo "<script>alert('Student left successfully');</script>";
    } else {
        echo "<script>alert('Error leaving student: " . mysqli_error($connection) . "');</script>";
    }

    //close the database connection
    if (isset($connection)) {
        mysqli_close($connection);
    }

}

//close the database connection
if (isset($connection)) {
    mysqli_close($connection);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Student | Admin</title>

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
                                Student Details 
                            </h4>
                        </div>
                        
                        <div class="col-12 col-md-9 d-flex">
                            <div class="card flex-fill border-0">
                                <div class="card-body py-4">
                                    <div class="row d-flex align-items-start">
                                        <!-- display student details and leave form , leave student button -->
                                        <form id="formLeaveStudent" method="post" action="#">
                                            <table width="100%">
                                                <tr>
                                                    <td>
                                                    Student ID : </td>
                                                    <td><input type="text" name="studentId" value="<?php echo $studentId; ?>" readonly></td>
                                                    <td>
                                                    First Name : </td>
                                                    <td><input type="text" name="firstName" value="<?php echo $first_name; ?>" readonly></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                    Last Name : </td>
                                                    <td><input type="text" name="lastName" value="<?php echo $last_name; ?>" readonly></td>
                                                    <td>
                                                    Sure Name : </td>
                                                    <td><input type="text" name="sureName" value="<?php echo $sure_name; ?>" readonly></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                    Admission Date : </td>
                                                    <td><input type="text" name="admissionDate" value="<?php echo $admission_date; ?>" readonly></td>
                                                    <td>
                                                    Address : </td>
                                                    <td><input type="text" name="address" value="<?php echo $address; ?>" readonly></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                    Email : </td>
                                                    <td><input type="text" name="email" value="<?php echo $email; ?>" readonly></td>
                                                    <td>
                                                    Date of Birth : </td>
                                                    <td><input type="text" name="dob" value="<?php echo $dob; ?>" readonly></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                    Guardian Name : </td>
                                                    <td><input type="text" name="guardianName" value="<?php echo $guardian_name; ?>" readonly></td>
                                                    <td>
                                                    Contact No : </td>
                                                    <td><input type="text" name="contactNo" value="<?php echo $contact_no; ?>" readonly></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                    Emergency Contact No : </td>
                                                    <td><input type="text" name="emergencyContactNo" value="<?php echo $emergency_contact_no; ?>" readonly></td>
                                                </tr>
                                                <tr>
                                                    <td><button class="btnStyle1 mx-2" type="submit" name="leaveStudent">Leave Student</button></td>
                                                </tr>
                                            </table>
                                        </form> 
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
