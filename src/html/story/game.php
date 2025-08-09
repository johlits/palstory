<!DOCTYPE html>
<html>

<head>
  <title>Play</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="apple-touch-icon" sizes="180x180" href="./apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="./favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="./favicon-16x16.png">
  <link rel="manifest" href="./site.webmanifest">
  <!-- Fonts: Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">

</head>

<body class="game-page" onload="init()">

  <canvas id="gc" width="200" height="100">
  </canvas>

  <section>
  <dialog class="nes-dialog is-dark is-rounded" id="win-dialog">
    <form method="dialog">
      <p class="title">Victory!</p>
      <div id="winBattleBox">Alert: this is a dialog.1</div>
      <menu class="dialog-menu">
        <button class="nes-btn is-primary" onclick="playSound(getImageUrl('click.mp3'));">Okay</button>
      </menu>
    </form>
  </dialog>
</section>

<section>
  <dialog class="nes-dialog is-dark is-rounded" id="help-dialog">
    <form method="dialog">
      <p class="title">Help</p>
      <div>
        <ul class="help-list">
          <li>WASD or arrow keys - Movement</li>
          <li>I - Toggle items</li>
          <li>Z - Toggle location info</li>
          <li>X - Toggle location stats</li>
          <li>C - Toggle character info</li>
          <li>V - Toggle monster info</li>
          <li>B - Toggle monster stats</li>
          <li>N - Toggle monster battle log</li>
          <li>M - Attack</li>
          <li>H - Toggle help</li>
        </ul>
      </div>
      <menu class="dialog-menu">
        <button id="close_help_btn" class="nes-btn is-primary" onclick="playSound(getImageUrl('click.mp3'));">Okay</button>
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
        <button class="nes-btn is-primary" onclick="gameOver();">Okay</button>
      </menu>
    </form>
  </dialog>
</section>

  <div id="player_box" class="box hidden">
    <div class="shadow">
    <!-- <a href="#" class="nes-badge is-splited" style="width: 300px;">
      <span id="room" class="is-dark"><? echo $_GET["room"]; ?></span>
      <span class="is-dark room_expire"></span>
    </a><br/> -->
    <!-- Room: &nbsp;(<span id="player_bx"></span>, <span id="player_by"></span>)<br /> -->
    <a href="#" class="nes-badge is-splited" onclick="toggleStats()">
      <span class="is-dark" id="b_player"><? echo substr($_GET["player"], 0, 8); ?></span>
      <span class="is-dark"><span id="player_bx"></span> <span id="player_by"></span></span>
    </a>
    <div class="statusbar_outer"><div class="statusbar_text">HP: <span id="player_hp"></span>/<span id="player_maxhp"></span></div><progress id="player_hp_progress" class="nes-progress is-success statusbar" value="100" max="100"></progress></div>
    <!-- <div class="statusbar_outer"><div class="statusbar_text">SP: <span id="player_sp"></span>/<span id="player_maxsp"></span></div><progress id="player_sp_progress" class="nes-progress is-primary statusbar" value="100" max="100"></progress></div> -->
    <div class="statusbar_outer"><div class="statusbar_text">LV: <span id="player_lvl"></span> (<span id="player_exp"></span>/<span id="player_expup"></span>)</div><progress id="player_lv_progress" class="nes-progress is-primary statusbar" value="100" max="100"></progress></div>
</div>


    <div id="player_bstats" class="shadow hidden">
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
    
    <div id="debug" class="shadow hidden">
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

  <div id="compass" class="box shadow hidden">
  <span>
      <a id="game_link" href="#"><span id="room"><? echo $_GET["room"]; ?></span></a>
      <span><span id="mouse_x">W0</span> <span id="mouse_y">N0</span></span>
</span>
</div>

<div id="items_box" class="hidden">

<div class="box">
<div id="items_table" class="hidden">
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

<div id="item_info_box" class="hidden">
<img id="item_image" src="">
<span class="item_name nes-text is-warning justify"></span><br/>
<span id="item_description" class="justify"></span>
</div>

<div>
<button type="button" class="nes-btn" onclick="toggleItemsTable()">Items</button>
<button id="items_description_btn" type="button" class="nes-btn hidden" onclick="toggleItemsDescription()">Close description</button>
<span id="audioBtns">

    <button id="sfxOffBtn" type="button" class="nes-btn is-error" onclick="getSfx(0)">SFX</button>
    <button id="sfxOnBtn" type="button" class="nes-btn is-success hidden" onclick="getSfx(1)">SFX</button>
    <button id="t2sOffBtn" type="button" class="nes-btn is-error hidden" onclick="getT2s(0)">T2S</button>
    <button id="t2sOnBtn" type="button" class="nes-btn is-success" onclick="getT2s(1)">T2S</button>
    <button id="bgmOffBtn" type="button" class="nes-btn is-error hidden" onclick="getMusic(0)">BGM</button>
    <button id="bgmOnBtn" type="button" class="nes-btn is-success" onclick="getMusic(1)">BGM</button>
