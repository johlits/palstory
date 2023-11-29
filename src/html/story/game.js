var gc;
var player;
var player_x;
var player_y;
var locations = [];
var locationsDict = {};
var itemsDict = {};
var playersDict = {};
var players = [];
var player_portrait_id = "-1";

var moveInstructions = "Move using WASD or by clicking on an adjacent tile and pressing the move button. ";
var helpInstructions = "Press H for help & shortcuts. ";

var ss;
var w;
var h;

var cX = 0;
var cY = 0;
var oX = 0;
var oY = 0;

var showDebug = false;
var showStats = false;

var mX = 0;
var mY = 0;

var mapCoordFromX = 0;
var mapCoordFromY = 0;
var mapCoordToX = 0;
var mapCoordToY = 0;
var bgm = 0;
var sfx = 1;
var t2s = 0;

var moveDirection = "";
var playersLoaded = false;
var locationsLoaded = false;

var currentMonster;
var currentMonsterId = -1;
var canMove = false;

var playerAtk = 0;
var playerDef = 0;
var playerSpd = 0;
var playerEvd = 0;

var playerItemAtk = 0;
var playerItemDef = 0;
var playerItemSpd = 0;
var playerItemEvd = 0;
var gameStarted = false;

bgImage = new Image();
bgImage.src = "uploads/messybg.jpg";

const scientificNumbers = [
  { value: 1, symbol: "" },
  { value: 1e3, symbol: "k" },
  { value: 1e6, symbol: "M" },
  { value: 1e9, symbol: "G" },
  { value: 1e12, symbol: "T" },
  { value: 1e15, symbol: "P" },
  { value: 1e18, symbol: "E" }
];

function speak(message) {
  window.speechSynthesis.cancel();
  if (t2s === 1) {
    var msg = new SpeechSynthesisUtterance(message);
    var voices = window.speechSynthesis.getVoices();
    msg.voice = voices[0];
    window.speechSynthesis.speak(msg);
  }
}

function monster(name, description, stats, image) {
  this.name = name;
  this.description = description;
  this.stats = stats;
  this.image = image;
}

var showCreatePlayerBox = function () {
  if (!gameStarted) {
    previewPortrait();
    $("#create_player_box").show();
  }
}

var showCreateRoomBox = function () {
  if (!gameStarted) {
    $("#create_game_box").show();
  }
}

var gameOver = function () {
  var url = "/story/game.php?room=" + $("#room").text() + "&player=" + $("#player").text();
  window.location.href = url;
}

var setGameLink = function () {
  var url = "/story/game.php?room=" + $("#room").text() + "&player=" + $("#player").text();
  $("#game_link").attr("href", url);
}

var playSound = function (url) {
  if (sfx === 1) {
    var audio = new Audio(url);
    audio.loop = false;
    audio.play();
  }
}

var previewPortrait = function () {
  $("#player_portrait_preview").attr("src", getPortrait("-" + $("#player_portrait").val()));
}

var getPortrait = function (id) {
  console.log(id);
  var url = "uploads/p_birdman.png";
  switch (parseInt(id)) {
    case -1: url = "uploads/p_female_warrior.png"; break;
    case -2: url = "uploads/p_female_bowman.png"; break;
    case -3: url = "uploads/p_male_barbarian.png"; break;
    case -4: url = "uploads/p_male_priest.png"; break;
    case -5: url = "uploads/p_female_paladin.png"; break;
    case -6: url = "uploads/p_male_thief.png"; break;
    case -7: url = "uploads/p_female_mage.png"; break;
    case -8: url = "uploads/p_male_monk.png"; break;
  }
  console.log(url);
  return url;
}

var init = function () {
  gc = document.getElementById("gc");
  gc.width = window.innerWidth;
  gc.height = window.innerHeight;
  w = gc.width;
  h = gc.height;
  ss = Math.min(w, h) / 10;
  gc.focus();

  gc.addEventListener(
    "mousemove",
    function (evt) {
      var mousePos = getMousePos(gc, evt);
      var tX =
        Math.round((mousePos.x - mapCoordFromX) / ss - 0.5) + mapCoordToX;
      var tY =
        Math.round((mousePos.y - mapCoordFromY) / ss - 0.5) + mapCoordToY;
      if ((tX + oX) != mX || (tY + oY) != mY) {
        mX = tX + oX;
        mY = tY + oY;
        $("#mouse_x").text(bX(mX));
        $("#mouse_y").text(bY(mY));
      }
    },
    false
  );

  gc.addEventListener(
    "click",
    function (evt) {

      var loc = locationsDict["" + mX + "," + mY];

      if (loc) {
        var location = locationsDict["" + mX + "," + mY];
        $("#location_box").show();
        $(".location_name").text(location.name);
        $("#location_image").attr("src", location.image.currentSrc);
        $("#location_description").text(location.description);

        var location_stats = location.stats;
        var location_fields = location_stats.split(";");
        $("#location_spawns").text("None");
        for (var index = 0; index < location_fields.length; index++) {
          var field = location_fields[index];
          if (field.startsWith("spawns")) {
            $("#location_spawns").text(
              field.split("=")[1].split(",").join(", ")
            );
          }
        }
      }

      if (Math.abs(player_x - mX) + Math.abs(player_y - mY) === 1) {
        $("#location_box").show();
        if (!loc) {
          $(".location_name").text('???');
          $("#locationStatsPrimaryBtn").hide();
          $("#locationStatsDisabledBtn").show();
          $("#locationInfoBtn").hide();
          $("#locationInfoDisabledBtn").show();
        }
        else {
          $("#locationStatsDisabledBtn").hide();
          $("#locationStatsPrimaryBtn").show();
          $("#locationInfoDisabledBtn").hide();
          $("#locationInfoBtn").show();
        }
        $("#moveDisabledBtn").hide();
        $("#moveSuccessBtn").show();
        if (mX < player_x) moveDirection = "left";
        if (mX > player_x) moveDirection = "right";
        if (mY < player_y) moveDirection = "up";
        if (mY > player_y) moveDirection = "down";
      } else {
        $("#moveSuccessBtn").hide();
        $("#moveDisabledBtn").show();

        if (!loc) {
          $("#location_box").hide();
        }
      }
    },
    false
  );

  initGame();
};

