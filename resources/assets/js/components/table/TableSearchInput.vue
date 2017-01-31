<template>
    <div class="clearfix search-container">
        <div id="search-input" class="form-group has-feedback">
            <input type="text" class="form-control input-lg" :value="value" v-on:input="updateValue($event.target.value)">
            <span v-if="value.length == 0" class="glyphicon glyphicon-search form-control-feedback"></span>
            <span v-else
                  class="glyphicon glyphicon-remove form-control-feedback text-primary"
                  @click="$emit('input', '')">
            </span>
        </div>
        <div id="table-options" class="btn-group pull-right">
            <button type="button" class="btn btn-default btn-lg dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-cog"></i> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu pull-right" role="menu">
                <li>
                    <a id="toggle-files" href="#" @click="filesVisible = !filesVisible">
                        Toggle files visibility
                    </a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script type="text/ecmascript-6">
    export default {
        props: {
            value: {
                type: String,
                default: ''
            }
        },

        computed: {
            filesVisible: {
                get() { return this.$store.state.filesVisible },
                set(value) { this.$store.commit('updateFilesVis', value) }
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
    #search-input {
        position: absolute;
        left: 270px;
        right: 100px;
    }
    .search-container {
        margin-bottom: 15px
    }
</style>
