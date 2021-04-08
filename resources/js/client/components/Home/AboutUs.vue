<template>
    <div class="">
        <div class="container" style="margin-top:50px;padding:30px;">
            <br>
            <h3 class="text-center mb-5">درباره گلشهری</h3>
            <div class="row">
                <br>
                این وبسایت در حوزه آگهی های گلشهر مشهد فعالیت می کند
                که شامل آگهی های مسکن (خرید و فروش - رهن و اجاره) و کسب و کار با موضوعات (خدمات -  سلامت - نیازمندی - هنری - آموزش و ...) می شود .
            </div>
            <div class="row mt-2">
               <h5> اهداف سایت گلشهری : </h5>
            </div>
            <div class="row mt-2">
            ایجاد یک فضای مناسب مبتنی بر تکنولوژی روز برای هماهنگی بین عرضه و تقاضای محصولات و خدمات 
            </div>
            <div class="row mt-2">
            حمایت از کسب و کار های خانگی و ... و ایجاد بازار مناسب برای عرضه محصولات
            </div>
            <div class="row mt-2">
            ایجاد فضای رقابتی بین کسب و کار ها در ارائه خدمات بهتر و قیمت مناسب تر 
            </div>
            <div class="row mt-2">
            پوشش دادن آگهی های مسکن به عنوان نیاز اساسی و دسترسی راحت به گزینه مورد نظر اعم از خرید - فروش و رهن و اجاره 
            </div>
            <h3 class="text-center mb-5 mt-2">ارتباط با ما</h3>
            <div class="row mt-2">
                شماره تماس : {{ setting.default_phone_number }}
            </div>
            <div class="row mt-2">
                تلفن ثابت : 32573865
            </div>
            <div class="row mt-2">
                ساعات پاسخگویی : 8 الی 18 
            </div>
            <div class="row mt-2">
                <a :href="setting.instagram_address" class="social-btn button4">
                    <img height="30px" :src="ImageUrl+setting.instagram_image">
                </a>
                <a :href="setting.telegram_address" style="margin-left:10px;" class="social-btn button4">
                    <img height="30px" :src="ImageUrl+setting.telegram_image">
                </a>
            </div>
        </div>
    </div>
</template>
<script>
import Axios from '../../Services/httpRequest/axios';
export default {
    name:"AboutUs",
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