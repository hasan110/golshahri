<template>
    <div class="">
        <div class="container" style="margin-top:50px;margin-bottom:50px;">
            <div class="row">
                <div v-if="advertise.images_count" class="col-md-6 col-sm-12 cleavager">
                    <div class="row">
                        <div class="imagebox col-12">
                            <img @click="galleryImage = true" :src="mainAdvertiseImage" alt="">
                        </div>
                    </div> 
                    <div class="row mt-2 px-3">
                        <div v-for="(image , key) in advertise.images" :key="key" class="img-icon pl-0 pr-0">
                            <img @click="showImage(ImageUrl+image.link , key)" :src="ImageUrl+image.link" alt="">
                        </div>
                    </div>       
                </div>
                <div v-else class="col-md-6 col-sm-12 cleavager ads-default-image">
                    <div class="row">
                        <div class="imagebox col-12">
                            <img :src="ImageUrl+advertise.default_image" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 cleavager mt-3">
                    <div class="row ml-1">
                        <h3>{{ advertise.title }}</h3>
                    </div>
                    <div class="row ml-1">
                        <small>شماره آگهی : {{ advertise.id }}</small>
                    </div>
                    <hr class="custom-color">  
                    <div class="row ml-1 mt-0">
                        <div class="col-1 ml-0 pl-0 text-center"><i class="fa fa-map-marker item-icon"></i></div>
                        <div class="col-11 ml-0 pl-0">
                            <p class="medium-text mr-text" >محدوده : </p>
                            <p class="medium-text">{{ advertise.region.title }} <template v-if="advertise.type == 1 || advertise.type == 2 || advertise.type == 3">- {{ advertise.street }}</template></p>
                        </div>
                    </div>
                    <hr v-show="advertise.price" class="custom-color">  
                    <div v-show="advertise.price" v-if="advertise.type == 1 || advertise.type == 4" class="row ml-1 mt-0">
                        <div class="col-1 ml-0 pl-0 text-center"><i class="fa fa-money item-icon"></i></div>
                        <div class="col-11 ml-0 pl-0">
                            <p class="medium-text mr-text"><template v-if="advertise.type == 4">حدود</template> قیمت : </p>
                            <p class="medium-text">  {{ advertise.pointer_price }} میلیون تومان</p>
                        </div>
                    </div>
                    <div class="row ml-1 mt-0">
                        <div v-if="advertise.type == 2 || advertise.type == 3 || advertise.type == 5" class="col-12 ml-0 pl-0">
                            <p class="medium-text">میزان رهن : </p>
                            <p v-if="advertise.rent > 0" class="medium-text">  {{ advertise.pointer_rent }} تومان</p>
                            <p v-else class="medium-text"><i class="fa fa-times text-danger"></i></p>
                        </div>
                        <div v-if="advertise.type == 3 || advertise.type == 5" class="col-12 ml-0 pl-0">
                            <p class="medium-text">اجاره بها : </p>
                            <p v-if="advertise.meed > 0" class="medium-text">  {{ advertise.pointer_meed }} تومان</p>
                            <p v-else class="medium-text"><i class="fa fa-times text-danger"></i></p>
                        </div>
                    </div>
                    <hr v-if="advertise.status !== 'زمین'" class="custom-color">  
                    <div v-if="advertise.status !== 'زمین'" class="row ml-1 mt-0">
                        <div class="col-1 ml-0 pl-0 text-center"><i class="fa fa-hourglass-start item-icon"></i></div>
                        <div class="col-11 ml-0 pl-0">
                            <p class="medium-text mr-text"> وضعیت عمر : </p>
                            <p class="medium-text">{{ advertise.lifetime_state }}</p>
                        </div>
                    </div>
                    <hr v-if="advertise.area" class="custom-color">
                    <div v-if="advertise.area" class="row ml-1 mt-0">
                        <div class="col-1 ml-0 pl-0 text-center"><i class="fa fa-arrows item-icon"></i></div>
                        <div class="col-11 ml-0 pl-0">
                            <p class="medium-text mr-text"><template v-if="advertise.type == 4 || advertise.type == 5">حدود</template> متراژ : </p>
                            <p class="medium-text"> {{ advertise.area }} متر</p>
                        </div>
                    </div>   
                    <hr v-if="advertise.type == 1 && advertise.length_house !== null && advertise.length_house >= 8" class="custom-color">
                    <div v-if="advertise.type == 1 && advertise.length_house !== null && advertise.length_house >= 8 " class="row medium-lh ml-1 mt-0">
                        <div class="col-1 ml-0 pl-0 text-center"><i class="fa fa-arrows-h item-icon"></i></div>
                        <div class="col-11 ml-0 pl-0">
                            <p class="medium-text mr-text"> حاشیه : </p>
                            <p class="medium-text">{{ advertise.length_house }} متر</p>
                        </div>
                    </div>
                    <hr v-if="advertise.status == 'منزل'" class="custom-color">  
                    <div v-if="advertise.status == 'منزل'" class="row medium-lh ml-1 mt-0">
                        <div class="col-1 ml-0 pl-0 text-center"><i class="fa fa-align-justify item-icon"></i></div>
                        <div class="col-11 ml-0 pl-0">
                            <p class="medium-text mr-text">تعداد طبقات :</p>
                            <p class="medium-text">{{ advertise.roof_number }}</p>
                        </div>
                    </div>
                    <hr class="custom-color">
                    <div class="row medium-lh ml-1 mt-0">
                        <div class="col-1 ml-0 pl-0 text-center"><i class="fa fa-plus item-icon"></i></div>
                        <div class="col-11 ml-0 pl-0">
                            <p class="medium-text">
                            <template v-if="advertise.is_in_lane">داخل کوچه <template v-if="advertise.lane_width !== null && advertise.lane_width > 0">{{advertise.lane_width}} متری</template> -</template>
                            <template v-if="!advertise.is_in_lane && advertise.lane_width !== null && advertise.lane_width >= 10">{{ advertise.lane_width }} متر عرض خیابان -</template>
                            <template v-if="advertise.skeleton_state == 'اسکلت'">تمام اسکلت</template>
                            </p>
                        </div>
                    </div>
                    <hr class="custom-color">  
                    <div class="row medium-lh ml-1 mt-0">
                        <div class="col-12 ml-0 pl-0">
                            <p class="medium-text">توضیحات تکمیلی </p>
                        </div>
                        <hr>
                        <div class="col-12 ml-0 pl-0">
                            <pre class="medium-text custom">
                                <bdi> {{ advertise.description }} </bdi>
                            </pre>
                        </div>
                    </div>
                </div>
            </div>
            <br><br>
        </div>
        <div class="call-btn">
            <a @click="call_number = !call_number" > شماره تماس <i class="fa fa-phone"></i></a>   
        </div>

        <image-gallery v-if="advertise.images_count" @closePreview="galleryImage = false" v-show="galleryImage" :index="index" :images="advertise.images" :imagesCount="advertise.images_count" :baseUrl="ImageUrl"></image-gallery>

        <v-bottom-sheet v-model="call_number">
            <v-sheet
                class="text-center"
                height="200px"
            >
                <v-btn
                class="mt-6"
                text
                color="red"
                @click="call_number = !call_number"
                >
                <i class="fa fa-times"></i>
                </v-btn>
                <div class="py-3">
                شماره تماس : {{ advertise.call_number }}
                </div>
                <a class="btn btn-sm btn-primary" :href="`tel:`+advertise.call_number">تماس</a>
            </v-sheet>
        </v-bottom-sheet>

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
    name:"Advertise",
    components:{
        ImageGallery
    },
    data(){
        return{
            advertise:{},
            errorSnackbar:false,
            errorMessage:'',
            loading:true,
            mainAdvertiseImage:'',
            index:0,
            galleryImage:false,
            call_number:false
        }
    },
    methods :{
        getAdvertiseData(advertise_id){
            Axios.get('advertises/advertise/'+advertise_id)
            .then(res => {
                this.advertise = res.data;
                this.loading = false;
                const link = res.data.images[0].link;
                this.mainAdvertiseImage = this.ImageUrl+link;
            })
            .catch(err => {
                this.loading = false;
                if(err.response.status == 404){
                    this.$router.push({ name: 'NotFound'});
                }
            });
        },
        showImage(link , key){
            this.mainAdvertiseImage = link;
            this.index = key;
        },
        previewImage(){
            galleryImage = true
        }
    },
    beforeMount(){
        if(this.$route.params.advertise_id){
            this.getAdvertiseData(this.$route.params.advertise_id)
            window.scrollTo(0, 0);
        }else{
            this.$router.push({ name: 'NotFound'});
        }
    }
}
</script>
<style scoped>

</style>