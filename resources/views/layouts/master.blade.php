<!DOCTYPE html>
<html lang="en">

<head>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <!-- Twitter Bootstrap -->
    @if(\Cookie::get('theme')=='dark')
    {!! HTML::style('/css/bootstrap-superhero.min.css') !!}
    @else
    {!! HTML::style('/vendor/bootstrap/dist/css/bootstrap.min.css') !!}
    @endif

    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <!-- Custom Styles -->
    {!! HTML::style('/css/layout_styles.css') !!}
    <!-- Font Awesome -->
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <!--Custom CSS-->
    {!! HTML::style('/vendor/stylesheet.css') !!}

    <!-- jQuery -->
    {!! HTML::script('/vendor/jquery/dist/jquery.min.js') !!}
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

    {!! HTML::style('/vendor/bootstrap-multiselect/dist/css/bootstrap-multiselect.css') !!}
    {!! HTML::script('/vendor/bootstrap-multiselect/dist/js/bootstrap-multiselect.js') !!}

    <!-- Meta Data -->
    <title>Engage Soccer</title>
    <meta name="description" content="Engage Soccer Stats">
    <meta name="keywords" content="">
    <meta name="author" content="Engage People Inc.">
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans">
    <style>
        .container-fluid{
            @if(\Cookie::get('theme')=='dark')
            background-color: #2b3e50;
            opacity: 0.95;
            @else
            background-color: #FFFFFF;
            opacity: 0.95;
            @endif
            min-height: 700px;
            margin-top: 20px;
        }
    </style>

    <!--
         ______________________________________________
        | \_/ \_/ \_/ \_/ \_/ \_/ \_/ \_/ \_/ \_/ \_/ |.
        |_/ \_/ \_/ \_/ \_/ \_/ \_/ \_/ \_/ \_/ \_/ \_||
       |\ \_/ \_/ \_/ \_/ \_/ \_/ \_/ \_/ \_/ \_/ \_/ /\
       |\|/ \_/ \_/ \_/ \_/ \|||/_/ \_/ \_/ \_/ \_/ \|/'.
      .'/|\_/ \_/ \_/ \_/ \q O O p\_/ \_/ \_/ \_/ \_/|\_|
      | \\/ \_/ \_/ \_/ \_/ | ) |_/ \_/ \_/ \_/ \_/ \|/ \
      |_/ |_/ \_/ \_/ \_/ __\_O_/___/ \_/ \_/ \_/ \_//\_'.
     |/ \_| \_/ \_,--._,-"         `-._,--. \_/ \_/ |_/ \|
     |\_/ |_/ \_/ \__/_,-"|       |`-._\__/_/ \_/ \_| \_/\
    |_/ \_\ \_/ \_/ \_/ \_|       | \_/ \_/ \_/ \_/ /_/ \'.
    | \_/ \|/ \_/ \_/ \_//   /\   \_/ \_/ \_/ \_/ \|/ \_/ |
   .'_/ \_/|\_/ \_/ \_/ /   /  \   \\_/ \_/ \_/ \_/|\_/ \_\
   |/ \_/__\___________/   /____\   \_______________\ \_/ '.
   |\_,-'           __/___/      \___\__             `-.__/|
   //              /_____/        \_____\                 `\

Gr

_________                        __             .___ __________
\_   ___ \_______   ____ _____ _/  |_  ____   __| _/ \______   \___.__. /\
/    \  \/\_  __ \_/ __ \\__  \\   __\/ __ \ / __ |   |    |  _<   |  | \/
\     \____|  | \/\  ___/ / __ \|  | \  ___// /_/ |   |    |   \\___  | /\
 \______  /|__|    \___  >____  /__|  \___  >____ |   |______  // ____| \/
        \/             \/     \/          \/     \/          \/ \/


   _____  .__  __   __           ___________           .__
  /     \ |__|/  |_|  | ______   \__    ___/___   ____ |  |__   _______  __
 /  \ /  \|  \   __\  |/ /  _ \    |    | /  _ \_/ ___\|  |  \_/ __ \  \/ /
/    Y    \  ||  | |    <  <_> )   |    |(  <_> )  \___|   Y  \  ___/\   /
\____|__  /__||__| |__|_ \____/    |____| \____/ \___  >___|  /\___  >\_/
        \/              \/                           \/     \/     \/
                                  ____
                                 /  _ \
                                 >  _ </\
                                /  <_\ \/
                                \_____\ \
                                       \/
     ____.            .__        _________                            .__
    |    | ____  _____|  |__    /   _____/____    _____  __ __   ____ |  |   ______
    |    |/  _ \/  ___/  |  \   \_____  \\__  \  /     \|  |  \_/ __ \|  |  /  ___/
/\__|    (  <_> )___ \|   Y  \  /        \/ __ \|  Y Y  \  |  /\  ___/|  |__\___ \
\________|\____/____  >___|  / /_______  (____  /__|_|  /____/  \___  >____/____  >
                    \/     \/          \/     \/      \/            \/          \/


    -->

</head>

<body id="body" style="background-color: #bdc3c7">
<div class="container">
    <div class="container-fluid" style="">
        <div class="row">
            <div class="col-md-12" style="">
                @yield('content')
            </div>
        </div>
    </div>
</div>

@yield('modals')

        <!-- Collapse/Expand code -->
<script type="text/javascript">
    $(document).ready(function () {
        $('.collapse').on('show.bs.collapse', function () {
            $(this).prev().find('.glyphicon').removeClass("glyphicon-plus").addClass("glyphicon-minus");
        });

        $('.collapse').on('hide.bs.collapse', function () {
            $(this).prev().find('.glyphicon').removeClass("glyphicon-minus").addClass("glyphicon-plus");
        });

        $('select.multiselect').multiselect({
            numberDisplayed: 5,
            buttonWidth: '100%'
        });
    });
</script>
<!-- HTML5SHIV -->
{!! HTML::script('/vendor/html5shiv/dist/html5shiv.min.js') !!}
        <!-- Twitter Bootstrap -->
{!! HTML::script('/vendor/bootstrap/dist/js/bootstrap.min.js') !!}
</body>

<script>
    $(function() {
        $( "#gamedate" ).datepicker();
    });
</script>




</html>
