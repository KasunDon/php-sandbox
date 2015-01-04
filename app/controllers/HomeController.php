<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/
        
        public function run() {
            $sandbox = new PHPSandBox(Input::get('v'), Input::get('code'));
            echo json_encode(array('output' => $sandbox->execute(), 'datetime' => new DateTime()));
        }
        
        public function reportIssue() {
            Storage::instance('issues')->getCollection()->insert(Issues::doc());
        }
        
         public function sendFeedback() {
            Storage::instance('feedback')->getCollection()->insert(Feedback::doc());
        }
}
