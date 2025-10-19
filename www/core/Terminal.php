<?php
$HOME = '/home/flffisamaune';
$message = "";
$cmd = "";
$is_start = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['start'])) {
        //$cmd = "/home/flffisamaune/.local/bin/supervisord -n -c /home/flffisamaune/project/supervisord.conf";
        $cmd = "/home/flffisamaune/nodejs/bin/node /home/flffisamaune/project/cloud/app1.js";
        exec($cmd1, $output, $status);
        if (isset($_GET['debug'])) {
            print_r($status);
            print_r($output);
        }
        $message = json_encode($output, JSON_PRETTY_PRINT);
    }else if (isset($_POST['stop'])) {
        $cmd = "lsof -i :3001 | grep -v grep | awk '{print $2}' | xargs kill -9";
        exec($cmd1, $output, $status);
        if (isset($_GET['debug'])) {
            print_r($status);
            print_r($output);
        }
        $message = json_encode($output, JSON_PRETTY_PRINT);
    }
    
}
?>