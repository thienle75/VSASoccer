<style type="text/css">
    .absolutePosition{
        position: absolute;
    }

    .playerDropdown{
        width: 150px;
    }

    #playerGoalie{
        top: 600px;
        left: 470px;
    }

    #playerLeftDefence{
        top: 350px;
        left: 40px;
    }

    #playerCenterDefence{
        top: 350px;
        left: 470px;
    }

    #playerRightDefence{
        top: 350px;
        right: 40px;
    }

    #playerLeftForward{
        top: 100px;
        left: 190px;
    }

    #playerRightForward{
        top: 100px;
        left: 570px;
    }

    .playerCard .playerInfo{
        display: block;
        font-size: 14px;
    }

    .playerCard{
        background-color: #ebebeb;
        padding: 10px;
        text-align: center;
        color: black;
    }

    .playerStat{
        font-size: 10px !important;
    }

    #fieldBackground{
        margin-top: 20px;
        background-image: url('/assets/soccer_field.png');
        background-size: 880px 1100px;
        background-position: 0 -400px;
        background-repeat: no-repeat;
        opacity: 1.0 !important;
    }
    
    #fieldBackground #diagram{
        opacity: 1.0;
    }

    .playerName{
        font-weight: bold;
    }
</style>
<div id="fieldBackground">
    <canvas id="diagram" width="880" height="800"></canvas>
</div>
<div id="playerGoalie" class="playerSelection absolutePosition">
    <select class="form-control  playerDropdown">
        <option value="" selected="selected"></option>
        @foreach($players as $player)
            <option value="{{ $player->id }}">{{ $player->formalName() }}</option>
        @endforeach
    </select>
    <div class="playerCard"></div>
</div>

<div id="playerLeftDefence" class="playerSelection absolutePosition">
    <select class="form-control playerDropdown" >
        <option value="" selected="selected"></option>
        @foreach($players as $player)
            <option value="{{ $player->id }}">{{ $player->formalName() }}</option>
        @endforeach
    </select>
    <div class="playerCard"></div>
</div>

<div id="playerCenterDefence" class="playerSelection absolutePosition">
    <select class="form-control playerDropdown">
        <option value="" selected="selected"></option>
        @foreach($players as $player)
            <option value="{{ $player->id }}">{{ $player->formalName() }}</option>
        @endforeach
    </select>
    <div class="playerCard"></div>
</div>

<div id="playerRightDefence" class="playerSelection absolutePosition">
    <select class="form-control playerDropdown">
        <option value="" selected="selected"></option>
        @foreach($players as $player)
            <option value="{{ $player->id }}">{{ $player->formalName() }}</option>
        @endforeach
    </select>
    <div class="playerCard"></div>
</div>

<div id="playerLeftForward" class="playerSelection absolutePosition">
    <select class="form-control playerDropdown">
        <option value="" selected="selected"></option>
        @foreach($players as $player)
            <option value="{{ $player->id }}">{{ $player->formalName() }}</option>
        @endforeach
    </select>
    <div class="playerCard"></div>
</div>

<div id="playerRightForward" class="playerSelection absolutePosition">
    <select class="form-control playerDropdown">
        <option value="" selected="selected"></option>
        @foreach($players as $player)
            <option value="{{ $player->id }}">{{ $player->formalName() }}</option>
        @endforeach
    </select>
    <div class="playerCard"></div>
</div>

