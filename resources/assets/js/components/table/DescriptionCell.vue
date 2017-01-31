<template>
    <div>
        <div>
            <i v-if="data.type.indexOf('@private') > -1" class="fa fa-lock fa-fw text-muted"></i>
            <i v-if="data.type.indexOf('@starred') > -1" class="fa fa-star fa-fw text-star"></i>
            <a :href="data.html_url" target="_blank">{{ data.description }}</a>
        </div>
        <div v-show="filesVisible">
            <a target="_blank"
               style="margin-right: 5px"
               v-for="file in data.files"
               :href="file.raw_url">
                <code>{{ file.filename }}</code>
            </a>
        </div>
    </div>
</template>

<script type="text/ecmascript-6">
    export default {
        props: {
            data: {
                type: Object,
                required: true
            }
        },

        computed: {
            filesVisible() {
                return this.$store.state.filesVisible;
            }
        }
    }
</script>
