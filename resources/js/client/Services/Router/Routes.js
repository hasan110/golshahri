import LoaderPannel from '../../components/LoaderPannel.vue';
import Home from '../../components/Home/Home.vue';

import Login from '../../components/Auth/Login.vue';
import Register from '../../components/Auth/Register.vue';
import Alert from '../../components/Auth/Alert.vue';
import GetCode from '../../components/Auth/GetCode.vue';

import CreateAdvertise from '../../components/Advertise/CreateAdvertise.vue';
import MyAdvertises from '../../components/Advertise/MyAdvertises.vue';
import EditAdvertise from '../../components/Advertise/EditAdvertise.vue';
import Advertise from '../../components/Advertise/Advertise.vue';

import NotFound from '../../components/MyComponents/NotFound.vue';

const allUrl = [
  {
    path: '/',
    component: LoaderPannel,
    redirect: '/Home',
    children: [
      { path: 'Home', name: 'Home', component: Home },

      { path: 'Login', name: 'Login', component: Login },
      { path: 'Register', name: 'Register', component: Register },
      { path: 'GetCode', name: 'GetCode', component: GetCode },
      { path: 'Alert', name: 'Alert', component: Alert },

      { path: 'CreateAdvertise', name: 'CreateAdvertise', component: CreateAdvertise },
      { path: 'MyAdvertises', name: 'MyAdvertises', component: MyAdvertises },
      { path: 'EditAdvertise/:advertise_id', name: 'EditAdvertise', component: EditAdvertise },
      { path: 'Advertise/:advertise_id', name: 'Advertise', component: Advertise },

    ]
  },
  { path: '*', name: 'NotFound', component: NotFound },
];
export default allUrl;