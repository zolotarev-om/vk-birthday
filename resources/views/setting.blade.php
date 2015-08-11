@extends('master')

@section('title')
    Setting
@endsection

@section('content')
    {{--<h1>Привет, {{ $user['name'] }}</h1>--}}

    @if(Session::has('result'))
        @if(is_array(Session::all()['result']))
            @foreach(Session::pull('result') as $msg)
                <div class="alert alert-dismissible alert-success">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    {{ $msg }}
                </div>
            @endforeach
        @else
            <div class="alert alert-dismissible alert-success">
                <button type="button" class="close" data-dismiss="alert">×</button>
                {{ Session::pull('result') }}
            </div>
        @endif
    @endif

    @if($message)
        <div class="panel panel-default">
            <div class="panel-heading">Удалить сообщений</div>
            <div class="panel-body">
                {!! Form::open(['action' => 'SettingController@deleteMessage', 'method' => 'post', 'class'=>'form-group']) !!}
                <fieldset>
                    @foreach($message as $id=>$msg)
                        {!! Form::checkbox($id, true) !!}
                        <label>
                            {{ $msg }}
                        </label>
                        <br>
                    @endforeach
                    <br>
                    {!! Form::submit('Удалить', ['class' => 'btn btn-default']) !!}
                </fieldset>
                {!! Form::close() !!}
            </div>
        </div>
    @endif

    <div class="panel panel-default">
        <div class="panel-heading">Добавить сообщение</div>
        {!! Form::open(['action' => 'SettingController@addMessage', 'method' => 'post', 'class'=>'form-group']) !!}
        <div class="panel-body">
            <fieldset>
                {!! Form::textarea('message', null, ['placeholder'=>'Введите новое сообщение', 'rows'=>3, 'cols'=>90]) !!}
                <br><br>
                {!! Form::submit('Добавить', ['class' => 'btn btn-default']) !!}
            </fieldset>
            {!! Form::close() !!}
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Токен</div>
        <div class="panel-body">
            {!! Form::open(['action' => 'SettingController@updateToken', 'method' => 'post', 'class'=>'form-group']) !!}
            <fieldset>
                <a href="http://oauth.vk.com/authorize?client_id=4978801&scope=messages,status,wall,
                offline&redirect_uri=blank.html&display=page&v=5.35&response_type=token">Получение токена</a>
                {!! Form::text('token', $token, ['class' => 'form-control', "size"=>90, "placeholder" => "Введите токен"]) !!}
                <br>
                {!! Form::submit('Обновить', ['class' => 'btn btn-default']) !!}
            </fieldset>
            {!! Form::close() !!}
        </div>
    </div>
@endsection