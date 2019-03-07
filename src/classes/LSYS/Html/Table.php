<?php
namespace LSYS\Html;
class Table implements DataHtml{
	const RENDER_HTML=1<<0;
	const RENDER_RES=1<<1;
	protected $_header;
	protected $_iterator;
	protected $_index_header=false;
	protected $_attr;
	protected $_hattr;
	public function __construct($header=true,array $attributes = array()){
		$this->_header=$header;
		$this->_attr=$attributes;
	}
	public function headAttr(array $attr){
		$this->_hattr=$attr;
		return $this;
	}
	public function setData(\Iterator $iterator){
		$this->_iterator=$iterator;
		return $this;
	}
	public function setIndex($index_header){
		$this->_index_header=$index_header;
		return $this;
	}
	public function render($render=self::RENDER_HTML|self::RENDER_RES){
		
		$tbody=$thead='';
		foreach ($this->_iterator as $k=>$v){
			if (is_object($v)&&method_exists($v, "as_array"))$v=$v->asArray();
			if (!is_array($v))continue;
			if ($this->_header===true){
				$this->_header=array_keys($v);
				$this->_header=array_combine($this->_header, $this->_header);
			}
			$tr="<tr>";
			
			if ($this->_index_header!==false){
				$tr.="<td>".($k+1)."</td>";
			}
			
			foreach ($v as $kk=>$vv){
				if (!isset($this->_header[$kk]))continue;
				$tr.="<td>".HTMLTag::chars($vv)."</td>";
			}
			$tr.="</tr>";
			$tbody.=$tr;
		}
		
		$thead="<tr>";
		
		if ($this->_index_header!==false){
			$thead.="<td>".$this->_index_header."</td>";
		}
		foreach ($this->_header as $k=>$v){
			if (isset($this->_hattr[$k])&&is_array($this->_hattr[$k]))$hattr=$this->_hattr[$k];
			else $hattr=[];
			$thead.="<td".HTMLTag::attributes($hattr).">".HTMLTag::chars($v)."</td>";
		}
		$thead.="</tr>";
		
		return "<table".HTMLTag::attributes($this->_attr)."><thead>{$thead}</thead><tbody>{$tbody}</tbody></table>";
	}
	public function __toString(){
		try{
			return $this->render();
		}catch (\Exception $e){
			return $e->getMessage();
		}
	}
}