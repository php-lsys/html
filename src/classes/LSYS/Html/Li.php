<?php
namespace LSYS\Html;
class Li implements DataHtml{
	protected $_attr;
	protected $_iattr;
	protected $_data;
	protected $_isindex;
	public function __construct($is_index=false,array $attributes = array()){
		$this->_attr=$attributes;
		$this->_isindex=$is_index;
	}
	public function setData(array $data){
		$this->_data=$data;
		return $this;
	}
	public function render():string{
		if ($this->_isindex){
			$i=[];
			foreach ($this->_data as $k=>$v){
				$dt="<dt>{$k}</dt>";
				$dd="<dd>{$v}</dd>";
				$i[]=$dt.$dd;
			}
			$i=implode("", $i);
			return "<dl".HTMLTag::attributes($this->_attr).">{$i}</dl>";
		}else{
			$li=[];
			foreach ($this->_data as $v){
				$li[]="<li>{$v}</li>";
			}
			$li=implode("", $li);
			return "<ul".HTMLTag::attributes($this->_attr).">{$li}</ul>";
		}
	}
	public function __toString(){
		try{
			return $this->render();
		}catch (\Exception $e){
			return $e->getMessage();
		}
	}
}