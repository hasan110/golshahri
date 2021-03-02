import Vue from 'vue';

import vuex from './store';
import store from './store'
import vuetify from './vuetify';
import router from './Services/Router/Router';

Vue.use(vuex);

import LoaderPannel from './components/LoaderPannel';
Vue.component('loader-pannel', LoaderPannel);

import NavBar from './components/NavBar';
Vue.component('nav-bar', NavBar);

Vue.mixin({
  data: function() {
    return {
      ImageUrl:'http://localhost:8000/uploads/'
    }
  }
})

window.Vue = require('vue');

const app = new Vue({
  el: '#golshahri',
  store,
  vuetify,
  router
});
export default app;