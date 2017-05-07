<?php

namespace App\Http\Controllers;

class PagesController extends Controller
{
    public function home()
    {
        if (\Auth::check()) {
            return \Redirect::action('GistsController@index');
        }

        return view('pages.home');
    }
}
