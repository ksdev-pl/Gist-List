<template>
    <table class="table">
        <thead>
            <tr>
                <th v-for="column in columns">{{ column.label }}</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="row in filteredRows">
                <td v-for="column in columns">
                    <span v-if="!column.hasOwnProperty('template')">{{ row[column.id]}}</span>
                    <component v-else
                               :is="column.template"
                               :data="row[column.id]"
                               v-on:cell-action="onCellAction">
                    </component>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
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
            orderBy: {
                type: String,
                required: false
            }
        },

        data: function () {
            return {
                mutableOrderBy: this.orderBy && this.orderBy.trim().length > 0 ? this.orderBy : this.columns[0].id
            }
        },

        computed: {
            filteredRows() {
                let rows = this.rows;
                let filter = this.filterBy.trim().toLowerCase();

                if (filter.length > 0) {
                    rows = rows.filter((row) => {
                        return Object.keys(row).some((key) => {
                            return String(row[key]).toLowerCase().indexOf(filter) > -1
                        })
                    })
                }

                return rows;
            }
        },

        methods: {
            onCellAction(data) {
                this.$emit('cell-action', data);
            }
        }
    }
</script>
