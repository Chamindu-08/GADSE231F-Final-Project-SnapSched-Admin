<?php
if(isset($_POST["date"])) {
    //accept HTML Form data
    $Date = $_POST["date"];
    $Announcement = $_POST["announcement"];

    //validate fields
    if (empty($Date) || empty($Announcement)) {
        echo "<script>alert('All fields are required.');</script>";
    } else {
        //include database connection
        include 'DBConnection/DBConnection.php';

        //check if the connection to the database failed
        if (!$connection) {
            echo "Database connection failed. Please try again later.";
        }

        //get the latest AbsentId from the database to determine the next ID
        $SQLAnnouncement = "SELECT * FROM announcement";
        $result = mysqli_query($connection, $SQLAnnouncement);

        if ($row = mysqli_fetch_assoc($result)) {
            $lastAbsentId = $row['AnnouncementId'];
            $lastNumber = intval(substr($lastAbsentId, 1));
            $nextNumber = $lastNumber + 1;
            $nextAbsentId = 'A' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        } else {
            //default value for the first record
            $nextAnnouncementId = 'A001';
        }

        //insert data into the database
        $sql = "INSERT INTO announcement (AnnouncementId, Announcement, AnnouncementDate) VALUES ('$nextAnnouncementId', '$Announcement', '$Date')";

        //execute the query
        $result = mysqli_query($connection, $sql);

        if ($result) {
            echo "<script>alert('Successful.');</script>";
        } else {
            echo "<script>alert('Error: " . $sql . "<br>" . mysqli_error($connection) . "');</script>";
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
    <title>Absentism | Teacher</title>

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
                    <div class="card-body table-responsive">
                        <form name="teacherProUp" action="#" method="post">
                            <table class="profileStyle">
                                <tbody>
                                    <tr>
                                        <td>
                                            Date : </br>
                                            <input type="date" class="form-control" name="date">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Announcement :
                                            <textarea class="form-control" rows="10" name="announcement"></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><button type="submit" class="btnStyle1 mx-2">submit</button></td>
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