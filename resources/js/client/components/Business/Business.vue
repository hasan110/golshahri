<template>
    <div class="">
        <div class="container" style="margin-top:50px;margin-bottom:50px;">
            <div class="row">
                <div v-if="business.images_count" class="col-md-6 col-sm-12 cleavager">
                    <div class="row">
                        <div class="imagebox col-12">
                            <img @click="galleryImage = true" :src="mainBusinessImage" alt="">
                        </div>
                    </div> 
                    <div class="row mt-2 px-3">
                        <div v-for="(image , key) in business.images" :key="key" class="img-icon pl-0 pr-0">
                            <img @click="showImage(ImageUrl+image.link , key)" :src="ImageUrl+image.link" alt="">
                        </div>
                    </div>       
                </div>
                <div v-else class="col-md-6 col-sm-12 cleavager">
                    <div class="row">
                        <div class="imagebox col-12">
                            <img :src="ImageUrl+business.default_image" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 cleavager mt-3">
                    <div class="row ml-1">
                        <h3>{{ business.title }}</h3>
                    </div>
                    <div class="row ml-1">
                        <small>شماره آگهی : {{ business.id }}</small>
                    </div>
                    
                    <hr class="custom-color">  
                    <div class="row medium-lh ml-1 mt-0">
                        <div class="col-12 ml-0 pl-0">
                            <p class="medium-text">توضیحات تکمیلی </p>
                        </div>
                        <hr>
                        <div class="col-12 ml-0 pl-0">
                            <pre class="medium-text custom">
                                <bdi> {{ business.description }} </bdi>
                            </pre>
                        </div>
                    </div>
                </div>
            </div>
            <br><br>
        </div>

        <image-gallery v-if="business.images_count" @closePreview="galleryImage = false" v-show="galleryImage" :index="index" :images="business.images" :imagesCount="business.images_count" :baseUrl="ImageUrl"></image-gallery>

        <v-snackbar color="red" :timeout="4000" v-model="errorSnackbar">
            <div>{{errorMessage}}</div>
        </v-snackbar>
        
        <div v-if="loading" class="appLoading">
            <div class="circles-wrapper">
                <div></div><div></div><div></div><div></div>
            </div>
        </div>
    </div>
</template>
<script>
import Axios from '../../Services/httpRequest/axios';
import ImageGallery from '../MyComponents/ImageGallery';
export default {
    name:"Business",
    components:{
        ImageGallery
    },
    data(){
        return{
            business:{},
            errorSnackbar:false,
            errorMessage:'',
            loading:true,
            mainBusinessImage:'',
            index:0,
            galleryImage:false
        }
    },
    methods :{
        getBusinessData(business_id){
            Axios.get('businesses/business/'+business_id)
            .then(res => {
                this.business = res.data;
                this.loading = false;
                const link = res.data.images[0].link;
                this.mainBusinessImage = this.ImageUrl+link;
            })
            .catch(err => {
                this.loading = false;
                if(err.response.status == 404){
                    this.$router.push({ name: 'NotFound'});
                }
            });
        },
        showImage(link , key){
            this.mainBusinessImage = link;
            this.index = key;
        },
        previewImage(){
            galleryImage = true
        }
    },
    beforeMount(){
        if(this.$route.params.business_id){
            this.getBusinessData(this.$route.params.business_id)
            window.scrollTo(0, 0);
        }else{
            this.$router.push({ name: 'NotFound'});
        }
    }
}
</script>
<style scoped>

</style>