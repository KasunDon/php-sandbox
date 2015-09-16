<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="
              PHP Sandbox
              PHP Legacy versions. Run-Debug-Share. 
              Anywhere-Anytime PHP Sandbox running versions of {{ implode(', ', array_keys($versions['PHP'])) }}
              HHVM Versions : {{ implode(', ', array_keys($versions['HHVM'])) }}
              Latest HippyVM
              ">
        <meta name="author" content="PHPBox">
        <meta name="_token" content="{{ csrf_token() }}" />
        <title>PHPBox - Run, Debug and Share! - PHP | HHVM | HIPPYVM  - PHP Sandbox</title>
        <link href="{{ \App\Models\Code::$VIEW_LINK }}assets/css/bootstrap.css?noCache={{ time() }}" rel="stylesheet">
        <link href="{{ \App\Models\Code::$VIEW_LINK }}assets/css/bootstrap-select.min.css" rel="stylesheet">
        <link rel="stylesheet" href="{{ \App\Models\Code::$VIEW_LINK }}assets/css/font-awesome.min.css" />
        <link rel="stylesheet" href="{{ \App\Models\Code::$VIEW_LINK }}assets/css/bootstrapValidator.min.css" />
        <link rel='stylesheet prefetch' href='{{ \App\Models\Code::$VIEW_LINK }}assets/css/font-awesome.css' />
        <link href="{{ \App\Models\Code::$VIEW_LINK }}assets/css/jquery.share.css" rel="stylesheet">
        <script src="{{ \App\Models\Code::$VIEW_LINK }}assets/js/ie-emulation-modes-warning.js"></script>
        <!--[if lt IE 9]><script src="{{ \App\Models\Code::$VIEW_LINK }}assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="{{ \App\Models\Code::$VIEW_LINK }}assets/js/html5shiv.min.js"></script>
          <script src="{{ \App\Models\Code::$VIEW_LINK }}assets/js/respond.min.js"></script>
        <![endif]-->
        <script src="{{ \App\Models\Code::$VIEW_LINK }}assets/js/jquery-1.11.2.min.js"></script>   	
        <script src="{{ \App\Models\Code::$VIEW_LINK }}assets/js/ace/ace.js" type="text/javascript" charset="utf-8"></script>
        <script src="{{ \App\Models\Code::$VIEW_LINK }}assets/js/ace/ext-language_tools.js"></script>
        <script src="{{ \App\Models\Code::$VIEW_LINK }}assets/js/ie10-viewport-bug-workaround.js"></script>
        <script src="{{ \App\Models\Code::$VIEW_LINK }}assets/js/bootstrap.min.js"></script>
        <script src="{{ \App\Models\Code::$VIEW_LINK }}assets/js/bootstrap-select.min.js"></script>
        <script src="{{ \App\Models\Code::$VIEW_LINK }}assets/js/bootbox.min.js"></script>
        <script src="{{ \App\Models\Code::$VIEW_LINK }}assets/js/button.js"></script>
        <script src="{{ \App\Models\Code::$VIEW_LINK }}assets/js/jquery.share.js"></script>
        <script type="text/javascript" src="{{ \App\Models\Code::$VIEW_LINK }}assets/js/bootstrapValidator.min.js"></script>
        <script src="{{ \App\Models\Code::$VIEW_LINK }}assets/js/sandbox.min.js?noCache={{ time() }}"></script>
    </head>
    <body>
        <div id="fb-root"></div>
        <script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id))
        return;
    js = d.createElement(s);
    js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3&appId=693612874005316";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
        <div class="container">
            <div class="page-header">
                @if (isset($meta))
                <input type="hidden" id="view-code"  value="{{{$meta}}}">
                @endif
                <h1>PHP Sandbox made for developers by <b>PHPBox</b></h1>
            </div>
            <p class="lead">Any available PHP | HHVM | HippyVM environments can be used anytime/anywhere. Also share your code and output.</p>
            <p>Easy as <b>1-2-3</b>. Place your code. Select runtime. Hit '<b><i>Run</i></b>' Button</p>
            <br>
            <div class="row">
                <div class="col-md-12 editor-height">  
                    <div  id="copy" class="zero-clipboard"><span  class="btn-clipboard">Copy</span></div>
                    <div class="editor-zone">
                        @if(isset($code))
                        <pre id="editor">{{$code}}</pre>
                        @else
                        <pre id="editor">&lt;&quest;php

