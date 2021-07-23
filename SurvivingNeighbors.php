<!DOCTYPE html>
<?php include "../inc/dbinfo.inc"; ?>

<head>
    <meta charset="utf-8">
    <meta name="author" content="Group 5 Members">
    <meta name="description" content="Group 5's Internship Project"
    <!-- Bootstrap CSS Style Sheets -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
     <!--This is where we put the .css file after bootstrap to override Bootstrap styling -->
    <link rel="stylesheet" href="css/sample_style.css">
    <script src="sample.js"></script>
     <!--Font Awesome (icons) -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.7/js/all.js"></script>
    <script src="https://kit.fontawesome.com/3ba8aa97bb.js" crossorigin="anonymous"></script>
     <!--TODO: Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Creepster&family=Ubuntu" rel="stylesheet">
    <!--Bootstrap Scripts -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <title>sample</title>
</head>

<nav id="floatingMenu">
        <ul>
                <button> </button>
                <li><a href="index.html"> Home </a></li>
                <li><a href="http://ec2-34-205-161-96.compute-1.amazonaws.com/Attacks.php"> Zombie Attacks </a></li>
                <li><a href="http://ec2-34-205-161-96.compute-1.amazonaws.com/SurvivingNeighbors.php"> Survivors </a></li>
                <li><a href="http://ec2-34-205-161-96.compute-1.amazonaws.com/HealthSurvey.php"> Health Survey </a></li>
                <li><a href="http://ec2-34-205-161-96.compute-1.amazonaws.com/FoodInventory.php"> Food Inventory </a></li>
        </ul>
</nav>

<html>
<body>
<h1>Surviving Neighbors</h1>
<?php

  /* Connect to MySQL and select the database. */
  $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

  if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

  $database = mysqli_select_db($connection, DB_DATABASE);

  /* Ensure that the add AddSurvivor table exists. */
  VerifySurvivorsTable($connection, DB_DATABASE);

  /* If input fields are populated, add a row to the AddSurvivor table. */
  $survivor_name= htmlentities($_POST['NAME']);
  $survivor_address= htmlentities($_POST['ADDRESS']);
  $survivor_ssn= htmlentities($_POST['SSN']);
  $survivor_age= htmlentities($_POST['AGE']);
  $survivor_gender= htmlentities($_POST['GENDER']);
  
  if (strlen($survivor_name) || strlen($survivor_address) || strlen($survivor_ssn) || strlen($survivor_age) || strlen($survivor_gender)) {
    AddSurvivor($connection, $survivor_name, $survivor_address, $survivor_ssn, $survivor_age, $survivor_gender);
  }
?>

<!-- Input form -->
<form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
  <table border="0">
    <tr>
      <td>NAME</td>
      <td>ADDRESS</td>
      <td>SSN</td>
      <td>AGE</td>
      <td>GENDER</td>
    </tr>
    <tr>
      <td>
        <input type="text" name="NAME" maxlength="45" size="30" />
      </td>
      <td>
        <input type="text" name="ADDRESS" maxlength="90" size="60" />
      </td>
      <td>
        <input type="text" name="SSN" maxlength="12" size="30" />
      </td>
      <td>
        <input type="text" name="AGE" maxlength="3" size="30" />
      </td>
      <td>
        <input type="text" name="GENDER" maxlength="4" size="30" />
      </td>
      <td>
        <input type="submit" value="Add Data" />
      </td>
    </tr>
  </table>
</form>

<!-- Display table data. -->
<table border="1" cellpadding="2" cellspacing="2">
  <tr>
    <td>ID</td>
    <td>NAME</td>
    <td>ADDRESS</td>
    <td>SSN</td>
    <td>AGE</td>
    <td>GENDER</td>
  </tr>

<?php

$result = mysqli_query($connection, "SELECT * FROM SURVIVORS");

while($query_data = mysqli_fetch_row($result)) {
  echo "<tr>";
  echo "<td>",$query_data[0], "</td>",
       "<td>",$query_data[1], "</td>",
       "<td>",$query_data[2], "</td>",
       "<td>",$query_data[3], "</td>",
       "<td>",$query_data[4], "</td>",
       "<td>",$query_data[5], "</td>";
  echo "</tr>";
}
?>

</table>

<!-- Clean up. -->
<?php

  mysqli_free_result($result);
  mysqli_close($connection);

?>

</body>
</html>


<?php

/* Add an employee to the table. */
function AddSurvivor($connection, $survivor_name, $survivor_address, $survivor_ssn, $survivor_age, $survivor_gender) {
   $n = mysqli_real_escape_string($connection, $survivor_name);
   $a = mysqli_real_escape_string($connection, $survivor_address);
   $s = mysqli_real_escape_string($connection, $survivor_ssn);
   $ag = mysqli_real_escape_string($connection, $survivor_age);
   $g = mysqli_real_escape_string($connection, $survivor_gender);
   $query = "INSERT INTO SURVIVORS (NAME, ADDRESS, SSN, AGE, GENDER) VALUES ('$n', '$a', '$s', '$ag', '$g');";

   if(!mysqli_query($connection, $query)) echo("<p>Error adding survivor data.</p>");
}

/* Check whether the table exists and, if not, create it. */
function VerifySurvivorsTable($connection, $dbName) {
  if(!TableExists("SURVIVORS", $connection, $dbName))
  {
     $query = "CREATE TABLE SURVIVORS(
         ID int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
         NAME VARCHAR(45),
         ADDRESS VARCHAR(90),
         SSN VARCHAR(12),
         AGE VARCHAR(3),
         GENDER VARCHAR(4)
       )";

     if(!mysqli_query($connection, $query)) echo("<p>Error creating table.</p>");
  }
}

/* Check for the existence of a table. */
function TableExists($tableName, $connection, $dbName) {
  $t = mysqli_real_escape_string($connection, $tableName);
  $d = mysqli_real_escape_string($connection, $dbName);

  $checktable = mysqli_query($connection,
      "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");

  if(mysqli_num_rows($checktable) > 0) return true;

  return false;
}
?>     