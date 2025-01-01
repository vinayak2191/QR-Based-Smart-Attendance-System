<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
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

/* Container Styles */
.container {
     width: 80%;
    max-width: 600px;
    background-color: rgba(255, 255, 255, 0.1);
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    text-align: center;
    backdrop-filter: blur(10px);
}

/* Form Styles */
#signUpForm {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
}

.form-group {
    width: 80%;
    margin-bottom: 15px;
    text-align: left;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    color: #f1faee;
    font-size: 1rem;
}

.form-group input,
.form-group select {
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

.form-group input:focus,
.form-group select:focus {
    border-color: #00b4d8;
    box-shadow: 0 0 5px rgba(0, 180, 216, 0.5);
}

/* Button styling */
.button {
    width: 80%;
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
.signup-text {
    font-size: 0.9rem;
    margin-top: 15px;
    color: #f1faee;
}

.signup-text a {
    color: #00b4d8;
    text-decoration: none;
    transition: color 0.3s ease;
}

.signup-text a:hover {
    color: #0077b6;
}

/* Responsive adjustments */
@media (max-width: 768px) {
      .container {
            width: 90%;
             padding: 15px;
        }
       header h1{
            font-size: 1.8rem;
        }
        .form-group {
            width: 90%;
        }
       .button {
            width: 90%;
        }
}
    </style>
</head>

<body>
    <header>
        <h1>Sign Up</h1>
    </header>
    <div class="container">
        <?php
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        include 'database.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullName = $_POST['fullName'];
            $rollNo = $_POST['rollNo'];
            $department = $_POST['department'];
            $class = $_POST['class'];
            $division = $_POST['division'];
            $dob = $_POST['dob'];
            $mobile = $_POST['mobile'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirmpassword'];
            $parentsMobile = $_POST['parentsMobile'];

            // Error Array
            $errors = [];

            // Mobile and Parents mobile validation
            if ($mobile === $parentsMobile) {
               $errors[] = "Parents and Students mobile number cannot be the same.";
            }

            // password validation
             if ($password !== $confirmPassword) {
                $errors[] = "Passwords do not match.";
            }

            // Validate email format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format.";
            }


              // if there are errors in form 
            if (!empty($errors)) {
                    foreach ($errors as $error) {
                        echo "<div style='color: red; text-align: center; margin-bottom: 10px;'>$error</div>";
                    }
            }else{
                   // hashing the password
                  $hashedPassword =  password_hash($password, PASSWORD_BCRYPT);
                   $sql = "INSERT INTO students (full_name, roll_no, department, class, division, dob, mobile, email, password, parents_mobile)
                    VALUES ('$fullName', '$rollNo', '$department', '$class', '$division', '$dob', '$mobile', '$email', '$hashedPassword', '$parentsMobile')";
                
                
                 if ($conn->query($sql) === TRUE) {
                        echo "<div style='color: green; text-align: center; margin-bottom: 10px;'>Signup successful!</div>";
                        header("Location: login.php");
                        exit();
                    } else {
                        echo "<div style='color: red; text-align: center; margin-bottom: 10px;'>Error: " . $conn->error . "</div>";
                    }
            }
           
            $conn->close();
        }
        ?>
         <form id="signUpForm" action="signup.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="fullName">Full Name</label>
                <input type="text" id="fullName" name="fullName" required>
            </div>
            <div class="form-group">
                <label for="rollNo">Roll No</label>
                <input type="text" id="rollNo" name="rollNo" required>
            </div>
            <div class="form-group">
                <label for="department">Department</label>
                <select id="department" name="department" required>
                    <option value="BSc (C.S)">BSc (C.S)</option>
                    <option value="BBA">BBA</option>
                    <option value="BBA (C.A)">BBA (C.A)</option>
                    <option value="MSc (C.S)">MSc (C.S)</option>
                    <option value="MSc (C.A)">MSc (C.A)</option>
                </select>
            </div>
            <div class="form-group">
                <label for="class">Class</label>
                <select id="class" name="class" required>
                    <option value="First Year">First Year</option>
                    <option value="Second Year">Second Year</option>
                    <option value="Third Year">Third Year</option>
                    <option value="Fourth Year">Fourth Year</option>
                </select>
            </div>
            <div class="form-group">
                <label for="division">Division</label>
                <select id="division" name="division" required>
                    <option value="A">A</option>
                    <option value="B">B</option>
                </select>
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="dob" required>
            </div>
            <div class="form-group">
                <label for="mobile">Mobile No</label>
                <input type="tel" id="mobile" name="mobile" required>
            </div>
            <div class="form-group">
                <label for="email">Email ID</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirmpassword">Confirm Password</label>
                <input type="password" id="confirmpassword" name="confirmpassword" required>
            </div>

            <div class="form-group">
                <label for="parentsMobile">Parents Mobile No</label>
                <input type="tel" id="parentsMobile" name="parentsMobile" required>
            </div>
            <button name="submit" type="submit" class="button">Sign Up</button>
        </form>
        <p class="signup-text">
             Already have an account? <a href="login.php">Login</a>
        </p>
    </div>
</body>

</html>