<template>
    <div>
        <sidebar></sidebar>

        <div class="main">
            <div class="container-fluid">
                <table-search-input v-model="state.filterBy"></table-search-input>
                <dynamic-table :columns="columns"
                               :rows="state.gists"
                               :filter-by="state.filterBy"
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
                state: store.state,
                columns: [
                    {key: 'tags', label: 'Tags', template: 'tags-cell'},
                    {key: 'description', label: 'Description'},
                    {key: 'owner', label: 'Owner'},
                    {key: 'created', label: 'Created'},
                    {key: 'updated', label: 'Updated'}
                ]
            }
        },

        methods: {
            onCellAction(data) {
                switch (data.name) {
                    case 'filterByTag':
                        this.state.filterBy = data.value;
                        break;
                    default:
                        throw 'Invalid onCellAction data.name'
                }
            }
        }
    }
</script>
