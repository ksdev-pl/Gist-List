<template>
    <div>
        <sidebar></sidebar>

        <div class="main">
            <div class="container-fluid">
                <div class="alert alert-warning alert-dismissable display-none">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <span class="alert-warning-text"></span>
                </div>
                <div class="alert alert-info alert-dismissable display-none">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    Backup is being prepared. Your download should start automatically.
                </div>
                <div class="alert alert-danger alert-dismissable display-none">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    Backup failed.
                </div>
                <table-search-input v-model="filterBy"></table-search-input>
                <dynamic-table :columns="columns"
                               :rows="gists"
                               :filter-by="filterBy"
                               sort-column="updated"
                               :sort-order="-1"
                               :rows-per-page="rowsPerPage"
                               @rows-per-page-changed="storeRowsPerPage">
                </dynamic-table>
            </div>
        </div>
    </div>
</template>

<script type="text/ecmascript-6">
    export default {
        data: function () {
            return {
                columns: [
                    { key: 'tags', label: 'Tags', component: 'tags-cell' },
                    {
                        key: 'description',
                        label: 'Description',
                        component: 'description-cell',
                        data: ['description', 'files', 'type', 'html_url']
                    },
                    { key: 'owner', label: 'Owner', component: 'owner-cell' },
                    { key: 'created', label: 'Created' },
                    { key: 'updated', label: 'Updated' }
                ],
                rowsPerPage: +localStorage.getItem('rows_per_page') || 20
            }
        },

        computed: {
            gists() { return this.$store.state.gists; },
            filterBy: {
                get() { return this.$store.state.filterBy },
                set(value) { this.$store.commit('updateFilter', value) }
            }
        },

        methods: {
            storeRowsPerPage(val) {
                localStorage.setItem('rows_per_page', val);
            }
        }
    }
</script>

<style>
    .table-th-tags {
        width: 200px;
    }
    .table-th-owner, .table-th-created, .table-th-updated {
        width: 150px;
    }
</style>
