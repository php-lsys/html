<?php
use LSYS\Html\Form;
include_once __DIR__."/../vendor/autoload.php";


//方式1:
class bform extends Form{
	protected $rule=array(
		array(Form::INPUT_TEXT,array("textname","Text label111")),
		array(Form::INPUT_TEXT,array("textname1","Text label111")),
		array(Form::INPUT_TEXT,array("textname2","Text label111")),
		array(Form::INPUT_TEXT,array("textname3","Text label111")),
	);
}

echo new bform();

// //方式二
// $ttt=array(
// 	'name'=>'bbb',
// 	'name2'=>'bbddddddfb',
// 	'name1'=>'bbbddddddddddd',
// 	'name3'=>'bbbddddddddd',
// );
// $label=array(
// 	'name'=>'姓名',
// 	'name2'=>'姓名2',
// );
// echo (new Form)->label($label)
// 	->add_tips(array(
// 		'tips 111','tips 3333',
// 	))
// 	->add_tips(array(
// 		'name'=>'tips 111','tips 3333',
// 	))
// 	->rule(array(
// 	array(Form::INPUT_TEXT,array("name")),
// 	array(Form::INPUT_TEXT,array("name2")),
// 	array(Form::INPUT_TEXT,array("name1")),
// 	array(Form::INPUT_TEXT,array("name3")),
// 	array(Form::INPUT_SUBMIT),
// ))->set_data(new LSYS\Html\Form\Data\ArrData($ttt));



//方式三
// $form=new Form();
// $form->add_tips("bbbb");
// $form->add_input_hidden("bbb");
// $form->add_input_checkbox("check",array("item1"=>"Item1","item2"=>"Item2"),"Check label");
// $form->add_input_file("pic","Pic label","fileinput");
// $form->add_input_password("pass","Pass label");
// $form->add_input_radio("radio",array("radio1"=>"Radio1","radio2"=>"Radio2"),"Radio label");
// $form->add_input_select("select",array("select1"=>"Select1","s2"=>"Select2"),"Select label");
// $form->add_input_text("text","Text label");
// $form->add_input_textarea("textarea","Textarea label");
// $form->add_input_button("bbb", "BUTTON");
// $form->add_input_submit("Submit");
// echo $form->render();