<!-- <button type="button" class="nes-btn" onclick="toggleItemsStats()">Stats</button> -->
</span>
</div>
</div>
</div>

<div id="location_box" class="hidden">
<div id="location_name_box" class="shadow">
<span class="location_name nes-text is-warning"></span>
</div>
  <div id="location_data_box" class="box shadow hidden">

  <div id="location_info_box">
  <img id="location_image" src="">
  <span class="location_name nes-text is-warning justify"></span><br/>
  <span id="location_description" class="justify"></span>
</div>

<div id="location_stats_box">
  <span class="nes-text is-warning">Location Stats</span><br/>
  <span class="location_bstat">Name: <span class="location_name"></span></span><br/>
    <span class="location_bstat  nes-text is-disabled">Spawns: <span id="location_spawns"></span></span>
    </div>
</div>

<div class="box">
  <button id="locationInfoDisabledBtn" type="button" class="nes-btn is-disabled hidden">Info</button>
  <button id="locationInfoBtn" type="button" class="nes-btn" onclick="toggleLocationInfo()">Info</button>
  <button id="locationStatsDisabledBtn" type="button" class="nes-btn is-disabled hidden">Stats</button>
  <button id="locationStatsPrimaryBtn" type="button" class="nes-btn is-primary" onclick="toggleLocationStats()">Stats</button>
  <button id="moveDisabledBtn" type="button" class="nes-btn is-disabled">Move</button>
  <button id="moveSuccessBtn" type="button" class="nes-btn is-success" onclick="moveClick()">Move</button>
</div>
  </div>

  <div id="monster_box" class="hidden">

<div id="monster_name_box" class="shadow">
<span class="monster_name nes-text is-warning" class="monster_name"></span>
</div>

<div id="monster_data_box" class="box shadow hidden">

<div id="monster_info_box">
<img id="monster_image" src="">
<span class="monster_name nes-text is-warning justify"></span><br/>
<span id="monster_description" class="justify"></span>
</div>

<div id="monster_battle_box" class="hidden">
<span class="nes-text is-warning">Battle Log</span><br/>
<span class="monster_bstat">Name: <span class="monster_name"></span></span><br/>
<div class="statusbar_outer"><div class="statusbar_text">HP: <span class="monster_hp"></span>/<span class="monster_maxhp"></span></div><progress class="monster_hp_progress nes-progress is-success statusbar" value="100" max="100"></progress></div>
<div id="battle_log"></div>
</div>

<div id="monster_stats_box">
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


<div class="box text-right">
<button type="button" class="nes-btn" onclick="toggleMonsterInfo()">Info</button>
<button type="button" class="nes-btn is-primary" onclick="toggleMonsterStats()">Stats</button>
<button type="button" class="nes-btn is-warning" onclick="toggleBattleLog()">Battle Log</button>
<button type="button" class="nes-btn is-error" onclick="attackMonster()">Attack</button>
</div>
</div>

  <div id="create_game_box" class="box hidden">
    <div class="stack">
      <span class="nes-text is-primary">Create game</span>
      <div class="stack">
        <label for="create_game_room_name">Room name:</label>
        <input type="text" id="create_game_room_name" class="nes-input" <? if (isset($_GET["room"])) { echo 'disabled value="' . $_GET["room"] . '"'; } ?>>
      </div>
      <div class="stack">
        <label for="create_game_expiration">Expiration:</label>
        <input type="date" id="create_game_expiration" name="create_game_expiration">
      </div>
      <!--
      <div class="stack">
        <label for="create_game_regen">Stamina regen (per hour):</label>
        <input type="number" id="create_game_regen" name="create_game_regen" min="1" max="100" value="10">
      </div>
      -->
      <button id="create_game_btn" onclick="createGame()" type="button" class="nes-btn is-success mt-8">Start game!</button>
    </div>
  </div>

  <div id="create_player_box" class="box hidden">
    <div class="stack">
      <span class="nes-text is-primary">Create player</span>
      <div class="stack">
        <label for="player_name">Player name:</label>
        <input type="text" id="player_name" class="nes-input" <? if (isset($_GET["player"])) { echo 'disabled value="' . $_GET["player"] . '"'; } ?>>
      </div>
      <div class="stack">
        <label for="player_portrait">Player portrait:</label>
        <input type="number" id="player_portrait" name="player_portrait" onchange="previewPortrait()" min="1" max="8" value="1">
      </div>
      <img id="player_portrait_preview" src="">
      <button id="create_player_btn" onclick="createPlayer()" type="button" class="nes-btn is-success mt-8">Create player!</button>
    </div>
  </div>
</body>
</html>

<script src="jquery-2.2.4.min.js"></script>
<script src="config.js"></script>
<script src="game.js"></script>