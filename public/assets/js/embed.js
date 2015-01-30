var PHPBOX = {};

PHPBOX.globals = {};
PHPBOX.utils = {};
PHPBOX.core = {};

PHPBOX.core.selectId = '#phpbox-version-selector';
PHPBOX.core.runButton = '#phpbox-code-run';
PHPBOX.core.editorId = '#phpbox-code-editor';
PHPBOX.core.editId = '#phpbox-code-edit';

PHPBOX.globals.jQuery = "http://code.jquery.com/jquery-1.11.2.min.js";
PHPBOX.globals.editorSrc = "http://phpbox.info/assets/js/ace/ace.js";
PHPBOX.globals.select = "http://phpbox.info/assets/js/bootstrap-select.min.js";
PHPBOX.globals.bootstrapjs = "http://phpbox.info/assets/js/bootstrap.min.js";
PHPBOX.globals.blockui = "http://malsup.github.io/jquery.blockUI.js";

PHPBOX.utils.createScript = function(s, t) {
    var type = t || 'text/javascript';
    var script = document.createElement('script');
    script.src = s;
    script.type = type;
    document.body.appendChild(script);
};

PHPBOX.utils.getEditor = function() {
    var editor = ace.edit(PHPBOX.core.editorId.replace('#', ''));
    editor.setTheme("ace/theme/xcode");
    editor.getSession().setMode("ace/mode/php");
    editor.setAutoScrollEditorIntoView(true);
    editor.setOption("minLines", 5);
    editor.setOption("maxLines", 1000);
    return editor;
};

PHPBOX.utils.addScript = function(a) {
    for (var ar in a) {
        PHPBOX.utils.createScript(a[ar]);
    }
};

PHPBOX.init = function() {
    PHPBOX.utils.addScript([PHPBOX.globals.jQuery, PHPBOX.globals.editorSrc]);

    var checkReady = function(callback) {
        if (window.jQuery) {
            callback(jQuery);
        }
        else {
            window.setTimeout(function() {
                checkReady(callback);
            }, 100);
        }
    };

    checkReady(function() {
        $(document).ready(function() {

            PHPBOX.utils.addScript([PHPBOX.globals.bootstrapjs, PHPBOX.globals.blockui]);
            PHPBOX.core.editor = PHPBOX.utils.getEditor();
            $.getScript(PHPBOX.globals.select, function() {
                $(PHPBOX.core.selectId).selectpicker();
            });
            PHPBOX.core.runCode();
        });
    });
};


PHPBOX.core.runCode = function() {
    $(PHPBOX.core.runButton).on("click", function() {
        var version = null;
        version = $(PHPBOX.core.selectId).find(":selected").val();

        if (version == null) {
            alert('Please select PHP runtime version');
            return false;
        }

        $(PHPBOX.core.runButton).attr('disabled', true);

        var unblockEvent = function() {
            $(PHPBOX.core.runButton).show();
            $(PHPBOX.core.runButton).attr('disabled', false);
            $(PHPBOX.core.editId).hide();
        };

        $(PHPBOX.core.editId).on('click', function() {
            $(PHPBOX.core.editorId).unblock();
            unblockEvent();
        });

        $('#loading').show();

        $.post('http://beta.phpbox.info/api/php/' + version + '/run',
                {v: version, code: PHPBOX.core.editor.getValue()}, function(output) {
            $('#loading').hide();
            $(PHPBOX.core.runButton).hide();
            // console.log($.blockUI.defaults.css);
            $.blockUI.defaults.css = {padding: '0 3em 0 3em', margin: 0, position: 'relative', cursor: 'hand', 'overflow-y': 'scroll', 'max-height': '100%'};
            $(PHPBOX.core.editorId).block({message: "<pre id='phpbox-output' class='well pre-phpbox'><strong>PHP Version </strong><small>" + version + "</small> @" + output.datetime + "<br><br>" + output.output + "</pre>",
                onBlock: function() {
                    $(PHPBOX.core.editId).show();
                }});

        });

    });
};