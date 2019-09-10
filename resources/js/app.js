try{

    window._ = require('lodash')
    window.jQuery =  window.$ = require('jquery')
    window.popper = require('popper.js').default
    require('bootstrap')
    window.Dropzone = require('dropzone');
    Dropzone.autoDiscover = false
    
}catch(e){}