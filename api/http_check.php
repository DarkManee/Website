<?php
    $bypassIPs = json_decode(file_get_contents("http-bypass.json") , true);
    $ch = curl_init('http://' . $_GET["ip"]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT_MS, 5000);
    $data = curl_exec($ch);
    $curl_errno = curl_errno($ch);
    $curl_error = curl_error($ch);
    curl_close($ch);
    if ($curl_errno > 0) {
        if ($curl_errno == 28 || $curl_errno == 7) {
            echo "Passed";
        } else {
            if (in_array($_GET["ip"], $bypassIPs)) {
                echo "Passed";
            }
        }
    } else {
        if (in_array($_GET["ip"], $bypassIPs)) {
            echo "Passed";
        }
    }
?>