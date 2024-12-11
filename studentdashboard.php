<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    header('Location: studentdashboard.php');
    exit();
}

include('db_connection.php');

$stmt = $pdo->prepare("SELECT name, course FROM students WHERE id = :id");
$stmt->bindParam(':id', $_SESSION['user_id']);
$stmt->execute();
$student = $stmt->fetch(PDO::FETCH_ASSOC);
$student_name = $student['name'];
$student_course = $student['course'];

$stmt = $pdo->prepare("SELECT * FROM quizzes");
$stmt->execute();
$quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            background-color: #f0f2f5;
            color: #333;
            margin: 0;
            padding: 0;
        }

        nav {
            background-color: #333;
            padding: 15px;
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        .nav-title {
            color: white;
            font-size: 24px;
            margin: 0;
        }

        .nav-links a {
            color: white;
            margin: 0 10px;
            text-decoration: none;
            font-size: 16px;
            padding: 8px 12px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .nav-links a:hover {
            background-color: #4caf50;
        }

        .dashboard-content {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .quiz-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .quiz {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .quiz:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .quiz h3 {
            font-size: 20px;
            margin: 0;
        }

        .quiz p {
            margin: 10px 0;
            font-size: 16px;
            color: #555;
        }

        .quiz button {
            padding: 10px 15px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .take-quiz-button {
            background-color: #4caf50;
            color: white;
        }

        .take-quiz-button:hover {
            background-color: #45a049;
        }

        .done-button {
            background-color: #bbb;
            color: white;
            cursor: not-allowed;
        }

        .score {
            margin-top: 10px;
            font-size: 14px;
            color: #333;
        }
    </style>
</head>
<body>

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

<div class="dashboard-content">
  

    <div class="quiz-container">
        <h3>Available Quizzes:</h3>
        <?php foreach ($quizzes as $quiz): ?>
            <?php
            $stmt = $pdo->prepare("
                SELECT COUNT(*) as count, SUM(is_correct) as total_score 
                FROM quiz_results 
                WHERE user_id = :user_id AND quiz_id = :quiz_id
            ");
            $stmt->bindParam(':user_id', $_SESSION['user_id']);
            $stmt->bindParam(':quiz_id', $quiz['id']);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $is_taken = $result['count'] > 0;
            $total_score = $result['total_score'] ?? 0;
            ?>
            <div class="quiz">
                <h3><?php echo htmlspecialchars($quiz['program']); ?></h3>
                <p><?php echo htmlspecialchars($quiz['description']); ?></p>
                <?php if ($is_taken): ?>
                    <button class="done-button">Done</button>
                    <p class="score">Total Points: <?php echo $total_score; ?></p>
                <?php else: ?>
                    <a href="take_quiz.php?quiz_id=<?php echo $quiz['id']; ?>">
                        <button class="take-quiz-button">Take Quiz</button>
                    </a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
