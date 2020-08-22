<?php
// Read the XML file into a DOM structure
header('Access-Control-Allow-Origin: *');
//header('Content-Type: text/plain; charset=utf-8');
$xml = new DOMDocument();
$xml->preserveWhiteSpace = false; // remove whitespace nodes 
$xml->load("cities.xml");

// Read all the movie nodes
$all_city_nodes = $xml->getElementsByTagName("city");

// Retrieve the search term and orderby term
//$search = $_GET["s"];
//$orderby = $_GET["orderby"];

// Create the movies array
$city_array = [];

// For each movie node, convert it to a PHP array
foreach ($all_city_nodes as $node) { 
    $city = [];
    //$movie["search"] = false;
    foreach ($node->childNodes as $child) {
        
        $city[$child->nodeName] = $child->nodeValue;
    }
    $city_array[] = $city;
    // Add the movie to the movies array
    // - You need to modify this code if you want to sort the movies
    // $orderby variable controls the order of movies
    /*
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
    */
    
}

$status = "";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Travel Around the World</title>
    <meta charset="utf-8">
    <meta name="viewport" 
          content="width=device-width">
          <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"> 
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="html2canvas.js"></script>
    <script>
    $(document).ready(function() {
        // This is the hashchange event function
        console.log($(document).height());
        if (!check_window_size()) {
            return;
        }
        iteratively_check_window_size();
        //window.location.hash = "#menu";
        $(window).on('hashchange', function() {
            
            // Get the fragment identifier from the URL
            var page = window.location.hash;
            if (page == "") page = "#menu";
            
            
            var cube_cell = $("#cube-cell");

            switch(page){
                case "#main":
                    
                    $("#menuPage").hide();
                    load_mainPage();
                    $("#mainPage").show();
                    $("#cube-container").show();
                    card_restore();
                    break;
                case "#menu":
                    $("#mainPage").hide();
                    $("#cardPage").hide();
                    $("#cube-container").hide();
                    $("#finishPage").hide();
                    $("#continue_travel_btn").hide();
                    $("#begin_travel_btn").hide();
                    continue_status();
                    $("#menuPage").show();
                    break;
            }
        });

        // You may want to trigger the hashchange event when the page loads
        if (window.location.hash=="#main") {window.location.hash="#menu";}
        else {$(window).trigger('hashchange');}
        

        $('textarea[data-limit-rows=true]').on('keypress', function (event) {
            var textarea = $(this),
                text = textarea.val(),
                numberOfLines = text.split("\n").length + 1,
                maxRows = parseInt(textarea.attr('rows'));
            //console.log(maxRows);
            //console.log(numberOfLines);
            if (event.which==13 && numberOfLines == maxRows ) {
                console.log("false");
                return false;
            }
        });
        
    });
    </script>
    
    <style>
    /* Set up your own styles for the navbar and the pokemon list */
        .navbar {
            margin:1em;
            border:2px darkgray solid;
            border-radius: 1em;
        }
        .navbar-brand img {
            height:80px;
            margin-right:80px;
        }
        .cell {
            background-color:#fff;
            height:100%;
            width:100%;
            padding:0;
        }

        #start-cell {
            height: 100%;
            width: 100%;
            padding:10%;
            z-index:1;
        }
        .row {
            height:100px;
        }

        #player-container {
            top:0;
            left:0;
            height:100%; 
            width:100%;
            position: absolute;
            margin:auto;
            animation-name: player-animation;
            animation-timing-function: ease-in-out;
            animation-iteration-count: infinite;
            animation-duration: 2s;
        }
        @keyframes player-animation {
            64% {transform: translateY(0px);}
            80% {transform: translateY(-75px);}
            96% {transform: translateY(0px);}
        }
        #cube .front {
            transform: 
                translateZ(100px);
        }

        #cube .back {
            transform: 
                rotateX(-180deg)
                translateZ(100px);
        }

        #cube .right {
            transform:
                rotateY(90deg)
                translateZ(100px);
        }

        #cube .left {
            transform:
                rotateY(-90deg)
                translateZ(100px);
        }

        #cube .top {
            transform:
                rotateX(90deg)
                translateZ(100px);
        }

        #cube .bottom {
            transform:
                rotateX(-90deg)
                translateZ(100px);
        }

        #cube-container {
            width: 200px;
            height: 200px;
            position: fixed;
            left: 50%;
            top: 40%;
            transform: translateX(-50%);
            margin: 0 auto 40px;
            border: 1px solid #FFF;
            z-index: 9998;
            perspective: 1000px;
            perspective-origin: 50% 100%;
        }

        #cube {
            width: 100%;
            height: 100%;
            top: 100px;
            transform-style: preserve-3d;
            transition: transform 1s;
        }

        #cube:hover {
            cursor: pointer;
        }

        #cube div {
            background: hsla(0, 85%, 50%, 0.8);
            display: block;
            position: absolute;
            width: 200px;
            height: 200px;
            border: 2px solid #ab1a1a;
            
            margin: 0 auto;  
            
            font-family: Arial, Helvetica, sans-serif;
            font-size: 500%;
            text-align: center;
            padding: 50px 0;
        }

        .dot {
            display: block;
            position: absolute;
            width: 30px;
            height: 30px;
            background: #fff;
            border-radius: 15px;  
        }

        .front .dot1 { top: 85px; left: 85px; }
        .back .dot1 { top: 45px; left: 45px; }
        .back .dot2 { top: 125px; left: 125px; }
        .right .dot1 { top: 45px; left: 45px; }
        .right .dot2 { top: 85px; left: 85px; }
        .right .dot3 { top: 125px; left: 125px; }
        .left .dot1 { top: 45px; left: 45px; }
        .left .dot2 { top: 45px; left: 125px; }
        .left .dot3 { top: 125px; left: 45px; }
        .left .dot4 { top: 125px; left: 125px; }
        .top .dot1 { top: 45px; left: 45px; }
        .top .dot2 { top: 45px; left: 125px; }
        .top .dot3 { top: 85px; left: 85px; }
        .top .dot4 { top: 125px; left: 45px; }
        .top .dot5 { top: 125px; left: 125px; }
        .bottom .dot1 { top: 45px; left: 45px; }
        .bottom .dot2 { top: 45px; left: 85px; }
        .bottom .dot3 { top: 45px; left: 125px; }
        .bottom .dot4 { top: 125px; left: 45px; }
        .bottom .dot5 { top: 125px; left: 85px; }
        .bottom .dot6 { top: 125px; left: 125px; }
        
        #mainPage {
            opacity:1;
            filter:alpha(opacity=30);
            background: #fff;
        }
        #cardPage {
            width: 600px;
            height: 480px;
            position: fixed;
            left: 50%;
            top: 50%;
            margin: 0;
            text-align: center;
            transform: translateX(-50%) translateY(-50%);
            z-index: 9998;
            /*background-color: transparent;*/
            perspective: 1000px; /* Remove this if you don't want the 3D effect */
        }
        #cardContent {
            width: 100%;
            height: 84%;
            text-align: center;
            transition: transform 0.8s;
            transform-style: preserve-3d;
        }
        #card-btn-div {
            margin:auto;
            width:100%;
            top:90%;
            height:16%;
            padding-top:5%;
        }
        /* Position the front and back side */
        #flip-card-front, #flip-card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            -webkit-backface-visibility: hidden; /* Safari */
            backface-visibility: hidden;
        }
        
        #flip-card-front {
            background-color: #eef7e9;
            box-shadow: 0 2px 6px #555;
        }
        /* Style the back side */
        #flip-card-back {
            background-color: #eef7e9;
            transform: rotateY(180deg);
            margin: 0 auto;
            /*background: url('https://developer.mozilla.org/files/4151/background.jpg'); img from: https://developer.mozilla.org/en-US/docs/Web/Guide/HTML/Forms/Styling_HTML_forms */
            font-family: "Comic Sans MS", cursive, sans-serif;
            box-shadow: 0 2px 8px #555;
        }

        .absolute {
            position: absolute;
        } 

        #postcard-bottom {
            width:100%;
            top: 71%;
            height: 29%; 
            text-align: center;
        }
        #postcard-up-right {
            right: 6%;
            top: 8%;
            width:35%;
            height: 60%
        }

        textarea {
            margin-top: 10px;
            width: 100%;
            padding: 10px;
            font-size: 0.7em;
            background: transparent;
            letter-spacing: 3px;
            line-height: 1.5em;
            border: none;
            display:block;
        }

        #mymessage_div {
            width:55%;
            height: 60%;
            left: 3.5%;
            top: 10.5%;
        }
        #mymessage:hover {
            border:1px solid #cdf;
        }

        #mymessage:focus {
            outline-color:#cdf;
        }
        .clip {
            object-fit: cover;
        }

        #card_front_text {
            background-color: rgb(80, 80, 80, 0.7);
            top: 50%;
            transform: translateY(-50%);
            left:10%;
            width: 80%;
            color: #fff;
            font-size: 1.2em;
            letter-spacing: 2px;
            padding: 1em;
            text-align: center;
            font-family: "Comic Sans MS", cursive, sans-serif;
            
        }

        #finishPage {
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translateX(-50%) translateY(-50%);
            text-align: center;
            font-size: 2em;
            font-family: "Comic Sans MS", cursive, sans-serif;
            font-weight: bold;
            margin: 0;
        }

        #finishPage p {
            margin: 2em,0;
        }

        label img {
            height: 100px;
            width: 100px;
            transition-duration: 0.2s;
            transform-origin: 50% 50%;
        }

        .icons {
            padding:1%;
        }
        
        .container{
            text-align: center;
        }

        .btn {
            max-height:100%;
            margin:0 auto;
            font-family: "Comic Sans MS", cursive, sans-serif;
        }

        #menu-btn-div {
            margin-top: 5em;
        }

        #menu-icons-div {
            margin-top: 2em;
        }
        
        #menu-text1 {
            font-size:3em;
            font-weight:bold;
            font-family: "Comic Sans MS", cursive, sans-serif;
            margin-top:1em;
        }

        #menu-text2 {
            font-size:2em;
            font-family: "Comic Sans MS", cursive, sans-serif;
            margin-top:2.5em;
        }
        
    </style>
