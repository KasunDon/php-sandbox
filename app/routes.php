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


Route::get('/', 'HomeController@index');


Route::get('/view-report-issue', function() {
    return View::make('report');
});

Route::get('/share/{codeId}', function($codeId) {
    $document = \App\Models\Storage::instance('phpsources')->getCollection()->findOne(array(
        '_id' => new MongoId($codeId)));

    if (!Session::has('visit-' . $codeId)) {
        Session::put('visit-' . $codeId, true);

        \App\Models\Storage::instance('views')
                ->getCollection()
                ->update(array('_id' => new MongoId($document['_id']->{'$id'})), array('$inc' => array('views' => 1)));
    }
    $views = \App\Models\Storage::instance('views')->getCollection()->findOne(array(
        '_id' => new MongoId($document['_id']->{'$id'})));

    $document['meta'] = json_encode(array(
        'version' => $document['version'],
        'id' => $document['_id']->{'$id'},
        'create_time' => $document['create_time'],
        'view_link' => \App\Models\Code::$VIEW_LINK,
        'views' => $views['views']
    ));
        
    $document['versions'] = App\Models\PHPSandBox::versions();

    return View::make('hello', $document);
});

Route::get('/view-feedback', function() {
    return View::make('feedback');
});

Route::get('/view-testing', function() {
    
});

Route::get('/view-service', function() {
    return View::make('service');
});

Route::get('/view-social', function() {
    return View::make('social');
});

Route::get('/view-terms', function() {
    return View::make('terms');
});

Route::post('/report-issue', array('before' => 'postParams', 'uses' => 'HomeController@reportIssue'));

Route::post('/save-code', array('before' => 'postParams', function() {
$document = \App\Models\Code::doc();
$id = $document['_id']->{'$id'};

\App\Models\Storage::instance('phpsources')->getCollection()->insert($document);
\App\Models\Storage::instance('views')->getCollection()->insert(\App\Models\Views::doc($id));

return Response::json(array('viewId' => $id, 'viewLink' => \App\Models\Code::$VIEW_LINK));
})
);

Route::post('/send-feedback', array('before' => 'postParams', 'uses' => 'HomeController@sendFeedback'));

Route::filter('postParams', function() {
    $config = array(
        't1' => \App\Models\Issues::$REQUIRED_PARMS,
        'f1' => \App\Models\Feedback::$REQUIRED_PARMS,
        'c1' => \App\Models\Code::$REQUIRED_PARMS
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


