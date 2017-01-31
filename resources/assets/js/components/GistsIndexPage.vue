<template>
    <div>
        <sidebar></sidebar>

        <div class="main">
            <div class="container-fluid">
                <table-search-input v-model="filterBy"></table-search-input>
                <dynamic-table :columns="columns"
                               :rows="gists"
                               :filter-by="filterBy"
                               sort-column="description">
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
                ]
            }
        },

        computed: {
            gists() { return this.$store.state.gists; },
            filterBy: {
                get() { return this.$store.state.filterBy },
                set(value) { this.$store.commit('updateFilter', value) }
            }
        }
    }
</script>
