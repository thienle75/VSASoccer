<script type="text/javascript"
        src="https://www.google.com/jsapi?autoload={
            'modules':[{
              'name':'visualization',
              'version':'1',
              'packages':['corechart', 'line']
            }]
          }">
</script>

<script type="text/javascript">
    google.setOnLoadCallback(drawGoalsAssistsVsGames);
    google.setOnLoadCallback(drawTeamPointsVsGames);
    google.setOnLoadCallback(drawGoalDiffVsGames);

    function drawGoalsAssistsVsGames() {
        var data = new google.visualization.DataTable();

        data.addColumn('date', 'Games');
        data.addColumn('number', 'Goals{{ ' ('.$performance['goalsAssistsVSgames']['goalsRate'].' goals/game)' }}');
        data.addColumn('number', 'Assists{{ ' ('.$performance['goalsAssistsVSgames']['assistsRate'].' assists/game)' }}');

        var jsonData = {!! json_encode($performance['goalsAssistsVSgames']['data']) !!};
        for ( var i = 0; i < jsonData.length; i++ ) {
            jsonData[i][0] = new Date( jsonData[i][0][0], jsonData[i][0][1]-1, jsonData[i][0][2]);
        }

        data.addRows(jsonData);

        var options = {
            title: 'Cumulative Goals & Assists vs. Games',
            @if(\Cookie::get('theme')=='dark')
            titleTextStyle: {
                color: '#FFFFFF'
            },
            backgroundColor: '#4e5d6c',
            @endif
            chartArea: {
                left: '10%',
                width: 500
            },
            series: {
                0: {
                    color: '#f04e5e'
                },
                1: {
                    color: '#428bca'
                }
            },
            curveType: 'none',
            legend: {
                position: 'right',
                @if(\Cookie::get('theme')=='dark')
                textStyle: {
                    color: '#FFFFFF',
                },
                titleTextStyle: {
                    color: '#FFFFFF',
                }
                @endif
            },
            vAxis: {
                title: 'Cumulative Goals & Assists',
                format: '#',
                @if(\Cookie::get('theme')=='dark')
                baselineColor: '#FFFFFF',
                gridlines: {
                    color: '#FFFFFF',
                },
                textStyle: {
                    color: '#FFFFFF',
                },
                titleTextStyle: {
                    color: '#FFFFFF',
                }
                @endif
            },
            hAxis: {
                title: 'Games',
                @if(\Cookie::get('theme')=='dark')
                baselineColor: '#FFFFFF',
                gridlines: {
                    color: '#FFFFFF',
                },
                textStyle: {
                    color: '#FFFFFF',
                },
                titleTextStyle: {
                    color: '#FFFFFF',
                }
                @endif
            },
            height: 300,
            width: 800
        };

        var chart = new google.visualization.LineChart(document.getElementById('goalsAssistsVSgames'));

        chart.draw(data, options);
    }

    function drawTeamPointsVsGames() {
        var data = new google.visualization.DataTable();

        data.addColumn('date', 'Games');
        data.addColumn('number', 'Team Points');

        var jsonData = {!! json_encode($performance['teamPointsVSgames']['data']) !!};
        for ( var i = 0; i < jsonData.length; i++ ) {
            jsonData[i][0] = new Date( jsonData[i][0][0], jsonData[i][0][1]-1, jsonData[i][0][2]);
        }

        data.addRows(jsonData);

        var options = {
            title: 'Cumulative Team Points vs. Games',
            @if(\Cookie::get('theme')=='dark')
            titleTextStyle: {
                color: '#FFFFFF'
            },
            backgroundColor: '#4e5d6c',
            @endif
            chartArea: {
                left: '10%',
                width: 500
            },
            series: {
                0: {
                    color: '#f04e5e'
                }
            },
            curveType: 'none',
            legend: {
                position: 'right',
                @if(\Cookie::get('theme')=='dark')
                textStyle: {
                    color: '#FFFFFF',
                },
                titleTextStyle: {
                    color: '#FFFFFF',
                }
                @endif
            },
            vAxis: {
                title: 'Cumulative Team Points',
                format: '#',
                @if(\Cookie::get('theme')=='dark')
                baselineColor: '#FFFFFF',
                gridlines: {
                    color: '#FFFFFF',
                },
                textStyle: {
                    color: '#FFFFFF',
                },
                titleTextStyle: {
                    color: '#FFFFFF',
                }
                @endif
            },
            hAxis: {
                title: 'Games',
                @if(\Cookie::get('theme')=='dark')
                baselineColor: '#FFFFFF',
                gridlines: {
                    color: '#FFFFFF',
                },
                textStyle: {
                    color: '#FFFFFF',
                },
                titleTextStyle: {
                    color: '#FFFFFF',
                }
                @endif
            },
            height: 300,
            width: 800
        };

        var chart = new google.visualization.LineChart(document.getElementById('teamPointsVSgames'));

        chart.draw(data, options);
    }

    function drawGoalDiffVsGames() {
        var data = new google.visualization.DataTable();

        data.addColumn('date', 'Games');
        data.addColumn('number', 'Goal Difference');

        var jsonData = {!! json_encode($performance['goalDiffVSgames']['data']) !!};
        for ( var i = 0; i < jsonData.length; i++ ) {
            jsonData[i][0] = new Date( jsonData[i][0][0], jsonData[i][0][1]-1, jsonData[i][0][2]);
        }

        data.addRows(jsonData);

        var options = {
            title: 'Cumulative Goal Difference vs. Games',
            @if(\Cookie::get('theme')=='dark')
            titleTextStyle: {
                color: '#FFFFFF'
            },
            backgroundColor: '#4e5d6c',
            @endif
            chartArea: {
                left: '10%',
                width: 500
            },
            series: {
                0: {
                    color: '#f04e5e'
                }
            },
            curveType: 'none',
            legend: {
                position: 'right',
                @if(\Cookie::get('theme')=='dark')
                textStyle: {
                    color: '#FFFFFF',
                },
                titleTextStyle: {
                    color: '#FFFFFF',
                }
                @endif
            },
            vAxis: {
                title: 'Cumulative Goal Difference',
                format: '#',
                @if(\Cookie::get('theme')=='dark')
                baselineColor: '#FFFFFF',
                gridlines: {
                    color: '#FFFFFF',
                },
                textStyle: {
                    color: '#FFFFFF',
                },
                titleTextStyle: {
                    color: '#FFFFFF',
                }
                @endif
            },
            hAxis: {
                title: 'Games',
                @if(\Cookie::get('theme')=='dark')
                baselineColor: '#FFFFFF',
                gridlines: {
                    color: '#FFFFFF',
                },
                textStyle: {
                    color: '#FFFFFF',
                },
                titleTextStyle: {
                    color: '#FFFFFF',
                }
                @endif
            },
            trendlines: {
                0: {
                    type: 'linear',
                    color: '#f04e5e',
                    lineWidth: 3,
                    opacity: 0.3,
                    showR2: false,
                    visibleInLegend: false
                }
            },
            height: 300,
            width: 800
        };

        var chart = new google.visualization.LineChart(document.getElementById('goalDiffVSgames'));

        chart.draw(data, options);
    }
</script>

<div class="row">
    <div class="col-md-12">
        <div id="goalsAssistsVSgames"></div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div id="teamPointsVSgames"></div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div id="goalDiffVSgames"></div>
    </div>
</div>
<br/>