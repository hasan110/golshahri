<template>
<div>
  <div class="preview">
    <div class="icon"><i @click="$emit('closePreview')" class="fa fa-times"></i></div>
    <div class="next-btn">
      <span @click="plusIndex" class="mover-icon"><i class="fa fa-chevron-right"></i></span>
    </div>
    <div class="prev-btn">
      <span @click="minusIndex" class="mover-icon"><i class="fa fa-chevron-left"></i></span>
    </div>
    <img id="preview" :src="baseUrl+images[index].link" alt="">
  </div>
</div>
</template>
<script>
export default {
  name:"ImageGallery",
  props:{
    images:{required:true},
    index:{required:true},
    baseUrl:{required:true},
    imagesCount:{required:true},
  },
  data(){
    return {
      imageLink:this.images[this.index].link,
    }
  },
  methods: {
    plusIndex(){
      if(this.index < this.imagesCount - 1){
        this.index++;
      }
    },
    minusIndex(){
      if(this.index > 0){
        this.index--;
      }
    }
  }
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
  width: 100vw;
  height: 100vh;
  margin: auto;
  display: block;
  object-fit: contain;
}
.preview .icon{
  position: absolute;
  top: 0;
  right: 0;
  text-align: right;
  z-index: 120;
}
.preview .icon i{
  font-size: 35px;
  color: rgb(250, 43, 43);
  padding: 12px;
  margin-right: 20px;
  cursor: pointer;
}
.next-btn{
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  width: 40px;
  background-color:transparent;
  height: 100vh;
  cursor: pointer;
}
.prev-btn{
  position: fixed;
  top: 0;
  left: 0;
  bottom: 0;
  width: 40px;
  background-color: transparent;
  height: 100vh;
  cursor: pointer;
}
.mover-icon{
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%,-50%);
  font-size: 30px;
}
.mover-icon i{
  color: rgb(211, 211, 211);
}
</style>