</head>
<body>
    <!-- Put your navbar here -->
    <nav id="navbar" class="navbar navbar-expand-sm navbar-light bg-light">
        <a id="navbar_logo" class="navbar-brand" href="#menu">
            <img src="logo.svg"/>
        </a>

      
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item">
              <a id="return_to_menu" class="nav-link" href="#menu">Return to menu</a>
            </li>
          </ul>
        </div>
        
    </nav>
    <!-- This is the main page -->
    <div id="mainPage" class="container" style="display: none">
        <h2> - Travel Map - </h2>
        <div id="map-container">
            <div class="row" id="row1">
                <div class="cell col-1 offset-1" id="start">
                    <img src='scenes/start.png' alt='map_img' id="start-cell"/>
                </div>
                <div class="cell col-1" id="order3">
                    <img alt='map_img' class="clip" height="100%" width="100%"/>
                </div>
                <div class="cell col-1" id="order4">
                    <img alt='map_img' class="clip" height="100%" width="100%"/>
                </div>
                <div class="cell col-1" id="order5">
                    <img alt='map_img' class="clip" height="100%" width="100%"/>
                </div>
                <div class="cell col-1" id="order6">
                    <img alt='map_img' class="clip" height="100%" width="100%"/>
                </div>
                <div class="cell col-1" id="order7">
                    <img alt='map_img' class="clip" height="100%" width="100%"/>
                </div>
                <div class="cell col-1" id="order8">
                    <img alt='map_img' class="clip" height="100%" width="100%"/>
                </div>
                <div class="cell col-1" id="order9">
                    <img alt='map_img' class="clip" height="100%" width="100%"/>
                </div>
                <div class="cell col-1" id="order10">
                    <img alt='map_img' class="clip" height="100%" width="100%"/>
                </div>
            </div>
            <div class="row" id="row2">
                <div class="cell col-1 offset-2" id="order2">
                    <img alt='map_img' class="clip" height="100%" width="100%"/>
                </div>
                <div class="cell col-1 offset-6" id="order11">
                    <img alt='map_img' class="clip" height="100%" width="100%"/>
                </div>
            </div>
            <div class="row" id="row3">
                <div class="cell col-1" id="order31">
                    <img alt='map_img' class="clip" height="100%" width="100%"/>
                </div>
                <div class="cell col-1" id="order32">
                    <img alt='map_img' class="clip" height="100%" width="100%"/>
                </div>
                <div class="cell col-1" id="order1">
                    <img alt='map_img' class="clip" height="100%" width="100%"/>
                </div>
                <div class="cell col-1 offset-6" id="order12">
                    <img alt='map_img' class="clip" height="100%" width="100%"/>
                </div>
                <div class="cell col-1" id="order13">
                    <img alt='map_img' class="clip" height="100%" width="100%"/>
                </div>
                <div class="cell col-1" id="order14">
                    <img alt='map_img' class="clip" height="100%" width="100%"/>
                </div>
            </div>
            <div class="row" id="row4">
                <div class="cell col-1" id="order30">
                    <img alt='map_img' class="clip" height="100%" width="100%"/>
                </div>
                <div class="cell col-1 offset-10" id="order15">
                    <img alt='map_img' class="clip" height="100%" width="100%"/>
                </div>
            </div>
            <div class="row" id="row5">
                <div class="cell col-1" id="order29">
                    <img alt='map_img' class="clip" height="100%" width="100%"/>
                </div>
                <div class="cell col-1" id="order28">
                    <img alt='map_img' class="clip" height="100%" width="100%"/>
                </div>
                <div class="cell col-1 offset-8" id="order17">
                    <img alt='map_img' class="clip" height="100%" width="100%"/>
                </div>
                <div class="cell col-1" id="order16">
                    <img alt='map_img' class="clip" height="100%" width="100%"/>
                </div>
            </div>
            <div class="row" id="row6">
                <div class="cell col-1 offset-1" id="order27">
                    <img alt='map_img' class="clip" height="100%" width="100%"/>
                </div>
                <div class="cell col-1" id="order26">
                    <img alt='map_img' class="clip" height="100%" width="100%"/>
                </div>
                <div class="cell col-1" id="order25">
                    <img alt='map_img' class="clip" height="100%" width="100%"/>
                </div>
                <div class="cell col-1" id="order24">
                    <img alt='map_img' class="clip" height="100%" width="100%"/>
                </div>
                <div class="cell col-1" id="order23">
                    <img alt='map_img' class="clip" height="100%" width="100%"/>
                </div>
                <div class="cell col-1" id="order22">
                    <img alt='map_img' class="clip" height="100%" width="100%"/>
                </div>
                <div class="cell col-1" id="order21">
                    <img alt='map_img' class="clip" height="100%" width="100%"/>
                </div>
                <div class="cell col-1" id="order20">
                    <img alt='map_img' class="clip" height="100%" width="100%"/>
                </div>
                <div class="cell col-1" id="order19">
                    <img alt='map_img' class="clip" height="100%" width="100%"/>
                </div>
                <div class="cell col-1" id="order18">
                    <img alt='map_img' class="clip" height="100%" width="100%"/>
                </div>
            </div>
        </div>
        <div id="player-container" style="display:none; height:100%; width:100%">
            <img src="" alt="player-icon" id="player_img" width="90%" height="95%" />
            <!--
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
                <g id="player">
                    <circle cx="50" cy="50" fill="rgb(100, 240, 120, 0.7)" id="svg_19" r="40" stroke="#dd4d4d" stroke-dasharray="null" stroke-linecap="null" stroke-linejoin="null" stroke-width="0"/>
                </g>
            </svg>
            -->
        </div>
    </div>

    <section id="cube-container">
        <div id="cube">
            <div class="front" id="front">
                <span class="dot dot1"></span>
            </div>
            <div class="back" id="back">
                <span class="dot dot1"></span>
                <span class="dot dot2"></span>
            </div>
            <div class="right" id="right">
                <span class="dot dot1"></span>
                <span class="dot dot2"></span>  
                <span class="dot dot3"></span>
            </div>
            <div class="left" id="left">
                <span class="dot dot1"></span>
                <span class="dot dot2"></span>  
                <span class="dot dot3"></span>
                <span class="dot dot4"></span>
            </div>
            <div class="top" id="top">
                <span class="dot dot1"></span>
                <span class="dot dot2"></span>  
                <span class="dot dot3"></span>
                <span class="dot dot4"></span>
                <span class="dot dot5"></span>
            </div>
            <div class="bottom" id="bottom">
                <span class="dot dot1"></span>
                <span class="dot dot2"></span>  
                <span class="dot dot3"></span>
                <span class="dot dot4"></span>
                <span class="dot dot5"></span>
                <span class="dot dot6"></span>
            </div>
        </div>
    </section>
    <!-- This is the card page -->
    <div id="cardPage" style="display: none">
        <div id="cardContent">
            <div id="flip-card-front">
                <img alt="front-img" class="clip" id="card_front_img" style="width:100%; height:100%;">
                <div class="absolute" id="card_front_text">You arrive at Paris!</div>
            </div>
            <div id="flip-card-back">
                <form class="postcard">
                    <div class="absolute" id="mymessage_div">
                        <label for="t-message" >Travel message: </label>
                        <textarea id="mymessage" name="message" rows="7" maxlength="154" placeholder="Hello! Write your message here in this fancy-schmancy Box!"></textarea>
                    </div>
                </form>
                <div class="absolute" id="postcard-up-right">
                    <h3 id="change_name"> Paris </h3>
                    <br>
                    <img alt="front-img" class="clip" id="card_back_img" style="width:90%;height:68%;">
                </div>
                <div class="absolute" id="postcard-bottom">
                    <p> This is destination No.<span id="change_number" style="font-size: 1.5em">1</span> of my around-the-world trip</p>
                    <p>The most famous attraction here is <span id="change_attraction" style="font-size:1.5em">La Tour Eiffel</span></p>
                </div>
            </div>
            
            
        </div>
        <div id="card-btn-div">
            <button class="btn btn-outline-dark" onclick="flip();">Make Postcard</button>
            <button class="btn btn-outline-dark" style="display:none" onclick="doCapture();">Save Postcard</button>
            <button class="btn btn-outline-dark" id="card_btn">Continue </button>
        </div>

    </div>
    <!-- This is the finish page -->
    <div id = "finishPage" style="display:none">
        <p>Congratulations!</p>
        <p> You have finished your around-the-world trip!</p>
    </div>
    <!-- This is the menu page -->
    <div id="menuPage" class="container page pb-3" style="display: block">
        <h1 id="menu-text1"> Welcome to Virtual World Travel ! </h1>
        <p id="menu-text2"> --- Please select your favorite player icon --- </p>
        <div id="menu-icons-div" class="row h-100">
            <div class="icons col-sm-2 offset-sm-2">
                <img src="icons/red.png" width="100%" height="100%" />
            </div>
            <div class="icons col-sm-2">
                <img src="icons/black.png" width="100%" height="100%" />
            </div>
            <div class="icons col-sm-2">
                <img src="icons/blue.png" width="100%" height="100%" />
            </div>
            <div class="icons col-sm-2">
                <img src="icons/yellow.png" width="100%" height="100%" />
            </div>

        </div>
        <div id="menu-btn-div" class="row h-100">
            <button id="continue_travel_btn" type="button" class="col-lg-3 col-xl-2 btn btn-outline-dark" style="display: none">Continue your trip</a></button>
            <button id="begin_travel_btn" type="button" class="col-lg-3 col-xl-2 btn btn-outline-dark" style="display: none">New trip</a></button>
        </div>
    </div>


    <script>
        var cube = document.getElementById("cube");
        var min = 1;
        var max = 24;
        var cur_order = 2;
        var total_cities = 32;
        var visited_set = new Set();
        var dest_idx = 0;
        var cur_city;
        var parts;
        var show_card_timer;
        var die_rolling_timer;
        $("#return_to_menu, #navbar_logo").click(function() {
            console.log("clear!!!!");
            clearTimeout(die_rolling_timer);
            clearTimeout(show_card_timer);
        });
        $(".icons").mouseenter(function(){
            $(this).css("box-shadow", "0 0 5px #73F1FD");
        });
        $(".icons").mouseleave(function(){
            if ($("#player-container").children('img').attr('src') == $(this).children('img').attr('src')){
                return;
            }
            $(this).css("box-shadow", "none");
        });
        $(".icons").click(function(){
            $(".icons").css("box-shadow", "none");
            $(".icons").css("border", "none");
            $(this).css("box-shadow", "0 0 5px #73F1FD");
            $(this).css("border", "2px #A7F5FD solid");
            $("#player-container").children('img').attr('src', $(this).children('img').attr('src'));
        })
        document.getElementById("begin_travel_btn").onclick = function() {
            if ($("#player-container").children('img').attr('src') == "") {
                alert("Please select a player icon !");
            }
            else {
                visited_set.clear();
                save_status();
                parts=[""];
                window.location.href="#main";
            }
        }
        document.getElementById("continue_travel_btn").onclick = function() {
            if ($("#player-container").children('img').attr('src') == "") {
                alert("Please select a player icon !");
            }
            else {
                card_restore();
                window.location.href="#main";
            }
        }
        function card_restore() {
            document.getElementById("cardContent").style.transform = "rotateY(0deg)";
            $("#mymessage").val("");
            $("#card_front_text").show();
        }
        function load_mainPage() {
            $("#mainPage").css("opacity", "1"); 
            if (parts[0].length==0) {
                dest_idx = 0;
                cur_order = 2;
                var target_cell = $("#start");
                $("#player-container").appendTo(target_cell);
                $("#player-container").show();
                show_map_img();
                return;
            }
            parts.forEach(function(part){ // for each line in parts
                visited_set.add(parseInt(part));
            });	
            show_map_img();

            //console.log("parts size = ", parts.length);
            dest_idx = visited_set.size;
            cur_order = parseInt(parts[parts.length-1]);
            var target_cell = $("#order"+cur_order.toString());
            $("#player-container").appendTo(target_cell);
            $("#player-container").show();
            //console.log("dest_idx changed to: ",dest_idx);
            alert("Welcome back!");
        }
        function continue_status() {
            $.ajax({
                url: "continue_status.php",
                type: "GET",
                dataType: "text",
                success: function (result) { 
                    parts=result.trim().split(" ");
                    console.log(parts);
                    console.log("length = ",parts[0].length);
                    if (parts[0].length==0) {
                        console.log("no status")
                        $("#begin_travel_btn").show();
                    }
                    else {
                        $("#continue_travel_btn").show();
                        $("#begin_travel_btn").show();
                        alert("Detected unfinished trip. Would you like to continue?");
                    }
                }
            });
        }
        function show_map_img() {
            $.ajax({
                url: "cities.xml",
                type: "GET",
                dataType: "xml",
                success: function(result) {
                    $(result).find("city").each(function() {
                        if (visited_set.has(parseInt($(this).find("order").text()))){
                            $("#order"+$(this).find("order").text()).children('img').attr('src','scenes/gray.png');
                        }
                        else {
                            var new_src = "scenes/"+$(this).find("title").text().replace(/\s+/g, '').toLowerCase()+".png";
                            $("#order"+$(this).find("order").text()).children('img').attr('src',new_src);
                        }
                        
                    });
                }
            }).fail(function(){
                    alert("failed");
            });
        }
        function show_travel_card() {
            $("#cube-container").hide(); 
            $("#mainPage").css("opacity", "0.2");           
            $.ajax({
                url: "cities.xml",
                type: "GET",
                dataType: "xml",
                
                success: function(result) {
                    $(result).find("city").each(function() {
                        if (parseInt($(this).find("order").text())==cur_order) {
                            cur_city = $(this);
                            dest_idx += 1;
                            if(dest_idx==total_cities) {
                                $("#card_btn").text("Finish");
                            }
                            $("#change_name").text($(this).find("title").text());
                            $("#change_number").text(dest_idx.toString());
                            $("#change_attraction").text($(this).find("attractions").text());
                            var card_front_text = "<p> You arrive at <span style='font-size:1.5em'>" + $(this).find("title").text() + "</span> !</p>";
                            card_front_text += "<p style='text-align:justify'>" + $(this).find("description").text() + "</p>";
                            $("#card_front_text").html(card_front_text);
                            
                            var img_src = "scenes/"+$(this).find("title").text().replace(/\s+/g, '').toLowerCase()+".png";
                            $("#card_front_img").attr("src", img_src);
                            $("#card_back_img").attr("src", img_src);
                        }
                    });
                    $("#cardPage").show();
                }
            }).fail(function(){
                    alert("failed");
            });
            
        }
        document.getElementById("card_btn").onclick = function() {
            if (dest_idx==total_cities) { // The end of game
                $("#cardPage").hide();
                $("#mainPage").css("opacity", "0.1"); 
                $("#finishPage").show();
                visited_set.clear();
                save_status();
                return;
            }
            card_restore();
            $("#cardPage").hide();
            $("#mainPage").css("opacity", "1");
            $("#cube-container").show();
        }
        function flip() {
            if (document.getElementById("cardContent").style.transform == "rotateY(180deg)") {
                document.getElementById("cardContent").style.transform = "rotateY(0deg)";
                $("#card_front_text").show();
            }
            else {
                document.getElementById("cardContent").style.transform = "rotateY(180deg)";
                $("#card_front_text").hide();
            }
        }
        function output_mouse_hover(){
            console.log(document.querySelectorAll(":hover"));
            setTimeout(output_mouse_hover, 2000);
        }
        //output_mouse_hover();

        cube.onclick = function() {
            console.log(cur_order, dest_idx);
            var xRand = getRandom(max, min);
            var yRand = getRandom(max, min);
                    
            cube.style.webkitTransform = 'rotateX('+xRand+'deg) rotateY('+yRand+'deg)';
            cube.style.transform = 'rotateX('+xRand+'deg) rotateY('+yRand+'deg)';
            die_rolling_timer = setTimeout(function(){
                var sides = ["front", "back", "right", "left", "top", "bottom"];
                var num_of_steps = 0;
                sides.forEach(check_result);
                function check_result(item, index){
                    if (document.getElementById(item).getBoundingClientRect().height-220>0 &&
                        document.getElementById(item).getBoundingClientRect().width-220>0){
                            num_of_steps = index+1;
                            walk(index+1);                            
                        }
                }
                show_card_timer = setTimeout(show_travel_card, 500*num_of_steps+400);
                
                
            }, 1200);
            
        }
        
        function walk(num_of_steps) {
            var step_remaining = num_of_steps;
            function countDown() {
                step_remaining -= 1;
                
                cur_order+=1;
                if(cur_order==total_cities+1) {cur_order=1;}
                while(visited_set.has(cur_order)){
                    if(cur_order==total_cities){cur_order=1;}
                    else {cur_order+=1;}
                }
                var target_cell = $("#order"+cur_order.toString());
                $("#player-container").appendTo(target_cell);
                if(step_remaining>0){
                    setTimeout(countDown, 500);
                }
                else{
                    visited_set.add(cur_order);
                    save_status();
                    var html = "<img src='scenes/gray.png' alt='map_img' height='100%' width='100%' class='clip' />";
                    $("#order"+cur_order.toString()).children('img').attr('src','scenes/gray.png');
                }
            }
            setTimeout(countDown, 500);
        }
        
        function getRandom(max, min) {
            return (Math.floor(Math.random() * (max-min)) + min) * 90;
        }

        function doCapture() {
            html2canvas(document.getElementById("flip-card-back")).then(function(canvas) {
                var ajax = new XMLHttpRequest();
                ajax.open("POST", "save-postercard.php", true);
                ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                ajax.send("image=" + canvas.toDataURL("image/jpeg"), 0.98);
                ajax.onreadystatechange = function() {
                    if(this.readyState=4 && this.status==200) {
                        console.log(this.responseText);
                    }
                };
            });
            //window.location.href = "postcard.jpeg";
        }
        
        function save_status() {
            var data = "";
            visited_set.forEach(function(item) {
                data += item.toString()+" ";
            });
            console.log(data);
            $.ajax({
                type: 'POST',
                    url: 'update_status.php',
                    data: { status:data },
                async: true
            });
        }

        function check_window_size() {
            if ($(document).width()<768) {
                $("body").html("<h2>Your window size is too small! Please enlarge your window size, or change to a device with larger screen.</h2><h2>Then reload the page</h2>");
                return false;
            }else if ($(document).width()>=768 && $(document).width()<992) {
                $(".row").css("height", "60px");
            }else if ($(document).width()>=992 && $(document).width()<1200){
                $(".row").css("height", "75px");
            }else {
                $(".row").css("height", "90px");
            }
            return true;
        }
        function iteratively_check_window_size() {
            check_window_size();
            setTimeout(iteratively_check_window_size,1000);
        }

    </script>

    
</body>
</html>
