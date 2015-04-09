<link rel="stylesheet" href="{{ \App\Models\Code::$VIEW_LINK }}assets/css/embed.css">
<link rel="stylesheet" href="{{ \App\Models\Code::$VIEW_LINK }}assets/css/embed-phpbox.css">
<link rel="stylesheet" href="{{ \App\Models\Code::$VIEW_LINK }}assets/css/bootstrap-select.min.css">
<div id="phpbox{{$code}}" class="phpbox">
    <div class="phpbox-file">
        <div class="phpbox-data phpbox-syntax">
            <div class="file-data">
                <div id="phpbox-code-editor">{{$code}}</div>
            </div>
        </div>
        <div class="phpbox-meta">
            <a href="{{ \App\Models\Code::$VIEW_LINK }}code/{{$_id}}/raw" target="_blank" style="float:right;">view raw</a>

            Platform by <a href="{{ \App\Models\Code::$VIEW_LINK }}">PHPBox</a>
            <span style="margin-left: 1em;">
                <select id="phpbox-version-selector" class="selectpicker dropup" data-container="body" data-width="110px" multiple data-max-options="1">
                    @include('versions')
                </select>
                <button id="phpbox-code-run" type="button" class="btn btn-sm btn-primary">Run</button>
                <button id="phpbox-code-edit" type="button" class="btn btn-sm btn-danger" style="display: none">Edit</button>
                <span id="loading" style="display: none; margin-left: 1em"><b>Executing...</b></span>
            </span>

        </div>
    </div>
</div>
<script src="{{ \App\Models\Code::$VIEW_LINK }}assets/js/embed.js" type="text/javascript" charset="utf-8"></script>
<script>
PHPBOX.init();
</script>
