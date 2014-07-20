@extends('base')

@section('title')
{{ 'Gist List' }}
@stop

@section('body')
<div class="left-menu">
    <div class="media">
        <a class="pull-left" href="https://gist.github.com/{{ $user->getLogin() }}" target="_blank">
            <img class="media-object avatar" src="{{ $user->getAvatarUrl() }}">
        </a>
        <div class="media-body">
            <h4 class="media-heading">{{{ $user->getName() }}}</h4>
            <span id="user-login">{{{ $user->getLogin() }}}</span><br>
            <a href="/signout">Sign out</a>
        </div>
    </div>
    <br>
    <div class="panel-group" id="accordion">
        <div class="panel panel-default">
            <div class="panel-heading text-center">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                        Actions
                    </a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse">
                <div class="panel-body">
                    <a href="https://gist.github.com" class="btn btn-default btn-block" target="_blank">
                        New Gist
                    </a>
                    <br>
                    <button class="btn btn-default btn-block btn-backup">
                        Backup Gists
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="list-group">
        <a href="#" class="search-filter list-group-item filter-all active">
            All
            <span class="pull-right">{{ $tagCount['all'] }}</span>
        </a>
        <a href="#" class="search-filter list-group-item filter-my-gists">
            My Gists
            <span class="pull-right">{{ $tagCount['myGists'] }}</span>
        </a>
        <a href="#" class="search-filter list-group-item filter-starred">
            Starred
            <span class="pull-right">{{ $tagCount['starred'] }}</span>
        </a>
        <a href="#" class="search-filter list-group-item filter-public">
            Public
            <span class="pull-right">{{ $tagCount['public'] }}</span>
        </a>
        <a href="#" class="search-filter list-group-item filter-private">
            Private
            <span class="pull-right">{{ $tagCount['private'] }}</span>
        </a>
    </div>
    <div class="list-group">
        <a href="#" class="search-filter list-group-item filter-no-tag">
            Without Tag
            <span class="pull-right">{{ $tagCount['noTag'] }}</span>
        </a>
        @foreach ($tagCount['tags'] as $tag)
        <a href="#" class="search-filter list-group-item filter-tag">
            <span class="label tag">{{{ $tag['name'] }}}</span>
            <span class="pull-right">{{ $tag['count'] }}</span>
        </a>
        @endforeach
    </div>
</div>
<div class="main">
    <div class="container-fluid">
        <div class="alert alert-warning alert-dismissable display-none">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            Backup interrupted. Please wait 5 minutes before trying again.
        </div>
        <div class="alert alert-info alert-dismissable display-none">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            Backup is being prepared. Your download should start automatically.
        </div>
        <div class="alert alert-danger alert-dismissable display-none">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            Backup failed.
        </div>
        <table id="gists" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th
                    @if ($tagCount['noTag'] === $tagCount['all'])
                    class="hidden"
                    @endif
                >
                    Tags
                </th>
                <th class="hidden">Starred</th>
                <th>Description and Files</th>
                <th>Owner</th>
                <th class="hidden">Access</th>
                <th>Created</th>
                <th>Updated</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($gists as $gist)
            <tr>
                <td
                    @if ($tagCount['noTag'] === $tagCount['all'])
                    class="hidden"
                    @endif
                    >
                    @foreach ($gist->getTags() as $tag)
                    <a class="search-filter row-filter-tag" href="#"><span class="label tag">{{{ $tag }}}</span></a>
                    @endforeach
                    @if (!$gist->getTags())
                    <span class="hidden">Without Tag</span>
                    @endif
                </td>
                <td class="hidden">
                    @if ($gist->isStarred())
                    Starred
                    @endif
                </td>
                <td>
                    @if (!$gist->isPublic())
                    <i class="fa fa-lock fa-fw text-muted"></i>
                    @endif
                    @if ($gist->isStarred())
                    <i class="fa fa-star fa-fw text-star"></i>
                    @endif
                    <a href="{{ $gist->getHtmlUrl() }}" target="_blank">
                        @if ($gist->getDescription() != '')
                        {{{ $gist->getDescription() }}}
                        @else
                        No description
                        @endif
                    </a><br>
                    @foreach ($gist->getFiles() as $file)
                    <a
                        href="{{ $file['raw_url'] }}"
                        target="_blank"><code>{{{ $file['filename'] }}}</code></a>
                    @endforeach
                </td>
                <td>
                    <a class="search-filter row-filter-owner" href="#">{{{ $gist->getOwner()['login'] }}}</a>
                </td>
                <td class="hidden">
                    @if ($gist->isPublic())
                    Public
                    @else
                    Private
                    @endif
                </td>
                <td>{{ $gist->getCreatedAt() }}</td>
                <td>{{ $gist->getUpdatedAt() }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop