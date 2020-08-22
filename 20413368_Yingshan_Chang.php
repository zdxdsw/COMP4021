<?php
// Read the XML file into a DOM structure
$xml = new DOMDocument();
$xml->preserveWhiteSpace = false; // remove whitespace nodes 
$xml->load("marvel_movies.xml");

// Read all the movie nodes
$movie_nodes = $xml->getElementsByTagName("movie");

// Retrieve the search term and orderby term
$search = $_GET["s"];
$orderby = $_GET["orderby"];

// Create the movies array
$movies = [];

// For each movie node, convert it to a PHP array
foreach ($movie_nodes as $node) { 
    $movie = [];
    $movie["search"] = false;
    foreach ($node->childNodes as $child) {
        if ($child->nodeName == "ratings") {
            $movie["ratings"] = [];
            foreach ($child->childNodes as $grandchild) {
                $movie["ratings"][$grandchild->nodeName] = $grandchild->nodeValue;
            }
        }
        else {
            $movie[$child->nodeName] = $child->nodeValue;

            // You need to look for the search term in every node value here
            if (stripos($child->nodeValue, $search)!=false) {
                // the search term does exists
                $movie["search"] = true;
            } 
        }
    }

    // Add the movie to the movies array
    // - You need to modify this code if you want to sort the movies
    // $orderby variable controls the order of movies
    if($search==NULL || $movie["search"]){
        if ($orderby == 'title') {
            $movies[$movie["title"]] = $movie;
            ksort($movies);
        }
        else if ($orderby == "box-office") {
            $movies[$movie["box-office"]] = $movie;
            krsort($movies); // sort in reverse order
        }
        else {
            $movies[] = $movie;
        }
    }
    
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Lab 4: Marvel Movies</title>
    <meta charset="utf-8">
    <meta name="viewport" 
          content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"> 
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script>
    $(document).ready(function() {
        // Construct the URL without the query string
        var url = window.location.protocol + "//" + window.location.host + window.location.pathname;
        console.log("base URL = ", url);
        // Set up the event handlers
        // for the sort by title menu item
        $("#sort-by-title").on("click", function() {
            // Handling the clicking of the menu item
            url += "?orderby=title";
            console.log(url);
            window.location = url;
            return false;
        });
        //     the sort by box office menu item
        $("#sort-by-box-office").on("click", function() {
            // Handling the clicking of the menu item
            url += "?orderby=box-office";
            console.log(url);
            window.location = url;
            return false;
        });
        //     the reset sort order menu item
        $("#reset-sort").on("click", function() {
            // Handling the clicking of the menu item
            url = window.location.protocol + "//" + window.location.host + window.location.pathname;
            console.log(url);
            window.location = url;
            return false;
        });
        //     the search button
        //     the clear button
        $("#clear-button").on("click", function() {
            // Handling the clicking of the menu item
            url = window.location.protocol + "//" + window.location.host + window.location.pathname;
            window.location = url;
            return false;
        });

    });
    </script>
    <style>
    .wrapper{
        box-shadow: 3px 3px 5px rgba(0,0,0,0.2); 
        border:0.8px solid; 
        border-color:rgba(0,0,0,0.2); 
        border-radius:10px; 
        height:90%;
        width:90%;
        margin:auto;
    }
    .col-md-6{
        margin:0px; 
        float:left; 
        width:50%; 
        height:100%;
        padding:3%; 
        font-size:18px;
        margin:0;
        line-height:160%;
        font-family:Tahoma, sans-serif;
    }
    .poster{
        float:right; 
        max-width:50%; 
        max-height:100%;
        margin:0;
        width:auto;
        height:auto;
        border-top-right-radius: 10px; 
        border-bottom-right-radius:10px;   
    }
    .navbar-brand{
        margin-left:20px;
    }
    #clear-button{
        margin-left:5px;
    }
    </style>
</head>
<body>
    <!-- Add your navbar here -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <img src="marvel-logo.png" alt="marvel-logo" width="100rem" height="50rem">
        <a class="navbar-brand" href="#">Marvel Movies</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Sorting
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" id="sort-by-title" href="#">Sort by Title</a>
                <a class="dropdown-item" id="sort-by-box-office" href="#">Sort by Box Office</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" id="reset-sort" href="#">Reset Sort Order</a>
                </div>
            </li>
            </ul>
            <form class="form-inline my-2 my-lg-0">
            <input class="form-control mr-sm-2" name="s" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" id="search-button" type="submit">Search</button>
            <button class="btn btn-outline-info my-2 my-sm-0" id="clear-button" type="submit">Clear</button>
            </form>
        </div>
    </nav>
 
    <!-- Add your movie list here -->
    <div class="container">
    <div class="row">
        <?php foreach ($movies as $movie) { ?>
            <div class="col-lg-4">
            <div class="wrapper">
                    <div class="col-md-6">
                        <?php echo $movie["title"] . "<br>"; ?>
                        <?php echo "<span style=\"color:rgba(0,0,0,0.4)\">(" . $movie["year"] . ") </span><br>"; ?>
                        <?php echo "<span style=\"font-size:0.9rem\"><b>Box office: " . $movie["box-office"] . "M</b></span>"; ?>
                    </div>
                        <?php echo "<img src=\"\\posters\\" . $movie["poster"] . "\" alt=\"marvel-poster\" class=\"poster\" width=\"100%\" height=\"100%\">"; ?>
            </div>
            </div>
        <?php } ?>
    </div>
</div>
</body>
</html>
