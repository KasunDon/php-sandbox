<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="PHP Legacy versions. Run-Debug-Share. Anywhere-Anytime PHP Sandbox running versions of {{ implode(', ', $versions) }}">
        <meta name="author" content="">
        <meta name="_token" content="{{ csrf_token() }}" />
        <title>PHPBox sandbox - Beta version - Debug, Test and Share!</title>
        <link href="{{ asset('/assets/css/bootstrap.css') }}?noCache={{ time() }}" rel="stylesheet">
        <link href="{{ asset('/assets/css/bootstrap-select.min.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('/assets/css/font-awesome.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('/assets/css/bootstrapValidator.min.css') }}" />
        <link rel='stylesheet prefetch' href='{{ asset('/assets/css/font-awesome.css') }}' />
        <link href="{{ asset('/assets/css/jquery.share.css') }}" rel="stylesheet">
        <script src="{{ asset('/assets/js/ie-emulation-modes-warning.js') }}"></script>
        <!--[if lt IE 9]><script src="{{ asset('/assets/js/ie8-responsive-file-warning.js') }}"></script><![endif]-->
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="{{ asset('/assets/js/html5shiv.min.js') }}"></script>
          <script src="{{ asset('/assets/js/respond.min.js') }}"></script>
        <![endif]-->
    </head>
    <body>
        <div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign"></span><b>  We're experiencing database cluster issues on our platform. Trying to resolve issue soon as possible. Apologize for inconvenience caused.</b></div>
        <div class="container">
            <div class="page-header">
                @if (isset($meta))
                <input type="hidden" id="view-code"  value="{{{$meta}}}">
                @endif
                <h1>PHPBox  - RUN, DEBUG and SHARE!</h1>
            </div>
            <p class="lead">Any PHP runtime can be used anytime/anywhere. Also share your code snippet and their output.</p>
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
                                        @foreach ($versions as $lang_version)
                                        <option value='{{$lang_version}}' {{{ isset($version) && $lang_version === $version ? 'selected' : '' }}} >PHP {{$lang_version}}</option>
                                        @endforeach
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
                    <span style="float: right;"><small id="run-datetime"></small></span>
                    <div>PHP Version: <b><small id="php-version"> </small></b></div>

                    <pre id="output" class="well">{{{isset($output)? $output: ''}}}</pre>
                    <span id="view-link-zone" style="float: left; display: none;"> 
                        <div class="row">
                            <span class="glyphicon glyphicon-link"></span> <b>  Link : </b> <a id="view-link"></a> 
                        </div>
                        <div class="row">
                            <span class="glyphicon glyphicon-eye-open"></span> <b>  Views : <small id="views">0</small></b>  
                        </div>
                    </span>
                    <button id="save_share" type="button" class="btn btn-success" style="float: right;"><b>Share</b></button>
                </div>
                
                <br/>
                <div id="embed-output" style="margin-top: 5px;">
                    @if (isset($_id))
                    @include('embed_code')
                    @endif

                </div>

            </div>
        </div>
        <br><br><br>
        <footer class="footer">
            <div class="container-fluid">
                <div class="row show-grid" style="margin-top: 5px;">
                    <div class="col-md-1"></div>
                    <div class="col-md-2 text-muted"><strong>PHPBox</strong> Sandbox</div>
                    <div class="col-md-2"><a id="service" style="cursor: pointer;">Service Status</a><span class="label label-danger" style="margin-left: 10px;">Issues</span></div>
                    <div class="col-md-2"><a id="feedback" style="cursor: pointer;">Send Feedback</a></div>
                    <div class="col-md-2"><a id="terms" style="cursor: pointer;">Terms & Conditions</a></div>
                    <div class="col-md-2">
                        <small><a href="https://www.digitalocean.com/?refcode=2fdebfd067c7">DigitalOcean</a></small>
                        <small> - <strong class="text-muted">Get $10 free credit</strong></small> 
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <script src="{{ asset('/assets/js/jquery-1.11.2.min.js') }}"></script>   	
    <script src="{{ asset('/assets/js/ace/ace.js') }}" type="text/javascript" charset="utf-8"></script>
    <script src="{{ asset('/assets/js/ie10-viewport-bug-workaround.js') }}"></script>
    <script src="{{ asset('/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/assets/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('/assets/js/bootbox.min.js') }}"></script>
    <script src="{{ asset('/assets/js/jquery.share.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/assets/js/bootstrapValidator.min.js') }}"></script>
    <script src="{{ asset('/assets/js/sandbox.min.js') }}?noCache={{ time() }}"></script>
    <script>
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
            
            SANDBOX.core.setup();
    </script>
</body>
</html>