echo 'hello world!';</pre>
                        @endif
                    </div>
                </div>
                <div class="row" style="margin: 0 -15px 0 -15px!important; padding-bottom: 10px;">
                    <div class="col-md-12" >
                        <div class="row">
                            <div class="col-md-2">
                                <div class="input-group">
                                    <select id="version-selector" class="selectpicker" data-width="110px" multiple data-max-options="1">
                                        @include('versions')
                                    </select>
                                    <span class="input-group-btn">
                                        <button type="button" id="run" class="btn btn-primary"><b>Run</b></button>
                                        <button type="button" style="display: none" id="stop" class="btn btn-danger"><b>Stop</b></button>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-2 col-md-offset-8">
                                <div class="pull-right">
                                    <div class="btn-group">
                                        <select id="theme-selector" class="selectpicker" data-width="120px" multiple data-max-options="1">
                                            @include('themes')
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id='loading' class="row text-center" style='display:none'>
                    <img src='/assets/images/loading.gif'/> <b><span class="text-muted"> executing ...</span></b>
                </div>

                <div id="output-zone" style="margin-top: 30px; display: none; margin-bottom: 80px;">
                    <div class="row">
                        <div class="col-xs-6" style="margin: 0 0 10px 0!important;">
                            <button id="select_output" type="button" class="btn btn-default" style="float: left;">Select</button>
                        </div>
                        <div class="col-xs-6" style="margin: 0 0 10px 0!important;">
                            <button id="save_share" type="button" class="btn btn-warning" style="float: right;"><b>Keep</b></button>
                        </div>
                    </div>
                    <span style="float: right;"><small id="run-datetime"></small></span>
                    <div><span id="code-type"></span> Version: <b><small id="code-version"> </small></b></div>

                    <pre id="output" class="well">{{{isset($output)? $output: ''}}}</pre>

                    <div class="pull-left">
                        <div id="filter-panel" class="collapse filter-panel">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="tabbable-panel">
                                        <div class="tabbable-line">
                                            <ul id="tabs" class="nav nav-tabs">
                                                <li id="ref">
                                                    <a href="#tab_default_1" data-toggle="tab">
                                                        <span class="glyphicon glyphicon-info-sign"> </span> References </a>
                                                </li>
                                                <li id="vld">
                                                    <a href="#tab_default_2" data-toggle="tab">
                                                        <span class="glyphicon glyphicon-th"> </span> VLD Opcode </a>
                                                </li>

                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="tab_default_1">
                                                    <div class="table-responsive">
                                                        <ul class="list-inline" id="ref-list">

                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="tab_default_2">
                                                    <p><small>Generated by <a href="http://pecl.php.net/package/vld"><b>VLD</b></a> using PHP <b>5.5.9</b></small></p>
                                                    <pre id="vld-data" ></pre>
                                                    <p>Quick tutorial about VLD <small><b>(Vulcan Logic Disassembler)</b></small> <a href="http://rancoud.com/read-phps-opcode/">click here</a></p></p>
                                                </div>
                                                <div class="tab-pane" id="tab_default_3">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>    
                        <button type="button" class="btn toggle btn-small btn-default" data-toggle="collapse" autocomplete="off" data-target="#filter-panel">
                            <span class="glyphicon glyphicon-cog"></span><small> Advanced</small></button>
                    </div>

                </div>
            </div>
            <div class="row" style="margin-top: 5px;">
                <div id="view-link-zone" class="pull-left" style="display: none;">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div >
                                <span class="glyphicon glyphicon-link"></span> <b>  Link : </b> <a id="view-link"></a> 
                            </div>
                            <div>
                                <span class="glyphicon glyphicon-eye-open"></span> <b>  Views : <small id="views">0</small></b>  
                            </div>
                        </div></div>
                </div>

            </div>
            <div class="row">
                <div id="embed-output">
                    @if (isset($_id))
                    @include('embed_code')
                    @endif

                </div>
            </div>

            <br><br>

            <div class="row">
            <div id="adzone">
                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <!-- software -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-9934334610290132"
                     data-ad-slot="2993561801"
                     data-ad-format="auto"></ins>
            </div>
            </div>
