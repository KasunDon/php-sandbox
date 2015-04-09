@foreach ($versions as $lang => $version_items)
    <optgroup label="{{$lang}}">
    @foreach (array_keys($version_items) as $version_item)
        <option value='{{$lang}} {{$version_item}}' {{{ isset($version) && $version_item === $version ? 'selected' : '' }}} >{{$lang}} {{$version_item}}</option>
    @endforeach
@endforeach