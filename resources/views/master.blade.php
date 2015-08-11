<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    <script src="https://bootswatch.com/bower_components/jquery/dist/jquery.js"></script>
    <script src="https://bootswatch.com/bower_components/bootstrap/dist/js/bootstrap.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.css">
    <link rel="stylesheet" href="https://bootswatch.com/paper/bootstrap.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
@include('nav')
<div class="container">
    <div class="row">
        <div class="col-md-10 center-block">
            @yield('content')
        </div>
    </div>
</div>
</body>
</html>
