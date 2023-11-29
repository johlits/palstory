<!DOCTYPE html>
<html>

<head>
  <title>Play</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="apple-touch-icon" sizes="180x180" href="./apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="./favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="./favicon-16x16.png">
  <link rel="manifest" href="./site.webmanifest">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-black.css">
  <? if (!isset($_GET['classic'])) { ?>
  <link rel="stylesheet" href="nes.min.css">
  <? } ?>
  
  <style>
    .box {
      background: rgba(0, 0, 0, 0.5);
      padding: 5px;
    }

@font-face {
    font-family: ps2p;
    src: url(PressStart2P-Regular.ttf);
}

    html, body, pre, code, kbd, samp {
      overflow: hidden;
      <? if (!isset($_GET['classic'])) { ?>
      font-family: ps2p;
      <? } ?>
    }

.statusbar {
  width: 320px;
  height: 30px;
}

.statusbar_outer {
  position: relative;
}

.statusbar_text {
  position: absolute;
  top: 0;
  left: 0;
}

.shadow {
  text-shadow: 2px 2px black;
}

#player_bstats, #monster_bstats {
  width: 300px;
}

.player_bstat {
  width: 150px;
  float: left;
}

.location_bstat {
  width: 100%;
  float: left;
}

  </style>
</head>

<body onload="init()" class="w3-theme-l1">

  <canvas id="gc" width="200" height="100" style="background-color: #333;">
  </canvas>

  <section>
  <dialog class="nes-dialog is-dark is-rounded" id="win-dialog">
    <form method="dialog">
      <p class="title">Victory!</p>
      <div id="winBattleBox">Alert: this is a dialog.1</div>
      <menu class="dialog-menu">
        <button class="nes-btn is-primary" onclick="playSound('uploads/click-21156.mp3');" style="float: right;">Okay</button>
      </menu>
    </form>
  </dialog>
</section>

<section>
  <dialog class="nes-dialog is-dark is-rounded" id="help-dialog">
    <form method="dialog">
      <p class="title">Help</p>
      <div>
        <span>WASD or arrow keys - Movement</span><br/>
        <span>I - Toggle items</span><br/>
        <span>Z - Toggle location info</span><br/>
        <span>X - Toggle location stats</span><br/>
        <span>C - Toggle character info</span><br/>
        <span>V - Toggle monster info</span><br/>
        <span>B - Toggle monster stats</span><br/>
        <span>N - Toggle monster battle log</span><br/>
        <span>M - Attack</span><br/>
        <span>H - Toggle help</span><br/>
      </div>
      <menu class="dialog-menu">
        <button id="close_help_btn" class="nes-btn is-primary" onclick="playSound('uploads/click-21156.mp3');" style="float: right;">Okay</button>
      </menu>
    </form>
  </dialog>
</section>

<section>
  <dialog class="nes-dialog is-dark is-rounded" id="lose-dialog">
    <form method="dialog">
      <p class="title">Defeat...</p>
      <div>You died. Better luck next time.</div>
      <menu class="dialog-menu">
        <button class="nes-btn is-primary" onclick="gameOver();" style="float: right;">Okay</button>
      </menu>
    </form>
  </dialog>
</section>

  <div id="player_box" class="box" style="position: absolute; top: 0; left: 0; display: none;">
    <div class="shadow">
    <!-- <a href="#" class="nes-badge is-splited" style="width: 300px;">
      <span id="room" class="is-dark"><? echo $_GET["room"]; ?></span>
      <span class="is-dark room_expire"></span>
    </a><br/> -->
    <!-- Room: &nbsp;(<span id="player_bx"></span>, <span id="player_by"></span>)<br /> -->
    <a href="#" class="nes-badge is-splited" onclick="toggleStats()" style="width: 290px;">
      <span class="is-dark" id="b_player"><? echo substr($_GET["player"], 0, 8); ?></span>
      <span class="is-dark"><span id="player_bx"></span> <span id="player_by"></span></span>
    </a>
    <div class="statusbar_outer"><div class="statusbar_text">HP: <span id="player_hp"></span>/<span id="player_maxhp"></span></div><progress id="player_hp_progress" class="nes-progress is-success statusbar" value="100" max="100"></progress></div>
    <!-- <div class="statusbar_outer"><div class="statusbar_text">SP: <span id="player_sp"></span>/<span id="player_maxsp"></span></div><progress id="player_sp_progress" class="nes-progress is-primary statusbar" value="100" max="100"></progress></div> -->
    <div class="statusbar_outer"><div class="statusbar_text">LV: <span id="player_lvl"></span> (<span id="player_exp"></span>/<span id="player_expup"></span>)</div><progress id="player_lv_progress" class="nes-progress is-primary statusbar" value="100" max="100"></progress></div>
