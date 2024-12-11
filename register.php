<?php

require_once 'db_connection.php';

$role = $_GET['role'] ?? null;
if (!$role || !in_array($role, ['student', 'instructor'])) {
    die('Invalid role.');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $course = $_POST['course'] ?? null; 
    if ($role == 'student') {
        $stmt = $pdo->prepare("SELECT * FROM students WHERE email = ?");
    } else {
        $stmt = $pdo->prepare("SELECT * FROM instructors WHERE email = ?");
    }
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
       
        echo "Email already registered. <a href='index.php'>Login here</a>";
        exit; 
    }

    if ($role == 'student') {
        $stmt = $pdo->prepare("INSERT INTO students (name, email, password, course) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $password, $course]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO instructors (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $password]);
    }

    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register as <?php echo ucfirst($role); ?> - eLearning Platform</title>
    
    <style>    body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #f0f4f8, #a1c4fd);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .main-title {
            text-align: center;
            margin-bottom: 30px; 
        }

        .main-title h1 {
            font-size: 2.5em;
            color: #4a90e2;
            text-transform: uppercase;
        }

        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 800px; /* Set a maximum width for landscape layout */
            display: flex;
            flex-direction: row; /* Arrange content in landscape */
            justify-content: space-between;
            gap: 30px; /* Add some space between the sections */
        }

        .login-container {
            width: 45%; /* Each container takes up about half of the available space */
        }

        .login-container h2 {
            color: #333;
            font-size: 1.6em;
            margin-bottom: 20px;
            text-align: center;
        }

        input, select {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1em;
        }

        button {
            width: 100%;
            padding: 14px;
            margin-top: 20px;
            background-color: #4a90e2;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 1.2em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #357ab7;
        }

        a button {
            width: auto;
            margin-top: 10px;
            background-color: #f0f4f8;
            color: #4a90e2;
            border: 1px solid #4a90e2;
        }

        a button:hover {
            background-color: #4a90e2;
            color: white;
        }

        p {
            text-align: center;
            font-size: 1.1em;
        }

        a {
            color: #4a90e2;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="main-title">
        <h1>Register as <?php echo ucfirst($role); ?></h1>
    </div>

    <div class="container">
        <div class="register-container">
            <h2>Register as <?php echo ucfirst($role); ?></h2>
            <form action="register.php?role=<?php echo $role; ?>" method="post">
                <input type="text" name="name" placeholder="Enter your full name" required>
                <input type="email" name="email" placeholder="Enter your email" required>
                <input type="password" name="password" placeholder="Create a password" required>

                <?php if ($role == 'student'): ?>
                    <label for="course">Select your course:</label>
                    <select name="course" required>
                        <option value="BSIT">BS in Information Technology (BSIT)</option>
                        <option value="TEP">Teacher Education Program (TEP)</option>
                        <option value="BSBA">BS in Business Administration (BSBA)</option>
                        <option value="CRIM">BS in Criminology (CRIM)</option>
                    </select>
                <?php endif; ?>

                <button type="submit">Register</button>
            </form>
            <br>
            <a href="index.php"><button type="button">Back to Login</button></a>
        </div>
    </div>

</body>
</html>
