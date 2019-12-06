<?php
error_reporting(E_ALL);
date_default_timezone_set('Europe/Berlin');

if ( $_SERVER['SERVER_NAME'] == 'localhost' )
{
    // Offline
    $db = new mysqli('localhost', 'root', 'root', 'goatpong');
}
else
{
    // Online
    // $db = new mysqli('axel.mobi', 'abc5555we', 'kennwortgeheim', 'dbname');
    $db = new mysqli('localhost', 'root', 'root', 'goatpong');
}

$db->set_charset('utf8');

if ($db->connect_errno){
    die('Sorry - gerade gibt es ein Problem');
}

function anzeige_kopfbereich($bereich = "", $ajax = true) {
	echo '
  <div data-role="header" data-position="fixed">
  	<a ';

    if ( $ajax == false )
    {
      echo ' data-ajax="false" ';
    }

    echo 'href="index.php#startseite" class="ui-btn ui-icon-home ui-btn-icon-left ';

  	if ($bereich == 'startseite') {
  		echo ' ui-btn-active ui-state-persist';
  	}
  	echo '">Startseite</a>
    <h1>GOATHOUSEPONG</h1>
    <a ';

    if ( $ajax == false )
    {
      echo ' data-ajax="false" ';
    }

    echo ' href="index.php#Rangliste" class="ui-btn ui-icon-calendar ui-btn-icon-left ';

  	if ($bereich == 'Rangliste') {
  		echo ' ui-btn-active ui-state-persist';
  	}

    echo '">Rangliste</a>
  </div>
  ';
}


