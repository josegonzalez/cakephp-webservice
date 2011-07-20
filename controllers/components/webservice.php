<?php
/**
 * Webservice Component
 *
 * Triggers the Webservice View
 *
 * PHP versions 4 and 5
 *
 * Copyright 2010, Jose Diaz-Gonzalez
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the below copyright notice.
 *
 * @copyright   Copyright 2010, Jose Diaz-Gonzalez
 * @package     webservice
 * @subpackage  webservice.controllers.components
 * @link        http://github.com/josegonzalez/webservice_plugin
 * @license     MIT License (http://www.opensource.org/licenses/mit-license.php)
 **/
class WebserviceComponent extends Object {

/**
 * Called before the Controller::beforeFilter().
 *
 * @param object  A reference to the controller
 * @return void
 * @access public
 * @link http://book.cakephp.org/view/65/MVC-Class-Access-Within-Components
 */
	function initialize(&$controller, $settings = array()) {
		if (in_array($controller->RequestHandler->ext, array('json', 'xml'))) {
			$controller->view = 'Webservice.Webservice';
		}
	}

}