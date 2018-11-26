<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "support/web_browser.php";
require_once "support/tag_filter.php";

// Retrieve the standard HTML parsing array for later use.
$htmloptions = TagFilter::GetHTMLOptions();

//Detalle	
$url = "https://www.fragrantica.es/perfume/Ralph-Lauren/Polo-Red-Extreme-43386.html";
$url = "https://www.fragrantica.es/perfume/Tom-Ford/Oud-Wood-1826.html";
$url = "https://www.fragrantica.com/perfume/Loewe/Solo-Loewe-Cedro-30321.html";


//busqueda global
//$url = "https://www.fragrantica.es/ajax.php?view=qsearch&q=carolina herrera&qgender=female%2Cmale%2Cunisex&searchtype=perfumes";
//genero femenino
//$url = "https://www.fragrantica.es/ajax.php?view=qsearch&q=carolina&qgender=female&searchtype=perfumes";
//genero masculino
//$url = "https://www.fragrantica.es/ajax.php?view=qsearch&q=carolina herrera&qgender=male&searchtype=perfumes";


$web = new WebBrowser();
$result = $web->Process($url);

// Check for connectivity and response errors.
if (!$result["success"])
{
	echo "Error retrieving URL.  " . $result["error"] . "\n";
	exit();
}

if ($result["response"]["code"] != 200)
{
	echo "Error retrieving URL.  Server returned:  " . $result["response"]["code"] . " " . $result["response"]["meaning"] . "\n";
	exit();
}

// Get the final URL after redirects.
$baseurl = $result["url"];

// Use TagFilter to parse the content.
$html = TagFilter::Explode($result["body"], $htmloptions);

// Retrieve a pointer object to the root node.
$root = $html->Get();

/*  $nombre = $html->nodes[556]['text'];
$descripcion = $html->nodes[15]['attrs']['content'];
$urlOrigen = $html->nodes[25]['attrs']['href'];
$img = $html->nodes[314]['attrs']['src']; */



$resultado = $html->Find('div[itemprop=description]');
$detalle = $resultado['ids'];
$descripcion = '';
foreach ($detalle as $value) {
	echo '<pre>';
	$parent = $html->nodes[$value]['children'][0];
	$children = $html->nodes[$parent]['children'];	 
var_dump($children);

	for ($i=0; $i < count($children) ; $i++) { 
		if($i == 0){
			if($html->nodes[$children[$i]]['children'][0]){
				$children = $html->nodes[$html->nodes[$children[$i]]['children'][0]];
				if($children){
					$idChildren = $children['children'][0];
					$nombre = $html->nodes[$idChildren]['text'];
					$descripcion .= $nombre;
				}
			}
		}
		
		if($i == 3){
			$children2 = $children['children'][0];
			var_dump($html->nodes[$children2]);
		}
		
		
	}
	
	
}

echo $descripcion;


//Búsqueda
/* $result2 = $html->Find("a");
$resultado = $result2['ids'];
foreach ($resultado as $value) {
	$url = $html->nodes[$value]['attrs']['href'];
	$idNombre = $html->nodes[$value]['children'][1];
	$idImg = $html->nodes[$value]['children'][0];
	$nombre = $html->nodes[$idNombre]['text'];
	echo $nombre.'<br>';
	echo '<img src='.str_replace('mm', 'm', $html->nodes[$idImg]['attrs']['src']).'>';
	
}  */


