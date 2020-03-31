<?php

if (!isset($_GET['call']) || $_GET['call'] != 'setMaintain') {
    $sql = "SELECT value FROM settings WHERE name='maintain' LIMIT 1";
    $mysqli->query($sql);
    if ($res = $mysqli->query($sql)) {
        $data = mysqli_fetch_array($res);
        if ($data && $data['value'] == 1) {
            header("HTTP/1.0 416 Maintain mode");
            die;
        }
    }
}