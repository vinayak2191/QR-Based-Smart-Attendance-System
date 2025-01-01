<?php
include 'database.php'; // Database connection details

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Create a MySQLi connection
    $conn = new mysqli($servername, $username, $dbpassword, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL to check if the email exists
    $sql = "SELECT * FROM teachers WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $teacher = $result->fetch_assoc();

    if ($teacher && password_verify($password, $teacher['password'])) {
        // Start session and set session variables
        session_start();
        $_SESSION['teacher_id'] = $teacher['id'];
        $_SESSION['teacher_name'] = $teacher['fullname'];

        // Redirect to teacher dashboard
        header("Location: teacherdashboard.php");
        exit();
    } else {
        echo "Invalid credentials!";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
