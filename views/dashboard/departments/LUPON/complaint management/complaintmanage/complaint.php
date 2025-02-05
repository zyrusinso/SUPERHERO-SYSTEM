<?php
include 'C:\xampp\htdocs\SUPERHERO-SYSTEM\controllers\db_connection.php';

// Check database connection
if (!$pdo) {
    die("Database connection failed!");
}

// Fetch data from the database
$sql = "SELECT case_number, date_of_incident, date_forward, origin_department, status 
        FROM turnover 
        WHERE (hearing_date IS NULL OR hearing_date = '0000-00-00') 
          AND (hearing_time IS NULL OR hearing_time = '00:00:00')";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$sqlTurnovers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// API Endpoint
$apiUrl = "https://yjme796l3k.execute-api.ap-southeast-2.amazonaws.com/dev/api/v1/brgy/lupon/complaint_records";

// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer YOUR_API_KEY" // Replace with your API key if required
]);

// Execute cURL request
$response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    die("Error: " . curl_error($ch));
}

// Close cURL session
curl_close($ch);

// Decode JSON response
$data = json_decode($response, true);

// Extract API data
$apiTurnovers = $data['lupon_all_complaints'] ?? [];

// Merge SQL and API data
$turnovers = array_merge($sqlTurnovers, $apiTurnovers);

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome, user! - Brgy Sta. Lucia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/perfect-scrollbar@1.5.0/css/perfect-scrollbar.css">
    <link href="../../../../../../custom/css/index.css" rel="stylesheet">
    <link href="../../LUPON/complaint management/style.css" rel="stylesheet">
    <link rel="icon" href="../../dist/images/favicon.ico" type="image/x-icon">
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Onboarding as LUPON for Brgy. Management">
    <meta property="og:description" content="Still in development phase.">
    <meta property="og:image" content="URL_to_your_image.jpg">
    <meta property="og:url" content="https://yourwebsite.com">
    <meta property="og:type" content="website">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Onboarding as LUPON for Brgy. Management">
    <meta name="twitter:description" content="Still in development phase.">
    <meta name="twitter:image" content="URL_to_your_image.jpg">
    <meta name="twitter:url" content="https://yourwebsite.com">
</head>

