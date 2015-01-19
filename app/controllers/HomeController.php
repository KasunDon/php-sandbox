<?php

use App\Models\PHPSandBox;
use App\Models\Storage;
use App\Models\Feedback;
use App\Models\Issues;
use App\Models\Utils;

/**
 * HomeConroller Class
 */
class HomeController extends BaseController {

    /**
     * Index Controller
     */
    public function index() {
        return View::make('hello', array('versions' => PHPSandBox::versions()));
    }

    /**
     *  Run Controller
     */
    public function run() {
        $sandbox = new PHPSandBox(Input::get('v'), Input::get('code'));
        return Response::json(array('output' => $sandbox->execute(), 'datetime' => Utils::datetime()));
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