var moveClick = function () {
  move(moveDirection);
};

var purge = function () {
  $.ajax({
    url: "gameServer.php",
    type: "get",
    data:
      "purge_rooms=1",
    dataType: "json",
    success: function (response, status, http) {
      console.log("purged " + response.length + " rooms..");
    },
    error: function (http, status, error) {
      console.error("error: " + error);
    },
  });
}

function initGame() {
  console.log("init game..");
  purge();
  getRoom();
}

var bX = function (x) {
  return x <= 0 ? "W" + -x : "E" + x;
};
var bY = function (y) {
  return y <= 0 ? "N" + -y : "S" + y;
};
var bG = function (num) {
  var digits = num >= 1000000 ? 2 : (num >= 1000 ? 1 : 0);
  const rx = /\.0+$|(\.[0-9]*[1-9])0+$/;
  var item = scientificNumbers.slice().reverse().find(function(item) {
    return num >= item.value;
  });
  return item ? (num / item.value).toFixed(digits).replace(rx, "$1") + item.symbol : "0";
};

var locationToggle = 0;
var itemToggle = 0;
var itemInfoBox = 0;
var monsterToggle = 0;

var toggleItemsTable = function () {
  playSound("uploads/click-21156.mp3");
  if (itemToggle == 0) {
    itemToggle = 1;
    $("#audioBtns").hide();
    $("#items_table").show();
    if (itemInfoBox == 1) {
      $("#items_table").hide();
      $("#item_info_box").show();
      $("#items_description_btn").show();
    }
    else {
      $("#audioBtns").hide();
      $("#items_table").show();
      $("#item_info_box").hide();
      $("#items_description_btn").hide();
    }
  }
  else {
    itemToggle = 0;
    $("#audioBtns").show();
    $("#items_table").hide();
    $("#item_info_box").hide();
    $("#items_description_btn").hide();
  }
}

var toggleItemsDescription = function () {
  playSound("uploads/click-21156.mp3");
  if (itemInfoBox == 0) {
    itemInfoBox = 1;
    $("#item_info_box").show();
    $("#items_description_btn").show();
    $("#items_table").hide();
  }
  else {
    itemInfoBox = 0;
    $("#item_info_box").hide();
    $("#items_description_btn").hide();
    $("#items_table").show();
  }
}

var toggleLocationInfo = function () {
  playSound("uploads/click-21156.mp3");
  if (locationToggle != 1) {
    $("#location_box").css("z-index", "2");
    $("#monster_box").css("z-index", "1");
    $("#location_data_box").show();
    speak($("#location_description").text());
    locationToggle = 1;
    $("#location_stats_box").hide();
    $("#location_info_box").show();
    $("#location_name_box").hide();
  } else {
    locationToggle = 0;
    $("#location_data_box").hide();
    $("#location_name_box").show();
  }
};

var toggleMonsterInfo = function () {
  playSound("uploads/click-21156.mp3");
  if (monsterToggle != 1) {
    $("#location_box").css("z-index", "1");
    $("#monster_box").css("z-index", "2");
    $("#monster_data_box").show();
    monsterToggle = 1;
    $("#monster_battle_box").hide();
    $("#monster_stats_box").hide();
    $("#monster_info_box").show();
    $("#monster_name_box").hide();
    speak($("#monster_description").text());
  } else {
    monsterToggle = 0;
    $("#monster_data_box").hide();
    $("#monster_name_box").show();
  }
};

var toggleLocationStats = function () {
  playSound("uploads/click-21156.mp3");
  if (locationToggle != 2) {
    $("#location_box").css("z-index", "2");
    $("#monster_box").css("z-index", "1");
    $("#location_data_box").show();
    locationToggle = 2;
    $("#location_info_box").hide();
    $("#location_stats_box").show();
    $("#location_name_box").hide();
  } else {
    locationToggle = 0;
    $("#location_data_box").hide();
    $("#location_name_box").show();
  }
};

var toggleMonsterStats = function () {
  playSound("uploads/click-21156.mp3");
  if (monsterToggle != 2) {
    $("#location_box").css("z-index", "1");
    $("#monster_box").css("z-index", "2");
    $("#monster_data_box").show();
    monsterToggle = 2;
    $("#monster_battle_box").hide();
    $("#monster_info_box").hide();
    $("#monster_stats_box").show();
    $("#monster_name_box").hide();
  } else {
    monsterToggle = 0;
    $("#monster_data_box").hide();
    $("#monster_name_box").show();
  }
};

