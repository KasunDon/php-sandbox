var SANDBOX = {};

SANDBOX.validation = {};
SANDBOX.utils = {};
SANDBOX.core = {};
SANDBOX.core.content = {};

SANDBOX.core.content.tabBackup = [];
SANDBOX.core.request = null;
SANDBOX.core.editor = null;
SANDBOX.core.defaultCode = null;
SANDBOX.core.theme = 'xcode';
SANDBOX.core.viewId = null;
SANDBOX.core.viewLink = null;
SANDBOX.core.create_time = null;
SANDBOX.core.version = [];
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
        SANDBOX.core.version[1] = data.version;
        SANDBOX.core.version[0] = data.type;
        SANDBOX.core.views = data.views;
        SANDBOX.core.shareMode = 1;
        SANDBOX.core.output = $('#output').text()
        SANDBOX.core.theme = data.theme || SANDBOX.core.theme;
        SANDBOX.core.editor = SANDBOX.utils.initEditor(SANDBOX.core.theme);
        $('#run-datetime').html("<span class='glyphicon glyphicon-time'></span> " + SANDBOX.core.create_time);
        $('#output-zone').show();
        $('#code-type').text(SANDBOX.core.version[0]);
        $('#code-version').text(SANDBOX.core.version[1]);
        $('#views').text(SANDBOX.core.views);
        $('#view-link').text(SANDBOX.core.viewLink);
        $('#view-link').attr('href', SANDBOX.core.viewLink);
        $('#view-link-zone').show();
        SANDBOX.core.tabControl(SANDBOX.core.getApiPayload());
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
            $.post('/store', {code: SANDBOX.core.editor.getValue(), output:
                        SANDBOX.core.output, create_time: SANDBOX.core.create_time,
                version: SANDBOX.core.version[1], type: SANDBOX.core.version[0], theme: theme, vType: 'c1'}, function(data) {
                SANDBOX.core.viewId = data.viewId;
                SANDBOX.core.viewLink = data.viewLink + data.viewId;

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
                SANDBOX.utils.showEmbed();
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

SANDBOX.core.setSettings = function() {
    $.post('/usr-slct', {theme: SANDBOX.core.theme, version: SANDBOX.core.version[1], type: SANDBOX.core.version[0]}, function(data) {
        SANDBOX.core.theme = data.theme;
        SANDBOX.core.version[1] = data.version;
        SANDBOX.core.version[0] = data.type;
        SANDBOX.core.editor = SANDBOX.utils.initEditor(SANDBOX.core.theme);
        SANDBOX.core.setUI();
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

SANDBOX.core.getApiPayload = function() {
    return {v: SANDBOX.core.version[1], code: SANDBOX.core.editor.getValue()};
};

SANDBOX.core.disableTabs = function(tabs) {
    for (var index in tabs) {
        $('#' + tabs[index]).removeClass('active').addClass('disabled');
        var tab = $('#' + tabs[index] + " a").attr('href');
        SANDBOX.core.content.tabBackup[tab] = $(tab).html();
        $(tab).html('<i>Option not supported.</i>');
    }
};

SANDBOX.core.activateAllTabs = function() {
    $('#tabs li').each(function()
    {
        $(this).removeClass('disabled');
        $(this).find('a').each(function() {
            var tab = $(this).attr('href');
            if (typeof SANDBOX.core.content.tabBackup[tab] !== "undefined") {
                $(tab).html(SANDBOX.core.content.tabBackup[tab]);
            }
        });
    });
}

SANDBOX.core.getRefs = function(params) {
    $.post('/api/code-ref', params, function(data) {
        var content = '';

        for (var ref in data.output) {
            content += "<li><a href='" + data.output[ref] + "' style='cursor: help;'>" + ref + "</a></li>";
        }

        if (content === '') {
            content = '<li><b>No Internal method references found.</b></li>'
        }
        $('#ref-list').html(content);
    });
};

SANDBOX.core.getVLD = function(params) {
    $.post('/api/vld-data', params, function(data) {
        var content = '';

        content = data.output;

        if (content === '') {
            content = '<li><b>No VLD data available.</b></li>'
        }

        $('#vld-data').html(content);
    });
};

SANDBOX.core.run = function() {
    $("#stop").on("click", function() {
        SANDBOX.core.request.abort();
        SANDBOX.core.runUpdate(false);
    });

    $("#run").on("click", function() {
        SANDBOX.core.version = SANDBOX.utils.getSelection('#version-selector');
        SANDBOX.core.version = SANDBOX.core.version.split(" ");

        SANDBOX.core.runUpdate(true);

        var payload = SANDBOX.core.getApiPayload();

        SANDBOX.core.request = $.post('/api/' + SANDBOX.core.version[0].toLowerCase() + '/' + SANDBOX.core.version[1],
                payload, function(output) {

                    SANDBOX.core.runUpdate(false);

                    $('#output').text(output.output);

                    SANDBOX.core.create_time = output.datetime;

                    if (SANDBOX.core.output !== output.output) {
                        SANDBOX.core.shareMode = 2;
                    }

                    SANDBOX.core.output = output.output;
                    $('#run-datetime').html("<span class='glyphicon glyphicon-time'></span> " + SANDBOX.core.create_time);
                    $('#output-zone').show();
                    $('#code-type').text(SANDBOX.core.version[0]);
                    $('#code-version').text(SANDBOX.core.version[1]);
                    $('html, body').animate({scrollTop: $('#output-zone').offset().top}, 'slow');
                });

        SANDBOX.core.tabControl(payload);
    });
};

SANDBOX.core.tabControl = function(payload) {
    if (SANDBOX.core.version[0].toLowerCase() === 'php') {
        SANDBOX.core.activateAllTabs();
        SANDBOX.core.getRefs(payload);
        SANDBOX.core.getVLD(payload);
    } else {
        SANDBOX.core.disableTabs(['ref', 'vld']);
    }

    SANDBOX.core.tabAutoFocus();
};

SANDBOX.core.tabAutoFocus = function() {
    $('#tabs li').each(function()
    {
        var status = $(this).attr('class');
        if (status !== "disabled") {
            $(this).find('a').each(function() {
                $(this).tab('show');
            });
            return false;
        }
    });
};

SANDBOX.core.setUI = function() {
    $('#theme-selector').val(SANDBOX.core.theme);
    $('#version-selector').val(SANDBOX.core.version[0] + " " + SANDBOX.core.version[1]);
    $('.selectpicker').selectpicker('refresh');
};

SANDBOX.core.clearSettings = function() {
    $.get('/usr-slct', {clear: true}, function() {
        return SANDBOX.core.setSettings();
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

    $('a[data-toggle="tab"]').on('click', function() {
        if ($(this).parent('li').hasClass('disabled')) {
            return false;
        }
        ;
    });

    SANDBOX.core.editor = SANDBOX.utils.initEditor(SANDBOX.core.theme);

    SANDBOX.core.defaultCode = SANDBOX.core.editor.getValue();

    SANDBOX.utils.viewLoader('/view-service', {}, 'serviceContent');
    SANDBOX.utils.viewLoader('/view-report-issue', {}, 'reportContent');
    SANDBOX.utils.viewLoader('/view-feedback', {}, 'feedbackContent');
    SANDBOX.utils.viewLoader('/view-social', {}, 'socialContent');
    SANDBOX.utils.viewLoader('/view-terms', {}, 'termsContent');

    $('#theme-selector, #version-selector').change(function() {
        SANDBOX.core.theme = SANDBOX.utils.getSelection('#theme-selector') || SANDBOX.core.theme;
        SANDBOX.core.version = SANDBOX.utils.getSelection('#version-selector');
        SANDBOX.core.version = SANDBOX.core.version.split(" ");
        SANDBOX.core.clearSettings();
    });

    SANDBOX.core.run();

    SANDBOX.utils.load();

    if (SANDBOX.core.shareMode !== 1) {
        SANDBOX.core.setSettings();
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
    ace.require("ace/ext/language_tools");
    var editor = ace.edit("editor");
    editor.setTheme("ace/theme/" + t);
    editor.getSession().setMode("ace/mode/php");
    editor.setOptions({
        enableBasicAutocompletion: true,
        enableSnippets: true,
        enableLiveAutocompletion: false
    });
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