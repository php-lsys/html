<?php
namespace LSYS\Html;
use LSYS\Core;
class HTMLTag{
	/**
	 * @var  array  preferred order of attributes
	 */
	public static $attribute_order = array
	(
		'action',
		'method',
		'type',
		'id',
		'name',
		'value',
		'href',
		'src',
		'width',
		'height',
		'cols',
		'rows',
		'size',
		'maxlength',
		'rel',
		'media',
		'accept-charset',
		'accept',
		'tabindex',
		'accesskey',
		'alt',
		'title',
		'class',
		'style',
		'selected',
		'checked',
		'readonly',
		'disabled',
	);
	/**
	 * @var  boolean  use strict XHTML mode?
	 */
	public static $strict = TRUE;
	/**
	 * @var  boolean  automatically target external URLs to a new window?
	 */
	public static $windowed_urls = FALSE;
	/**
	 * Convert special characters to HTML entities. All untrusted content
	 * should be passed through this method to prevent XSS injections.
	 *
	 *     echo HTMLTag::chars($username);
	 *
	 * @param   string  $value          string to convert
	 * @param   boolean $double_encode  encode existing entities
	 * @return  string
	 */
	public static function chars(?string $value, bool  $double_encode = TRUE):?string
	{
		return htmlspecialchars( (string) $value, ENT_QUOTES, Core::charset(), $double_encode);
	}
	/**
	 * Convert all applicable characters to HTML entities. All characters
	 * that cannot be represented in HTML with the current character set
	 * will be converted to entities.
	 *
	 *     echo HTMLTag::entities($username);
	 *
	 * @param   string  $value          string to convert
	 * @param   boolean $double_encode  encode existing entities
	 * @return  string
	 */
	public static function entities(?string $value, bool $double_encode = TRUE):?string
	{
	    return htmlentities( (string) $value, ENT_QUOTES, Core::charset(), $double_encode);
	}
	/**
	 * Create HTML link anchors. Note that the title is not escaped, to allow
	 * HTML elements within links (images, etc).
	 *
	 *     echo HTMLTag::anchor('/user/profile', 'My Profile');
	 *
	 * @param   string  $uri        URL or URI string
	 * @param   string  $title      link text
	 * @param   array   $attributes HTML anchor attributes
	 * @param   mixed   $protocol   protocol 
	 * @param   boolean $index      include the index page
	 * @return  string
	 * @uses    HTMLTag::attributes
	 */
	public static function anchor(?string $uri, ?string $title = NULL, array $attributes = NULL):string 
	{
		if ($title === NULL)
		{
			// Use the URI as the title
			$title = $uri;
		}
		if (strpos($uri, '://') !== FALSE)
		{
			if (HTMLTag::$windowed_urls === TRUE AND empty($attributes['target']))
			{
				// Make the link open in a new window
				$attributes['target'] = '_blank';
			}
		}
		// Add the sanitized link to the attributes
		$attributes['href'] = $uri;
		return '<a'.HTMLTag::attributes($attributes).'>'.$title.'</a>';
	}
	/**
	 * Creates an email (mailto:) anchor. Note that the title is not escaped,
	 * to allow HTML elements within links (images, etc).
	 *
	 *     echo HTMLTag::mailto($address);
	 *
	 * @param   string  $email      email address to send to
	 * @param   string  $title      link text
	 * @param   array   $attributes HTML anchor attributes
	 * @return  string
	 * @uses    HTMLTag::attributes
	 */
	public static function mailto(?string $email, ?string $title = NULL, array $attributes = NULL):string 
	{
		if ($title === NULL)
		{
			// Use the email address as the title
			$title = $email;
		}
		return '<a href="&#109;&#097;&#105;&#108;&#116;&#111;&#058;'.$email.'"'.HTMLTag::attributes($attributes).'>'.$title.'</a>';
	}
	/**
	 * Creates a style sheet link element.
	 *
	 *     echo HTMLTag::style('media/css/screen.css');
	 *
	 * @param   string  $file       file name
	 * @param   array   $attributes default attributes
	 * @param   mixed   $protocol   protocol 
	 * @param   boolean $index      include the index page
	 * @return  string
	 * @uses    HTMLTag::attributes
	 */
	public static function style(?string $file, array $attributes = NULL):string
	{
		// Set the stylesheet link
		$attributes['href'] = $file;
		// Set the stylesheet rel
		$attributes['rel'] = empty($attributes['rel']) ? 'stylesheet' : $attributes['rel'];
		// Set the stylesheet type
		$attributes['type'] = 'text/css';
		return '<link'.HTMLTag::attributes($attributes).' />';
	}
	/**
	 * Creates a script link.
	 *
	 *     echo HTMLTag::script('media/js/jquery.min.js');
	 *
	 * @param   string  $file       file name
	 * @param   array   $attributes default attributes
	 * @param   mixed   $protocol   protocol 
	 * @param   boolean $index      include the index page
	 * @return  string
	 * @uses    HTMLTag::attributes
	 */
	public static function script(?string $file, array $attributes = NULL):string
	{
		// Set the script link
		$attributes['src'] = $file;
		// Set the script type
		$attributes['type'] = 'text/javascript';
		return '<script'.HTMLTag::attributes($attributes).'></script>';
	}
	/**
	 * Creates a image link.
	 *
	 *     echo HTMLTag::image('media/img/logo.png', array('alt' => 'My Company'));
	 *
	 * @param   string  $file       file name
	 * @param   array   $attributes default attributes
	 * @param   mixed   $protocol   protocol 
	 * @param   boolean $index      include the index page
	 * @return  string
	 * @uses    HTMLTag::attributes
	 */
	public static function image(?string $file, array $attributes = NULL):string
	{
		// Add the image link
		if (!empty($file))$attributes['src'] = $file;
		return '<img'.HTMLTag::attributes($attributes).' />';
	}
	/**
	 * Creates a image link.
	 *
	 *     echo HTMLTag::image('media/img/logo.png', array('alt' => 'My Company'));
	 *
	 * @param   string  $file       file name
	 * @param   array   $attributes default attributes
	 * @param   mixed   $protocol   protocol 
	 * @param   boolean $index      include the index page
	 * @return  string
	 * @uses    HTMLTag::attributes
	 */
	public static function video(?string $file, array $attributes = NULL):string
	{
		// Add the image link
		$attributes['src'] = $file;
		return '<video'.HTMLTag::attributes($attributes).' />';
	}
	/**
	 * Compiles an array of HTML attributes into an attribute string.
	 * Attributes will be sorted using HTMLTag::$attribute_order for consistency.
	 *
	 *     echo '<div'.HTMLTag::attributes($attrs).'>'.$content.'</div>';
	 *
	 * @param   array   $attributes attribute list
	 * @return  string
	 */
	public static function attributes(array $attributes = NULL)
	{
		if (empty($attributes))
			return '';
		$sorted = array();
		foreach (HTMLTag::$attribute_order as $key)
		{
			if (isset($attributes[$key]))
			{
				// Add the attribute to the sorted list
				$sorted[$key] = $attributes[$key];
			}
		}
		// Combine the sorted attributes
		$attributes = $sorted + $attributes;
		$compiled = '';
		foreach ($attributes as $key => $value)
		{
			if ($value === NULL)
			{
				// Skip attributes that have NULL values
				continue;
			}
			if (is_int($key))
			{
				// Assume non-associative keys are mirrored attributes
				$key = $value;
				if ( ! HTMLTag::$strict)
				{
					// Just use a key
					$value = FALSE;
				}
			}
			// Add the attribute key
			$compiled .= ' '.$key;
			if ($value OR HTMLTag::$strict)
			{
				// Add the attribute value
				$compiled .= '="'.HTMLTag::chars($value).'"';
			}
		}
		return $compiled;
	}
	
