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

if(isset($_POST["date"], $_POST["announcement"])) {
    //get the form data
    $Date = $_POST["date"];
    $Subject = $_POST["subject"];
    $Announcement = $_POST["announcement"];

    //error message variable
    $errorMessage = "";

    // Check if teacher is set, if not, set it to empty string
    $Student = isset($_POST["student"]) ? $_POST["student"] : "";
    $Teacher = isset($_POST["teacher"]) ? $_POST["teacher"] : ""; 

    //validate fields
    if (empty($Date) || empty($Announcement) || empty($Subject)) {
        $errorMessage = "Please fill in all the fields.";
    } else {
        //include database connection
        include 'DBConnection/DBConnection.php';

        //check if the connection to the database failed
        if (!$connection) {
            echo "Database connection failed. Please try again later.";
        }

        $errorMessage = "";

        //determine the recipient
        if (!empty($Student) && !empty($Teacher)) {
            $Recipient = 'both';
        } elseif (!empty($Student)) {
            $Recipient = 'student';
        } elseif (!empty($Teacher)) {
            $Recipient = 'teacher';
        } else {
            $errorMessage = "Please select the recipient.";
        }

        if (empty($errorMessage)) {
            //get the latest AbsentId from the database to determine the next ID
            $SQLAnnouncement = "SELECT * FROM announcement";
            $result = mysqli_query($connection, $SQLAnnouncement);

            if ($row = mysqli_fetch_assoc($result)) {
                $lastAnnouncementId = $row['AnnouncementId'];
                $lastNumber = intval(substr($lastAnnouncementId, 1));
                $nextNumber = $lastNumber + 1;
                $nextAnnouncementId = 'A' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            } else {
                //default value for the first record
                $nextAnnouncementId = 'A001';
            }

            //insert data into the database
            $sql = "INSERT INTO announcement (AnnouncementId, Subject, Announcement, Recipient, AnnouncementDate) VALUES ('$nextAnnouncementId','$Subject', '$Announcement','$Recipient', '$Date')";

            //execute the query
            $result = mysqli_query($connection, $sql);

            if ($result) {
                echo "<script>alert('Announcement created successfully.');</script>";
            } else {
                echo "<script>alert('Error: " . $sql . "<br>" . mysqli_error($connection) . "');</script>";
            }
        }

        //close the database connection
        mysqli_close($connection);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcement | Teacher</title>

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
            <div class="container">
                <!-- Table Element -->
                <div class="card border-0">
                    <div class="card-header">
                        <h4 class="card-title">
                            Announcement
                        </h4>
                    </div>
                    <?php if (!empty($errorMessage)) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $errorMessage; ?>
                        </div>
                    <?php endif; ?>
                    <div class="card-body table-responsive">
                        <form name="teacherProUp" action="#" method="post">
                            <table class="profileStyle">
                                <tbody>
                                    <tr>
                                        <td>
                                            Date : </br>
                                            <input type="date" class="form-control" name="date" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Subject :
                                            <input type="text" class="form-control" name="subject" required>
                                        </td>
                                    <tr>
                                        <td>
                                            Announcement :
                                            <textarea class="form-control" rows="10" name="announcement" required></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="student" value="student">Student
                                            <input type="checkbox" name="teacher" value="teacher">Teacher
                                        </td>
                                    <tr>
                                        <td colspan="2"><button type="submit" class="btnStyle1 mx-2">Submit</button></td>
                                    </tr>
                                </tbody>
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