var toggleBattleLog = function () {
  playSound("uploads/click-21156.mp3");
  if (monsterToggle != 3) {
    $("#location_box").css("z-index", "1");
    $("#monster_box").css("z-index", "2");
    $("#monster_data_box").show();
    monsterToggle = 3;
    $("#monster_info_box").hide();
    $("#monster_stats_box").hide();
    $("#monster_name_box").hide();
    $("#monster_battle_box").show();
  } else {
    monsterToggle = 0;
    $("#monster_data_box").hide();
    $("#monster_name_box").show();
  }
};

var attackMonster = function () {

  console.log("fighting..");
  $.ajax({
    url: "gameServer.php",
    type: "get",
    data:
      "fight_monster=" +
      $("#player").text() +
      "&room_id=" +
      $("#room_id").text(),
    dataType: "json",
    success: function (response, status, http) {
      if (response.length > 0) {
        var wasSlain = false;
        var died = false;
        $.each(response, function (index, item) {
          $("#battle_log").prepend("<span>" + item + '</span><br/>');
          if (wasSlain || item.includes(" was slain!")) {
            wasSlain = true;
            $("#winBattleBox").append("<span>" + item + '</span><br/>');
          }
          else if (item.includes(" died.")) {
            died = true;
          }
        });

        if (died) {
          document.getElementById('lose-dialog').showModal();
        }
        else if (wasSlain) {
          playSound("uploads/good-6081.mp3");
          document.getElementById('win-dialog').showModal();
        }
        else {
          playSound("uploads/sword-hit-7160.mp3");
        }
        getPlayer(false);
        move('na');
      }
    },
    error: function (http, status, error) {
      console.error("error: " + error);
    },
  });
};

var toggleDebug = function () {
  if (!showDebug) {
    showDebug = true;
    $("#debug").show();
  } else {
    showDebug = false;
    $("#debug").hide();
  }
};

var toggleStats = function () {
  playSound("uploads/click-21156.mp3");
  if (!showStats) {
    showStats = true;
    $("#player_bstats").show();
    $("#showStatsBtn").prop("value", "Hide Stats");
  } else {
    showStats = false;
    $("#player_bstats").hide();
    $("#showStatsBtn").prop("value", "Show Stats");
  }
};

var getPlayer = function (initGame = true) {
  console.log("getting player..");
  $.ajax({
    url: "gameServer.php",
    type: "get",
    data:
      "get_player=" + $("#player").text() + "&room_id=" + $("#room_id").text(),
    dataType: "json",
    success: function (response, status, http) {
      if (response.length == 0) {
        showCreatePlayerBox();
      } else {
        $("#player_id").text(response[0].id);
        player_x = parseInt(response[0].x);
        $("#player_x").text(player_x);
        player_y = parseInt(response[0].y);
        $("#player_y").text(player_y);
        $("#player_bx").text(bX(response[0].x));
        $("#player_by").text(bY(response[0].y));

        if (initGame) {
          cX = response[0].x;
          cY = response[0].y;
          setGameLink();
        }

        player_portrait_id = response[0].resource_id;

        var stats = response[0].stats;
        var fields = stats.split(";");
        playerAtk = 0;
        playerDef = 0;
        playerSpd = 0;
        playerEvd = 0;
        for (var index = 0; index < fields.length; index++) {
          var field = fields[index];
          if (field.startsWith("atk")) {
            playerAtk = parseInt(field.split("=")[1]);
          } else if (field.startsWith("def")) {
            playerDef = parseInt(field.split("=")[1]);
          } else if (field.startsWith("spd")) {
            playerSpd = parseInt(field.split("=")[1]);
          } else if (field.startsWith("evd")) {
            playerEvd = parseInt(field.split("=")[1]);
          }
          else if (field.startsWith("lvl")) {
            $("#player_lvl").text(field.split("=")[1]);
            var lvlx = parseInt(field.split("=")[1]);
            var lvlup = parseInt(10 + 3 * lvlx + Math.pow(10, 0.01 * lvlx));
            $("#player_expup").text(lvlup);
            $("#player_lv_progress").attr("max", lvlup);
          }
          else if (field.startsWith("exp")) {
            $("#player_exp").text(field.split("=")[1]);
            $("#player_lv_progress").attr("value", field.split("=")[1]);
          }
          else if (field.startsWith("hp")) {
            $("#player_hp").text(field.split("=")[1]);
            $("#player_hp_progress").attr("value", field.split("=")[1]);
          }
          else if (field.startsWith("maxhp")) {
            $("#player_maxhp").text(field.split("=")[1]);
            $("#player_hp_progress").attr("max", field.split("=")[1]);
          }
          else if (field.startsWith("gold")) {
            $("#player_gold").text(bG(field.split("=")[1]));
          }
        }
        $("#player_stats").text(stats);
        $("#player_sp").text("100");
        $("#player_maxsp").text("100");

        $("#player_box").show();
        $("#compass").show();

        getItems();

        if (initGame) {
          startGame();
        }

      }
    },
    error: function (http, status, error) {
      console.error("error: " + error);
    },
  });
};

