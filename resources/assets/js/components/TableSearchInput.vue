<template>
    <div class="form-group has-feedback">
        <input type="text" class="form-control" :value="value" v-on:input="updateValue($event.target.value)">
        <span v-if="value.length == 0" class="glyphicon glyphicon-search form-control-feedback"></span>
        <span v-else
              class="glyphicon glyphicon-remove form-control-feedback text-primary"
              @click="updateValue('')">
        </span>
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

<style>
    .glyphicon-remove.form-control-feedback {
        cursor: pointer;
        pointer-events: inherit;
    }
</style>
