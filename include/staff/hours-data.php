<?php
$db=1;
require('../ost-config.php');

$host = DBHOST;
$db = DBNAME;
$user = DBUSER;
$pass = DBPASS;
$connect = new PDO("mysql:host=$host;dbname=$db", "$user", "$pass");

$method = $_SERVER['REQUEST_METHOD'];

if($method == 'GET')
{
 $data = array(
  ':Location'   => "%" . $_GET['Location'] . "%",
  ':Hours'   => "%" . $_GET['Hours'] . "%",
  ':Month'     => "%" . $_GET['Month'] . "%",
  ':Year'    => "%" . $_GET['Year'] . "%"
 );
 $query = "SELECT * FROM ost_hours WHERE location LIKE :Location AND hours LIKE :Hours AND month LIKE :Month AND year LIKE :Year ORDER BY id DESC";

 $statement = $connect->prepare($query);
 $statement->execute($data);
 $result = $statement->fetchAll();
 foreach($result as $row)
 {
  $output[] = array(
   'id'    => $row['id'],   
   'Location'  => $row['location'],
   'Hours'   => $row['hours'],
   'Month'    => $row['month'],
   'Year'   => $row['year']
  );
 }
 header("Content-Type: application/json");
 echo json_encode($output);
}

if($method == "POST")
{
 $data = array(
  ':Location'  => $_POST['Location'],
  ':Hours'  => $_POST["Hours"],
  ':Month'    => $_POST["Month"],
  ':Year'   => $_POST["Year"]
 );

 $query = "INSERT INTO ost_hours (location, hours, month, year) VALUES (:Location, :Hours, :Month, :Year)";
 $statement = $connect->prepare($query);
 $statement->execute($data);

}

if($method == 'PUT')
{
 parse_str(file_get_contents("php://input"), $_PUT);
 $data = array(
  ':id'   => $_PUT['id'],
  ':Location' => $_PUT['Location'],
  ':Hours' => $_PUT['Hours'],
  ':Month'   => $_PUT['Month'],
  ':Year'  => $_PUT['Year']
 );
 $query = "
 UPDATE ost_hours 
 SET location = :Location, 
 hours = :Hours, 
 month = :Month, 
 year = :Year 
 WHERE id = :id
 ";
 $statement = $connect->prepare($query);
 $statement->execute($data);
}

if($method == "DELETE")
{
 parse_str(file_get_contents("php://input"), $_DELETE);
 $query = "DELETE FROM ost_hours WHERE id = '".$_DELETE["id"]."'";
 $statement = $connect->prepare($query);
 $statement->execute();
}

?>