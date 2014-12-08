<?php
  require 'autoload.inc.php';

  $query = SPDO::prepare("SELECT * FROM nuklide WHERE ordnungszahl = ? AND massenzahl = ? ")->execute(array($_GET['ordnungszahl'],$_GET['massenzahl']));
  $row = $query->fetch();

  $keys = array_keys($_GET);
  $values = array_values($_GET);

  if(!$row){
    $keys_csv = '';
    $placeholders_csv = '';
    foreach ($keys as $key) {
      $keys_csv .= $key . ",";
      $placeholders_csv .= "?,";
    }
    $keys_csv = substr($keys_csv, 0, -1);
    $placeholders_csv = substr($placeholders_csv, 0, -1);

    SPDO::prepare("INSERT INTO nuklide (".$keys_csv.") VALUES (".$placeholders_csv.")")->execute($values);
  }else{
    $mysql_input = "";
    foreach ($keys as $key) {
      $mysql_input .= $key.' = ?,';
    }
    $mysql_input = substr($mysql_input, 0, -1);

    array_push($values, $_GET['ordnungszahl'],$_GET['massenzahl']);
    
    SPDO::prepare("UPDATE nuklide SET ".$mysql_input." WHERE ordnungszahl = ? AND massenzahl = ? ")->execute($values);
  }

$query = SPDO::prepare("SELECT * FROM nuklide WHERE ordnungszahl = ? AND massenzahl = ? ")->execute(array($_GET['ordnungszahl'],$_GET['massenzahl']+1));
$row = $query->fetch(PDO::FETCH_ASSOC);

if($row){
  echo json_encode($row);
}else{
  echo json_encode("");
}
?>