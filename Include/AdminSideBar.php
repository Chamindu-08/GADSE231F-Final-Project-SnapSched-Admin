<div class="wrapper">
        <aside id="sidebar" class="js-sidebar">
            <!-- content for sidebar -->
            <div class="h-100">
                <div class="sidebar-logo">
                    <a href="#">Sri Indasara Maha Vidyalaya</a>
                    <img src="Images/flags.png" alt="">
                </div>
                <ul class="sidebar-nav">
                    <li class="sidebar-item">
                        <a href="AdminDashBoard.php" class="sidebar-link">
                            <img src="Images/menu-burger.png" style="width:15px;">
                            Dashboard
                        </a>
                    </li>
                    <li class="sidebar-item">
                    <a href="#" class="sidebar-link collapsed" data-bs-target="#posts" data-bs-toggle="collapse"
                            aria-expanded="false"><img src="Images/user1.png" style="width:15px;">
                            Student
                        </a>
                        <ul id="posts" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="StudentProfile.php" class="sidebar-link">Student Profile</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="StudentProgressReport.php" class="sidebar-link">Students Progress Report</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="StudentTimeTable.php" class="sidebar-link">Student Time Table</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="StudentRegister.php" class="sidebar-link">Student Register</a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                    <a href="#" class="sidebar-link collapsed" data-bs-target="#posts" data-bs-toggle="collapse"
                            aria-expanded="false"><img src="Images/user1.png" style="width:15px;">
                            Teacher
                        </a>
                        <ul id="posts" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="TeacherProfile.php" class="sidebar-link">Teacher Profile</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="TeacherTimeTable.php" class="sidebar-link">Teacher Time Table</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="TeacherRegister.php" class="sidebar-link">Teacher Register</a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="YearTimeTable.php" class="sidebar-link" data-bs-target="#posts" aria-expanded="false">
                            <img src="Images/table-calendar.png" style="width:15px;">
                            Year Time Table
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="Absentism.php" class="sidebar-link" data-bs-target="#posts" aria-expanded="false">
                            <img src="Images/absentism.png" style="width:15px;">
                            Absentism
                        </a>
                    </li>
                    <li class="sidebar-item">
                    <a href="#" class="sidebar-link collapsed" data-bs-target="#posts" data-bs-toggle="collapse"
                            aria-expanded="false"><img src="Images/user1.png" style="width:15px;">
                            Announcements
                        </a>
                        <ul id="posts" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="AnnouncementCreate.php" class="sidebar-link">Create Announcement</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="AnnouncementUpdate.php" class="sidebar-link">Update Announcement</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="AnnouncementDelete.php" class="sidebar-link">Delete Announcement</a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-target="#posts" data-bs-toggle="collapse"
                            aria-expanded="false"><img src="Images/user1.png" style="width:15px;">
                            Profile
                        </a>
                        <ul id="posts" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="AdminProfile.php" class="sidebar-link">View Profile</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="AdminProfileEdit.php" class="sidebar-link">Update Profile</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="AdminPasswordChange.php" class="sidebar-link">Change Password</a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="AdminLogout.php" class="sidebar-link" data-bs-target="#multi" aria-expanded="false">
                            <img src="Images/logout.png" style="width:15px;">
                            Logout
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

<!-- menu bar -->
<div class="main">
    <nav class="navbar navbar-expand-lg px-3 border-bottom">
        <div class="container-fluid">
            <button class="btn" id="sidebar-toggle" type="button">
                <span><img class="menu-burger" src="images/menu-burger.png"></span>
            </button>
            <a class="navbar-brand" href="#">
                <img src="Images/logo.jpg" alt="Logo" style="width:100px; height: auto;">
            </a>
            <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                <form class="d-flex">
                    <input class="form-control me-2" id="searchBar" type="text" style="border-color: rgb(0,35,135);;" placeholder="Search">
                    <button class="btn" type="submit">
                        <img id="btnsearch" src="images/search.png" alt="Search" style="width:15px;">
                    </button>
                </form>
                <label style="margin-left: 10px; margin-right: 10px;">Induwara D C I</label>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a href="#" data-bs-toggle="dropdown" class="nav-icon pe-md-0">
                            <img src="images/user-icon.png" class="avatar" alt="">
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="AdminProfile.php" class="dropdown-item"><img src="Images/user2.png" style="width:15px;">Profile</a>
                            <a href="AdminProfileEdit.php" class="dropdown-item"><img src="Images/settings.png" style="width:15px;">Setting</a>
                            <a href="AdminLogout.php" class="dropdown-item"><img src="Images/sign-out-alt.png" style="width:15px;">Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>