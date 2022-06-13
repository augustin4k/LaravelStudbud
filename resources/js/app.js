/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue').default;

import Vue from "vue";
// vuelidate
import Vuelidate from 'vuelidate';
import VueCompositionAPI from '@vue/composition-api';
// bootstrap import
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import PortalVue from 'portal-vue'
// jquery
import JQuery from 'jquery'
window.$ = JQuery
// vuelidate
Vue.use(VueCompositionAPI)
Vue.use(Vuelidate)
// bootstrap
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
Vue.use(PortalVue)

// layouts
Vue.component('v-header', require('./layouts/Header_v1.vue').default);
Vue.component('v-header-auth', require('./layouts/Header.vue').default);
Vue.component('v-footer', require('./layouts/Footer.vue').default);
Vue.component('v-left-bar', require('./layouts/LeftBarMenu.vue').default);
Vue.component('v-sidebar', require('./layouts/Sidebar.vue').default);
// for pages 
Vue.component('v-register', require('./pages/Register.vue').default);
Vue.component('v-login', require('./pages/Login.vue').default);
Vue.component('v-contact', require('./pages/Contact.vue').default);
Vue.component('v-search', require('./pages/Search.vue').default);
Vue.component('v-chat', require('./pages/Chat.vue').default);
Vue.component('v-friends', require('./pages/Friends.vue').default);
Vue.component('v-page-reviews', require('./pages/Reviews.vue').default);
Vue.component('v-page-feed', require('./pages/Feed.vue').default);
Vue.component('v-page-files', require('./pages/Files.vue').default);
// other components
Vue.component('v-buttons-friends', require('./components/others/EditFriendship.vue').default);
Vue.component('v-posts-reviews', require('./components/others/PostsAndReviews.vue').default);
Vue.component('v-features', require('./components/others/InfoCardsAbout.vue').default);
Vue.component('v-table-admin', require('./components/others/TablesAsmin.vue').default);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.                                
 */



const app = new Vue({
    el: '#app',
    // router
});