var getRoom = function () {
  console.log("getting room..");
  $.ajax({
    url: "gameServer.php",
    type: "get",
    data: "get_room=" + $("#room").text(),
    dataType: "json",
    success: function (response, status, http) {
      if (response.length == 0) {
        showCreateRoomBox();
      } else {
        if (response[0].name === "") {
          showCreateRoomBox();
        }
        else {
          $("#room_id").text(response[0].id);
          $("#room_expire").text(response[0].expiration);
          // $(".room_expire").text(response[0].expiration.split(" ")[0]);
          $("#room_regen").text(response[0].regen);
          getPlayer();
        }
      }
    },
    error: function (http, status, error) {
      console.error("error: " + error);
    },
  });
};

var getMusic = function (setBgm) {
  playSound("uploads/click-21156.mp3");
  if (setBgm == 1) {
    bgm = 1;
    $("#bgmOnBtn").hide();
    $("#bgmOffBtn").show();

    console.log("getting music..");
    $.ajax({
      url: "gameServer.php",
      type: "get",
      data: "get_music=1",
      dataType: "json",
      success: function (response, status, http) {
        if (response.length > 0) {
          console.log(response);

          var e = document.getElementById("bgm");
          if (e !== null) e.remove();

          var s = document.createElement("audio");
          s.setAttribute("id", "bgm");
          s.src = response[2 + Math.floor(Math.random() * (response.length - 2))];
          s.setAttribute("preload", "auto");
          s.setAttribute("controls", "loop");
          s.style.display = "none";
          document.body.appendChild(s);
          s.play();
        }
      },
      error: function (http, status, error) {
        console.error("error: " + error);
      },
    });
  }
  else {
    bgm = 0;
    $("#bgmOffBtn").hide();
    $("#bgmOnBtn").show();
    var e = document.getElementById("bgm");
    if (e !== null) e.pause();
  }
};

var getSfx = function (setSfx) {
  sfx = 1;
  playSound("uploads/click-21156.mp3");
  if (setSfx == 1) {
    sfx = 1;
    $("#sfxOnBtn").hide();
    $("#sfxOffBtn").show();
  }
  else {
    sfx = 0;
    $("#sfxOffBtn").hide();
    $("#sfxOnBtn").show();
  }
};

var getT2s = function (setT2s) {
  t2s = 1;
  playSound("uploads/click-21156.mp3");
  if (setT2s == 1) {
    t2s = 1;
    $("#t2sOnBtn").hide();
    $("#t2sOffBtn").show();
  }
  else {
    t2s = 0;
    window.speechSynthesis.cancel();
    $("#t2sOffBtn").hide();
    $("#t2sOnBtn").show();
  }
};

var getItems = function () {
  console.log("getting items..");
  $.ajax({
    url: "gameServer.php",
    type: "get",
    data: "get_items=" + $("#player_id").text() + "&room_id=" + $("#room_id").text(),
    dataType: "json",
    success: function (response, status, http) {
      if (response.length > 0) {
        $("#items_table_body").find('tbody tr').remove();

        playerItemAtk = 0;
        playerItemDef = 0;
        playerItemSpd = 0;
        playerItemEvd = 0;

        $.each(response, function (index, item) {

          var itemId = parseInt(item['id']);
          var itemName = item['name'];
          var itemAtk = '';
          var itemDef = '';
          var itemSpd = '';
          var itemEvd = '';
          var itemType = '';
          var itemEquipped = parseInt(item['equipped']) == 1 ? 'checked' : '';
          var itemRemove = 'Drop';
          var itemImage = item['image'];
          var itemDescription = item['description'];

          var item_fields = item['stats'].split(";");

          for (var index = 0; index < item_fields.length; index++) {

            var field = item_fields[index];
            if (field.startsWith("atk=")) {
              itemAtk = field.split("=")[1];
            }
            if (field.startsWith("def=")) {
              itemDef = field.split("=")[1];
            }
            if (field.startsWith("spd=")) {
              itemSpd = field.split("=")[1];
            }
            if (field.startsWith("evd=")) {
              itemEvd = field.split("=")[1];
            }
            if (field.startsWith("type=")) {
              itemType = field.split("=")[1];
            }
          }

          if (itemEquipped === 'checked') {
            playerItemAtk += parseInt(itemAtk);
            playerItemDef += parseInt(itemDef);
            playerItemSpd += parseInt(itemSpd);
            playerItemEvd += parseInt(itemEvd);
          }

          itemsDict[itemId] = {
            name: itemName,
            image: itemImage,
            description: itemDescription
          };

          // TODO remove listeners?
          $("#items_table_body").find('tbody').append('<tr><td id="in' + itemId + '">' + itemName + '</td><td>' + itemAtk + '</td><td>' + itemDef + '</td><td>' + itemSpd + '</td><td>' + itemEvd + '</td><td><label><input id="ie' + itemId + '" type="checkbox" class="nes-checkbox is-dark" ' + itemEquipped + ' /><span>' + itemType + '</span></label></td><td><span id="ir' + itemId + '" class="nes-text is-error">' + itemRemove + '</span></td></tr>');
          $('#ie' + itemId).change(function () {
            playSound("uploads/click-21156.mp3");
            var no = parseInt(this.id.slice(2));

            $.ajax({
              url: "gameServer.php",
              type: "get",
              data: "equip_item=" + no + "&player_id=" + $("#player_id").text(),
              dataType: "json",
              success: function (response, status, http) {
                if (response[0] === "ok") {
                  getItems();
                }
              },
              error: function (http, status, error) {
                console.error("error: " + error);
              },
            });

            // equip item
          });
          $('#ir' + itemId).click(function () {

            playSound("uploads/click-21156.mp3");
            var no = parseInt(this.id.slice(2));

            // remove item
            $.ajax({
              url: "gameServer.php",
              type: "get",
              data: "drop_item=" + no + "&player_id=" + $("#player_id").text(),
              dataType: "json",
              success: function (response, status, http) {
                if (response[0] === "ok") {
                  getItems();
                }
              },
              error: function (http, status, error) {
                console.error("error: " + error);
              },
            });

          });
          $('#in' + itemId).click(function () {
            playSound("uploads/click-21156.mp3");
            var no = parseInt(this.id.slice(2));
            $(".item_name").text(itemsDict[no].name);
            $("#item_image").attr("src", 'uploads/' + itemsDict[no].image);
            $("#item_description").text(itemsDict[no].description);
            $("#items_table").hide();
            $("#item_info_box").show();
            $("#items_description_btn").show();
            itemInfoBox = 1;
            speak($("#item_description").text());
          });
        });

        $("#player_atk").html("<span>" + (playerAtk + playerItemAtk) + "</span><br/><span style='color: #bbb'>(" + playerAtk + "+" + playerItemAtk + ")</span>");
        $("#player_def").html("<span>" + (playerDef + playerItemDef) + "</span><br/><span style='color: #bbb'>(" + playerDef + "+" + playerItemDef + ")</span>");
        $("#player_spd").html("<span>" + (playerSpd + playerItemSpd) + "</span><br/><span style='color: #bbb'>(" + playerSpd + "+" + playerItemSpd + ")</span>");
        $("#player_evd").html("<span>" + (playerEvd + playerItemEvd) + "</span><br/><span style='color: #bbb'>(" + playerEvd + "+" + playerItemEvd + ")</span>");
      }
      else {
        $("#items_table_body").find('tbody tr').remove();
      }
    },
    error: function (http, status, error) {
      console.error("error: " + error);
    },
  });
};

