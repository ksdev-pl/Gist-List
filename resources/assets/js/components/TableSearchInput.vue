<template>
    <div class="form-group">
        <input type="text" class="form-control" :value="value" v-on:input="updateValue($event.target.value)">
    </div>
</template>

<script>
    export default {
        props: {
            value: {
                type: String,
                default: ''
            }
        },

        methods: {
            updateValue: _.debounce(function (value) {
                this.$emit('input', value);
            }, 200)
        }
    }
</script>
