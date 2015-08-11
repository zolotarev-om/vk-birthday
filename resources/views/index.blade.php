@extends('master')

@section('content')
    <div class="row center-block pt200">
        <div class="col-md-6">
            <p class="text-center">Последние 3 поздравления:</p>
            @foreach($latest as $row)
                <div class="col-md-3">
                    <img src="{{$row['avatar']}}">
                    Поздравили {{ $row['created_at'] }} - {{ $row['name'] }},
                    <attr title="{{ $row['message'] }}">сообщение</attr>
                </div>
            @endforeach
        </div>

        <div class="col-md-6">
            <p class="text-center">Ближайшие 3 дня рождения:</p>
            @foreach($upcoming as $row)
                <div class="col-md-3">
                    <img src="{{$row['avatar']}}">
                    У {{ $row['name'] }} => {{ $row['bdate'] }}
                </div>
            @endforeach
        </div>
    </div>
@endsection