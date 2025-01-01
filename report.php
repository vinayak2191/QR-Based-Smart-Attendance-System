<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'database.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $rollno = $_SESSION['roll_no'];
    $month = date('m');
    $year = date('Y');
    $attendanceData = [];
    $calendarAttendance = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $month = $_POST['month'];
        $year = $_POST['year'];
    }

    // Fetch attendance data from 'records' table
    $sql = "SELECT DAY(timestamp) AS day, COUNT(*) AS attendance_count
            FROM report
            WHERE rollno = :rollno AND MONTH(timestamp) = :month AND YEAR(timestamp) = :year
            GROUP BY DAY(timestamp)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':rollno', $rollno);
    $stmt->bindParam(':month', $month);
    $stmt->bindParam(':year', $year);
    $stmt->execute();
    $attendanceData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare attendance array for calendar (present/absent)
    $totalDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    for ($day = 1; $day <= $totalDaysInMonth; $day++) {
        $calendarAttendance[$day] = 'absent'; // Default: absent
        foreach ($attendanceData as $attendance) {
            if ($attendance['day'] == $day) {
                $calendarAttendance[$day] = 'present';
                break;
            }
        }
    }


    // Calculate statistics
    $daysPresent = count($attendanceData);
    $daysAbsent = $totalDaysInMonth - $daysPresent;
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
        }

        .container > div {
            flex: 1;
            border: 1px solid #ccc;
            padding: 20px;
        }
        .calendar-wrapper {
            width: 100%;
            max-width: 500px;
            margin: 30px auto;
        }

        .header {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
        }

        .calendar div {
            width: 40px;
            height: 40px;
            text-align: center;
            line-height: 40px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .present {
            background-color: green;
            color: white;
        }

        .absent {
            background-color: red;
            color: white;
        }

        .empty {
            background-color: #f0f0f0;
        }

        .calendar-wrapper h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        body {
    margin: 0;
    padding: 0;
    font-family: 'Arial', sans-serif;
    background: linear-gradient(135deg, #1d3557, #457b9d);
    color: #fff;
    display: flex;
    flex-direction: column;
    align-items: center;
    min-height: 100vh;
}

/* Header Styles */
header {
    width: 100%;
    padding: 20px 0;
    text-align: center;
    margin-bottom: 20px;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

header h1 {
    font-size: 2rem;
    font-weight: bold;
}

/* Navigation Styles */
nav {
    width: 100%;
    background: rgba(255, 255, 255, 0.1);
    padding: 10px 0;
    margin-bottom: 20px;
    backdrop-filter: blur(10px);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    display: flex;
    justify-content: center;
    align-items: center;
}

nav a {
    color: #fff;
    text-decoration: none;
    padding: 10px 20px;
    margin: 0 10px;
    border-radius: 5px;
    transition: background-color 0.3s ease, transform 0.2s ease;
    position: relative;
    overflow: hidden;
}

nav a:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, #00b4d8, #0077b6);
    z-index: -1;
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.3s ease;
    border-radius: 5px;
}

nav a:hover:before {
    transform: scaleX(1);
}

nav a:hover {
    transform: translateY(-2px);
}

/* Container Styles */
.container {
    width: 80%;
    max-width: 1200px;
    background-color: rgba(255, 255, 255, 0.1);
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    text-align: center;
    backdrop-filter: blur(10px);
}

.container h1,
.container h4 {
    color: #f1faee;
    margin-bottom: 15px;
}

.container hr {
    border: 0;
    height: 1px;
    background: #fff;
    margin: 15px 0;
}

/* QR Code Scanner Styles */
#reader {
    width: 300px;
    height: 300px;
    border: 2px solid #fff;
}

.result {
    background-color: rgba(255, 255, 255, 0);
    color: #fff;
    padding: 20px;
    text-align: center;
    border-radius: 5px;
    margin-top: 10px;
}

/* Form Styles */
#attendanceForm {
    margin-top: 20px;
}

.btn {
    padding: 10px 20px;
    background: #00b4d8;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.btn:hover {
    background: #0077b6;
}

.center {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 60vh;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .container {
        width: 90%;
        padding: 15px;
    }

    header h1 {
        font-size: 1.8rem;
    }

    nav a {
        padding: 8px 15px;
        margin: 0 5px;
    }

    #reader {
        width: 250px;
        height: 250px;
    }
}
    </style>
</head>

