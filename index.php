<?php
session_start();

$strength = "";
$errors = [];
$display = false;

// handle POST submission and redirect to avoid resubmission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['password'])) {
    $password = $_POST['password'];

    if (strlen($password) < 12) {
        $errors[] = "Password too short!Must be atleast 12 characters.";
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Password must contain at least one uppercase letter.";
    }
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = "Password must contain at least one lowercase letter.";
    }
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = "Password must contain at least one number.";
    }
    if (!preg_match('/[!@#$%^&*()-+]/', $password)) {
        $errors[] = "Password must contain at least one special character.";
    }

    if (empty($errors)) {
        $strength = "Strong";
    } elseif (count($errors) <= 3) {
        $strength = "medium";
    } else {
        $strength = "weak";
    }

    // store results and display flag in session then redirect
    $_SESSION['strength'] = $strength;
    $_SESSION['errors'] = $errors;
    $_SESSION['display'] = true;
    header('Location: index.php');
    exit;
}

// retrieve results from session if present
if (isset($_SESSION['display'])) {
    $display = $_SESSION['display'];
    unset($_SESSION['display']);
}
if (isset($_SESSION['strength'])) {
    $strength = $_SESSION['strength'];
    unset($_SESSION['strength']);
}
if (isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    unset($_SESSION['errors']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Strength Checker</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            text-align: center;
        }
        h2 {
            margin-bottom: 30px;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #7fa728;
            color: #fff;
            border: none;
            border-radius: 1px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Password strength checker</h2>
        <form action="index.php" method="post">
        <input type="password" name="password" placeholder="Enter your password">
        <br><br>
        <input type="submit" name="Submit" value="Check Strength">
        <br><br>
        </form>
        <?php
if ($display) {
    echo "<h3>Strength: $strength</h3>";

    if (!empty($errors)) {
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
    }
}
?>
    </div>
</body>
</html>