window.Vue = require('vue')

Vue.component('ad', require('./components/AdComponent.vue').default)

window.vm  = new Vue({
    el: '#app',
})