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
    <script type="text/javascript">
var SANDBOX = {};


SANDBOX.validation = {};
SANDBOX.utils = {};
SANDBOX.core = {};

SANDBOX.core.defaultCode = null;
SANDBOX.core.viewId = null;
SANDBOX.core.viewLink = null;
SANDBOX.core.create_time = null;
SANDBOX.core.version = null;
SANDBOX.core.output = null;
SANDBOX.core.views = null;


SANDBOX.utils.loadData = function() {
    if ($("#view-code").length) {
        var data = JSON.parse($('#view-code').val());
        SANDBOX.core.viewId = data.id;
        SANDBOX.core.viewLink = data.view_link + data.id;
        SANDBOX.core.create_time = data.create_time;
        SANDBOX.core.version = data.version;
        SANDBOX.core.views = data.views;
        $('#run-datetime').html("<span class='glyphicon glyphicon-time'></span> " + SANDBOX.core.create_time);
        $('#output-zone').show();
        $('#php-version').text($('#php-version').text() + SANDBOX.core.version);
        $('#save_share').text('Share');
        $('#views').text(SANDBOX.core.views);
        $('#view-link').text(SANDBOX.core.viewLink);
        $('#view-link').attr('href', SANDBOX.core.viewLink);
        $('#view-link-zone').show();
    }
};

SANDBOX.utils.closeModal = function(timeout) {
    timeout = typeof timeout !== 'undefined' ? timeout : 0;
    window.setTimeout(function() {
        bootbox.hideAll();
    }, timeout);
};

SANDBOX.validation.issue = function() {
    $('#report-form').bootstrapValidator({
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            email: {
                validators: {
                    notEmpty: {
                        message: 'The email address is required and cannot be empty'
                    },
                    emailAddress: {
                        message: 'The email address is not a valid'
                    }
                }
            },
            subject: {
                validators: {
                    notEmpty: {
                        message: 'The subject cannot be empty'
                    }
                }
            }
        }
    }).on('success.form.bv', function(e) {
        e.preventDefault();

        $.post("/report-issue", {email: $('#report-email').val(),
            subject: $('#report-subject').val(), issue: $('#report-issue').val(),
            vType: 't1'}, function() {
            $("button[data-bb-handler='report']").hide();
            $('#report-success').show();
            $('#report-form').hide();
            SANDBOX.utils.closeModal(3000);
        })
                .fail(function() {
                    $("button[data-bb-handler='report']").prop("disabled", true);
                    $('#report-error').show();
                    $('#report-form').hide();
                    SANDBOX.utils.closeModal(5000);
                })
    });
};

SANDBOX.utils.initEditor = function() {
    var editor = ace.edit("editor");
    editor.setTheme("ace/theme/xcode");
    editor.getSession().setMode("ace/mode/php");
    return editor;
};

SANDBOX.validation.feedback = function() {
    $('#feedback-form').bootstrapValidator({
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            feedback: {
                validators: {
                    notEmpty: {
                        message: 'Feedback cannot be empty'
                    }
                }
            }
        }
    }).on('success.form.bv', function(e) {
        e.preventDefault();

        $.post("/send-feedback", {feedback: $('#feedback-message').val(), vType: 'f1'}, function() {
            $("button[data-bb-handler='feedback']").hide();
            $('#feedback-success').show();
            $('#feedback-form').hide();
            SANDBOX.utils.closeModal(3000);
        })
                .fail(function() {
                    $("button[data-bb-handler='feedback']").prop("disabled", true);
                    $('#feedback-error').show();
                    $('#feedback-form').hide();
                    SANDBOX.utils.closeModal(5000);
                })
    });
};

$(function() {
    var editor = SANDBOX.utils.initEditor();

    SANDBOX.core.defaultCode = editor.getValue();

    $('.selectpicker').selectpicker();

    $("#run").on("click", function() {
        var version = null;
        version = $('.selectpicker').find(":selected").val();

        if (version == null) {
            alert('Please select PHP runtime version');
            return false;
        }

        if (SANDBOX.core.defaultCode === editor.getValue()) {
            alert("Code editor hasn't changed. Assume there are no code to be run");
            return false;
        }

        $.post('/api/php/' + version + '/run',
                {v: version, code: editor.getValue()}, function(output) {
            output = JSON.parse(output);
            $('#output').text(output.output);
            SANDBOX.core.create_time = output.datetime.date;
            SANDBOX.core.output = output.output;
            SANDBOX.core.version = $("#version-selector option:selected").val();
            $('#run-datetime').html("<span class='glyphicon glyphicon-time'></span> " + SANDBOX.core.create_time);
            $('#output-zone').show();
            $('#php-version').text(SANDBOX.core.version);
            $('html, body').animate({scrollTop: $('#output-zone').offset().top}, 'slow');
        });

    });

    //load content
    var reportContent = null;
    var feedbackContent = null;
    var serviceContent = null;
    var socialContent = null;

    $.get('/view-report-issue', function(data) {
        reportContent = data;
    });

    $.get('/view-feedback', function(data) {
        feedbackContent = data;
    });

    $.get('/view-service', function(data) {
        serviceContent = data;
    });

    $.get('/view-social', function(data) {
        socialContent = data;
    });

    setInterval(function() {
        $.get('/view-service', function(data) {
            serviceContent = data;
        });
    }, 5000);


    SANDBOX.utils.loadData();


    $('#report').on("click", function(e) {
        bootbox.dialog({
            message: reportContent,
            title: "<span class='glyphicon glyphicon-exclamation-sign' style='padding-left:5px;'></span> Report a issue",
            buttons: {
                report: {
                    label: "Report",
                    className: "btn-danger",
                    callback: function() {
                        $('#report-form').trigger('submit');
                        return false;
                    }
                },
                close: {
                    label: "Close",
                    className: "btn-default",
                    callback: function() {

                    }
                }
            }
        });
    });

    $('#feedback').on("click", function(e) {
        bootbox.dialog({
            message: feedbackContent,
            title: "<span class='glyphicon glyphicon-hand-up' style='padding-left:5px;'></span> Send Feedback",
            buttons: {
                feedback: {
                    label: "Send",
                    className: "btn-success",
                    callback: function() {
                        $('#feedback-form').trigger('submit');
                        return  false;
                    }
                },
                close: {
                    label: "Close",
                    className: "btn-default",
                    callback: function() {

                    }
                }
            }
        });
    });

    $('#service').on("click", function(e) {
        bootbox.dialog({
            message: serviceContent,
            title: "<span class='glyphicon glyphicon-wrench' style='padding-left:5px;'></span> Service Status",
            buttons: {
                close: {
                    label: "Close",
                    className: "btn-default",
                    callback: function() {

                    }
                }
            }
        });
    });


    $("#save_share").on("click", function() {
        var shareTitle = "<span class='glyphicon glyphicon-globe' style='padding-left:5px;'></span> Save & Share";

        if (SANDBOX.core.viewId != null) {
            shareTitle = "<span class='glyphicon glyphicon-globe' style='padding-left:5px;'></span> Share";
        }

        bootbox.dialog({
            message: socialContent,
            title: shareTitle,
            buttons: {
                close: {
                    label: "Close",
                    className: "btn-default",
                    callback: function() {

                    }
                }
            }
        });
    });


});
    </script>
</body>
</html>
