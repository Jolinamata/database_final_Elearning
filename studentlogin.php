<?php
session_start();

// Include database connection
include('db_connection.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input values
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    try {
        // Prepare and execute the query to fetch student details
        $stmt = $pdo->prepare("SELECT * FROM students WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        // Validate student credentials
        if ($student && password_verify($password, $student['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $student['id'];
            $_SESSION['user_role'] = 'student';
            $_SESSION['user_name'] = $student['name'];

            // Redirect to student dashboard
            header('Location: studentdashboard.php');
            exit();
        } else {
            // Invalid credentials
            echo "<script>alert('Invalid email or password! Please try again.'); window.location.href = 'studentlogin.php';</script>";
        }
    } catch (PDOException $e) {
        // Handle database errors
        echo "<script>alert('An error occurred while processing your request. Please try again later.');</script>";
        error_log("Database error: " . $e->getMessage());
    }
}
?>
