import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from 'vuex';
import router from './router';
import StoreData from './store';
import App from './components/App';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';


Vue.use(VueRouter);
Vue.use(Vuex);

const store = new Vuex.Store(StoreData);
// Install BootstrapVue
Vue.use(BootstrapVue);
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin);

const app = new Vue({
    el: '#app',
    router,
    store,
    components: {
        App
    }
});
