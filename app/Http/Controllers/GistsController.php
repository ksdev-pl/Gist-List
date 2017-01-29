<?php

namespace App\Http\Controllers;

use App\Helpers\GistFinder;
use Illuminate\Http\Request;

class GistsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(GistFinder $gistFinder)
    {
        $gistsAndTags = $gistFinder->fetchGistsAndTags();

        $state = collect([
            'user'     => auth()->user(),
            'gists'    => $gistsAndTags['gists'],
            'tags'     => $gistsAndTags['tags'],
            'filterBy' => '',
        ]);

        return view('gists.index', compact('state'));
    }
}
