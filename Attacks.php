<?php include "../inc/dbinfo.inc"; ?>
<html>
<body>
<h1>Attacks</h1>
<?php

  /* Connect to MySQL and select the database. */
  $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

  if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

  $database = mysqli_select_db($connection, DB_DATABASE);

  /* Ensure that the Attacks table exists. */
  VerifyAttacksTable($connection, DB_DATABASE);

  /* If input fields are populated, add a row to the Attacks table. */
  $attacks_date = htmlentities($_POST['DATE']);
  $attacks_location = htmlentities($_POST['ADDRESS']);
  $attacks_time = htmlentities($_POST['TIME']);
  $attacks_amount = htmlentities($_POST['AMOUNT']);
  $attacks_whathappened = htmlentities($_POST['INCIDENT']);

 if (strlen($attacks_date) || strlen($attacks_location) || strlen($attacks_time) || strlen($attacks_amount) || strlen($attacks_whathappened)) {
    AddAttacks($connection, $attacks_date, $attacks_location, $attacks_time, $attacks_amount, $attacks_whathappened);
  }
?>

<!-- Input form -->
<form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
  <table border="0">
    <tr>
      <td>DATE</td>
      <td>ADDRESS</td>
      <td>TIME</td>
      <td>AMOUNT</td>
      <td>INCIDENT</td>
    </tr>
    <tr>
      <td>
        <input type="text" name="DATE" maxlength="10" size="30" />
      </td>
      <td>
        <input type="text" name="ADDRESS" maxlength="90" size="60" />
      </td>
      <td>
        <input type="text" name="TIME" maxlength="5" size="60" />
      </td>
      <td>
        <input type="text" name="AMOUNT" maxlength="10" size="60" />
      </td>
      <td>
        <input type="text" name="INCIDENT" maxlength="90" size="60" />
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
    <td>DATE</td>
    <td>ADDRESS</td>
    <td>TIME</td>
    <td>AMOUNT</td>
    <td>INCIDENT</td>
  </tr>

<?php

$result = mysqli_query($connection, "SELECT * FROM ATTACKS");

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
function AddAttacks($connection, $attacks_date, $attacks_location, $attacks_time, $attacks_amount, $attacks_whathappened) {
   $d = mysqli_real_escape_string($connection, $attacks_date);
   $a = mysqli_real_escape_string($connection, $attacks_location);
   $t = mysqli_real_escape_string($connection, $attacks_time);
   $amo = mysqli_real_escape_string($connection, $attacks_amount);
   $i = mysqli_real_escape_string($connection, $attacks_whathappened);

   $query = "INSERT INTO ATTACKS (DATE, ADDRESS, TIME, AMOUNT, INCIDENT)
   VALUES ('$d', '$a', '$t', '$amo', '$i');";

   if(!mysqli_query($connection, $query)) echo("<p>Error adding attacks data.</p>");
}

/* Check whether the table exists and, if not, create it. */
function VerifyAttacksTable($connection, $dbName) {
  if(!TableExists("ATTACKS", $connection, $dbName))
  {
     $query = "CREATE TABLE ATTACKS (
         ID int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
         DATE VARCHAR(10),
         ADDRESS VARCHAR(90)
         TIME VARCHAR(5)
         AMOUNT VARCHAR(10)
         INCIDENT VARCHAR(90)
       )";

     if(!mysqli_query($connection, $query)) echo("<p>Error creating table.</p>");
  }
}

/* Check for the existence of a table. */
function TableExists($tableName, $connection, $dbName) {
  $t = mysqli_real_escape_string($connection, $tableName);
  $d = mysqli_real_escape_string($connection, $dbName);

  $checktable = mysqli_query($connection,
      "SELECT ATTACKS FROM sample.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");

  if(mysqli_num_rows($checktable) > 0) return true;

  return false;
}
?>                    