<?php
session_start();

include('db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    try {
        $stmt = $pdo->prepare("SELECT * FROM students WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($student && password_verify($password, $student['password'])) {
         
            $_SESSION['user_id'] = $student['id'];
            $_SESSION['user_role'] = 'student';
            $_SESSION['user_name'] = $student['name'];

            header('Location: studentdashboard.php');
            exit();
        } else {
            echo "<script>alert('Invalid email or password! Please try again.'); window.location.href = 'studentlogin.php';</script>";
        }
    } catch (PDOException $e) {
 
        echo "<script>alert('An error occurred while processing your request. Please try again later.');</script>";
        error_log("Database error: " . $e->getMessage());
    }
}
?>
