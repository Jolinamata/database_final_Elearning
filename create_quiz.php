<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'instructor') {
    header('Location: login.php');
    exit();
}

include('db_connection.php');

$program = isset($_GET['program']) ? $_GET['program'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
    $description = $_POST['description'];
    $program = $_POST['program'];

    $stmt = $pdo->prepare("INSERT INTO quizzes (program, description) VALUES (:program, :description)");
    $stmt->bindParam(':program', $program);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    $quiz_id = $pdo->lastInsertId();

    if (isset($_POST['mcq_question'])) {
        foreach ($_POST['mcq_question'] as $key => $question) {
            $option1 = $_POST['mcq_option1'][$key];
            $option2 = $_POST['mcq_option2'][$key];
            $option3 = $_POST['mcq_option3'][$key];
            $option4 = $_POST['mcq_option4'][$key];
            $answer = $_POST['mcq_answer'][$key];

            $stmt = $pdo->prepare("INSERT INTO questions (quiz_id, question, type, option1, option2, option3, option4, answer) 
            VALUES (:quiz_id, :question, 'MCQ', :option1, :option2, :option3, :option4, :answer)");
            $stmt->bindParam(':quiz_id', $quiz_id);
            $stmt->bindParam(':question', $question);
            $stmt->bindParam(':option1', $option1);
            $stmt->bindParam(':option2', $option2);
            $stmt->bindParam(':option3', $option3);
            $stmt->bindParam(':option4', $option4);
            $stmt->bindParam(':answer', $answer);
            $stmt->execute();
        }
    }

    if (isset($_POST['tf_question'])) {
        foreach ($_POST['tf_question'] as $key => $question) {
            $answer = $_POST['tf_answer'][$key];

            $stmt = $pdo->prepare("INSERT INTO questions (quiz_id, question, type, answer) 
            VALUES (:quiz_id, :question, 'True/False', :answer)");
            $stmt->bindParam(':quiz_id', $quiz_id);
            $stmt->bindParam(':question', $question);
            $stmt->bindParam(':answer', $answer);
            $stmt->execute();
        }
    }

    if (isset($_POST['fib_question'])) {
        foreach ($_POST['fib_question'] as $key => $question) {
            $answer = $_POST['fib_answer'][$key];

            $stmt = $pdo->prepare("INSERT INTO questions (quiz_id, question, type, answer) 
            VALUES (:quiz_id, :question, 'Fill-in-the-Blank', :answer)");
            $stmt->bindParam(':quiz_id', $quiz_id);
            $stmt->bindParam(':question', $question);
            $stmt->bindParam(':answer', $answer);
            $stmt->execute();
        }
    }

    echo "Quiz created successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Quiz</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
        }

        form {
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            width: 70%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"], textarea, select {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .question-container {
            margin-bottom: 20px;
        }

        .question-container input[type="text"], .question-container select {
            width: 70%;
        }

        .question-container button {
            padding: 10px 15px;
            background-color: #333;
            color: white;
            border: none;
            cursor: pointer;
        }

        .question-container button:hover {
            background-color: #555;
        }

        .submit-button {
            padding: 10px 20px;
            background-color: #333;
            color: white;
            border: none;
            cursor: pointer;
        }

        .submit-button:hover {
            background-color: #555;
        }

        .back-button {
            padding: 10px 20px;
            background-color: #ccc;
            color: black;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }

        .back-button:hover {
            background-color: #aaa;
        }
    </style>
    <script>
        function addMCQQuestion() {
            const container = document.getElementById("mcq-container");
            const newQuestion = document.createElement("div");
            newQuestion.innerHTML = ` 
                <div class="form-group">
                    <label for="mcq_question">Question</label>
                    <input type="text" name="mcq_question[]" required>
                </div>
                <div class="form-group">
                    <label for="mcq_option1">Option 1</label>
                    <input type="text" name="mcq_option1[]" required>
                </div>
                <div class="form-group">
                    <label for="mcq_option2">Option 2</label>
                    <input type="text" name="mcq_option2[]" required>
                </div>
                <div class="form-group">
                    <label for="mcq_option3">Option 3</label>
                    <input type="text" name="mcq_option3[]" required>
                </div>
                <div class="form-group">
                    <label for="mcq_option4">Option 4</label>
                    <input type="text" name="mcq_option4[]" required>
                </div>
                <div class="form-group">
                    <label for="mcq_answer">Correct Answer</label>
                    <input type="text" name="mcq_answer[]" required>
                </div>
            `;
            container.appendChild(newQuestion);
        }

        function addTFQuestion() {
            const container = document.getElementById("tf-container");
            const newQuestion = document.createElement("div");
            newQuestion.innerHTML = `
                <div class="form-group">
                    <label for="tf_question">Question</label>
                    <input type="text" name="tf_question[]" required>
                </div>
                <div class="form-group">
                    <label for="tf_answer">Answer</label>
                    <select name="tf_answer[]" required>
                        <option value="True">True</option>
                        <option value="False">False</option>
                    </select>
                </div>
            `;
            container.appendChild(newQuestion);
        }

        function addFIBQuestion() {
            const container = document.getElementById("fib-container");
            const newQuestion = document.createElement("div");
            newQuestion.innerHTML = `
                <div class="form-group">
                    <label for="fib_question">Question</label>
                    <input type="text" name="fib_question[]" required>
                </div>
                <div class="form-group">
                    <label for="fib_answer">Answer</label>
                    <input type="text" name="fib_answer[]" required>
                </div>
            `;
            container.appendChild(newQuestion);
        }
    </script>
</head>
<body>

<h2>Create a Quiz for <?php echo htmlspecialchars($program); ?></h2>

<form action="create_quiz.php?program=<?php echo htmlspecialchars($program); ?>" method="POST">

    <div class="form-group">
        <label for="description">Quiz Description</label>
        <textarea id="description" name="description" rows="4" required></textarea>
    </div>

    <input type="hidden" name="program" value="<?php echo htmlspecialchars($program); ?>">

    <div id="mcq-container">
        <h3>Multiple Choice Questions</h3>
        <button type="button" onclick="addMCQQuestion()">Add Another MCQ</button>
    </div>

    <div id="tf-container">
        <h3>True/False Questions</h3>
        <button type="button" onclick="addTFQuestion()">Add Another True/False Question</button>
    </div>

    <div id="fib-container">
        <h3>Fill-in-the-Blank Questions</h3>
        <button type="button" onclick="addFIBQuestion()">Add Another Fill-in-the-Blank Question</button>
    </div>

    <div class="form-group">
        <button type="submit" class="submit-button">Submit Quiz</button>
    </div>
</form>

<a href="instructordashboard.php" class="back-button">Back to Dashboard</a>

</body>
</html>
