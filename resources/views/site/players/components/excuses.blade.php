<!-- Excuses -->
<div class="row">
    <div class="col-lg-12">
        <table class="table table-hover table-striped table-condensed">
            <thead>
                <tr>
                    <th>Game</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                @if(count($excuses)>0)
                    @foreach($excuses as $excuse)
                        <tr>
                            <td> {{ $excuse->game()->getResults()->date }} </td>
                            <td> {{ $excuse->description }} </td>
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="2">This player has no excuses.</td></tr>
                @endif
            </tbody>
        </table>
    </div>
</div>