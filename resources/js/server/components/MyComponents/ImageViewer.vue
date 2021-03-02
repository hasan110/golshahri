<template>
<div>
  <div class="preview">
    <div class="icon"><i @click="$emit('closePreview')" class="fa fa-times"></i></div>
    <slot name="image">
      <img id="preview" src="" alt="">
    </slot>
  </div>
</div>
</template>
<script>
export default {
  name:'ImageViewer'
}
</script>
<style scoped>
.preview{
  width: 100vw;
  height: 100vh;
  z-index: 1222;
  background: #000;
  position: fixed;
  top: 0;
  left: 0;
}
.preview img{
  width: 90vw;
  height: 90vh;
  margin: auto;
  display: block;
  object-fit: contain;
}
.preview .icon{
  height: 10vh;
  width: 100%;
  text-align: right;
}
.preview .icon i{
  font-size: 25px;
  color: rgb(250, 43, 43);
  padding: 12px;
  margin-right: 20px;
  cursor: pointer;
}
</style>