<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<?php
session_start();

if (!isset($_SESSION["employee_id"]))
{
    header("Location: login.php");
    exit();
}

include("../config/database.php");

$id = $_GET["id"];

$result = $conn->query(
    "SELECT * FROM equipment WHERE equipment_id = $id"
);

$row = $result->fetch_assoc();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $equipment_name = $_POST["equipment_name"];
    $equipment_category = $_POST["equipment_category"];
    $equipment_condition = $_POST["equipment_condition"];
    $availability_status = $_POST["availability_status"];
    $replacement_value = $_POST["replacement_value"];

    $stmt = $conn->prepare(
        "UPDATE equipment
         SET equipment_name=?,
             equipment_category=?,
             equipment_condition=?,
             availability_status=?,
             replacement_value=?
         WHERE equipment_id=?"
    );

    $stmt->bind_param(
        "ssssdi",
        $equipment_name,
        $equipment_category,
        $equipment_condition,
        $availability_status,
        $replacement_value,
        $id
    );

    if ($stmt->execute())
    {
        header("Location: view_equipment.php");
        exit();
    }
    else
    {
        $message = "Update failed.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Equipment</title>
</head>
<body>

<h2>Edit Equipment</h2>

<p><?php echo $message; ?></p>

<form method="POST">

<label>Equipment Name</label><br>
<input type="text"
       name="equipment_name"
       value="<?php echo $row['equipment_name']; ?>">
<br><br>

<label>Category</label><br>
<select name="equipment_category">

<option <?php if($row['equipment_category']=="Laptop") echo "selected"; ?>>Laptop</option>
<option <?php if($row['equipment_category']=="Projector") echo "selected"; ?>>Projector</option>
<option <?php if($row['equipment_category']=="Tablet") echo "selected"; ?>>Tablet</option>
<option <?php if($row['equipment_category']=="Camera") echo "selected"; ?>>Camera</option>
<option <?php if($row['equipment_category']=="Microphone") echo "selected"; ?>>Microphone</option>
<option <?php if($row['equipment_category']=="Speciality Equipment") echo "selected"; ?>>Speciality Equipment</option>

</select>
<br><br>

<label>Condition</label><br>
<select name="equipment_condition">

<option <?php if($row['equipment_condition']=="New") echo "selected"; ?>>New</option>
<option <?php if($row['equipment_condition']=="Good") echo "selected"; ?>>Good</option>
<option <?php if($row['equipment_condition']=="Fair") echo "selected"; ?>>Fair</option>
<option <?php if($row['equipment_condition']=="Damaged") echo "selected"; ?>>Damaged</option>

</select>
<br><br>

<label>Status</label><br>
<select name="availability_status">

<option <?php if($row['availability_status']=="Available") echo "selected"; ?>>Available</option>
<option <?php if($row['availability_status']=="Loaned Out") echo "selected"; ?>>Loaned Out</option>
<option <?php if($row['availability_status']=="Unavailable") echo "selected"; ?>>Unavailable</option>

</select>
<br><br>

<label>Replacement Value</label><br>
<input type="number"
       step="0.01"
       name="replacement_value"
       value="<?php echo $row['replacement_value']; ?>">
<br><br>

<input type="submit" value="Update Equipment">

</form>

</body>
</html>