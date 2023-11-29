<?php
header("Access-Control-Allow-Origin: *");

require_once "../config.php";
require_once "../helper.php";

function parseItemStats($stats)
{
  $statparts = explode(';', $stats);
  $truestats = "";
  $atkSet = false;
  $defSet = false;
  $spdSet = false;
  $evdSet = false;
  $typeSet = false;

  for ($i = 0; $i < count($statparts); $i++) {
    if ($atkSet == false && str_starts_with($statparts[$i], 'atk=')) {
      $atkSet = true;
      $statval = explode('=', $statparts[$i])[1];
      if (str_contains($statval, '-')) {
        $truestats = $truestats . "atk=" . rand(intval(explode('-', $statval)[0]), intval(explode('-', $statval)[1])) . ";";
      } else {
        $truestats = $truestats . "atk=" . $statval . ";";
      }
    }
    if ($defSet == false && str_starts_with($statparts[$i], 'def=')) {
      $statval = explode('=', $statparts[$i])[1];
      if (str_contains($statval, '-')) {
        $truestats = $truestats . "def=" . rand(intval(explode('-', $statval)[0]), intval(explode('-', $statval)[1])) . ";";
      } else {
        $truestats = $truestats . "def=" . $statval . ";";
      }
    }
    if ($spdSet == false && str_starts_with($statparts[$i], 'spd=')) {
      $spdSet = true;
      $statval = explode('=', $statparts[$i])[1];
      if (str_contains($statval, '-')) {
        $truestats = $truestats . "spd=" . rand(intval(explode('-', $statval)[0]), intval(explode('-', $statval)[1])) . ";";
      } else {
        $truestats = $truestats . "spd=" . $statval . ";";
      }
    }
    if ($evdSet == false && str_starts_with($statparts[$i], 'evd=')) {
      $evdSet = true;
      $statval = explode('=', $statparts[$i])[1];
      if (str_contains($statval, '-')) {
        $truestats = $truestats . "evd=" . rand(intval(explode('-', $statval)[0]), intval(explode('-', $statval)[1])) . ";";
      } else {
        $truestats = $truestats . "evd=" . $statval . ";";
      }
    }
    if ($typeSet == false && str_starts_with($statparts[$i], 'type=')) {
      $typeSet = true;
      $statval = explode('=', $statparts[$i])[1];
      $truestats = $truestats . "type=" . $statval . ";";
    }
  }

  return $truestats;
}

function parseMonsterStats($stats)
{
  $statparts = explode(';', $stats);
  $truestats = "";
  $atkSet = false;
  $defSet = false;
  $spdSet = false;
  $evdSet = false;
  $dropsSet = false;
  $hpSet = false;
  $goldSet = false;
  $expSet = false;

  for ($i = 0; $i < count($statparts); $i++) {
    if ($atkSet == false && str_starts_with($statparts[$i], 'atk=')) {
      $atkSet = true;
      $statval = explode('=', $statparts[$i])[1];
      if (str_contains($statval, '-')) {
        $truestats = $truestats . "atk=" . rand(intval(explode('-', $statval)[0]), intval(explode('-', $statval)[1])) . ";";
      } else {
        $truestats = $truestats . "atk=" . $statval . ";";
      }
    }
    if ($defSet == false && str_starts_with($statparts[$i], 'def=')) {
      $statval = explode('=', $statparts[$i])[1];
      if (str_contains($statval, '-')) {
        $truestats = $truestats . "def=" . rand(intval(explode('-', $statval)[0]), intval(explode('-', $statval)[1])) . ";";
      } else {
        $truestats = $truestats . "def=" . $statval . ";";
      }
    }
    if ($spdSet == false && str_starts_with($statparts[$i], 'spd=')) {
      $spdSet = true;
      $statval = explode('=', $statparts[$i])[1];
      if (str_contains($statval, '-')) {
        $truestats = $truestats . "spd=" . rand(intval(explode('-', $statval)[0]), intval(explode('-', $statval)[1])) . ";";
      } else {
        $truestats = $truestats . "spd=" . $statval . ";";
      }
    }
    if ($evdSet == false && str_starts_with($statparts[$i], 'evd=')) {
      $evdSet = true;
      $statval = explode('=', $statparts[$i])[1];
      if (str_contains($statval, '-')) {
        $truestats = $truestats . "evd=" . rand(intval(explode('-', $statval)[0]), intval(explode('-', $statval)[1])) . ";";
      } else {
        $truestats = $truestats . "evd=" . $statval . ";";
      }
    }
    if ($dropsSet == false && str_starts_with($statparts[$i], 'drops=')) {
      $dropsSet = true;
      $statval = explode('=', $statparts[$i])[1];
      $truestats = $truestats . "drops=" . $statval . ";";
    }
    if ($hpSet == false && str_starts_with($statparts[$i], 'hp=')) {
      $hpSet = true;
      $statval = explode('=', $statparts[$i])[1];
      if (str_contains($statval, '-')) {
        $hp = rand(intval(explode('-', $statval)[0]), intval(explode('-', $statval)[1]));
        $truestats = $truestats . "hp=" . $hp . ";";
        $truestats = $truestats . "maxhp=" . $hp . ";";
      } else {
        $truestats = $truestats . "hp=" . $statval . ";";
        $truestats = $truestats . "maxhp=" . $statval . ";";
      }
    }
    if ($goldSet == false && str_starts_with($statparts[$i], 'gold=')) {
      $goldSet = true;
      $statval = explode('=', $statparts[$i])[1];
      if (str_contains($statval, '-')) {
        $truestats = $truestats . "gold=" . rand(intval(explode('-', $statval)[0]), intval(explode('-', $statval)[1])) . ";";
      } else {
        $truestats = $truestats . "gold=" . $statval . ";";
      }
    }
    if ($expSet == false && str_starts_with($statparts[$i], 'exp=')) {
      $expSet = true;
      $statval = explode('=', $statparts[$i])[1];
      $truestats = $truestats . "exp=" . $statval . ";";
    }
  }
  return $truestats;
}

