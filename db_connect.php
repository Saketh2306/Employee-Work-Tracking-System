<?php
function db(){
    global $link;
    $link = mysqli_connect("localhost", "root", "", "reports") or die("couldn't connect to database");
    return $link;
}