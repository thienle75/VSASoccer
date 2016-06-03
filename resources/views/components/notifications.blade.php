@if (count($errors->all()) > 0)
    <br/>
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><span class="glyphicon glyphicon-warning-sign"></span> Error</h4>
        <hr class="message-inner-separator">
        <p>
        @foreach ($errors->all() as $error)
            {{ $error }} <br/>
        @endforeach
    </div>
@endif

@if ($message = Session::get('error'))
    <br/>
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><span class="glyphicon glyphicon-warning-sign"></span> Error</h4>
        <hr class="message-inner-separator">
        <p>
        @if(is_array($message))
            @foreach ($message as $m)
                {{ $m }}<br/>
            @endforeach
        @else
            {{ $message }}
        @endif
    </div>
@endif

@if ($message = Session::get('warning'))
    <br/>
    <div class="alert alert-warning">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><span class="glyphicon glyphicon-record"></span> Warning</h4>
        <hr class="message-inner-separator">
        <p>
        @if(is_array($message))
            @foreach ($message as $m)
                {{ $m }}<br/>
            @endforeach
        @else
            {{ $message }}
        @endif
    </div>
@endif

@if ($message = Session::get('info'))
    <br/>
    <div class="alert alert-info">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><span class="glyphicon glyphicon-info-sign"></span> Info</h4>
        <hr class="message-inner-separator">
        <p>
        @if(is_array($message))
            @foreach ($message as $m)
                {{ $m }}<br/>
            @endforeach
        @else
            {{ $message }}
        @endif
    </div>
@endif

@if ($message = Session::get('success'))
    <br/>
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><span class="glyphicon glyphicon-ok"></span> Success</h4>
        <hr class="message-inner-separator">
        <p>
            @if(is_array($message))
                @foreach ($message as $m)
                    {{ $m."<br>" }}
                @endforeach
            @else
                {{ $message }}
            @endif
        </p>
    </div>
@endif