<script type="text/javascript">

    /**
     * This function draws the dots for the formation
     */
    function drawFormation()
    {
        var canvas = document.getElementById('diagram');
        var context = canvas.getContext('2d');
        var centerX = canvas.width / 2;
        var centerY = canvas.height / 2;
        var radius = 10;

        context.globalAlpha = 1.0;

        var points = [
            [centerX, canvas.height-200],   // Goalie
            [centerX-250, centerY-50],      // Left Defence
            [centerX, centerY-50],          // Center Defence
            [centerX+250, centerY-50],      // Right Defence
            [centerX-100, 100],             // Left Forward
            [centerX+100, 100]              // Right Forward
        ];

        points.forEach(function(item){
            // Draw Nodes
            context.beginPath();
            context.arc(item[0], item[1], radius, 0, 2 * Math.PI, false);
            context.fillStyle = '#f04e5e';
            context.fill();
            context.lineWidth = 1;
            context.strokeStyle = '#003300';
            context.stroke();
        });
    }


    function drawLine(line, chemistry)
    {
        var canvas = document.getElementById('diagram');
        var context = canvas.getContext('2d');
        var centerX = canvas.width / 2;
        var centerY = canvas.height / 2;

        context.globalAlpha = 1.0;

        var lines = {
            "GoalieLeftDefence": [[centerX, canvas.height - 200], [centerX - 250, centerY - 50]],       // Goalie to Left Defence
            "GoalieCenterDefence": [[centerX, canvas.height - 200], [centerX, centerY - 50]],           // Goalie to Center Defence
            "GoalieRightDefence": [[centerX, canvas.height - 200], [centerX + 250, centerY - 50]],      // Goalie to Right Defence
            "LeftDefenceCenterDefence": [[centerX - 250, centerY - 50], [centerX, centerY - 50]],       // Left Defence to Center Defence
            "CenterDefenceRightDefence": [[centerX, centerY - 50], [centerX + 250, centerY - 50]],      // Center Defence to Right Defence
            "LeftDefenceLeftForward": [[centerX - 250, centerY - 50], [centerX - 100, 100]],            // Left Defence to Left Forward
            "CenterDefenceLeftForward": [[centerX, centerY - 50], [centerX - 100, 100]],                // Center Defence to Left Forward
            "CenterDefenceRightForward": [[centerX, centerY - 50], [centerX + 100, 100]],               // Center Defence to Right Forward
            "RightDefenceRightForward": [[centerX + 250, centerY - 50], [centerX + 100, 100]],          // Right Defence to Right Forward
            "LeftForwardRightForward": [[centerX - 100, 100], [centerX + 100, 100]],                    // Left Forward to Right Forward

            "LeftDefenceGoalie": [[centerX, canvas.height - 200], [centerX - 250, centerY - 50]],       // Goalie to Left Defence
            "CenterDefenceGoalie": [[centerX, canvas.height - 200], [centerX, centerY - 50]],           // Goalie to Center Defence
            "RightDefenceGoalie": [[centerX, canvas.height - 200], [centerX + 250, centerY - 50]],      // Goalie to Right Defence
            "CenterDefenceLeftDefence": [[centerX - 250, centerY - 50], [centerX, centerY - 50]],       // Left Defence to Center Defence
            "RightDefenceCenterDefence": [[centerX, centerY - 50], [centerX + 250, centerY - 50]],      // Center Defence to Right Defence
            "LeftForwardLeftDefence": [[centerX - 250, centerY - 50], [centerX - 100, 100]],            // Left Defence to Left Forward
            "LeftForwardCenterDefence": [[centerX, centerY - 50], [centerX - 100, 100]],                // Center Defence to Left Forward
            "RightForwardCenterDefence": [[centerX, centerY - 50], [centerX + 100, 100]],               // Center Defence to Right Forward
            "RightForwardRightDefence": [[centerX + 250, centerY - 50], [centerX + 100, 100]],          // Right Defence to Right Forward
            "RightForwardLeftForward": [[centerX - 100, 100], [centerX + 100, 100]]                     // Left Forward to Right Forward
        };

        var theLine = lines[line];
        context.beginPath();
        context.moveTo(theLine[0][0], theLine[0][1]);
        context.lineTo(theLine[1][0], theLine[1][1]);
        context.lineWidth = 5;

        if(chemistry<=25){
            context.strokeStyle = '#FF0000';
        }else if(chemistry>25 && chemistry<=50){
            context.strokeStyle = '#FF8400';
        }else if(chemistry>50 && chemistry<=75){
            context.strokeStyle = '#84FF00';
        }else if(chemistry>75){
            context.strokeStyle = '#4b9100';
        }

        context.stroke();
    }

    /**
     * This function draws the lines for the formation
     */
    function drawLines(type)
    {
        var canvas = document.getElementById('diagram');
        var context = canvas.getContext('2d');
        var centerX = canvas.width / 2;
        var centerY = canvas.height / 2;
        var radius = 10;

        context.globalAlpha = 1.0;
        context.clearRect(0, 0, canvas.width, canvas.height);

        var lines = {
            all: [
                [[centerX, canvas.height-200],[centerX-250, centerY-50]],       // Goalie to Left Defence
                [[centerX, canvas.height-200],[centerX, centerY-50]],           // Goalie to Center Defence
                [[centerX, canvas.height-200],[centerX+250, centerY-50]],       // Goalie to Right Defence
                [[centerX-250, centerY-50],[centerX, centerY-50]],              // Left Defence to Center Defence
                [[centerX, centerY-50],[centerX+250, centerY-50]],              // Center Defence to Right Defence
                [[centerX-250, centerY-50],[centerX-100, 100]],                 // Left Defence to Left Forward
                [[centerX, centerY-50],[centerX-100, 100]],                     // Center Defence to Left Forward
                [[centerX, centerY-50],[centerX+100, 100]],                     // Center Defence to Right Forward
                [[centerX+250, centerY-50],[centerX+100, 100]],                 // Right Defence to Right Forward
                [[centerX-100, 100],[centerX+100, 100]]                         // Left Forward to Right Forward
            ],
            goalie: [
                [[centerX, canvas.height-200],[centerX-250, centerY-50]],       // Goalie to Left Defence
                [[centerX, canvas.height-200],[centerX, centerY-50]],           // Goalie to Center Defence
                [[centerX, canvas.height-200],[centerX+250, centerY-50]]        // Goalie to Right Defence
            ],
            leftDefence: [
                [[centerX, canvas.height-200],[centerX-250, centerY-50]],       // Goalie to Left Defence
                [[centerX-250, centerY-50],[centerX, centerY-50]],              // Left Defence to Center Defence
                [[centerX-250, centerY-50],[centerX-100, 100]]                  // Left Defence to Left Forward
            ],
            centerDefence: [
                [[centerX, canvas.height-200],[centerX, centerY-50]],           // Goalie to Center Defence
                [[centerX-250, centerY-50],[centerX, centerY-50]],              // Left Defence to Center Defence
                [[centerX, centerY-50],[centerX+250, centerY-50]],              // Center Defence to Right Defence
                [[centerX, centerY-50],[centerX-100, 100]],                     // Center Defence to Left Forward
                [[centerX, centerY-50],[centerX+100, 100]]                      // Center Defence to Right Forward
            ],
            rightDefence: [
                [[centerX, canvas.height-200],[centerX+250, centerY-50]],       // Goalie to Right Defence
                [[centerX, centerY-50],[centerX+250, centerY-50]],              // Center Defence to Right Defence
                [[centerX+250, centerY-50],[centerX+100, 100]]                  // Right Defence to Right Forward
            ],
            leftForward: [
                [[centerX-250, centerY-50],[centerX-100, 100]],                 // Left Defence to Left Forward
                [[centerX, centerY-50],[centerX-100, 100]],                     // Center Defence to Left Forward
                [[centerX-100, 100],[centerX+100, 100]]                         // Left Forward to Right Forward
            ],
            rightForward: [
                [[centerX, centerY-50],[centerX+100, 100]],                     // Center Defence to Right Forward
                [[centerX+250, centerY-50],[centerX+100, 100]],                 // Right Defence to Right Forward
                [[centerX-100, 100],[centerX+100, 100]]                         // Left Forward to Right Forward
            ]
        };

        type = typeof type !== 'undefined' ? type : 'all';

        lines[type].forEach(function(line){
            // Golly to Left Defence
            context.beginPath();
            context.moveTo(line[0][0], line[0][1]);
            context.lineTo(line[1][0], line[1][1]);
            context.lineWidth = 5;
            context.strokeStyle = '#f04e5e';
            context.stroke();
        });
    }
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $('.playerDropdown').change(function(){
            var playerDropdown = $(this);
            var player1_id = playerDropdown.val();
            var playerSelection = $(playerDropdown.parent().get(0));
            var playerCard = playerSelection.children().last();

            var positions = {
                'playerGoalie' : ['playerLeftDefence', 'playerCenterDefence', 'playerRightDefence'],
                'playerLeftDefence' : ['playerGoalie', 'playerCenterDefence', 'playerLeftForward'],
                'playerCenterDefence' : ['playerLeftDefence', 'playerGoalie', 'playerRightDefence', 'playerLeftForward', 'playerRightForward'],
                'playerRightDefence' : ['playerGoalie', 'playerCenterDefence', 'playerRightForward'],
                'playerLeftForward' : ['playerLeftDefence', 'playerCenterDefence', 'playerRightForward'],
                'playerRightForward' : ['playerRightDefence', 'playerCenterDefence', 'playerLeftForward']
            };

            $.ajax({
                url: '{{ URL::to('/team-builder/player') }}/'+player1_id,
                type: 'get',
                dataType: 'json',
                complete: function(jqXHR, status){
                    var playerInfo = jqXHR.responseJSON;

                    var pic = '<img src="/assets/player_avatars/'+player1_id+'.jpg" width="70px"/>';
                    var name = '<span class="playerName playerInfo">'+playerInfo.name+'</span>';
                    var position = '<span class="playerPos playerInfo playerStat">'+playerInfo.position+'</span>';
                    var tr = '<span class="playerTR playerInfo playerStat">TR: '+playerInfo.tr+'</span>';
                    var pr = '<span class="playerPR playerInfo playerStat">PR: '+playerInfo.pr+'</span>';

                    // Draw Card
                    playerCard.html(pic+name+position+tr+pr);

                    positions[playerSelection.attr('id')].forEach(function(item){
                        var player2_id = $('#'+item+' .playerDropdown').val();

                        if(player2_id != '' && player1_id !='') {
                            var chemistry = getChemistry(player1_id, player2_id);
                            var fistPart = playerSelection.attr('id').substring(6);
                            var secondPart = item.substring(6);

                            drawLine(fistPart+secondPart, chemistry);
                            drawFormation();
                        }
                    });
                }
            });
        });

        drawLines();
        drawFormation();
    });

    /**
     *
     * @param player1_id
     * @param player2_id
     */
    function getChemistry(player1_id, player2_id)
    {
        var chemistry = 0;

        $.ajax({
            url: '{{ URL::route('teamBuilder.getChemistry') }}',
            type: 'get',
            async: false,
            data: {
                player1_id: player1_id,
                player2_id: player2_id
            },
            dataType: 'json',
            complete: function(jqXHR, status){
                var chemistryInfo = jqXHR.responseJSON;
                chemistry = chemistryInfo['chemistry'];

                console.log(chemistry);
            }
        });

        return chemistry;
    }

</script>

