<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<?php
include("../config/database.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $name = trim($_POST["name"]);
    $surname = trim($_POST["surname"]);
    $employee_number = trim($_POST["employee_number"]);
    $campus_name = trim($_POST["campus_name"]);

    // Validation
    if (empty($name) || empty($surname) || empty($employee_number) || empty($campus_name))
    {
        $message = "All fields are required.";
    }
    elseif (!preg_match("/^[a-zA-Z ]+$/", $name))
    {
        $message = "Name may only contain letters.";
    }
    elseif (!preg_match("/^[a-zA-Z ]+$/", $surname))
    {
        $message = "Surname may only contain letters.";
    }
    elseif (!preg_match("/^[0-9]{5,10}$/", $employee_number))
    {
        $message = "Employee number must contain 5 to 10 digits.";
    }
    else
    {
        // Check if employee number already exists
        $check = $conn->prepare(
            "SELECT employee_id FROM employees WHERE employee_number = ?"
        );

        $check->bind_param("s", $employee_number);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0)
        {
            $message = "Employee number already exists.";
        }
        else
        {
            $stmt = $conn->prepare(
                "INSERT INTO employees
                (name, surname, employee_number, campus_name)
                VALUES (?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "ssss",
                $name,
                $surname,
                $employee_number,
                $campus_name
            );

            if ($stmt->execute())
            {
                $message = "Registration successful!";
            }
            else
            {
                $message = "Registration failed.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Registration</title>
</head>
<body>

<h2>Employee Registration</h2>

<p style="color:red;">
    <?php echo $message; ?>
</p>

<form method="POST">

    <label>Name:</label><br>
    <input type="text" name="name"><br><br>

    <label>Surname:</label><br>
    <input type="text" name="surname"><br><br>

    <label>Employee Number:</label><br>
    <input type="text" name="employee_number"><br><br>

    <label>Campus Name:</label><br>
    <input type="text" name="campus_name"><br><br>

    <input type="submit" value="Register">

</form>

<br>

<a href="login.php">Go to Login</a>

</body>
</html>