<body>
    <div id="app">
        <nav class="sidebar" style="z-index: 1000;">
            <div class="sidebar-content">
                <div class="sidebar-header">Brgy. Sta. Lucia</div>

                <div class="sidebar-category">
                    <div class="sidebar-category-header">
                        <a href="http://localhost:3000/views/dashboard/departments/LUPON/dashboard/dashboard.php" class="sidebar-link" style="text-decoration: none; color: inherit;">
                            <span><i class="fa-solid fa-desktop category-icon"></i>Dashboard</span>
                        </a>
                    </div>
                </div>


                <div class="sidebar-category">
                    <div class="sidebar-category-header" onclick="toggleSubMenu()">
                        <span><i class="fa-solid fa-folder category-icon"></i>Complaint Management</span>
                        <i class="fas fa-chevron-down toggle-icon"></i>
                    </div>
                    <div class="sidebar-submenu-show">
                        <a href="http://localhost:3000/views/dashboard/departments/LUPON/complaint%20management/complaintmanage/complaint.php" class="sidebar-link">
                            <div class="sidebar-submenu-item">Complaints</div>
                        </a>
                        <a href="http://localhost:3000/views/dashboard/departments/LUPON/complaint%20management/CFA/issuecfa.php" class="sidebar-link">
                            <div class="sidebar-submenu-item">Issue CFA</div>
                        </a>
                        <a href="http://localhost:3000/views/dashboard/departments/LUPON/complaint%20management/schedule/schedule.php" class="sidebar-link">
                            <div class="sidebar-submenu-item">Schedule Hearing</div>
                        </a>
                        <a href="http://localhost:3000/views/dashboard/departments/LUPON/complaint%20management/pendingcase.php" class="sidebar-link">
                            <div class="sidebar-submenu-item">Pending Cases</div>
                        </a>
                    </div>
                </div>

                <div class="sidebar-category">
                    <div class="sidebar-category-header">
                        <a href="http://localhost/SUPERHERO-SYSTEM/views/dashboard/departments/LUPON/notification/notification.php" class="sidebar-link">
                            <span>
                                <i class="fa-solid fa-bell category-icon"></i> Notification
                                <?php
                                $unreadCountStmt = $pdo->prepare("SELECT COUNT(*) FROM lupon_notification WHERE is_read = 0");
                                $unreadCountStmt->execute();
                                $unreadCount = $unreadCountStmt->fetchColumn();
                                if ($unreadCount > 0) {
                                    echo '<span class="badge">' . $unreadCount . '</span>';
                                }
                                ?>
                            </span>
                        </a>
                    </div>
                </div>
                <div class="sidebar-category">
                    <div class="sidebar-category-header">
                        <span><i class="fa-solid fa-id-card category-icon"></i>User Profile</span>
                    </div>
                </div>

                <div class="sidebar-category">
                    <div class="sidebar-category-header" data-bs-toggle="modal" data-bs-target="#signOutModal">
                        <span><i class="fa-solid fa-door-open category-icon"></i>Sign Out</span>
                    </div>
                </div>
            </div>
        </nav>



        <!-- Dashboard Side -->
        <nav style="width: 77%; margin-top: 10px; border-radius: 7px; margin-left: 21%; height: 104px; border: 1px solid #d4d4d4; background-color: #ffffff; position: relative; box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.1);">
            <h1 style="font-size: 2rem; position: absolute; left: 3%; top: 25px;">
                COMPLAINTS REVIEW
            </h1>

            <?php
            $current_page = basename($_SERVER['PHP_SELF']); // Get the current filename.
            ?>

            <div style="position: absolute; right: 4%; top: 40px; display: flex; align-items: center; gap: 10px;">
                <!-- BPSO Link -->
                <a href="http://localhost:3000/views/dashboard/departments/LUPON/complaint%20management/complaintmanage/complaint.php"
                    style="font-size: 17px; text-decoration: none; color: <?= ($current_page === 'complaint.php') ? '#004080' : '#888'; ?>; font-weight: <?= ($current_page === 'complaint.php') ? 'bold' : 'normal'; ?>;">
                    BPSO
                </a>
                <!-- Separator -->
                <span style="font-size: 17px; color: #888;">/</span>
                <!-- LUPON Link -->
                <a href="http://localhost:3000/views/dashboard/departments/LUPON/complaint%20management/complaintmanage/luponcomplaint/luponcomplaint.php"
                    style="font-size: 17px; text-decoration: none; color: <?= ($current_page === 'luponcomplaint.php') ? '#004080' : '#888'; ?>; font-weight: <?= ($current_page === 'luponcomplaint.php') ? 'bold' : 'normal'; ?>;">
                    LUPON
                </a>
            </div>





        </nav>


        <!-- Wag gagalawin -->
        <!-- Dashboard body -->
        <nav style="margin-top: 30px; margin-left: 21%; padding: 20px; min-height: 80vh; width: 77%; box-sizing: border-box; background-color: #ffffff; border-radius: 10px;">

            <div style="position: relative; padding: 5px; margin-top: 10px; margin-left: 1%; min-height: 75vh; width: 98%; border-bottom: 2.5px solid #004080; border-top: 50px solid #004080; border-left: 2.5px solid #004080; border-right: 2.5px solid #004080; border-radius: 5px;">
                <span style="position: absolute; top: -38px; left: 40%; padding: 0 10px; font-weight: bold; font-size: 18px; color: white;">
                    Turnover Complaint
                </span>

                <div style="display: flex; align-items: center; margin-left: 30px; margin-top: 10px; gap: 30px;">
                    <input type="date" id="date" name="date" placeholder="Date..."
                        style="padding: 10px; width: 400px; height: 50px; border-radius: 5px; border: 1px solid #b1b1b1; font-weight: 300; margin-right: 20px;">
                </div>


                <div style="overflow-y: auto; height: 420px; width: 95%; max-width: 1500px; margin-top: 40px; margin-left: 30px; border: 1px solid #d4d4d4;  box-shadow: 0 4px 5px rgba(0, 0, 0, 0.1);">
                    <table id="tablecase" class="table table-bordered" style="width: 100%; text-align: center;">
                        <thead>
                            <tr>
                                <th scope="col" style="background-color: #004080; color: white; position: sticky; top: 0; z-index: 1;">Case Number</th>
                                <th scope="col" style="background-color: #004080; color: white; position: sticky; top: 0; z-index: 1;">Case Date</th>
                                <th scope="col" style="background-color: #004080; color: white; position: sticky; top: 0; z-index: 1;">Turnover Date</th>
                                <th scope="col" style="background-color: #004080; color: white; position: sticky; top: 0; z-index: 1;">Origin Department</th>
                                <th scope="col" style="background-color: #004080; color: white; position: sticky; top: 0; z-index: 1;">Status</th>
                                <th scope="col" style="background-color: #004080; color: white; position: sticky; top: 0; z-index: 1;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($turnovers)) {
                                foreach ($turnovers as $turnover) {
                                    echo "<tr>";
                                    echo "<th scope='row'>" . htmlspecialchars($turnover["case_number"] ?? 'N/A') . "</th>";
                                    echo "<td>" . htmlspecialchars($turnover["date_of_incident"] ?? 'N/A') . "</td>";
                                    echo "<td>" . htmlspecialchars($turnover["date_forward"] ?? 'N/A') . "</td>";
                                    echo "<td>" . htmlspecialchars($turnover["origin_department"] ?? 'N/A') . "</td>";
                                    $status = htmlspecialchars($turnover["status"] ?? 'N/A');
                                    if ($status === 'new') {
                                        $status = "<span style='color: red; font-weight: 400;'>New</span>";
                                    }
                                    echo "<td>" . $status . "</td>";
                                    echo "<td>
                <a href='seedetails.php?case_number=" . urlencode($turnover["case_number"] ?? '') . "' class='btn-container'>
                <button type='button' id='Updatebutton' class='btn btn-primary btn-hover' style='font-weight: 500;'>See details</button>
                </a>
            </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>No turnover record</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </nav>






        <!-- Sign Out Confirmation Modal -->
        <div class="modal fade" id="signOutModal" tabindex="-1" aria-labelledby="signOutModalLabel" aria-hidden="true"
            data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered"> <!-- Added modal-dialog-centered here -->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="signOutModalLabel">Confirm Sign Out</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to sign out?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmSignOutBtn" data-bs-dismiss="modal">Sign
                            Out</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/perfect-scrollbar@1.5.0/dist/perfect-scrollbar.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <style>
        .sidebar-submenu-item {
            display: block;
            text-decoration: none;
            padding: 8px 130px;

            padding-left: 20px
        }

        .sidebar-submenu-show {
            display: none;
        }

        .sidebar-link {
            text-decoration: none;
            color: inherit;
            display: flex;
            align-items: center;
            width: 100%;
        }


        .badge {
            background-color: #ff0000;
            color: white;
            border-radius: 50%;
            padding: 0.3rem 0.6rem;
            font-size: 0.9rem;
            position: absolute;
            top: 5px;
            right: 10px;
            transform: translateY(-50%);
            display: inline-block;
        }

        .sidebar-category-header {
            position: relative;
        }
    </style>
    <script>
        const sidebar = document.querySelector('.sidebar-content');
        const ps = new PerfectScrollbar(sidebar);

        function toggleSubmenu(element) {
            const submenu = element.nextElementSibling;
            const allSubmenus = document.querySelectorAll('.sidebar-submenu');
            const allHeaders = document.querySelectorAll('.sidebar-category-header');

            allSubmenus.forEach(menu => {
                if (menu !== submenu) {
                    menu.classList.remove('show');
                }
            });

            allHeaders.forEach(header => {
                if (header !== element) {
                    header.classList.remove('active');
                }
            });

            submenu.classList.toggle('show');
            element.classList.toggle('active');
            ps.update();
        }

        function createRipple(event) {
            const button = event.currentTarget;
            const ripple = document.createElement('span');
            const rect = button.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = event.clientX - rect.left - size / 2;
            const y = event.clientY - rect.top - size / 2;

            ripple.style.width = ripple.style.height = `${size}px`;
            ripple.style.left = `${x}px`;
            ripple.style.top = `${y}px`;
            ripple.classList.add('ripple');

            button.appendChild(ripple);

            ripple.addEventListener('animationend', () => {
                ripple.remove();
            });
        }

        document.querySelectorAll('.sidebar-submenu-item').forEach(item => {
            item.addEventListener('mouseenter', createRipple);
        });

        document.getElementById('confirmSignOutBtn').addEventListener('click', function() {
            // Redirect to signout.php to handle session destruction and redirection
            window.location.href = '../../../../../signout.php';
        });





        function toggleSubMenu() {
            const submenu = document.querySelector(".sidebar-submenu-show");
            submenu.style.display = submenu.style.display === "none" || submenu.style.display === "" ? "block" : "none";
        }


        document.getElementById("date").addEventListener("change", filterTableByDate);

        function filterTableByDate() {
            var filterDate = document.getElementById("date").value;
            var table = document.getElementById("tablecase");
            var rows = table.querySelectorAll("tbody tr");

            rows.forEach(row => {
                var caseDateCell = row.cells[2];
                if (caseDateCell) {
                    var caseDate = caseDateCell.textContent.trim();

                    row.style.display = (filterDate === "" || caseDate === filterDate) ? "" : "none";
                }
            });
        }
    </script>
</body>

</html>