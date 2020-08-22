<?php
header('Access-Control-Allow-Origin: *');
header("content-type: text/xml");

echo "<?xml version=\"1.0\"?>\n";

// read the xml file into a dom structure
$xml = new DOMDocument();
$xml->preserveWhiteSpace = false; // remove whitespace nodes 
$xml->load("pokemon.xml");

// retrieve the get request values
$number     = $_GET["nationalNumber"];
$name       = $_GET["pokemonName"];
$image      = $_GET["imageAddress"];
$types      = $_GET["pokemonType"];
$generation = $_GET["generation"];

function validateFields() {
    global $xml, $number, $name;

    // check if the number has been taken
    $pokemons = $xml->getElementsByTagName("pokemon");
    foreach ($pokemons as $node) {
        if (intval($node->getAttribute("num")) == intval($number)) {
            return "National number already exists!";
        }
    }

    // check if the name has been taken
    $names = $xml->getElementsByTagName("name");
    foreach ($names as $node) {
        if ($node->nodeValue == trim($name)) {
            return "Name already exists!";
        }
    }

    return null;
}

// validate the two fields: national number and name
$error = validateFields();

// show the error or add the new pokemon
if ($error != null) {
    // Show the error
    echo "<error>" . $error . "</error>";
}
else {
    // get the correct generation
    $target = null;
    $generations = $xml->getElementsByTagName("generation");
    foreach ($generations as $node) {
        if ($node->getAttribute("num") == $generation) {
            $target = $node;
            break;
        }
    }

    // add the new pokemon
    $pokemon = $xml->createDocumentFragment();
    $typeNode = "<types>";
    foreach ($types as $type) $typeNode .= "<type>$type</type>";
    $typeNode .= "</types>";
    $pokemon->appendXML("<pokemon num=\"$number\"><name>$name</name><image>$image</image>$typeNode</pokemon>");
    $target->appendChild($pokemon);

    $xml->save("pokemon.xml");

    // show success
    echo "<success/>";
}
?>
