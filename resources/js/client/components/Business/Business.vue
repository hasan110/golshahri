<template>
    <div class="">
        <div class="container" style="margin-top:50px;margin-bottom:50px;">
            <div class="row">
                <div v-if="business.images_count" class="col-md-6 col-sm-12 cleavager  pb-0">
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
                <div v-else class="col-md-6 col-sm-12 cleavager  pb-0">
                    <div class="row">
                        <div class="imagebox col-12 pb-0">
                            <img :src="ImageUrl+business.default_image" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 cleavager mt-1">
                    <div class="row ml-1">
                        <h4>{{ business.title }}</h4>
                    </div>
                    <div class="row ml-1">
                        <small>شماره آگهی : {{ business.id }}</small>
                    </div>
                    
                    <hr class="custom-color  my-2">  
                    <div class="row medium-lh ml-1 mt-0">
                        <hr>
                        <div class="col-12 ml-0 pl-0 py-0">
                            <pre class="medium-text custom">
                                <bdi> {{ business.description }} </bdi>
                            </pre>
                        </div>
                    </div>
                    <hr v-if="business.contact_number" class="custom-color my-2">  
                    <div v-if="business.contact_number" class="row ml-1 mt-0">
                        <div class="col-1 ml-0 pl-0 text-center"><i class="fa fa-phone item-icon"></i></div>
                        <div class="col-11 ml-0 pl-0 pr-2" style="padding-top: 20px;">
                            <a style="color:#212529" :href="'tel:'+business.contact_number" class="medium-text">{{ business.contact_number }}</a>
                        </div>
                    </div>
                    <hr v-if="business.telegram_id" class="custom-color my-2">  
                    <div v-if="business.telegram_id" class="row ml-1 mt-0">
                        <div class="col-1 ml-0 pl-0 text-center"><i class="fa fa-paper-plane item-icon"></i></div>
                        <div class="col-11 ml-0 pl-0 pr-2" style="padding-top: 20px;">
                            <a style="color:#212529" :href="'https://t.me/'+business.telegram_id" class="medium-text">{{ business.telegram_id }}</a>
                        </div>
                    </div>
                    <hr v-if="business.instagram_id" class="custom-color my-2">  
                    <div v-if="business.instagram_id" class="row ml-1 mt-0">
                        <div class="col-1 ml-0 pl-0 text-center"><i class="fa fa-instagram item-icon"></i></div>
                        <div class="col-11 ml-0 pl-0 pr-2" style="padding-top: 20px;">
                            <a style="color:#212529" :href="'https://instagram.com/'+business.instagram_id" class="medium-text">{{ business.instagram_id }}</a>
                        </div>
                    </div>

                    <!-- <div class="row ml-1">
                        <div class="col-6">
                            <h3 @click="vote('like')" class="vote-icon like">
                                <i class="fa fa-thumbs-o-up liked"></i>
                            </h3>
                        </div>
                        <div class="col-6">
                            <h3 @click="vote('dislike')" class="vote-icon dislike">
                                <i class="fa fa-thumbs-o-down disliked"></i>
                            </h3>
                        </div>
                    </div>
                    
                    <div class="row ml-1">
                        <div class="col-12">
                            <div style="font-size:12px; color:rgb(134, 126, 126); ;line-height:1.4"><i class="fa fa-heart"></i> میزان رضایت مندی کاربران: {{business.percent}}%</div>
                            <div  style="font-size:12px; color:rgb(134, 126, 126); ;line-height:1.4"><i class="fa fa-users"></i> میزان مشارکت: {{business.all_votes}} نفر</div>
                        </div>
                        <div class="col-12 pt-0">
                            <progress min="0" max="100" :value="business.percent" style="width:100%"></progress>
                        </div>
                    </div> -->
                </div>
            </div>
            <br><br>
        </div>

        <div class="call-btn">
            <router-link to="/CreateBusiness"> ثبت آگهی <i class="fa fa-plus"></i></router-link>
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

        <v-dialog
        v-model="dialog"
        max-width="290"
        >
        <v-card>
            <v-card-title class="headline">
            {{msgText}}
            </v-card-title>

            <v-card-actions>
            <v-spacer></v-spacer>

            <v-btn
                color="green darken-1"
                text
                @click="dialog = false"
            >
                فهمیدم 
            </v-btn>
            </v-card-actions>
        </v-card>
        </v-dialog>
        
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
            galleryImage:false,
            dialog:false,
            msgText:'',
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
        },
        vote(type){
            const data = {
                'type' : type,
                'business_id' : this.$route.params.business_id,
            };
            Axios.post('businesses/vote',data)
            .then(res => {
                console.log(res)
                if(res.data.message){
                    this.msgText = res.data.message
                    this.dialog = true
                }
                this.getBusinessData(this.$route.params.business_id)
            })
            .catch(err => {
                console.log(err)
                if(err.response.data.message){
                    this.msgText = err.response.data.message
                    this.dialog = true
                }
            });
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