<body>
    <header>
        <h1>Attendance Report</h1>
    </header>
    <nav>
    <a href="home.php">Home</a>
        <a href="lecture.php">Lectures</a>
        <a href="report.php">Report</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="login.php" onclick="logout()">Logout</a>
    </nav>

    <main>
        <form method="POST" action="report.php" style="margin-bottom: 20px;">
            <label for="month">Select Month:</label>
            <select id="month" name="month">
                <?php
                for ($m = 1; $m <= 12; $m++) {
                    $selected = ($m == $month) ? 'selected' : '';
                    echo "<option value='$m' $selected>" . date('F', mktime(0, 0, 0, $m, 10)) . "</option>";
                }
                ?>
            </select>

            <label for="year">Select Year:</label>
            <select id="year" name="year">
                <?php
                for ($y = date('Y') - 5; $y <= date('Y'); $y++) {
                    $selected = ($y == $year) ? 'selected' : '';
                    echo "<option value='$y' $selected>$y</option>";
                }
                ?>
            </select>

            <button type="submit">Generate Report</button>
        </form>

        <div style="display: flex; align-items: flex-start; gap: 20px;">
            <!-- Attendance Summary -->
            <div style="flex: 1; border: 1px solid #ccc; padding: 20px;">
                <h2>Attendance Summary</h2>
                <p>Days Present: <?php echo $daysPresent; ?></p>
                <p>Days Absent: <?php echo $daysAbsent; ?></p>
            </div>

            <!-- Attendance Chart -->
            <div style="flex: 1; border: 1px solid #ccc; padding: 20px; display: flex; justify-content: center;">
                <h2>Attendance Chart</h2>
                <canvas id="attendanceChart" width="400" height="200"></canvas>
            </div>
        </div>

        <div class="calendar-wrapper">
            <h2>Attendance Calendar for <?php echo date('F Y', mktime(0, 0, 0, $month, 1, $year)); ?></h2>
            <div class="header">
                <div>Sun</div>
                <div>Mon</div>
                <div>Tue</div>
                <div>Wed</div>
                <div>Thu</div>
                <div>Fri</div>
                <div>Sat</div>
            </div>
            <div class="calendar" id="calendar">
                <!-- Calendar days will be inserted here -->
            </div>
        </div>
    </main>

    <footer>
        <p>© <?php echo date('Y'); ?> Your University Name. All Rights Reserved.</p>
    </footer>

    <script>
        // Chart
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        const attendanceChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Present', 'Absent'],
                datasets: [{
                    data: [<?php echo $daysPresent; ?>, <?php echo $daysAbsent; ?>],
                    backgroundColor: ['#28a745', '#dc3545']
                }]
            },
            options: {
                responsive: false,
                maintainAspectRatio: false,
            }
        });

          // Calendar
          function generateCalendar(monthDays, startDayOfWeek, attendance) {
            const calendarContainer = document.getElementById('calendar');
            calendarContainer.innerHTML = ''; // Clear previous content

            // Fill in the empty cells before the first day of the month
            for (let i = 0; i < startDayOfWeek; i++) {
                const emptyDiv = document.createElement('div');
                emptyDiv.classList.add('empty');
                calendarContainer.appendChild(emptyDiv);
            }

             // Fill in the days of the month
             for (let day = 1; day <= monthDays; day++) {
                const dayDiv = document.createElement('div');
                dayDiv.textContent = day;

                // Check attendance status and assign a class
                if (attendance[day] === 'present') {
                    dayDiv.classList.add('present');
                } else if (attendance[day] === 'absent') {
                    dayDiv.classList.add('absent');
                } else {
                    dayDiv.classList.add('empty');
                }

                calendarContainer.appendChild(dayDiv);
             }
        }

        function getFirstDayOfMonth(year, month) {
            const date = new Date(year, month - 1, 1);
            return date.getDay(); // Return the day of the week (0: Sunday, 1: Monday, ...)
        }

        const currentYear = <?php echo $year; ?>;
        const currentMonth = <?php echo $month; ?>;
        const firstDayOfWeek = getFirstDayOfMonth(currentYear, currentMonth);
        const daysInMonth = new Date(currentYear, currentMonth, 0).getDate();
        const attendance = <?php echo json_encode($calendarAttendance); ?>;

        generateCalendar(daysInMonth, firstDayOfWeek, attendance);

    </script>
</body>
</html>