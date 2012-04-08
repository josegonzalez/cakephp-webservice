# Webservice Plugin 2.0

For those times when you need a quick and dirty webservice for your data.

## Background

While working on a freelance app, I realized I was spending way too much time trying to create a well-maintained separation between the API and the frontend. There was no need to do this, the api users would figure out how to use it in time. So I decided to create a View class to auto-transform the data into JSON and XML. The result is the `Webservice View`.

Later, I wanted to move an entire controller to webservice, but have it also optionally render html. As a result, I created the `Webservice Component`.

## Requirements

* CakePHP 2.x

## Installation

For 1.3 support, please see the [1.3 branch](https://github.com/josegonzalez/webservice_plugin/tree/1.3).

_[Manual]_

* Download this: [https://github.com/josegonzalez/webservice_plugin/zipball/master](https://github.com/josegonzalez/webservice_plugin/zipball/master)
* Unzip that download.
* Copy the resulting folder to `app/Plugin`
* Rename the folder you just copied to `Webservice`

_[GIT Submodule]_

In your app directory type:

	git submodule add git://github.com/josegonzalez/webservice_plugin.git Plugin/Webservice
	git submodule init
	git submodule update

_[GIT Clone]_

In your plugin directory type

	git clone git://github.com/josegonzalez/webservice_plugin.git Webservice

### Enable plugin

In 2.0 you need to enable the plugin your `app/Config/bootstrap.php` file:

    CakePlugin::load('Webservice');

If you are already using `CakePlugin::loadAll();`, then this is not necessary.


## Usage

Specify the extensions you'd like to parse in `Config/routes.php`, for example:

	Router::parseExtensions('json');

Attach the `Webservice Component` to your controller for an instant _automagic_ webservice:

	<?php
	class PostsController extends AppController {

		public $components = array(
			'RequestHandler',
			'Webservice.Webservice'
		);

	}

Or simply set the `Webservice` View class where necessary (don't forget `Router::parseExtensions()` and `CakeRequest->type()`):

	<?php
	class PostsController extends AppController {

		public $components = array('RequestHandler');

		public function index() {
			$this->request->type(array('json' => 'application/json'));
			$this->viewClass = 'Webservice.Webservice';
			$posts = $this->paginate();
			$this->set(compact('posts'));
		}

	}

Views are not necessary, the View class takes care of everything.

### Blacklisting actions

On occasion, it will be necessary to specify certain actions as not having a webservice response. To do so, you can either specify a property on the Controller or specify it in the component configuration:

	<?php
	class PostsController extends AppController {

		public $viewClass = 'Webservice.Webservice';

		// In the component configuration
		public $components = array(
			'Webservice.Webservice' => array(
				'blacklist' => array('home', 'index')
			)
		);

		// As a class property
		public $webserviceBlacklist = array(
			'home', 'index'
		);

	}

The `$webserviceBlacklist` property will be merged into the `settings` array if available.

You can also specify `*` to blacklist all actions.

	<?php
	class PostsController extends AppController {

		public $webserviceBlacklist = array('*');

	}

The `$webserviceBlacklist` property will be merged into the `settings` array if available.

You can also specify `*` to blacklist all actions.

	<?php
	class PostsController extends AppController {

		public $webserviceBlacklist = array('*');

	}

### Blacklisting variables

It is possible to blacklist certain variables from being output. By default, these are the following reserved variables:

* `debugToolbarPanels`
* `debugToolbarJavascript`
* `webserviceTextarea`
* `webserviceNoxjson`

To blacklist more variables, you can use the `$webserviceBlacklistVars` controller property.

	<?php
	class PostsController extends AppController {

		public $webserviceBlacklistVars = array(
			'_metas'
		);

		public function index() {
			$someMetaInfo = array('some', 'meta', 'info');
			$this->set('_metas', $someMetaInfo); // Will not be in output
		}

	}

### Outputting session flash messages

Session flash messages are not output by default, but this can be turned on through a controller property:

	<?php
	class PostsController extends AppController {

		public $webserviceSessionFlash = true;

	}

This will include all the relevant data for you to decide how to output the flash messages on your current page.

## TODO

1. Unit Tests
2. More thorough documentation
3. YAML and Serialized PHP output

## License

Copyright (c) 2010-2012 Jose Diaz-Gonzalez

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.