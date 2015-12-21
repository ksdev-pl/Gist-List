<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

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
