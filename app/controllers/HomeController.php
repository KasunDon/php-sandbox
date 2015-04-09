<?php

use App\Models\PHPAPI;
use App\Models\SandBox;
use App\Models\Storage;
use App\Models\Feedback;
use App\Models\Issues;
use App\Models\Utils;
use App\Models\HHVM;

/**
 * HomeConroller Class
 */
class HomeController extends BaseController {

    /**
     * Index Controller
     */
    public function index() {
        $versions = SandBox::versions();
        $version = array_keys($versions['PHP']);
        return View::make('hello', array('versions' => $versions, 'version' => end($version)));
    }

    /**
     *  Run Controller
     */
    public function run($sandbox, $version) {
        switch ($sandbox = strtoupper($sandbox)) {
            case 'PHP':
                $api = new PHPAPI($version, Input::get('code'));
                break;
            
            case 'HHVM':
                $api = new HHVM(Input::get('code'), $version);
                break;
            
            default:
                $api = new PHPAPI($version, Input::get('code'));
        }
        
        return Response::json(
            array(
                'output' => $api->execute(),
                'type' => $sandbox, 
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
