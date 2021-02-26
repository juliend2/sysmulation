<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'config.php';
include_once 'src/Time.php';
include_once 'src/Event.php';

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <script src="https://code.highcharts.com/highcharts.js"></script>

</head>
<body>
<?php

$now = Time::fromString('now');

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
  $days_to_generate = 0;
  $min_timestamp = Time::fromString('now')->timestamp();
  $max_timestamp = Time::fromString('now')->timestamp();
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
      $moment = Time::fromString($event['moment']);
      $evt = new Event($moment, $flow['regularity'], $repetitions);
      $moment_timestamp = strtotime($event['moment']);
      echo "&nbsp;&nbsp;> ". $event['stock_change'] . " since ". $event['moment'] ." (".strtotime($event['moment']).") <br>";
      echo "&nbsp;&nbsp;&nbsp;&nbsp;". $flow['regularity'] . "<br>";

      if ($evt->minTimestamp() < $min_timestamp) {
        $min_timestamp = $evt->minTimestamp();
      }
      if ($evt->maxTimestamp() > $max_timestamp) {
        $max_timestamp = $evt->maxTimestamp();
      }
      if ($now->daysSince(new Time($moment_timestamp)) > 0) {
        // moment is in the past
      } else {
        // moment is in the future
      }
    }

  }

  echo strftime('%Y-%m-%d', $min_timestamp). "<br>";
  echo strftime('%Y-%m-%d', $max_timestamp). "<br>";

}
?>

<script>
</script>
</body>
</html>
