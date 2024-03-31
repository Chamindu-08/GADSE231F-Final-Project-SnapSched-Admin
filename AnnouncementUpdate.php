<?php
//include database connection
include 'DBConnection/DBConnection.php';

//check if the connection to the database failed
if (!$connection) {
    echo "Database connection failed. Please try again later.";
}

//check if the form is submitted
if (isset($_POST['date'], $_POST['announcement_id'])) {
    //get the form data
    $date = $_POST['date'];
    $subject = $_POST['subject'];
    $announcement = $_POST['announcement'];
    $announcement_id = $_POST['announcement_id'];

    // Check if teacher is set, if not, set it to empty string
    $student = isset($_POST['student']) ? $_POST['student'] : '';
    $teacher = isset($_POST['teacher']) ? $_POST['teacher'] : '';

    //validate fields
    if (empty($date) || empty($announcement) || empty($subject) || (empty($student) && empty($teacher))) {
        echo "<script>alert('All fields are required.');</script>";
    } else {
        //determine the recipient
        if (!empty($student) && !empty($teacher)) {
            $recipient = 'both';
        } elseif (!empty($student)) {
            $recipient = 'student';
        } elseif (!empty($teacher)) {
            $recipient = 'teacher';
        } else {
            echo "<script>alert('Please select the recipient.');</script>";
            exit();
        }

        //update data into the database
        $sql = "UPDATE announcement SET Subject = '$subject', Announcement = '$announcement', Recipient = '$recipient', AnnouncementDate = '$date' WHERE AnnouncementId = '$announcement_id'";

        //execute the query
        $result = mysqli_query($connection, $sql);

        if ($result) {
            echo "<script>alert('Successful.');</script>";
        } else {
            echo "<script>alert('Error: " . $sql . "<br>" . mysqli_error($connection) . "');</script>";
        }
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
                    <!-- select announcement to update form database load to combo box-->
                    <div class="card-body table-responsive">
                        <form name="teacherProUp" action="#" method="post">
                            <table class="profileStyle">
                                <tbody>
                                    <tr>
                                        <td>
                                            Announcement :
                                            <select class="form-control" name="announcement_id">
                                                <?php
                                                //include database connection
                                                include 'DBConnection/DBConnection.php';

                                                //check if the connection to the database failed
                                                if (!$connection) {
                                                   echo "Database connection failed. Please try again later.";
                                                }

                                                //query to select announcement
                                                $query = "SELECT * FROM announcement";
                                                //execute the query
                                                $result = mysqli_query($connection, $query);
                                                //check if the query is executed
                                                if ($result) {
                                                    //fetch the result
                                                    while ($row = mysqli_fetch_array($result)) {
                                                        //display the announcement
                                                        echo "<option value='" . $row['AnnouncementId'] . "'>" . $row['Subject'] . "</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="submit" class="btnStyle1" name="submit" value="Update">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>

                    <!-- click update button display each announcement values in text box and click on the save button update the announcement -->
                    <div class="card-body table-responsive">
                        <form name="teacherProUp" action="#" method="post">
                            <table class="profileStyle">
                                <?php
                                // Initialize variables to avoid undefined variable notices
                                $date = "";
                                $subject = "";
                                $announcement = "";

                                // Check if the form is submitted and process the data
                                if (isset($_POST['submit'], $_POST['announcement_id'])) {
                                    //get the announcement id
                                    $announcement_id = isset($_POST['announcement_id']) ? $_POST['announcement_id'] : '';

                                    //query to select announcement
                                    $query = "SELECT * FROM announcement WHERE AnnouncementId = '$announcement_id'";
                                    //execute the query
                                    $result = mysqli_query($connection, $query);
                                    //check if the query is executed
                                    if ($result) {
                                        //fetch the result
                                        while ($row = mysqli_fetch_array($result)) {
                                            //get the announcement values
                                            $date = $row['AnnouncementDate'];
                                            $subject = $row['Subject'];
                                            $announcement = $row['Announcement'];
                                        }

                                        // add hidden field for announcement id
                                        echo '<input type="hidden" name="announcement_id" value="' . $announcement_id . '">';

                                        //display the announcement values
                                        echo '<tbody>';
                                        echo '<tr>';
                                        echo '<td>';
                                        echo 'Date : </br>';
                                        echo '<input type="date" class="form-control" name="date" value="' . $date . '">';
                                        echo '</td>';
                                        echo '</tr>';
                                        echo '<tr>';
                                        echo '<td>';
                                        echo 'Subject :';
                                        echo '<input type="text" class="form-control" name="subject" value="' . $subject . '">';
                                        echo '</td>';
                                        echo '</tr>';
                                        echo '<tr>';
                                        echo '<td>';
                                        echo 'Announcement :';
                                        echo '<textarea class="form-control" rows="10" name="announcement">' . $announcement . '</textarea>';
                                        echo '</td>';
                                        echo '</tr>';
                                        echo '<tr>';
                                        echo '<td>';
                                        echo '<input type="checkbox" name="student" value="student">Student';
                                        echo '<input type="checkbox" name="teacher" value="teacher">Teacher';
                                        echo '</td>';
                                        echo '</tr>';
                                        echo '<tr>';
                                        echo '<td colspan="2"><button type="submit" class="btnStyle1 mx-2">submit</button></td>';
                                        echo '</tr>';
                                        echo '</tbody>';
                                    }
                                }
                                ?>
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
