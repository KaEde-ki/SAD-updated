<?php
session_start();
require 'config.php'; // Ensure this file sets up your $pdo connection

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if($_POST['action'] == 'Login'){
        header('Location: login.php');
        exit();
    }

    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $Fname = $_POST['Fname'];
    $Mname = $_POST['Mname'];
    $Lname = $_POST['Lname'];
    $email = $_POST['email'];
    $yr = $_POST['yr'];
    $course = $_POST['course'];

    // Check if username or Email already exists
    $stmt = $pdo->prepare('SELECT UserID FROM users WHERE Username = ? OR Email = ?');
    $stmt->execute([$username, $email]);
    if ($stmt->fetch()) {
        $message = 'Username or Email already taken.';
    } else {
        // Insert new user
        $stmt = $pdo->prepare('INSERT INTO users (Username, password, Fname, Mname, Lname, Email, Yr_lvl, Course) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$username, $password, $Fname, $Mname, $Lname, $email, $yr, $course]);
        $userID = $pdo->lastInsertId();
        $_SESSION['UserID'] = $userID;
        $_SESSION['Username'] = $username;
        header('Location: dashboard.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0;
            background-color: #f0f8ff; /* Light blue background */
        }
        .header {
            background-color: #4e4a81;
            height: 80px;
            padding-top: 10px;
            text-align: center;
            margin: 0;
        }
        .header p {
            margin: 0;
            color: white;
            font-weight: bolder;
            font-size: 40px;
            user-select: none;
        }
        .container {
            width: 400px;
            margin: 50px auto;
            padding: 25px 25px 30px;
            border: 1px solid #007bff;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.2);
        }
        h2 {
            text-align: center;
            color: #4e4a81;
            margin-bottom: 25px;
            font-weight: 700;
            font-size: 28px;
            user-select: none;
        }
        .message {
            color: #d93025; /* red message */
            text-align: center;
            font-weight: 600;
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-top: 10px;
            color: #007bff;
            font-weight: 600;
            user-select: none;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px 12px;
            margin-top: 4px;
            margin-bottom: 12px;
            border: 1.5px solid #007bff;
            border-radius: 6px;
            font-size: 15px;
            transition: border-color 0.3s ease;
            box-sizing: border-box;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        select:focus {
            border-color: #0056b3;
            outline: none;
            box-shadow: 0 0 8px rgba(0, 86, 179, 0.3);
        }
        button[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #4e4a81;
            color: white;
            font-size: 17px;
            font-weight: 700;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.25s ease, transform 0.2s ease;
            user-select: none;
            margin-top: 10px;
        }
        button[type="submit"]:hover {
            background-color: blue;
            transform: translateY(-2px);
        }
        .login-prompt {
            text-align: center;
            margin-top: 18px;
            font-size: 15px;
            color: #007bff;
            user-select: none;
        }
        .login-prompt form {
            display: inline;
        }
        .login-prompt button {
            width: auto;
            padding: 6px 15px;
            font-size: 15px;
            font-weight: 700;
            margin-left: 8px;
            vertical-align: middle;
            border-radius: 6px;
            border: none;
            background-color: #4e4a81;
            color: white;
            cursor: pointer;
            transition: background-color 0.25s ease, transform 0.2s ease;
        }
        .login-prompt button:hover {
            background-color: blue;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="header"><p>SyncEase</p></div>

    <div class="container">
        <h2>Sign Up</h2>
        <?php if ($message): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form method="post" novalidate>
            <label for="Fname">First Name:</label>
            <input type="text" id="Fname" name="Fname" required>

            <label for="Mname">Middle Name:</label>
            <input type="text" id="Mname" name="Mname">

            <label for="Lname">Last Name:</label>
            <input type="text" id="Lname" name="Lname" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="yr">Yr Level:</label>
            <select name="yr" id="yr" required>
                <option value="" disabled selected>Select your year level</option>
                <option value="1">1st year</option>
                <option value="2">2nd year</option>
                <option value="3">3rd year</option>
                <option value="4">4th year</option>
                <option value="5">5th year</option>
            </select>

            <label for="course">Course:</label>
            <input type="text" id="course" name="course" required>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" name="action" value="Sign Up">Sign Up</button>
        </form>

        <div class="login-prompt">
            Already have an account?
            <form method="post" style="display:inline;">
                <button type="submit" name="action" value="Login">Log in</button>
            </form>
        </div>
    </div>
</body>
</html>
