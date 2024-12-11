<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'instructor') {
    header('Location: login.php');
    exit();
}

include('db_connection.php');

$stmt = $pdo->prepare("SELECT name FROM instructors WHERE id = :id");
$stmt->bindParam(':id', $_SESSION['user_id']);
$stmt->execute();
$instructor = $stmt->fetch(PDO::FETCH_ASSOC);
$instructor_name = $instructor['name']; 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Dashboard</title>
    <style>
        nav {
            background-color: #333;
            color: white;
            padding: 10px 20px;
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-title h1 {
            font-size: 24px;
        }

        .nav-links a {
            color: white;
            margin: 0 10px;
            text-decoration: none;
            font-size: 18px;
        }

        .nav-links a:hover {
            text-decoration: underline;
        }

        /* Style for the dashboard content */
        .dashboard-content {
            margin: 20px;
            font-size: 20px;
        }

        .container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin-top: 30px;
        }

        .program-container {
            width: 250px;
            margin: 20px;
            border: 1px solid #ddd;
            padding: 20px;
            text-align: center;
            background-color: #f4f4f4;
        }

        .program-container img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .program-container h3 {
            margin-top: 10px;
        }

        .program-container button {
            padding: 10px 15px;
            background-color: #333;
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 10px;
        }

        .program-container button:hover {
            background-color: #555;
        }
        .program-container img {
        width: 200px; 
        height: 150px;
        object-fit: cover; 
        border-radius: 5px;
    }
    </style>
</head>
<body>

<nav>
    <div class="nav-container">
        <div class="nav-title">
            <h1>Welcome, <?php echo htmlspecialchars($instructor_name); ?>!</h1>
        </div>
        <div class="nav-links">
           
            <a href="logout.php">Logout</a>
        </div>
    </div>
</nav>

<div class="dashboard-content">
    <h2>Instructor Dashboard</h2>
    <div class="container">
     
        <div class="program-container">
            <img src="1.jpg" alt="BSIT Program">
            <h3>BSIT</h3>
            <button onclick="location.href='create_quiz.php?program=BSIT'">Create Quiz</button>
        </div>

        <div class="program-container">
            <img src="2.png" alt="TEP Program">
            <h3>TEP</h3>
            <button onclick="location.href='create_quiz.php?program=TEP'">Create Quiz</button>
        </div>

        <div class="program-container">
            <img src="3.jpg" alt="BSBA Program">
            <h3>BSBA</h3>
            <button onclick="location.href='create_quiz.php?program=BSBA'">Create Quiz</button>
        </div>

        <div class="program-container">
            <img src="4.jpg" alt="CRIM Program">
            <h3>CRIM</h3>
            <button onclick="location.href='create_quiz.php?program=CRIM'">Create Quiz</button>
        </div>

    </div>
</div>

</body>
</html>
