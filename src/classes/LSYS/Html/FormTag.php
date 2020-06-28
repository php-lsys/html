<?php
namespace LSYS\Html;
use LSYS\Core;

class FormTag{
	/**
	 * Generates an opening HTMLTag form tag.
	 *
	 *     // Form will submit back to the current page using POST
	 *     echo FormTag::open();
	 *
	 *     // Form will submit to 'search' using GET
	 *     echo FormTag::open('search', array('method' => 'get'));
	 *
	 *     // When "file" inputs are present, you must include the "enctype"
	 *     echo FormTag::open(NULL, array('enctype' => 'multipart/form-data'));
	 *
	 * @param   mixed   $action     form action, defaults to the current request URI, or [Request] class to use
	 * @param   array   $attributes html attributes
	 * @return  string
	 * @uses    Request
	 * @uses    URL::site
	 * @uses    HTMLTag::attributes
	 */
    public static function open(?string $action = NULL, array $attributes = NULL):string
	{
		// Add the form action to the attributes
		$attributes['action'] = $action;
		// Only accept the default character set
		$attributes['accept-charset'] = Core::charset();
		if ( ! isset($attributes['method']))
		{
			// Use POST method
			$attributes['method'] = 'post';
		}
		return '<form'.HTMLTag::attributes($attributes).'>';
	}
	/**
	 * Creates the closing form tag.
	 *
	 *     echo FormTag::close();
	 *
	 * @return  string
	 */
	public static function close():string
	{
		return '</form>';
	}
	/**
	 * Creates a form input. If no type is specified, a "text" type input will
	 * be returned.
	 *
	 *     echo FormTag::input('username', $username);
	 *
	 * @param   string  $name       input name
	 * @param   string  $value      input value
	 * @param   array   $attributes html attributes
	 * @return  string
	 * @uses    HTMLTag::attributes
	 */
	public static function input(string $name, string $value = NULL, array $attributes = NULL):string
	{
		// Set the input name
		if (!empty($name)) $attributes['name'] = $name;
		// Set the input value
		$attributes['value'] = $value;
		if ( ! isset($attributes['type']))
		{
			// Default type is text
			$attributes['type'] = 'text';
		}
		return '<input'.HTMLTag::attributes($attributes).' />';
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
	public static function hidden(string $name,string  $value = NULL, array $attributes = NULL):string
	{
		$attributes['type'] = 'hidden';
		return FormTag::input($name, $value, $attributes);
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
	public static function password(string  $name, string  $value = NULL, array $attributes = NULL):string 
	{
		$attributes['type'] = 'password';
		return FormTag::input($name, $value, $attributes);
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
	public static function file(string  $name, array $attributes = NULL):string
	{
		$attributes['type'] = 'file';
		return FormTag::input($name, NULL, $attributes);
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
	public static function checkbox(string  $name,string  $value = NULL, bool $checked = FALSE, array $attributes = NULL):string 
	{
		$attributes['type'] = 'checkbox';
		if ($checked === TRUE)
		{
			// Make the checkbox active
			$attributes[] = 'checked';
		}
		return FormTag::input($name, $value, $attributes);
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
	public static function radio(string  $name, string $value = NULL, bool $checked = FALSE, array $attributes = NULL):string 
	{
		$attributes['type'] = 'radio';
		if ($checked === TRUE)
		{
			// Make the radio active
			$attributes[] = 'checked';
		}
		return FormTag::input($name, $value, $attributes);
	}
	/**
	 * Creates a textarea form input.
	 *
	 *     echo FormTag::textarea('about', $about);
	 *
	 * @param   string  $name           textarea name
	 * @param   string  $body           textarea body
	 * @param   array   $attributes     html attributes
	 * @param   boolean $double_encode  encode existing HTMLTag characters
	 * @return  string
	 * @uses    HTMLTag::attributes
	 * @uses    HTMLTag::chars
	 */
	public static function textarea(string  $name, string  $body = '', array $attributes = NULL, bool $double_encode = TRUE):string 
	{
		// Set the input name
		$attributes['name'] = $name;
		// Add default rows and cols attributes (required)
		$attributes += array('rows' => 10, 'cols' => 50);
		return '<textarea'.HTMLTag::attributes($attributes).'>'.HTMLTag::chars($body, $double_encode).'</textarea>';
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
	 * @uses    HTMLTag::attributes
	 */
	public static function select(string  $name, array $options = NULL, $selected = NULL, array $attributes = NULL):string
	{
		// Set the input name
		$attributes['name'] = $name;
		if (is_array($selected))
		{
			// This is a multi-select, god save us!
			$attributes[] = 'multiple';
		}
		if ( ! is_array($selected))
		{
			if ($selected === NULL)
			{
				// Use an empty array
				$selected = array();
			}
			else
			{
				// Convert the selected options to an array
				$selected = array( (string) $selected);
			}
		}
		if (empty($options))
		{
			// There are no options
			$options = '';
		}
		else
		{
			foreach ($options as $value => $name)
			{
				if (is_array($name))
				{
					// Create a new optgroup
					$group = array('label' => $value);
					// Create a new list of options
					$_options = array();
					foreach ($name as $_value => $_name)
					{
						// Force value to be string
						$_value = (string) $_value;
						// Create a new attribute set for this option
						$option = array('value' => $_value);
						if (in_array($_value, $selected))
						{
							// This option is selected
							$option[] = 'selected';
						}
						// Change the option to the HTMLTag string
						$_options[] = '<option'.HTMLTag::attributes($option).'>'.HTMLTag::chars($_name, FALSE).'</option>';
					}
					// Compile the options into a string
					$_options = "\n".implode("\n", $_options)."\n";
					$options[$value] = '<optgroup'.HTMLTag::attributes($group).'>'.$_options.'</optgroup>';
				}
				else
				{
					// Force value to be string
					$value = (string) $value;
					// Create a new attribute set for this option
					$option = array('value' => $value);
					if (in_array($value, $selected))
					{
						// This option is selected
						$option[] = 'selected';
					}
					// Change the option to the HTMLTag string
					$options[$value] = '<option'.HTMLTag::attributes($option).'>'.HTMLTag::chars($name, FALSE).'</option>';
				}
			}
			// Compile the options into a single string
			$options = "\n".implode("\n", $options)."\n";
		}
		return '<select'.HTMLTag::attributes($attributes).'>'.$options.'</select>';
	}
	/**
	 * Creates a submit form input.
	 *
	 *     echo FormTag::submit(NULL, 'Login');
	 *
	 * @param   string  $name       input name
	 * @param   string  $value      input value
	 * @param   array   $attributes html attributes
	 * @return  string
	 * @uses    FormTag::input
	 */
	public static function submit(string $name,?string $value, array $attributes = NULL):string
	{
		$attributes['type'] = 'submit';
		return FormTag::input($name, $value, $attributes);
	}
	/**
	 * Creates a image form input.
	 *
	 *     echo FormTag::image(NULL, NULL, array('src' => 'media/img/login.png'));
	 *
	 * @param   string  $name       input name
	 * @param   string  $value      input value
	 * @param   array   $attributes html attributes
	 * @param   boolean $index      add index file to URL?
	 * @return  string
	 * @uses    FormTag::input
	 */
	public static function image(string $name,?string  $value, array $attributes = NULL, $index = FALSE):string 
	{
		$attributes['type'] = 'image';
		return FormTag::input($name, $value, $attributes);
	}
	/**
	 * Creates a button form input. Note that the body of a button is NOT escaped,
	 * to allow images and other HTMLTag to be used.
	 *
	 *     echo FormTag::button('save', 'Save Profile', array('type' => 'submit'));
	 *
	 * @param   string  $name       input name
	 * @param   string  $body       input value
	 * @param   array   $attributes html attributes
	 * @return  string
	 * @uses    HTMLTag::attributes
	 */
	public static function button(string $name, ?string  $body, array $attributes = NULL):string 
	{
		// Set the input name
		$attributes['name'] = $name;
		return '<button'.HTMLTag::attributes($attributes).'>'.$body.'</button>';
	}
	/**
	 * Creates a form label. Label text is not automatically translated.
	 *
	 *     echo FormTag::label('username', 'Username');
	 *
	 * @param   string  $input      target input
	 * @param   string  $text       label text
	 * @param   array   $attributes html attributes
	 * @return  string
	 * @uses    HTMLTag::attributes
	 */
	public static function label(string $input, ?string $text = NULL, array $attributes = NULL):string 
	{
		if ($text === NULL)
		{
			// Use the input name as the text
			$text = ucwords(preg_replace('/[\W_]+/', ' ', $input));
		}
		// Set the label target
		if (!empty($input))$attributes['for'] = $input;
		return '<label'.HTMLTag::attributes($attributes).'>'.$text.'</label>';
	}
}