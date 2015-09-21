@extends('master')

@section('content')
    <div class="row">
        <div class="column col-md-2"></div>
        <div class="column col-md-8">
            <div class="row">
                <div class="column col-md-7 pull-left text-left">
                    <p class="text-center">Последние 3 поздравления:</p>
                    @foreach($latest as $row)
                        <div class="row flex-item-left">
                            <img src="{{ $row['avatar' ]}}">
                            <span>Мы поздравили {{ $row['name'] }} - {{ $row['created_at'] }} числа,<br>
                            наше поздравление: "{{ $row['message'] }}"</span>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="row">
                <div class="column col-md-7 pull-right text-right">
                    <p class="text-center">Ближайшие 3 дня рождения:</p>
                    @foreach($upcoming as $row)
                        <div class="row flex-item-right">
                            <span>У {{ $row['name'] }} будет день рождения {{ $row['bdate'] }} числа</span>
                            <img src="{{ $row['avatar'] }}">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="column col-md-2"></div>
    </div>
@endsection