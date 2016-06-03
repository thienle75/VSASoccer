<!-- Excuses -->
<div class="row">
    <div class="col-lg-12">
        <table class="table table-hover table-striped table-condensed">
            <thead>
                <tr>
                    <th colspan="2">Type</th>
                    <th>Description</th>
                    <th>Season</th>
                </tr>
            </thead>
            <tbody>
                @if(count($awards)>0)
                    @foreach($awards as $award)
                        <?php $awardType = $award->award_type()->getResults(); ?>
                        <tr>
                            <td><img src="{{ $award->getBadge() }}" width="32px" height="32px"/></td>
                            <td>{{ $awardType->name }}</td>
                            <td>{{ $awardType->description }}</td>
                            <td>{{ $award->season()->getResults()->name }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="4">This player has no awards.</td></tr>
                @endif
            </tbody>
        </table>
    </div>
</div>