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
                            <button type="button"
                                    @click.prevent="doBackup"
                                    :disabled="!isBackupEnabled"
                                    class="btn btn-default btn-block btn-backup">
                                <span v-if="isBackupEnabled">Backup Gists</span>
                                <span v-else>Please wait...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="list-group">
                <a href="#" class="search-filter list-group-item filter-all"
                   :class="{'active': filterBy.length == 0}" @click.prevent="filterBy = ''">
                    All
                    <span class="pull-right">{{ counter.all }}</span>
                </a>
                <a href="#" class="search-filter list-group-item filter-my-gists"
                   :class="{'active': ['@owned', user.nickname].indexOf(filterBy) > -1}"
                   @click.prevent="filterBy = '@owned'">
                    @owned
                    <span class="pull-right">{{ counter.owned }}</span>
                </a>
                <a href="#" class="search-filter list-group-item filter-starred"
                   :class="{'active': filterBy == '@starred'}" @click.prevent="filterBy = '@starred'">
                    @starred
                    <span class="pull-right">{{ counter.starred }}</span>
                </a>
                <a href="#" class="search-filter list-group-item filter-public"
                   :class="{'active': filterBy == '@public'}" @click.prevent="filterBy = '@public'">
                    @public
                    <span class="pull-right">{{ counter.public }}</span>
                </a>
                <a href="#" class="search-filter list-group-item filter-private"
                   :class="{'active': filterBy == '@private'}" @click.prevent="filterBy = '@private'">
                    @private
                    <span class="pull-right">{{ counter.private }}</span>
                </a>
            </div>
        </div>
        <div class="list-group scrollable-list">
            <a v-for="tag in sortedTags" href="#" class="search-filter list-group-item filter-tag"
               :class="{'active': filterBy == tag}"
               @click.prevent="filterBy = tag">
                <span class="label tag"
                      :style="{backgroundColor: $root.tagColors[tag]}">{{ tag | truncate(20) }}</span>
                <span class="pull-right">{{ counter.tags[tag] }}</span>
            </a>
        </div>
    </div>
</template>

<script type="text/ecmascript-6">
    export default {
        data: function () {
            return {
                isBackupEnabled: true
            }
        },

        computed: {
            user() { return this.$store.state.user },
            gists() { return this.$store.state.gists },
            tags() { return this.$store.state.tags },
            filterBy: {
                get() { return this.$store.state.filterBy },
                set(value) { this.$store.commit('updateFilter', value) }
            },
            sortedTags() {
                return _.sortBy(this.tags);
            },
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
        },

        methods: {
            doBackup() {
                $.fileDownload('/gists/backup', {
                    prepareCallback: url => {
                        this.isBackupEnabled = false;
                        $(".alert-info").fadeIn().delay(6000).fadeOut();
                    },
                    successCallback: url => {
                        this.isBackupEnabled = true;
                    },
                    failCallback: (html, url) => {
                        this.isBackupEnabled = true;
                        console.log(html);

                        switch (html) {
                            case 'WAIT_A_MINUTE':
                                console.log('WAIT_A_MINUTE');
                                $(".alert-info").hide();
                                $(".alert-warning-text").text('Backup interrupted. Please wait 5 minutes before trying again.');
                                $(".alert-warning").fadeIn().delay(6000).fadeOut();
                                break;
                            case 'NO_FILES':
                                console.log('NO_FILES');
                                $(".alert-info").hide();
                                $(".alert-warning-text").text('Backup interrupted. There are no files to backup.');
                                $(".alert-warning").fadeIn().delay(6000).fadeOut();
                                break;
                            default:
                                console.log('DEFAULT');
                                $(".alert-info").hide();
                                $(".alert-danger").fadeIn();
                                break;
                        }
                    }
                });
            }
        }
    }
</script>
