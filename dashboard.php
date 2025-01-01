<?php
// Start session and include database connection
session_start();
include 'database.php';

// Check if user is logged in
if (!isset($_SESSION['roll_no'])) {
    header("Location: login.php");
    exit();
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch data for the logged-in student
    $rollNo = $_SESSION['roll_no'];
    $sql = "SELECT * FROM students WHERE roll_no = :roll_no";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':roll_no', $rollNo);
    $stmt->execute();

    $student = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        /* Vertical layout styling */
        .student-info {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }

        .student-info h2 {
            text-align: center;
        }

        .info-row {
            display: flex;
            margin-bottom: 10px;
        }

        .info-row label {
            font-weight: bold;
            width: 150px;
        }

        .info-row div {
            flex-grow: 1;
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
    <link rel="stylesheet" href="styles.css">

</head>

<body>
<header>
        <h1>Dashboard</h1>
    </header>
    <nav>
        <a href="home.php">Home</a>
        <!-- <a href="lecture.php">Lectures</a> -->
        <a href="report.php">Report</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="login.php" onclick="logout()">Logout</a>
    </nav>
    <!-- Vertical Layout for Logged-In Student Info -->
    <div class="student-info">
        <h2>Student Information</h2>

        <?php if (!empty($student)): ?>
            <?php foreach ($student as $key => $value): ?>
                <div class="info-row">
                    <label for="<?php echo $key; ?>"><?php echo ucfirst(str_replace('_', ' ', $key)); ?>:</label>
                    <div><?php echo htmlspecialchars($value); ?></div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No data available for the logged-in student.</p>
        <?php endif; ?>
    </div>
</body>

</html>