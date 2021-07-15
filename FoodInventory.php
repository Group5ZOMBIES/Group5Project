<?php include "../inc/dbinfo.inc"; ?>
<html>
<body>
<h1>Food Inventory</h1>
<?php

  /* Connect to MySQL and select the database. */
  $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

  if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

  $database = mysqli_select_db($connection, DB_DATABASE);

  /* Ensure that the FOOD INVENTORY table exists. */
  VerifyFoodInventoryTable($connection, DB_DATABASE);

  /* If input fields are populated, add a row to the FOOD INVENTORY table. */
  $food_item = htmlentities($_POST['FOOD_ITEM']);
  $food_amount = htmlentities($_POST['FOOD_AMOUNT']);
  $food_location = htmlentities($_POST['FOOD_LOCATION']);

  if (strlen($food_item) || strlen($food_amount) || strlen($food_location)) {
    AddFoodInventory($connection, $food_item, $food_amount, $food_location);
  }
?>

<!-- Input form -->
<form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
  <table border="0">
    <tr>
      <td>ITEM</td>
      <td>AMOUNT</td>
      <td>LOCATION</td>
    </tr>
    <tr>
      <td>
        <input type="text" name="ITEM" maxlength="45" size="30" />
      </td>
      <td>
        <input type="text" name="AMOUNT" maxlength="45" size="30" />
      </td>
      <td>
        <input type="text" name="LOCATION" maxlength="90" size="60" />
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
    <td>ITEM</td>
    <td>AMOUNT</td>
    <td>LOCATION</td>
  </tr>

<?php

$result = mysqli_query($connection, "SELECT * FROM EMPLOYEES");

while($query_data = mysqli_fetch_row($result)) {
  echo "<tr>";
  echo "<td>",$query_data[0], "</td>",
       "<td>",$query_data[1], "</td>",
       "<td>",$query_data[2], "</td>";
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

/* Add the food inventory to the table. */
function AddFoodInventory($connection, $item, $amount, $location) {
   $i = mysqli_real_escape_string($connection, $item);
   $a = mysqli_real_escape_string($connection, $amount);
   $l = mysqli_real_escape_string($connection, $location);

   $query = "INSERT INTO EMPLOYEES (NAME, ADDRESS) VALUES ('$i', '$a', '$l');";

   if(!mysqli_query($connection, $query)) echo("<p>Error adding foood inventory data.</p>");
}

/* Check whether the table exists and, if not, create it. */
function VerifyFoodInventoryTable($connection, $dbName) {
  if(!TableExists("EMPLOYEES", $connection, $dbName))
  {
     $query = "CREATE TABLE FOOD INVENTORY (
         ID int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
         ITEM VARCHAR(45),
         AMOUNT VARCHAR(45),
         LOCATION VARCHAR(90)
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