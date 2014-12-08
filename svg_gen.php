<?php
  header('Content-type: image/svg+xml');
  header("Cache-Control: public");
  header("Content-Description: File Transfer");
  if(isset($_GET['print'])){
    header("Content-Disposition: attachment; filename=nuklidkarte_print.svg");
  }elseif(isset($_GET['split'])){
    header("Content-Disposition: attachment; filename=nuklidkarte_split.svg");
  }else{
    header("Content-Disposition: attachment; filename=nuklidkarte.svg");
  }
  header("Content-Type: application/octet-stream; "); 
  header("Content-Transfer-Encoding: binary");

  if(!isset($_GET['split'])) $_GET['split'] = "";

  $svg_height = 5000;
  $svg_width = 7500;

  if($_GET['split'] == 1){
    $svg_height = 2220;
    $svg_width = 3820;
  }

  $rotate = "";
  if(isset($_GET['print'])){
    $svg_height = 1500;
    $svg_width = 9000;
    $rotate = ' transform = "rotate(35 '.(($svg_width/2)+2150).' '.(($svg_height/2)+2150).')"';
  }

  require ("autoload.inc.php");

  $result = SPDO::prepare("SELECT * FROM nuklide ORDER BY id ASC LIMIT 5000")->execute(array())->fetchAll();

  $colors = array(
    "alpha" => "yellow",
    "proton" => "magenta",
    "neutron_emission" => "purple",
    "sf" => "green",
    "beta_plus_sf" => "#8f8",
    "beta_plus" => "orange",
    "beta_plus_double_proton" => "red",
    "beta_plus_proton" => "#f44",
    "elektroneneinfang" => "#700000",
    "double_elektroneneinfang" => "#B00000",
    "beta_minus" => "#44f",
    "beta_minus_neutron" => "#00CCCC",
    "double_beta_minus" => "blue",
    "double_beta_plus" => "#88f",
    "stable" => "black",
    "beta_plus_alpha" => "lime",
    "double_neutron_emission" => "#C5908E",
    "double_proton_emission" => "#FF6A51",
    "beta_minus_alpha" => "grey",
    "it" => "silver",
    "beta_minus_double_neutron" => "cyan",
    "beta_plus_tripple_proton" => "#f7f",
    "cluster_decay" => "#aaf"
  );

  $svg  = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>'."\n";
  $svg .= '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.0//EN" "http://www.w3.org/TR/2001/REC-SVG-20010904/DTD/svg10.dtd">'."\n";
  $svg .= '<svg width="'.$svg_width.'" height="'.$svg_height.'">'."\n";

  $svg .= '<defs>'."\n";
  foreach ($result as $key => $row) {
    $svg .= '<linearGradient id="grad_'.$row['id'].'" x1="0%" y1="0%" x2="100%" y2="100%">'."\n";

    $color = 'white';
    $anteil = 0;
    $anteil2 = 0;
    $neuer_anteil = 0;
    $over_50 = false;
    $bg_zusatz = "";
    $not_sure_counter = 0 ;
    $all_others = 0;
    $any_bg = false;

    foreach ($colors as $key => $value) {
      if($row[$key] > 100){
        $not_sure_counter++;
      }else{
        $all_others += $row[$key];
      }
    }

    if($not_sure_counter > 1){
      $anteil = 0-(100/($not_sure_counter-1));
    }

    foreach ($colors as $key => $value) {
      if($not_sure_counter == 1 and $row[$key] > 100){
        $row[$key] = 100-$all_others;
      }
      $old_percentage = $row[$key];
      if($row[$key] > 100 and $not_sure_counter > 1){
        $row[$key] = ((100-$all_others)/($not_sure_counter-1));
      }
      if($row[$key] > 0){
        if($old_percentage <= 100){
          $svg .= '  <stop offset="'.round($anteil,4).'%" style="stop-color:'.$value.';stop-opacity:1" />'."\n";
          $any_bg = true;
        }
        $anteil += $row[$key];
        $svg .= '  <stop offset="'.round($anteil,4).'%" style="stop-color:'.$value.';stop-opacity:1" />'."\n";
        $any_bg = true;
      }
    }
    if($any_bg == false){
      $svg .= '  <stop offset="0%" style="stop-color:white;stop-opacity:1" />'."\n";
      $svg .= '  <stop offset="100%" style="stop-color:white;stop-opacity:1" />'."\n";
    }
    $svg .= '</linearGradient>'."\n";
  }
  $svg .= '</defs>'."\n";

  foreach ($result as $key => $row) {
    if($_GET['split'] == 1){
      if($row['ordnungszahl'] < 23){
        $top = ($row['ordnungszahl']*-38)+1000-100;
        $left = (($row['massenzahl']-$row['ordnungszahl'])*38)+50;
      }
      elseif($row['ordnungszahl'] < 60) {
        $top = ($row['ordnungszahl']*-38)+2408-100;
        $left = (($row['massenzahl']-$row['ordnungszahl'])*38)-500;
      }
      elseif($row['ordnungszahl'] < 94) {
        $top = ($row['ordnungszahl']*-38)+4500-100;
        $left = (($row['massenzahl']-$row['ordnungszahl'])*38)-2000;
      }
      else {
        $top = ($row['ordnungszahl']*-38)+5796-100;
        $left = (($row['massenzahl']-$row['ordnungszahl'])*38)-3050;
      }
    }
    else{
      $top = ($row['ordnungszahl']*-42)+5000;
      $left = (($row['massenzahl']-$row['ordnungszahl'])*42)+5;
    }
    $svg .= '<rect'.$rotate.' x="'.$left.'" y="'.$top.'" height="40" width="40" style="fill:url(#grad_'.$row['id'].');stroke-width:1;stroke:rgb(0,0,0)"  />'."\n";
    $svg .= '<text'.$rotate.' font-weight="bold" font-family="Helvetica, Arial, Verdana" style="stroke-width:.6;stroke:rgb(0,0,0);" font-size="16" x="'.($left+3).'" y="'.($top+15).'" fill="white">'.$row['symbol'].'</text>'."\n";
    $svg .= '<text'.$rotate.' font-weight="bold" font-family="Helvetica, Arial, Verdana" style="stroke-width:.4;stroke:rgb(0,0,0);" font-size="11" x="'.($left+3).'" y="'.($top+26).'" fill="white">'.$row['massenzahl'].'</text>'."\n";
    $svg .= '<text'.$rotate.' font-weight="bold" font-family="Helvetica, Arial, Verdana" style="stroke-width:.4;stroke:rgb(0,0,0);" font-size="11" x="'.($left+3).'" y="'.($top+36).'" fill="white">'.$row['ordnungszahl'].'</text>'."\n";
  }

  $svg .= '<text'.$rotate.' font-family="Helvetica, Arial, Verdana" font-size="10" x="320" y="4983" fill="black">2014 by Swannekke.de ©</text>';

  $svg .= '</svg>'."\n";

  echo $svg;

?>
