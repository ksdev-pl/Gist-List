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

        return view('gists.index', [
                'gists' => json_encode($gistsAndTags['gists']),
                'tags'  => json_encode($gistsAndTags['tags'])
            ]
        );
    }
}
