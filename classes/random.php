<?php

  function randomstring($laenge=16)
  {
	  //Zeichen, die im Zufallsstring vorkommen sollen
	  $zeichen = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

	  $zufalls_string = '';
	  $anzahl_zeichen = strlen($zeichen);
	  for($i=0;$i<$laenge;$i++)
	  {
		  $zufalls_string .= $zeichen[mt_rand(0, $anzahl_zeichen - 1)];
	  }
	  return $zufalls_string;
  }

?>
