<?
session_start();
require_once "../helper.php";
?>
<!DOCTYPE html>
<html>

<head>
  <title>PalStory - Create</title>
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
      padding: 10px;
    }

    .left {
      width: 40%;
    }

    .right {
      width: 60%;
    }


    /* Clear floats after the columns */
    .row:after {
      content: "";
      display: table;
      clear: both;
    }

    #category,
    #resources {
      width: 100%;
      height: 100%;
    }

    input,
    textarea {

      background-color: #222222;
      color: #fff;

    }

    td {
      vertical-align: top;
    }

    th {
      text-align: left;
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

    a.ylink:link { color: #ffeb3b; }
    a.ylink:visited {color: #ffeb3b; }
    a.ylink:hover {color: #fdd835;}
    a.ylink:active {color: #fbc02d;}

  </style>
</head>

<body id="top" class="w3-theme-l1">

<div style="position: fixed; bottom: 0; right: 0; padding: 10px; background: rgba(0,0,0,0.5);">
<a class="ylink" href="#top">Top</a>
</div>

<?
if (isset($_POST["logout"]) && !empty($_POST['logout'])) {
  $_SESSION['secret'] = '';
}
else if (isset($_POST["secret"]) && !empty($_POST['secret'])) {
  $_SESSION['secret'] = $_POST["secret"];
}

if (!isset($_SESSION['secret']) || ($_SESSION['secret'] != admin_game() && $_SESSION['secret'] != super_admin_game())) {
?>
<div style="background-color: #222222">
    <b><a href="/story/">Play game</a></b>
    <div style="float: right"><a href="/">Home</a></div>
  </div>
<div id="login_box">
<form action="/story/create.php" method="post">
  <label for="secret">Password:</label><br>
  <input type="password" id="secret" name="secret" value="" autofocus><br/><br/>
  <input type="submit" value="Submit">
</form>
</div>
<?
} else {
?>

  <div style="background-color: #222222">
    <b><a href="/story/">Play game</a></b> |
    <a href="https://chat.openai.com/chat" target="_blank">ChatGPT</a> |
    <!-- <a href="https://www.bing.com/" target="_blank">Bing</a> | -->
    <!-- <a href="https://labs.openai.com/" target="_blank">DALL-E</a> -->
    <!-- <a href="https://www.bing.com/create" target="_blank">Bing create</a> -->
    <!-- <a href="https://www.midjourney.com/" target="_blank">Midjourney</a> -->
    <a href="https://creator.nightcafe.studio/" target="_blank">NightCafe</a>
    <!-- <a href="https://soundraw.io/" target="_blank">SOUNDRAW</a> -->
    <div style="float: right"><a href="/">Home</a></div>
    <div style="float: right">
    <form name="logoutForm" action="/story/create.php" method="post">
    <input style="display: none" type="password" id="logout" name="logout" value="1">
    <!-- <input type="submit" value="Logout"> -->
    <a href="javascript:document.forms['logoutForm'].submit()">Log out</a> |&nbsp;
    </form>
    </div> 
    
  </div>

  <div>

    <div class="row">
      <div class="column left">
        <h1>Create</h1>

        <a class="ylink" href="#create_locations_section">Locations</a>
        <a class="ylink" href="#create_monsters_section">Monsters</a>
        <a class="ylink" href="#create_items_section">Items</a>

        <div id="create_location_section">
          <h2>Locations</h2>
          <label for="location_name">Location name:</label>
          <br />
          <input type="text" id="location_name" name="location_name" placeholder="Forest" size="40">
          <br />
          <label for="location_image">Location image:</label>
          <br />
          <input type="text" id="location_image" name="location_image" placeholder="forest.png" size="40">
          <br />
          <label for="location_description">Location description:</label>
          <br />
          <textarea id="location_description" name="location_description" rows="4" cols="50"
            placeholder="An agglomeration of lush green dark canopies of adjacent trees which merge into one another."></textarea>
          <br />
          <label for="location_range">Level range:</label>
          <div id="location_range">
            From <input type="number" id="location_from" name="location_from" min="1" max="999999999" value="1"> to
            <input type="number" id="location_to" name="location_to" min="1" max="999999999" value="5">
          </div>
          <label for="location_stats">Location stats:</label>
          <i style="color:#999; font-size:12px;">spawns=Slime,Shroom;</i><br/>
          <textarea id="location_stats" name="location_stats" rows="4" cols="50">spawns=</textarea>
          <br />
          <button id="location_save" onclick="saveLocation()">Save location</button>
          <span id="location_error" style="color: red;"></span>
        </div>

        <div id="create_monsters_section">
          <h2>Monsters</h2>
          <label for="monster_name">Monster name:</label>
          <br />
          <input type="text" id="monster_name" name="monster_name" placeholder="Slime" size="40">
          <br />
          <label for="monster_image">Monster image:</label>
          <br />
          <input type="text" id="monster_image" name="monster_image" placeholder="slime.png" size="40">
          <br />
          <label for="monster_description">Monster description:</label>
          <br />
          <textarea id="monster_description" name="monster_description" rows="4" cols="50"
            placeholder="Slimes are gelatinous monsters that hunt by submerging their prey in their body to be slowly dissolved by strong acids."></textarea>
          <br />
          <label for="monster_stats">Monster stats:</label>
          <span><input style="width: 80px;" type="number" id="monster_generate_level" name="monster_generate_level" value="1"><button onclick="generateMonsterStats()">Generate</button></span>
          <i style="color:#999; font-size:12px;">...;drops=Sword,Shield;</i><br/>
          <textarea id="monster_stats" name="monster_stats" rows="4" cols="50"></textarea>
          <br />
          <button id="monster_save" onclick="saveMonster()">Save monster</button>
          <span id="monster_error" style="color: red;"></span>
        </div>

        <div id="create_items_section">
          <h2>Items</h2>
          <label for="item_name">Item name:</label>
          <br />
          <input type="text" id="item_name" name="item_name" placeholder="Sword" size="40">
          <br />
          <label for="item_image">Item image:</label>
          <br />
          <input type="text" id="item_image" name="item_image" placeholder="sword.png" size="40">
          <br />
          <label for="item_description">Item description:</label>
          <br />
          <textarea id="item_description" name="item_description" rows="4" cols="50"
            placeholder="An edged, bladed weapon intended for manual cutting or thrusting."></textarea>
          <br />
          <label for="item_stats">Item stats:</label>
          <span><input style="width: 150px;" type="text" id="item_generate_type" name="item_generate_type" placeholder="type"><input style="width: 80px;" type="number" id="item_generate_level" name="item_generate_level" value="1"><button onclick="generateItemStats()">Generate</button></span>
          <!-- <i style="color:#999; font-size:12px;">Example: type=weapon;atk=8-10;def=0-1;spd=4-5;evd=2-4;</i><br/> -->
          <textarea id="item_stats" name="item_stats" rows="4" cols="50"></textarea>
          <br />
          <button id="item_save" onclick="saveItem()">Save item</button>
          <span id="item_error" style="color: red;"></span>
        </div>

      </div>
      <div class="column right">
        <h1>Resources</h1>
        <?
        require_once "../config.php";
        require_once "../helper.php";
        ?>
        <a class="ylink" href="#locations_section">Locations</a>
        <a class="ylink" href="#monsters_section">Monsters</a>
        <a class="ylink" href="#items_section">Items</a>

        <h2>Images</h2>

        <form action="upload.php" method="post" enctype="multipart/form-data">
          Select image to upload:
          <input type="file" name="fileToUpload" id="fileToUpload">
          <input type="submit" value="Upload Image" name="submit">
        </form>

        <table>
          <tr>
            <th>Link</th>
            <th>Name</th>
          </tr>

          <?
          $log_directory = 'uploads';

          $results_array = array();

          if (is_dir($log_directory)) {
            if ($handle = opendir($log_directory)) {
              //Notice the parentheses I added:
              while (($file = readdir($handle)) !== FALSE) {
                $results_array[] = $file;
              }
              closedir($handle);
            }
          }

          //Output findings
          for ($i = 0; $i < count($results_array); $i++) {
            if ($i > 1) {
              echo '<tr><td><a href="/story/uploads/' . $results_array[$i] . '" target="_blank">View</a></td><td>' . $results_array[$i] . '</td></tr>';
            }
          }
          ?>
        </table>

        <h2 id="locations_section">Locations</h2>
        <table>
          <tr>
            <th>Name</th>
            <th>Image</th>
            <th>Description</th>
            <th>From</th>
            <th>To</th>
            <th>Stats</th>
            <th>Banned</th>
          </tr>
          <?php
          $selectstmt = $db->prepare("SELECT * FROM resources_locations");
          $arr = array();
          if ($selectstmt->execute()) {
            $result = $selectstmt->get_result();
            $row_count = mysqli_num_rows($result);
            if ($row_count > 0) {
              while ($row = mysqli_fetch_array($result)) {
                ?>
                <tr>
                  <td>
                    <? echo $row[1]; ?>
                  </td>
                  <td><a href="/story/uploads/<? echo $row[2]; ?>" target="_blank">View</a></td>
                  <td>
                    <div title="<? echo $row[3]; ?>"><? echo substr($row[3], 0, 10) . "..."; ?></div>
                  </td>
                  <td>
                    <? echo $row[4]; ?>
                  </td>
                  <td>
                    <? echo $row[5]; ?>
                  </td>
                  <td>
                    <div title="<? echo $row[6]; ?>"><? echo substr($row[6], 0, 10) . "..."; ?></div>
                  </td>
                  <td>
                    <? echo $row[7] == 1 ? "yes" : "no"; ?>
                    <? if ($row[7] == 0 && $_SESSION['secret'] == super_admin_game()) { ?>
                      <button onclick="banLocation(<? echo $row[0]; ?>, '<? echo $_SESSION['secret'] ?>')">Ban</button>
                    <? } ?>
                    <? if ($row[7] == 1 && $_SESSION['secret'] == admin_game()) { ?>
                      <button onclick="deleteLocation(<? echo $row[0]; ?>, '<? echo $_SESSION['secret'] ?>')">Delete</button>
                    <? } ?>
                  </td>
                </tr>
              <?
              }
            }
          }
          $selectstmt->close();
          ?>
        </table>

        <h2 id="monsters_section">Monsters</h2>
        <table>
          <tr>
            <th>Name</th>
            <th>Image</th>
            <th>Description</th>
            <th>Stats</th>
            <th>Banned</th>
          </tr>
          <?php
          $selectstmt = $db->prepare("SELECT * FROM resources_monsters");
          $arr = array();
          if ($selectstmt->execute()) {
            $result = $selectstmt->get_result();
            $row_count = mysqli_num_rows($result);
            if ($row_count > 0) {
              while ($row = mysqli_fetch_array($result)) {
                ?>
                <tr>
                  <td>
                    <? echo $row[1]; ?>
                  </td>
                  <td><a href="/story/uploads/<? echo $row[2]; ?>" target="_blank">View</a></td>
                  <td>
                    <div title="<? echo $row[3]; ?>"><? echo substr($row[3], 0, 10) . "..."; ?></div>
                  </td>
                  <td>
                    <div title="<? echo $row[4]; ?>"><? echo substr($row[4], 0, 10) . "..."; ?></div>
                  </td>
                  <td>
                    <? echo $row[5] == 1 ? "yes" : "no"; ?>
                    <? if ($row[5] == 0 && $_SESSION['secret'] == super_admin_game()) { ?>
                      <button onclick="banMonster(<? echo $row[0]; ?>, '<? echo $_SESSION['secret'] ?>')">Ban</button>
                    <? } ?>
                    <? if ($row[5] == 1 && $_SESSION['secret'] == admin_game()) { ?>
                      <button onclick="deleteMonster(<? echo $row[0]; ?>, '<? echo $_SESSION['secret'] ?>')">Delete</button>
                    <? } ?>
                  </td>
                </tr>
              <?
              }
            }
          }
          $selectstmt->close();
          ?>
        </table>

        <h2 id="items_section">Items</h2>
        <table>
          <tr>
            <th>Name</th>
            <th>Image</th>
            <th>Description</th>
            <th>Stats</th>
            <th>Banned</th>
          </tr>
          <?php
          $selectstmt = $db->prepare("SELECT * FROM resources_items");
          $arr = array();
          if ($selectstmt->execute()) {
            $result = $selectstmt->get_result();
            $row_count = mysqli_num_rows($result);
            if ($row_count > 0) {
              while ($row = mysqli_fetch_array($result)) {
                ?>
                <tr>
                  <td>
                    <? echo $row[1]; ?>
                  </td>
                  <td><a href="/story/uploads/<? echo $row[2]; ?>" target="_blank">View</a></td>
                  <td>
                    <div title="<? echo $row[3]; ?>"><? echo substr($row[3], 0, 10) . "..."; ?></div>
                  </td>
                  <td>
                    <div title="<? echo $row[4]; ?>"><? echo substr($row[4], 0, 10) . "..."; ?></div>
                  </td>
                  <td>
                    <? echo $row[5] == 1 ? "yes" : "no"; ?>
                    <? if ($row[5] == 0 && $_SESSION['secret'] == super_admin_game()) { ?>
                      <button onclick="banItem(<? echo $row[0]; ?>, '<? echo $_SESSION['secret'] ?>')">Ban</button>
                    <? } ?>
                    <? if ($row[5] == 1 && $_SESSION['secret'] == admin_game()) { ?>
                      <button onclick="deleteItem(<? echo $row[0]; ?>, '<? echo $_SESSION['secret'] ?>')">Delete</button>
                    <? } ?>
                  </td>
                </tr>
              <?
              }
            }
          }
          $selectstmt->close();
          ?>
        </table>

        <?
        mysqli_close($db);
        ?>


      </div>
    </div>

  </div>
<?
}
?>
</body>

</html>

<script src="jquery-2.2.4.min.js"></script>
<script src="create.js"></script>