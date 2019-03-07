<?php
use LSYS\Html\Table;
include_once __DIR__."/../vendor/autoload.php";
$ttt=array(
	array(
		'name'=>'bbb',
		'name2'=>'bbddddddfb',
		'name1'=>'bbbddddddddddd',
		'name3'=>'bbbddddddddd',
	),
	array(
		'name'=>'bbb',
		'name2'=>'bbddddddfb',
		'name1'=>'bbbddddddddddd',
		'name3'=>'bbbddddddddd',
	),
	array(
		'name'=>'bbb',
		'name2'=>'bbddddddfb',
		'name1'=>'bbbddddddddddd',
		'name3'=>'bbbddddddddd',
	),
);
$label=array(
	'name'=>'姓名',
	'name2'=>'姓名2',
	'name1'=>'姓名2',
	'name3'=>'姓名2',
);
echo (new Table($label))->headAttr(array(
	//'name'=>array("class"=>"ddd")
))->setData((new ArrayObject($ttt))->getIterator());

// echo ltable($label,$ttt);