@extends('__base')

@section('styles')
<style>
    body {
        font-family: Helvetica Neue, Arial, sans-serif;
        font-size: 14px;
        color: #444;
    }

    th {
        /*background-color: #42b983;*/
        /*color: rgba(255,255,255,0.66);*/
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -user-select: none;
    }

    th.selected {
        /*color: #fff;*/
        color: #337ab7;
    }

    th.selected .arrow {
        /*opacity: 1;*/
        border-bottom-color: #337ab7;
        border-top-color: #337ab7;
    }

    .arrow {
        display: inline-block;
        vertical-align: middle;
        width: 0;
        height: 0;
        margin-left: 5px;
        /*opacity: 0.66;*/
    }

    .arrow.asc {
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-bottom: 4px solid #fff;
    }

    .arrow.dsc {
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-top: 4px solid #fff;
    }

    .tag {
        cursor: pointer;
    }

    #table-th-1 {
        width: 200px;
    }
    #table-th-3, #table-th-4, #table-th-5 {
        width: 150px;
    }

    #search-input {
        position: absolute;
        left: 270px;
        right: 100px;
    }
</style>
@endsection

@section('scripts')
    <!-- component template -->
    <script type="text/x-template" id="table-grid-template">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th v-for="key in columns"
                            v-on:click="sortBy(key)"
                            v-bind:class="{selected: sortKey == key, 'display-none': key == 'type'}"
                            id="table-th-@{{ $index }}">
                            @{{ key | capitalize }}
                        <span class="arrow"
                              v-bind:class="sortOrders[key] > 0 ? 'asc' : 'dsc'">
                      </span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="entry in data
                        | filterBy filterKey in 'type' 'tags' 'description' 'owner' 'created' 'updated' 'files'
                        | orderBy sortKey sortOrders[sortKey]">
                        <td v-for="key in columns"
                            v-bind:class="{'display-none': key == 'type'}">
                            <template v-if="key == 'tags'">
                                <span v-for="tag in entry[key]" class="label tag"
                                      v-on:click="filterKey = tag"
                                      v-bind:style="{marginRight: '5px', backgroundColor: this.$root.tagColors[tag]}">
                                    @{{ tag }}
                                </span>
                            </template>
                            <template v-if="key == 'type'">
                                <span v-for="type in entry[key]">
                                    @{{ type }}
                                </span>
                            </template>
                            <template v-if="key == 'owner'">
                                <a href="#" v-on:click.prevent="filterKey = entry[key]">@{{ entry[key] }}</a>
                            </template>
                            <template v-if="key == 'description'">
                                <i v-if="entry.type.indexOf('@private') > -1" class="fa fa-lock fa-fw text-muted"></i>
                                <i v-if="entry.type.indexOf('@starred') > -1" class="fa fa-star fa-fw text-star"></i>
                                <a href="@{{ entry.html_url }}" target="_blank">@{{ entry[key] }}</a>
                                <br>
                                <a v-for="file in entry.files" href="@{{ file.raw_url }}" v-show="filesVisible"
                                   target="_blank" style="margin-right: 5px"><code>@{{ file.filename }}</code></a>
                            </template>
                            <template v-if="['tags', 'type', 'owner', 'description'].indexOf(key) == -1">
                                @{{ entry[key] }}
                            </template>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </script>

    <script>
        // register the grid component
        Vue.component('table-grid', {
            template: '#table-grid-template',
            props: {
                data: Array,
                columns: Array,
                filterKey: String,
                filesVisible: Boolean
            },
            data: function () {
                var sortOrders = {}
                this.columns.forEach(function (key) {
                    sortOrders[key] = -1
                })
                return {
                    sortKey: 'updated',
                    sortOrders: sortOrders
                }
            },
            methods: {
                sortBy: function (key) {
                    this.sortKey = key
                    this.sortOrders[key] = this.sortOrders[key] * -1
                }
            }
        })

        // bootstrap the app
        var vm = new Vue({
            el: 'body',
            data: {
                searchQuery: '',
                gridColumns: ['type', 'tags', 'description', 'owner', 'created', 'updated'],
                gridData: {!! $gists !!},

                tags: {!! $tags !!},

                filesVisible: false
            },
            computed: {
                tagColors: function () {
                    var tagColors = {};
                    _.forEach(this.tags, function (tag) {
                        tagColors[tag] = this.stringToColor(tag);
                    }, this)
                    return tagColors;
                },
                counter: function () {
                    var counter = {
                        'all': 0,
                        'owned': 0,
                        'starred': 0,
                        'public': 0,
                        'private': 0,
                        'tags': {}
                    };

                    _.forEach(this.gridData, function (gist) {
                        counter.all++;

                        if (_.contains(gist.type, '@owned')) {
                            counter.owned++;
                        }
                        if (_.contains(gist.type, '@starred')) {
                            counter.starred++;
                        }
                        if (_.contains(gist.type, '@public')) {
                            counter.public++;
                        } else {
                            counter.private++;
                        }

                        if (gist.tags.length == 0) {
                            counter.withoutTag++;
                        } else {
                            _.forEach(gist.tags, function (tag) {
                                if (counter.tags.hasOwnProperty(tag)) {
                                    counter.tags[tag]++;
                                } else {
                                    counter.tags[tag] = 1;
                                }
                            })
                        }
                    });

                    return counter;
                }
            },
            methods: {
                stringToColor: function(str) {
                    if (str == '#none') {
                        return '#777777'
                    }

                    var hash = 0;
                    for (var i = 0; i < str.length; i++) {
                        hash = str.charCodeAt(i) + ((hash << 5) - hash);
                    }
                    var color = '#';
                    for (var j = 0; j < 3; j++) {
                        var value = (hash >> (j * 8)) & 0xFF;
                        color += ('00' + value.toString(16)).substr(-2);
                    }

                    var tc = tinycolor(color);
                    var brightness = tc.getBrightness();
                    if (brightness > 180) {
                        tc.darken(brightness - 180);
                    }

                    return tc.toString();
                }
            }
        })
    </script>
@endsection

@section('content')
    @include('gists._left_menu')
    <div class="main">
        <div class="container-fluid">
            <!-- app root element -->
            <div>
                <form class="clearfix" style="margin-bottom: 15px">
                    <div id="search-input" class="form-group has-feedback">
                        <input class="form-control input-lg" name="query"
                               v-model="searchQuery" debounce="200" autofocus>
                        <span class="glyphicon glyphicon-search form-control-feedback"></span>
                    </div>
                    <div id="table-options" class="btn-group pull-right">
                        <button type="button" class="btn btn-default btn-lg dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-cog"></i> <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu pull-right" role="menu">
                            <li>
                                <a id="toggle-files" href="#" v-on:click="filesVisible = !filesVisible">
                                    Toggle files visibility
                                </a>
                            </li>
                        </ul>
                    </div>
                </form>
                <table-grid v-bind:data="gridData"
                    v-bind:columns="gridColumns"
                    v-bind:filter-key.sync="searchQuery"
                    v-bind:files-visible="filesVisible">
                </table-grid>
            </div>
        </div>
    </div>
@endsection
