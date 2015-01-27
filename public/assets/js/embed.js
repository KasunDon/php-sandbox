var PHPBOX = {};
PHPBOX.globals = {};
PHPBOX.utils = {};
PHPBOX.core = {};

PHPBOX.globals.jQuery = "http://code.jquery.com/jquery-1.11.2.min.js";
PHPBOX.globals.editorSrc = "http://beta.phpbox.info/assets/js/ace/ace.js";

PHPBOX.utils.createScript = function(s, t) {
    var type = t || 'text/javascript';
    var script = document.createElement('script');
    script.src = s;
    script.type = type;
document.body.appendChild(script);
};

PHPBOX.utils.getEditor = function() {
    var editor = ace.edit("editor");
    editor.setTheme("ace/theme/xcode");
    editor.getSession().setMode("ace/mode/php");
    editor.setAutoScrollEditorIntoView(true);
    editor.setOption("minLines", 1);
    editor.setOption("maxLines", 1000);
    return editor;
};

PHPBOX.init = function() {
    var sources = [PHPBOX.globals.editorSrc, PHPBOX.globals.jQuery];
    for (var source in sources) {
        PHPBOX.utils.createScript(sources[source]);
    }

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

    checkReady(function($) {
        PHPBOX.core.editor = PHPBOX.utils.getEditor();
    });
};