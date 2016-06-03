<nav class="navbar navbar-default" role="navigation">
    <div class="navbar-header">
        <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#bs-navbar" aria-controls="bs-navbar" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar" style="background-color: #f04e5e"></span>
            <span class="icon-bar" style="background-color: #f04e5e"></span>
            <span class="icon-bar" style="background-color: #f04e5e"></span>
        </button>
    </div>
    <nav id="bs-navbar" class="collapse navbar-collapse">
        <ul class="nav nav-pills nav-stacked fixed">
            <?php $routeName = Route::getCurrentRoute()->getName(); ?>
            @if(!Auth::user()->googleAuth())
                <li {!! ($routeName=='users.index' || $routeName=='users.edit' || $routeName=='users.show') ? ' class="active"' : '' !!}>
                    <a href="{{ URL::route('users.edit',['userId'=>Auth::user()->id]) }}">
                        <span class="glyphicon glyphicon-cog"></span>&nbsp;&nbsp;&nbsp;Account
                    </a>
                </li>
            @endif
            <!--games element of sidebar-->
            <li {!! ($routeName=='seasons.index' || $routeName=='seasons.edit' || $routeName=='seasons.games.show'  || $routeName=='seasons.games.edit') ? ' class="active"' : '' !!}>
                <a href="{{ URL::to('seasons') }}">
                    <span class="glyphicon glyphicon-calendar"></span>&nbsp;&nbsp;&nbsp;Seasons
                </a>
            </li>
            <!--players element of sidebar-->
            <li {!! ($routeName=='players.index' || $routeName=='players.edit' || $routeName=='players.show') ? ' class="active"' : '' !!}>
                <a href="{{ URL::to('players') }}">
                    <span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;&nbsp;Players
                </a>
            </li>
            <!--Ratings element of sidebar-->
            <li {!! ($routeName=='ratings.index' || $routeName=='ratings.seasons.show') ? ' class="active"' : '' !!}>
                <a href="{{ URL::route('ratings.index') }}">
                    <span class="glyphicon glyphicon-signal"></span>&nbsp;&nbsp;&nbsp;Ratings
                </a>
            </li>
            <!--Stats element of sidebar-->
            <li {!! ($routeName=='stats.index' || $routeName=='stats.edit' || $routeName=='stats.seasons.show') ? ' class="active"' : '' !!}>
                <a href="{{ URL::to('stats') }}">
                    <span class="glyphicon glyphicon-stats"></span>&nbsp;&nbsp;&nbsp;Stats
                </a>
            </li>
            <!--Chemistry Graph-->
            <!--<li {!! ($routeName=='chemistry.index') ? ' class="active"' : '' !!}>
                <a href="{{ URL::route('chemistry.index') }}">
                    <span class="glyphicon glyphicon-flash"></span>&nbsp;&nbsp;&nbsp;Chemistry
                </a>
            </li>-->
            <!--Team builder element of sidebar-->
            @if(Auth::getUser()->status==2)
            <li {!! ($routeName=='teamBuilder.index') ? ' class="active"' : '' !!}>
                <a href="{{ URL::route('teamBuilder.index') }}">
                    <span class="glyphicon glyphicon-wrench"></span>&nbsp;&nbsp;&nbsp;Builder
                </a>
            </li>
            @endif
            <!--awards element of sidebar-->
            <li {!! ($routeName=='awards.index' || $routeName=='awards.edit') ? ' class="active"' : '' !!}>
                <a href="{{ URL::to('awards') }}">
                    <span class="glyphicon glyphicon-gift"></span>&nbsp;&nbsp;&nbsp;Awards
                </a>
            </li>
            <!--traits element of sidebar-->
            <li {!! ($routeName=='traits.index' || $routeName=='traits.edit') ? ' class="active"' : '' !!}>
                <a href="{{ URL::to('traits') }}">
                    <span class="glyphicon glyphicon-th-list"></span>&nbsp;&nbsp;&nbsp;Traits
                </a>
            </li>
            <!--footage element of sidebar-->
            <li {!! ($routeName=='footage.index' || $routeName=='footage.edit' || $routeName=='footage.create') ? ' class="active"' : '' !!}>
                <a href="{{ URL::to('footage') }}">
                    <span class="glyphicon glyphicon-film"></span>&nbsp;&nbsp;&nbsp;Footage
                </a>
            </li>
            <!--login logout element of sidebar-->
            @if(!Auth::check())
                <li>
                    <a href="{{ URL::to('login') }}">
                        <span class="glyphicon glyphicon-log-in"></span>&nbsp;&nbsp;&nbsp;Login
                    </a>
                </li>
            @else
                <li>
                    <a href="{{ URL::to('logout') }}">
                        <span class="glyphicon glyphicon-log-out"></span>&nbsp;&nbsp;&nbsp;Logout
                    </a>
                </li>
            @endif
        </ul>
    </nav>
</nav>