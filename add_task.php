<?php
session_start();
require 'config.php';

if (!isset($_SESSION['UserID'])) {
    header('Location: login.php');
    exit();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title      = trim($_POST['title']);
    $category   = trim($_POST['category']);
    $priority   = trim($_POST['Prio']);
    $startDate  = $_POST['start_date'] ?: null;
    $startTime  = $_POST['start_time'] ?: null;
    $dueDate    = $_POST['due_date']   ?: null;
    $endTime    = $_POST['end_time']   ?: null;

    // Basic validation
    if (!$title || !$startDate || !$startTime || !$dueDate || !$endTime) {
        $message = 'Please fill in all required fields.';
    } elseif ($dueDate < $startDate || ($dueDate === $startDate && $endTime <= $startTime)) {
        $message = 'End date/time must be after start date/time.';
    } else {
        // Check for time overlap on each date
        // Here we only check on the same day; adjust as needed for multi-day tasks
        $stmt = $pdo->prepare(
            'SELECT COUNT(*) FROM tasks 
             WHERE UserID = ?
               AND StartDate = ?
               AND (
                   (StartTime < ? AND EndTime > ?)  -- overlap condition
               )'
        );
        $stmt->execute([
            $_SESSION['UserID'],
            $startDate,
            $endTime,
            $startTime
        ]);
        $conflicts = $stmt->fetchColumn();

        if ($conflicts > 0) {
            $message = 'This time slot is already occupied. Please choose a different time.';
        } else {
            // Insert new task
            $insert = $pdo->prepare(
                'INSERT INTO tasks 
                 (UserID, Title, Category, Priority, StartDate, StartTime, DueDate, EndTime)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
            );
            $insert->execute([
                $_SESSION['UserID'],
                $title,
                $category,
                $priority,
                $startDate,
                $startTime,
                $dueDate,
                $endTime
            ]);

            $message = 'Task added successfully.';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Task with Time</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0;
        }
        .container { 
            width: 400px; 
            margin: 50px auto; 
            max-width: 1500px;
        }
        .message { 
            color: green; 
            text-align: center; 
        }
        .error {
             color: red; 
        }

        label {
             display: block; 
             margin-top: 10px;
        }
        input, select {
             width: 100%; 
             padding: 8px; 
             margin-top: 4px; 
        }
        button {
            margin-top: 2rem;
            padding:  15px;
            background-color: #4e4a81;
            color: white;
            font-size: 15px;
            font-weight: bold;
            border-radius: 5px;
        }
        button:hover {
            background-color: blue;
            transform: translateY(-2px);
        }
        a {
            text-decoration: none;
        }
        .back {
            display: inline-block;
            background-color: lightblue;
            color: white;
            padding: 10px;
            margin-top: 1rem;
            margin-left: 2rem;
            margin-right:6rem;
            border-radius: 5px;
        }

        .back:hover {
            background-color:lightgray;
            color: black;
        }
        .header {
            background-color: #4e4a81;
            height: 80px;
            margin: 0px;
            padding-top: 10px;
        }
        .header p {
            margin: 0;
            color: white;
            font-weight: bolder;
            font-size: 40px;
        }
    </style>
</head>
<body>
<div class="header">
        <p>SyncEase</p>
</div>

    <div class="container">
        <h2>Add Task with Time</h2>
        <?php if ($message): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form method="post">
            <label>Title:<span class="error">*</span></label>
            <input type="text" name="title" required>

            <label>Category:</label>
            <select name="category">
                <option value="Academic">Academic</option>
                <option value="Extracurricular">Extracurricular</option>
            </select>

            <label>Priority:</label>
            <select name="Prio">
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
            </select>

            <label>Start Date:<span class="error">*</span></label>
            <input type="date" name="start_date" required>

            <label>Start Time:<span class="error">*</span></label>
            <input type="time" name="start_time" required>

            <label>Due Date:<span class="error">*</span></label>
            <input type="date" name="due_date" required>

            <label>End Time:<span class="error">*</span></label>
            <input type="time" name="end_time" required>

            <div class="back">
                <a href="dashboard.php">Back to Dashboard</a>
            </div>
            <button type="submit">Add Task</button>
            
        </form>

        
    </div>
</body>
</html>
