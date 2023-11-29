<?
require_once "../config.php";
?>
<!DOCTYPE html>
<html>

<head>
  <title>PalStory</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="manifest" href="manifest.json" />

  <link rel="apple-touch-icon" sizes="512x512" href="android/android-launchericon-512-512.png">
  <link rel="apple-touch-icon" sizes="192x192" href="android/android-launchericon-192-192.png">
  <link rel="apple-touch-icon" sizes="180x180" href="ios/180.png">
  <link rel="icon" type="image/png" sizes="32x32" href="ios/32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="ios/16.png">
  <link rel="shortcut icon" href="./favicon.ico">
  
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-black.css">

  <style>
    * {
      box-sizing: border-box;
    }

    /* Create two unequal columns that floats next to each other */
    .column {
      float: left;
      height: 100%;
    }

    .left {
      width: 75%;
    }

    .right {
      width: 25%;
    }

    .row {
      position: absolute;
      overflow: hidden;
      top: 0;
      bottom: 0;
      left: 0;
      right: 0;
    }

    /* Clear floats after the columns */
    .row:after {
      content: "";
      display: table;
      clear: both;
    }

    #chat,
    #game {
      width: 100%;
      height: 100%;
    }

    #login_box {
      position: absolute;
      left: 50%;
      top: 50%;
      -webkit-transform: translate(-50%, -50%);
      transform: translate(-50%, -50%);
      padding-right: 50px;
      padding-bottom: 50px;
    }

    #screen_2 {
      display: none;
    }

    input,
    textarea {

      background-color:rgba(0, 0, 0, 0.5);
      border: 1px solid white;
      border-radius: 2px;
      color: #fff;

    }

    <?
    require_once "../config.php";
    $bg = "/story/uploads/riverwood.jpg";
$srlocs = $db->prepare("SELECT * 
FROM resources_locations");
  if ($srlocs->execute()) {
    $srlocr = $srlocs->get_result();
    $srlocrc = mysqli_num_rows($srlocr);

    if ($srlocrc > 0) {
      $rloc = mysqli_fetch_all($srlocr)[rand(0, $srlocrc - 1)];
      $bg = $rloc[2];
    }
  }
  $srlocs->close();
    ?>

    body {
      
      background:linear-gradient(
          rgba(0, 0, 0, 0.7), 
          rgba(0, 0, 0, 0.7)
        ),url("/story/uploads/<?echo $bg;?>");
      background-repeat: no-repeat;
      background-size: 100% 100%;
    }
    html {
      height: 100%
    }
  </style>
</head>

<body class="w3-theme-l1">

  <div id="screen_1">
  
    <div style="background-color: #222222;text-align:center">
      <div style="float:left;"><a href="/story/create.php">Admin</a></div>
      <span style="color:#aaa;" id="resource_info"></span>
      <div style="float: right">&nbsp;| <a href="/">Home</a></div>
      <div style="float: right"><a href="/story/credits.html">Credits</a></div>
      <div style='clear:both;'></div>
    </div>
    <div id="login_box">
      <div id="header_logo">
      <h1 style="width: 100%; text-align: center; margin-bottom: 20px;">
      <img src="/story/uploads/palstory_logo.png" alt="PalStory" width="162" height="83">
      </h1>
      </div>
      <div id="header_text" style="display: none;">
      <h1 style="width: 100%; text-align: center;">
      PalStory
      </h1>
      </div>
      <label for="room_name">Game name:</label>
      <br />

      <? if (isset($_GET["room"])) { ?>

        <input type="text" id="room_name" name="room_name" value="<? echo $_GET["room"]; ?>">
        <br />
        <label for="player_name">Player name:</label>
        <br />
        <input type="text" id="player_name" name="player_name" value="<? echo isset($_GET["player"]) ? $_GET["player"] : ""; ?>" autofocus>

      <? } else { ?>

        <input type="text" id="room_name" name="room_name" autofocus>
        <br />
        <label for="player_name">Player name:</label>
        <br />
        <input type="text" id="player_name" name="player_name" value="<? echo isset($_GET["player"]) ? $_GET["player"] : ""; ?>">

      <? } ?>

      <br />
      <input type="checkbox" id="classic" name="classic">
      <label for="classic"> Classic UI</label><br />
      <input type="checkbox" id="chatmode" name="chatmode">
      <label for="chatmode"> Show chat</label><br /><br />
      <button id="login_btn" onclick="login()" style="width: 120px;">Play!</button><br />
      <a href="/story/game.php?room=room<? echo rand(0,999); ?>&player=user<? echo rand(0,999); ?>">Demo</a>
    </div>
  </div>


  <div class="row" id="screen_2">
    <div class="column left" style="background-color:#aaa;">
      <iframe id="game" src="/story/loading.html" title="Game" scrolling="no" frameborder="0"></iframe>
    </div>
    <div class="column right" style="background-color:#aaa;">
      <iframe id="chat" src="/story/loading.html" title="Chat" frameborder="0"></iframe>
    </div>
  </div>

</body>

</html>

<script src="jquery-2.2.4.min.js"></script>
<script>
  function login() {
    $("#screen_1").hide();
    $("#screen_2").show();
    var classic = "";
    if ($("#classic").is(":checked")) {
      classic = "&classic";
    }
    if ($("#chatmode").is(":checked")) {
	document.getElementById("chat").src = "/?chat=" + $("#room_name").val() + "&edro_char=" + $("#player_name").val() + classic;
    	document.getElementById("game").src = "/story/game.php?room=" + $("#room_name").val() + "&player=" + $("#player_name").val() + classic;
    	$("#game").focus();
    }
    else {
        window.location = "/story/game.php?room=" + $("#room_name").val() + "&player=" + $("#player_name").val() + classic;
    }
  }

  document.getElementById("room_name").addEventListener("keypress", function (event) {
    if (event.key === "Enter") {
      event.preventDefault();
      document.getElementById('player_name').focus();
    }
  });

  document.getElementById("player_name").addEventListener("keypress", function (event) {
    if (event.key === "Enter") {
      event.preventDefault();
      document.getElementById('login_btn').click();
    }
  });

  document.getElementById("login_btn").addEventListener("keypress", function (event) {
    if (event.key === "Enter") {
      event.preventDefault();
      document.getElementById('login_btn').click();
    }
  });

  $(window).resize(function() {
    if ($(this).height() < 360) {
      $('#header_logo').hide();
      $('#header_text').hide();
    }
    else if ($(this).height() < 420) {
      $('#header_logo').hide();
      $('#header_text').show();
    } else {
      $('#header_text').hide();
      $('#header_logo').show();
    }
  });

  $(document).ready(function(){

    if ($(document).height() < 360) {
      $('#header_logo').hide();
      $('#header_text').hide();
    }
    else if ($(document).height() < 420) {
      $('#header_logo').hide();
      $('#header_text').show();
    } else {
      $('#header_text').hide();
      $('#header_logo').show();
    }

    $.ajax({
    url: "gameServer.php",
    type: "get",
    data: "get_resource_info=1",
    dataType: "json",
    success: function (response, status, http) {
      console.log(response);
      if (response.length === 3) {
        $("#resource_info").html("I/M/L: " + response[0][0] + ", " + response[1][0] + ", " + response[2][0]);
      } 
    },
    error: function (http, status, error) {
      console.error("error: " + error);
    },
  });
  }); 

</script>