<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>/* General Styles */
/* General Styles */
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
        <h1>Welcome to the Dashboard</h1>
    </header>
    <nav>
        <a href="home.php">Home</a>
        <a href="report.php">Report</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="login.php" onclick="logout()">Logout</a>
    </nav>
    <div style="display:flex;gap:20px">
    <div class="container" style="height: 80vh; width:150vh;">
        <?php
        session_start();

        // // Check if user is logged in
        // if (!isset($_SESSION['user_id'])) {
        //     header("Location: dashboard.html"); // Redirect to login page if not logged in
        //     exit();
        // }

        echo "Welcome, " . $_SESSION['full_name'] . "!" . "<br>";

        ?>
        <h1>QR Code Scanner</h1>
        <hr>
        <div class="center">
            <div id="reader"></div>
        </div>
        <br>
    </div>

    <div class="container" style="height: 50vh; position: relative;height:80vh">
    <inp>
        <h4>SCAN RESULT</h4>
        <hr>
        <div id="result">Result Here</div>
        <form id="attendanceForm" action="home.php" method="POST">
            <input type="text" id="fullname" name="fullname" style="display:none;">
            <input type="text" id="rollno" name="rollno" style="display:none;">

            <?php
                date_default_timezone_set('Asia/Kolkata');

                include 'C:/xampp/htdocs/QR_system/database.php';
                try {
                    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        // Fetch data from session
                        $fullname = $_SESSION['full_name'];
                        $rollno = $_SESSION['roll_no'];
                        $timestamp = date('Y-m-d H:i:s'); // Current timestamp

                        // Check if rollno already exists for the same session today
                        $checkSql = "SELECT COUNT(*) FROM records WHERE rollno = :rollno AND DATE(timestamp) = CURDATE()";
                        $checkStmt = $conn->prepare($checkSql);
                        $checkStmt->bindParam(':rollno', $rollno);
                        $checkStmt->execute();

                        $existingCount = $checkStmt->fetchColumn();

                        if ($existingCount > 0) {
                            // Rollno already exists, do not mark attendance
                            echo "Attendance already marked for Roll Number: $rollno today.";
                        } else {
                            // 1. Insert into 'records' table
                            $sql_records = "INSERT INTO records (fullname, rollno, timestamp) 
                                    VALUES (:fullname, :rollno, :timestamp)";

                            $stmt_records = $conn->prepare($sql_records);
                            $stmt_records->bindParam(':fullname', $fullname);
                            $stmt_records->bindParam(':rollno', $rollno);
                            $stmt_records->bindParam(':timestamp', $timestamp);

                            if ($stmt_records->execute()) {
                                // 2. Insert into 'report' table
                                $sql_report = "INSERT INTO report (fullname, rollno, timestamp, status) 
                                                    VALUES (:fullname, :rollno, :timestamp, :status)";


                                $stmt_report = $conn->prepare($sql_report);
                                $stmt_report->bindParam(':fullname', $fullname);
                                $stmt_report->bindParam(':rollno', $rollno);
                                $stmt_report->bindParam(':timestamp', $timestamp);
                                $status = 'present';
                                $stmt_report->bindParam(':status', $status);

                                if ($stmt_report->execute()) {
                                    echo "Attendance marked successfully!";
                                } else {
                                    echo "Error marking attendance in 'report' table! Error: " . $stmt_report->errorInfo()[2];
                                }
                            } else {
                                echo "Error marking attendance in 'records' table!";
                            }
                        }
                    }
                } catch (PDOException $e) {
                    echo "Database error: " . $e->getMessage();
                }
                ?>
                <button type="submit" class="btn">Mark Attendance</button>
            </form>
        </inp>
</div>

    <script type="text/javascript" src="https://unpkg.com/html5-qrcode@2.0.9/dist/html5-qrcode.min.js"></script>

    <script type="text/javascript">
        function onScanSuccess(qrCodeMessage) {
            document.getElementById('result').innerHTML = '<span class="result">' + qrCodeMessage + '</span>';
            // Extract rollno and fullname from QR code message
            const parts = qrCodeMessage.split(',');
            if (parts.length === 2) {
                const rollno = parts[0].trim();
                const fullname = parts[1].trim();

                document.getElementById('rollno').value = rollno;
                document.getElementById('fullname').value = fullname;
                document.getElementById('attendanceForm').style.display = 'block';
            } else {
                console.error("QR code format is not valid.");
            }
        }

        function onScanError(errorMessage) {
            //handle scan error
        }

        var html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", {
            fps: 10,
            qrbox: 250
        });
        html5QrcodeScanner.render(onScanSuccess, onScanError);
    </script>
</body>
</html>