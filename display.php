<?php

if(!isset($_GET['print'])) $_GET['print'] = "";
if(!isset($_GET['split'])) $_GET['split'] = "";

?>

<!DOCTYPE html>
<html>
  <head>
    <META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
    <title>Nuklidkarte</title>
    <script type="text/javascript" src="jquery.min.js"></script>

    <style>
    html {
      position: relative;
      margin:0;
    }
    body {
      min-width: 100%;
      min-height: 100%;
      margin:0;
      font-family: Helvetica, Arial, Verdana;
    }
    .nuklid {
      position:absolute;
      border:1px solid black;
      height:35px;
      width:35px;
      margin:2px;
      color:white;
      text-shadow:0 0 10px black,0 0 5px black,0 0 15px black;
      font-size: 8px;
    }
    .config_box {
      position:absolute;
      width:450px;
      height:800px;
      margin: -260px -225px;
      left: 50%;
      top:30%;
      display:none;
      background-color: grey;
    }

    .config_box iframe {
      width:100%;
      height:100%;
    }

    .main_box {
      position: relative;
      height: 100%;
      width: 100%;
      -webkit-touch-callout: none;
      -webkit-user-select: none;
      -khtml-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
    }
    a {
      text-decoration: none;
      color:white;
    }
    a:hover {
      text-decoration: underline;
    }
    </style>
  </head>
  <body<?php
    if($_GET['print'] == 1){
      echo ' style="-webkit-transform: rotate(35deg);"';
    }
  ?>
  >
    <div class="main_box">

    <?php
      require ("autoload.inc.php");

      $query = SPDO::prepare("SELECT * FROM nuklide ORDER BY id ASC ")->execute(array());

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

      $translation = array(
        "alpha" => "Alpha",
        "proton" => "Protonenzerfall",
        "neutron_emission" => "Neutronenemission",
        "sf" => "Spontane Spaltung",
        "beta_plus_sf" => "Beta+, Spontane Spaltung",
        "beta_plus" => "Beta+",
        "beta_plus_double_proton" => "Beta+, doppelte Protonenemission",
        "beta_plus_proton" => "Beta+, Protonenemission",
        "elektroneneinfang" => "Elektroneneinfang",
        "double_elektroneneinfang" => "doppelter Elektroneneinfang",
        "beta_minus" => "Beta-",
        "beta_minus_neutron" => "Beta-, Neutronenemission",
        "double_beta_minus" => "doppel Beta-",
        "double_beta_plus" => "doppel Beta+",
        "stable" => "Stabil",
        "beta_plus_alpha" => "Beta+, Alpha",
        "double_neutron_emission" => "doppelte Neutronenemission",
        "double_proton_emission" => "doppelte Protonenemission",
        "beta_minus_alpha" => "Beta-, Alpha",
        "it" => "Isomerer Übergang",
        "beta_minus_double_neutron" => "Beta-, doppelte Neutronenemission",
        "beta_plus_tripple_proton" => "Beta+, dreifache Protonenemission",
        "cluster_decay" => "Clusterzerfall"
      );

      $counter = 0;

      while ($row = $query->fetch()) {
        $color = 'white';
        $anteil = 0;
        $anteil2 = 0;
        $neuer_anteil = 0;
        $over_50 = false;
        $bg_zusatz = "";


        $bg = ' linear-gradient(135deg ';

        $not_sure_counter = 0 ;
        $all_others = 0;
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
              $bg .= ','.$value.' '.round($anteil,4). '%';
            }
            $anteil += $row[$key];
            /*
            $neuer_anteil2 = 0;
            if(!$over_50){
              $neuer_anteil = sqrt(($anteil*$anteil)+(50*$row[$key]))-$anteil;
            }
            if($anteil+$neuer_anteil > 50 and !$over_50){
              $row[$key] = (pow(($anteil+$neuer_anteil),2))/50-50;
              $neuer_anteil = 50;
              $anteil = 0;
              $over_50 = true;

              $neuer_anteil2 = sqrt(50*$row[$key]);
              if($neuer_anteil2 < 0) echo sqrt(50*$row[$key])."<br>";
            }
            elseif($over_50){
              $anteil_neu = $anteil-50;
              $neuer_anteil2 = sqrt(($anteil_neu*$anteil_neu)+(2500-(50*(50-$row[$key]))))-$anteil_neu-50;
            }
            $anteil = $neuer_anteil + $anteil + $neuer_anteil2;
            */
            
            $bg .= ','.$value.' '.round($anteil,4).'%';
            
          }
          if($old_percentage == "111" and $not_sure_counter == 1){
            $bg_zusatz = ' url("not_sure.png"), ';
          }
        }


        $bg .= ',white 0%';
        $bg .= ',white 100%';
        $bg .= ')';
        
        if($_GET['split'] == 1){
          if($row['ordnungszahl'] < 23){
            $top = ($row['ordnungszahl']*-38)+1000;
            $left = (($row['massenzahl']-$row['ordnungszahl'])*38)+50;
          }
          elseif($row['ordnungszahl'] < 60) {
            $top = ($row['ordnungszahl']*-38)+2408;
            $left = (($row['massenzahl']-$row['ordnungszahl'])*38)-500;
          }
          elseif($row['ordnungszahl'] < 94) {
            $top = ($row['ordnungszahl']*-38)+4500;
            $left = (($row['massenzahl']-$row['ordnungszahl'])*38)-2000;
          }
          else {
            $top = ($row['ordnungszahl']*-38)+5796;
            $left = (($row['massenzahl']-$row['ordnungszahl'])*38)-3050;
          }
        }
        elseif($_GET['print'] != 1){
          $top = ($row['ordnungszahl']*-38)+4550;
          $left = (($row['massenzahl']-$row['ordnungszahl'])*38)+5;
        }
        elseif($_GET['print'] == 1){
          $top = ($row['ordnungszahl']*-38)+1000;
          $left = (($row['massenzahl']-$row['ordnungszahl'])*38)+800;
        }
        

        


        echo "<div onclick='display_config(".$row['ordnungszahl'].",".$row['massenzahl'].")' style='top:".$top."px;left:".$left."px;background-image:".$bg_zusatz.$bg.";' class='nuklid' >
                <div style='position:absolute;left:3px;'>".$row['massenzahl']."<br>".$row['ordnungszahl']."</div>
                <div style='position:absolute;right:2px;font-size:12px;' ><a target='_blank' href='http://en.wikipedia.org/wiki/Isotopes_of_".strtolower($row['name'])."#Table'>".$row['symbol']."</a></div>
              </div>\n\n";
      }
      $key_counter = 0;
      foreach ($colors as $key => $value) {
        echo '<div style="top:'.(($key_counter*40)+3000).'px;left:20px;background:'.$value.';" class="nuklid">
                <div style="position:relative;left:40px;top:8px;color:black;font-size:16px;text-shadow:none;width:300px;">'.$translation[$key].'</div>
              </div>'."\n\n";
        $key_counter++;
      }
      

      if($_GET['print'] != 1){
        echo '<img src="zerfall.png" style="position:absolute;top:4300px;left:730px;">';
      }
    ?>


    </div>
    <div class="config_box">
      <iframe frameborder="0" border="0" src="index.php"></iframe>
      <a style="display:block;width:98%;padding:1%;background-color:red;color:black;" href="" onclick="$('.config_box').hide();return false;">Schließen</a>
    </div>
    <script type="text/javascript">
      function display_config(ordnungszahl, massenzahl){
        $('.config_box').show();
        $('.config_box').css('top',$(window).scrollTop()+300).css('left',$(window).scrollLeft()+500);
        $('.config_box iframe').attr('src','index.php?m='+massenzahl+'&o='+ordnungszahl);
      }
      $(document).mouseup(function (e)
      {
          var container = $('.config_box');

          if (!container.is(e.target) // if the target of the click isn't the container...
              && container.has(e.target).length === 0) // ... nor a descendant of the container
          {
            container.hide();
          }
      });

      $(window).load(function(){
        //next two: fix chrome bug (was not showing page)
        $('body').scrollTop(1);
        $('body').scrollTop(4000);
        var clicked = false, clickY, clickX;
        $(window).on({
            'mousemove': function(e) {
                clicked && updateScrollPos(e);
            },
            'mousedown': function(e) {
                clicked = true;
                clickY = e.pageY;
                clickX = e.pageX;
            },
            'mouseup': function() {
                clicked = false;
                $('html').css('cursor', 'auto');
            }
        });

        var updateScrollPos = function(e) {
            $('html').css('cursor', 'move');
            $(window).scrollTop($(window).scrollTop() + (clickY - e.pageY));
            $(window).scrollLeft($(window).scrollLeft() + (clickX - e.pageX));
        }
      });

    </script>
  </body>
</html>

<?php
  function delete_minus($number){
    if($number < 0){
      return 0;
    }else{
      return $number;
    }
  }
?>