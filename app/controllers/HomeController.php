<?php

use App\Models\PHPAPI;
use App\Models\SandBox;
use App\Models\Storage;
use App\Models\Feedback;
use App\Models\Issues;
use App\Models\Utils;
use App\Models\Code;

/**
 * HomeConroller Class
 */
class HomeController extends BaseController {

    /**
     * Index Controller
     */
    public function index() {
        $versions = SandBox::versions();
        return View::make('hello', array('versions' => $versions, 'version' => end($versions), 'settings' => Code::cookieSettings()));
    }

    /**
     *  Run Controller
     */
    public function run() {
        $sandbox = new PHPAPI(Input::get('v'), Input::get('code'));
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
