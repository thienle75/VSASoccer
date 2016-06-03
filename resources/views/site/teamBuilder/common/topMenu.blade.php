<nav class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">
                Builder
            </a>
        </div>
        <ul class="nav navbar-nav">
            <li class="{{ Route::getCurrentRoute()->getName()=='teamBuilder.index' ? 'active' : ''}}">
                <a href="{{ URL::route('teamBuilder.index') }}">Guide</a>
            </li>
            <li class="{{ Route::getCurrentRoute()->getName()=='teamBuilder.six' ? 'active' : ''}}">
                <a href="{{ URL::route('teamBuilder.six') }}">5+1</a>
            </li>
            <li class="{{ Route::getCurrentRoute()->getName()=='teamBuilder.seven' ? 'active' : ''}}">
                <a href="{{ URL::route('teamBuilder.seven') }}">6+1</a>
            </li>
            <li class="{{ Route::getCurrentRoute()->getName()=='teamBuilder.auto' ? 'active' : ''}}">
                <a href="{{ URL::route('teamBuilder.auto') }}">Auto</a>
            </li>
        </ul>
    </div>
</nav>