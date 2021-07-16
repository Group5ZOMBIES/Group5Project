<?php include "../inc/dbinfo.inc"; ?>
<html>
<body>
<h1>Zombies</h1>
<?php

  /* Connect to MySQL and select the database. */
  $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

  if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

  $database = mysqli_select_db($connection, DB_DATABASE);

  /* Ensure that the Zombies table exists. */
  VerifyZombiesTable($connection, DB_DATABASE);

  /* If input fields are populated, add a row to the Zombies table. */
  $zombie_name = htmlentities($_POST['NAME']);
  $zombie_address = htmlentities($_POST['ADDRESS']);
  $zombie_datebitten = htmlentities($_POST['DATE_BITTEN']);
  $zombie_ssn = htmlentities($_POST['SSN']);
  $zombie_knownwhereabouts = htmlentities($_POST['KNOWN_WHEREABOUTS']);
  $zombie_age = htmlentities($_POST['AGE']);
  $zombie_gender = htmlentities($_POST['GENDER']);
  $zombie_symptoms = htmlentities($_POST['SYMPTOMS']);
  

  if (strlen($zombie_name) || strlen($zombie_address) || strlen($zombie_datebitten) || strlen($zombie_ssn) || strlen($zombie_knownwhereabouts)
  || strlen($zombie_age) || strlen($zombie_gender) || strlen($zombie_symptoms)) {
    AddZombie($connection, $zombie_name, $zombie_address, $zombie_datebitten, $zombie_ssn, $zombie_knownwhereabouts,$zombie_age, 
    $zombie_gender, $zombie_symptoms);
  }
?>

<!-- Input form -->
<form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
  <table border="0">
    <tr>
      <td>NAME</td>
      <td>ADDRESS</td>
      <td>DATE BITTEN</td>
      <td>SSN</td>
      <td>KNOWN WHEREABOUTS</td>
      <td>AGE</td>
      <td>GENDER</td>
      <td>SYMPTOMS</td>
    </tr>
    <tr>
      <td>
        <input type="text" name="NAME" maxlength="45" size="30" />
      </td>
      <td>
        <input type="text" name="ADDRESS" maxlength="90" size="60" />
      </td>
      <td>
        <input type="text" name="DATE BITTEN" maxlength="10" size="30" />
      </td>
      <td>
        <input type="text" name="SSN" maxlength="12" size="30" />
      </td>
      <td>
        <input type="text" name="KNOWN WHEREABOUTS" maxlength="200" size="60" />
      </td>
      <td>
        <input type="text" name="AGE" maxlength="3" size="30" />
      </td>
      <td>
        <input type="text" name="GENDER" maxlength="4" size="30" />
      </td>
      <td>
        <input type="text" name="SYMPTOMS" maxlength="200" size="30" />
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
    <td>DATE BITTEN</td>
    <td>SSN</td>
    <td>KNOWN WHEREABOUTS</td>
    <td>AGE</td>
    <td>GENDER</td>
    <td>SYMPTOMS</td>
  </tr>

<?php

$result = mysqli_query($connection, "SELECT * FROM ZOMBIES");

while($query_data = mysqli_fetch_row($result)) {
  echo "<tr>";
  echo "<td>",$query_data[0], "</td>", //this corresponds to the amount of columns in the previous table tag that we just added to
       "<td>",$query_data[1], "</td>",
       "<td>",$query_data[2], "</td>",
       "<td>",$query_data[3], "</td>",
       "<td>",$query_data[4], "</td>",
       "<td>",$query_data[5], "</td>",
       "<td>",$query_data[6], "</td>",
       "<td>",$query_data[7], "</td>",
       "<td>",$query_data[8], "</td>";
       
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
function AddZombie($connection, $zombie_name, $zombie_address, $zombie_datebitten, $zombie_ssn, $zombie_knownwhereabouts, $zombie_age, 
    $zombie_gender, $zombie_symptoms) {
   $n = mysqli_real_escape_string($connection, $zombie_name);
   $a = mysqli_real_escape_string($connection, $zombie_address);
   $db = mysqli_real_escape_string($connection, $zombie_datebitten);
   $ssn = mysqli_real_escape_string($connection, $zombie_ssn);
   $k = mysqli_real_escape_string($connection, $zombie_knownwhereabouts);
   $age = mysqli_real_escape_string($connection, $zombie_age);
   $g = mysqli_real_escape_string($connection, $zombie_gender);
   $s = mysqli_real_escape_string($connection, $zombie_symptoms);

   $query = "INSERT INTO ZOMBIES (NAME, ADDRESS, DATE_BITTEN, KNOWN_WHEREABOUTS, AGE, GENDER, SYMPTOMS) 
   VALUES ('$n', '$a', '$db', '$ssn', '$k', '$age', '$g', '$s');";

   if(!mysqli_query($connection, $query)) echo("<p>Error adding zombie data.</p>");
}

/* Check whether the table exists and, if not, create it. */
function VerifyZombiesTable($connection, $dbName) {
  if(!TableExists("ZOMBIES", $connection, $dbName))
  {
     $query = "CREATE TABLE ZOMBIES (
         ID int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
         NAME VARCHAR(45),
         ADDRESS VARCHAR(90),
         DATE_BITTEN VARCHAR(10),
         SSN VARCHAR(12),
         KNOWN_WHEREABOUTS VARCHAR(90),
         AGE VARCHAR(3),
         GENDER VARCHAR(4),
         SYMPTOMS VARCHAR(200)
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