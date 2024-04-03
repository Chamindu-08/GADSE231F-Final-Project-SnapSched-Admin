<?php
//get database connection
include 'DBConnection/DBConnection.php';

//check connection
if (!$connection) {
    echo "Connection failed";
}

//check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //retrieve form data
    $admissionNo = $_POST['admissionNo'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $surName = $_POST['surName'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $grade = $_POST['grade'];
    $admissionDate = $_POST['admissionDate'];
    $guardianName = $_POST['guardianName'];
    $guardianContact = $_POST['guardianContact'];
    $emergencyContact = $_POST['emergencyContact'];
    $Password = $_POST['Password'];
    $confirmPassword = $_POST['confirmPassword'];
    
    //check all fields are filled
    if (empty($firstName) || empty($lastName) || empty($surName) || empty($dob) || empty($address) || empty($email) || empty($guardianName) || empty($guardianContact) || empty($emergencyContact) || empty($Password) || empty($confirmPassword) || empty($grade) || empty($admissionDate)){
        echo "<script>alert('Please fill all fields.');</script>";
        exit();
    }

    //validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.');</script>";
        exit();
    }

    //validate password and confirm password 8 characters long
    if (strlen($Password) < 8 || strlen($confirmPassword) < 8) {
        echo "<script>alert('Password must be at least 8 characters long.');</script>";
        exit();
    }

    //validate contact number
    if (!preg_match("/^[0-9]{10}+$/", $guardianContact) || !preg_match("/^[0-9]{10}+$/", $emergencyContact)) {
        echo "<script>alert('Invalid contact number!');</script>";
        exit();
    }

    //confirm password and password match
    if ($Password != $confirmPassword) {
        echo "<script>alert('Password and Confirm Password do not match. Please enter again.');</script>";
        exit();
    }

    //check if the student already exists
    $sqlCheckStudent = "SELECT * FROM student WHERE StudentId='$admissionNo'";
    $resultCheckStudent = mysqli_query($connection, $sqlCheckStudent);

    if (mysqli_num_rows($resultCheckStudent) > 0) {
        echo "<script>alert('Student already exists.');</script>";
        exit();
    }

    //check if the email already exists
    $sqlCheckEmail = "SELECT * FROM student WHERE StudentEmail='$email'";
    $resultCheckEmail = mysqli_query($connection, $sqlCheckEmail);

    if (mysqli_num_rows($resultCheckEmail) > 0) {
        echo "<script>alert('Email already exists.');</script>";
        exit();
    }


    //insert student details into the database
    $sqlStudent = "INSERT INTO student (StudentId, FirstName, LastName, SurName, StudentAddress, DOB, AdmissionDate, StudentEmail, StudentPassword, Grade) VALUES ('$admissionNo', '$firstName', '$lastName', '$surName', '$address', '$dob', '$admissionDate', '$email', '$Password', '$grade')";

    //insert guardian details into the database
    $sqlGuardian = "INSERT INTO currentStudent (StudentId, GuardianName, GuardianContactNo, EmergencyContactNo) VALUES ('$admissionNo', '$guardianName', '$guardianContact', '$emergencyContact')";
}

//close the database connection
mysqli_close($connection);
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Register | Admin</title>

    <!-- stylesheet -->
    <link rel="stylesheet" href="CSS/Dashboard.css" />
    <link rel="stylesheet" href="CSS/style.css" />
    <!-- Bootstrap link -->
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
                            Student Registration
                        </h4>
                    </div>
                    <div class="card-body">
                        <form name="studentProUp" action="#" method="post">
                            <table class="puTable">
                                <tr>
                                    <th colspan="2">Student Details</th>
                                </tr>
                                <tr>
                                    <td>
                                        Admission No :<br>
                                        <input type="text" name="admissionNo" required>
                                    </td>
                                <tr>
                                    <td>
                                        First Name :<br>
                                        <input type="text" name="firstName" required>
                                    </td>
                                    <td>
                                        Last Name :<br>
                                        <input type="text" name="lastName" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Surn Name :<br>
                                        <input type="text" name="surName" required>
                                    </td>
                                    <td>
                                        DOB :<br>
                                        <input type="date" name="dob" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Address :<br>
                                        <input type="text" name="address" required>
                                    </td>
                                    <td>
                                        Email :<br>
                                        <input type="email" name="email" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Grade :<br>
                                        <select name="grade" required>
                                            <option value="6">Grade 6</option>
                                            <option value="7">Grade 7</option>
                                            <option value="8">Grade 8</option>
                                            <option value="9">Grade 9</option>
                                            <option value="10">Grade 10</option>
                                            <option value="11">Grade 11</option>
                                        </select>
                                    </td>
                                    <td>
                                        Admission Date :<br>
                                        <input type="date" name="admissionDate" required>
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="2">Guardian Details</th>
                                </tr>
                                <tr>
                                    <td>
                                        Guardian Name :<br>
                                        <input type="text" name="guardianName" required>
                                    </td>
                                    <td>
                                        Guardian contact no :<br>
                                        <input type="text" name="guardianContact" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Emergency contact no :<br>
                                        <input type="text" name="emergencyContact" required>
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="2">Password</th>
                                </tr>
                                <tr>
                                    <td>
                                        New Password :<br>
                                        <input type="password" name="Password" required>
                                    </td>
                                    <td>
                                        Confirm Password :<br>
                                        <input type="password" name="confirmPassword" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td><button class="btnStyle1 mx-2">Save</button></td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script src="js/Dashboard.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
