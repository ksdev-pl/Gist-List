<!--
Partials example:

<template id="table-cell-partial">
    <template v-if="column.key == 'name'">
        <b>@{{ rowData[column.key] }}</b>
    </template>
    <template v-else>
        @{{ rowData[column.key] }}
    </template>
</template>

<template id="row-details-partial">
    Row details
</template>
-->

<style>
    table .arrow {
        display: inline-block;
        vertical-align: middle;
        width: 0;
        height: 0;
        margin-left: 5px;
    }
    table .arrow.asc {
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-bottom: 4px solid #fff;
    }
    table .arrow.dsc {
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-top: 4px solid #fff;
    }

    th {
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -user-select: none;
    }

    th.selected {
        color: #337ab7;
    }

    th.selected .arrow {
        border-bottom-color: #337ab7;
        border-top-color: #337ab7;
    }

    .table > tbody + tbody.tbody-no-border {
        border-top: none;
    }
</style>

<template>
    <form>
        <div class="form-group has-feedback">
            <input class="form-control" v-model="searchQuery" debounce="200">
            <span class="glyphicon glyphicon-search form-control-feedback"></span>
        </div>
    </form>
    <div class="clearfix">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th v-for="column in columns"
                            @click="sortBy(column.key)"
                            :class="{selected: sortByColumn == column.key}"
                            id="table-th-{{ column.key }}">
                            {{ column.name | capitalize }}
                                <span class="arrow"
                                      :class="sortOrder > 0 ? 'asc' : 'dsc'">
                                </span>
                        </th>
                    </tr>
                </thead>
                <tbody :class="{'tbody-no-border': !rowDetailsPartial}"
                       v-for="rowData in filteredRowsData
                           | orderBy sortByColumn sortOrder
                           | limitBy limit offset">
                    <tr>
                        <td v-for="column in columns">
                            <partial :name="tableCellPartial"></partial>
                        </td>
                    </tr>
                    <tr v-if="rowDetailsPartial">
                        <td :colspan="columns.length">
                            <partial :name="rowDetailsPartial"></partial>
                        </td>
                    </tr>
                </tbody>
                <tbody v-if="filteredRowsData.length == 0">
                    <tr>
                        <td :colspan="columns.length">Nothing found</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <pagination :num-rows="filteredRowsData.length"
                    :limit.sync="limit"
                    :offset.sync="offset"
                    :page.sync="page">
        </pagination>
    </div>
</template>

<script type="text/ecmascript-6">
    const Vue = require('vue');
    const Pagination = require('../components/Pagination.vue');

    module.exports = {
        props: {
            rowsData: {
                type: Array,
                required: true
            },
            columns: {
                type: Array,
                required: true
            },
            sortByColumn: {
                type: String,
                required: true
            },
            tableCellPartial: {
                type: String,
                required: true
            },
            rowDetailsPartial: {
                type: String,
                default: null
            },
            searchQuery: {
                type: String,
                default: ''
            }
        },
        components: {
            Pagination
        },
        created() {
            Vue.partial(this.tableCellPartial, this.tableCellPartial);
            if (this.rowDetailsPartial) {
                Vue.partial(this.rowDetailsPartial, this.rowDetailsPartial);
            }
        },
        data() {
            return {
                sortOrder: 1,

                // Pagination
                page: 0,
                limit: 20,
                offset: 0
            }
        },
        computed: {
            filteredRowsData() {
                let filterBy = Vue.filter('filterBy');
                return filterBy(this.rowsData, this.searchQuery);
            }
        },
        methods: {
            sortBy(columnName) {
                this.sortByColumn = columnName;
                this.sortOrder *= -1;
            }
        },
        watch: {
            sortByColumn() {
                this.sortOrder = 1;
            },
            searchQuery() {
                this.page = 0;
            }
        }
    }
</script>
