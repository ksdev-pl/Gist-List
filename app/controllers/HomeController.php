<?php

class HomeController extends Controller
{
    /**
     * Show main page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return View::make('index');
    }
}
