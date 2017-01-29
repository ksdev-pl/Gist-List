
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('dynamic-table', require('./components/DynamicTable.vue'));
Vue.component('gists-index-page', require('./components/GistsIndexPage.vue'));
Vue.component('tags-cell', require('./components/TagsCell.vue'));
Vue.component('table-search-input', require('./components/TableSearchInput.vue'));
Vue.component('sidebar', require('./components/Sidebar.vue'));

import store from './store';

const app = new Vue({
    el: '#app',
    store
});