var createPlayer = function () {
  playSound("uploads/click-21156.mp3");
  $("#create_player_box").hide();
  var name = $("#player_name").val();
  console.log("name is ");
  $("#player").text(name);
  $("#b_player").text(name.slice(0, 8));
  console.log(name);
  var player_portrait = parseInt($("#player_portrait").val());
  var room_id = $("#room_id").text();
  console.log("creating player " + name + " with room id " + room_id + "..");
  $.ajax({
    url: "gameServer.php",
    type: "get",
    data: "create_player=" + name + "&room_id=" + room_id + "&player_portrait=" + player_portrait,
    dataType: "json",
    success: function (response, status, http) {
      if (response[0] === "ok") {
        getPlayer();
      } else {
        console.log("error creating player");
        showCreatePlayerBox();
      }
    },
    error: function (http, status, error) {
      showCreatePlayerBox();
      console.error("error: " + error);
    },
  });
};

var createGame = function () {
  playSound("uploads/click-21156.mp3");
  $("#create_game_box").hide();
  var name = $("#create_game_room_name").val();
  $("#room").text(name);
  var expiration = $("#create_game_expiration").val();
  var regen = 0;//$("#create_game_regen").val();
  console.log(
    "creating game " +
    name +
    " (expiration " +
    expiration +
    // ", regen: " +
    // regen +
    ").."
  );
  $.ajax({
    url: "gameServer.php",
    type: "get",
    data:
      "create_room=" +
      name +
      "&expiration=" +
      expiration +
      "&regen=" +
      regen,
    dataType: "json",
    success: function (response, status, http) {
      if (response[0] === "ok") {
        getRoom();
      } else {
        console.log("error creating room");
        showCreateRoomBox();
      }
    },
    error: function (http, status, error) {
      showCreateRoomBox();
      console.error("error: " + error);
    },
  });
};

function startGame() {
  console.log("starting game..");
  $("#items_box").show();

  // set to 0
  var offsetx = player_x * ss;
  var offsety = player_y * ss;

  mapCoordFromX = w / 2 + offsetx;
  mapCoordFromY = h / 2 + offsety;
  mapCoordToX = player_x;
  mapCoordToY = player_y;

  player = new component(
    -1,
    ss,
    ss,
    getPortrait(player_portrait_id),
    w / 2 + offsetx,
    h / 2 + offsety,
    "image",
    $("#player").text(),
    "",
    $("#player_stats").text(),
    1
  );
  myGameArea.start();
  gameStarted = true;

  getAllPlayers(true);
}

function getAllLocations(newX, newY, dX, dY) {
  console.log("get all locations..");
  $.ajax({
    url: "gameServer.php",
    type: "get",
    data: "get_all_locations=" + $("#room_id").text(),
    dataType: "json",
    success: function (response, status, http) {
      if (response.length > 0) {
        locations = [];
        locationsDict = {};
        $.each(response, function (index, item) {
          var landscape = new component(
            -1,
            ss,
            ss,
            "uploads/" + item.image,
            player.x + parseInt(item.x - player_x + dX) * ss,
            player.y + parseInt(item.y - player_y + dY) * ss,
            "image",
            item.name,
            item.description,
            item.stats,
            3
          );
          locations.push(landscape);
          locationsDict["" + item.x + "," + item.y] = landscape;
        });
        locationsLoaded = true;
        if (newX === null) {
          canMove = true;
          move("na");
          center();
        }
        else {
          getMonsters(newX, newY);
        }
      }
      else {
        locationsLoaded = true;
        if (newX === null) {
          canMove = true;
          move("na");
          center();
        }
        else {
          getMonsters(newX, newY);
        }
      }
    },
    error: function (http, status, error) {
      console.error("error: " + error);
    },
  });
}

