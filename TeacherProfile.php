<?php
//database connection
include 'DBConnection/DBConnection.php';

//check connection
if (!$connection) {
    echo "Connection failed";
}

//initialize variables to store retrieved values
$first_name = "";
$last_name = "";
$sure_name = "";
$admission_no = "";
$assume_date = "";
$address = "";
$email = "";
$contact_no = "";

if (isset($_POST['teacherSelect'])) {
    $teacherId = $_POST['teacherSelect'];

    $sql = "SELECT * FROM teacher WHERE TeacherId = '$teacherId'";

    $result = mysqli_query($connection, $sql);

    if ($result === false) {
        echo "<script>alert('Error executing the query: " . $connection->error . "');</script>";
    }

    if ($result->num_rows > 0) {
        //output data of the first row
        $row = $result->fetch_assoc();

        //assign values to variables
        $first_name = $row["FirstName"];
        $last_name = $row["LastName"];
        $sure_name = $row["SurName"]; 
        $admission_no = $row["TeacherId"];
        $assume_date = $row["AssumeDate"];
        $address = $row["TeacherAddress"];
        $email = $row["TeacherEmail"];
        $contact_no = $row["TeacherContactNo"];
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
    <title>Account | Teacher</title>

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
                <!-- select teacher panel -->
                <div class="row">
                    <div class="card flex-fill border-0 illustration">
                        <div class="card-body p-0 d-flex flex-fill">
                            <div class="row g-0 w-100">
                                <div class="col">
                                    <div class="p-3 m-1">
                                        <h4>Teacher Profile</h4>
                                        <form id="formTeacherSearch" method="post" action="#">
                                            <table>
                                                <tr>
                                                    <td>Teacher</td>
                                                    <td>
                                                        <?php
                                                        //display teacher options
                                                        //SQL query select all teachers
                                                        $sql = "SELECT TeacherId,FirstName,LastName FROM teacher";
                                                        $result = mysqli_query($connection, $sql);

                                                        if ($result) {
                                                            echo '<select id="teacherSelect" class="grade-select-dropdown" name="teacherSelect">';
                                                            //display teachers in dropdown
                                                            while ($row = mysqli_fetch_assoc($result)) {
                                                                echo '<option value="' . $row['TeacherId'] . '">' . $row['FirstName'] . ' ' . $row['LastName'] . '</option>';
                                                            }
                                                            echo '</select>';
                                                            mysqli_free_result($result);
                                                        } else {
                                                            echo "Error: " . mysqli_error($connection);
                                                        }
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
                                                    <h5>Assume Date :</h5>
                                                    <label><?php echo $assume_date; ?></label>
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
                                                    <h5>Contact No :</h5>
                                                    <label><?php echo $contact_no; ?></label>
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
        
    <script src="js/Dashboard.js"></script>
    <!-- link Bootstap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
