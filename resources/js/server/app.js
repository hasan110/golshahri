import Vue from 'vue';

import vuetify from './vuetify'
import router from './Services/Router/Router';

import LoaderPannel from './components/LoaderPannel';
Vue.component('loader-pannel', LoaderPannel);

import VuePersianDatetimePicker from 'vue-persian-datetime-picker';
Vue.use(VuePersianDatetimePicker, {
  name: 'custom-date-picker',
  props: {
    inputFormat: 'YYYY/MM/DD',
    format: 'jYYYY/jMM/jDD',
    editable: false,
    inputClass: 'form-control my-custom-class-name',
    placeholder: 'لطفا تاریخ مورد نظر را انتخاب نمایید',
    altFormat: 'YYYY/MM/DD',
    color: '#00acc1',
    autoSubmit: true
  }
});

Vue.mixin({
  data: function() {
    return {
      ImageUrl:'http://localhost:8000/uploads/'
    }
  }
})

import Vue2Editor from "vue2-editor";
Vue.use(Vue2Editor);

window.Vue = require('vue');

const app = new Vue({
  el: '#arianaapp',
  router,
  vuetify
});
export default app;