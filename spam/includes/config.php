<?php

$localhost="127.0.0.1";
$dbuser="root123";
$dbpass="majd";
$dbname="shareposts";

$db=new MySQLi($localhost,$dbuser,$dbpass,$dbname);

if($db->connect_errno > 0){
    echo "Error".$db->connect_error."]";
}

$db->query("set names 'utf8");



?>