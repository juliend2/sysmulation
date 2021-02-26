<?php

include 'config.php';

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <script src="https://code.highcharts.com/highcharts.js"></script>

</head>
<body>
<?php

try {
  $pdo = new PDO("mysql:host=".MYSQL_HOST.";dbname=".MYSQL_DBNAME, MYSQL_USER, MYSQL_PASS, [
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
  ]);
  //echo "Connected to $dbname at $host successfully.";
} catch (PDOException $pe) {
  die("Could not connect to the database $dbname :" . $pe->getMessage());
}

$stmt = $pdo->prepare("SELECT * FROM stocks");
$stmt->execute(); 
$stocks = $stmt->fetchAll();

// For each
foreach ($stocks as $stock) {
  echo $stock['name']."<br />\n";
  $stock_id = $stock['id'];
  $initial_value = $stock['initial_value'];
  $initial_time = $stock['initial_time'];

  $stmt = $pdo->prepare("SELECT * FROM flows WHERE stock_id = :stock_id");
  $stmt->execute(['stock_id' => $stock_id]);
  $flows = $stmt->fetchAll();

  foreach ($flows as $flow) {
    echo "&nbsp;";
    echo "&nbsp;";
    $flow_id = $flow['id'];
    echo $flow['name'] . "<br>";

    $stmt = $pdo->prepare("SELECT * FROM flow_events WHERE flow_id = :flow_id");
    $stmt->execute(['flow_id' => $flow_id]);
    $flow_events = $stmt->fetchAll();

    foreach ($flow_events as $event) {
      $repetitions = $event['repetitions'];
      $stock_change = $event['stock_change']; // quantity
      $moment = $event['moment'];
      $moment_timestamp = strtotime($event['moment']);
      echo "&nbsp;&nbsp;> ". $event['stock_change'] . " since ". $event['moment'] ." (".strtotime($event['moment']).") <br>";
    }

  }

}
?>

<script>
</script>
</body>
</html>
