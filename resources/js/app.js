window.Vue = require('vue').default;
window.axios = require('axios');

Vue.component('survey', require('./components/Survey.vue').default);

const app = new Vue({
    el: '#app',
});
