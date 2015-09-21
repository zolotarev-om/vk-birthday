<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="{{ route('main') }}">VK BirthDay</a>
        </div>

        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li><a href="{{ route('main') }}">Main</a></li>
                <li><a href="{{ route('about') }}">About</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li><a href="{{ route('setting') }}">Setting</a></li>
            </ul>
        </div>
    </div>
</nav>