import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        user: window.state ? window.state.user : null,
        gists: window.state ? window.state.gists : null,
        tags: window.state ? window.state.tags : null,
        filterBy: '',
        filesVisible: false
    },
    mutations: {
        updateFilter(state, filter) {
            state.filterBy = filter;
        },
        updateFilesVis(state, value) {
            state.filesVisible = value;
        }
    }
})
