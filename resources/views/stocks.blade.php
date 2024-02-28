<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Array Page</title>
</head>
<body>
<ul>
    @foreach($stocks as $key => $value)
        <li><strong>{{ $value->symbol }}:</strong> {{ $value->value }}
            @switch($value->direction)
                @case('up')
                    &#8593;
                    @break
                @case('down')
                    &#8595;
                    @break
                @case('none')
                    &mdash;
                    @break
            @endswitch
        </li>
    @endforeach

</ul>
</body>
</html>
