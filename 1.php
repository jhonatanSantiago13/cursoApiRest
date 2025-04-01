<?php 

echo file_get_contents('http://xkcd.com/info.0.json').PHP_EOL;

/*
?solo mostrar una propiedad del json que arrojo
*/

$json = file_get_contents('http://xkcd.com/info.0.json');
$data = json_decode($json, true);
echo $data['img'].PHP_EOL;

?>