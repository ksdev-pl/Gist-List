@extends('base')

@section('styles')
<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=RobotoDraft:300,400'>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/octicons/2.0.2/octicons.min.css">
@stop

@section('body')
<section id="page-intro">
    <div id="intro-wrapper">
        <div class="pull-left text-center">
            <h1>Gist List</h1>
            <p>With <mark>#tags</mark> and <mark>backup</mark></p>
            <a href="/signin" class="btn btn-primary btn-lg btn-action">Sign in with GitHub</a>
        </div>
        <div class="pull-right">
            <span class="mega-octicon octicon-gist text-muted"></span>
        </div>
    </div>
    <div id="intro-arrow-wrapper" class="text-center display-none">
        <span class="mega-octicon octicon-arrow-down"></span>
    </div>
</section>
<a href="https://github.com/ksdev-pl/Gist-List" target="_blank">
    <img
        style="position: absolute; top: 0; right: 0; border: 0;"
        src="https://camo.githubusercontent.com/38ef81f8aca64bb9a64448d0d70f1308ef5341ab/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f6461726b626c75655f3132313632312e706e67"
        alt="Fork me on GitHub"
        data-canonical-src="https://s3.amazonaws.com/github/ribbons/forkme_right_darkblue_121621.png">
</a>
<section id="page-about">
    <div id="about-wrapper">
        <h2 class="text-center">Clear Organization of Your Gists</h2>
        <div class="row">
            <div class="col-sm-6">
                <h3><span class="mega-octicon octicon-tag fa-fw"></span> <mark>Add tags</mark></h3>
                <p>
                    Every word in a #tag format added to a gist description will be transformed to a colored tag. As
                    this is done using Github, you are not dependent on Gist List implementation.
                </p>
                <h3><span class="mega-octicon octicon-file-zip fa-fw"></span> <mark>Backup</mark></h3>
                <p>
                    Download a zip archive of all your gists, together with those that you starred. The only
                    limitation is the size of each gist file (max 100 kB), and the frequency of one backup every
                    five minutes.
                </p>
            </div>
            <div class="col-sm-6">
                <h3><span class="mega-octicon octicon-search fa-fw"></span> <mark>Sort and Search</mark></h3>
                <p>
                    Unleash the power of a sortable table! ;-) With the help of the great DataTables
                    instantly paginate, sort, search and filter all your gists using clear and simple interface.
                </p>
                <h3><span class="mega-octicon octicon-lock fa-fw"></span> <mark>Keep your privacy</mark></h3>
                <p>
                    No user data is stored apart from the usual server statistics and temporary files
                    needed to perform backup - these are deleted every hour by a cron job. No database is used
                    - gists are fetched anew with every connection.
                </p>
            </div>
        </div>
    </div>
</section>
<section id="page-contact">
    <div id="contact-wrapper" class="text-center">
        <h2>Contact <a href="https://github.com/ksdev-pl" target="_blank">@ksdev-pl</a></h2>
        <p>Source code available on GitHub<br> under the MIT license</p>
    </div>
</section>
@stop