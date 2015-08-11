@extends('master');

@section('title')
    Login Page
@endsection

@section('content')
    Войти через ВКонтакте {!! link_to('login/vkontakte', 'START') !!}
@endsection