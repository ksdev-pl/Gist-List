<template>
    <div>
        <span v-for="tag in data"
              class="label tag"
              style="background-color: #5cb85c; margin-right: 5px"
              @click="$emit('cell-action', {name: 'filterByTag', value: tag})">
            {{ tag }}
        </span>
    </div>
</template>

<script>
    export default {
        props: {
            data: {
                type: Array,
                required: true
            }
        }
    }
</script>