</div>


    <div id="player_bstats" class="shadow" style="display: none">
    <span class="nes-text is-warning">Player Stats</span><br/>
    <div class="player_bstat">ATK: <span id="player_atk"></span></div>
    <div class="player_bstat">DEF: <span id="player_def"></span></div>
    <div class="player_bstat">SPD: <span id="player_spd"></span></div>
    <div class="player_bstat">EVD: <span id="player_evd"></span></div>
    <span>Gold: <span id="player_gold"></span></span>
    <br/>
    </div>

    <!-- <button id="showStatsBtn" type="button" class="nes-btn" onclick="toggleStats()">Stats</button> -->
    <!-- <button id="bgmOffBtn" type="button" class="nes-btn is-error" style="display: none;" onclick="getMusic(0)">BGM</button>
    <button id="bgmOnBtn" type="button" class="nes-btn is-success" onclick="getMusic(1)">BGM</button>
    <button id="sfxOffBtn" type="button" class="nes-btn is-error" onclick="getSfx(0)">SFX</button>
    <button id="sfxOnBtn" type="button" class="nes-btn is-success" style="display: none;" onclick="getSfx(1)">SFX</button> -->
    
    <div id="debug" class="shadow" style="display: none">
    <br/>
    <b class="nes-text is-error">DEBUG</b><br/>
    Player id: <span id="player_id"></span><br/>
    Player name: <span id="player"><? echo $_GET["player"]; ?></span><br/>
    Room id: <span id="room_id"></span><br/>
    Room expires: <span id="room_expire"></span><br/>
    Room regen: <span id="room_regen"></span><br/>
    Position: <span id="player_x"></span>, <span id="player_y"></span><br/>
    Stats: <span id="player_stats"></span>
    </div>

    <!-- <button type="button" class="nes-btn is-error" onclick="toggleDebug()">Debug</button> -->

    
  </div>

  <div id="compass" class="box shadow" style="padding-left: 10px; padding-right: 10px; margin-top: 5px; display: none; position: absolute; top: 0; left: 50%; transform: translate(-50%, 0);">
  <span>
      <a id="game_link" href="#"><span id="room"><? echo $_GET["room"]; ?></span></a>
      <span><span id="mouse_x">W0</span> <span id="mouse_y">N0</span></span>
</span>
</div>

<div id="items_box" style="position: absolute; top: 0; right: 0; display: none;">

<div class="box">
<div style="overflow-y: scroll;max-height: 400px; display: none;" id="items_table">
<div class="nes-table-responsive">
  <table class="nes-table is-bordered is-dark" id="items_table_body">
  <thead>
      <tr>
        <th>Name</th>
        <th>ATK</th>
        <th>DEF</th>
        <th>SPD</th>
        <th>EVD</th>
        <th>Equip</th>
        <th>Remove</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
</div>
</div>

<div id="item_info_box" style="max-height:300px;overflow-y:scroll;display: none;">
<img id="item_image" src="" style="width:280px;height:280px;float: left; margin: 5px;">
<span class="item_name nes-text is-warning" style="text-align: justify;"></span><br/>
<span id="item_description" style = "text-align: justify;"></span>
</div>

<div>
<button type="button" class="nes-btn" onclick="toggleItemsTable()">Items</button>
<button id="items_description_btn" type="button" class="nes-btn" onclick="toggleItemsDescription()" style="display: none">Close description</button>
<span id="audioBtns">

    <button id="sfxOffBtn" type="button" class="nes-btn is-error" onclick="getSfx(0)">SFX</button>
    <button id="sfxOnBtn" type="button" class="nes-btn is-success" style="display: none;" onclick="getSfx(1)">SFX</button>
    <button id="t2sOffBtn" type="button" class="nes-btn is-error" style="display: none;" onclick="getT2s(0)">T2S</button>
    <button id="t2sOnBtn" type="button" class="nes-btn is-success" onclick="getT2s(1)">T2S</button>
    <button id="bgmOffBtn" type="button" class="nes-btn is-error" style="display: none;" onclick="getMusic(0)">BGM</button>
    <button id="bgmOnBtn" type="button" class="nes-btn is-success" onclick="getMusic(1)">BGM</button>
<!-- <button type="button" class="nes-btn" onclick="toggleItemsStats()">Stats</button> -->
</span>
</div>
</div>
</div>

<div id="location_box" style="position: absolute; bottom: 0; left: 0; display: none;">
<div id="location_name_box" class="shadow">
<span class="location_name nes-text is-warning"></span>
</div>
  <div id="location_data_box" class="box shadow" style="display: none;">

  <div id="location_info_box" style="max-height:300px;overflow-y: scroll;">
  <img id="location_image" src="" style="width:280px;height:280px;float: left; margin: 5px;">
  <span class="location_name nes-text is-warning" style="text-align: justify;"></span><br/>
  <span id="location_description" style = "text-align: justify;"></span>
</div>

<div id="location_stats_box" style="height: 200px;">
  <span class="nes-text is-warning">Location Stats</span><br/>
  <span class="location_bstat">Name: <span class="location_name"></span></span><br/>
    <span class="location_bstat  nes-text is-disabled">Spawns: <span id="location_spawns"></span></span>
    </div>
</div>

