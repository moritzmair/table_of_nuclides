<?php
 require ("autoload.inc.php");
?>

<!DOCTYPE html>
<html>
  <head>
    <META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
    <title>Element Anlegen</title>
    <script type="text/javascript" src="jquery.min.js"></script>
    <script>
    function save() {
      var getpar = '';

      $( 'input[type="text"]' ).each(function( index ) {
        if (isNaN($(this).val()) || ($(this).val()) == '') {
          if ($(this).attr('id') == 'symbol' || $(this).attr('id') == 'name') {
            dim = ($(this).val());
          } else {
            dim = 0;
          }
        } else {
          dim = ($(this).val());
        }
        getpar = getpar + ( $(this).attr('id') + "=" + dim ) + "&";
      });
      getpar = getpar.substring(0,getpar.length-1);
  
      $.ajax({
        type: "GET",
        url: "save.php",
        data: getpar,
        dataType: 'json',
        success: function(response){
          console.log(response);
          if(response == ""){
            $('#save_button').val("Create");
          }else{
            $('#save_button').val("Save");
          }

          var ordnungszahl = $('#ordnungszahl').val();
          var symbol = $('#symbol').val();
          var name = $('#name').val();
          var massenzahl = $('#massenzahl').val();


          $( 'input[type="text"]' ).each(function( index ) {
            $(this).val('');
          });

          $('#ordnungszahl').val(ordnungszahl);
          $('#symbol').val(symbol);
          $('#name').val(name);
          $('#massenzahl').val(parseInt(massenzahl)+1);

          if(response != ""){
            $.each(response, function(index, value) {
              if(value != '0' && value != ''){
                $('#'+index).val(value);
              }
            });
          }
        }
      });
    }
    </script>
    <style>
    .mainBox {
      border-radius: 4px;
      padding:15px;
      background:gray;
      margin:auto;
      width:380px;
      height:650px;
    }
    .input {
      width:170px;
    }
    </style>
  </head>
  <body>
    <div class="mainBox" >
     <form>
      <?php

      $query = SPDO::prepare("SELECT * FROM nuklide WHERE ordnungszahl = ? AND massenzahl = ? ")->execute(array($_GET['o'],$_GET['m']));
      $row = $query->fetch();
      foreach ($row as $key => $value) {
        if($value === '0'){
          $row[$key] = "";
        }
      }

      ?>

      <input class="input" value="<?=$row['ordnungszahl'] ?>" type="text" id="ordnungszahl" placeholder="Ordnungszahl" ></input><br><br>
      <input class="input" value="<?=$row['symbol'] ?>" type="text" id="symbol" placeholder="Symbol" ></input><input class="input" <?php if($row['name'] != "") echo 'value="'.$row['name'].'"'; ?> type="text" id="name" placeholder="Name" ></input><br><br>

      Nuklid: <br>
          <input class="input" value="<?=$row['massenzahl'] ?>" type="text" id="massenzahl" placeholder="Massenzahl" ></input><br>
          <input class="input" value="<?=$row['half_life_time'] ?>" type="text" id="half_life_time" placeholder="Halbwertszeit" ></input><br>
      <input class="input" value="<?=$row['alpha'] ?>" type="text" id="alpha" placeholder="Alpha Anteil" ></input><input class="input" value="<?=$row['alpha_energy'] ?>" id="alpha_energy" type="text" placeholder="Alpha Energie" ></input><br>
      <input class="input" value="<?=$row['beta_minus'] ?>" type="text" id="beta_minus" placeholder="Beta- Anteil" ></input><input class="input" value="<?=$row['beta_minus_energy'] ?>" id="beta_minus_energy" type="text" placeholder="Beta- Energie" ></input><br>
      <input class="input" value="<?=$row['beta_plus'] ?>" type="text" id="beta_plus" placeholder="Beta+ Anteil" ></input><input class="input" value="<?=$row['beta_plus_energy'] ?>" id="beta_plus_energy" type="text" placeholder="Beta+ Energie" ></input><br>
      <input class="input" value="<?=$row['neutron_emission'] ?>" type="text" id="neutron_emission" placeholder="Neutronen-Emission" ></input><input class="input" value="<?=$row['neutron_emission_energy'] ?>" id="neutron_emission_energy" type="text" placeholder="Neutron-Emission Energie" ></input><br>
      <input class="input" value="<?=$row['double_neutron_emission'] ?>" type="text" id="double_neutron_emission" placeholder="2 Neutronen-Emission" ></input><input class="input" value="<?=$row['double_neutron_emission_energy'] ?>" id="double_neutron_emission_energy" type="text" placeholder="2 Neutron-Emission Energie" ></input><br>
      <input class="input" value="<?=$row['beta_minus_neutron'] ?>" type="text" id="beta_minus_neutron" placeholder="Beta- Neutron" ></input><input class="input" value="<?=$row['beta_minus_neutron_energy'] ?>" id="beta_minus_neutron_energy" type="text" placeholder="Beta-Neutron Energie" ></input><br>
      <input class="input" value="<?=$row['beta_minus_double_neutron'] ?>" type="text" id="beta_minus_double_neutron" placeholder="Beta- 2 Neutronen" ></input><input class="input" value="<?=$row['beta_minus_double_neutron_energy'] ?>" id="beta_minus_double_neutron_energy" type="text" placeholder="Beta- 2 Neutronen Energie" ></input><br>
      <input class="input" value="<?=$row['double_beta_minus'] ?>" type="text" id="double_beta_minus" placeholder="Doppel Beta Minus" ></input><input class="input" value="<?=$row['double_beta_minus_energy'] ?>" id="double_beta_minus_energy" type="text" placeholder="Doppel Beta Minus Energie" ></input><br>
      <input class="input" value="<?=$row['double_beta_plus'] ?>" type="text" id="double_beta_plus" placeholder="Doppel Beta Plus" ></input><input class="input" value="<?=$row['double_beta_plus_energy'] ?>" id="double_beta_plus_energy" type="text" placeholder="Doppel Beta Plus Energie" ></input><br>
      <input class="input" value="<?=$row['proton'] ?>" type="text" id="proton" placeholder="Proton-Emission" ></input><input class="input" value="<?=$row['proton_energy'] ?>" id="proton_energy" type="text" placeholder="Proton-Emission Energie" ></input><br>
      <input class="input" value="<?=$row['double_proton_emission'] ?>" type="text" id="double_proton_emission" placeholder="2 Protonen-Emission" ></input><input class="input" value="<?=$row['double_proton_emission_energy'] ?>" id="double_proton_emission_energy" type="text" placeholder="2 Protonen-Emission Energie" ></input><br>
      <input class="input" value="<?=$row['elektroneneinfang'] ?>" type="text" id="elektroneneinfang" placeholder="Elektroneneinfang" ></input><input class="input" value="<?=$row['elektroneneinfang_energy'] ?>" id="elektroneneinfang_energy" type="text" placeholder="Elektroneinfang Energie" ></input><br>
      <input value="<?=$row['double_elektroneneinfang'] ?>" type="text" id="double_elektroneneinfang" placeholder="2 Elektroneneinfang" ></input><input value="<?=$row['double_elektroneneinfang_energy'] ?>" id="double_elektroneneinfang_energy" type="text" placeholder="2 Elektroneinfang Energie" ></input><br>
      <input value="<?=$row['beta_plus_proton'] ?>" type="text" id="beta_plus_proton" placeholder="Beta+ Proton" ></input><input value="<?=$row['beta_plus_proton_energy'] ?>" id="beta_plus_proton_energy" type="text" placeholder="Beta+ Proton Energie" ></input><br>
      <input value="<?=$row['beta_plus_double_proton'] ?>" type="text" id="beta_plus_double_proton" placeholder="Beta+ 2 Protonen" ></input><input value="<?=$row['beta_plus_double_proton_energy'] ?>" id="beta_plus_double_proton_energy" type="text" placeholder="Beta+ 2 Protonen Energie" ></input><br>
      <input value="<?=$row['beta_plus_tripple_proton'] ?>" type="text" id="beta_plus_tripple_proton" placeholder="Beta+ 3 Protonen" ></input><input value="<?=$row['beta_plus_tripple_proton_energy'] ?>" id="beta_plus_tripple_proton_energy" type="text" placeholder="Beta+ 3 Protonen Energie" ></input><br>
      <input value="<?=$row['beta_plus_alpha'] ?>" type="text" id="beta_plus_alpha" placeholder="Beta+ Alpha" ></input><input value="<?=$row['beta_plus_alpha_energy'] ?>" id="beta_plus_alpha_energy" type="text" placeholder="Beta+ Alpha Energie" ></input><br>
      <input value="<?=$row['beta_minus_alpha'] ?>" type="text" id="beta_minus_alpha" placeholder="Beta- Alpha" ></input><input value="<?=$row['beta_minus_alpha_energy'] ?>" id="beta_minus_alpha_energy" type="text" placeholder="Beta- Alpha Energie" ></input><br>
      <input value="<?=$row['it'] ?>" type="text" id="it" placeholder="Isomeric transition" ></input><input value="<?=$row['it_energy'] ?>" type="text" id="it_energy" placeholder="Isomeric transition energy" ></input><br>
      <input value="<?=$row['cluster_decay'] ?>" type="text" id="cluster_decay" placeholder="Cluster Decay" ></input><input value="<?=$row['cluster_decay_energy'] ?>" type="text" id="cluster_decay_energy" placeholder="Cluster Decay energy" ></input><br>

      <input class="input" value="<?=$row['sf'] ?>" type="text" id="sf" placeholder="Spontane Spaltung" ></input><br>
      <input class="input" value="<?=$row['beta_plus_sf'] ?>" type="text" id="beta_plus_sf" placeholder="Beta+ Spontane Spaltung" ></input><br>
      <input class="input" value="<?=$row['stable'] ?>" type="text" id="stable" placeholder="Stabil?" ></input><br><br>
      <input type="submit" id="save_button" value="Save" onClick="save();return false;" >
     </form>
    </div>

  </body>
</html>
