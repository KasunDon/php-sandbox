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
    $document = \App\Models\Code::getCode($codeId);
    return View::make('hello', $document);
});

Route::get('/get-embed/{codeId}', function($codeId) {
    return View::make('embed_code')->with('_id', $codeId);;
});

Route::get('/view-feedback', function() {
    return View::make('feedback');
});

Route::get('/code/{code}/raw', function($code) {
    $document = \App\Models\Code::getCode($code);
    $response = Response::make(html_entity_decode($document['code']));
    $response->header('Content-Type', "text/plain");
    return $response;
});

Route::get('/embed.js', function() {
    $document = \App\Models\Code::getCode(Input::get('c'));
    $content = json_encode(View::make('embed_content', $document)->render());
    
       $jsRaw = <<<EOF
    <iframe id="phpbox-embed" style="border: none; width: 100%; height: 100%;  display: block" src="about:blank"></iframe>
    <script type="text/javascript">
var doc = document.getElementById('phpbox-embed').contentWindow.document;
doc.open();
doc.write($content);
doc.close();
</script>
</script>
EOF;
    $response = Response::make('document.write(' . json_encode($jsRaw) . ');');
    $response->header('Content-Type', "text/javascript");
    return $response;
});
Route::get('/testing', function() {
     return View::make('embed_testing');
});


Route::get('/view-service', function() {
    return View::make('service');
});

Route::get('/view-social', function() {
    return View::make('social');
});

Route::match(array('GET', 'POST'), '/usr-slct', array('before' => 'csrf', function() {
$settings = App\Models\Code::cookieSettings();
$response = null;

if (Input::get('clear')) {
    $response = Response::make('reset')->withCookie(Cookie::forget('tstgs'));
} else if (empty($settings)) {
    $theme = Input::get('theme');
    $version = empty(Input::get('version'))? end(PHPSandBox::versions()): Input::get('version');
    $response = Response::json(array('theme' => $theme, 'version' => $version));
    $response->headers->setCookie(Cookie::make('tstgs', "$theme|$version", 43200));
} else {
    $parts = explode('|', $settings);
    $response = Response::json(array('theme' => $parts[0], 'version' => $parts[1]));
}

return $response;
}));


Route::get('/view-terms', function() {
    return View::make('terms');
});

Route::post('/auth/token', 'AuthController@token');
Route::post('/auth/resource', 'AuthController@resource');

Route::post('/report-issue', array('before' => array('postParams', 'csrf'), 'uses' => 'HomeController@reportIssue'));

Route::post('/save-code', array('before' => array('postParams', 'csrf'), function() {
$document = \App\Models\Code::doc();
$id = $document['_id']->{'$id'};

\App\Models\Storage::instance('phpsources')->getCollection()->insert($document);
\App\Models\Storage::instance('views')->getCollection()->insert(\App\Models\Views::doc($id));

return Response::json(array('viewId' => $id, 'viewLink' => \App\Models\Code::$SHARE_LINK));
})
);

Route::post('/send-feedback', array('before' => array('postParams', 'csrf'), 'uses' => 'HomeController@sendFeedback'));

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


