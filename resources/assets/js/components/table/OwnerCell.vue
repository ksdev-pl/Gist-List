<template>
    <a href="#" @click.prevent="$store.commit('updateFilter', data)">{{ data }}</a>
</template>

<script type="text/ecmascript-6">
    export default {
        props: {
            data: {
                type: String,
                required: true
            }
        }
    }
</script>
