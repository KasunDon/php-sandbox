<?php

use App\Models\SandBox;
use App\Models\Storage;
use App\Models\Feedback;
use App\Models\Issues;
use App\Models\Utils;
use App\Models\SandboxClient;

/**
 * HomeConroller Class
 */
class HomeController extends BaseController {

    /**
     * Index Controller
     */
    public function index() {
        $versions = SandboxClient::versions();
        $version = array_keys($versions['PHP']);
        return View::make('hello', array('versions' => $versions, 'version' => end($version)));
    }

    /**
     *  Run Controller
     */
    public function run($api, $version) {
       $output = SandboxClient::request($api, $version, Input::get('code'));
        return Response::json(
            array(
                'output' => $output['output'],
                'type' => $api, 
                'datetime' => Utils::datetime()
            )
        );
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
