<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
