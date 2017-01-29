import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        user: window.state.user,
        gists: window.state.gists,
        filterBy: '',
        tags: window.state.tags
    },
    mutations: {
        updateFilter(state, filter) {
            state.filterBy = filter;
        }
    }
})
