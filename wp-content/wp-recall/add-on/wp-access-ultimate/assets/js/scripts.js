function wau_get_payment_form(tariff_id, e){
    
    if(e && jQuery(e).parents('.preloader-parent')){
        rcl_preloader_show(jQuery(e).parents('.preloader-parent'));
    }

    rcl_ajax({
        data:{
            action: 'wau_ajax_get_payment_form',
            tariff_id: tariff_id
        }
    });
    
}

