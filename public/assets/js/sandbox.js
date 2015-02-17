var SANDBOX = {};

SANDBOX.validation = {};
SANDBOX.utils = {};
SANDBOX.core = {};
SANDBOX.core.content = {};

SANDBOX.core.request = null;
SANDBOX.core.editor = null;
SANDBOX.core.defaultCode = null;
SANDBOX.core.theme = 'xcode';
SANDBOX.core.viewId = null;
SANDBOX.core.viewLink = null;
SANDBOX.core.create_time = null;
SANDBOX.core.version = null;
SANDBOX.core.output = null;
SANDBOX.core.views = null;
SANDBOX.core.shareMode = 0;

SANDBOX.core.selectors = {
    "version-selector": {},
    "theme-selector": {noneSelectedText: "Theme", size: "auto"}
};

SANDBOX.utils.load = function() {
    if ($("#view-code").length) {
        var data = JSON.parse($('#view-code').val());
        SANDBOX.core.viewId = data.id;
        SANDBOX.core.viewLink = data.view_link + data.id;
        SANDBOX.core.create_time = data.create_time;
        SANDBOX.core.version = data.version;
        SANDBOX.core.views = data.views;
        SANDBOX.core.shareMode = 1;
        SANDBOX.core.output = $('#output').text()
        SANDBOX.core.theme = data.theme;
        SANDBOX.core.editor = SANDBOX.utils.initEditor(SANDBOX.core.theme);
        $('#run-datetime').html("<span class='glyphicon glyphicon-time'></span> " + SANDBOX.core.create_time);
        $('#output-zone').show();
        $('#php-version').text($('#php-version').text() + SANDBOX.core.version);
        $('#views').text(SANDBOX.core.views);
        $('#view-link').text(SANDBOX.core.viewLink);
        $('#view-link').attr('href', SANDBOX.core.viewLink);
        $('#view-link-zone').show();
    }
};

SANDBOX.utils.showEmbed = function() {
    $.get('/get-embed/' + SANDBOX.core.viewId, function(output) {
        $('#embed-output').html(output);
        $('#modal-embed').html(output);
    });
};

SANDBOX.utils.getSelection = function(e) {
    return $(e).find(":selected").val();
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
    if (SANDBOX.core.viewId == null || SANDBOX.core.shareMode === 2) {
        $('#progress').show();
        setTimeout(function() {
            var theme = $('#theme-selector').find(":selected").val() || 'xcode';
            $.post('/save-code', {code: SANDBOX.core.editor.getValue(), output:
                        SANDBOX.core.output, create_time: SANDBOX.core.create_time,
                version: SANDBOX.core.version, theme: theme, vType: 'c1'}, function(data) {
                SANDBOX.core.viewId = data.viewId;
                SANDBOX.core.viewLink = data.viewLink + data.viewId;
                SANDBOX.utils.showEmbed();
                $('#view-link').text(SANDBOX.core.viewLink);
                $('#view-link').attr('href', SANDBOX.core.viewLink);
                $('#view-link-zone').show();
                $('#progress').hide();
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

SANDBOX.core.getTheme = function() {
    $.post('/theme-settings', {theme: SANDBOX.core.theme}, function(data) {
        SANDBOX.core.theme = data.theme;
        SANDBOX.core.editor = SANDBOX.utils.initEditor(SANDBOX.core.theme);
        SANDBOX.core.selectTheme();
    });
};

SANDBOX.core.runUpdate = function(b) {
    $("#run").attr('disabled', b);

    var se = ["#stop", "#loading"];

    for (var e in se) {
        if (b === false) {
            $(se[e]).hide();
            continue;
        }
        $(se[e]).show();
    }
};

SANDBOX.core.run = function() {
    $("#stop").on("click", function() {
        SANDBOX.core.request.abort();
        SANDBOX.core.runUpdate(false);
    });

    $("#run").on("click", function() {
        var version = SANDBOX.utils.getSelection('#version-selector');

        if (version == null) {
            alert('Please select PHP runtime version');
            return false;
        }

        if (SANDBOX.core.shareMode === 0 && SANDBOX.core.defaultCode === SANDBOX.core.editor.getValue()) {
            alert("Code editor hasn't changed. Assume there are no code to be run");
            return false;
        }

        SANDBOX.core.runUpdate(true);

        SANDBOX.core.request = $.post('/api/php/' + version + '/run',
                {v: version, code: SANDBOX.core.editor.getValue()}, function(output) {

            SANDBOX.core.runUpdate(false);

            $('#output').text(output.output);

            SANDBOX.core.create_time = output.datetime;

            if (SANDBOX.core.output !== output.output) {
                SANDBOX.core.shareMode = 2;
            }

            SANDBOX.core.output = output.output;
            SANDBOX.core.version = $("#version-selector option:selected").val();
            $('#run-datetime').html("<span class='glyphicon glyphicon-time'></span> " + SANDBOX.core.create_time);
            $('#output-zone').show();
            $('#php-version').text(SANDBOX.core.version);
            $('html, body').animate({scrollTop: $('#output-zone').offset().top}, 'slow');
        });

    });
};

SANDBOX.core.selectTheme = function() {
    $('#theme-selector').val(SANDBOX.core.theme);
};

SANDBOX.core.setTheme = function() {
    $.get('/theme-settings', {clear: true}, function() {
        return SANDBOX.core.getTheme();
    });
};

SANDBOX.utils.viewLoader = function(url, data, assign, interval) {
    var time = interval || false;

    var callback = function(url, data, assign) {
        $.get(url, data, function(data) {
            SANDBOX.core.content[assign] = data;
        });
    };

    callback(url, data, assign);

    if (time !== false) {
        setInterval(function() {
            callback(url, data, assign);
        }, (time));
    }
};

SANDBOX.core.setup = function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
        }
    });

    for (var select in SANDBOX.core.selectors) {
        $('#' + select).selectpicker(SANDBOX.core.selectors[select]);
    }

    SANDBOX.core.editor = SANDBOX.utils.initEditor(SANDBOX.core.theme);

    SANDBOX.core.defaultCode = SANDBOX.core.editor.getValue();

    SANDBOX.utils.viewLoader('/view-service', {}, 'serviceContent', 60000);
    SANDBOX.utils.viewLoader('/view-report-issue', {}, 'reportContent');
    SANDBOX.utils.viewLoader('/view-feedback', {}, 'feedbackContent');
    SANDBOX.utils.viewLoader('/view-social', {}, 'socialContent');
    SANDBOX.utils.viewLoader('/view-terms', {}, 'termsContent');

    $('#theme-selector').change(function() {
        SANDBOX.core.theme = SANDBOX.utils.getSelection(this);
        SANDBOX.core.setTheme();
    });

    SANDBOX.core.run();

    SANDBOX.utils.load();

    if (SANDBOX.core.shareMode !== 1) {
        SANDBOX.core.getTheme();
    }

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

        if (SANDBOX.core.viewId !== null) {
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

        if (SANDBOX.core.viewId !== null) {
            SANDBOX.utils.showEmbed();
        }
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

SANDBOX.utils.initEditor = function(t) {
    var editor = ace.edit("editor");
    editor.setTheme("ace/theme/" + t);
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
        }).fail(function() {
            $("button[data-bb-handler='feedback']").prop("disabled", true);
            $('#feedback-error').show();
            $('#feedback-form').hide();
            SANDBOX.utils.closeModal(5000);
        });
    });
};