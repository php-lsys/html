<?php
namespace LSYS\Html;
use LSYS\Html\Form\Data;
use LSYS\Html\Form\Input;

class Form implements DataHtml{
	const RENDER_HTML=1<<0;
	const RENDER_RES=1<<1;
	
	const INPUT_HIDDEN=1;
	const INPUT_PASSWORD=2;
	const INPUT_TEXT=3;
	const INPUT_FILE=4;
	const INPUT_CHECKBOX=5;
	const INPUT_RADIO=6;
	const INPUT_TEXTAREA=7;
	const INPUT_SELECT=8;
	const INPUT_SUBMIT=9;
	const INPUT_BUTTON=10;
	
	public static $attr_map=array(
		'input_item'=>array("class"=>"form-group-item"),	
		'input_wrap'=>array("class"=>"form-group"),	
		'tips_item'=>array("class"=>"form-tips-item"),	
		'tips_wrap'=>array("class"=>"form-tips-warp"),	
	);
	
	protected $formdata;
	protected $tips=array();
	protected $attr=array();
	protected $rule=array();
	protected $label=array();
	/**
	 * 创建表单HTML
	 * @param string $action 提交地址
	 * @param array $attributes 属性数组
	 */
	public function __construct($action='javascript:;',array $attributes = array()){
		$this->attr($action,$attributes);
	}
	/**
	 * 获取或创建内容规则
	 * @param array $rule
	 * @return array|string|\LSYS\Html\Form
	 */
	public function rule($rule=null){
		if (!is_array($rule))return $this->rule;
		$this->rule=$rule;
		return $this;
	}
	/**
	 * 获取或设置表单对应字段默认显示名
	 * @param string $label
	 * @return array|string|\LSYS\Html\Form
	 */
	public function label($label=null){
		if (!is_array($label))return $this->label;
		$this->label=$label;
		return $this;
	}
	/**
	 * 更改表单属性
	 * @param string $action
	 * @param array $attributes
	 * @return \LSYS\Html\Form
	 */
	public function attr($action,array $attributes){
		$this->attr=func_get_args();
		return $this;
	}
	/**
	 * add top tip message
	 * @param string $message
	 * @param int $timeout
	 * @param string $name
	 * @return \LSYS\Html\Form
	 */
	public  function addTips($message)
	{
		if (!is_array($message))$message=array($message);
		foreach ($message as $k=>$v){
			if (is_int($k))$this->tips[]=$v;
			else {$this->tips[$k]=$v;echo $k;}
		}
		return $this;
	}
	/**
	 * Creates a hidden form input.
	 *
	 *     echo FormTag::hidden('csrf', $token);
	 *
	 * @param   string  $name       input name
	 * @param   string  $value      input value
	 * @param   array   $attributes html attributes
	 * @return  string
	 * @uses    FormTag::input
	 */
	public  function addInputCustom(Input $input)
	{
		$this->rule[]=array($input);
		return $this;
	}
	/**
	 * Creates a hidden form input.
	 *
	 *     echo FormTag::hidden('csrf', $token);
	 *
	 * @param   string  $name       input name
	 * @param   string  $value      input value
	 * @param   array   $attributes html attributes
	 * @return  string
	 * @uses    FormTag::input
	 */
	public  function addInputHidden($name, array $attributes = NULL)
	{
		$this->rule[]=array(self::INPUT_HIDDEN,func_get_args());
		return $this;
	}
	/**
	 * Creates a password form input.
	 *
	 *     echo FormTag::password('password');
	 *
	 * @param   string  $name       input name
	 * @param   string  $value      input value
	 * @param   array   $attributes html attributes
	 * @return  string
	 * @uses    FormTag::input
	 */
	public  function addInputPassword($name,$label=NULL, array $attributes = NULL)
	{
		$this->rule[]=array(self::INPUT_PASSWORD,func_get_args());
		return $this;
	}
	/**
	 * Creates a file upload form input. No input value can be specified.
	 *
	 *     echo FormTag::file('image');
	 *
	 * @param   string  $name       input name
	 * @param   array   $attributes html attributes
	 * @return  string
	 * @uses    FormTag::input
	 */
	public  function addInputText($name,$label=NULL, array $attributes = NULL)
	{
		$this->rule[]=array(self::INPUT_TEXT,func_get_args());
		return $this;
	}
	/**
	 * Creates a file upload form input. No input value can be specified.
	 *
	 *     echo FormTag::file('image');
	 *
	 * @param   string  $name       input name
	 * @param   array   $attributes html attributes
	 * @return  string
	 * @uses    FormTag::input
	 */
	public  function addInputFile($name,$label=NULL,$upload_name='file', array $attributes = NULL)
	{
		$this->rule[]=array(self::INPUT_FILE,func_get_args());
		return $this;
	}
	/**
	 * Creates a checkbox form input.
	 *
	 *     echo FormTag::checkbox('remember_me', 1, (bool) $remember);
	 *
	 * @param   string  $name       input name
	 * @param   string  $value      input value
	 * @param   boolean $checked    checked status
	 * @param   array   $attributes html attributes
	 * @return  string
	 * @uses    FormTag::input
	 */
	public  function addInputCheckbox($name, array $items=NULL, $label=NULL, array $attributes = NULL)
	{
		$this->rule[]=array(self::INPUT_CHECKBOX,func_get_args());
		return $this;
	}
	/**
	 * Creates a radio form input.
	 *
	 *     echo FormTag::radio('like_cats', 1, $cats);
	 *     echo FormTag::radio('like_cats', 0, ! $cats);
	 *
	 * @param   string  $name       input name
	 * @param   string  $value      input value
	 * @param   boolean $checked    checked status
	 * @param   array   $attributes html attributes
	 * @return  string
	 * @uses    FormTag::input
	 */
	public  function addInputRadio($name, array $items, $label=NULL, array $attributes = NULL)
	{
		$this->rule[]=array(self::INPUT_RADIO,func_get_args());
		return $this;
	}
	/**
	 * Creates a textarea form input.
	 *
	 *     echo FormTag::textarea('about', $about);
	 *
	 * @param   string  $name           textarea name
	 * @param   string  $body           textarea body
	 * @param   array   $attributes     html attributes
	 * @param   boolean $double_encode  encode existing HTML characters
	 * @return  string
	 * @uses    HTML::attributes
	 * @uses    HTML::chars
	 */
	public  function addInputTextarea($name, $label=NULL, array $attributes = NULL)
	{
		$this->rule[]=array(self::INPUT_TEXTAREA,func_get_args());
		return $this;
	}
	/**
	 * Creates a select form input.
	 *
	 *     echo FormTag::select('country', $countries, $country);
	 *
	 * [!!] Support for multiple selected options was added in v3.0.7.
	 *
	 * @param   string  $name       input name
	 * @param   array   $options    available options
	 * @param   mixed   $selected   selected option string, or an array of selected options
	 * @param   array   $attributes html attributes
	 * @return  string
	 * @uses    HTML::attributes
	 */
	public  function addInputSelect($name, array $items, $label=NULL,array $attributes = NULL)
	{
		$this->rule[]=array(self::INPUT_SELECT,func_get_args());
		return $this;
	}
	/**
	 * add submit to form.
	 *
	 *     echo FormTag::submit(NULL, 'Login');
	 *
	 * @param   string  $name       input name
	 * @param   string  $value      input value
	 * @param   array   $attributes html attributes
	 * @return  string
	 * @uses    FormTag::input
	 */
	public  function addInputSubmit($value,$name=null, array $attributes = NULL)
	{
		$this->rule[]=array(self::INPUT_SUBMIT,func_get_args());
		return $this;
	}
	public  function addInputButton($value,$name, array $attributes = NULL)
	{
		$this->rule[]=array(self::INPUT_BUTTON,func_get_args());
		return $this;
	}
	public function setData(Data $formdata){
		$this->formdata=$formdata;
		return $this;
	}
	protected function _tipsTag($message,$name){
		$tip_attr=isset(self::$attr_map['tips_item'])?self::$attr_map['tips_item']:[];
		return '<span'.HTMLTag::attributes($tip_attr).'>'.HTMLTag::chars($message).'</span>';
	}
	protected function _label($name){
		if (isset($this->label[$name]))return $this->label[$name];
		return $name;
	}
	protected function _data($name){
		if ($this->formdata instanceof Data) return $this->formdata->get($name);
		return null;
	}
	public function render($render=self::RENDER_HTML|self::RENDER_RES){
		$tags=array();
		$open=call_user_func_array(array(FormTag::class,'open'), $this->attr);
		
		$tips=$this->tips;
		
		$item_attr=isset(self::$attr_map['input_item'])?self::$attr_map['input_item']:[];
		$wrap_attr=isset(self::$attr_map['input_wrap'])?self::$attr_map['input_wrap']:[];
		$tips_attr=isset(self::$attr_map['tips_wrap'])?self::$attr_map['tips_wrap']:[];
		
		
		foreach ($this->rule as $k=>$_v){
			if (isset($_v[1])) list($method,$args)=$_v;
			else list($method)=$_v;
			switch ($method){
				case self::INPUT_HIDDEN://$name, array $attributes = NULL
					$name=$args[0];
					$attr=isset($args[1])?$args[1]:array();
					$tags[]=FormTag::hidden($args[0],$this->_data($name),$attr);
				break;
				case self::INPUT_TEXT://$name,$label=NULL, array $attributes = NULL
					$name=$args[0];
					$label=empty($args[1])?$this->_label($name):$args[1];
					$attr=isset($args[2])?$args[2]:array();
					if (!isset($attr['id']))$attr['id']=uniqid();
					
					
					$_tags='<div'.HTMLTag::attributes($item_attr).'>';
					$_tags.=FormTag::label($attr['id'],$label);
					
					$_tags.='<div'.HTMLTag::attributes($wrap_attr).'>';
					$attr['class']='form-control';
					$_tags.=FormTag::input($args[0],$this->_data($name),$attr);
					$_tags.='</div>';
					
					
					$_tags.='<div'.HTMLTag::attributes($tips_attr).'>';
					if (isset($tips[$name])){
						$_tags.=$this->_tipsTag($tips[$name], $name);
						unset($tips[$name]);
					}
					$_tags.='</div>';
					
					$_tags.='</div>';
					$tags[]=$_tags;
				break;
				case self::INPUT_PASSWORD://$name,$label=NULL, array $attributes = NULL
					$name=$args[0];
					$label=empty($args[1])?$this->_label($name):$args[1];
					$attr=isset($args[2])?$args[2]:array();
					if (!isset($attr['id']))$attr['id']=uniqid();
					
					$_tags='<div'.HTMLTag::attributes($item_attr).'>';
					$_tags.=FormTag::label($attr['id'],$label);
					
					$_tags.='<div'.HTMLTag::attributes($wrap_attr).'>';
					$attr['class']='form-control';
					$_tags.=FormTag::password($args[0],$this->_data($name),$attr);
					$_tags.='</div>';
					
					$_tags.='<div'.HTMLTag::attributes($tips_attr).'>';
					if (isset($tips[$name])){
						$_tags.=$this->_tipsTag($tips[$name], $name);
						unset($tips[$name]);
					}
					$_tags.='</div>';
					
					$_tags.='</div>';
					$tags[]=$_tags;
				break;
				case self::INPUT_FILE://$name,$label=NULL,$upload_name='file', array $attributes = NULL
					$name=$args[0];
					$label=empty($args[1])?$this->_label($name):$args[1];
					$upload_name=isset($args[2])?$args[2]:'file';
					$attr=isset($args[3])?$args[3]:array();
					if (!isset($attr['id']))$attr['id']=uniqid();
					$_tags='<div'.HTMLTag::attributes($item_attr).'>';
					$_tags.=FormTag::label($attr['id'],$label);
					
					$_tags.='<div'.HTMLTag::attributes($wrap_attr).'>';
					$attr['class']='form-control';
					$_tags.=FormTag::file($upload_name,$attr);
					$_tags.=HTMLTag::image(NULL);
					$_tags.=FormTag::hidden($name,$this->_data($name));
					$_tags.='</div>';
					
					$_tags.='<div'.HTMLTag::attributes($tips_attr).'>';
					if (isset($tips[$name])){
						$_tags.=$this->_tipsTag($tips[$name], $name);
						unset($tips[$name]);
					}
					$_tags.='</div>';
					
					$_tags.='</div>';
					$tags[]=$_tags;
				break;
				case self::INPUT_CHECKBOX://$name, $label=NULL, array $items=NULL, array $attributes = NULL
					$name=$args[0];
					$label=empty($args[2])?$this->_label($name):$args[2];
					$items=isset($args[1])?$args[1]:[];
					$_tags='<div'.HTMLTag::attributes($item_attr).'>';
					if (count($items)==0){
						$attr=isset($args[3])?$args[3]:array();
						if (!isset($attr['id']))$attr['id']=uniqid();
						
						$_tags.='<div'.HTMLTag::attributes($wrap_attr).'>';
						$_tags.=FormTag::label($attr['id'],$label);
						$_tags.=FormTag::checkbox($name,1,!!$this->_data($name),$attr);
						$_tags.='</div>';
						
					}else{
						$_tags.=FormTag::label(NULL,$label);
						
						$_tags.='<div'.HTMLTag::attributes($wrap_attr).'>';
						foreach ($items as $k=>$v){
							$attr=isset($args[3])?$args[3]:array();
							if (!isset($attr['id']))$attr['id']=uniqid();
							$_tags.=FormTag::label($attr['id'],$v);
							$_data=$this->_data($name);
							$_tags.=FormTag::checkbox($name."[{$k}]",1,!empty(intval($_data[$k])),$attr);
						}
						$_tags.='</div>';
					}
					
					$_tags.='<div'.HTMLTag::attributes($tips_attr).'>';
					if (isset($tips[$name])){
						$_tags.=$this->_tipsTag($tips[$name], $name);
						unset($tips[$name]);
					}
					$_tags.='</div>';
					
					$_tags.='</div>';
					$tags[]=$_tags;
				break;
				case self::INPUT_RADIO://$name, $label=NULL, array $items, array $attributes = NULL
					$name=$args[0];
					$label=empty($args[2])?$this->_label($name):$args[2];
					$items=isset($args[1])?$args[1]:[];
					$_tags='<div'.HTMLTag::attributes($item_attr).'>';
					$_tags.=FormTag::label(NULL,$label);
					
					$_tags.='<div'.HTMLTag::attributes($wrap_attr).'>';
					foreach ($items as $k=>$v){
						$attr=isset($args[3])?$args[3]:array();
						if (!isset($attr['id']))$attr['id']=uniqid();
						$_tags.=FormTag::label($attr['id'],$v);
						$_tags.=FormTag::radio($name,$k,$this->_data($name)==$k,$attr);
					}
					$_tags.='</div>';
					
					$_tags.='<div'.HTMLTag::attributes($tips_attr).'>';
					if (isset($tips[$name])){
						$_tags.=$this->_tipsTag($tips[$name], $name);
						unset($tips[$name]);
					}
					$_tags.='</div>';
					
					
					$_tags.='</div>';
					$tags[]=$_tags;
				break;
				case self::INPUT_TEXTAREA://$name, $label=NULL, array $attributes = NULL
					$name=$args[0];
					$label=empty($args[1])?$this->_label($name):$args[1];
					$attr=isset($args[2])?$args[2]:array();
					if (!isset($attr['id']))$attr['id']=uniqid();
					$_tags='<div'.HTMLTag::attributes($item_attr).'>';
					$_tags.=FormTag::label($attr['id'],$label);
					
					$_tags.='<div'.HTMLTag::attributes($wrap_attr).'>';
					$_tags.=FormTag::textarea($name,$this->_data($_v[0]),$attr);
					$_tags.='</div>';
					
					$_tags.='<div'.HTMLTag::attributes($tips_attr).'>';
					if (isset($tips[$name])){
						$_tags.=$this->_tipsTag($tips[$name], $name);
						unset($tips[$name]);
					}
					$_tags.='</div>';
					
					$_tags.='</div>';
					$tags[]=$_tags;
				break;
				case self::INPUT_SELECT://$name, $label=NULL, array $items,array $attributes = NULL
					$name=$args[0];
					$label=empty($args[2])?$this->_label($name):$args[2];
					$items=isset($args[1])?$args[1]:[];
					$_tags='<div'.HTMLTag::attributes($item_attr).'>';
					$_tags.=FormTag::label(NULL,$label);
					$attr=isset($args[3])?$args[3]:array();
					if (!isset($attr['id']))$attr['id']=uniqid();
					$_tags.=FormTag::label($attr['id'],$v);
					$_data=$this->_data($name);
					$_tags.=FormTag::select($name,$items,$_data,$attr);
					
					$_tags.='<div'.HTMLTag::attributes($tips_attr).'>';
					if (isset($tips[$name])){
						$_tags.=$this->_tipsTag($tips[$name], $name);
						unset($tips[$name]);
					}
					$_tags.='</div>';
					
					
					$_tags.='</div>';
					$tags[]=$_tags;
				break;
				case self::INPUT_SUBMIT://$value,$name=null, array $attributes = NULL
					$value=$args[0];
					$name=isset($args[1])?$args[1]:NULL;
					$attr=isset($args[2])?$args[2]:array();
					if (!isset($attr['id']))$attr['id']=uniqid();
					$_tags='<div'.HTMLTag::attributes($item_attr).'>';
					$_tags.=FormTag::submit($name,$value,$attr);
					$_tags.='</div>';
					$tags[]=$_tags;
				break;
				case self::INPUT_BUTTON://$value,$name=null, array $attributes = NULL
					$value=$args[0];
					$name=isset($args[1])?$args[1]:NULL;
					$attr=isset($args[2])?$args[2]:array();
					if (!isset($attr['id']))$attr['id']=uniqid();
					$_tags='<div'.HTMLTag::attributes($item_attr).'>';
					$_tags.=FormTag::button($name,$value,$attr);
					$_tags.='</div>';
					$tags[]=$_tags;
				break;
			}
		}
		foreach ($tips as $k=>$v){
			$open.=$this->_tipsTag($v, $k);
		}
		$close=FormTag::close();
		return $open.implode("\n", $tags).$close;
	}
	public function __toString(){
		try{
			return $this->render();
		}catch (\Exception $e){
			return $e->getMessage();
		}
	}
}