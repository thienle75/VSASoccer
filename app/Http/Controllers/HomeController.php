<?php

namespace App\Http\Controllers;


use Auth;
use App\Http\Requests;
use DebugBar\DebugBar;
use Illuminate\Support\Facades\View;
use Redirect;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getHome()
    {
        return Redirect::to('seasons');
    }

    /**
     * This function sets the theme to be used
     * @param $theme
     * @return $this
     */
    public function getTheme($theme)
    {
        \Cookie::forget('theme');

        $cookie = \Cookie::forever('theme', $theme);
        $response = Redirect::back()->withCookies([$cookie]);

        return $response;

    }
}
