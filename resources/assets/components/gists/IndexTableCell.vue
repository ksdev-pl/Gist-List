<template>
    <template v-if="columnKey == 'tags'">
        <span v-for="tag in cellData" class="label tag"
              @click="filterBy(tag)"
              :style="{marginRight: '5px', backgroundColor: $root.tagColors[tag]}">
            {{ tag }}
        </span>
    </template>
    <template v-if="['tags'].indexOf(columnKey) == -1">
        {{ cellData }}
    </template>
</template>

<script type="text/ecmascript-6">
    module.exports = {
        props: {
            cellData: {
                required: true
            },
            columnKey: {
                type: String,
                required: true
            }
        },
        methods: {
            filterBy(query) {
                this.$dispatch('searchQuery', query);
            }
        }
    }
</script>
