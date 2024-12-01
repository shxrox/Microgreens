<?php

@session_start();

$con = mysqli_connect('localhost','root','','microgreen');
if(!$con){
    die('cannot established DB');
}
?>                                                                