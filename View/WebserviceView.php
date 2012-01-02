<?php
/**
 * Webservice View Class
 *
 * Renders the data as either json or xml
 *
 * PHP version 5
 *
 * Copyright 2010-2012, Jose Diaz-Gonzalez
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the below copyright notice.
 *
 * @copyright   Copyright 2010-2012, Jose Diaz-Gonzalez
 * @package     Webservice
 * @subpackage  Webservice.View
 * @link        http://github.com/josegonzalez/webservice_plugin
 * @license     MIT License (http://www.opensource.org/licenses/mit-license.php)
 **/

App::uses('View', 'View');
class WebserviceView extends View {

/**
 * List of variables to collect from the associated controller
 *
 * @var array
 * @access protected
 */
	protected $_passedVars = array(
		'viewVars', 'params'
	);

/**
 * Blacklisted view variables
 *
 * @var string
 */
	protected $_webserviceBlacklistVars = array(
		'debugToolbarPanels',
		'debugToolbarJavascript',
		'webserviceTextarea',
		'webserviceNoxjson',
	);

/**
 * Include the session in output
 *
 * @var boolean
 **/
	protected $_webserviceSessionFlash = false;

/**
 * Constructor
 *
 * @param Controller $controller A controller object to pull View::_passedVars from.
 */
	public function __construct($controller) {
		if (is_object($controller)) {
			if (isset($controller->webserviceBlacklistVars)) {
				$this->_webserviceBlacklistVars = array_merge(
					$this->_webserviceBlacklistVars,
					$controller->webserviceBlacklistVars
				);
			}

			if (isset($controller->webserviceSessionFlash)) {
				$this->_webserviceSessionFlash = $controller->webserviceSessionFlash;
			}
		}

		parent::__construct($controller);
	}

/**
 * Renders a webservice response
 *
 * @return void
 */
	public function render() {
		Configure::write('debug', 0);
		$textarea  = isset($this->viewVars['webserviceTextarea']);
		$noXjson   = isset($this->viewVars['webserviceNoxjson']);

		foreach ($this->_webserviceBlacklistVars as $blacklisted) {
			if (isset($this->viewVars[$blacklisted])) {
				unset($this->viewVars[$blacklisted]);
			}
		}

		if (!empty($this->validationErrors)) {
			$this->viewVars['validationErrors'] = $this->validationErrors;
		}

		if ($this->_webserviceSessionFlash) {
			$this->viewVars['messages'] = CakeSession::read('Message');
			CakeSession::delete('Message');
			if (empty($this->viewVars['messages'])) {
				unset($this->viewVars['messages']);
			}
		}

		$format = $textarea ? '<textarea>%s</textarea>' : '%s';

		$ext = 'json';
		if (!empty($this->params->params['ext'])) {
			$ext = $this->params->params['ext'];
		}

		if ($ext == 'json') {
			$this->_header("Pragma: no-cache");
			$this->_header("Cache-Control: no-store, no-cache, max-age=0, must-revalidate");
			$this->_header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			$this->_header("Last-Modified: " . gmdate('D, d M Y H:i:s') . ' GMT');

			if (!$textarea) {
				$this->_header('Content-type: application/json');
			}

			$output = json_encode($this->viewVars);
			if (!$noXjson) {
				$this->_header("X-JSON: " . $output);
			}

			return sprintf($format, $output);
		}

		$this->_header('Content-type: application/xml');
		return sprintf($format, $this->toXml($this->viewVars));
	}

/**
 * Dummy method
 *
 * @deprecated deprecated in Webservice view
 */
	public function renderLayout() {
	}


/**
 * The main function for converting to an XML document.
 * Pass in a multi dimensional array and this recrusively loops through and builds up an XML document.
 *
 * @param array $data
 * @param string $rootNodeName - what you want the root node to be - defaultsto data.
 * @param SimpleXMLElement $xml - should only be used recursively
 * @return string XML
 */
	public function toXML($data, $rootNodeName = 'ResultSet', &$xml = null) {
		// turn off compatibility mode as simple xml throws a wobbly if you don't.
		if (ini_get('zend.ze1_compatibility_mode') == 1) ini_set('zend.ze1_compatibility_mode', 0);
		if (is_null($xml)) $xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><{$rootNodeName} />");

		// loop through the data passed in.
		foreach ($data as $key => $value) {
			// no numeric keys in our xml please!
			$numeric = false;
			if (is_numeric($key)) {
				$numeric = 1;
				$key = $rootNodeName;
			}

			// delete any char not allowed in XML element names
			$key = preg_replace('/[^a-z0-9\-\_\.\:]/i', '', $key);

			// if there is another array found recrusively call this function
			if (is_array($value)) {
				$node = $this->isAssoc($value) || $numeric ? $xml->addChild($key) : $xml;

				// recrusive call.
				if ($numeric) $key = 'anon';
				$this->toXml($value, $key, $node);
			} else {
				// add single node.
				$value = htmlentities($value);
    			$xml->addChild($key, $value);
			}
		}

		$doc = new DOMDocument('1.0');
		$doc->preserveWhiteSpace = false;
		$doc->loadXML($xml->asXML());
		$doc->formatOutput = true;
		return $doc->saveXML();
	}

/**
 * Determine if a variable is an associative array
 *
 * @param mixed $variable variable to checked for associativity
 * @return boolean try if variable is an associative array, false if otherwise
 */
	public function isAssoc($variable) {
		return (is_array($variable) && 0 !== count(array_diff_key($variable, array_keys(array_keys($variable)))));
	}

/**
 * Dummy header method to allow for mocking in tests
 *
 * @param string $header 
 * @return void
 */
	protected function _header($header) {
		header($header);
	}

}