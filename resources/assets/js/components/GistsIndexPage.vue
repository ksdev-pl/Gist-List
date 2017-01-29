<template>
    <div class="container" style="margin-top: 40px">
        <table-search-input v-model="filterBy"></table-search-input>
        <dynamic-table :columns="columns"
                       :rows="gists"
                       :filter-by="filterBy"
                       sort-column="description"
                       v-on:cell-action="onCellAction">
        </dynamic-table>
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

        data: function () {
            return {
                columns: [
                    {key: 'tags', label: 'Tags', template: 'tags-cell', sortable: false},
                    {key: 'description', label: 'Description'},
                    {key: 'owner', label: 'Owner'},
                    {key: 'created', label: 'Created'},
                    {key: 'updated', label: 'Updated'}
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
