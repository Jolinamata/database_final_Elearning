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
        // Check if the email exists in the instructors table
        $stmt = $pdo->prepare("SELECT * FROM instructors WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $instructor = $stmt->fetch(PDO::FETCH_ASSOC);

        // Validate instructor credentials
        if ($instructor && password_verify($password, $instructor['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $instructor['id'];
            $_SESSION['user_role'] = 'instructor';
            $_SESSION['user_name'] = $instructor['name'];

            // Redirect to instructor dashboard
            header('Location: instructordashboard.php');
            exit();
        } else {
            // Invalid credentials
            echo "<script>alert('Invalid email or password!');</script>";
        }
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
}
?>