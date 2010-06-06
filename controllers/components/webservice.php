<?php
/**
 * WebservicesComponent
 *
 * Triggers the Webservice View
 *
 * @package webservice
 * @author Jose Diaz-Gonzalez
 * @version 1.0
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
?>