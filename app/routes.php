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

Route::get('/{codeId}', function($codeId) {
    $document = \App\Models\Code::getCode($codeId);
    return View::make('hello', $document);
})->where('codeId', '[A-Za-z0-9]+');
;

Route::get('/share/{codeId}', function($codeId) {
    return Redirect::to("/$codeId");
});

Route::get('/get-embed/{codeId}', function($codeId) {
    return View::make('embed_code')->with('_id', $codeId);
    ;
});

Route::get('/view-feedback', function() {
    return View::make('feedback');
});

Route::get('/testing-v2', function() {
    
});

Route::get('/api/notice', array('before' => 'csrf', function() {
        $notices = json_decode(file_get_contents(getenv("SANDBOX_NOTICE")), true);
        return Response::json($notices);
    }));

Route::post('/api/vld-data', array('before' => 'csrf', function() {
        return Response::json(array('output' => App\Models\VLD::init(Input::get('code'))->execute()));
    }));

        Route::post('/api/code-ref', array('before' => 'csrf', function() {
                $refs = App\Models\SandboxClient::getPHPSyntaxRefs(Input::get('v'), Input::get('code'));
                return Response::json(array('output' => $refs["output"], 'source' => Input::get('code')));
            }));

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
                            $deafult = 'PHP';
                            $theme = Input::get('theme');
                            $inputVersion = Input::get('version');
                            $type = Input::get('type');
                            $allVersions = \App\Models\SandboxClient::versions();
                            $phpVersions = array_keys($allVersions[$deafult]);
                            $version = empty($inputVersion) ? end($phpVersions) : $inputVersion;
                            $type = empty($type) ? $deafult : $type;
                            $response = Response::json(array('theme' => $theme, 'version' => $version, 'type' => $type));
                            $response->headers->setCookie(Cookie::make('tstgs', "$theme|$version|$type", 43200));
                        } else {
                            $parts = explode('|', $settings);
                            $response = Response::json(array('theme' => $parts[0], 'version' => $parts[1], 'type' => $parts[2]));
                        }
                        return $response;
                    }));

                        Route::get('/view-terms', function() {
                            return View::make('terms');
                        });

                        Route::post('/auth/token', 'AuthController@token');
                        Route::post('/auth/resource', 'AuthController@resource');

                        Route::post('/report-issue', array('before' => array('postParams', 'csrf'), 'uses' => 'HomeController@reportIssue'));

                        Route::post('/store', array('before' => array('postParams', 'csrf'), function() {
                        $document = \App\Models\Code::doc();
                        $id = $document['_id']->{'$id'};

                        \App\Models\Storage::instance('phpsources')->getCollection()->insert($document);

                        $view = \App\Models\Views::doc($id);

                        \App\Models\Storage::instance('views')->getCollection()->insert($view);

                        return Response::json(array('viewId' => $view['tracking_code'], 'viewLink' => \App\Models\Code::$VIEW_LINK));
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

                        Route::post('/api/{sandbox}/{version}', 'HomeController@run');


                        