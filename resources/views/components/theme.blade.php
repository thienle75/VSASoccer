<div class="text-center">
    @if(\Cookie::get('theme')=='dark')
    <a href="{{ URL::to('/theme/light') }}" style="color:white;">Follow the Light</a>
    @else
    <a href="{{ URL::to('theme/dark') }}">Come to the Dark Side</a>
    @endif
</div>