<?php
session_start();

require_once('../config.php');
require_once('mysqli.php');

define("LOG_TYPE_BANK", 4);

if (!isset($_SESSION['id'])) {
  print("Session Error");
  exit;
}

$id = $_SESSION['id'];
$accsql = accountSQL($mysqli, $id);
$logsql = accountLogSQL($mysqli, $id);

$data = $accsql->fetch_assoc();
$data['Log'] = array();
while ($datum = $logsql->fetch_assoc()) {
  array_push($data['Log'], $datum);
}
print(json_encode($data));

function accountSQL($db, $id) {
  $sql = "
    SELECT
      Bankbook,
      Bank
    FROM ".DB_LARP.".user_data
    WHERE ID = $id";
  return $db->query($sql);
}
function accountLogSQL($db, $id) {
  $sql = "
    SELECT
      a.Value_A AS Amount,
      a.Value_B AS Balance,
      IF(a.Value_A < 0,
        \"출금\",
        \"입금\"
      ) AS Type,
      IF(a.DestID <> 0 AND LENGTH(b.Username) > 0,
        b.Username,
        a.Contents
      ) AS Contents,
      TIME_FORMAT(a.Time, \"%Y.%m.%d %H:%i:%s\") AS Time
    FROM ".DB_LARP_LOG."._log_user a
    LEFT OUTER JOIN
      ".DB_LARP_LOG.".user_data b
      ON a.DestID = b.ID
    WHERE
      a.UserID = $id
      AND a.Type = ".LOG_TYPE_BANK."
    ORDER BY a.Time DESC";
  return $db->query($sql);
}
?>