<br>
            <div class="row">
                <div class="col-md-5" style="bottom: 4px;">
                    <div class="fb-like"  data-href="https://www.facebook.com/pages/PHPBox-Sandbox/864368126975513" data-layout="standard" data-action="like" data-show-faces="false" data-share="false"></div>
                </div>
                <div class="col-md-6">
                    <!-- Place this tag where you want the +1 button to render. -->
                    <div class="g-plusone" data-annotation="inline" data-width="300" data-href="https://www.google.com/+PhpboxInfocloud" data-share="true"></div>

                    <!-- Place this tag after the last +1 button tag. -->
                    <script type="text/javascript">
                        window.___gcfg = {lang: 'en-GB'};

                        (function() {
                            var po = document.createElement('script');
                            po.type = 'text/javascript';
                            po.async = true;
                            po.src = 'https://apis.google.com/js/platform.js';
                            var s = document.getElementsByTagName('script')[0];
                            s.parentNode.insertBefore(po, s);
                        })();
                    </script>
                </div>
                <div class="col-md-1">
                    <a class="twitter-share-button" href="https://twitter.com/php_box"
                       data-related="twitterdev"
                       data-count="horizontal">
                        Tweet
                    </a>
                    <script>
                        window.twttr = (function(d, s, id) {
                            var js, fjs = d.getElementsByTagName(s)[0], t = window.twttr || {};
                            if (d.getElementById(id))
                                return;
                            js = d.createElement(s);
                            js.id = id;
                            js.src = "https://platform.twitter.com/widgets.js";
                            fjs.parentNode.insertBefore(js, fjs);
                            t._e = [];
                            t.ready = function(f) {
                                t._e.push(f);
                            };
                            return t;
                        }(document, "script", "twitter-wjs"));
                    </script>
                </div>
            </div>
        </div>
    <br><br><br><br>
    <footer class="footer">
        <div class="container-fluid">
            <div class="row show-grid" style="margin-top: 5px;">
                <div class="col-md-1"></div>
                <div class="col-md-2 text-muted"><strong>PHPBox</strong> Sandbox</div>
                <div class="col-md-2"><a id="service" style="cursor: pointer;">Service Status</a><span class="label label-success" style="margin-left: 10px;">OK</span></div>
                <div class="col-md-2"><a id="feedback" style="cursor: pointer;">Send Feedback</a></div>
                <div class="col-md-2"><a id="terms" style="cursor: pointer;">Terms & Conditions</a></div>
                <div class="col-md-2">
                    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                        <input type="hidden" name="cmd" value="_s-xclick">
                        <input type="hidden" name="hosted_button_id" value="NETPW44ZFYCQ6">
                        <input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online.">
                        <img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
                        <small class="text-muted"> <strong>Help us for better service.</strong></small> 
                    </form>
                </div>
            </div>
        </div>
</footer>
<script>
    @if (\App::make('app.config.env')->APP_ENV !== 'local')
            (function(i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function() {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                        m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

    ga('create', 'UA-58231333-1', 'auto');
    ga('send', 'pageview');
            @endif
            SANDBOX.core.setup();
            window.setTimeout(function (){
                (adsbygoogle = window.adsbygoogle || []).push({});
            }, 300);
</script>
</body>
</html>