function getAllPlayers(getLocations = false) {
  console.log("get all players..");
  $.ajax({
    url: "gameServer.php",
    type: "get",
    data: "get_players=" + $("#room_id").text(),
    dataType: "json",
    success: function (response, status, http) {
      var pid = parseInt($("#player_id").text());
      if (response.length > 0) {
        $.each(response, function (index, item) {
          var tid = parseInt(item.id);
          if (tid != pid) {
            if (!playersDict[tid]) {

              var temp_player = new component(
                tid,
                ss,
                ss,
                getPortrait(item.resource_id),
                player.x + parseInt(item.x - player_x) * ss,
                player.y + parseInt(item.y - player_y) * ss,
                "image",
                item.name,
                "",
                item.stats,
                2
              );

              playersDict[tid] = {
                id: tid,
                name: item.name,
                x: parseInt(item.x),
                y: parseInt(item.y),
                stats: item.stats,
                resource_id: parseInt(item.resource_id),
                comp: temp_player
              };

              players.push(temp_player);

            }
            else {

              playersDict[tid] = {
                id: tid,
                name: item.name,
                x: parseInt(item.x),
                y: parseInt(item.y),
                stats: item.stats,
                resource_id: parseInt(item.resource_id),
                comp: playersDict[tid].comp
              };

            }
          }
        });
        playersLoaded = true;
        if (getLocations) {
          getAllLocations(null, null, null, null);
        }
      }
      else {
        playersLoaded = true;
        if (getLocations) {
          getAllLocations(null, null, null, null);
        }
      }
    },
    error: function (http, status, error) {
      console.error("error: " + error);
    },
  });
}

function getMousePos(canvas, evt) {
  var rect = canvas.getBoundingClientRect();
  return {
    x: evt.clientX - rect.left,
    y: evt.clientY - rect.top,
  };
}

var myGameArea = {
  canvas: document.getElementById("gc"),
  start: function () {
    this.context = this.canvas.getContext("2d");
    this.frameNo = 0;
    this.interval = setInterval(updateGameArea, 20);
  },
  clear: function () {
    this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);
  },
  stop: function () {
    clearInterval(this.interval);
  },
};

function component(id, width, height, color, x, y, type, name, description, stats, meta) {
  this.id = id;
  this.type = type;
  if (type == "image") {
    this.image = new Image();
    this.image.src = color;
  }
  this.width = width;
  this.height = height;
  this.speedX = 0;
  this.speedY = 0;
  this.x = x;
  this.y = y;
  this.nX = x;
  this.nY = y;
  this.name = name;
  this.meta = meta;
  this.description = description;
  this.stats = stats;
  this.moving = false;
  this.update = function () {
    ctx = myGameArea.context;
    if (type == "image") {
      ctx.drawImage(this.image, this.x, this.y, this.width, this.height);
      if (this.meta < 3) {

        ctx.font = parseInt(Math.min(Math.max(25 - this.name.length, 10), 20)) + "px Arial";
        ctx.fillStyle = "white";
        ctx.textAlign = "center";
        ctx.strokeStyle = 'black';

        if (this.meta === 1 && !this.moving) {
          ctx.lineWidth = 2;
          ctx.strokeRect(this.x + ss, this.y, ss, ss);
          ctx.strokeRect(this.x - ss, this.y, ss, ss);
          ctx.strokeRect(this.x, this.y + ss, ss, ss);
          ctx.strokeRect(this.x, this.y - ss, ss, ss);
        }

        ctx.lineWidth = 4;
        ctx.strokeText(this.name, this.x + ss / 2, this.y + ss - 2);
        ctx.fillText(this.name, this.x + ss / 2, this.y + ss - 2);

        if (this.meta === 1 && !this.moving && player_x === 0 && player_y === 0) {
          ctx.font = "20px Arial";
          ctx.fillStyle = "yellow";
          ctx.strokeText(moveInstructions, this.x + ss / 2, this.y + ss * 2.4);
          ctx.fillText(moveInstructions, this.x + ss / 2, this.y + ss * 2.4);
          ctx.strokeText(helpInstructions, this.x + ss / 2, this.y + ss * 2.4 + ss * 0.4);
          ctx.fillText(helpInstructions, this.x + ss / 2, this.y + ss * 2.4 + ss * 0.4);
        }
      }
    } else {
      ctx.fillStyle = color;
      ctx.fillRect(this.x, this.y, this.width, this.height);
    }
  };
  this.newPos = function () {
    var mS = 0.1;
    if (this.moving) {
      if (this.x > this.nX + 1) {
        this.x -= Math.max((this.x - this.nX) * mS, 1.0);
        this.moving = true;
      } else if (this.x < this.nX - 1) {
        this.x += Math.max((this.nX - this.x) * mS, 1.0);
        this.moving = true;
      } else if (this.y > this.nY + 1) {
        this.y -= Math.max((this.y - this.nY) * mS, 1.0);
        this.moving = true;
      } else if (this.y < this.nY - 1) {
        this.y += Math.max((this.nY - this.y) * mS, 1.0);
        this.moving = true;
      } else {
        if (this.moving == true) {
          this.moving = false;
          this.x = this.nX;
          this.y = this.nY;
          if (this.meta === 1 && (cX != 0 || cY != 0)) {
            center();
          }
        }
      }
    }
  };
}

