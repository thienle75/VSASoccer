<table id="Videos" class="table table-striped table-hover table-condensed">
    <thead class="">
    <tr>
        <th>Footage</th>
        <th>Link</th>
    </tr>
    </thead>
    <tbody>
    <?php
        $footage = $game->footage()->getResults();
    ?>
    @if(count($footage)>0)
        @foreach($footage as $video)
            <tr>
                <td>{{ ucwords($video->name) }}</td>
                <td>
                    <a title="view" class="btnModal" href="" data-toggle="modal" data-target="#myModal" data-atr="{{ $video->url }}">
                        <span class="glyphicon glyphicon-hd-video"></span>
                    </a>
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="2">No videos have been uploaded for this game.</td>
        </tr>
    @endif
    </tbody>
</table>
<br/>
<br/>
