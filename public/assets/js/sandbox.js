var SANDBOX = {};

SANDBOX.validation = {};
SANDBOX.utils = {};
SANDBOX.core = {};
SANDBOX.core.content = {};

SANDBOX.core.defaultCode = null;
SANDBOX.core.viewId = null;
SANDBOX.core.viewLink = null;
SANDBOX.core.create_time = null;
SANDBOX.core.version = null;
SANDBOX.core.output = null;
SANDBOX.core.views = null;

SANDBOX.core.content.reportContent = null;
SANDBOX.core.content.feedbackContent = null;
SANDBOX.core.content.serviceContent = null;
SANDBOX.core.content.socialContent = null;
SANDBOX.core.content.termsContent = null;

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

SANDBOX.core.serviceView = function(e) {
    $(e).on("click", function(e) {
        bootbox.dialog({
            message: SANDBOX.core.content.serviceContent,
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
};

SANDBOX.core.socialTab = function() {
    var editor = ace.edit("editor");
    if (SANDBOX.core.viewId == null) {
        $('#progress').show();
        setTimeout(function() {
            $.post('/save-code', {code: editor.getValue(), output:
                        SANDBOX.core.output, create_time: SANDBOX.core.create_time,
                version: SANDBOX.core.version, vType: 'c1'}, function(data) {
                SANDBOX.core.viewId = data.viewId;
                SANDBOX.core.viewLink = data.viewLink + data.viewId;
                $('#view-link').text(SANDBOX.core.viewLink);
                $('#view-link').attr('href', SANDBOX.core.viewLink);
                $('#view-link-zone').show();
                $('#progress').hide();
                $('#save_share').text('Share');
                $('#modal-view-link-zone').show();
                $('#modal-view-link').text(SANDBOX.core.viewLink);
                $('#modal-view-link').attr('href', SANDBOX.core.viewLink);
                $('#social-zone').share({
                    networks: ['facebook', 'pinterest', 'googleplus', 'twitter', 'linkedin', 'tumblr', 'in1', 'email', 'stumbleupon', 'digg'],
                    urlToShare: SANDBOX.core.viewLink,
                });
                $('#social-links').show();
            }).fail(function() {
                $('#progress').hide();
                SANDBOX.utils.closeModal(4000);
                $('#social-error').show();
            });
        }, 2000);
    } else {
        $('#modal-view-link-zone').show();
        $('#modal-view-link').text(SANDBOX.core.viewLink);
        $('#modal-view-link').attr('href', SANDBOX.core.viewLink);
        $('#social-zone').share({
            networks: ['facebook', 'pinterest', 'googleplus', 'twitter', 'linkedin', 'tumblr', 'in1', 'email', 'stumbleupon', 'digg'],
            urlToShare: SANDBOX.core.viewLink,
        });
        $('#social-links').show();
    }
};

SANDBOX.core.setup = function() {
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

    $.get('/view-report-issue', function(data) {
        SANDBOX.core.content.reportContent = data;
    });

    $.get('/view-feedback', function(data) {
        SANDBOX.core.content.feedbackContent = data;
    });

    $.get('/view-service', function(data) {
        SANDBOX.core.content.serviceContent = data;
    });

    $.get('/view-social', function(data) {
        SANDBOX.core.content.socialContent = data;
    });

    $.get('/view-terms', function(data) {
        SANDBOX.core.content.termsContent = data;
    });

    setInterval(function() {
        $.get('/view-service', function(data) {
            SANDBOX.core.content.serviceContent = data;
        });
    }, (600000));

    SANDBOX.utils.loadData();

    $('#report').on("click", function(e) {
        bootbox.dialog({
            message: SANDBOX.core.content.reportContent,
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
            message: SANDBOX.core.content.feedbackContent,
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

    SANDBOX.core.serviceView('#service');

    $("#save_share").on("click", function() {
        var shareTitle = "<span class='glyphicon glyphicon-globe' style='padding-left:5px;'></span> Save & Share";

        if (SANDBOX.core.viewId != null) {
            shareTitle = "<span class='glyphicon glyphicon-globe' style='padding-left:5px;'></span> Share";
        }

        bootbox.dialog({
            message: SANDBOX.core.content.socialContent,
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

    $('#terms').on("click", function(e) {
        bootbox.dialog({
            message: SANDBOX.core.content.termsContent,
            title: "<span class='glyphicon glyphicon-th-list' style='padding-left:5px;'></span> Terms & Conditions",
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
                });
    });
};