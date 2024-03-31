<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- Bootstrap link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- stylesheet -->
    <link rel="stylesheet" href="CSS/Dashboard.css" />
    <link rel="stylesheet" href="CSS/style.css" />

    <!-- Internal CSS -->
    <style>
        .sidebar-dropdown1 {
            background-color: rgb(0,35,135);
            color: white;
            padding: 10px;
            border-radius: 5px;
        }

        .sidebar-item1 {
            margin-bottom: 5px;
        }

        .sidebar-link1 {
            background-color: rgb(0,35,135);
            color: white; 
            text-decoration: none;
            text-align: center;
            display: block;
            padding: 5px 10px;
            transition: background-color 0.3s;
        }
    </style>
</head>
<body>
    
<div class="wrapper">
    <?php include 'Include/AdminSideBar.php'; ?>
        
    <main class="content px-3 py-2">
        <div class="container-fluid">
            <div class="card border-0">
                <div class="card-header">
                    <h4 class="card-title">
                        Admin
                    </h4>
                </div>
                <div class="row" style="padding-top: 20px; background-color: lightgray;">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title" style="text-align: center;">Student</h4>
                                <img class="card-img-top" src="Images/Student.JPG" alt="Card image" style="width:100%">
                                <div class="card-body">
                                    <li class="sidebar-item1">
                                        <a href="#" class="sidebar-link1 collapsed" style="text" data-bs-target="#studentOptions" data-bs-toggle="collapse" aria-expanded="false">
                                            Student
                                        </a>
                                        <ul id="studentOptions" class="sidebar-dropdown1 list-unstyled collapse" data-bs-parent="#sidebar">
                                            <li class="sidebar-item1">
                                                <a href="StudentProfile.php" class="sidebar-link1">Student Profile</a>
                                            </li>
                                            <li class="sidebar-item1">
                                                <a href="StudentProgressReport.php" class="sidebar-link1">Students Progress Report</a>
                                            </li>
                                            <li class="sidebar-item1">
                                                <a href="StudentTimeTable.php" class="sidebar-link1">Student Time Table</a>
                                            </li>
                                            <li class="sidebar-item1">
                                                <a href="StudentRegister.php" class="sidebar-link1">Student Register</a>
                                            </li>
                                        </ul>
                                    </li>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title" style="text-align: center;">Teacher</h4>
                                <img class="card-img-top" src="Images/Teacher.JPG" alt="Card image" style="width:100%">
                                <div class="card-body">
                                    <li class="sidebar-item1">
                                        <a href="#" class="sidebar-link1 collapsed" style="text" data-bs-target="#teacherOptions" data-bs-toggle="collapse" aria-expanded="false">
                                            Teacher
                                        </a>
                                        <ul id="teacherOptions" class="sidebar-dropdown1 list-unstyled collapse" data-bs-parent="#sidebar">
                                            <li class="sidebar-item1">
                                                <a href="TeacherProfile.php" class="sidebar-link1">Teacher Profile</a>
                                            </li>
                                            <li class="sidebar-item1">
                                                <a href="TeacherTimeTable.php" class="sidebar-link1">Teacher Time Table</a>
                                            </li>
                                            <li class="sidebar-item1">
                                                <a href="TeacherRegister.php" class="sidebar-link1">Teacher Register</a>
                                            </li>
                                        </ul>
                                    </li>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title" style="text-align: center;">Time Table</h4>
                                <img class="card-img-top" src="Images/TimeTable.JPG" alt="Card image" style="width:100%">
                                <div class="card-body">
                                    <li class="sidebar-item1">
                                        <a href="YearTimeTable.php" class="sidebar-link1" data-bs-target="#posts" aria-expanded="false">
                                            Generate Time Table
                                        </a>
                                    </li>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title" style="text-align: center;">Announcement</h4>
                                <img class="card-img-top" src="Images/Announcement.JPG" alt="Card image" style="width:100%">
                                <div class="card-body">
                                    <li class="sidebar-item1">
                                    <a href="#" class="sidebar-link1 collapsed" style="text" data-bs-target="#announcementOptions" data-bs-toggle="collapse" aria-expanded="false">
                                            Announcements
                                        </a>
                                        <ul id="announcementOptions" class="sidebar-dropdown1 list-unstyled collapse" data-bs-parent="#sidebar">
                                            <li class="sidebar-item1">
                                                <a href="AnnouncementCreate.php" class="sidebar-link1">Create Announcement</a>
                                            </li>
                                            <li class="sidebar-item1">
                                                <a href="AnnouncementUpdate.php" class="sidebar-link1">Edit Announcement</a>
                                            </li>
                                            <li class="sidebar-item1">
                                                <a href="AnnouncementDelete.php" class="sidebar-link1">Delete Announcement</a>
                                            </li>
                                        </ul>
                                    </li>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>              
            </div>
        </div>
    </main>
</div>

<!-- Bootstrap script -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
