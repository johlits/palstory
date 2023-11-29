<?php
function clean($str) {
  return htmlspecialchars($str, ENT_QUOTES);
}
function strip($str) {
  return strip_tags($str);
}
function admin_game() {
  return $_ENV["ADMIN_GAME"];
}
function super_admin_game() {
  return $_ENV["SUPER_ADMIN_GAME"];
}
?>