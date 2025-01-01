<?php
// Start session and include database connection
session_start();
include 'database.php';

// // Check if teacher is logged in
// if (!isset($_SESSION['teacher_id'])) {
//     header("Location: login.php");
//     exit();
// }

$studentInfo = null;
$searchTerm = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get search term (either Roll No or Name)
    $searchTerm = $_POST['searchTerm'];

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Search for student based on Roll No or Name in records table
        $sql = "SELECT * FROM records WHERE rollno = :searchTerm OR fullname LIKE :searchTerm";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':searchTerm', $searchTerm);
        $stmt->execute();

        $studentInfo = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <style>
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
    width: 25%;
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

/* Button styling */
.button {
    width: 100%;
    padding: 12px;
    font-size: 1.2rem;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.button:hover {
    background-color: #0056b3;
}
.center {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 60vh;
}
/* Login Container */
.login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        /* Login Box */
        .login-box {
            background: rgba(255, 255, 255, 0.1);
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 100%;
            text-align: center;
            backdrop-filter: blur(10px);
        }

        /* Heading */
        .login-box h2 {
            font-size: 1.8rem;
            color: #f1faee;
            margin-bottom: 20px;
        }

        /* Form Group */
        .form-group {
            margin-bottom: 20px;
            text-align: left;
            color: #000;
        }

        .form-group label {
            font-size: 1rem;
            display: block;
            margin-bottom: 5px;
            color: #000
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #f8f9fa;
            color: #000;
            outline: none;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-group input:focus {
            border-color: #00b4d8;
            box-shadow: 0 0 5px rgba(0, 180, 216, 0.5);
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
        <h1>Teacher Dashboard</h1>
    </header>

    <nav>
        <a href="attendance.php">Attendance</a>
        <a href="teacherDashBoard.php">Dashboard</a>
        <a href="present.php">Attendance Records</a>
        <a href="teacherLogin.php">Logout</a>
    </nav>

    <div class="container" >
        <form class="search-form" method="POST" style="display:flex; margin:10px;">
            <input type="text" name="searchTerm" id="searchTerm" placeholder="Enter Roll No or Name" required
                value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button type="submit" class="button" type="submit" >Search</button>
        </form>
    </div>

    <?php if ($studentInfo): ?>
        <div class="student-info">
            <h2 style="text-align: center;">Student Information</h2>
            <?php foreach ($studentInfo as $key => $value): ?>
                <div class="info-row">
                    <label><?php echo ucfirst(str_replace('_', ' ', $key)); ?>:</label>
                    <span><?php echo htmlspecialchars($value); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    <?php elseif ($searchTerm): ?>
        <p style="text-align: center; color: white;">No student found with that Roll No or Name.</p>
    <?php endif; ?>
</body>

</html>