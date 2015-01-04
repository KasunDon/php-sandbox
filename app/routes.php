<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the Closure to execute when that URI is requested.
  |
 */

Route::get('/', function() {
    return View::make('hello');
});

Route::get('/view-report-issue', function() {
    return View::make('report');
});

Route::get('/share/{codeId}', function($codeId) {
    $document = Storage::instance('phpsources')->getCollection()->findOne(array(
        '_id' => new MongoId($codeId)));
    
    Storage::instance('views')->getCollection()->update(array('_id' => new MongoId($document['_id']->{'$id'})), array('$inc' => array('views' => 1)));
    
    $views = Storage::instance('views')->getCollection()->findOne(array(
        '_id' => new MongoId($document['_id']->{'$id'})));
    
    $document['meta'] = json_encode(array(
        'version' => $document['version'],
        'id' => $document['_id']->{'$id'},
        'create_time' => $document['create_time'],
        'view_link' => Code::VIEW_LINK,
        'views' => $views['views']
    ));
        
    return View::make('hello', $document);
});

Route::get('/view-feedback', function() {
    return View::make('feedback');
});

Route::get('/view-service', function() {
    return View::make('service');
});

Route::get('/view-social', function() {
    return View::make('social');
});


Route::post('/report-issue', array('before' => 'postParams', 'uses' => 'HomeController@reportIssue'));

Route::post('/save-code', array('before' => 'postParams', function() {
$document = Code::doc();
$id = $document['_id']->{'$id'};
Storage::instance('phpsources')->getCollection()->insert($document);
Storage::instance('views')->getCollection()->insert(Views::doc($id));
return json_encode(array('viewId' => $id, 'viewLink' => Code::VIEW_LINK));
}));

Route::post('/send-feedback', array('before' => 'postParams', 'uses' => 'HomeController@sendFeedback'));

Route::filter('postParams', function() {
    $config = array(
        't1' => Issues::$REQUIRED_PARMS,
        'f1' => Feedback::$REQUIRED_PARMS,
        'c1' => Code::$REQUIRED_PARMS
    );

    if (empty(Input::get('vType'))) {
        throw new Exception("Validation Type is missing.");
    }

    foreach ($config[Input::get('vType')] as $parameter) {
        if (empty(Input::get($parameter))) {
            throw new Exception('Required paramter missing. param :: ' . $parameter);
        }
    }
});

Route::post('/api/php/{version}/run', 'HomeController@run');


