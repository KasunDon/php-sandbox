<?php

/**
 * HomeConroller Class
 */
class HomeController extends BaseController {

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
