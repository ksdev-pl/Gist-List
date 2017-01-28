<template>
    <div>
        <table-search-input v-model="filterBy"></table-search-input>
        <dynamic-table :columns="columns"
                       :rows="gists"
                       :filter-by="filterBy"
                       v-on:cell-action="onCellAction"></dynamic-table>
    </div>
</template>

<script>
    export default {
        props: {
            gists: {
                type: Array,
                required: true
            }
        },

        data: () => {
            return {
                columns: [
                    {id: 'tags', label: 'Tags', template: 'tags-cell'},
                    {id: 'description', label: 'Description'},
                    {id: 'owner', label: 'Owner'},
                    {id: 'created', label: 'Created'},
                    {id: 'updated', label: 'Updated'}
                ],
                filterBy: ''
            }
        },

        methods: {
            onCellAction(data) {
                switch (data.name) {
                    case 'filterByTag':
                        this.filterBy = data.value;
                        break;
                    default:
                        throw 'Invalid onCellAction data.name'
                }
            }
        }
    }
</script>
