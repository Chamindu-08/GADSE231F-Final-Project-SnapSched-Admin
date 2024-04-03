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
        echo "<script>alert('Please fill all fields.');</script>";
        exit();
    }

    //validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.');</script>";
        exit();
    }

    //validate password and confirm password 8 characters long
    if (strlen($password) < 8 || strlen($confirmPassword) < 8) {
        echo "<script>alert('Password must be at least 8 characters long.');</script>";
        exit();
    }

    //validate contact number
    if (!preg_match("/^[0-9]{10}+$/", $contact_no)) {
        echo "<script>alert('Invalid contact number!');</script>";
        exit();
    }

    //validate NIC 12 or 10 characters long
    if (strlen($nic) != 12 && strlen($nic) != 10) {
        echo "<script>alert('Invalid NIC!');</script>";
        exit();
    }

    //confirm password and password match
    if ($password != $confirmPassword) {
        echo "<script>alert('Password and Confirm Password do not match. Please enter again.');</script>";
        exit();
    }
    //check if teacher ID exists in the database
    $check = "SELECT * FROM teacher WHERE TeacherId='$teacher_id'";
    $result = mysqli_query($connection, $check);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Teacher ID already exists!');</script>";
        exit();
    }

    //check if NIC exists in the database
    $check = "SELECT * FROM teacher WHERE NIC='$nic'";
    $result = mysqli_query($connection, $check);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('NIC already exists!');</script>";
        exit();
    }

    //check if email exists in the database
    $check = "SELECT * FROM teacher WHERE TeacherEmail='$email'";
    $result = mysqli_query($connection, $check);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Email already exists!');</script>";
        exit();
    }

    //insert teacher details into the database
    $sqlTeacher = "INSERT INTO teacher (TeacherId, FirstName, LastName, SurName, TeacherAddress, TeacherContactNo, NIC, TeacherEmail, TeacherPassword, AssumeDate, TeacherStatus) VALUES ('$teacher_id', '$first_name', '$last_name', '$sur_name', '$address', '$contact_no', '$nic', '$email', '$password', '$assume_date', 'Avilable')";

    //teacher teach subjects
    $sqlTeacherTeach = "INSERT INTO teach (TeacherId, SubjectId) VALUES ('$teacher_id', '$subject')";
    //execute the query
    $result = mysqli_query($connection, $sqlTeacher);

    //check if the query is executed
    if ($result) {
        echo "<script>alert('Teacher registered successfully.');</script>";
    } else {
        echo "<script>alert('Error: " . $sqlTeacher . "<br>" . mysqli_error($connection) . "');</script>";
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
                            <form name="teacherProUp" action="#" method="post">
                                <table class="puTable">
                                    <tr>
                                        <th colspan="2">Teacher Details</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            Teacher ID :<br>
                                            <input type="text" name="teacher_id" required>
                                    <tr>
                                        <td>
                                            First Name :<br>
                                            <input type="text" name="first_name" required>
                                        </td>
                                        <td>
                                            Last Name :<br>
                                            <input type="text" name="last_name" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Surn Name :<br>
                                            <input type="text" name="sur_name" required>
                                        </td>
                                        <td>
                                            Address :<br>
                                            <input type="text" name="address" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Contact no :<br>
                                            <input type="text" name="contact_no" required>
                                        </td>
                                        <td>
                                            Email :<br>
                                            <input type="email" name="email" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            NIC :<br>
                                            <input type="text" name="nic" required>
                                        </td>
                                        <td>
                                            Assume Date :<br>
                                            <input type="date" name="assume_date" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Subject</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            Subject :<br>
                                            <select name="subject" required>
                                                <!-- fetch subjects from the database -->
                                                <?php
                                                //get database connection
                                                include 'DBConnection/DBConnection.php';

                                                //check connection
                                                if (!$connection) {
                                                    echo "Connection failed";
                                                }

                                                //SQL query select all subjects
                                                $sql = "SELECT * FROM subjects";

                                                $result = mysqli_query($connection, $sql);

                                                if ($result) {
                                                    //display subjects in dropdown
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
                                    <tr>
                                        <th colspan="2">Password</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            Password :<br>
                                            <input type="password" name="password" required>
                                        </td>
                                        <td>
                                            Confirm Password :<br>
                                            <input type="password" name="confirmPassword" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><button type="submit" class="btnStyle1 mx-2">Save</button></td>
                                    </tr>
                                    
                                  </table>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>


    <script src="js/Dashboard.js"></script>
    <!-- link Bootstap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
