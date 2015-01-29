<link rel="stylesheet" href="http://{{$_SERVER['HTTP_HOST']}}/assets/css/embed.css">
<link rel="stylesheet" href="http://{{$_SERVER['HTTP_HOST']}}/assets/css/embed-phpbox.css">
<link rel="stylesheet" href="http://{{$_SERVER['HTTP_HOST']}}/assets/css/bootstrap-select.min.css">
<div id="phpbox{{$code}}" class="phpbox">
    <div class="phpbox-file">
        <div class="phpbox-data phpbox-syntax">
            <div class="file-data">
                <div id="phpbox-code-editor">{{$code}}</div>
            </div>
        </div>
        <div class="phpbox-meta">
            <a href="http://{{$_SERVER['HTTP_HOST']}}/code/{{$_id}}/raw" style="float:right;">view raw</a>

            Platform by <a href="https://phpbox.info">PHPBox</a>
            <span style="margin-left: 1em;">
                <select id="phpbox-version-selector" class="selectpicker dropup" data-container="body" data-width="110px" multiple data-max-options="1">
                    @foreach ($versions as $lang_version)
                    <option value='{{$lang_version}}' {{{ isset($version) && $lang_version == $version  ? 'selected' : '' }}}  >PHP {{$lang_version}}</option>
                    @endforeach
                </select>
                <button id="phpbox-code-run" type="button" class="btn btn-sm btn-primary">Run</button>
                <button id="phpbox-code-edit" type="button" class="btn btn-sm btn-danger" style="display: none">Edit</button>
                <span id="loading" style="display: none; margin-left: 1em"><b>Executing...</b></span>
            </span>

        </div>
    </div>
</div>
<script src="http://{{$_SERVER['HTTP_HOST']}}/assets/js/embed.js" type="text/javascript" charset="utf-8"></script>
<script>
PHPBOX.init();
</script>
