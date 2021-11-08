
function generalFunctions() {
    "use strict";
    var _this = this;
    var _gf = generalFunctions.prototype;

    _gf.preloader = function(){
        return bootbox.dialog({
            message: '<p class="text-center mb-0"><i class="fal fa-spin fa-cog"></i> Por favor, espera mientras procesamos su solicitud...</p>',
            centerVertical: true,
            closeButton: false,
            className: "modal-alert",
        });
    };

    _gf.preloaderFinish = function(preloader){
        setTimeout(function(){
            preloader.modal('hide');

        }, 1000);
    };

    _gf.formEventProloader = function(){
        $('#newRegister').click(function () {
            _gf.preloader();
        });
        $('#returnList').click(function () {
            _gf.preloader();
        });
        $('#form_cancel').click(function () {
            _gf.preloader();
            window.location = $('#returnList').attr('href');
        });
        $('a.returnList').click(function (e) {
            e.preventDefault();
            _gf.preloader();
            window.location = $('#returnList').attr('href');
        });
        $("form").submit(function() {
            _gf.preloader();
        });
    };
}
