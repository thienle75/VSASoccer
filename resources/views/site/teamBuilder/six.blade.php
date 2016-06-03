@extends('layouts.dashboard')

@section('innerContent')
    <h1 class="titles">Team Builder</h1>
    <hr/>
    @include('site.teamBuilder.common.topMenu')
    <div class="row">
        <div class="col-lg-2">
            <label for="formation">Formation</label>
            <select id="formation" class="form-control">
                @foreach($formations as $template=>$name)
                    <option value="{{ $template }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div id="formationCanvas"></div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function(){
            $.ajax({
                url: '{{ URL::to('/team-builder/formation') }}/threeTwo',
                type: 'get',
                complete: function(jqXHR, status){
                    $('#formationCanvas').html(jqXHR.responseText);
                }
            });

            $('#formation').change(function(){
                var value = document.getElementById('formation').value;

                $.ajax({
                    url: '{{ URL::to('/team-builder/formation') }}/'+value,
                    type: 'get',
                    complete: function(jqXHR, status){
                        $('#formationCanvas').html(jqXHR.responseText);
                    }
                });
            });
        });
    </script>
@endsection