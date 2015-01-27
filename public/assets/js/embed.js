var PHPBOX = {};
PHPBOX.globals = {};
PHPBOX.utils = {};
PHPBOX.core = {};

PHPBOX.globals.jQuery = "http://code.jquery.com/jquery-1.11.2.min.js";
PHPBOX.globals.editorSrc = "/assets/js/ace/ace.js";

PHPBOX.utils.createScript = function(s, t) {
    var type = t || 'text/javascript';
    var script = document.createElement('script');
    script.src = s;
    script.type = type;

    console.log(document.getElementsByTagName('script'));
    document.getElementsByTagName('script')[0].appendChild(script);
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
    var sources = [PHPBOX.globals.jQuery];
    for (var source in sources) {
        PHPBOX.utils.createScript(sources[source]);
    }

    $.getScript(PHPBOX.globals.editorSrc, function() {
        PHPBOX.core.editor = PHPBOX.utils.getEditor();
    });

};