<div class="box">
  <button id="locationInfoDisabledBtn" type="button" class="nes-btn is-disabled" style="display: none">Info</button>
  <button id="locationInfoBtn" type="button" class="nes-btn" onclick="toggleLocationInfo()">Info</button>
  <button id="locationStatsDisabledBtn" type="button" class="nes-btn is-disabled" style="display: none">Stats</button>
  <button id="locationStatsPrimaryBtn" type="button" class="nes-btn is-primary" onclick="toggleLocationStats()">Stats</button>
  <button id="moveDisabledBtn" type="button" class="nes-btn is-disabled">Move</button>
  <button id="moveSuccessBtn" type="button" class="nes-btn is-success" onclick="moveClick()">Move</button>
</div>
  </div>

  <div id="monster_box" style="position: absolute; bottom: 0; right: 0; display: none;">

<div id="monster_name_box" class="shadow">
<span class="monster_name nes-text is-warning" class="monster_name"></span>
</div>

<div id="monster_data_box" class="box shadow" style="display: none;">

<div id="monster_info_box" style="max-height:300px; overflow-y: scroll;">
<img id="monster_image" src="" style="width:280px;height:280px;float: left; margin: 5px;">
<span class="monster_name nes-text is-warning" style="text-align: justify;"></span><br/>
<span id="monster_description" style = "text-align: justify;"></span>
</div>

<div id="monster_battle_box" style="display: none;">
<span class="nes-text is-warning">Battle Log</span><br/>
<span class="monster_bstat">Name: <span class="monster_name"></span></span><br/>
<div class="statusbar_outer"><div class="statusbar_text">HP: <span class="monster_hp"></span>/<span class="monster_maxhp"></span></div><progress class="monster_hp_progress nes-progress is-success statusbar" value="100" max="100"></progress></div>
<div id="battle_log" style="height:240px; overflow-y: scroll;"></div>
</div>

<div id="monster_stats_box" style="height: 300px; width: 500px;">
<span class="nes-text is-warning">Monster Stats</span><br/>
<span class="monster_bstat">Name: <span class="monster_name"></span></span><br/>
<div class="statusbar_outer"><div class="statusbar_text">HP: <span class="monster_hp"></span>/<span class="monster_maxhp"></span></div><progress class="monster_hp_progress nes-progress is-success statusbar" value="100" max="100"></progress></div>

<div id="monster_bstats">
    <div class="player_bstat">ATK: <span id="monster_atk"></span></div>
    <div class="player_bstat">DEF: <span id="monster_def"></span></div>
    <div class="player_bstat">SPD: <span id="monster_spd"></span></div>
    <div class="player_bstat">EVD: <span id="monster_evd"></span></div>
    </div><br/><br/>

    <span class="monster_bstat nes-text is-disabled">Drops: <span id="monster_drops"></span></span><br/>
    <span class="monster_bstat nes-text is-disabled">Gold: <span id="monster_gold"></span></span><br/>
    <span class="monster_bstat nes-text is-disabled">Exp: <span id="monster_exp"></span></span>
  </div>
</div>


<div class="box" style="text-align: right;">
<button type="button" class="nes-btn" onclick="toggleMonsterInfo()">Info</button>
<button type="button" class="nes-btn is-primary" onclick="toggleMonsterStats()">Stats</button>
<button type="button" class="nes-btn is-warning" onclick="toggleBattleLog()">Battle Log</button>
<button type="button" class="nes-btn is-error" onclick="attackMonster()">Attack</button>
</div>
</div>

  <div id="create_game_box" class="box" style="position: absolute; bottom: 0; left: 0; width: 500px; height: 400px; display: none;">
    <span class="nes-text is-primary">Create game</span><br/><br/>
    <label for="create_game_room_name">Room name:</label>
    <br />
    <input type="text" id="create_game_room_name" class="nes-input" <? if (isset($_GET["room"])) { echo 'disabled style="color: #000;" value="' . $_GET["room"] . '"'; } ?>><br />
    <br/>
    <label for="create_game_expiration">Expiration:</label>
    <br />
    <input type="date" id="create_game_expiration" name="create_game_expiration">
    <!-- <br />
    <label for="create_game_expiration">Stamina regen (per hour):</label>
    <br />
    <input type="number" id="create_game_regen" name="create_game_regen" min="1" max="100" value="10"> -->
    <br />
    <br />
    <button id="create_game_btn" onclick="createGame()" type="button" class="nes-btn is-success">Start game!</button>
  </div>

  <div id="create_player_box" class="box" style="position: absolute; bottom: 0; left: 0; width: 500px; height: 500px; display: none;">
  <span class="nes-text is-primary">Create player</span><br/><br/>
    <label for="player_name">Player name:</label>
    <input type="text" id="player_name" class="nes-input" <? if (isset($_GET["player"])) { echo 'disabled style="color: #000;" value="' . $_GET["player"] . '"'; } ?>><br /><br/>
    <label for="player_portrait">Player portrait:</label>
    <input type="number" id="player_portrait" name="player_portrait" onchange="previewPortrait()" min="1" max="8" value="1">
    <br/>
    <img id="player_portrait_preview" src="" style="width:200px;height:200px; margin: 5px;">
    <br/>
    <button id="create_player_btn" onclick="createPlayer()" type="button" class="nes-btn is-success">Create player!</button>
  </div>
</body>
</html>

<script src="jquery-2.2.4.min.js"></script>
<script src="game.js"></script>