	/**
	 * 清理,整理,闭合 HTML字符串
	 */
	static public function repair(?string $html):string{
		libxml_use_internal_errors(true);
		$texth=preg_replace('/\s*/', '', $html);
		if(empty($texth))
			return '';
			$encode=mb_detect_encoding($html);
			$html='
		<head>
		<meta http-equiv="Content-Type" content="text/html;charset='.$encode.'">
		</head>
		'.$html;
			$doc = new \DOMDocument();
			$doc->loadHTML($html);
			
			$dd=$doc->getElementsByTagName('*');
			
			
			for ($i=0; $i<$dd->length; $i++)
			{
				$item = $dd->item($i);
				
				$nodeName = $item->nodeName;
				$a1=array('img','embed','object','input');
				$a1_attr=array(
						'src','width','height',
						'style','data','movie','flashvars',
						'name','value','param','border'
				);
				if(in_array($nodeName, $a1))
				{
					$attrs = $item->attributes;
					for ($j=0;$j<$attrs->length;$j++){
						$attr_nodeName = $attrs->item($j)->nodeName;
						if(!in_array($attr_nodeName , $a1_attr)) {
							$item->removeAttribute($attr_nodeName);
						}
					}
					continue;
				}
				$a1=array('table','tr','td');
				$a1_attr=array(
						'width','height','style',
						'border','rows',
						'cols','alt'
				);
				if(in_array($nodeName, $a1))
				{
					$attrs = $item->attributes;
					for ($j=0;$j<$attrs->length;$j++){
						$attr_nodeName = $attrs->item($j)->nodeName;
						if(!in_array($attr_nodeName , $a1_attr)) {
							$item->removeAttribute($attr_nodeName);
						}
					}
					continue;
				}
				
				$a3_save=array('href','style');
				
				if ($item->nodeName == 'a') {
					$attrs = $item->attributes;
					for ($j=0;$j<$attrs->length;$j++){
						$attr_nodeName = $attrs->item($j)->nodeName;
						if(!in_array($attr_nodeName , $a3_save)) {
							$item->removeAttribute($attr_nodeName);
						}
					}
					//一次清理不掉....
					$attrs = $item->attributes;
					for ($j=0;$j<$attrs->length;$j++){
						$attr_nodeName = $attrs->item($j)->nodeName;
						if(!in_array($attr_nodeName , $a3_save)) {
							$item->removeAttribute($attr_nodeName);
						}
					}
					$item->setattribute('rel','nofollow');
					continue;
				}
				
				$save_attr=array(
						'style','fontSize','color'
				);
				$attrs = $item->attributes;
				for ($j=0;$j<$attrs->length;$j++){
					$attr_nodeName = $attrs->item($j)->nodeName;
					if(!in_array($attr_nodeName , $save_attr)) {
						$item->removeAttribute($attr_nodeName);
					}
				}
				
				$empty=false;
				$et=array(
						'br','hr','img','input','param','embed','hr','button','object'
				);
				foreach ($et as $d){
					$s=$item->getElementsByTagName($d);
					if ($s->length){
						$empty=true;
						break;
					}
				}
				if(!$empty&&!in_array($item->nodeName,$et)&&!trim($item->textContent)){
					if($item->parentNode instanceof \DOMNode){
						$item->parentNode->removeChild($item);
						continue;
					}
				}
				
				if(in_array($item->nodeName, array('script', 'style', 'iframe', 'applet', 'marqueue', 'area')))
				{
					if ($item->parentNode instanceof \DOMNode)
					{
						$item->parentNode->removeChild($item);
					}
				}
			}
			$Bodys = $doc->getElementsByTagName('body');
			$Body=$Bodys->item(0);
			$children = @$Body->childNodes;
			$innerHTML='';
			if ($children)
				foreach ($children as $child) {
					$tmp_doc = new \DOMDocument();
					$tmp_doc->appendChild($tmp_doc->importNode($child,true));
					$innerHTML .= $tmp_doc->saveHTML();
				}
			return html_entity_decode($innerHTML,ENT_COMPAT,'utf-8');
	}
	/**
	 * 修复HTML内容中不完整(没有主域名的)url链接地址
	 */
	static function linkAddHost(?string $html,?string $host):string{
		if (preg_match('/src=[\s]*["\']\//isU',$html)){
			$html=preg_replace('/src=[\s]*["\']\//isU','src="'.$host.'/', $html);
		}
		if (preg_match('/href=[\s]*["\']\//isU',$html)){
			$html=preg_replace('/href=[\s]*["\']\//isU','href="'.$host.'/', $html);
		}
		return $html;
	}
	/**
	 * 把替换 HTML 页面 <meta 标签里的编码 为指定 编码 ($charset)
	 * @param string $html 网页源代码
	 * @param string $charset 指定编码
	 */
	static public function replaceCharset(?string $html,?string $charset):string{
		$html=preg_replace(
				'/(<meta[^>]+charset[\s]*=[\s]*[\'"]?[\s]*)([^>\'" ]+)([^>]+)/is',
				'$1'.$charset.'$3',
				$html
				);
		return $html ;
	}
	/**
	 * 把指定HTML内容设置为指定编码
	 * @param string $html 网页源代码
	 * @param string $charset 指定编码
	 */
	static public function setCharset(?string $html,?string $charset,?string $orgin_charset=null):string{
		if ($orgin_charset===null){
			if(preg_match(
					'/<meta[^>]+charset[\s]*=[\s]*[\'"]([^>\'" ]+)[\'"]/isU',
					$html,$match
					))$orgin_charset=$match[1];
		}
		if(empty($orgin_charset)){
			mb_internal_encoding("UTF-8");
			$convmap = array(0x80, 0xFFFF, 0, 0xFFFF);
			$html= mb_encode_numericentity($html, $convmap, $charset);
		}else{
			$html=iconv($orgin_charset, $charset.'//IGNORE', $html);
		}
		return self::replaceCharset($html, $charset);
	}
	/**
	 * 得到文本里的第一张图片
	 */
	static public function firstImgSrc(?string $string):?string{
		if(preg_match('/<img[^>]+src=[\'"](.+)[\'"]/isU',$string,$arr)){
			return $arr[1];
		}
		return ;
	}
	/**
	 * Creates a mailto link with Javascript to prevent bots from picking up the
	 * email address.
	 *
	 * @param	string	$email		the email address
	 * @param	string	$text		the text value
	 * @param	string	$subject	the subject
	 * @param	array	$attr		attributes for the tag
	 * @return	string	the javascript code containing email
	 */
	public static function mailtoSafe(?string $email, ?string $text = null, ?string $subject = null, ?string $attr = ''):string
	{
		$text or $text = str_replace('@', '[at]', $email);
		
		$email = explode("@", $email);
		
		$subject and $subject = '?subject='.$subject;
		
		$output = '<script type="text/javascript">';
		$output .= '(function() {';
		$output .= 'var user = "'.$email[0].'";';
		$output .= 'var at = "@";';
		$output .= 'var server = "'.$email[1].'";';
		$output .= "document.write('<a href=\"' + 'mail' + 'to:' + user + at + server + '$subject\"$attr>$text</a>');";
		$output .= '})();';
		$output .= '</script>';
		return $output;
	}
}