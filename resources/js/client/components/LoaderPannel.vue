<template>
  <div>
    <v-app>
      <keep-alive :include="['Businesses', 'Advertises','Home']">
        <router-view></router-view>
      </keep-alive>
    </v-app>
  </div>
</template>
<script>
export default {
  name:'LoaderPannel',
  methods: {
    
  }
}
</script>
<style>
  .v-application {
    font-family: "iranyekan" !important;
  }
  .v-application a{
    color: #fff;
  }
  .v-application .cyan {
    background-color: #00bcd4 !important;
    border-color: #00bcd4 !important;
  }
  .loading{
    width: 100%;
    display: table-caption;
  }
  .icon{
    width:auto;
    height: 30px;
  }
  .v-pagination__item{
    margin: 0 !important;
  }
  .v-pagination__navigation{
    margin: 0 !important;
  }
  .v-pagination{
      margin: 6px 0px;
  }
  .v-input--selection-controls {
    margin-top: 0px !important;
    padding-top: 0px !important;
  }
  .v-input__slot{
    margin-bottom: 0px !important;
  }
  .v-application--is-rtl .v-input--selection-controls__input {
    margin-left: 0px !important;
  }
  .v-messages{
    min-height: 0px !important;
  }
  .pdatepicker{
    width: 100%;
  }
  .pdatepicker input{
    width: 100% !important;
    border-style: solid;
    text-align: right !important;
  }
  .pdatepicker .pdatepicker-dialog{
    width: 280px !important;
  }
  .ql-toolbar.ql-snow .ql-picker.ql-expanded .ql-picker-label {
    border-color: #ccc;
    text-align: left;
  }
  .ql-toolbar.ql-snow .ql-picker-label {
    text-align: left;
    border: 1px solid #ccc;
  }
  .thumbnail-preview{
    width: 50px;
    height: 50px;
  }
  .thumbnail-preview img{
    width: 100%;
    height: 100%;
    object-fit: contain;
  }
  .ql-toolbar.ql-snow + .ql-container.ql-snow {
    font-family: "Sahel" !important;
  }
</style>