function updateGameArea() {
  if (playersLoaded && locationsLoaded) {
    myGameArea.clear();
    myGameArea.context.drawImage(bgImage, 0, 0, w, h);

    for (index = 0; index < locations.length; index++) {
      locations[index].newPos();
      locations[index].update();
    }

    for (index = 0; index < players.length; index++) {
      players[index].newPos();
      players[index].update();
    }

    player.newPos();
    player.update();
  }
}

var getMonsters = function (newX, newY) {
  console.log("get monsters..");
  var location = locationsDict["" + newX + "," + newY];
  $("#location_box").show();
  $(".location_name").text(location.name);
  $("#location_image").attr("src", location.image.currentSrc);
  $("#location_description").text(location.description);

  $("#locationStatsPrimaryBtn").show();
  $("#locationStatsDisabledBtn").hide();
  $("#locationInfoBtn").show();
  $("#locationInfoDisabledBtn").hide();

  $("#moveSuccessBtn").hide();
  $("#moveDisabledBtn").show();

  var location_stats = location.stats;
  var location_fields = location_stats.split(";");
  $("#location_spawns").text("None");
  for (var index = 0; index < location_fields.length; index++) {
    var field = location_fields[index];
    if (field.startsWith("spawns")) {
      $("#location_spawns").text(
        field.split("=")[1].split(",").join(", ")
      );
    }
  }

  // no new location, but check for monsters
  $.ajax({
    url: "gameServer.php",
    type: "get",
    data:
      "get_monster=" +
      $("#room_id").text() +
      "&x=" +
      newX +
      "&y=" +
      newY,
    dataType: "json",
    success: function (response, status, http) {
      player.moving = true;
      if (response.length > 0) {
        var currentMonsterIdTemp = -1;
        $.each(response, function (index, item) {
          currentMonsterIdTemp = parseInt(item.id);
          currentMonster = new monster(
            item.name,
            item.description,
            item.stats,
            "uploads/" + item.image
          );
        });

        $("#monster_box").show();
        if (currentMonsterIdTemp != currentMonsterId) {
          currentMonsterId = currentMonsterIdTemp;
          $("#battle_log").empty();
          $("#winBattleBox").empty();
        }
        $(".monster_name").text(currentMonster.name);
        $("#monster_image").attr("src", currentMonster.image);
        $("#monster_description").text(currentMonster.description);

        var monster_fields = currentMonster.stats.split(";");
        for (var index = 0; index < monster_fields.length; index++) {
          var field = monster_fields[index];
          if (field.startsWith("drops")) {
            $("#monster_drops").text(
              field.split("=")[1].split(",").join(", ")
            );
          }
          if (field.startsWith("gold")) {
            $("#monster_gold").text(field.split("=")[1]);
          }
          else if (field.startsWith("exp")) {
            $("#monster_exp").text(field.split("=")[1]);
          }
          else if (field.startsWith("atk")) {
            $("#monster_atk").text(field.split("=")[1]);
          } else if (field.startsWith("def")) {
            $("#monster_def").text(field.split("=")[1]);
          } else if (field.startsWith("spd")) {
            $("#monster_spd").text(field.split("=")[1]);
          } else if (field.startsWith("evd")) {
            $("#monster_evd").text(field.split("=")[1]);
          } else if (field.startsWith("hp")) {
            $(".monster_hp").text(field.split("=")[1]);
            $(".monster_hp_progress").attr("value", field.split("=")[1]);
          } else if (field.startsWith("maxhp")) {
            $(".monster_maxhp").text(field.split("=")[1]);
            $(".monster_hp_progress").attr("max", field.split("=")[1]);
          }
        }
        canMove = true;
      } else {
        canMove = true;
        currentMonster = null;
        $("#monster_box").hide();
      }
    },
    error: function (http, status, error) {
      canMove = true;
      console.error("error: " + error);
    },
  });
}

var center = function () {
  if (playersLoaded && locationsLoaded) {
    for (index = 0; index < locations.length; index++) {
      locations[index].nX = locations[index].x - cX * ss;
      locations[index].nY = locations[index].y - cY * ss;
      locations[index].moving = true;
    }

    for (index = 0; index < players.length; index++) {
      players[index].nX = player.x + parseInt(playersDict[players[index].id].x - player_x) * ss - cX * ss;
      players[index].nY = player.y + parseInt(playersDict[players[index].id].y - player_y) * ss - cY * ss;
      players[index].moving = true;
    }

    player.nX = player.x - cX * ss;
    player.nY = player.y - cY * ss;
    player.moving = true;

    oX += cX;
    oY += cY;
    cX = 0;
    cY = 0;
  }
}

function anythingMoving() {
  for (index = 0; index < locations.length; index++) {
    if (locations[index].moving) {
      return true;
    }
  }

  for (index = 0; index < players.length; index++) {
    if (players[index].moving) {
      return true;
    }
  }

  return false;
}

