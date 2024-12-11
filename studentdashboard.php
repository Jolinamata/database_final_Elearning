<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    header('Location: login.php');
    exit();
}

include('db_connection.php');

// Fetch the student's name and course
$stmt = $pdo->prepare("SELECT name, course FROM students WHERE id = :id");
$stmt->bindParam(':id', $_SESSION['user_id']);
$stmt->execute();
$student = $stmt->fetch(PDO::FETCH_ASSOC);
$student_name = $student['name'];
$student_course = $student['course'];

// Fetch questions for the student (you can adjust the query based on quiz_id or any other condition)
$stmt = $pdo->prepare("SELECT * FROM questions");
$stmt->execute();
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            color: #333;
            margin: 0;
            padding: 0;
        }

        nav {
            background-color: #4CAF50;
            padding: 15px 0;
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .nav-title h1 {
            color: white;
            font-size: 24px;
            margin: 0;
        }

        .nav-links a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
            font-size: 18px;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: #ffeb3b;
        }

        .dashboard-content {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .question-container {
            margin-top: 20px;
        }

        .question {
            background-color: #f1f1f1;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .question h3 {
            margin: 0;
            font-size: 18px;
        }

        .options {
            margin-top: 10px;
        }

        .options p {
            margin: 5px 0;
        }

    </style>
</head>
<body>

<!-- Navigation Bar -->
<nav>
    <div class="nav-container">
        <div class="nav-title">
            <h1>Welcome, <?php echo htmlspecialchars($student_name); ?>!</h1>
        </div>
        <div class="nav-links">
            <a href="logout.php">Logout</a>
        </div>
    </div>
</nav>

<!-- Student Dashboard Content -->
<div class="dashboard-content">
    <h2>You're enrolled in the <?php echo htmlspecialchars($student_course); ?> course.</h2>

    <!-- Display Questions -->
    <div class="question-container">
        <h3>Questions for your quiz:</h3>
        <?php foreach ($questions as $question): ?>
            <div class="question">
                <h3>Question: <?php echo htmlspecialchars($question['question']); ?></h3>
                <div class="options">
                    <?php if ($question['type'] === 'MCQ'): ?>
                        <p><strong>Option 1:</strong> <?php echo htmlspecialchars($question['option1']); ?></p>
                        <p><strong>Option 2:</strong> <?php echo htmlspecialchars($question['option2']); ?></p>
                        <p><strong>Option 3:</strong> <?php echo htmlspecialchars($question['option3']); ?></p>
                        <p><strong>Option 4:</strong> <?php echo htmlspecialchars($question['option4']); ?></p>
                    <?php elseif ($question['type'] === 'True/False'): ?>
                        <p><strong>Answer:</strong> <?php echo htmlspecialchars($question['answer']); ?></p>
                    <?php elseif ($question['type'] === 'Fill-in-the-Blank'): ?>
                        <p><strong>Answer:</strong> <?php echo htmlspecialchars($question['answer']); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
