<?php
namespace LSYS\Html;
/**
 * @author     lonely
*/
class Uri{
	/**
	 * 清理路径
	 * @param string $path URL地址
	 * @return string 
	 */
    static public function clear(string $path):string{
	    $arr=[];
		if (preg_match('/^([a-zA-Z0-9]*:\/\/)(.*)/is',$path,$arr)){
			$para=preg_replace('/[\s\/\\\]{1,}/i','/',@$arr[2]);
			$path=@$arr[1].trim($para,'/') ;
		}else if (preg_match('/[\/\\\]$/i',$path)){
			$path=preg_replace("/[\s\/\\\]{1,}/i", '/', $path);
		}else{
			$path=preg_replace("/[\s\/\\\]{1,}/i", '/', $path);
			$path=preg_replace("/\/$/i", '', $path);
		}
		return $path; 
	}
	/**
	 * 添加URL变量
	 * @param string $url
	 * @param array $var如array('a'=>'b','d'='f') or string如a=b&c=d
	 * @return string
	 */
	static public function add(?string $url,array $var=null):?string{
		$temp = array();
		//统一存到数组里去,参数名作为键名,参数值作为键值
		$url = trim($url);
		if (empty($var)) return $url;
		if(is_array($var)){
			foreach($var as $key=>$v){
				$temp[$key]=$v;	
			}
		}else{
			if(strpos($var,'&')!==false){
				$param = explode('&',$var);
				foreach($param as $value){
					if(empty($value))continue;
					$par = explode('=',$value);
					$temp[$par[0]] = isset($par[1])?$par[1]:'';
				}
			}else{
				$var = explode('=',$var);
				$temp[$var[0]] = isset($var[1])?$var[1]:'';	
			}	
		}
		//添加到url,如果存在相同参数名则替换它的值
		if (count($temp)==0)return $url; 
		if(strpos($url, "?")!==false){
			$urlparam = parse_url($url);
			if (isset($urlparam['query'])) parse_str($urlparam['query'],$outparam);
			else $outparam=array();
			foreach($temp as $k=>$v){
				$outparam[$k] = $temp[$k];
			}
			$url = strstr($url, "?",true);
			$url .='?';
			foreach($outparam as $key=>$value){
				$url.=$key.'='.$value.'&';		
			}
		}else{
			if(strpos($url,'=')){
				parse_str($url,$outparam);
				if(strpos($url,'&')==0){
					$url='&';	
				}else{
					$url='';
				}
				foreach($temp as $k=>$v){
					$outparam[$k] = $temp[$k];	
				}
				foreach($outparam as $key=>$value){
					$url.=$key.'='.$value.'&';		
				}
			}else{
				$url.='?';
				foreach($temp as $k=>$v){
					$url.=$k.'='.$v.'&';
				}
			}
		}
		return rtrim($url,'&');
	}
	/**
	 * 移除URL的指定变量
	 * @param string $url
	 * @param string $key
	 * @return string
	 */
	static public function del(?string $url,?string $key):?string{
		if (empty($key))
			return $url;
		if(strpos($url, "?")!==false){
			$urlparam = parse_url($url);
			parse_str($urlparam['query'],$outparam);
			if(array_key_exists($key,$outparam)){
				unset($outparam[$key])	;
			}
			$url = preg_replace("/\?.*$/siU","",$url);
			$url .='?';
			foreach($outparam as $key=>$value){
				$url.=$key.'='.$value.'&';		
			}
		}else{
			if(strpos($url,'=')){
				parse_str($url,$outparam);
				if(strpos($url,'&')==0){
					$url='&';	
				}else{
					$url='';
				}
				if(array_key_exists($key,$outparam)){
					unset($outparam[$key])	;
				}
				foreach($outparam as $key=>$value){
					$url.=$key.'='.$value.'&';		
				}
			}
		}
		return rtrim($url,'&');
	}
	/**
	 * 填充url
	 * 	http://domain/page=:page&alpan=:alpan
	 * 	array(
	 * 		':page'=>array('1','100','111')
	 * 		':alpan'=>array('a','z')
	 * 	)
	 * @param string $url_tmp
	 * @param array $prams
	 * @return array
	 */
	static public function fill(?string $url,array $prams=null):array{
		$url=trim($url,'?');
		$data=array();
		foreach ($prams as $k=>$v){
			$arr=call_user_func_array('range',$v);
			if (!$data){
				foreach ($arr as $value){	
					$data[]=array($k=>$value);
				}
			}else{
				$i=0;
				foreach ($data as $v){
					foreach ($arr as $value){
						$data[$i]=array_merge($v,array($k=>$value));
						$i++;
					}	
				}
			}
		}
		$url_arr=array();
		foreach ($data as $v){
			$url_arr[]=URi::add($url, $v);
		}
		return $url_arr;
	}
}
