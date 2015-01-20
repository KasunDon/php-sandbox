<?php

use OAuth2\Request;

class AuthController extends Controller {

    public function token() {
        return App::make('app.auth.service')
                        ->handleTokenRequest(Request::createFromGlobals())
                        ->send();
    }

    public function resource() {
        $server = App::make('app.auth.service');
        if (!$server->verifyResourceRequest(Request::createFromGlobals())) {
            return $server->getResponse()->send();
        }
        return Response::json(array('success' => true, 'message' => 'You accessed my APIs!'));
    }

}
