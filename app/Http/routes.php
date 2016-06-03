<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => 'auth'], function () {

    Route::get('/home', function() {
        return Redirect::to('seasons');
    });

    // static routes
    Route::get('/', 'HomeController@getHome');
    Route::get('/theme/{theme}', 'HomeController@getTheme');

    Route::group(['middleware' => 'captain'], function () {
        Route::post('/seasons/{seasonId}/games/{gameId}/teams/{teamId}/add-player', 'TeamController@postAddPlayer');
        Route::post('/seasons/{seasonId}/games/{gameId}/teams/{teamId}/remove-player', 'TeamController@postRemovePlayer');
    });

    //protected to users with a status 2 (admin)
    Route::group(['middleware' => 'admin'], function () {

        Route::post('/seasons/{seasonId}/games/{gameId}/addAttendance', [
            'as' => 'seasons.games.addAttendance',
            'uses' => 'AttendanceController@postAddAttendance'
        ]);
        Route::post('/seasons/{seasonId}/games/{gameId}/deleteAttendance', [
            'as' => 'seasons.games.deleteAttendance',
            'uses' => 'AttendanceController@postDeleteAttendance'
        ]);

        //add goals and assists
        Route::post('/stats/{statId}/add-goal', 'StatController@postAddGoal');
        Route::post('/stats/{statId}/add-assist', 'StatController@postAddAssist');
        Route::post('/stats/{statId}/delete-goal', 'StatController@postDeleteGoal');
        Route::post('/stats/{statId}/delete-assist', 'StatController@postDeleteAssist');

    });

    // seasons
    Route::resource('seasons','SeasonController');
    Route::resource('seasons.games', 'GameController');
    Route::resource('seasons.games.teams', 'TeamController');

    Route::get('/seasons/{seasonId}/games/{gameId}/gameSheet', [
        'as' => 'seasons.games.gameSheet',
        'uses' => 'GameController@getGameSheet'
    ]);

    // players
    Route::resource('players', 'PlayerController');

    // traits
    Route::resource('traits', 'TraitController');

    // ratings
    Route::get('/ratings', [
        'as' => 'ratings.index',
        'uses' => 'StatController@getRatings'
    ]);
    Route::get('/ratings/seasons/{seasonId}', [
        'as' => 'ratings.seasons.show',
        'uses' => 'StatController@getRatings'
    ]);

    // stats
    Route::resource('stats', 'StatController');

    Route::get('/stats/seasons/{seasonId}', [
        'as' => 'stats.seasons.show',
        'uses' => 'StatController@index'
    ]);

    // footage
    Route::resource('footage', 'FootageController');

    // awards
    Route::resource('awards', 'AwardController');

    // chemistry routes
    Route::resource('chemistry', 'ChemistryController');

    // teamBuilder routes
    Route::get('/team-builder', [
        'as' => 'teamBuilder.index',
        'uses' => 'TeamBuilderController@index'
    ]);
    Route::get('/team-builder/formation/{formation}', [
        'as' => 'teamBuilder.getFormation',
        'uses' => 'TeamBuilderController@getFormation'
    ]);
    Route::get('/team-builder/six', [
        'as' => 'teamBuilder.six',
        'uses' => 'TeamBuilderController@getSix'
    ]);
    Route::get('/team-builder/seven', [
        'as' => 'teamBuilder.seven',
        'uses' => 'TeamBuilderController@getSeven'
    ]);
    Route::get('/team-builder/auto', [
        'as' => 'teamBuilder.auto',
        'uses' => 'TeamBuilderController@getAutoSelect'
    ]);
    Route::post('/team-builder/balanceTeams', [
        'as' => 'teamBuilder.balanceTeams',
        'uses' => 'TeamBuilderController@postBalanceTeams'
    ]);
    Route::get('/team-builder/player/{formation}', [
        'as' => 'teamBuilder.getPlayerInfo',
        'uses' => 'TeamBuilderController@getPlayerInfo'
    ]);
    Route::get('/team-builder/chemistry', [
        'as' => 'teamBuilder.getChemistry',
        'uses' => 'TeamBuilderController@getChemistry'
    ]);

    // attendance
    Route::get('/attendance', 'AttendanceController@index');
    Route::post('/attendance', 'AttendanceController@postAttendance');

    // player of the match
    Route::get('/potm', 'PlayerOfTheMatchController@index');
    Route::post('/potm', 'PlayerOfTheMatchController@postAttendance');

    // user routes
    Route::resource('users', 'UserController');

    //logout user
    Route::get('logout', 'Login\LoginController@logout');
});

Route::group(['middleware' => 'checkSignIn'], function () {
// authentication
    Route::get('/login', 'Login\LoginController@login');
    Route::get('login/{provider?}', 'Auth\AuthController@login');
    Route::post('/login', 'Login\LoginController@postLogin');
});

// reset password route
Route::controllers([
    'password' => 'Auth\PasswordController',
]);
