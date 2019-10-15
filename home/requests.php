<?php
include_once "../controllers/oAuth.php";
include_once "../controllers/requestCtrlr.php";
include_once "../controllers/db_connection.php";

if (!isset($_GET['accion'])) {
  die();
}

switch ($_GET['accion']) {
  case 'getTrack':
    if (!isset($_GET['id'])) {
      die();
    }
    //"1JSTJqkT5qHq8MDJnJbRE1"
    echo json_encode(array('track' => json_decode(requestTrack($_GET['id'])), 'features' => json_decode(requestAudioFeatures($_GET['id'])), 'analysis' => json_decode(requestAudioAnalysis($_GET['id']))));
    break;
}

 ?>
