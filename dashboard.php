<?php
session_start();
require 'config.php';

if (!isset($_SESSION['UserID'])) {
    header('Location: login.php');
    exit();
}

// Fetch tasks for the logged-in user, including times
$stmt = $pdo->prepare('SELECT TaskID, Title, Category, StartDate, StartTime, DueDate, EndTime FROM tasks WHERE UserID = ?');
$stmt->execute([$_SESSION['UserID']]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Organize tasks by date
$tasksByDate = [];
foreach ($tasks as $task) {
    $title   = htmlspecialchars($task['Title']);
    $start   = $task['StartDate'];
    $due     = $task['DueDate'];
    $stTime  = $task['StartTime'];
    $endTime = $task['EndTime'];

    $entry = ['title' => $title, 'time' => "$stTime – $endTime"];
    $tasksByDate[$start][] = $entry;
    if (!empty($due) && $due !== $start) {
        $tasksByDate[$due][] = $entry;
    }
}

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['doneTaskId'])){
        $taskId =(Int)$_POST['doneTaskId'];
        $stmt = $pdo -> prepare("DELETE FROM tasks WHERE TaskID = ? AND UserID = ?");
        $stmt-> execute([$taskId, $_SESSION['UserID']]);
        header("Location: dashboard.php");
        exit();
    }

// Calendar params
$year = date('Y');
$month = date('m');
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$firstDayOfMonth = date('w', strtotime("$year-$month-01"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard SyncEase</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <header class="site-header">
        <div class="logo">
            <img src="img/Logo.png" alt="SyncEase logo">
            <span class="site-title">SyncEase</span>
        </div>
        <div class="user-controls">
            <span class="welcome">Welcome, <?php echo htmlspecialchars($_SESSION['Username']); ?>!</span>
            <img class="avatar" src="img/Profile.webp" alt="User profile picture">
            <a href="add_task.php" class="btn">Add Task</a>
            <a href="logout.php" class="btn btn-secondary">Logout</a>
        </div>
    </header>

    <main class="dashboard">
        <section class="calendar">
            <h2>Calendar  <?php echo date('F Y'); ?></h2>
            <table>
                <thead>
                    <tr>
                        <th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th>
                        <th>Thu</th><th>Fri</th><th>Sat</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <?php
                    $day = 1;
                    $cell = 0;
                    for ($i = 0; $i < $firstDayOfMonth; $i++) { echo '<td></td>'; $cell++; }
                    while ($day <= $daysInMonth) {
                        $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
                        echo '<td><span class="day-number">' . $day . '</span>';
                        if (isset($tasksByDate[$date])) {
                            foreach ($tasksByDate[$date] as $task) {
                                echo '<div class="task"><span class="task-title">' . $task['title'] . '</span>'
                                    . '<span class="task-time">' . $task['time'] . '</span></div>';
                            }
                        }
                        echo '</td>';
                        $day++; $cell++;
                        if ($cell % 7 === 0 && $day <= $daysInMonth) { echo "</tr><tr>"; }
                    }
                    while ($cell % 7 !== 0) { echo '<td></td>'; $cell++; }
                    ?>
                    </tr>
                </tbody>
            </table>
        </section>

        <section class="task-list">
            <h2>Your Tasks</h2>
            <ul>
                <?php foreach ($tasks as $task): ?>
                    <li class="task-item">
                        <strong><?php echo htmlspecialchars($task['Title']); ?></strong><br>
                        <small><?php echo $task['StartDate'] . ' ' . $task['StartTime']; ?></small>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="doneTaskId" value="<?php echo $task['TaskID']; ?>">
                            <button type="submit" class="btn btn-done">Done</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </main>

</body>
</html>