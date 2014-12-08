<?php
if(!function_exists('autoload')) {
	/**
	 * Classloader zum automatischen laden von PHP-Klassen
	 * 
	 * @param string $c Klassenname
	 */
	function autoload($c) {
		$path = dirname(__FILE__).'/classes/'.$c.'.php';
		//teste, ob Klassen-Datei existiert und lesbar ist
		if(file_exists($path) && is_readable($path)) {
			//Klassen-Datei laden
			include $path;
		}
	}
	//Autoload-Funktion beim PHP-Laufzeitsystem registrieren
	spl_autoload_register('autoload');
}
