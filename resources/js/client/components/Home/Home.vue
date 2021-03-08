<template>
    <div class="">
        <div class="container" style="margin-top:50px">
            <div class="row">
                <div class="col-md-6 col-sm-12 pb-0">
                    <div class="card text-white bg-primary mb-3" style="">
                        <!-- <div class="card-header"></div> -->
                        <div class="card-body"><h3 class="text-center">آگهی های مسکن</h3>
                            <!-- <p class="card-text">
                                شما می توانید آگهی های مسکن را در این قسمت درج کنید و یا از بین آگهی ها مورد دلخواه خود را پیدا کنید
                                    آگهی ها شامل موارد: خرید ، فروش ، رهن و اجاره منزل و... می باشد.
                            </p> -->
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 pb-0">
                    <div class="card text-white bg-danger mb-3" style="">
                        <!-- <div class="card-header"></div> -->
                        <div class="card-body"><h3 class="text-center">معرفی کسب و کارها</h3>
                            <!-- <p class="card-text">
                                چنانچه کسب وکاری دارید که احتیاج به معرفی و شناخت کاربران دارد در این قسمت میتوانید آن را معرفی کنید.
                                درحال حاضر این امکان برای گلشهر مقدور می باشد.
                    </p> -->
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="row">
                <div class="col-12 " v-html="setting.about_us_text"></div>
            </div>
            <div class="row">
                <div class="social-media">
                    <button class="social-btn button4" 
                        style="float:left;background-color: #e7e7e7; color: black; margin-left:13px;">
                        <img :src="ImageUrl+setting.instagram_image">
                    </button>
                    <button class="social-btn button4" 
                        style="float:left;background-color: #e7e7e7; color: black;">
                        <img :src="ImageUrl+setting.telegram_image">
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Axios from '../../Services/httpRequest/axios';
export default {
    name:"Home",
    data(){
        return {
            setting:{},
        }
    },
    methods:{
        getSettings(){
            Axios.get(`getSettings`)
            .then(res => {
                this.setting = res.data;
            })
            .catch(err => {
                console.log(err)
            });
        }
    },
    watch:{
    },
    beforeMount(){
        this.getSettings();
    },
}
</script>
<style scoped>

</style>