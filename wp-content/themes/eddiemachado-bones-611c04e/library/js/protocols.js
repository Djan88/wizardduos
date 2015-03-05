var count_animation = 1,
    cur_animation_val = 0,
    phaseOne,
    phaseTwo,
    phaseThree,
    phaseFour,
    phaseSeven_one,
    phaseSeven_two,
    firstTriangleAnimation,
    secondTriangleAnimation,
    thirdTriangleAnimation,
    onEnd,
    protocol,
    v2,
    v3,
    d12Val,
    reloadTime,
    reloadTime1,
    tickSound = new buzz.sound( "/sounds/tick", {
        formats: [ "ogg", "mp3" ]
    }),
    reloadSound = new buzz.sound( "/sounds/reload", {
        formats: [ "ogg", "mp3" ]
    });

onEnd = function(){
    swal({   
        title: "Процедура окончена",   
        text: "Что вы хотите делать дальше?",   
        type: "success",   
        showCancelButton: true,   
        confirmButtonColor: "#DD6B55",   
        confirmButtonText: "Продолжить сессию",   
        cancelButtonText: "Выйти"
    }, 
    function(isConfirm){   
        if (isConfirm) {
            var protocol = undefined;     
            jQuery('.fast-protocol-wrap')
                .removeClass('hidden')
                .addClass('animated')
                .addClass('fadeIn');
        } else {
            var protocol = undefined;    
            jQuery(location).attr('href','/wizard');
        } 
    });
    var endSound = new buzz.sound( "/sounds/duos", {
        formats: [ "ogg", "mp3" ]
    });
    endSound.play();
}

