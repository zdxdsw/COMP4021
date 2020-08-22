<?php
header('Access-Control-Allow-Origin: *');
//header('Content-Type: text/plain; charset=utf-8');
header("content-type: text/xml");

// read the xml file into a dom structure
$xml = new DOMDocument();
$xml->preserveWhiteSpace = false; // remove whitespace nodes 
$xml->load("pokemon.xml");

// retrieve the get request values
$generation = $_GET["generation"];
$type       = $_GET["type"];

// remove the non-matching generations
if ($generation != null) {
    $generations = $xml->getElementsByTagName("generation");
    for ($i = $generations->length - 1; $i >= 0; $i--) {
        $node = $generations->item($i);
        if ($node->getAttribute("num") != $generation) {
            $node->parentNode->removeChild($node);
        }
    }
}

// remove the non-matching types
if ($type != null) {
    $pokemon = $xml->getElementsByTagName("pokemon");
    for ($i = $pokemon->length - 1; $i >= 0; $i--) {
        $node = $pokemon->item($i);

        $found = false;
        $types = $node->getElementsByTagName("type");
        foreach ($types as $typeNode) {
            if ($typeNode->nodeValue == $type) $found = true;
        }
        if (!$found) {
            $node->parentNode->removeChild($node);
        }
    }
}

echo $xml->saveXML();
?>
