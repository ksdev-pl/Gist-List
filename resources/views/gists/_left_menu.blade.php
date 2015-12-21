<div class="left-menu">
    <div id="actions-wrapper">
        <div class="media">
            <a class="pull-left" href="#" target="_blank">
                <img class="media-object avatar" src="{{ \Auth::user()->avatar }}">
            </a>
            <div class="media-body">
                <h4 class="media-heading">{{ \Auth::user()->name }}</h4>
                <span id="user-login">{{ \Auth::user()->nickname }}</span><br>
                <a href="{{ action('Auth\AuthController@logout') }}">Sign out</a>
            </div>
        </div>
        <br>
        <div class="panel-group" id="accordion">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <h4 class="panel-title">
                        <a id="actions-collapse" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
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
            <a href="#" class="search-filter list-group-item filter-all"
               v-bind:class="{'active': searchQuery.length == 0}" v-on:click.prevent="searchQuery = ''">
                All
                <span class="pull-right">@{{ counter.all }}</span>
            </a>
            <a href="#" class="search-filter list-group-item filter-my-gists"
               v-bind:class="{'active': ['@owned', '{{ \Auth::user()->nickname }}'].indexOf(searchQuery) > -1}"
               v-on:click.prevent="searchQuery = '@owned'">
                @owned
                <span class="pull-right">@{{ counter.owned }}</span>
            </a>
            <a href="#" class="search-filter list-group-item filter-starred"
               v-bind:class="{'active': searchQuery == '@starred'}" v-on:click.prevent="searchQuery = '@starred'">
                @starred
                <span class="pull-right">@{{ counter.starred }}</span>
            </a>
            <a href="#" class="search-filter list-group-item filter-public"
               v-bind:class="{'active': searchQuery == '@public'}" v-on:click.prevent="searchQuery = '@public'">
                @public
                <span class="pull-right">@{{ counter.public }}</span>
            </a>
            <a href="#" class="search-filter list-group-item filter-private"
               v-bind:class="{'active': searchQuery == '@private'}" v-on:click.prevent="searchQuery = '@private'">
                @private
                <span class="pull-right">@{{ counter.private }}</span>
            </a>
        </div>
    </div>
    <div class="list-group scrollable-list">
        <a v-for="tag in tags | orderBy true" href="#" class="search-filter list-group-item filter-tag"
           v-bind:class="{'active': searchQuery == tag}"
           v-on:click.prevent="searchQuery = tag">
            <span class="label tag"
                  v-bind:style="{backgroundColor: this.$root.tagColors[tag]}">@{{ tag }}</span>
            <span class="pull-right">@{{ counter.tags[tag] }}</span>
        </a>
    </div>
</div>
