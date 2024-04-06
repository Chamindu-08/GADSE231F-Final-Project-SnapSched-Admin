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
$teacherId = "";
$first_name = "";
$last_name = "";
$sure_name = "";
$assume_date = "";
$address = "";
$email = "";
$contact_no = "";
$nic = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['teacherSelect'])) {
    // Retrieve form data
    $teacherId = $_POST['teacherSelect'];

    // Get teacher details
    $sql = "SELECT * FROM teacher WHERE TeacherId='$teacherId'";
    $result = mysqli_query($connection, $sql);

    // Check if the query was successful
    if ($result) {
        // Check if there are any rows returned
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $first_name = $row['FirstName'];
            $last_name = $row['LastName'];
            $sure_name = $row['SurName'];
            $assume_date = $row['AssumeDate'];
            $address = $row['TeacherAddress'];
            $email = $row['TeacherEmail'];
            $contact_no = $row['TeacherContactNo'];
            $nic = $row['NIC'];
        } else {
            echo "<script>alert('Teacher not found.');</script>";
        }
    } else {
        echo "<script>alert('Error fetching teacher details.');</script>";
    }

    // Close the result set
    mysqli_free_result($result);
}

// Leave student button click event
if (isset($_POST['leaveStudent'])) {
    // Get teacher ID
    $teacherId = $_POST['teacherId'];

    // Get teacher details
    $sql = "SELECT * FROM teacher WHERE TeacherId='$teacherId'";
    $result = mysqli_query($connection, $sql);

    // Check if the query was successful
    if ($result) {
        // Check if there are any rows returned
        if (mysqli_num_rows($result) > 0) {
            // Delete teacher from the database
            $sqlDeleteTeacher = "UPDATE teacher SET TeacherStatus='NotAvilable', ResignDate=CURDATE() WHERE TeacherId='$teacherId'";
            $resultDeleteTeacher = mysqli_query($connection, $sqlDeleteTeacher);

            if ($resultDeleteTeacher) {
                echo "<script>alert('Teacher left successfully.');</script>";
            } else {
                echo "<script>alert('Error leaving teacher.');</script>";
            }
        } else {
            echo "<script>alert('Teacher not found.');</script>";
        }
    } else {
        echo "<script>alert('Error fetching teacher details.');</script>";
    }

    // Close the result set
    mysqli_free_result($result);
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
                
                <div id="secondFilterBar" class="row">
                    <div class="card flex-fill border-0 illustration">
                        <div class="card-body p-0 d-flex flex-fill">
                            <div class="row g-0 w-100">
                                <div class="col">
                                    <div class="p-3 m-1">
                                        <form id="formStudentSearch" method="post" action="#">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="teacherSelect" class="form-label">Select Teacher</label>
                                                    <?php
                                                        //database connection
                                                        include 'DBConnection/DBConnection.php';

                                                        if (!$connection) {
                                                            echo "Connection failed";
                                                        }

                                                        //display teacher list
                                                        $sql = "SELECT TeacherId, FirstName, LastName FROM teacher";
                                                        $result = mysqli_query($connection, $sql);

                                                        if ($result) {
                                                            echo '<select id="teacherSelect" class="grade-select-dropdown" name="teacherSelect">';
                                                            while ($row = mysqli_fetch_assoc($result)) {
                                                                echo '<option value="' . $row['TeacherId'] . '">' . $row['FirstName'] . ' ' . $row['LastName'] . '</option>';
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
                                            </div>
                                            <button id="secondSearchBtn" class="btnStyle1 mx-2" type="submit" name="submit">Search</button>
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
                                            <table class="puTable">
                                                <tr>
                                                    <td>
                                                    Teacher ID : </br>
                                                    <input type="text" name="teacherId" value="<?php echo $teacherId; ?>" readonly></td>
                                                    <td>
                                                    First Name : </br>
                                                    <input type="text" name="firstName" value="<?php echo $first_name; ?>" readonly></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                    Last Name : </br>
                                                    <input type="text" name="lastName" value="<?php echo $last_name; ?>" readonly></td>
                                                    <td>
                                                    Sure Name : </br>
                                                    <input type="text" name="sureName" value="<?php echo $sure_name; ?>" readonly></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                    Assume Date : </br>
                                                    <input type="text" name="admissionDate" value="<?php echo $assume_date; ?>" readonly></td>
                                                    <td>
                                                    Address : </br>
                                                    <input type="text" name="address" value="<?php echo $address; ?>" readonly></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                    Email : </br>
                                                    <input type="text" name="email" value="<?php echo $email; ?>" readonly></td>
                                                    <td>
                                                    Contact No : </br>
                                                    <input type="text" name="contactNo" value="<?php echo $contact_no; ?>" readonly></td>
                                                </tr>
                                                <tr>
                                                    <td>NIC : </br>
                                                    <input type="text" name="nic" value="<?php echo $nic; ?>" readonly></td>
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
