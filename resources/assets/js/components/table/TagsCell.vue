<template>
    <div>
        <span v-for="tag in data"
              class="label tag cell-tag"
              :style="{'background-color': $root.tagColors[tag]}"
              @click="$store.commit('updateFilter', tag)">
            {{ tag }}
        </span>
    </div>
</template>

<script type="text/ecmascript-6">
    export default {
        props: {
            data: {
                type: Array,
                required: true
            }
        }
    }
</script>

<style>
    .cell-tag {
        margin-right: 5px;
        cursor: pointer;
    }
</style>
