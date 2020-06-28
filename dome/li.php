<?php
use LSYS\Html\Li;
include_once __DIR__."/../vendor/autoload.php";
$ttt=array(
	'a'=>'bbb',
	'b'=>'bbddddddfb',
	'c'=>'bbbddddddddddd',
	'd'=>'bbbddddddddd',
);
echo (new Li(true))->setData($ttt);