function move(dir) {
  if (player.moving || anythingMoving())
    return;




  if (canMove === true) {
    console.log("move " + dir);
    getAllPlayers();
    canMove = false;

    var room_id = $("#room_id").text();
    var nx = player_x;
    var ny = player_y;

    if (dir === "up") {
      ny = ny - 1;
    }
    if (dir === "down") {
      ny = ny + 1;
    }
    if (dir === "left") {
      nx = nx - 1;
    }
    if (dir === "right") {
      nx = nx + 1;
    }

    $.ajax({
      url: "gameServer.php",
      type: "get",
      data:
        "move_player=" +
        $("#player").text() +
        "&room_id=" +
        room_id +
        "&x=" +
        nx +
        "&y=" +
        ny,
      dataType: "json",
      success: function (response, status, http) {
        console.log(response[0]);
        if (response[0] == "ok") {
          var dx = parseInt(response[1]);
          var dy = parseInt(response[2]);

          if (dy === -1) {

            player.nX = player.x;
            player.nY = player.y - ss;
            cY--;
          }
          if (dy === 1) {

            player.nX = player.x;
            player.nY = player.y + ss;
            cY++;
          }
          if (dx === -1) {

            player.nX = player.x - ss;
            player.nY = player.y;
            cX--;
          }
          if (dx === 1) {

            player.nX = player.x + ss;
            player.nY = player.y;
            cX++;
          }

          // if (dx !== 0 || dy !== 0) {
          //   playSound("uploads/running-in-grass-6237.mp3");
          // }

          player_x = player_x + dx;
          player_y = player_y + dy;
          $("#player_x").text(player_x);
          $("#player_y").text(player_y);
          $("#player_bx").text(bX(player_x));
          $("#player_by").text(bY(player_y));



          if (response[3] === "draw") {
            // new location
            $.ajax({
              url: "gameServer.php",
              type: "get",
              data:
                "get_location=" +
                $("#room_id").text() +
                "&x=" +
                player_x +
                "&y=" +
                player_y,
              dataType: "json",
              success: function (response, status, http) {
                if (response.length > 0) {

                  var landscape = new component(
                    -1,
                    ss,
                    ss,
                    "uploads/" + response[0].image,
                    player.nX,
                    player.nY,
                    "image",
                    response[0].name,
                    response[0].description,
                    response[0].stats,
                    3
                  );

                  locations.push(landscape);
                  locationsDict["" + response[0].x + "," + response[0].y] =
                    landscape;

                  getMonsters(player_x, player_y);
                }
                else {
                  canMove = true;
                }
              },
              error: function (http, status, error) {
                canMove = true;
                console.error("error: " + error);
              },
            });
            console.log("new location, adding");
          } else {

            var location = locationsDict["" + player_x + "," + player_y];

            if (!location) {
              console.log("no new location, but exists in db, get all locations");
              getAllLocations(player_x, player_y, dx, dy);
            }
            else {
              console.log("no new location, but get monsters")
              getMonsters(player_x, player_y);
            }


          }
        } else if (response[0] == "fight") {
          console.log(response);

          var first = true;
          var wasSlain = false;
          var died = false;
          $.each(response, function (index, item) {
            if (first) {
              first = false;
            }
            else {
              $("#battle_log").prepend("<span>" + item + '</span><br/>');
              if (wasSlain || item.includes(" was slain!")) {
                wasSlain = true;
                $("#winBattleBox").append("<span>" + item + '</span><br/>');
              }
              else if (item.includes(" died.")) {
                died = true;
              }
            }
          });

          if (died) {
            document.getElementById('lose-dialog').showModal();
          }
          else if (wasSlain) {
            playSound("uploads/good-6081.mp3");
            document.getElementById('win-dialog').showModal();
          }
          else {
            playSound("uploads/sword-hit-7160.mp3");
          }
          getPlayer(false);
          canMove = true;
          move('na');


        } else {
          canMove = true;
          console.error(response[1]);
        }
      },
      error: function (http, status, error) {
        canMove = true;
        console.error("error: " + error);
      },
    });
  }
}

// function clearmove() {
//   player.image.src = "uploads/p_female_warrior.png";
//   player.speedX = 0;
//   player.speedY = 0;
// }

document.onkeydown = function (event) {
  switch (event.keyCode) {
    case 37:
      move("left");
      break;
    case 38:
      move("up");
      break;
    case 39:
      move("right");
      break;
    case 40:
      move("down");
      break;
    case 65:
      move("left");
      break;
    case 87:
      move("up");
      break;
    case 68:
      move("right");
      break;
    case 83:
      move("down");
      break;
    case 67:
      toggleStats();
      break;
    case 73:
      toggleItemsTable();
      break;
    case 90:
      toggleLocationInfo();
      break;
    case 88:
      toggleLocationStats();
      break;
    case 86:
      if ($("#monster_box").is(':visible')) {
        toggleMonsterInfo();
      }
      break;
    case 66:
      if ($("#monster_box").is(':visible')) {
        toggleMonsterStats();
      }
      break;
    case 78:
      if ($("#monster_box").is(':visible')) {
        toggleBattleLog();
      }
      break;
    case 77:
      if ($("#monster_box").is(':visible')) {
        attackMonster();
      }
      break;
      // case 13:
      //   if ($("#monster_box").is(':visible')) {
      //     attackMonster();
      //   }
      //   break;
    case 72:
      if ($("#help-dialog").is(':visible')) {
        $("#close_help_btn").click();
      }
      else {
        playSound("uploads/click-21156.mp3");
        document.getElementById('help-dialog').showModal();
      }
      break;
    
  }
};
