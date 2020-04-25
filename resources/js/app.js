require('./bootstrap');

window.Vue = require('vue');

const moment = require('moment')
require('moment/locale/ru')

Vue.use(require('vue-moment'), {
    moment
})

Vue.component('page', require('./components/Page').default);
Vue.component('upload', require('./components/Upload').default);
Vue.component('products', require('./components/Products').default);
Vue.component('chart', require('./components/Chart').default);
Vue.component('charts', require('./components/Charts').default);

const app = new Vue({
    el: '#app'
});
