@extends('layouts.dashboard')

@section('innerContent')
    @if(Auth::user()->authorized())
        <a href="{{ URL::route('footage.create') }}" class="btn btn-success pull-right margin-top-25">Add Video</a>
    @endif
    <h1 class="titles">Footage</h1>
    <hr />
    <div>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            @foreach($seasons as $season)
                <li role="presentation" class="{{ ($season->name=="Season 1" ? 'active' : '') }}">
                    <a href="#season{{ $season->id }}" aria-controls="season{{ $season->id }}" role="tab" data-toggle="tab">{{ $season->name }}</a>
                </li>
            @endforeach
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            @foreach($seasons as $season)
                <div role="tabpanel" class="tab-pane {{ ($season->name=="Season 1" ? 'active' : '') }}" id="season{{ $season->id }}">
                    <table class="table table-striped table-hover table-condensed">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Footage</th>
                            <th>Link</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $games = $season->games()->getResults();
                        ?>
                        @foreach($games as $game)
                            <?php $footage = $game->footage()->getResults(); ?>
                            @foreach($footage as $video)
                                <tr>
                                    <td>{{ $game->date }}</td>
                                    <td>{{ ucwords($video->name) }}</td>
                                    <td>
                                        <a title="view" class="btnModal" href="" data-toggle="modal" data-target="#myModal" data-atr="{{ $video->url }}">
                                            <span class="glyphicon glyphicon-hd-video"></span>
                                        </a>
                                    </td>
                                    <td>
                                        @if(Auth::user()->authorized())
                                            <a title="edit" href="{{ URL::route('footage.edit',['videoId'=>$video->id]) }}">
                                                <span class="glyphicon glyphicon-edit"></span>
                                            </a>

                                            <a title="delete" href="" data-toggle="modal" data-target="#deleteVideoModal" data-video="{{ $video->id }}">
                                                <span class="glyphicon glyphicon-remove"></span>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.btnModal').click(function () {
                var youtubeUrl = "https://www.youtube.com/embed/" + $(this).data('atr');
                document.getElementById("youtubeVideo").src = youtubeUrl;
            });


            $('#deleteVideoModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var videoId = button.data('video');
                var modal = $(this);

                console.log(videoId);

                modal.find(".modal-footer #yesBtn").attr('onclick', "deleteVideo("+videoId+");");
            });
        });

        function deleteVideo(videoId)
        {
            var modal = $('#deleteVideoModal');

            $.ajax({
                url: '{{ URL::route('footage.index') }}/'+videoId,
                type: 'delete',
                success: function(){
                    window.location.reload();
                }
            });

            modal.modal('toggle');
        }

        <!-- Jquery to inject button data-atr(youtube video identifier) into modal-->

    </script>
@endsection

@section('modals')
    <!-- Modals -->
    @include('site.footage.modals.deleteVideo')
    @include('site.footage.modals.showVideo')
@endsection