<style>

</style>

<template>
    <div class="left-menu">
        <div id="actions-wrapper">
            <div class="media">
                <a class="pull-left" href="#" target="_blank">
                    <img class="media-object avatar" :src="user.avatar">
                </a>
                <div class="media-body">
                    <h4 class="media-heading">{{ user.name }}</h4>
                    <span id="user-login">{{ user.nickname }}</span><br>
                    <a href="/logout">Sign out</a>
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
                   :class="{'active': searchQuery.length == 0}" @click.prevent="searchQuery = ''">
                    All
                    <span class="pull-right">{{ counter.all }}</span>
                </a>
                <a href="#" class="search-filter list-group-item filter-my-gists"
                   :class="{'active': ['@owned', user.nickname].indexOf(searchQuery) > -1}"
                   @click.prevent="searchQuery = '@owned'">
                    @owned
                    <span class="pull-right">{{ counter.owned }}</span>
                </a>
                <a href="#" class="search-filter list-group-item filter-starred"
                   :class="{'active': searchQuery == '@starred'}" @click.prevent="searchQuery = '@starred'">
                    @starred
                    <span class="pull-right">{{ counter.starred }}</span>
                </a>
                <a href="#" class="search-filter list-group-item filter-public"
                   :class="{'active': searchQuery == '@public'}" @click.prevent="searchQuery = '@public'">
                    @public
                    <span class="pull-right">{{ counter.public }}</span>
                </a>
                <a href="#" class="search-filter list-group-item filter-private"
                   :class="{'active': searchQuery == '@private'}" @click.prevent="searchQuery = '@private'">
                    @private
                    <span class="pull-right">{{ counter.private }}</span>
                </a>
            </div>
        </div>
        <div class="list-group scrollable-list">
            <a v-for="tag in tags | orderBy true" href="#" class="search-filter list-group-item filter-tag"
               :class="{'active': searchQuery == tag}"
               @click.prevent="searchQuery = tag">
                <span class="label tag"
                      :style="{backgroundColor: $root.tagColors[tag]}">{{ tag }}</span>
                <span class="pull-right">{{ counter.tags[tag] }}</span>
            </a>
        </div>
    </div>
</template>

<script type="text/ecmascript-6">
    window._ = require('lodash');

    module.exports = {
        props: {
            gists: {
                type: Array,
                required: true
            },
            user: {
                type: Object,
                required: true
            },
            searchQuery: {
                type: String,
                default: ''
            },
            tags: {
                type: Array,
                required: true
            }
        },
        computed: {
            counter() {
                let counter = {
                    'all': 0,
                    'owned': 0,
                    'starred': 0,
                    'public': 0,
                    'private': 0,
                    'tags': {}
                };
                _.forEach(this.gists, gist => {
                    counter.all++;
                    if (_.includes(gist.type, '@owned')) {
                        counter.owned++;
                    }
                    if (_.includes(gist.type, '@starred')) {
                        counter.starred++;
                    }
                    if (_.includes(gist.type, '@public')) {
                        counter.public++;
                    } else {
                        counter.private++;
                    }
                    if (gist.tags.length == 0) {
                        counter.withoutTag++;
                    } else {
                        _.forEach(gist.tags, tag => {
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
        }
    }
</script>
