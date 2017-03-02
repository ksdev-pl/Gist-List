<template>
    <div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th v-for="column in columns"
                        @click="column.sortable != false ? sortBy(column.key) : null"
                        :class="[{'th-clickable': column.sortable != false, 'th-active': column.key == mtblSortCol}, 'table-th-' + column.key]">
                        {{ column.label }}
                        <span v-if="column.sortable != false"
                              :class="['sort-order-icon', {'sort-order-active': column.key == mtblSortCol}]">
                            <i class="fa fa-sort" v-if="column.key != mtblSortCol"></i>
                            <i class="fa fa-sort-up" v-if="column.key == mtblSortCol && mtblSortOrder == 1"></i>
                            <i class="fa fa-sort-down" v-if="column.key == mtblSortCol && mtblSortOrder == -1"></i>
                        </span>
                    </th>
                </tr>
            </thead>
            <tbody>
            <tr v-for="row in paginatedRows">
                <td v-for="column in columns">
                    <span v-if="!column.hasOwnProperty('component')">{{ row[column.key]}}</span>
                    <component v-else
                               :is="column.component"
                               :data="getCellCompData(row, column)"
                               @cell-action="onCellAction">
                    </component>
                </td>
            </tr>
            <tr v-if="paginatedRows.length == 0" class="text-center active">
                <td :colspan="columns.length">
                    <span v-if="rows.length > 0">No matching records found</span>
                    <span v-else>No data available in table</span>
                </td>
            </tr>
            </tbody>
        </table>

        <div>
            <div class="pull-left pagination-info">
                <select v-model="mtblRowsPerPage" class="form-control input-sm rows-per-page-select">
                    <option :value="option" v-for="option in rowsPerPageOptions">{{ option }}</option>
                </select>

                Showing {{ offset + 1 < filteredRows.length ? offset + 1 : filteredRows.length }}
                to {{ offset + mtblRowsPerPage < filteredRows.length ? offset + mtblRowsPerPage : filteredRows.length }}
                of {{ filteredRows.length }} entries
                <span v-if="filterBy.trim().length > 0 && filteredRows.length < rows.length">
                    (filtered from {{ rows.length }} total entries)
                </span>
            </div>
            <nav class="pull-right" v-if="formattedPages.length > 1">
                <ul class="pagination">
                    <li :class="{'disabled': currentPage == 1}">
                        <a href="#" @click.prevent="changePage(currentPage - 1)">
                            <i class="fa fa-angle-left"></i>
                        </a>
                    </li>
                    <li v-for="page in formattedPages"
                        :class="{'active': currentPage == page, 'disabled': page == ELLIPSIS}">
                        <a href="#" @click.prevent="changePage(page)" v-html="page"></a>
                    </li>
                    <li :class="{'disabled': currentPage == pagesCount}">
                        <a href="#" @click.prevent="changePage(currentPage + 1)">
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>

<script type="text/ecmascript-6">
    export default {
        props: {
            columns: {
                type: Array,
                required: true
            },
            rows: {
                type: Array,
                required: true
            },
            filterBy: {
                type: String,
                default: ''
            },
            sortColumn: {
                type: String,
                required: false
            },
            sortOrder: {
                type: Number,
                default: 1
            },
            rowsPerPage: {
                type: Number,
                default: 20
            }
        },

        data: function () {
            return {
                ELLIPSIS: '&hellip;',
                mtblSortCol: this.sortColumn && this.sortColumn.trim().length > 0
                    ? this.sortColumn
                    : this.columns[0].id,
                mtblSortOrder: this.sortOrder,
                currentPage: 1,
                mtblRowsPerPage: this.rowsPerPage,
                rowsPerPageOptions: [10, 20, 50, 100]
            }
        },

        computed: {
            filteredRows() {
                let rows = this.rows;
                let filters = this.filterBy.trim().toLowerCase().split(' ');
                let filteredRows = null;

                _.forEach(filters, (filter) => {
                    let filteredRowsPart = rows.filter((row) => {
                        return Object.keys(row).some((key) => {
                            return String(row[key]).toLowerCase().indexOf(filter) > -1
                        })
                    });

                    if (filteredRows === null) {
                        filteredRows = filteredRowsPart;
                    } else {
                        filteredRows = _.intersectionBy(filteredRows, filteredRowsPart, 'id');
                    }
                });

                filteredRows = filteredRows.sort((a, b) => {
                    a = a[this.mtblSortCol];
                    b = b[this.mtblSortCol];

                    return (a === b ? 0 : (a > b ? 1 : -1)) * this.mtblSortOrder
                });

                return filteredRows;
            },
            paginatedRows() {
                return this.filteredRows.slice(this.offset, this.offset + this.mtblRowsPerPage);
            },
            offset() {
                return (this.currentPage - 1) * this.mtblRowsPerPage;
            },
            pagesCount() {
                return Math.ceil(this.filteredRows.length / this.mtblRowsPerPage);
            },
            watchableSortFilter() {
                return this.filterBy + this.mtblSortCol + this.mtblSortOrder + this.mtblRowsPerPage;
            },
            formattedPages() {
                let pages = [];
                let lastPageDots = false;

                for (let page = 1; page <= this.pagesCount; page++) {

                    if ((page == 1 || page == this.pagesCount)
                        || (page > this.currentPage - 5 && page < this.currentPage + 5)
                    ) {
                        lastPageDots = false;
                        pages.push(page);
                    } else if (!lastPageDots) {
                        lastPageDots = true;
                        pages.push(this.ELLIPSIS);
                    }
                }

                return pages;
            }
        },

        methods: {
            onCellAction(data) {
                this.$emit('cell-action', data);
            },
            sortBy(key) {
                if (this.mtblSortCol == key) {
                    this.mtblSortOrder = - this.mtblSortOrder;
                    return;
                }

                this.mtblSortCol = key;
                this.mtblSortOrder = 1;
            },
            changePage(page) {
                if (page != this.ELLIPSIS && page >= 1 && page <= this.pagesCount) {
                    this.currentPage = page;
                }
            },
            getCellCompData(row, column) {
                if (column.hasOwnProperty('data')) {
                    let data = {};
                    _.forEach(column.data, (key) => {
                        data[key] = row[key];
                    });
                    return data;
                } else {
                    return row[column.key];
                }
            }
        },

        watch: {
            watchableSortFilter() {
                this.currentPage = 1;
            },
            mtblRowsPerPage(val) {
                this.$emit('rows-per-page-changed', val);
            }
        }
    }
</script>

<style>
    .sort-order-icon {
        float: right;
        color: #c7c7c7;
    }
    .sort-order-active, .th-active {
        color: #337ab7;
    }
    .th-clickable {
        cursor: pointer;
    }
    .pagination {
        margin: 0;
    }
    .pagination-info {
        padding-top: 7px;
    }
    .rows-per-page-select {
        display: inline-block;
        width: inherit;
        margin-right: 10px;
        margin-top: -5px;
    }
</style>
