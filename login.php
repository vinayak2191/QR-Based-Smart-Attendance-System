<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* General Styles */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #1d3557, #457b9d);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #fff;
        }

        /* Header Styles */
        header {
            position: absolute;
            top: 20px;
            left: 20px;
        }

        header h1 {
           
           font-size: 2rem;
           font-weight: bold;
           color: #fff;
            
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

        /* Button */
        .button {
            width: 100%;
            padding: 10px;
            font-size: 1.1rem;
            background: #00b4d8;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .button:hover {
            background: #0077b6;
        }

        .button:active {
            transform: scale(0.98);
        }

        /* Additional Text */
        .signup-text,
        .info-text {
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

        /* Responsive Design */
        @media (max-width: 768px) {
            .login-box {
                padding: 20px;
            }

            .login-box h2 {
                font-size: 1.5rem;
            }

            .button {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <header>
        <h1 class="container">Login</h1>
    </header>
    <div class="login-container">
    <?php
        // Include database connection
        include 'database.php';

        // Start session for user login
        session_start();

        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Use prepared statement to prevent SQL injection
            $stmt = $conn->prepare("SELECT * FROM students WHERE email = ?");
            $stmt->bind_param("s", $email); // "s" indicates string type
            $stmt->execute();
            $result = $stmt->get_result();
            

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();

                if (password_verify($password, $user['password'])) {
                     $_SESSION['user_id'] = $user['id'];
                        $_SESSION['full_name'] = $user['full_name'];
                        $_SESSION['roll_no'] = $user['roll_no'];


                    echo "Login successful! Redirecting...";
                    header("Location: home.php");
                    exit();
                } else {
                    echo "Invalid password.";
                }
            } else {
                echo "No account found with this email.";
            }

            $stmt->close();
             $conn->close();
        }
        ?>
        <div class="login-box">
            <form id="loginForm" action="login.php" method="POST">
                <div class="form-group">
                    <label for="username">Email ID</label>
                    <input type="email" id="username" name="email" placeholder="Enter your email ID" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <button class="button" type="submit">Login</button>
            </form>
            <p class="signup-text">
                Don't have an account? <a href="signup.php">Sign Up</a>
            </p>
            <p class="info-text">
                Remember to save your Email and Password for future use.
            </p>
        </div>
    </div>
</body>

