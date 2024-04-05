<?php
//get database connection
include 'DBConnection/DBConnection.php';

//check connection
if (!$connection) {
    echo "Connection failed";
}

//initialize validation messages variable
$validationMessages = "";

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

//check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //retrieve form data
    $teacher_id = $_POST['teacher_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $sur_name = $_POST['sur_name'];
    $address = $_POST['address'];
    $contact_no = $_POST['contact_no'];
    $email = $_POST['email'];
    $nic = $_POST['nic'];
    $assume_date = $_POST['assume_date'];
    $subject = $_POST['subject'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    
    //check all fields are filled
    if (empty($teacher_id) || empty($first_name) || empty($last_name) || empty($sur_name) || empty($address) || empty($contact_no) || empty($email) || empty($nic) || empty($assume_date) || empty($password) || empty($confirmPassword) || empty($subject)){
        $validationMessages .= "Please fill all fields. ";
    }

    //validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $validationMessages .= "Invalid email format. ";
    }

    //validate contact number
    if (!preg_match("/^[0-9]{10}+$/", $contact_no)) {
        $validationMessages .= "Invalid contact number. ";
    }

    //validate if nic in 12 characters only numbers
    if (strlen($nic) == 12 && !preg_match("/^[0-9]+$/", $nic)) {
        $validationMessages .= "Invalid NIC. ";
    }

    //validate if nic in 10 characters only 9 numbers and last character is V or v
    if (strlen($nic) == 10 && !preg_match("/^[0-9]{9}[Vv]$/", $nic)) {
        $validationMessages .= "Invalid NIC. ";
    }

    //validate password and confirm password 4 characters long
    if (strlen($password) < 4 || strlen($confirmPassword) < 4) {
        $validationMessages .= "Password must be at least 4 characters long. ";
    }

    //confirm password and password match
    if ($password != $confirmPassword) {
        $validationMessages .= "Passwords do not match. ";
    }
    //check if teacher ID exists in the database
    $check = "SELECT * FROM teacher WHERE TeacherId='$teacher_id'";
    $result = mysqli_query($connection, $check);

    if (mysqli_num_rows($result) > 0) {
        $validationMessages .= "Teacher ID already exists. ";
    }

    //check if NIC exists in the database
    $check = "SELECT * FROM teacher WHERE NIC='$nic'";
    $result = mysqli_query($connection, $check);

    if (mysqli_num_rows($result) > 0) {
        $validationMessages .= "NIC already exists. ";
    }

    //check if email exists in the database
    $check = "SELECT * FROM teacher WHERE TeacherEmail='$email'";
    $result = mysqli_query($connection, $check);

    if (mysqli_num_rows($result) > 0) {
        $validationMessages .= "Email already exists. ";
    }

    //if there are validation errors, display them
    if (!empty($validationMessages)) {
        $validationMessages = '<div class="alert alert-danger">' . $validationMessages . '</div>';
    } else {
        //insert teacher details into the database
        $sqlTeacher = "INSERT INTO teacher (TeacherId, FirstName, LastName, SurName, TeacherAddress, TeacherContactNo, NIC, TeacherEmail, TeacherPassword, AssumeDate, TeacherStatus) VALUES ('$teacher_id', '$first_name', '$last_name', '$sur_name', '$address', '$contact_no', '$nic', '$email', '$password', '$assume_date', 'Available')";

        //teacher teach subjects
        $sqlTeacherTeach = "INSERT INTO teach (TeacherId, SubjectId) VALUES ('$teacher_id', '$subject')";
        //execute the query
        $resultTeacher = mysqli_query($connection, $sqlTeacher);
        $resultTeacherTeach = mysqli_query($connection, $sqlTeacherTeach);

        //check if the query executed successfully
        if ($resultTeacher && $resultTeacherTeach) {
            $validationMessages = '<div class="alert alert-success">Teacher registered successfully</div>';

            //send email to the teacher
         /*   $to = $email;
            $subject = "Account Registration";
            $message = "Dear " . $first_name . " " . $last_name . ",\n\nYour account has been successfully registered.\n\nYour login details are as follows:\nEmail: " . $email . "\nPassword: " . $password . "\n\nPlease login to the system to view your account details.\n\nThank you.";
            $headers = "From: School Management System";

            mail($to, $subject, $message, $headers);  */
        } else {
            $validationMessages = '<div class="alert alert-danger">Error: ' . mysqli_error($connection) . '</div>';
        }

        //clear form data
        $teacher_id = $first_name = $last_name = $sur_name = $address = $contact_no = $email = $nic = $assume_date = $password = $confirmPassword = $subject = "";
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
    <title>Account Update | Teacher</title>

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
                <!-- Table Element -->
                <div class="card border-0">
                    <div class="card-header">
                        <h4 class="card-title">
                            Teacher Registration
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php echo $validationMessages; ?>
                        <form name="teacherProUp" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <table class="puTable">
                                <tr>
                                    <th colspan="2">Teacher Details</th>
                                </tr>
                                <tr>
                                    <td>
                                        Teacher ID:<br>
                                        <input type="text" name="teacher_id" required>
                                    </td>
                                    <td>
                                        First Name:<br>
                                        <input type="text" name="first_name" required>
                                    </td>
                                    <!-- Add other fields here -->
                                </tr>
                                <tr>
                                    <td>
                                        Last Name:<br>
                                        <input type="text" name="last_name" required>
                                    </td>
                                    <td>
                                        Surn Name:<br>
                                        <input type="text" name="sur_name" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Address:<br>
                                        <input type="text" name="address" required>
                                    </td>
                                    <td>
                                        Contact no:<br>
                                        <input type="text" name="contact_no" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Email:<br>
                                        <input type="email" name="email" required>
                                    </td>
                                    <td>
                                        NIC:<br>
                                        <input type="text" name="nic" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Assume Date:<br>
                                        <input type="date" name="assume_date" required>
                                    </td>
                                    <td>
                                        Subject:<br>
                                        <select name="subject" required>
                                            <!-- Fetch subjects from the database -->
                                            <?php
                                            //get database connection
                                            include 'DBConnection/DBConnection.php';

                                            //check connection
                                            if (!$connection) {
                                                echo "Connection failed";
                                            }

                                            //SQL query to select all subjects
                                            $sql = "SELECT * FROM subjects";
                                            $result = mysqli_query($connection, $sql);

                                            if ($result) {
                                                // Display subjects in dropdown
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo '<option value="' . $row['SubjectId'] . '">' . $row['SubjectName'] . '</option>';
                                                }
                                                mysqli_free_result($result);
                                            } else {
                                                echo "Error: " . mysqli_error($connection);
                                            }

                                            //close the database connection
                                            mysqli_close($connection);
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="2">Password</th>
                                </tr>
                                <tr>
                                    <td>
                                        Password:<br>
                                        <input type="password" name="password" required>
                                    </td>
                                    <td>
                                        Confirm Password:<br>
                                        <input type="password" name="confirmPassword" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <button type="submit" class="btnStyle1 mx-2">Save</button>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="js/Dashboard.js"></script>
    <!-- link Bootstap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
