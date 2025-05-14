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

    // Check if username and Email already exists
    $stmt = $pdo->prepare('SELECT UserID FROM users WHERE Username = ? AND Email = ?');
    $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $message = 'Username already taken.';
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
        body { font-family: Arial, sans-serif; }
        .container { width: 300px; margin: 50px auto; }
        .message { color: red; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Sign Up</h2>
        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="post">
            <label >First Name:</label>
            <input type="text" id="Fname" name="Fname" ><br><br>

            <label >Middle Name: </label>
            <input type="text" id="Mname" name="Mname"><br><br>

            <label>Last Name:</label>
            <input type="text" id="Lname" name="Lname" ><br><br>

            <label>Email:</label>
            <input type="email" id="email" name="email" ><br><br>

            <label>Yr Level: </label>
            <select name="yr" id="yr" name="yr">
                <option value="1">1st year</option>
                <option value="2">2nd year</option>
                <option value="3">3rd year</option>
                <option value="4">4th year</option>
                <option value="5">5th year</option>
            </select><br><br>

            <label>Course</label>
            <input type="text" id="course" name="course"><br><br>

            <label>Username:</label><br>
            <input type="text" name="username" ><br><br>

            <label>Password:</label><br>
            <input type="password" name="password" ><br><br>

            <button type="submit" name="action" value="Sign Up"> Sign Up</button>
            already have an account?
            <button type="submit" name="action" value="Login">Log in</button>
        </form>
    </div>
</body>
</html>
