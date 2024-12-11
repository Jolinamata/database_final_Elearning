<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$quiz_id = isset($_GET['quiz_id']) ? intval($_GET['quiz_id']) : 0;

if ($quiz_id <= 0) {
    echo "Invalid quiz ID.";
    exit();
}

$stmt = $pdo->prepare("SELECT program, description FROM quizzes WHERE id = :quiz_id");
$stmt->bindParam(':quiz_id', $quiz_id);
$stmt->execute();
$quiz = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$quiz) {
    echo "Quiz not found.";
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM questions WHERE quiz_id = :quiz_id");
$stmt->bindParam(':quiz_id', $quiz_id);
$stmt->execute();
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    foreach ($_POST['answers'] as $question_id => $answer) {
        $stmt = $pdo->prepare("SELECT answer FROM questions WHERE id = :question_id");
        $stmt->bindParam(':question_id', $question_id);
        $stmt->execute();
        $correct_answer = $stmt->fetchColumn();

        $is_correct = ($answer === $correct_answer) ? 1 : 0;

        $stmt = $pdo->prepare("INSERT INTO quiz_results (user_id, quiz_id, question_id, answer, is_correct)
            VALUES (:user_id, :quiz_id, :question_id, :answer, :is_correct)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':quiz_id', $quiz_id);
        $stmt->bindParam(':question_id', $question_id);
        $stmt->bindParam(':answer', $answer);
        $stmt->bindParam(':is_correct', $is_correct);
        $stmt->execute();
    }

    header('Location: studentdashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take Quiz - <?php echo htmlspecialchars($quiz['program']); ?></title>
    <style> body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #1c92d2, #f2fcfe);
            color: #333;
        }

        header {
    max-width: 700px;
    width: 90%;
    background: rgba(255, 255, 255, 0.1);
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(10px);
}

        header h1 {
    font-size: 3rem;
    margin: 0;
    color: #f9f9f9;
    text-transform: uppercase;
    letter-spacing: 2px;
    text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
}
        header p {
    font-size: 1.5rem;
    margin-top: 20px;
    color: #e0e0e0;
    line-height: 1.8;
    text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.3);
}

        form {
            max-width: 900px;
            margin: 30px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
        }

        .question {
            margin-bottom: 30px;
            padding: 20px;
            background: #f9f9f9;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            transition: transform 0.2s;
        }

        .question:hover {
            transform: scale(1.02);
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
        }

        .question p {
            margin: 0 0 10px;
            font-size: 1.2em;
            font-weight: bold;
        }

        .question div {
            margin-top: 10px;
        }

        .question label {
            display: block;
            margin-bottom: 5px;
            font-size: 1em;
            cursor: pointer;
        }

        button {
            display: block;
            width: 100%;
            padding: 15px;
            font-size: 1.2em;
            font-weight: bold;
            color: white;
            background-color: #1c92d2;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #1479a6;
        }</style>
</head>
<body>
    <h1><?php echo htmlspecialchars($quiz['program']); ?></h1>
    <p><?php echo htmlspecialchars($quiz['description']); ?></p>

    <form method="POST">
        <?php foreach ($questions as $question): ?>
            <div>
                <p><strong><?php echo htmlspecialchars($question['question']); ?></strong></p>
                <?php if ($question['type'] === 'MCQ'): ?>
                    <div>
                        <label><input type="radio" name="answers[<?php echo $question['id']; ?>]" value="<?php echo $question['option1']; ?>"> <?php echo htmlspecialchars($question['option1']); ?></label><br>
                        <label><input type="radio" name="answers[<?php echo $question['id']; ?>]" value="<?php echo $question['option2']; ?>"> <?php echo htmlspecialchars($question['option2']); ?></label><br>
                        <label><input type="radio" name="answers[<?php echo $question['id']; ?>]" value="<?php echo $question['option3']; ?>"> <?php echo htmlspecialchars($question['option3']); ?></label><br>
                        <label><input type="radio" name="answers[<?php echo $question['id']; ?>]" value="<?php echo $question['option4']; ?>"> <?php echo htmlspecialchars($question['option4']); ?></label>
                    </div>
                <?php elseif ($question['type'] === 'True/False'): ?>
                    <div>
                        <label><input type="radio" name="answers[<?php echo $question['id']; ?>]" value="True"> True</label><br>
                        <label><input type="radio" name="answers[<?php echo $question['id']; ?>]" value="False"> False</label>
                    </div>
                <?php else: ?>
                    <div>
                        <label>Answer: <input type="text" name="answers[<?php echo $question['id']; ?>]"></label>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        <button type="submit">Submit Quiz</button>
    </form>
</body>
</html>
