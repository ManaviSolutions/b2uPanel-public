<?php
namespace B2uPanel;

class B2uPanelAction extends \B2U\Core\Action {

	// b2u.panel.js uses specific JSON responses to properly set content
	// for new panels to load and display. this function will setup all
	// these required fields, and provide an easy to use interfce for a
	// user to communicate back-forth with JavaScript using the $args &
	// $options parameters.
	// 
	// @param $content - the content to apply b2upanel data-effect on
	// @param $code - 200 to allow content to be processed else fires
	//				  error.b2upanel event in JavaScript.
	// @param $args - optional arguments to pass to JavaScript and is
	//				  available to the application in success.b2upanel
	//				  in e.response.args
	// @param $options - allow override of the b2upanel configurations
	//					 for example to turn on/off binding, the modes
	//					 and effects, and other runtime options.
	public function buildResponse($content, $args = [], $code = 200, $options = []) {
		// populate the response with the default JSON used by b2u.panel.js
		$this->Response->setHeader("Content-Type", "application/json")
						->setContent(json_encode([
								"status_code" => $code,
								"content" => $content,
								"args" =>  ($this->Parameters["_b2upanel_args"] ?? []) + $args,
								"options" => $options
							]
						));
	}

	// when in panel actions a user can redirect completely off the panel
	// page by calling this method, which will set the special "redirect"
	// parameter that will be used by b2u.panel.js to perform a redirect.
	public function redirect($url, $args = [], $options = []) {
		$this->Response->setHeader("Content-Type", "application/json")
						->setContent(json_encode([
								"redirect" => true,
								"status_code" => 200,
								"content" => $url,
								"args" =>  ($this->Parameters["_b2upanel_args"] ?? []) + $args,
								"options" => $options
							]
						));
	}

	// this function is called by the b2uframework prior to calling methods
	// to provide the plugin developers to move any data they want into the
	// main Parameters array. this is useful in cases where the plugin will
	// generate data in arbitrary fields, but they would like them to be in
	// and easy to access first-level key/value entry in the Parameters.
	public function modifyRequest() {
		// b2u.panel.js will post to endpoints with the following parameters:
		// [
		//		_b2upanel_id
		//		_b2upanel_endpoint
		//		_b2upanel_options
		//		_b2upanel_args
		// ]
		// the args will be the form, or refresh data submitted, as well as
		// data provided in the options.args on initial setup. This data is
		// needed to be placed into _REQUEST parameters for b2uFramework to
		// be able to call methods within an action when mapping signatures
		// for parameter to functions.
		if (isset($this->Parameters["_b2upanel_args"])) {
			// without losing the existing data in _REQUEST append the args
			return array_replace_recursive($this->Parameters, $this->Parameters["_b2upanel_args"]);
		}
		return $this->Parameters;
	}

	public function submit() {
		throw new \Exception("submit must be overloaded by derive class!");
	}
}