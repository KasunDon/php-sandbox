<?php

use App\Models\PHPSandBox;

/**
 * HomeConroller Class
 */
class HomeController extends BaseController {

    /**
     * Index Controller
     */
    public function index() {
        return View::make('hello', array('versions' => array_keys(PHPSandBox::$VERSIONS)));
    }

    /**
     *  Run Controller
     */
    public function run() {
        $sandbox = new PHPSandBox(Input::get('v'), Input::get('code'));
        return Response::json(array('output' => $sandbox->execute(), 'datetime' => new DateTime()));
    }

    /**
     * Report a issue Controller
     */
    public function reportIssue() {
        Storage::instance('issues')->getCollection()->insert(Issues::doc());
    }

    /**
     * Send Feedback Controller
     */
    public function sendFeedback() {
        Storage::instance('feedback')->getCollection()->insert(Feedback::doc());
    }

}
