<template>
    <div>
        <sidebar></sidebar>

        <div class="main">
            <div class="container-fluid">
                <table-search-input v-model="filterBy"></table-search-input>
                <dynamic-table :columns="columns"
                               :rows="gists"
                               :filter-by="filterBy"
                               sort-column="description"
                               @cell-action="onCellAction">
                </dynamic-table>
            </div>
        </div>
    </div>
</template>

<script>
    import store from '../store';

    export default {
        data: function () {
            return {
                columns: [
                    { key: 'tags', label: 'Tags', component: 'tags-cell' },
                    { key: 'description', label: 'Description' },
                    { key: 'owner', label: 'Owner' },
                    { key: 'created', label: 'Created' },
                    { key: 'updated', label: 'Updated' }
                ]
            }
        },

        computed: {
            gists() {
                return this.$store.state.gists;
            },
            filterBy: {
                get() { return this.$store.state.filterBy },
                set(value) { this.$store.commit('updateFilter', value) }
            }
        },

        methods: {
            onCellAction(data) {
                switch (data.name) {
                    case 'filterByTag':
                        this.$store.commit('updateFilter', data.value);
                        break;
                    default:
                        throw 'Invalid onCellAction data.name'
                }
            }
        }
    }
</script>
