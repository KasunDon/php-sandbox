@foreach (\App\Models\Code::$THEMES as $type => $themeSet)
<optgroup label="{{ $type }}">
    @foreach ($themeSet as $themeItem)
        <option value='{{ $themeItem }}' {{{ isset($theme) && $themeItem == $theme ? 'selected' : '' }}}>{{ucfirst(str_replace('_', ' ', $themeItem))}}</option>
    @endforeach
@endforeach

