<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eLearning Platform</title>
    <link rel="stylesheet" href="style.css">
    <style>
         body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #f0f4f8, #a1c4fd);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .main-title {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
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
            max-width: 800px;
            display: flex;
            flex-direction: row; 
            justify-content: space-between;
            gap: 30px; 
        }

        .login-container {/* Each container takes up about half of the available space */
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
<body>
<nav>
    <div class="main-title">
        <h1>eLearning Platform</h1>
    </div>
</nav>
<div class="container">
        <div class="login-container">
            <h2>Login as Student</h2>
            <form action="studentlogin.php" method="post">
                <input type="email" name="email" placeholder="Enter your email" required>
                <input type="password" name="password" placeholder="Enter your password" required>
                <button type="submit">Login</button>
            </form>
            <p>Don't have an account? <a href="register.php?role=student">Register as Student</a></p>
        </div>

    <div class="login-container">
        <h2>Login as Instructor</h2>
        <form action="login.php" method="post">
            <input type="email" name="email" placeholder="Enter your email" required>
            <input type="password" name="password" placeholder="Enter your password" required>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php?role=instructor">Register as Instructor</a></p>
    </div>
</div>
</body>
</html>
