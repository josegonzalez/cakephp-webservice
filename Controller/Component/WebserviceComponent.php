<?php
/**
 * Webservice Component
 *
 * Triggers the Webservice View
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
 * @subpackage  Webservice.Controller.Component
 * @link        http://github.com/josegonzalez/webservice_plugin
 * @license     MIT License (http://www.opensource.org/licenses/mit-license.php)
 **/
class WebserviceComponent extends Component {

/**
 * Called before the Controller::beforeFilter().
 *
 * @param object  A reference to the controller
 * @return void
 * @access public
 * @link http://book.cakephp.org/view/65/MVC-Class-Access-Within-Components
 */
	public function initialize(&$controller, $settings = array()) {
		$settings = array_merge(array(
			'blacklist' => array(),
		), (array) $settings);

		if (isset($controller->webserviceBlacklist)) {
			$settings['blacklist'] = array_merge(
				(array) $settings['blacklist'],
				(array) $controller->webserviceBlacklist
			);
		}

		if (in_array('*', $settings['blacklist'])) {
			return;
		}

		if (in_array($controller->request->params['action'], $settings['blacklist'])) {
			return;
		}

		if (in_array($controller->request->ext, array('json', 'xml'))) {
			$controller->viewClass = 'Webservice.Webservice';
		}
	}

}