<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Export</title>

    <style>
        table th {
            padding: 10px;
            background-color: lightgray;
        }
        table td {
            padding: 10px;
        }
        .item-green {
            color: #42b983;
        }
        .item-red {
            color: firebrick;
        }
    </style>
</head>
<body>

<h2>Date: {{$dataRange}}</h2>
<h3>Total time: {!! Helper::renderDuration($tasks['total_duration']) !!}</h3>
<br />

<table>
    @foreach ($tasks['tasks'] as $date => $item)
    <tr>
        <th align="left">{{$date}}</th>
        <th align="left">{!! Helper::renderDuration($item['total_duration']) !!}</th>
    </tr>
        @foreach ($item['tasks'] as $task)
        <tr class="@if($item['covered_day_hours']) item-green @else item-red @endif">
            <td>
                {{ $task['title'] }}
            </td>
            <td>
                {!! Helper::renderDuration($task['duration']) !!}
            </td>
        </tr>
        @endforeach
    @endforeach
</table>
</body>
</html>
