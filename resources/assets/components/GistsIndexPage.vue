<style>
    .tag {
        cursor: pointer;
    }
</style>

<template>
    <left-menu :gists="store.gists"
               :user="store.user"
               :tags="store.tags"
               :search-query.sync="store.searchQuery">
    </left-menu>

    <div class="main">
        <div class="container-fluid">
            <table-grid :rows-data="store.gists"
                        :columns="store.columns"
                        :search-query.sync="store.searchQuery"
                        sort-by-column="updated"
                        table-cell-component="GistsTableCell">
            </table-grid>
        </div>
    </div>
</template>

<script type="text/ecmascript-6">
    const TableGrid = require('../components/TableGrid.vue');
    const LeftMenu = require('../components/LeftMenu.vue');

    module.exports = {
        data() {
            return {
                store: this.$root.store
            }
        },
        components: {
            TableGrid,
            LeftMenu
        }
    }
</script>
