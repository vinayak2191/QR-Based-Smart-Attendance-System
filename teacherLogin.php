<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Login</title>
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
        <h1>Teacher Login</h1>
        
    </header>

    <div class="form-container">
    <form id="teacherLoginForm" action="teacherlogin_process.php" method="POST">
        <h2 class="form-title">Teacher Login</h2>

        <div class="form-group">
            <label for="email" style="color:white;">Email:</label>
            <input type="email" id="email" name="email" required placeholder="Enter your email">
        </div>

        <div class="form-group">
            <label for="password"  style="color:white;">Password:</label>
            <input type="password" id="password" name="password" required placeholder="Enter your password">
        </div>

        <button class="button" type="submit">Login</button>
    </form>

    <div class="form-footer">
        <h5>Don't have an account? <a href="teacher_register.php">Register</a></h5>
        <h4>Save your ID and Password for Future Login</h4>
    </div>
</div>

    <script>
         document.querySelector('form').addEventListener('submit', function(e) {
        const email = document.getElementById('email');
        const password = document.getElementById('password');

        // Validate the email and password
        if (!email.value || !password.value) {
            alert('Please fill in both fields.');
            e.preventDefault(); // Prevent form submission
        }
    });
        // Toggle between light and dark mode
        function toggleTheme() {
            var body = document.body;
            var themeIcon = document.querySelector(".icon-sun-moon");

            // Toggle the classes for light and dark mode
            if (body.classList.contains("dark-mode")) {
                body.classList.remove("dark-mode");
                body.classList.add("light-mode");
                themeIcon.textContent = "🌞"; // Sun icon for light mode
            } else {
                body.classList.remove("light-mode");
                body.classList.add("dark-mode");
                themeIcon.textContent = "🌙"; // Moon icon for dark mode
            }
        }
    </script>
</body>

</html>