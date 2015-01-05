<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="PHP Legacy versions. Run-Test-Debug-Share. Anywhere-Anytime PHP Sandbox running versions of {{ implode(', ', array_keys(PHPSandBox::$VERSIONS)) }}">
        <meta name="author" content="Kasun Don">
        <title>PHP sandbox - Beta version</title>
        <link href="/assets/css/bootstrap.css" rel="stylesheet">
        <link href="/assets/css/sticky-footer.css" rel="stylesheet">
        <link href="/assets/css/grid.css" rel="stylesheet">
        <link href="/assets/css/bootstrap-select.min.css" rel="stylesheet">
        <link href="/assets/css/docs.css" rel="stylesheet">
        <link rel="stylesheet" href="//cdn.jsdelivr.net/fontawesome/4.2.0/css/font-awesome.min.css" />
        <link rel="stylesheet" href="//cdn.jsdelivr.net/jquery.bootstrapvalidator/0.5.3/css/bootstrapValidator.min.css"/>
        <link rel='stylesheet prefetch' href='http://netdna.bootstrapcdn.com/font-awesome/3.1.1/css/font-awesome.css'>
        <link href="/assets/css/jquery.share.css" rel="stylesheet">
        <script src="/assets/js/ie-emulation-modes-warning.js"></script>
        <!--[if lt IE 9]><script src="/assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="container">
            <div class="page-header">
                @if (isset($meta))
                <input type="hidden" id="view-code"  value="{{{$meta}}}">
                @endif
                <h1>Sandbox - DEBUG, TEST and SHARE!</h1>
            </div>
            <p class="lead">Any PHP runtime can be used anytime/anywhere. Also share your code snippet and their output.</p>
            <p>Easy as <b>1-2-3</b>. Place your code. Select runtime. Hit '<b><i>Run</i></b>' Button</p>
            <br>
            <div class="row">
                <div class="col-md-12" style="height: 350px;">  
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

                <div class="row">

                    <div class="col-md-2">
                        <select id="version-selector" class="selectpicker" data-width="110px" multiple data-max-options="1">
                            @foreach (array_keys(PHPSandBox::$VERSIONS) as $lang_version)
                            <option value='{{$lang_version}}' {{{ isset($version) && $lang_version == $version  ? 'selected' : '' }}}  >PHP {{$lang_version}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-9" style="padding-left: 10px; float: left">
                        <button type="button"  id="run" class="btn btn-primary">Run</button>
                    </div>

                    <div class="col-md-1" style="float: right; padding-left: 5px; padding-top: 0;">
                        <a id="report" class="reportLink">Report</a>
                    </div>

                </div>

                <div id="output-zone" style="margin-top: 30px; display: none; margin-bottom: 20px;">
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
                    <button id="save_share" type="button" class="btn btn-info" style="float: right;">Save & Share</button>
                </div>


            </div>
        </div>

        <footer class="footer">
            <div class="container">
                <table  style="border: none;" >
                    <tr>
                        <td  valign="middle" class="col-md-3 text-muted">Cloud Sandbox</td>
                        <td valign="middle" class="col-md-3"><a id="service" style="cursor: pointer;">Service Status</a><span class="label label-success" style="margin-left: 10px;">OK</span></td>
                        <td  valign="middle" class="col-md-2"><a id="feedback" style="cursor: pointer;">Send Feedback</a></td>
                        <td  valign="middle" class="col-md-1"></td>
                    </tr>
                </table>

            </div>
        </div>
    </footer>
    <script src="http://code.jquery.com/jquery-1.11.2.min.js"></script>   	
    <script src="/assets/js/ace/ace.js" type="text/javascript" charset="utf-8"></script>
    <script src="/assets/js/ie10-viewport-bug-workaround.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/bootstrap-select.min.js"></script>
    <script src="/assets/js/docs.min.js"></script>
    <script src="/assets/js/bootbox.min.js"></script>
    <script src="/assets/js/jquery.share.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/jquery.bootstrapvalidator/0.5.3/js/bootstrapValidator.min.js"></script>
    <script src="/assets/js/sandbox.min.js"></script>
    <script>
$(function() {
    SANDBOX.core.setup();
});
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

    </script>
</body>
</html>