function setPlayerStats($lvl, $exp, $hp, $maxhp, $atk, $def, $spd, $evd, $gold)
{
  return "lvl=" . $lvl . ";exp=" . $exp . ";hp=" . $hp . ";maxhp=" . $maxhp . ";atk=" . $atk . ";def=" . $def . ";spd=" . $spd . ";evd=" . $evd . ";gold=" . $gold . ";";
}

function setMonsterStats($hp, $maxhp, $atk, $def, $spd, $evd, $drops, $gold, $exp)
{
  return "hp=" . $hp . ";maxhp=" . $maxhp . ";atk=" . $atk . ";def=" . $def . ";spd=" . $spd . ";evd=" . $evd . ";drops=" . $drops . ";gold=" . $gold . ";exp=" . $exp . ";";
}

function verifyLocationStats($locationStats)
{
  return $locationStats . ";";
}

function fightMonster($db, $data, $itemDropRate)
{
  $player_name = clean($data['fight_monster']);
  $room_id = intval(clean($data['room_id']));

  $sp = $db->prepare("SELECT * 
				FROM game_players 
				WHERE room_id = ? AND name = ?");
  $sp->bind_param("is", $room_id, $player_name);

  $arr = array();

  if ($sp->execute()) {

    $rp = $sp->get_result();
    $rpc = mysqli_num_rows($rp);
    if ($rpc > 0) {

      while ($rprow = mysqli_fetch_array($rp)) {
        $player_id = intval($rprow["id"]);
        $player_stats = $rprow["stats"];
        $player_x = intval($rprow["x"]);
        $player_y = intval($rprow["y"]);
        break;
      }

      $player_stats_parts = explode(';', $player_stats);
      for ($i = 0; $i < count($player_stats_parts); $i++) {
        if (str_starts_with($player_stats_parts[$i], "lvl=")) {
          $player_lvl = intval(explode('=', $player_stats_parts[$i])[1]);
        }
        if (str_starts_with($player_stats_parts[$i], "exp=")) {
          $player_exp = intval(explode('=', $player_stats_parts[$i])[1]);
        }
        if (str_starts_with($player_stats_parts[$i], "atk=")) {
          $player_atk = intval(explode('=', $player_stats_parts[$i])[1]);
        }
        if (str_starts_with($player_stats_parts[$i], "def=")) {
          $player_def = intval(explode('=', $player_stats_parts[$i])[1]);
        }
        if (str_starts_with($player_stats_parts[$i], "spd=")) {
          $player_spd = intval(explode('=', $player_stats_parts[$i])[1]);
        }
        if (str_starts_with($player_stats_parts[$i], "evd=")) {
          $player_evd = intval(explode('=', $player_stats_parts[$i])[1]);
        }
        if (str_starts_with($player_stats_parts[$i], "hp=")) {
          $player_hp = intval(explode('=', $player_stats_parts[$i])[1]);
        }
        if (str_starts_with($player_stats_parts[$i], "maxhp=")) {
          $player_maxhp = intval(explode('=', $player_stats_parts[$i])[1]);
        }
        if (str_starts_with($player_stats_parts[$i], "gold=")) {
          $player_gold = intval(explode('=', $player_stats_parts[$i])[1]);
        }
      }

      $itemAtk = 0;
      $itemDef = 0;
      $itemSpd = 0;
      $itemEvd = 0;

      $se = $db->prepare("SELECT * 
				FROM game_items WHERE owner_id = ? AND equipped = 1");
      $se->bind_param("i", $player_id);
      if ($se->execute()) {
        $r = $se->get_result();
        $rc = mysqli_num_rows($r);
        if ($rc > 0) {
          while ($row = mysqli_fetch_array($r)) {
            $item_stats_parts = explode(';', $row["stats"]);
            for ($i = 0; $i < count($item_stats_parts); $i++) {
              if (str_starts_with($item_stats_parts[$i], "atk=")) {
                $itemAtk += intval(explode('=', $item_stats_parts[$i])[1]);
              }
              if (str_starts_with($item_stats_parts[$i], "def=")) {
                $itemDef += intval(explode('=', $item_stats_parts[$i])[1]);
              }
              if (str_starts_with($item_stats_parts[$i], "spd=")) {
                $itemSpd += intval(explode('=', $item_stats_parts[$i])[1]);
              }
              if (str_starts_with($item_stats_parts[$i], "evd=")) {
                $itemEvd += intval(explode('=', $item_stats_parts[$i])[1]);
              }
            }
          }
        }
      }
      $se->close();

      $sm = $db->prepare("SELECT gm.id, gm.stats, rm.name  
				FROM game_monsters gm INNER JOIN resources_monsters rm ON gm.resource_id = rm.id 
				WHERE gm.room_id = ? AND gm.x = ? AND gm.y = ?");
      $sm->bind_param("iii", $room_id, $player_x, $player_y);

      if ($sm->execute()) {

        $rm = $sm->get_result();
        $rmc = mysqli_num_rows($rm);
        if ($rmc > 0) {

          while ($rmrow = mysqli_fetch_array($rm)) {
            $monster_id = intval($rmrow["id"]);
            $monster_stats = $rmrow["stats"];
            $monster_name = $rmrow["name"];
            break;
          }

          $monster_stats_parts = explode(';', $monster_stats);
          for ($i = 0; $i < count($monster_stats_parts); $i++) {
            if (str_starts_with($monster_stats_parts[$i], "atk=")) {
              $monster_atk = intval(explode('=', $monster_stats_parts[$i])[1]);
            }
            if (str_starts_with($monster_stats_parts[$i], "def=")) {
              $monster_def = intval(explode('=', $monster_stats_parts[$i])[1]);
            }
            if (str_starts_with($monster_stats_parts[$i], "spd=")) {
              $monster_spd = intval(explode('=', $monster_stats_parts[$i])[1]);
            }
            if (str_starts_with($monster_stats_parts[$i], "evd=")) {
              $monster_evd = intval(explode('=', $monster_stats_parts[$i])[1]);
            }
            if (str_starts_with($monster_stats_parts[$i], "hp=")) {
              $monster_hp = intval(explode('=', $monster_stats_parts[$i])[1]);
            }
            if (str_starts_with($monster_stats_parts[$i], "maxhp=")) {
              $monster_maxhp = intval(explode('=', $monster_stats_parts[$i])[1]);
            }
            if (str_starts_with($monster_stats_parts[$i], "drops=")) {
              $monster_drops = explode('=', $monster_stats_parts[$i])[1];
            }
            if (str_starts_with($monster_stats_parts[$i], "gold=")) {
              $monster_gold = intval(explode('=', $monster_stats_parts[$i])[1]);
            }
            if (str_starts_with($monster_stats_parts[$i], "exp=")) {
              $monster_exp = intval(explode('=', $monster_stats_parts[$i])[1]);
            }
          }

          if ($player_evd + $itemEvd >= $monster_evd) {
            $monster_dodge = 1;
            $player_dodge = min(99, ($player_evd + $itemEvd) / $monster_evd);
          } else {
            $player_dodge = 1;
            $monster_dodge = min(99, $monster_evd / ($player_evd + $itemEvd));
          }

          // fight here
          for ($i = min(($player_spd + $itemSpd), $monster_spd); $i <= max(($player_spd + $itemSpd), $monster_spd); $i++) {
            if ($i % $monster_spd == 0) {

              if (rand(0, 100) > $monster_dodge) {
                // player attack
                $player_force = ($player_atk + $itemAtk) + rand(0, ($player_atk + $itemAtk));
                $monster_force = $monster_def + rand(0, $monster_def);
                $hit = max(0, $player_force - $monster_force);
                $monster_hp = $monster_hp - $hit;
                array_push($arr, $player_name . " hits for " . $hit . " damage.");
                if ($monster_hp <= 0) {
                  array_push($arr, $monster_name . " was slain!");

                  // drops
                  $monster_drops_parts = explode(',', $monster_drops);
                  if (rand(1, 100) <= $itemDropRate) {
                    $spawnItemName = trim($monster_drops_parts[rand(0, count($monster_drops_parts) - 1)], " ");

                    $sir = $db->prepare("SELECT id, stats, name FROM resources_items WHERE name = ?");
                    $sir->bind_param("s", $spawnItemName);

                    if ($sir->execute()) {

                      $sires = $sir->get_result();
                      $sircnt = mysqli_num_rows($sires);
                      if ($sircnt > 0) {
                        while ($sirrow = mysqli_fetch_array($sires)) {
                          $item_resource_id = intval($sirrow["id"]);
                          $item_resource_stats = parseItemStats($sirrow["stats"]);
                          $item_resource_name = $sirrow["name"];

                          array_push($arr, "Dropped " . $item_resource_name . "!");

                          $ii = $db->prepare("INSERT INTO game_items( room_id, stats, resource_id, owner_id ) 
				VALUES(?, ?, ?, ?)");
                          $ii->bind_param("isii", $room_id, $item_resource_stats, $item_resource_id, $player_id);
                          $ii->execute();
                          $ii->close();

                          break;
                        }
                      }
                    }
                    $sir->close();

                  }

                  array_push($arr, "Gained " . $monster_gold . " gold!");
                  array_push($arr, "Gained " . $monster_exp . " EXP!");

                  $player_gold = $player_gold + $monster_gold;
                  $player_exp = $player_exp + $monster_exp;

                  $lvl_cutoff = 10 + 3 * $player_lvl + pow(10, 0.01 * $player_lvl);
                  if ($player_exp >= $lvl_cutoff) {
                    $player_exp = intval($player_exp - $lvl_cutoff);
                    $player_lvl = $player_lvl + 1;

                    // stat increase
                    $stat_incr = rand(1, 4);
                    if ($stat_incr == 1) {
                      $player_atk += 1;
                    } else if ($stat_incr == 2) {
                      $player_def += 1;
                    } else if ($stat_incr == 3) {
                      $player_spd += 1;
                    } else if ($stat_incr == 4) {
                      $player_evd += 1;
                    }

                    $player_maxhp += 10 + intval($player_maxhp * 0.01);
                    $player_hp = $player_maxhp;
                  }

                  // update player stats
                  $up = $db->prepare("UPDATE game_players SET stats = ? WHERE id = ?");
                  $player_stats_str = setPlayerStats($player_lvl, $player_exp, $player_hp, $player_maxhp, $player_atk, $player_def, $player_spd, $player_evd, $player_gold);
                  $up->bind_param("si", $player_stats_str, $player_id);
                  $up->execute();
                  $up->close();

                  $dm = $db->prepare("DELETE FROM game_monsters WHERE id = ?");
                  $dm->bind_param("i", $monster_id);
                  $dm->execute();
                  $dm->close();
                  break;
                } else {
                  // update monster stats
                  $um = $db->prepare("UPDATE game_monsters SET stats = ? WHERE id = ?");
                  $monster_stats_str = setMonsterStats($monster_hp, $monster_maxhp, $monster_atk, $monster_def, $monster_spd, $monster_evd, $monster_drops, $monster_gold, $monster_exp);
                  $um->bind_param("si", $monster_stats_str, $monster_id);
                  $um->execute();
                  $um->close();
                }
              } else {
                array_push($arr, $player_name . " missed!");
              }
            }
            if ($i % ($player_spd + $itemSpd) == 0) {

              if (rand(0, 100) > $player_dodge) {
                // monster attack
                $monster_force = $monster_atk + rand(0, $monster_atk);
                $player_force = ($player_def + $itemDef) + rand(0, ($player_def + $itemDef));
                $hit = max(0, $monster_force - $player_force);
                $player_hp = $player_hp - $hit;
                array_push($arr, $monster_name . " hits for " . $hit . " damage.");
                if ($player_hp <= 0) {

                  array_push($arr, $player_name . " died.");
                  $reset_player_x = 0;
                  $reset_player_y = 0;
                  $player_hp = $player_maxhp;
                  $player_exp = 0;
                  $player_gold = 0;

                  // update player stats
                  $up = $db->prepare("UPDATE game_players SET stats = ?, x = ?, y = ? WHERE id = ?");
                  $player_stats_str = setPlayerStats($player_lvl, $player_exp, $player_hp, $player_maxhp, $player_atk, $player_def, $player_spd, $player_evd, $player_gold);
                  $up->bind_param("siii", $player_stats_str, $reset_player_x, $reset_player_y, $player_id);
                  $up->execute();
                  $up->close();

                  $di = $db->prepare("DELETE FROM game_items 
                    WHERE owner_id = ?");
                  $di->bind_param("i", $player_id);
                  if ($di->execute()) {
                    $dp = $db->prepare("DELETE FROM game_players 
                    WHERE id = ?");
                    $dp->bind_param("i", $player_id);
                    $dp->execute();
                    $dp->close();
                  }
                  $di->close();

                  break;

                } else {
                  // update player stats
                  $up = $db->prepare("UPDATE game_players SET stats = ? WHERE id = ?");
                  $player_stats_str = setPlayerStats($player_lvl, $player_exp, $player_hp, $player_maxhp, $player_atk, $player_def, $player_spd, $player_evd, $player_gold);
                  $up->bind_param("si", $player_stats_str, $player_id);
                  $up->execute();
                  $up->close();
                }
              } else {
                array_push($arr, $monster_name . " missed!");
              }
            }
          }

        }
      }

      $sm->close();

    }
  }
  $sp->close();
  return $arr;
}

function getRoom($db, $data)
{
  $room_name = clean($data['get_room']);

  $ss = $db->prepare("SELECT * 
				FROM game_rooms 
				WHERE name = ?");
  $ss->bind_param("s", $room_name);

  $arr = array();
  if ($ss->execute()) {
    $r = $ss->get_result();
    $rc = mysqli_num_rows($r);
    if ($rc > 0) {
      while ($row = mysqli_fetch_array($r)) {
        array_push($arr, $row);
      }
    }
  }
  $ss->close();
  return $arr;
}

function getMusic()
{
  $log_directory = '../diskutans/music/ambient';

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

  $arr = array();
  foreach ($results_array as $value) {
    array_push($arr, '/diskutans/music/ambient/' . $value);
  }
  return $arr;
}

function getPlayers($db, $data)
{
  $room_id = clean($data['get_players']);

  $ss = $db->prepare("SELECT * 
				FROM game_players 
				WHERE room_id = ?");
  $ss->bind_param("i", $room_id);

  $arr = array();
  if ($ss->execute()) {
    $r = $ss->get_result();
    $rc = mysqli_num_rows($r);
    if ($rc > 0) {
      while ($row = mysqli_fetch_array($r)) {
        array_push($arr, $row);
      }
    }
  }
  $ss->close();
  return $arr;
}

function purgeRooms($db, $data)
{
  $ss = $db->prepare("SELECT * 
				FROM game_rooms 
				WHERE expiration < NOW()");

  $arr = array();
  if ($ss->execute()) {
    $r = $ss->get_result();
    $rc = mysqli_num_rows($r);
    if ($rc > 0) {
      while ($row = mysqli_fetch_array($r)) {
        $room_id = intval($row["id"]);

        $di = $db->prepare("DELETE FROM game_items 
				WHERE room_id = ?");
        $di->bind_param("i", $room_id);
        if ($di->execute()) {
          $dl = $db->prepare("DELETE FROM game_locations 
				WHERE room_id = ?");
          $dl->bind_param("i", $room_id);
          if ($dl->execute()) {
            $dm = $db->prepare("DELETE FROM game_monsters 
				WHERE room_id = ?");
            $dm->bind_param("i", $room_id);
            if ($dm->execute()) {
              $dp = $db->prepare("DELETE FROM game_players 
				WHERE room_id = ?");
              $dp->bind_param("i", $room_id);
              if ($dp->execute()) {
                $dr = $db->prepare("DELETE FROM game_rooms 
				WHERE id = ?");
                $dr->bind_param("i", $room_id);
                if ($dr->execute()) {
                  array_push($arr, $room_id);
                }
              }
            }
          }
        }
      }
    }
  }
  $ss->close();
  return $arr;
}

function getPlayer($db, $data)
{
  $player_name = clean($data['get_player']);
  $room_id = clean($data['room_id']);

  $ss = $db->prepare("SELECT * 
				FROM game_players 
				WHERE name = ? AND room_id = ?");
  $ss->bind_param("si", $player_name, $room_id);

  $arr = array();
  if ($ss->execute()) {
    $r = $ss->get_result();
    $rc = mysqli_num_rows($r);
    if ($rc > 0) {
      while ($row = mysqli_fetch_array($r)) {
        array_push($arr, $row);
      }
    }
  }
  $ss->close();
  return $arr;
}

function createRoom($db, $data)
{
  $name = clean($data['create_room']);
  $expiration = clean($data['expiration']);
  $regen = intval(clean($data['regen']));

  $arr = array();

  $dexp = new DateTime($expiration);
  $dnow = new DateTime(date('Y-m-d'));
  $dDiff = $dnow->diff($dexp);
  $diffInDays = (int) $dDiff->format("%r%a");
  $daysMin = 1;
  $daysMax = 365;

  if ($diffInDays >= $daysMin && $diffInDays <= $daysMax) {

    $is = $db->prepare("INSERT INTO game_rooms( name, expiration, regen ) 
				VALUES(?, ?, ?)");
    $is->bind_param("ssi", $name, $expiration, $regen);
    if ($is->execute()) {
      array_push($arr, "ok");
    } else {
      array_push($arr, "err");
    }
    $is->close();

  } else {
    array_push($arr, $diffInDays < $daysMin ? "date min " . $daysMin : "date max " . $daysMax);
  }
  return $arr;
}

function createPlayer($db, $data)
{
  $name = clean($data['create_player']);
  $portrait = -intval(clean($data['player_portrait']));
  $room_id = intval(clean($data['room_id']));

  $arr = array();
  if ($portrait <= -1 && $portrait >= -8) {

    $x = 0;
    $y = 0;
    $stats = "lvl=1;exp=0;hp=100;maxhp=100;atk=10;def=10;spd=10;evd=10;gold=0;";

    $is = $db->prepare("INSERT INTO game_players( name, room_id, x, y, stats, resource_id ) 
				VALUES(?, ?, ?, ?, ?, ?)");
    $is->bind_param("siiisi", $name, $room_id, $x, $y, $stats, $portrait);
    if ($is->execute()) {
      array_push($arr, "ok");
    } else {
      array_push($arr, "err");
    }
    $is->close();
  }
  return $arr;
}

function getLocation($db, $data)
{
  $room_id = intval(clean($data['get_location']));
  $x = intval(clean($data['x']));
  $y = intval(clean($data['y']));

  $ss = $db->prepare("SELECT * 
				FROM game_locations INNER JOIN resources_locations ON game_locations.resource_id = resources_locations.id 
				WHERE room_id = ? AND x = ? AND y = ?");
  $ss->bind_param("iii", $room_id, $x, $y);

  $arr = array();
  if ($ss->execute()) {
    $r = $ss->get_result();
    $rc = mysqli_num_rows($r);
    if ($rc > 0) {
      while ($row = mysqli_fetch_array($r)) {
        array_push($arr, $row);
        break;
      }
    }
  }
  $ss->close();
  return $arr;
}

function getItems($db, $data)
{
  $player_id = intval(clean($data['get_items']));
  $room_id = intval(clean($data['room_id']));

  $ss = $db->prepare("SELECT gi.id, gi.stats, gi.equipped, ri.name, ri.image, ri.description 
				FROM game_items gi INNER JOIN resources_items ri ON gi.resource_id = ri.id 
				WHERE gi.room_id = ? AND gi.owner_id = ?");
  $ss->bind_param("ii", $room_id, $player_id);

  $arr = array();
  if ($ss->execute()) {
    $r = $ss->get_result();
    $rc = mysqli_num_rows($r);
    if ($rc > 0) {
      while ($row = mysqli_fetch_array($r)) {
        array_push($arr, $row);
      }
    }
  }
  $ss->close();
  return $arr;
}

function getResourceInfo($db) {
  $arr = array();

  $sri = $db->prepare("SELECT COUNT(*) FROM resources_items");  
  if ($sri->execute()) {
    $srir = $sri->get_result();
    while ($srirow = mysqli_fetch_array($srir)) {
      array_push($arr, $srirow);
      break;
    }
  }
  $sri->close();

  $srm = $db->prepare("SELECT COUNT(*) FROM resources_monsters");  
  if ($srm->execute()) {
    $srmr = $srm->get_result();
    while ($srmrow = mysqli_fetch_array($srmr)) {
      array_push($arr, $srmrow);
      break;
    }
  }
  $srm->close();

  $srl = $db->prepare("SELECT COUNT(*) FROM resources_locations");  
  if ($srl->execute()) {
    $srlr = $srl->get_result();
    while ($srlrow = mysqli_fetch_array($srlr)) {
      array_push($arr, $srlrow);
      break;
    }
  }
  $srl->close();

  return $arr;
}

function getMonster($db, $data)
{
  $room_id = intval(clean($data['get_monster']));
  $x = intval(clean($data['x']));
  $y = intval(clean($data['y']));

  $ss = $db->prepare("SELECT rm.name, rm.description, gm.stats, rm.image, gm.id  
				FROM game_monsters gm INNER JOIN resources_monsters rm ON gm.resource_id = rm.id 
				WHERE gm.room_id = ? AND gm.x = ? AND gm.y = ?");
  $ss->bind_param("iii", $room_id, $x, $y);

  $arr = array();
  if ($ss->execute()) {
    $r = $ss->get_result();
    $rc = mysqli_num_rows($r);
    if ($rc > 0) {
      while ($row = mysqli_fetch_array($r)) {
        array_push($arr, $row);
        break;
      }
    }
  }
  $ss->close();
  return $arr;
}

function getAllLocations($db, $data)
{
  $room_id = intval(clean($data['get_all_locations']));

  $ss = $db->prepare("SELECT * 
				FROM game_locations INNER JOIN resources_locations ON game_locations.resource_id = resources_locations.id 
				WHERE room_id = ?");
  $ss->bind_param("i", $room_id);

  $arr = array();
  if ($ss->execute()) {
    $r = $ss->get_result();
    $rc = mysqli_num_rows($r);
    if ($rc > 0) {
      while ($row = mysqli_fetch_array($r)) {
        array_push($arr, $row);
      }
    }
  }
  $ss->close();
  return $arr;
}

function dropItem($db, $data)
{
  $item_id = intval(clean($data['drop_item']));
  $player_id = intval(clean($data['player_id']));
  $arr = array();

  $di = $db->prepare("DELETE FROM game_items WHERE id = ? AND owner_id = ?");
  $di->bind_param("ii", $item_id, $player_id);

  if ($di->execute()) {
    array_push($arr, "ok");
  } else {
    array_push($arr, "err");
  }

  $di->close();
  return $arr;
}

function equipItem($db, $data)
{
  $item_id = intval(clean($data['equip_item']));
  $player_id = intval(clean($data['player_id']));
  $arr = array();
  $ids = array();
  $types = array();

  $se = $db->prepare("SELECT * 
				FROM game_items WHERE owner_id = ?");
  $se->bind_param("i", $player_id);

  $item_type = '';
  if ($se->execute()) {
    $r = $se->get_result();
    $rc = mysqli_num_rows($r);
    if ($rc > 0) {
      while ($row = mysqli_fetch_array($r)) {
        $item_stats_parts = explode(';', $row["stats"]);
        for ($i = 0; $i < count($item_stats_parts); $i++) {
          if (str_starts_with($item_stats_parts[$i], "type=")) {

            if (intval($row["id"]) == $item_id) {
              $item_type = explode('=', $item_stats_parts[$i])[1];
            } else if (intval($row["equipped"]) == 1) {
              array_push($ids, intval($row["id"]));
              array_push($types, explode('=', $item_stats_parts[$i])[1]);
            }
          }
        }
      }
    }
  }
  $se->close();

  if ($item_type != '') {

    for ($i = 0; $i < count($ids); $i++) {
      $temp_type = $types[$i];
      if ($temp_type == $item_type) {

        $temp_id = $ids[$i];
        $uis = $db->prepare("UPDATE game_items SET equipped=0 WHERE id = ? AND owner_id = ?");
        $uis->bind_param("ii", $temp_id, $player_id);
        $uis->execute();
        $uis->close();
      }
    }

    $ui = $db->prepare("UPDATE game_items SET equipped=1 WHERE id = ? AND owner_id = ?");
    $ui->bind_param("ii", $item_id, $player_id);

    if ($ui->execute()) {
      array_push($arr, "ok");
    } else {
      array_push($arr, "err");
    }

    $ui->close();
  } else {
    array_push($arr, "err: item type " . $item_type);
  }
  return $arr;
}

function spawnMonster($db, $monsterName, $room_id, $x, $y, $newloc)
{
  $smres = $db->prepare("SELECT * 
                        FROM resources_monsters 
                        WHERE name = ?");
  $smres->bind_param("s", $monsterName);

  if ($smres->execute()) {
    $smresr = $smres->get_result();
    $smresrc = mysqli_num_rows($smresr);
    if ($smresrc > 0) {
      while ($smresrow = mysqli_fetch_array($smresr)) {

        $monstats = parseMonsterStats($smresrow['stats']);
        $monresource = intval($smresrow['id']);

        // insert into game_monsters
        $im = $db->prepare("INSERT INTO game_monsters(room_id, x, y, stats, resource_id) 
				VALUES(?, ?, ?, ?, ?)");
        $im->bind_param("iiisi", $room_id, $x, $y, $monstats, $monresource);
        if (!$im->execute()) {
          $newloc = false;
        }
        $im->close();
      }
    }
  } else {
    $newloc = false;
  }
  $smres->close();
  return $newloc;
}

function spawnMonsterRoll($db, $room_id, $x, $y, $monsterSpawnRate, $locstats, $newloc)
{
  // if no monster, roll dice, spawn monster
  $sm = $db->prepare("SELECT * 
FROM game_monsters 
WHERE room_id = ? AND x = ? AND y = ?");
  $sm->bind_param("iii", $room_id, $x, $y);
  if ($sm->execute()) {
    $smr = $sm->get_result();
    $smrows = mysqli_num_rows($smr);
    if ($smrows == 0) {
      if (rand(1, 100) <= $monsterSpawnRate) {
        $debug = $locstats;
        $locstats = verifyLocationStats($locstats);
        $locparts = explode(';', $locstats);
        for ($i = 0; $i < count($locparts); $i++) {
          if (str_starts_with($locparts[$i], 'spawns=')) {
            $monsters = explode(',', explode('=', $locparts[$i])[1]);
            $monsterName = trim($monsters[rand(0, count($monsters) - 1)], " ");
            $debug = $monsterName;
            $newloc = spawnMonster($db, $monsterName, $room_id, $x, $y, $newloc);
          }
        }
      }
    }
  } else {
    $newloc = false;
  }
  $sm->close();
  return $newloc;
}

function spawnLocation($db, $x, $y, $room_id, $diffx, $diffy, $monsterSpawnRate, $newloc)
{
  $room_lvl = max(1, abs($x) + abs($y));
  $srlocs = $db->prepare("SELECT * 
FROM resources_locations 
WHERE lvl_from <= ? AND lvl_to >= ?");
  $srlocs->bind_param("ii", $room_lvl, $room_lvl);
  if ($srlocs->execute()) {
    $srlocr = $srlocs->get_result();
    $srlocrc = mysqli_num_rows($srlocr);

    if ($srlocrc > 0) {
      $rloc = mysqli_fetch_all($srlocr)[rand(0, $srlocrc - 1)];

      $is = $db->prepare("INSERT INTO game_locations(room_id, x, y, stats, resource_id) 
VALUES(?, ?, ?, ?, ?)");
      $locstats = $rloc[6];
      $rlocid = intval($rloc[0]);
      $is->bind_param("iiisi", $room_id, $x, $y, $locstats, $rlocid);
      if ($is->execute()) {

        if (!($x == 0 && $y == 0) && ($diffx + $diffy) != 0) {
          $newloc = spawnMonsterRoll($db, $room_id, $x, $y, $monsterSpawnRate, $locstats, $newloc);
        }

      } else {
        $newloc = false;
      }
      $is->close();
    } else {
      $newloc = false;
    }
  }
  $srlocs->close();
  return $newloc;
}

function performMove($db, $diffx, $diffy, $room_id, $x, $y, $monsterSpawnRate, $player_name, $prevx, $prevy)
{
  $arr = array();
  $newloc = true;
  $drawLocation = false;
  if ($diffx + $diffy <= 1) {

    $sls = $db->prepare("SELECT * 
				FROM game_locations 
				WHERE room_id = ? AND x = ? AND y = ?");
    $sls->bind_param("iii", $room_id, $x, $y);
    if ($sls->execute()) {
      $slr = $sls->get_result();
      $slrc = mysqli_num_rows($slr);

      if ($slrc == 0) {
        $drawLocation = spawnLocation($db, $x, $y, $room_id, $diffx, $diffy, $monsterSpawnRate, $newloc);
        $newloc = $drawLocation;
      } else {

        while ($slrow = mysqli_fetch_array($slr)) {
          $locstats = $slrow["stats"];
          break;
        }

        if (!($x == 0 && $y == 0) && ($diffx + $diffy) != 0) {
          $newloc = spawnMonsterRoll($db, $room_id, $x, $y, $monsterSpawnRate, $locstats, $newloc);
        }
      }
    }
    $sls->close();

    if ($newloc == true) {
      $us = $db->prepare("UPDATE game_players 
            SET x=?, y=? 
            WHERE name = ? AND room_id = ?");
      $us->bind_param("iisi", $x, $y, $player_name, $room_id);
      if ($us->execute()) {
        array_push($arr, "ok");
        array_push($arr, $x - $prevx);
        array_push($arr, $y - $prevy);
        array_push($arr, $drawLocation == true ? "draw" : "");
      } else {
        array_push($arr, "err");
      }
      $us->close();
    } else {
      array_push($arr, "err");
    }
  } else {
    array_push($arr, "err");
  }
  return $arr;
}

function movePlayer($db, $data, $itemDropRate, $monsterSpawnRate)
{
  $player_name = clean($data['move_player']);
  $room_id = intval(clean($data['room_id']));
  $x = intval(clean($data['x']));
  $y = intval(clean($data['y']));

  $ss = $db->prepare("SELECT * 
				FROM game_players 
				WHERE name = ? AND room_id = ?");
  $ss->bind_param("si", $player_name, $room_id);

  $arr = array();

  if ($ss->execute()) {
    $r = $ss->get_result();
    $rc = mysqli_num_rows($r);
    if ($rc > 0) {
      while ($row = mysqli_fetch_array($r)) {
        $prevx = intval($row["x"]);
        $prevy = intval($row["y"]);
        $diffx = abs($x - $prevx);
        $diffy = abs($y - $prevy);

        $canMove = true;
        if ($diffx > 0 || $diffy > 0) {
          $sm = $db->prepare("SELECT * 
                FROM game_monsters 
                WHERE x = ? AND y = ? AND room_id = ?");
          $sm->bind_param("iii", $prevx, $prevy, $room_id);

          $arr = array();

          if ($sm->execute()) {
            $smr = $sm->get_result();
            $smrc = mysqli_num_rows($smr);
            if ($smrc > 0) {
              if (rand(1, 100) <= 50) {
                $canMove = false;
              }
            }
          }
          $sm->close();
        }

        if ($canMove) {
          $arr = performMove($db, $diffx, $diffy, $room_id, $x, $y, $monsterSpawnRate, $player_name, $prevx, $prevy);

        } else {
          $data['fight_monster'] = $player_name;
          $arr = fightMonster($db, $data, $itemDropRate);
          array_unshift($arr, "fight");
        }
      }
    }
  }
  $ss->close();
  return $arr;
}

// API

$data = $_REQUEST;
$monsterSpawnRate = 50;
$itemDropRate = 50;

if (isset($data['get_room'])) {
  echo json_encode(getRoom($db, $data));
} else if (isset($data['get_music'])) {
  echo json_encode(getMusic());
} else if (isset($data['get_players'])) {
  echo json_encode(getPlayers($db, $data));
} else if (isset($data['purge_rooms'])) {
  echo json_encode(purgeRooms($db, $data));
} else if (isset($data['get_player'])) {
  echo json_encode(getPlayer($db, $data));
} else if (isset($data['create_room'])) {
  echo json_encode(createRoom($db, $data));
} else if (isset($data['create_player'])) {
  echo json_encode(createPlayer($db, $data));
} else if (isset($data['get_location'])) {
  echo json_encode(getLocation($db, $data));
} else if (isset($data['get_items'])) {
  echo json_encode(getItems($db, $data));
} else if (isset($data['get_monster'])) {
  echo json_encode(getMonster($db, $data));
} else if (isset($data['get_all_locations'])) {
  echo json_encode(getAllLocations($db, $data));
} else if (isset($data['drop_item'])) {
  echo json_encode(dropItem($db, $data));
} else if (isset($data['equip_item'])) {
  echo json_encode(equipItem($db, $data));
} else if (isset($data['fight_monster'])) {
  echo json_encode(fightMonster($db, $data, $itemDropRate));
} else if (isset($data['move_player'])) {
  echo json_encode(movePlayer($db, $data, $itemDropRate, $monsterSpawnRate));
} else if (isset($data['get_resource_info'])) {
  echo json_encode(getResourceInfo($db));
}

mysqli_close($db);

?>