duos = function(){
//фаза 1
    reloadTime = 0;
    cur_animation_val = 0;
    d12Val = 0;
    count_animation = 1;
    phaseOne = setInterval(function(){
        if (count_animation <= 55){                                                                         //55
            tickSound.play();
            jQuery('#draggableD11, #draggableD11_1').css({
                color: 'transparent',
                borderColor: 'transparent',
                opacity: 0.8,
                transform: 'scale(1)',
                borderWidth: '1px',
                paddingTop: '4px',
                background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat',
                zIndex: '1000'
            });
            jQuery('#draggableD12')
                .removeClass('hidden')
                .css({
                    opacity: 0.8,
                    transform: 'scale(1)',
                    background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat',
                    transform: 'rotate(-'+d12Val+'deg)',
                    borderColor: 'transparent'
                });;
            count_animation += 1;
            if(count_animation <= 30){
                cur_animation_val += 6;
                d12Val+= 36;
                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
            } else {
                cur_animation_val -= 6;
                d12Val+= 36;
                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
            }
        } else {
            clearInterval(phaseOne);
            count_animation = 1;
            jQuery('#draggableD11, #draggableD11_1').css({
                background: 'transparent',
                color: 'red',
                borderColor: 'red',
                opacity: 1,
                transform: 'scale(0.5)',
                borderWidth: '1px',
                paddingTop: '9px',
                zIndex: '1'
            });
            // jQuery('#draggableD12').addClass('hidden');
            tickSound.stop();
            phaseTwo = setInterval(function(){
                if (reloadTime <= 1){                                                                       //1
                    tickSound.stop();
                    reloadSound.play();
                    reloadTime += 1;
                } else {
                    clearInterval(phaseTwo);
                    reloadSound.stop();
                    tickSound.play();
                }
            }, 1000);
            jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
            jQuery('#draggableD12').css('transform', 'rotate(0deg)');
//фаза 2
            reloadTime = 0;
            d12Val = 0;
            cur_animation_val = 0;
            count_animation = 1;
            phaseOne = setInterval(function(){
                if (count_animation <= 55){                                                                         //55
                    jQuery('#draggableD1, #draggableD1_1').css({
                        color: 'transparent',
                        borderColor: 'transparent',
                        opacity: 0.8,
                        transform: 'scale(1)',
                        borderWidth: '1px',
                        paddingTop: '4px',
                        background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat',
                        zIndex: '1000'
                    });
                    jQuery('#draggableD12')
                        .removeClass('hidden')
                        .css({
                            opacity: 0.8,
                            transform: 'scale(1)',
                            background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat',
                            transform: 'rotate(-'+d12Val+'deg)',
                            borderColor: 'transparent'
                        });;
                    count_animation += 1;
                    if(count_animation <= 30){
                        cur_animation_val += 6;
                        d12Val+= 36; 
                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                    } else {
                        d12Val+= 36;
                        cur_animation_val -= 6;
                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                        jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                    }
                } else {
                    clearInterval(phaseOne);
                    count_animation = 1;
                    jQuery('#draggableD1, #draggableD1_1').css({
                        background: 'transparent',
                        color: 'red',
                        borderColor: 'red',
                        opacity: 1,
                        transform: 'scale(0.5)',
                        borderWidth: '1px',
                        paddingTop: '5px',
                        zIndex: '1'
                    });
                    // jQuery('#draggableD12').addClass('hidden');
                    tickSound.stop();
                    phaseTwo = setInterval(function(){
                        if (reloadTime <= 1){                                                                       //1
                            tickSound.stop();
                            reloadSound.play();
                            reloadTime += 1;
                        } else {
                            clearInterval(phaseTwo);
                            reloadSound.stop();
                            tickSound.play();
                        }
                    }, 1000);
                    jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                    jQuery('#draggableD12').css('transform', 'rotate(0deg)');
//фаза 3
                    reloadTime = 0;
                    reloadTime1 = 0;
                    d12Val = 0;
                    cur_animation_val = 0;
                    count_animation = 1;
                    phaseOne = setInterval(function(){
                        if (count_animation <= 85){                                                                         //90
                            tickSound.play();
                            jQuery('#draggableD2, #draggableD2_1').css({
                                color: 'transparent',
                                borderColor: 'transparent',
                                opacity: 0.8,
                                transform: 'scale(1)',
                                borderWidth: '1px',
                                paddingTop: '4px',
                                background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/disfunction.jpg) 0 0/100% no-repeat',
                                zIndex: '1000'
                            });
                            jQuery('#draggableD12')
                                .removeClass('hidden')
                                .css({
                                    opacity: 0.8,
                                    transform: 'scale(1)',
                                    background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat',
                                    transform: 'rotate(-'+d12Val+'deg)',
                                    borderColor: 'transparent'
                                });;
                            count_animation += 1;
                            console.log(count_animation);
                            if(count_animation <= 30){
                                cur_animation_val += 6;
                                d12Val+= 36;
                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                            } else if (count_animation >= 30 && count_animation <= 55){
                                cur_animation_val -= 6;
                                d12Val+= 36;
                                jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                            } else if (count_animation >= 55 && count_animation <= 85){
                                cur_animation_val -= 6;
                                d12Val+= 36;
                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                            } else {
                                d12Val+= 36;
                                cur_animation_val -= 6;
                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                            }
                        } else {
                            clearInterval(phaseOne);
                            count_animation = 1;
                            jQuery('#draggableD2, #draggableD2_1').css({
                                background: 'transparent',
                                color: 'red',
                                borderColor: 'red',
                                opacity: 1,
                                transform: 'scale(0.5)',
                                borderWidth: '2px',
                                paddingTop: '5px',
                                zIndex: '1'
                            });
                            // jQuery('#draggableD12').addClass('hidden');
                            tickSound.stop();
                            phaseTwo = setInterval(function(){
                                if (reloadTime <= 1){                                                                       //1
                                    tickSound.stop();
                                    reloadSound.play();
                                    reloadTime += 1;
                                } else {
                                    clearInterval(phaseTwo);
                                    reloadSound.stop();
                                    tickSound.play();
                                }
                            }, 1000);
                            jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                            jQuery('#draggableD12').css('transform', 'rotate(0deg)');
//фаза 4
                            reloadTime = 0;
                            reloadTime1 = 0;
                            d12Val = 0;
                            cur_animation_val = 0;
                            count_animation = 1;
                            phaseOne = setInterval(function(){
                                if (count_animation <= 85){                                                                         //90
                                    tickSound.play();
                                    jQuery('#draggableD22, #draggableD22_1').css({
                                        color: 'transparent',
                                        borderColor: 'transparent',
                                        opacity: 0.8,
                                        transform: 'scale(1)',
                                        borderWidth: '1px',
                                        paddingTop: '4px',
                                        background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/disfunction.jpg) 0 0/100% no-repeat',
                                        zIndex: '1000'
                                    });
                                    jQuery('#draggableD12')
                                        .removeClass('hidden')
                                        .css({
                                            opacity: 0.8,
                                            transform: 'scale(1)',
                                            background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat',
                                            transform: 'rotate(-'+d12Val+'deg)',
                                            borderColor: 'transparent'
                                        });;
                                    count_animation += 1;
                                    if(count_animation <= 30){
                                        cur_animation_val += 6;
                                        d12Val+= 36;
                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                    } else if (count_animation >= 30 && count_animation <= 55){
                                        cur_animation_val -= 6;
                                        d12Val+= 36;
                                        jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                    } else if (count_animation >= 55 && count_animation <= 85){
                                        cur_animation_val -= 6;
                                        d12Val+= 36;
                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                        jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                        jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                    } else {
                                        d12Val+= 36;
                                        cur_animation_val -= 6;
                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                        jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                        jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                    }
                                } else {
                                    clearInterval(phaseOne);
                                    count_animation = 1;
                                    jQuery('#draggableD22, #draggableD22_1').css({
                                        background: 'transparent',
                                        color: 'red',
                                        borderColor: 'red',
                                        opacity: 1,
                                        transform: 'scale(0.5)',
                                        borderWidth: '2px',
                                        paddingTop: '5px',
                                        zIndex: '1'
                                    });
                                    // jQuery('#draggableD12').addClass('hidden');
                                    tickSound.stop();
                                    phaseTwo = setInterval(function(){
                                        if (reloadTime <= 1){                                                                       //1
                                            reloadSound.play();
                                            reloadTime += 1;
                                        } else {
                                            clearInterval(phaseTwo);
                                            reloadSound.stop();
                                        }
                                    }, 1000);
                                    jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                                    jQuery('#draggableD12').css('transform', 'rotate(0deg)');
//фаза 5
                                    reloadTime = 0;
                                    reloadTime1 = 0;
                                    d12Val = 0;
                                    cur_animation_val = 0;
                                    count_animation = 1;
                                    phaseOne = setInterval(function(){
                                        if (count_animation <= 85){                                                                         //90
                                            tickSound.play();
                                            jQuery('#draggableD3, #draggableD3_1').css({
                                                color: 'transparent',
                                                borderColor: 'transparent',
                                                opacity: 0.8,
                                                transform: 'scale(1)',
                                                borderWidth: '1px',
                                                paddingTop: '4px',
                                                background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/disfunction.jpg) 0 0/100% no-repeat',
                                                zIndex: '1000'
                                            });
                                            jQuery('#draggableD12')
                                                .removeClass('hidden')
                                                .css({
                                                    opacity: 0.8,
                                                    transform: 'scale(1)',
                                                    background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat',
                                                    transform: 'rotate(-'+d12Val+'deg)',
                                                    borderColor: 'transparent'
                                                });;
                                            count_animation += 1;
                                            if(count_animation <= 30){
                                                cur_animation_val += 6;
                                                d12Val+= 36;
                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                            } else if (count_animation >= 30 && count_animation <= 55){
                                                cur_animation_val -= 6;
                                                d12Val+= 36;
                                                jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                            } else if (count_animation >= 55 && count_animation <= 85){
                                                cur_animation_val -= 6;
                                                d12Val+= 36;
                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                            } else {
                                                d12Val+= 36;
                                                cur_animation_val -= 6;
                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                            }
                                        } else {
                                            clearInterval(phaseOne);
                                            count_animation = 1;
                                            jQuery('#draggableD3, #draggableD3_1').css({
                                                background: 'transparent',
                                                color: 'red',
                                                borderColor: 'red',
                                                opacity: 1,
                                                transform: 'scale(0.5)',
                                                borderWidth: '2px',
                                                paddingTop: '5px',
                                                zIndex: '1'
                                            });
                                            // jQuery('#draggableD12').addClass('hidden');
                                            tickSound.stop();
                                            phaseTwo = setInterval(function(){
                                                if (reloadTime <= 1){                                                                       //1
                                                    reloadSound.play();
                                                    reloadTime += 1;
                                                } else {
                                                    clearInterval(phaseTwo);
                                                    reloadSound.stop();
                                                }
                                            }, 1000);
                                            jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                                            jQuery('#draggableD12').css('transform', 'rotate(0deg)');
//фаза 6
                                            reloadTime = 0;
                                            reloadTime1 = 0;
                                            d12Val = 0;
                                            cur_animation_val = 0;
                                            count_animation = 1;
                                            phaseOne = setInterval(function(){
                                                if (count_animation <= 85){                                                                         //90
                                                    tickSound.play();
                                                    jQuery('#draggableD4, #draggableD4_1').css({
                                                        color: 'transparent',
                                                        borderColor: 'transparent',
                                                        opacity: 0.8,
                                                        transform: 'scale(1)',
                                                        borderWidth: '1px',
                                                        paddingTop: '4px',
                                                        background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/disfunction.jpg) 0 0/100% no-repeat',
                                                        zIndex: '1000'
                                                    });
                                                    jQuery('#draggableD12')
                                                        .removeClass('hidden')
                                                        .css({
                                                            opacity: 0.8,
                                                            transform: 'scale(1)',
                                                            background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat',
                                                            transform: 'rotate(-'+d12Val+'deg)',
                                                            borderColor: 'transparent'
                                                        });;
                                                    count_animation += 1;
                                                    if(count_animation <= 30){
                                                        cur_animation_val += 6;
                                                        d12Val+= 36;
                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                    } else if (count_animation >= 30 && count_animation <= 55){
                                                        cur_animation_val -= 6;
                                                        d12Val+= 36;
                                                        jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                    } else if (count_animation >= 55 && count_animation <= 85){
                                                        cur_animation_val -= 6;
                                                        d12Val+= 36;
                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                        jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                        jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                                    } else {
                                                        d12Val+= 36;
                                                        cur_animation_val -= 6;
                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                        jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                        jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                                    }
                                                } else {
                                                    clearInterval(phaseOne);
                                                    count_animation = 1;
                                                    jQuery('#draggableD4, #draggableD4_1').css({
                                                        background: 'transparent',
                                                        color: 'red',
                                                        borderColor: 'red',
                                                        opacity: 1,
                                                        transform: 'scale(0.5)',
                                                        borderWidth: '2px',
                                                        paddingTop: '5px',
                                                        zIndex: '1'
                                                    });
                                                    // jQuery('#draggableD12').addClass('hidden');
                                                    tickSound.stop();
                                                    phaseTwo = setInterval(function(){
                                                        if (reloadTime <= 1){                                                                       //1
                                                            reloadSound.play();
                                                            reloadTime += 1;
                                                        } else {
                                                            clearInterval(phaseTwo);
                                                            reloadSound.stop();
                                                        }
                                                    }, 1000);
                                                    jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                                                    jQuery('#draggableD12').css('transform', 'rotate(0deg)');
//фаза 7
                                                    reloadTime = 0;
                                                    reloadTime1 = 0;
                                                    d12Val = 0;
                                                    cur_animation_val = 0;
                                                    count_animation = 1;
                                                    phaseOne = setInterval(function(){
                                                        if (count_animation <= 85){                                                                         //90
                                                            tickSound.play();
                                                            jQuery('#draggableD5, #draggableD5_1').css({
                                                                color: 'transparent',
                                                                borderColor: 'transparent',
                                                                opacity: 0.8,
                                                                transform: 'scale(1)',
                                                                borderWidth: '1px',
                                                                paddingTop: '4px',
                                                                background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/disfunction.jpg) 0 0/100% no-repeat',
                                                                zIndex: '1000'
                                                            });
                                                            jQuery('#draggableD12')
                                                                .removeClass('hidden')
                                                                .css({
                                                                    opacity: 0.8,
                                                                    transform: 'scale(1)',
                                                                    background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat',
                                                                    transform: 'rotate(-'+d12Val+'deg)',
                                                                    borderColor: 'transparent'
                                                                });;
                                                            count_animation += 1;
                                                            if(count_animation <= 30){
                                                                cur_animation_val += 6;
                                                                d12Val+= 36;
                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                            } else if (count_animation >= 30 && count_animation <= 55){
                                                                cur_animation_val -= 6;
                                                                d12Val+= 36;
                                                                jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                            } else if (count_animation >= 55 && count_animation <= 85){
                                                                cur_animation_val -= 6;
                                                                d12Val+= 36;
                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                                jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                                            } else {
                                                                d12Val+= 36;
                                                                cur_animation_val -= 6;
                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                                jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                                            }
                                                        } else {
                                                            clearInterval(phaseOne);
                                                            count_animation = 1;
                                                            jQuery('#draggableD5, #draggableD5_1').css({
                                                                background: 'transparent',
                                                                color: 'red',
                                                                borderColor: 'red',
                                                                opacity: 1,
                                                                transform: 'scale(0.5)',
                                                                borderWidth: '2px',
                                                                paddingTop: '5px',
                                                                zIndex: '1'
                                                            });
                                                            // jQuery('#draggableD12').addClass('hidden');
                                                            tickSound.stop();
                                                            phaseTwo = setInterval(function(){
                                                                if (reloadTime <= 1){                                                                       //1
                                                                    reloadSound.play();
                                                                    reloadTime += 1;
                                                                } else {
                                                                    clearInterval(phaseTwo);
                                                                    reloadSound.stop();
                                                                }
                                                            }, 1000);
                                                            jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                                                            jQuery('#draggableD12').css('transform', 'rotate(0deg)');
//фаза 8
                                                            reloadTime = 0;
                                                            reloadTime1 = 0;
                                                            d12Val = 0;
                                                            cur_animation_val = 0;
                                                            count_animation = 1;
                                                            phaseOne = setInterval(function(){
                                                                if (count_animation <= 85){                                                                         //90
                                                                    tickSound.play();
                                                                    jQuery('#draggableD6, #draggableD6_1').css({
                                                                        color: 'transparent',
                                                                        borderColor: 'transparent',
                                                                        opacity: 0.8,
                                                                        transform: 'scale(1)',
                                                                        borderWidth: '1px',
                                                                        paddingTop: '4px',
                                                                        background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/disfunction.jpg) 0 0/100% no-repeat',
                                                                        zIndex: '1000'
                                                                    });
                                                                    jQuery('#draggableD12')
                                                                        .removeClass('hidden')
                                                                        .css({
                                                                            opacity: 0.8,
                                                                            transform: 'scale(1)',
                                                                            background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat',
                                                                            transform: 'rotate(-'+d12Val+'deg)',
                                                                            borderColor: 'transparent'
                                                                        });;
                                                                    count_animation += 1;
                                                                    if(count_animation <= 30){
                                                                        cur_animation_val += 6;
                                                                        d12Val+= 36;
                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                    } else if (count_animation >= 30 && count_animation <= 55){
                                                                        cur_animation_val -= 6;
                                                                        d12Val+= 36;
                                                                        jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                    } else if (count_animation >= 55 && count_animation <= 85){
                                                                        cur_animation_val -= 6;
                                                                        d12Val+= 36;
                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                        jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                                        jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                                                    } else {
                                                                        d12Val+= 36;
                                                                        cur_animation_val -= 6;
                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                        jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                                        jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                                                    }
                                                                } else {
                                                                    clearInterval(phaseOne);
                                                                    count_animation = 1;
                                                                    jQuery('#draggableD6, #draggableD6_1').css({
                                                                        background: 'transparent',
                                                                        color: 'red',
                                                                        borderColor: 'red',
                                                                        opacity: 1,
                                                                        transform: 'scale(0.5)',
                                                                        borderWidth: '2px',
                                                                        paddingTop: '5px',
                                                                        zIndex: '1'
                                                                    });
                                                                    // jQuery('#draggableD12').addClass('hidden');
                                                                    tickSound.stop();
                                                                    phaseTwo = setInterval(function(){
                                                                        if (reloadTime <= 1){                                                                       //1
                                                                            reloadSound.play();
                                                                            reloadTime += 1;
                                                                        } else {
                                                                            clearInterval(phaseTwo);
                                                                            reloadSound.stop();
                                                                        }
                                                                    }, 1000);
                                                                    jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                                                                    jQuery('#draggableD12').css('transform', 'rotate(0deg)');
//фаза 9
                                                                    reloadTime = 0;
                                                                    reloadTime1 = 0;
                                                                    d12Val = 0;
                                                                    cur_animation_val = 0;
                                                                    count_animation = 1;
                                                                    phaseOne = setInterval(function(){
                                                                        if (count_animation <= 85){                                                                         //90
                                                                            tickSound.play();
                                                                            jQuery('#draggableD7, #draggableD7_1').css({
                                                                                color: 'transparent',
                                                                                borderColor: 'transparent',
                                                                                opacity: 0.8,
                                                                                transform: 'scale(1)',
                                                                                borderWidth: '1px',
                                                                                paddingTop: '4px',
                                                                                background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/disfunction.jpg) 0 0/100% no-repeat',
                                                                                zIndex: '1000'
                                                                            });
                                                                            jQuery('#draggableD12')
                                                                                .removeClass('hidden')
                                                                                .css({
                                                                                    opacity: 0.8,
                                                                                    transform: 'scale(1)',
                                                                                    background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat',
                                                                                    transform: 'rotate(-'+d12Val+'deg)',
                                                                                    borderColor: 'transparent'
                                                                                });;
                                                                            count_animation += 1;
                                                                            if(count_animation <= 30){
                                                                                cur_animation_val += 6;
                                                                                d12Val+= 36;
                                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                            } else if (count_animation >= 30 && count_animation <= 55){
                                                                                cur_animation_val -= 6;
                                                                                d12Val+= 36;
                                                                                jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                            } else if (count_animation >= 55 && count_animation <= 85){
                                                                                cur_animation_val -= 6;
                                                                                d12Val+= 36;
                                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                                                jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                                                            } else {
                                                                                d12Val+= 36;
                                                                                cur_animation_val -= 6;
                                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                                                jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                                                            }
                                                                        } else {
                                                                            clearInterval(phaseOne);
                                                                            count_animation = 1;
                                                                            jQuery('#draggableD7, #draggableD7_1').css({
                                                                                background: 'transparent',
                                                                                color: 'red',
                                                                                borderColor: 'red',
                                                                                opacity: 1,
                                                                                transform: 'scale(0.5)',
                                                                                borderWidth: '2px',
                                                                                paddingTop: '5px',
                                                                                zIndex: '1'
                                                                            });
                                                                            // jQuery('#draggableD12').addClass('hidden');
                                                                            tickSound.stop();
                                                                            phaseTwo = setInterval(function(){
                                                                                if (reloadTime <= 1){                                                                       //1
                                                                                    reloadSound.play();
                                                                                    reloadTime += 1;
                                                                                } else {
                                                                                    clearInterval(phaseTwo);
                                                                                    reloadSound.stop();
                                                                                }
                                                                            }, 1000);
                                                                            jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                                                                            jQuery('#draggableD12').css('transform', 'rotate(0deg)');
//фаза 10
                                                                            reloadTime = 0;
                                                                            reloadTime1 = 0;
                                                                            d12Val = 0;
                                                                            cur_animation_val = 0;
                                                                            count_animation = 1;
                                                                            phaseOne = setInterval(function(){
                                                                                if (count_animation <= 85){                                                                         //90
                                                                                    tickSound.play();
                                                                                    jQuery('#draggable6, #draggable6_1').css({
                                                                                        color: 'transparent',
                                                                                        borderColor: 'transparent',
                                                                                        opacity: 0.8,
                                                                                        transform: 'scale(1)',
                                                                                        borderWidth: '1px',
                                                                                        paddingTop: '4px',
                                                                                        background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/povregdenie_demona.jpg) 0 0/100% no-repeat',
                                                                                        zIndex: '1000'
                                                                                    });
                                                                                    jQuery('#draggableD12')
                                                                                        .removeClass('hidden')
                                                                                        .css({
                                                                                            opacity: 0.8,
                                                                                            transform: 'scale(1)',
                                                                                            background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat',
                                                                                            transform: 'rotate(-'+d12Val+'deg)',
                                                                                            borderColor: 'transparent'
                                                                                        });;
                                                                                    count_animation += 1;
                                                                                    if(count_animation <= 30){
                                                                                        cur_animation_val += 6;
                                                                                        d12Val+= 36;
                                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                    } else if (count_animation >= 30 && count_animation <= 55){
                                                                                        cur_animation_val -= 6;
                                                                                        d12Val+= 36;
                                                                                        jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                    } else if (count_animation >= 55 && count_animation <= 85){
                                                                                        cur_animation_val -= 6;
                                                                                        d12Val+= 36;
                                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                        jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                                                        jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                                                                    } else {
                                                                                        d12Val+= 36;
                                                                                        cur_animation_val -= 6;
                                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                        jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                                                        jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                                                                    }
                                                                                } else {
                                                                                    clearInterval(phaseOne);
                                                                                    count_animation = 1;
                                                                                    jQuery('#draggable6, #draggable6_1').css({
                                                                                        background: 'transparent',
                                                                                        color: 'red',
                                                                                        borderColor: 'red',
                                                                                        opacity: 1,
                                                                                        transform: 'scale(0.5)',
                                                                                        borderWidth: '2px',
                                                                                        paddingTop: '5px',
                                                                                        zIndex: '1'
                                                                                    });
                                                                                    // jQuery('#draggableD12').addClass('hidden');
                                                                                    tickSound.stop();
                                                                                    phaseTwo = setInterval(function(){
                                                                                        if (reloadTime <= 1){                                                                       //1
                                                                                            reloadSound.play();
                                                                                            reloadTime += 1;
                                                                                        } else {
                                                                                            clearInterval(phaseTwo);
                                                                                            reloadSound.stop();
                                                                                        }
                                                                                    }, 1000);
                                                                                    jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                                                                                    jQuery('#draggableD12').css('transform', 'rotate(0deg)');
//фаза 11
                                                                                    reloadTime = 0;
                                                                                    reloadTime1 = 0;
                                                                                    d12Val = 0;
                                                                                    cur_animation_val = 0;
                                                                                    count_animation = 1;
                                                                                    phaseOne = setInterval(function(){
                                                                                        if (count_animation <= 85){                                                                         //90
                                                                                            tickSound.play();
                                                                                            jQuery('#draggable5, #draggable5_1').css({
                                                                                                color: 'transparent',
                                                                                                borderColor: 'transparent',
                                                                                                opacity: 0.8,
                                                                                                transform: 'scale(1)',
                                                                                                borderWidth: '1px',
                                                                                                paddingTop: '4px',
                                                                                                background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/disfunction.jpg) 0 0/100% no-repeat',
                                                                                                zIndex: '1000'
                                                                                            });
                                                                                            jQuery('#draggableD12')
                                                                                                .removeClass('hidden')
                                                                                                .css({
                                                                                                    opacity: 0.8,
                                                                                                    transform: 'scale(1)',
                                                                                                    background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat',
                                                                                                    transform: 'rotate(-'+d12Val+'deg)',
                                                                                                    borderColor: 'transparent'
                                                                                                });;
                                                                                            count_animation += 1;
                                                                                            if(count_animation <= 30){
                                                                                                cur_animation_val += 6;
                                                                                                d12Val+= 36;
                                                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                            } else if (count_animation >= 30 && count_animation <= 55){
                                                                                                cur_animation_val -= 6;
                                                                                                d12Val+= 36;
                                                                                                jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                            } else if (count_animation >= 55 && count_animation <= 85){
                                                                                                cur_animation_val -= 6;
                                                                                                d12Val+= 36;
                                                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                                                                jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                                                                            } else {
                                                                                                d12Val+= 36;
                                                                                                cur_animation_val -= 6;
                                                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                                                                jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                                                                            }
                                                                                        } else {
                                                                                            clearInterval(phaseOne);
                                                                                            count_animation = 1;
                                                                                            jQuery('#draggable5, #draggable5_1').css({
                                                                                                background: 'transparent',
                                                                                                color: 'red',
                                                                                                borderColor: 'red',
                                                                                                opacity: 1,
                                                                                                transform: 'scale(0.5)',
                                                                                                borderWidth: '2px',
                                                                                                paddingTop: '5px',
                                                                                                zIndex: '1'
                                                                                            });
                                                                                            // jQuery('#draggableD12').addClass('hidden');
                                                                                            tickSound.stop();
                                                                                            phaseTwo = setInterval(function(){
                                                                                                if (reloadTime <= 1){                                                                       //1
                                                                                                    reloadSound.play();
                                                                                                    reloadTime += 1;
                                                                                                } else {
                                                                                                    clearInterval(phaseTwo);
                                                                                                    reloadSound.stop();
                                                                                                }
                                                                                            }, 1000);
                                                                                            jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                                                                                            jQuery('#draggableD12').css('transform', 'rotate(0deg)');
//фаза 12
                                                                                            reloadTime = 0;
                                                                                            reloadTime1 = 0;
                                                                                            d12Val = 0;
                                                                                            cur_animation_val = 0;
                                                                                            count_animation = 1;
                                                                                            phaseOne = setInterval(function(){
                                                                                                if (count_animation <= 85){                                                                         //90
                                                                                                    tickSound.play();
                                                                                                    jQuery('#draggable4, #draggable4_1').css({
                                                                                                        color: 'transparent',
                                                                                                        borderColor: 'transparent',
                                                                                                        opacity: 0.8,
                                                                                                        transform: 'scale(1)',
                                                                                                        borderWidth: '1px',
                                                                                                        paddingTop: '4px',
                                                                                                        background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/disfunction.jpg) 0 0/100% no-repeat',
                                                                                                        zIndex: '1000'
                                                                                                    });
                                                                                                    jQuery('#draggableD12')
                                                                                                        .removeClass('hidden')
                                                                                                        .css({
                                                                                                            opacity: 0.8,
                                                                                                            transform: 'scale(1)',
                                                                                                            background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat',
                                                                                                            transform: 'rotate(-'+d12Val+'deg)',
                                                                                                            borderColor: 'transparent'
                                                                                                        });;
                                                                                                    count_animation += 1;
                                                                                                    if(count_animation <= 30){
                                                                                                        cur_animation_val += 6;
                                                                                                        d12Val+= 36;
                                                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                    } else if (count_animation >= 30 && count_animation <= 55){
                                                                                                        cur_animation_val -= 6;
                                                                                                        d12Val+= 36;
                                                                                                        jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                    } else if (count_animation >= 55 && count_animation <= 85){
                                                                                                        cur_animation_val -= 6;
                                                                                                        d12Val+= 36;
                                                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                        jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                                                                        jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                                                                                    } else {
                                                                                                        d12Val+= 36;
                                                                                                        cur_animation_val -= 6;
                                                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                        jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                                                                        jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                                                                                    }
                                                                                                } else {
                                                                                                    clearInterval(phaseOne);
                                                                                                    count_animation = 1;
                                                                                                    jQuery('#draggable4, #draggable4_1').css({
                                                                                                        background: 'transparent',
                                                                                                        color: 'red',
                                                                                                        borderColor: 'red',
                                                                                                        opacity: 1,
                                                                                                        transform: 'scale(0.5)',
                                                                                                        borderWidth: '2px',
                                                                                                        paddingTop: '5px',
                                                                                                        zIndex: '1'
                                                                                                    });
                                                                                                    // jQuery('#draggableD12').addClass('hidden');
                                                                                                    tickSound.stop();
                                                                                                    phaseTwo = setInterval(function(){
                                                                                                        if (reloadTime <= 1){                                                                       //1
                                                                                                            reloadSound.play();
                                                                                                            reloadTime += 1;
                                                                                                        } else {
                                                                                                            clearInterval(phaseTwo);
                                                                                                            reloadSound.stop();
                                                                                                        }
                                                                                                    }, 1000);
                                                                                                    jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                                                                                                    jQuery('#draggableD12').css('transform', 'rotate(0deg)');
//фаза 13
                                                                                                    reloadTime = 0;
                                                                                                    reloadTime1 = 0;
                                                                                                    d12Val = 0;
                                                                                                    cur_animation_val = 0;
                                                                                                    count_animation = 1;
                                                                                                    phaseOne = setInterval(function(){
                                                                                                        if (count_animation <= 85){                                                                         //90
                                                                                                            tickSound.play();
                                                                                                            jQuery('#draggable3, #draggable3_1').css({
                                                                                                                color: 'transparent',
                                                                                                                borderColor: 'transparent',
                                                                                                                opacity: 0.8,
                                                                                                                transform: 'scale(1)',
                                                                                                                borderWidth: '1px',
                                                                                                                paddingTop: '4px',
                                                                                                                background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/disfunction.jpg) 0 0/100% no-repeat',
                                                                                                                zIndex: '1000'
                                                                                                            });
                                                                                                            jQuery('#draggableD12')
                                                                                                                .removeClass('hidden')
                                                                                                                .css({
                                                                                                                    opacity: 0.8,
                                                                                                                    transform: 'scale(1)',
                                                                                                                    background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat',
                                                                                                                    transform: 'rotate(-'+d12Val+'deg)',
                                                                                                                    borderColor: 'transparent'
                                                                                                                });;
                                                                                                            count_animation += 1;
                                                                                                            if(count_animation >= 45){
                                                                                                                jQuery('#draggable3, #draggable3_1, #draggable0, #draggable0_1').css({
                                                                                                                    background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/edinenie_s_tvorcom.jpg) 0 0/100% no-repeat',
                                                                                                                    color: 'transparent',
                                                                                                                    borderColor: 'transparent',
                                                                                                                    opacity: 0.8,
                                                                                                                    transform: 'scale(1)',
                                                                                                                    borderWidth: '1px',
                                                                                                                    paddingTop: '4px',
                                                                                                                    zIndex: '1000'
                                                                                                                });
                                                                                                            }
                                                                                                            if(count_animation <= 30){
                                                                                                                cur_animation_val += 6;
                                                                                                                d12Val+= 36;
                                                                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                            } else if (count_animation >= 30 && count_animation <= 55){
                                                                                                                cur_animation_val -= 6;
                                                                                                                d12Val+= 36;
                                                                                                                jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                                                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                            } else if (count_animation >= 55 && count_animation <= 85){
                                                                                                                cur_animation_val -= 6;
                                                                                                                d12Val+= 36;
                                                                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                                jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                                                                                jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                                                                                            } else {
                                                                                                                d12Val+= 36;
                                                                                                                cur_animation_val -= 6;
                                                                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                                jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                                                                                jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                                                                                            }
                                                                                                        } else {
                                                                                                            clearInterval(phaseOne);
                                                                                                            count_animation = 1;
                                                                                                            jQuery('#draggable3, #draggable3_1, #draggable0, #draggable0_1').css({
                                                                                                                background: 'transparent',
                                                                                                                color: 'red',
                                                                                                                borderColor: 'red',
                                                                                                                opacity: 1,
                                                                                                                transform: 'scale(0.5)',
                                                                                                                borderWidth: '2px',
                                                                                                                paddingTop: '5px',
                                                                                                                zIndex: '1'
                                                                                                            });
                                                                                                            // jQuery('#draggableD12').addClass('hidden');
                                                                                                            tickSound.stop();
                                                                                                            phaseTwo = setInterval(function(){
                                                                                                                if (reloadTime <= 1){                                                                       //1
                                                                                                                    reloadSound.play();
                                                                                                                    reloadTime += 1;
                                                                                                                } else {
                                                                                                                    clearInterval(phaseTwo);
                                                                                                                    reloadSound.stop();
                                                                                                                }
                                                                                                            }, 1000);
                                                                                                            jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                                                                                                            jQuery('#draggableD12').css('transform', 'rotate(0deg)');
//фаза 14
                                                                                                            reloadTime = 0;
                                                                                                            reloadTime1 = 0;
                                                                                                            d12Val = 0;
                                                                                                            cur_animation_val = 0;
                                                                                                            count_animation = 1;
                                                                                                            phaseOne = setInterval(function(){
                                                                                                                if (count_animation <= 85){                                                                         //90
                                                                                                                    tickSound.play();
                                                                                                                    jQuery('#draggable2, #draggable2_1').css({
                                                                                                                        color: 'transparent',
                                                                                                                        borderColor: 'transparent',
                                                                                                                        opacity: 0.8,
                                                                                                                        transform: 'scale(1)',
                                                                                                                        borderWidth: '1px',
                                                                                                                        paddingTop: '4px',
                                                                                                                        background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/disfunction.jpg) 0 0/100% no-repeat',
                                                                                                                        zIndex: '1000'
                                                                                                                    });
                                                                                                                    jQuery('#draggableD12')
                                                                                                                        .removeClass('hidden')
                                                                                                                        .css({
                                                                                                                            opacity: 0.8,
                                                                                                                            transform: 'scale(1)',
                                                                                                                            background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat',
                                                                                                                            transform: 'rotate(-'+d12Val+'deg)',
                                                                                                                            borderColor: 'transparent'
                                                                                                                        });;
                                                                                                                    count_animation += 1;
                                                                                                                    if(count_animation >= 45){
                                                                                                                        jQuery('#draggable2, #draggable2_1, #draggable0, #draggable0_1').css({
                                                                                                                            background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/edinenie_s_tvorcom.jpg) 0 0/100% no-repeat',
                                                                                                                            color: 'transparent',
                                                                                                                            borderColor: 'transparent',
                                                                                                                            opacity: 0.8,
                                                                                                                            transform: 'scale(1)',
                                                                                                                            borderWidth: '1px',
                                                                                                                            paddingTop: '4px',
                                                                                                                            zIndex: '1000'
                                                                                                                        });
                                                                                                                    }
                                                                                                                    if(count_animation <= 30){
                                                                                                                        cur_animation_val += 6;
                                                                                                                        d12Val+= 36;
                                                                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                                    } else if (count_animation >= 30 && count_animation <= 55){
                                                                                                                        cur_animation_val -= 6;
                                                                                                                        d12Val+= 36;
                                                                                                                        jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                                                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                                    } else if (count_animation >= 55 && count_animation <= 85){
                                                                                                                        cur_animation_val -= 6;
                                                                                                                        d12Val+= 36;
                                                                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                                        jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                                                                                        jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                                                                                                    } else {
                                                                                                                        d12Val+= 36;
                                                                                                                        cur_animation_val -= 6;
                                                                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                                        jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                                                                                        jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    clearInterval(phaseOne);
                                                                                                                    count_animation = 1;
                                                                                                                    jQuery('#draggable2, #draggable2_1, #draggable0, #draggable0_1').css({
                                                                                                                        background: 'transparent',
                                                                                                                        color: 'red',
                                                                                                                        borderColor: 'red',
                                                                                                                        opacity: 1,
                                                                                                                        transform: 'scale(0.5)',
                                                                                                                        borderWidth: '2px',
                                                                                                                        paddingTop: '5px',
                                                                                                                        zIndex: '1'
                                                                                                                    });
                                                                                                                    // jQuery('#draggableD12').addClass('hidden');
                                                                                                                    tickSound.stop();
                                                                                                                    phaseTwo = setInterval(function(){
                                                                                                                        if (reloadTime <= 1){                                                                       //1
                                                                                                                            reloadSound.play();
                                                                                                                            reloadTime += 1;
                                                                                                                        } else {
                                                                                                                            clearInterval(phaseTwo);
                                                                                                                            reloadSound.stop();
                                                                                                                        }
                                                                                                                    }, 1000);
                                                                                                                    jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                                                                                                                    jQuery('#draggableD12').css('transform', 'rotate(0deg)');
//фаза 15
                                                                                                                    reloadTime = 0;
                                                                                                                    reloadTime1 = 0;
                                                                                                                    d12Val = 0;
                                                                                                                    cur_animation_val = 0;
                                                                                                                    count_animation = 1;
                                                                                                                    phaseOne = setInterval(function(){
                                                                                                                        if (count_animation <= 85){                                                                         //90
                                                                                                                            tickSound.play();
                                                                                                                            jQuery('#draggable1, #draggable1_1').css({
                                                                                                                                color: 'transparent',
                                                                                                                                borderColor: 'transparent',
                                                                                                                                opacity: 0.8,
                                                                                                                                transform: 'scale(1)',
                                                                                                                                borderWidth: '1px',
                                                                                                                                paddingTop: '4px',
                                                                                                                                background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/disfunction.jpg) 0 0/100% no-repeat',
                                                                                                                                zIndex: '1000'
                                                                                                                            });
                                                                                                                            jQuery('#draggableD12')
                                                                                                                                .removeClass('hidden')
                                                                                                                                .css({
                                                                                                                                    opacity: 0.8,
                                                                                                                                    transform: 'scale(1)',
                                                                                                                                    background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat',
                                                                                                                                    transform: 'rotate(-'+d12Val+'deg)',
                                                                                                                                    borderColor: 'transparent'
                                                                                                                                });;
                                                                                                                            count_animation += 1;
                                                                                                                            if(count_animation <= 30){
                                                                                                                                cur_animation_val += 6;
                                                                                                                                d12Val+= 36;
                                                                                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                                            } else if (count_animation >= 30 && count_animation <= 55){
                                                                                                                                cur_animation_val -= 6;
                                                                                                                                d12Val+= 36;
                                                                                                                                jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                                                                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                                            } else if (count_animation >= 55 && count_animation <= 85){
                                                                                                                                cur_animation_val -= 6;
                                                                                                                                d12Val+= 36;
                                                                                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                                                jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                                                                                                jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                                                                                                            } else {
                                                                                                                                d12Val+= 36;
                                                                                                                                cur_animation_val -= 6;
                                                                                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                                                jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                                                                                                jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                                                                                                            }
                                                                                                                        } else {
                                                                                                                            clearInterval(phaseOne);
                                                                                                                            count_animation = 1;
                                                                                                                            jQuery('#draggable1, #draggable1_1').css({
                                                                                                                                background: 'transparent',
                                                                                                                                color: 'red',
                                                                                                                                borderColor: 'red',
                                                                                                                                opacity: 1,
                                                                                                                                transform: 'scale(0.5)',
                                                                                                                                borderWidth: '2px',
                                                                                                                                paddingTop: '5px',
                                                                                                                                zIndex: '1'
                                                                                                                            });
                                                                                                                            tickSound.stop();
                                                                                                                            jQuery('#draggableD12').addClass('hidden');
                                                                                                                            jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                                                                                                                            jQuery('#draggableD12').css('transform', 'rotate(0deg)');
                                                                                                                            onEnd();
                                                                                                                        }
                                                                                                                    }, 1000);
                                                                                                                }
                                                                                                            }, 1000);
                                                                                                        }
                                                                                                    }, 1000);
                                                                                                }
                                                                                            }, 1000);
                                                                                        }
                                                                                    }, 1000);
                                                                                }
                                                                            }, 1000);
                                                                        }
                                                                    }, 1000);
                                                                }
                                                            }, 1000);
                                                        }
                                                    }, 1000);
                                                }
                                            }, 1000);
                                        }
                                    }, 1000);
                                }
                            }, 1000);
                        }
                    }, 1000);
                }
            }, 1000);
        }
    }, 1000);
};
