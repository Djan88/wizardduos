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
    count_animation_letter = 0,
    cur_letter,
    onEnd,
    protocol,
    v2,
    v3,
    d12Val,
    let,
    count_animation_let = 0,
    cur_let,
    letters = {
        0: 'Б',
        1: 'Т',
        2: 'Н',
        3: 'М',
        4: 'Г',
        5: 'Р',
        6: 'В',
        7: 'Х',
    },
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
        confirmButtonText: "Выйти",   
        cancelButtonText: "Повторить сессию"
    }, 
    function(isConfirm){
        if (isConfirm) {
            var protocol = undefined;    
            jQuery(location).attr('href','/wizard');
            localStorage.removeItem('croppedImgTwo');
            localStorage.removeItem('croppedImg');
        } else {
            
        }
    });
    var endSound = new buzz.sound( "/sounds/duos", {
        formats: [ "ogg", "mp3" ]
    });
    endSound.play();
}

mw = function(){
//фаза 1
    reloadTime = 0;
    reloadTime1 = 0;
    d12Val = 0;
    cur_animation_val = 0;
    count_animation = 1;
    jQuery('#draggableD12').removeClass('hidden');
    jQuery('.box_rounded').removeClass('hidden');
    jQuery('.chart').data('easyPieChart').update(0);
    jQuery('.chart').find('span').text('0');
    phaseOne = setInterval(function(){
        if (count_animation <= 344){                                                                         //90
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
                });
            count_animation += 1;
            // console.log(count_animation);
            if(count_animation <= 120){
                cur_animation_val += 1.5;
                d12Val+= 9;
                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
            } else if (count_animation >= 120 && count_animation <= 228){
                cur_animation_val -= 1.5;
                d12Val+= 9;
                jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
            } else if (count_animation >= 228 && count_animation <= 292){
                cur_animation_val -= 1.5;
                d12Val+= 9;
                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
            } else if (count_animation >= 292 && count_animation <= 344){
                cur_animation_val += 1.5;
                d12Val+= 9;
                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
            } else {
                d12Val+= 9;
                cur_animation_val += 1.5;
                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
            }
        } else {
            clearInterval(phaseOne);
            count_animation = 1;
            jQuery('#draggable3, #draggable3_1').css({
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
            }, 250);
            jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
            jQuery('#draggableD12').css('transform', 'rotate(0deg)');
            jQuery('.chart').data('easyPieChart').update(6);
            jQuery('.chart').find('span').text('6');

        //фаза 2
            reloadTime = 0;
            cur_animation_val = 0;
            d12Val = 0;
            count_animation = 1;
            phaseOne = setInterval(function(){
                if (count_animation <= 224){                                                                         //56
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
                    jQuery('#draggableD1, #draggableD1_1').text(' ');
                    jQuery('#draggableD1, #draggableD1_1').css({
                        color: '#000',
                        borderColor: 'transparent',
                        opacity: 0.8,
                        transform: 'scale(1) rotateY(180deg)',
                        borderWidth: '1px',
                        paddingTop: '8px',
                        background: 'url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/oct.png) 0 0/100% no-repeat',
                        zIndex: '1000'
                    });
                    if (count_animation <= 224){
                        cur_let = Math.round(Math.random() * (7 - 0))
                        // console.log(letters[cur_let]);
                        jQuery('#draggableD1').text(letters[cur_let]);
                        cur_let = Math.round(Math.random() * (7 - 0))
                        jQuery('#draggableD1_1').text(letters[cur_let]);
                    } else {
                        jQuery('#draggableD1, #draggableD1_1').css({
                            color: 'transparent',
                            paddingTop: '4px'
                        });
                    }
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
                    if(count_animation <= 120){
                        cur_animation_val += 1.5;
                        d12Val+= 9;
                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                    } else {
                        cur_animation_val -= 1.5;
                        d12Val+= 9;
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
                        paddingTop: '7px',
                        zIndex: '1'
                    });
                    jQuery('#draggableD1, #draggableD1_1').text('D+');
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
                    }, 250);
                    jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                    jQuery('#draggableD12').css('transform', 'rotate(0deg)');
                    jQuery('.chart').data('easyPieChart').update(12);
                    jQuery('.chart').find('span').text('12');

        //фаза 3
                    reloadTime = 0;
                    reloadTime1 = 0;
                    d12Val = 0;
                    cur_animation_val = 0;
                    count_animation = 1;
                    phaseOne = setInterval(function(){
                        if (count_animation <= 344){                                                                         //90
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
                            // console.log(count_animation);
                            if(count_animation <= 120){
                                cur_animation_val += 1.5;
                                d12Val+= 9;
                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                            } else if (count_animation >= 120 && count_animation <= 228){
                                cur_animation_val -= 1.5;
                                d12Val+= 9;
                                jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                            } else if (count_animation >= 228 && count_animation <= 292){
                                cur_animation_val -= 1.5;
                                d12Val+= 9;
                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                            } else if (count_animation >= 292 && count_animation <= 344){
                                cur_animation_val += 1.5;
                                d12Val+= 9;
                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                            } else {
                                d12Val+= 9;
                                cur_animation_val += 1.5;
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
                                    tickSound.stop();
                                    reloadSound.play();
                                    reloadTime += 1;
                                } else {
                                    clearInterval(phaseTwo);
                                    reloadSound.stop();
                                    tickSound.play();
                                }
                            }, 250);
                            jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                            jQuery('#draggableD12').css('transform', 'rotate(0deg)');
                            jQuery('.chart').data('easyPieChart').update(18);
                            jQuery('.chart').find('span').text('18');
        //фаза 4
                            reloadTime = 0;
                            reloadTime1 = 0;
                            d12Val = 0;
                            cur_animation_val = 0;
                            count_animation = 1;
                            phaseOne = setInterval(function(){
                                if (count_animation <= 340){                                                                         //90
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
                                    if(count_animation <= 120){
                                        cur_animation_val += 1.5;
                                        d12Val+= 9;
                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                    } else if (count_animation >= 120 && count_animation <= 228){
                                        cur_animation_val -= 1.5;
                                        d12Val+= 9;
                                        jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                    } else if (count_animation >= 228 && count_animation <= 292){
                                        cur_animation_val -= 1.5;
                                        d12Val+= 9;
                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                        jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                        jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                    } else if (count_animation >= 292 && count_animation <= 344){
                                        cur_animation_val += 1.5;
                                        d12Val+= 9;
                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                        jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                        jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                    } else {
                                        d12Val+= 9;
                                        cur_animation_val += 1.5;
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
                                    }, 250);
                                    jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                                    jQuery('#draggableD12').css('transform', 'rotate(0deg)');
                                    jQuery('.chart').data('easyPieChart').update(24);
                                    jQuery('.chart').find('span').text('24');
        //фаза 5
                                    reloadTime = 0;
                                    reloadTime1 = 0;
                                    d12Val = 0;
                                    cur_animation_val = 0;
                                    count_animation = 1;
                                    phaseOne = setInterval(function(){
                                        if (count_animation <= 340){                                                                         //90
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
                                            if(count_animation <= 120){
                                                cur_animation_val += 1.5;
                                                d12Val+= 9;
                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                            } else if (count_animation >= 120 && count_animation <= 228){
                                                cur_animation_val -= 1.5;
                                                d12Val+= 9;
                                                jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                            } else if (count_animation >= 228 && count_animation <= 292){
                                                cur_animation_val -= 1.5;
                                                d12Val+= 9;
                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                            } else if (count_animation >= 292 && count_animation <= 344){
                                                cur_animation_val += 1.5;
                                                d12Val+= 9;
                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                            } else {
                                                d12Val+= 9;
                                                cur_animation_val += 1.5;
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
                                            }, 250);
                                            jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                                            jQuery('#draggableD12').css('transform', 'rotate(0deg)');
                                            jQuery('.chart').data('easyPieChart').update(30);
                                            jQuery('.chart').find('span').text('30');
        //фаза 6
                                            reloadTime = 0;
                                            reloadTime1 = 0;
                                            d12Val = 0;
                                            cur_animation_val = 0;
                                            count_animation = 1;
                                            phaseOne = setInterval(function(){
                                                if (count_animation <= 340){                                                                         //90
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
                                                    if(count_animation <= 120){
                                                        cur_animation_val += 1.5;
                                                        d12Val+= 9;
                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                    } else if (count_animation >= 120 && count_animation <= 228){
                                                        cur_animation_val -= 1.5;
                                                        d12Val+= 9;
                                                        jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                    } else if (count_animation >= 228 && count_animation <= 292){
                                                        cur_animation_val -= 1.5;
                                                        d12Val+= 9;
                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                        jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                        jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                                    } else if (count_animation >= 292 && count_animation <= 344){
                                                        cur_animation_val += 1.5;
                                                        d12Val+= 9;
                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                        jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                        jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                                    } else {
                                                        d12Val+= 9;
                                                        cur_animation_val += 1.5;
                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                        jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                        jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                                    }
                                                } else {
                                                    clearInterval(phaseOne);
                                                    count_animation = 1;
                                                    jQuery('#draggable3, #draggable3_1').css({
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
                                                    }, 250);
                                                    jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                                                    jQuery('#draggableD12').css('transform', 'rotate(0deg)');
                                                    jQuery('.chart').data('easyPieChart').update(36);
                                                    jQuery('.chart').find('span').text('36');
        //фаза 7
                                                    reloadTime = 0;
                                                    reloadTime1 = 0;
                                                    d12Val = 0;
                                                    cur_animation_val = 0;
                                                    count_animation = 1;
                                                    phaseOne = setInterval(function(){
                                                        if (count_animation <= 340){                                                                         //90
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
                                                            if(count_animation <= 120){
                                                                cur_animation_val += 1.5;
                                                                d12Val+= 9;
                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                            } else if (count_animation >= 120 && count_animation <= 228){
                                                                cur_animation_val -= 1.5;
                                                                d12Val+= 9;
                                                                jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                            } else if (count_animation >= 228 && count_animation <= 292){
                                                                cur_animation_val -= 1.5;
                                                                d12Val+= 9;
                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                                jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                                            } else if (count_animation >= 292 && count_animation <= 344){
                                                                cur_animation_val += 1.5;
                                                                d12Val+= 9;
                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                                jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                                            } else {
                                                                d12Val+= 9;
                                                                cur_animation_val += 1.5;
                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                                jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                                            }
                                                        } else {
                                                            clearInterval(phaseOne);
                                                            count_animation = 1;
                                                            jQuery('#draggable2, #draggable2_1').css({
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
                                                            }, 250);
                                                            jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                                                            jQuery('#draggableD12').css('transform', 'rotate(0deg)');
                                                            jQuery('.chart').data('easyPieChart').update(42);
                                                            jQuery('.chart').find('span').text('42');
        //фаза 8
                                                            reloadTime = 0;
                                                            reloadTime1 = 0;
                                                            d12Val = 0;
                                                            cur_animation_val = 0;
                                                            count_animation = 1;
                                                            phaseOne = setInterval(function(){
                                                                if (count_animation <= 340){                                                                         //90
                                                                    tickSound.play();
                                                                    jQuery('#draggable1, #draggable1_1').css({
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
                                                                    if(count_animation <= 120){
                                                                        cur_animation_val += 1.5;
                                                                        d12Val+= 9;
                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                    } else if (count_animation >= 120 && count_animation <= 228){
                                                                        cur_animation_val -= 1.5;
                                                                        d12Val+= 9;
                                                                        jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                    } else if (count_animation >= 228 && count_animation <= 292){
                                                                        cur_animation_val -= 1.5;
                                                                        d12Val+= 9;
                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                        jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                                        jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                                                    } else if (count_animation >= 292 && count_animation <= 344){
                                                                        cur_animation_val += 1.5;
                                                                        d12Val+= 9;
                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                        jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                                        jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                                                    } else {
                                                                        d12Val+= 9;
                                                                        cur_animation_val += 1.5;
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
                                                                    }, 250);
                                                                    jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                                                                    jQuery('#draggableD12').css('transform', 'rotate(0deg)');
                                                                    jQuery('.chart').data('easyPieChart').update(48);
                                                                    jQuery('.chart').find('span').text('48');
        //фаза 9
                                                                    reloadTime = 0;
                                                                    reloadTime1 = 0;
                                                                    d12Val = 0;
                                                                    cur_animation_val = 0;
                                                                    count_animation = 1;
                                                                    phaseOne = setInterval(function(){
                                                                        if (count_animation <= 340){                                                                         //90
                                                                            tickSound.play();
                                                                            jQuery('#draggable0, #draggable0_1').css({
                                                                                color: 'transparent',
                                                                                borderColor: 'transparent',
                                                                                opacity: 0.8,
                                                                                transform: 'scale(1)',
                                                                                borderWidth: '1px',
                                                                                paddingTop: '4px',
                                                                                background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/veter.png) 0 0/100% no-repeat',
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
                                                                            if(count_animation <= 120){
                                                                                cur_animation_val += 1.5;
                                                                                d12Val+= 9;
                                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                            } else if (count_animation >= 120 && count_animation <= 228){
                                                                                cur_animation_val -= 1.5;
                                                                                d12Val+= 9;
                                                                                jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                            } else if (count_animation >= 228 && count_animation <= 292){
                                                                                cur_animation_val -= 1.5;
                                                                                d12Val+= 9;
                                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                                                jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                                                            } else if (count_animation >= 292 && count_animation <= 344){
                                                                                cur_animation_val += 1.5;
                                                                                d12Val+= 9;
                                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                                                jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                                                            } else {
                                                                                d12Val+= 9;
                                                                                cur_animation_val += 1.5;
                                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                                                jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                                                            }
                                                                        } else {
                                                                            clearInterval(phaseOne);
                                                                            count_animation = 1;
                                                                            jQuery('#draggable0, #draggable0_1').css({
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
                                                                            }, 250);
                                                                            jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                                                                            jQuery('#draggableD12').css('transform', 'rotate(0deg)');
                                                                            jQuery('.chart').data('easyPieChart').update(54);
                                                                            jQuery('.chart').find('span').text('54');
        //фаза 10
                                                                            reloadTime = 0;
                                                                            reloadTime1 = 0;
                                                                            d12Val = 0;
                                                                            cur_animation_val = 0;
                                                                            count_animation = 1;
                                                                            phaseOne = setInterval(function(){
                                                                                if (count_animation <= 340){                                                                         //90
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
                                                                                    if(count_animation <= 120){
                                                                                        cur_animation_val += 1.5;
                                                                                        d12Val+= 9;
                                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                    } else if (count_animation >= 120 && count_animation <= 228){
                                                                                        cur_animation_val -= 1.5;
                                                                                        d12Val+= 9;
                                                                                        jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                    } else if (count_animation >= 228 && count_animation <= 292){
                                                                                        cur_animation_val -= 1.5;
                                                                                        d12Val+= 9;
                                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                        jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                                                        jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                                                                    } else if (count_animation >= 292 && count_animation <= 344){
                                                                                        cur_animation_val += 1.5;
                                                                                        d12Val+= 9;
                                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                        jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                                                        jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat');
                                                                                    } else {
                                                                                        d12Val+= 9;
                                                                                        cur_animation_val += 1.5;
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
                                                                                            reloadSound.play();
                                                                                            reloadTime += 1;
                                                                                        } else {
                                                                                            clearInterval(phaseTwo);
                                                                                            reloadSound.stop();
                                                                                        }
                                                                                    }, 250);
                                                                                    jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                                                                                    jQuery('#draggableD12').css('transform', 'rotate(0deg)');
                                                                                    jQuery('.chart').data('easyPieChart').update(60);
                                                                                    jQuery('.chart').find('span').text('60');
        //фаза 11
                                                                                    reloadTime = 0;
                                                                                    reloadTime1 = 0;
                                                                                    d12Val = 0;
                                                                                    cur_animation_val = 0;
                                                                                    count_animation = 1;
                                                                                    phaseOne = setInterval(function(){
                                                                                        if (count_animation <= 340){                                                                         //90
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
                                                                                                    background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat',
                                                                                                    transform: 'rotate('+d12Val+'deg)',
                                                                                                    borderColor: 'transparent'
                                                                                                });;
                                                                                            count_animation += 1;
                                                                                            if(count_animation <= 60){
                                                                                                cur_animation_val -= 1.5;
                                                                                                d12Val+= 9;
                                                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                            } else if (count_animation >= 60 && count_animation <= 120){
                                                                                                cur_animation_val += 1.5;
                                                                                                d12Val+= 9;
                                                                                                jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                            } else if (count_animation >= 120 && count_animation <= 228){
                                                                                                cur_animation_val += 1.5;
                                                                                                d12Val+= 9;
                                                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                                                                                jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat');
                                                                                            } else if (count_animation >= 228 && count_animation <= 332){
                                                                                                cur_animation_val -= 1.5;
                                                                                                d12Val+= 9;
                                                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                                                                                jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat');
                                                                                            } else {
                                                                                                d12Val+= 9;
                                                                                                cur_animation_val -= 1.5;
                                                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                                                                                jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat');
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
                                                                                            }, 250);
                                                                                            jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                                                                                            jQuery('#draggableD12').css('transform', 'rotate(0deg)');
                                                                                            jQuery('.chart').data('easyPieChart').update(66);
                                                                                            jQuery('.chart').find('span').text('66');
        //фаза 12
                                                                                            reloadTime = 0;
                                                                                            reloadTime1 = 0;
                                                                                            d12Val = 0;
                                                                                            cur_animation_val = 0;
                                                                                            count_animation = 1;
                                                                                            phaseOne = setInterval(function(){
                                                                                                if (count_animation <= 332){                                                                         //90
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
                                                                                                            background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat',
                                                                                                            transform: 'rotate('+d12Val+'deg)',
                                                                                                            borderColor: 'transparent'
                                                                                                        });;
                                                                                                    count_animation += 1;
                                                                                                    if(count_animation <= 60){
                                                                                                        cur_animation_val -= 1.5;
                                                                                                        d12Val+= 9;
                                                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                    } else if (count_animation >= 60 && count_animation <= 120){
                                                                                                        cur_animation_val += 1.5;
                                                                                                        d12Val+= 9;
                                                                                                        jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                    } else if (count_animation >= 120 && count_animation <= 228){
                                                                                                        cur_animation_val += 1.5;
                                                                                                        d12Val+= 9;
                                                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                        jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                                                                                        jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat');
                                                                                                    } else if (count_animation >= 228 && count_animation <= 332){
                                                                                                        cur_animation_val -= 1.5;
                                                                                                        d12Val+= 9;
                                                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                        jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                                                                                        jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat');
                                                                                                    } else {
                                                                                                        d12Val+= 9;
                                                                                                        cur_animation_val -= 1.5;
                                                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                        jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                                                                                        jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat');
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
                                                                                                    }, 250);
                                                                                                    jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                                                                                                    jQuery('#draggableD12').css('transform', 'rotate(0deg)');
                                                                                                    jQuery('.chart').data('easyPieChart').update(72);
                                                                                                    jQuery('.chart').find('span').text('72');
                                                                //фаза 13
                                                                                                    reloadTime = 0;
                                                                                                    reloadTime1 = 0;
                                                                                                    d12Val = 0;
                                                                                                    cur_animation_val = 0;
                                                                                                    count_animation = 1;
                                                                                                    phaseOne = setInterval(function(){
                                                                                                        if (count_animation <= 340){                                                                         //90
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
                                                                                                            
                                                                                                            count_animation += 1;
                                                                                                            jQuery('#draggableD12')
                                                                                                                .removeClass('hidden')
                                                                                                                .css({
                                                                                                                    opacity: 0.8,
                                                                                                                    transform: 'scale(1)',
                                                                                                                    background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat',
                                                                                                                    transform: 'rotate('+d12Val+'deg)',
                                                                                                                    borderColor: 'transparent'
                                                                                                                });
                                                                                                            count_animation += 1;
                                                                                                            if(count_animation <= 60){
                                                                                                                cur_animation_val -= 1.5;
                                                                                                                d12Val+= 9;
                                                                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                            } else if (count_animation >= 60 && count_animation <= 120){
                                                                                                                cur_animation_val += 1.5;
                                                                                                                d12Val+= 9;
                                                                                                                jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                            } else if (count_animation >= 120 && count_animation <= 228){
                                                                                                                cur_animation_val += 1.5;
                                                                                                                d12Val+= 9;
                                                                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                                jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                                                                                                jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat');
                                                                                                            } else if (count_animation >= 228 && count_animation <= 332){
                                                                                                                cur_animation_val -= 1.5;
                                                                                                                d12Val+= 9;
                                                                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                                jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                                                                                                jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat');
                                                                                                            } else {
                                                                                                                d12Val+= 9;
                                                                                                                cur_animation_val -= 1.5;
                                                                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                                jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                                                                                                jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat');
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
                                                                                                            }, 250);
                                                                                                            jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                                                                                                            jQuery('#draggableD12').css('transform', 'rotate(0deg)');
                                                                                                            jQuery('.chart').data('easyPieChart').update(78);
                                                                                                            jQuery('.chart').find('span').text('78');
                                                                                //фаза 14
                                                                                                            reloadTime = 0;
                                                                                                            reloadTime1 = 0;
                                                                                                            d12Val = 0;
                                                                                                            cur_animation_val = 0;
                                                                                                            count_animation = 1;
                                                                                                            phaseOne = setInterval(function(){
                                                                                                                if (count_animation <= 332){                                                                         //90
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
                                                                                                                    
                                                                                                                    count_animation += 1;
                                                                                                                    jQuery('#draggableD12')
                                                                                                                        .removeClass('hidden')
                                                                                                                        .css({
                                                                                                                            opacity: 0.8,
                                                                                                                            transform: 'scale(1)',
                                                                                                                            background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat',
                                                                                                                            transform: 'rotate('+d12Val+'deg)',
                                                                                                                            borderColor: 'transparent'
                                                                                                                        });
                                                                                                                    count_animation += 1;
                                                                                                                    if(count_animation <= 60){
                                                                                                                        cur_animation_val -= 1.5;
                                                                                                                        d12Val+= 9;
                                                                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                                    } else if (count_animation >= 60 && count_animation <= 120){
                                                                                                                        cur_animation_val += 1.5;
                                                                                                                        d12Val+= 9;
                                                                                                                        jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                                    } else if (count_animation >= 120 && count_animation <= 228){
                                                                                                                        cur_animation_val += 1.5;
                                                                                                                        d12Val+= 9;
                                                                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                                        jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                                                                                                        jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat');
                                                                                                                    } else if (count_animation >= 228 && count_animation <= 332){
                                                                                                                        cur_animation_val -= 1.5;
                                                                                                                        d12Val+= 9;
                                                                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                                        jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                                                                                                        jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat');
                                                                                                                    } else {
                                                                                                                        d12Val+= 9;
                                                                                                                        cur_animation_val -= 1.5;
                                                                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                                        jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                                                                                                        jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat');
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
                                                                                                                    }, 250);
                                                                                                                    jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                                                                                                                    jQuery('#draggableD12').css('transform', 'rotate(0deg)');
                                                                                                                    jQuery('.chart').data('easyPieChart').update(84);
                                                                                                                    jQuery('.chart').find('span').text('84');
                                                                                                //фаза 15
                                                                                                                    reloadTime = 0;
                                                                                                                    reloadTime1 = 0;
                                                                                                                    d12Val = 0;
                                                                                                                    cur_animation_val = 0;
                                                                                                                    count_animation = 1;
                                                                                                                    phaseOne = setInterval(function(){
                                                                                                                        if (count_animation <= 332){                                                                         //90
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
                                                                                                                                    background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat',
                                                                                                                                    transform: 'rotate('+d12Val+'deg)',
                                                                                                                                    borderColor: 'transparent'
                                                                                                                                });
                                                                                                                            count_animation += 1;
                                                                                                                            if(count_animation <= 60){
                                                                                                                                cur_animation_val -= 1.5;
                                                                                                                                d12Val+= 9;
                                                                                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                                            } else if (count_animation >= 60 && count_animation <= 120){
                                                                                                                                cur_animation_val += 1.5;
                                                                                                                                d12Val+= 9;
                                                                                                                                jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                                                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                                            } else if (count_animation >= 120 && count_animation <= 228){
                                                                                                                                cur_animation_val += 1.5;
                                                                                                                                d12Val+= 9;
                                                                                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                                                jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                                                                                                                jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat');
                                                                                                                            } else if (count_animation >= 228 && count_animation <= 332){
                                                                                                                                cur_animation_val -= 1.5;
                                                                                                                                d12Val+= 9;
                                                                                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                                                jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                                                                                                                jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat');
                                                                                                                            } else {
                                                                                                                                d12Val+= 9;
                                                                                                                                cur_animation_val -= 1.5;
                                                                                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                                                jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                                                                                                                jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat');
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
                                                                                                                                if (reloadTime <= 1){
                                                                                                                                    reloadSound.play();
                                                                                                                                    reloadTime += 1;
                                                                                                                                } else {
                                                                                                                                    clearInterval(phaseTwo);
                                                                                                                                    reloadSound.stop();
                                                                                                                                }
                                                                                                                            }, 250);
                                                                                                                            jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                                                                                                                            jQuery('#draggableD12').css('transform', 'rotate(0deg)');
                                                                                                                            jQuery('.chart').data('easyPieChart').update(90);
                                                                                                                            jQuery('.chart').find('span').text('90');
                                                                                                //фаза 16
                                                                                                                            reloadTime = 0;
                                                                                                                            reloadTime1 = 0;
                                                                                                                            d12Val = 0;
                                                                                                                            cur_animation_val = 0;
                                                                                                                            count_animation = 1;
                                                                                                                            phaseOne = setInterval(function(){
                                                                                                                                if (count_animation <= 332){
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
                                                                                                                                            background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat',
                                                                                                                                            transform: 'rotate('+d12Val+'deg)',
                                                                                                                                            borderColor: 'transparent'
                                                                                                                                        });
                                                                                                                                    count_animation += 1;
                                                                                                                                    if(count_animation <= 60){
                                                                                                                                        cur_animation_val -= 1.5;
                                                                                                                                        d12Val+= 9;
                                                                                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                                                    } else if (count_animation >= 60 && count_animation <= 120){
                                                                                                                                        cur_animation_val += 1.5;
                                                                                                                                        d12Val+= 9;
                                                                                                                                        jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                                                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                                                    } else if (count_animation >= 120 && count_animation <= 228){
                                                                                                                                        cur_animation_val += 1.5;
                                                                                                                                        d12Val+= 9;
                                                                                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                                                        jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                                                                                                                        jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat');
                                                                                                                                    } else if (count_animation >= 228 && count_animation <= 332){
                                                                                                                                        cur_animation_val -= 1.5;
                                                                                                                                        d12Val+= 9;
                                                                                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                                                        jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                                                                                                                        jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat');
                                                                                                                                    } else {
                                                                                                                                        d12Val+= 9;
                                                                                                                                        cur_animation_val -= 1.5;
                                                                                                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                                                                                        jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                                                                                                                        jQuery('#draggableD12').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat');
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
                                                                                                                                    }, 250);
                                                                                                                                    jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                                                                                                                                    jQuery('#draggableD12').css('transform', 'rotate(0deg)');
                                                                                                                                    jQuery('.chart').data('easyPieChart').update(96);
                                                                                                                                    jQuery('.chart').find('span').text('96');
                                                                                                                        //фаза 17
                                                                                                                                    cur_animation_val = 55;
                                                                                                                                    count_animation = 1;
                                                                                                                                    jQuery('.box_rounded').addClass('hidden');
                                                                                                                                    phaseSeven_one = setInterval(function(){
                                                                                                                                        if (count_animation <= 88){                                                                         //22
                                                                                                                                            cur_animation_val += 0.375;
                                                                                                                                            jQuery('#draggable5_1, #draggable2').css({
                                                                                                                                                transform: 'rotate(-'+cur_animation_val+'deg) scale(1)',
                                                                                                                                                background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/mo_right.png) 0 0/100% no-repeat',
                                                                                                                                                color: 'transparent',
                                                                                                                                                borderColor: 'transparent',
                                                                                                                                                opacity: 0.8,
                                                                                                                                                borderWidth: '1px',
                                                                                                                                                paddingTop: '4px',
                                                                                                                                                zIndex: '1000'
                                                                                                                                            });
                                                                                                                                            count_animation += 1;
                                                                                                                                        } else if(count_animation <= 156) {                                                         //39
                                                                                                                                            count_animation += 1;
                                                                                                                                        } else {
                                                                                                                                            clearInterval(phaseSeven_one);
                                                                                                                                            count_animation = 1;
                                                                                                                                            jQuery('#draggable5_1, #draggable2').css({
                                                                                                                                                transform: 'rotate(-'+0+'deg) scale(0.5)',
                                                                                                                                                background: 'rgba(255,255,255, 0.5)',
                                                                                                                                                color: 'red',
                                                                                                                                                borderColor: 'red',
                                                                                                                                                opacity: 1,
                                                                                                                                                borderWidth: '2px',
                                                                                                                                                paddingTop: '2px',
                                                                                                                                                zIndex: '1'
                                                                                                                                            });
                                                                                                                                            jQuery('.chart').data('easyPieChart').update(97);
                                                                                                                                            jQuery('.chart').find('span').text('97');
                                                                                                                        //Этап 17-1-2
                                                                                                                                            cur_animation_val = 10;
                                                                                                                                            count_animation = 1;
                                                                                                                                            phaseSeven_one = setInterval(function(){
                                                                                                                                                if (count_animation <= 212){                                                                         //53
                                                                                                                                                    cur_animation_val += 0.375;
                                                                                                                                                    jQuery('#draggable5_1, #draggable2').css({
                                                                                                                                                        transform: 'rotate('+cur_animation_val+'deg) scale(1)',
                                                                                                                                                        background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/mo_left.png) 0 0/100% no-repeat',
                                                                                                                                                        color: 'transparent',
                                                                                                                                                        borderColor: 'transparent',
                                                                                                                                                        opacity: 0.8,
                                                                                                                                                        borderWidth: '1px',
                                                                                                                                                        paddingTop: '4px',
                                                                                                                                                        zIndex: '1000'
                                                                                                                                                    });
                                                                                                                                                    count_animation += 1;
                                                                                                                                                } else if(count_animation <= 280) {                                                         //70
                                                                                                                                                    count_animation += 1;
                                                                                                                                                } else {
                                                                                                                                                    clearInterval(phaseSeven_one);
                                                                                                                                                    count_animation = 1;
                                                                                                                                                    jQuery('#draggable5_1, #draggable2').css({
                                                                                                                                                        transform: 'rotate(-'+0+'deg) scale(0.5)',
                                                                                                                                                        background: 'rgba(255,255,255, 0.5)',
                                                                                                                                                        color: 'red',
                                                                                                                                                        borderColor: 'red',
                                                                                                                                                        opacity: 1,
                                                                                                                                                        borderWidth: '2px',
                                                                                                                                                        paddingTop: '2px',
                                                                                                                                                        zIndex: '1'
                                                                                                                                                    });
                                                                                                                                                    jQuery('.chart').data('easyPieChart').update(98);
                                                                                                                                                    jQuery('.chart').find('span').text('98');
                                                                                                                        //Этап 17-1-3
                                                                                                                                                    cur_animation_val = 270;
                                                                                                                                                    count_animation = 1;
                                                                                                                                                    phaseSeven_one = setInterval(function(){
                                                                                                                                                        if (count_animation <= 240){                                                                         //60
                                                                                                                                                            cur_animation_val += 0.375;
                                                                                                                                                            jQuery('#draggable5_1, #draggable2').css({
                                                                                                                                                                transform: 'rotate('+cur_animation_val+'deg) scale(1)',
                                                                                                                                                                background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/mo_left.png) 0 0/100% no-repeat',
                                                                                                                                                                color: 'transparent',
                                                                                                                                                                borderColor: 'transparent',
                                                                                                                                                                opacity: 0.8,
                                                                                                                                                                borderWidth: '1px',
                                                                                                                                                                paddingTop: '4px',
                                                                                                                                                                zIndex: '1000'
                                                                                                                                                            });
                                                                                                                                                            count_animation += 1;
                                                                                                                                                        } else if(count_animation <= 308) {                                                         //77
                                                                                                                                                            count_animation += 1;
                                                                                                                                                        } else {
                                                                                                                                                            clearInterval(phaseSeven_one);
                                                                                                                                                            count_animation = 1;
                                                                                                                                                            jQuery('#draggable5_1, #draggable2').css({
                                                                                                                                                                transform: 'rotate(-'+0+'deg) scale(0.5)',
                                                                                                                                                                background: 'rgba(255,255,255, 0.5)',
                                                                                                                                                                color: 'red',
                                                                                                                                                                borderColor: 'red',
                                                                                                                                                                opacity: 1,
                                                                                                                                                                borderWidth: '2px',
                                                                                                                                                                paddingTop: '2px',
                                                                                                                                                                zIndex: '1'
                                                                                                                                                            });
                                                                                                                                                            jQuery('.chart').data('easyPieChart').update(99);
                                                                                                                                                            jQuery('.chart').find('span').text('99');
                                                                                                                        //Этап 17-1-4
                                                                                                                                                            cur_animation_val = 300;
                                                                                                                                                            count_animation = 1;
                                                                                                                                                            phaseSeven_one = setInterval(function(){
                                                                                                                                                                if (count_animation <= 120){                                                                         //40
                                                                                                                                                                    cur_animation_val += 0.375;
                                                                                                                                                                    jQuery('#draggable5_1, #draggable2').css({
                                                                                                                                                                        transform: 'rotate(-'+cur_animation_val+'deg) scale(1)',
                                                                                                                                                                        background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/mo_right.png) 0 0/100% no-repeat',
                                                                                                                                                                        color: 'transparent',
                                                                                                                                                                        borderColor: 'transparent',
                                                                                                                                                                        opacity: 0.8,
                                                                                                                                                                        borderWidth: '1px',
                                                                                                                                                                        paddingTop: '4px',
                                                                                                                                                                        zIndex: '1000'
                                                                                                                                                                    });
                                                                                                                                                                    count_animation += 1;
                                                                                                                                                                } else if(count_animation <= 228) {                                                         //57
                                                                                                                                                                    count_animation += 1;
                                                                                                                                                                } else {
                                                                                                                                                                    clearInterval(phaseSeven_one);
                                                                                                                                                                    count_animation = 1;
                                                                                                                                                                    jQuery('#draggable5_1, #draggable2').css({
                                                                                                                                                                        transform: 'rotate(-'+0+'deg) scale(0.5)',
                                                                                                                                                                        background: 'rgba(255,255,255, 0.5)',
                                                                                                                                                                        color: 'red',
                                                                                                                                                                        borderColor: 'red',
                                                                                                                                                                        opacity: 1,
                                                                                                                                                                        borderWidth: '2px',
                                                                                                                                                                        paddingTop: '2px',
                                                                                                                                                                        zIndex: '1'
                                                                                                                                                                    });
                                                                                                                                                                    jQuery('.chart').data('easyPieChart').update(100);
                                                                                                                                                                    jQuery('.chart').find('span').text('100');
                                                                                                                                                                    tickSound.stop();
                                                                                                                                                                    jQuery('#draggableD12').addClass('hidden');
                                                                                                                                                                    jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                                                                                                                                                                    jQuery('#draggableD12').css('transform', 'rotate(0deg)');
                                                                                                                                                                    onEnd();
                                                                                                                                                                }
                                                                                                                                                            }, 250);
                                                                                                                                                        }
                                                                                                                                                    }, 250);
                                                                                                                                                }
                                                                                                                                            }, 250);
                                                                                                                                        }
                                                                                                                                    }, 250);
                                                                                                                                }
                                                                                                                            }, 250);
                                                                                                                        }
                                                                                                                    }, 250);
                                                                                                                }
                                                                                                            }, 250);
                                                                                                        }
                                                                                                    }, 250);
                                                                                                }
                                                                                            }, 250);
                                                                                        }
                                                                                    }, 250);
                                                                                }
                                                                            }, 250);
                                                                        }
                                                                    }, 250);
                                                                }
                                                            }, 250);
                                                        }
                                                    }, 250);
                                                }
                                            }, 250);
                                        }
                                    }, 250);
                                }
                            }, 250);
                        }
                    }, 250);
                }
            }, 250);
        }
    }, 250);
};

ww = function(){
//фаза 1
    reloadTime = 0;
    cur_animation_val = 0;
    d12Val = 0;
    count_animation = 1;
    jQuery('#draggableD12').removeClass('hidden');
    jQuery('.box_rounded').removeClass('hidden');
    jQuery('.chart').data('easyPieChart').update(0);
    jQuery('.chart').find('span').text('0');
    phaseOne = setInterval(function(){
        if (count_animation <= 240){                                                                         //56
            tickSound.play();
            jQuery('#draggable0, #draggable0_1, #draggable3, #draggable3_1').css({
                color: 'transparent',
                borderColor: 'transparent',
                opacity: 0.8,
                transform: 'scale(1)',
                borderWidth: '1px',
                paddingTop: '4px',
                background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/edinenie_s_tvorcom.jpg) 0 0/100% no-repeat',
                zIndex: '1000'
            });
            jQuery('#draggable0, #draggable0_1').css({
                background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/plod.png) 0 0/100% no-repeat',
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
            if(count_animation <= 120){
                cur_animation_val += 1.5;
                d12Val+= 9;
                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
            } else {
                cur_animation_val -= 1.5;
                d12Val+= 9;
                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
            }
        } else {
            clearInterval(phaseOne);
            count_animation = 1;
            jQuery('#draggable0, #draggable0_1, #draggable3, #draggable3_1').css({
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
            }, 250);
            jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
            jQuery('#draggableD12').css('transform', 'rotate(0deg)');
            jQuery('.chart').data('easyPieChart').update(15);
            jQuery('.chart').find('span').text('15');
//фаза 2
            reloadTime = 0;
            cur_animation_val = 0;
            d12Val = 0;
            count_animation = 1;
            phaseOne = setInterval(function(){
                if (count_animation <= 240){                                                                         //56
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
                    if(count_animation <= 120){
                        cur_animation_val += 1.5;
                        d12Val+= 9;
                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                    } else {
                        cur_animation_val -= 1.5;
                        d12Val+= 9;
                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                        jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
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
                    }, 250);
                    jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                    jQuery('#draggableD12').css('transform', 'rotate(0deg)');
                    jQuery('.chart').data('easyPieChart').update(30);
                    jQuery('.chart').find('span').text('30');
        //фаза 3
                    reloadTime = 0;
                    cur_animation_val = 0;
                    d12Val = 0;
                    count_animation = 1;
                    phaseOne = setInterval(function(){
                        if (count_animation <= 240){                                                                         //56
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
                            if(count_animation <= 120){
                                cur_animation_val += 1.5;
                                d12Val+= 9;
                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                            } else {
                                cur_animation_val -= 1.5;
                                d12Val+= 9;
                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                            }
                        } else {
                            clearInterval(phaseOne);
                            count_animation = 1;
                            jQuery('#draggable2, #draggable2_1').css({
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
                            }, 250);
                            jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                            jQuery('#draggableD12').css('transform', 'rotate(0deg)');
                            jQuery('.chart').data('easyPieChart').update(45);
                            jQuery('.chart').find('span').text('45');
                //фаза 4
                            reloadTime = 0;
                            cur_animation_val = 0;
                            d12Val = 0;
                            count_animation = 1;
                            phaseOne = setInterval(function(){
                                if (count_animation <= 240){                                                                         //56
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
                                    if(count_animation <= 120){
                                        cur_animation_val += 1.5;
                                        d12Val+= 9;
                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                    } else {
                                        cur_animation_val -= 1.5;
                                        d12Val+= 9;
                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                        jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                    }
                                } else {
                                    clearInterval(phaseOne);
                                    count_animation = 1;
                                    jQuery('#draggable3, #draggable3_1').css({
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
                                    }, 250);
                                    jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                                    jQuery('#draggableD12').css('transform', 'rotate(0deg)');
                                    jQuery('.chart').data('easyPieChart').update(60);
                                    jQuery('.chart').find('span').text('60');
                        //фаза 5
                                    reloadTime = 0;
                                    cur_animation_val = 0;
                                    d12Val = 0;
                                    count_animation = 1;
                                    phaseOne = setInterval(function(){
                                        if (count_animation <= 240){                                                                         //56
                                            tickSound.play();
                                            jQuery('#draggable4, #draggable4_1').css({
                                                color: 'transparent',
                                                borderColor: 'transparent',
                                                opacity: 0.8,
                                                transform: 'rotate(-'+d12Val/2+'deg) scale(1)',
                                                borderWidth: '1px',
                                                paddingTop: '4px',
                                                background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat',
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
                                            if(count_animation <= 120){
                                                cur_animation_val += 1.5;
                                                d12Val+= 9;
                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                            } else {
                                                cur_animation_val -= 1.5;
                                                d12Val+= 9;
                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
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
                                            }, 250);
                                            jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                                            jQuery('#draggableD12').css('transform', 'rotate(0deg)');
                                            jQuery('.chart').data('easyPieChart').update(75);
                                            jQuery('.chart').find('span').text('75');
                                //фаза 6
                                            reloadTime = 0;
                                            cur_animation_val = 0;
                                            d12Val = 0;
                                            count_animation = 1;
                                            phaseOne = setInterval(function(){
                                                if (count_animation <= 240){                                                                         //56
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
                                                    if(count_animation <= 120){
                                                        cur_animation_val += 1.5;
                                                        d12Val+= 9;
                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                    } else {
                                                        cur_animation_val -= 1.5;
                                                        d12Val+= 9;
                                                        jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                        jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
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
                                                    }, 250);
                                                    jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                                                    jQuery('#draggableD12').css('transform', 'rotate(0deg)');
                                                    jQuery('.chart').data('easyPieChart').update(90);
                                                    jQuery('.chart').find('span').text('90');
                                        //фаза 7
                                                    reloadTime = 0;
                                                    cur_animation_val = 0;
                                                    d12Val = 0;
                                                    count_animation = 1;
                                                    phaseOne = setInterval(function(){
                                                        if (count_animation <= 240){                                                                         //56
                                                            tickSound.play();
                                                            jQuery('#draggable6, #draggable6_1').css({
                                                                color: 'transparent',
                                                                borderColor: 'transparent',
                                                                opacity: 0.8,
                                                                transform: 'rotate(-'+d12Val/2+'deg) scale(1)',
                                                                borderWidth: '1px',
                                                                paddingTop: '4px',
                                                                background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat',
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
                                                            if(count_animation <= 120){
                                                                cur_animation_val += 1.5;
                                                                d12Val+= 9;
                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                            } else {
                                                                cur_animation_val -= 1.5;
                                                                d12Val+= 9;
                                                                jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                                                                jQuery('#draggableD12').css('transform', 'rotate(-'+d12Val+'deg)');
                                                            }
                                                        } else {
                                                            clearInterval(phaseOne);
                                                            tickSound.stop();
                                                            count_animation = 1;
                                                            jQuery('#draggable6, #draggable6_1').css({
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
                                                                }
                                                            }, 250);
                                                            jQuery('.chart').data('easyPieChart').update(100);
                                                            jQuery('.chart').find('span').text('100');
                                                            tickSound.stop();
                                                            jQuery('#draggableD12').addClass('hidden');
                                                            jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                                                            jQuery('#draggableD12').css('transform', 'rotate(0deg)');
                                                            onEnd();
                                                        }
                                                    }, 250);
                                                }
                                            }, 250);
                                        }
                                    }, 250);
                                }
                            }, 250);
                        }
                    }, 250);
                }
            }, 250);
        }
    }, 250);
};

mm = function(){
//фаза 1
    reloadTime = 0;
    cur_animation_val = 0;
    d12Val = 0;
    count_animation = 1;
    count_animation_let = 0;
    cur_let;
    jQuery('#draggableD12').removeClass('hidden');
    jQuery('.box_rounded').removeClass('hidden');
    jQuery('.chart').data('easyPieChart').update(0);
    jQuery('.chart').find('span').text('0');
    phaseOne = setInterval(function(){
        // console.log(count_animation);
        if (count_animation <= 200){                                                                         //56
            tickSound.play();
            jQuery('#draggableD1, #draggableD1_1').text(' ');
            jQuery('#draggableD1, #draggableD1_1').css({
                color: '#000',
                borderColor: 'transparent',
                opacity: 0.8,
                transform: 'scale(1) rotateY(180deg)',
                borderWidth: '1px',
                paddingTop: '8px',
                background: 'url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/oct.png) 0 0/100% no-repeat',
                zIndex: '1000'
            });
            if (count_animation <= 200){
                cur_let = Math.round(Math.random() * (7 - 0))
                // console.log(letters[cur_let]);
                jQuery('#draggableD1').text(letters[cur_let]);
                cur_let = Math.round(Math.random() * (7 - 0))
                jQuery('#draggableD1_1').text(letters[cur_let]);
            } else {
                jQuery('#draggableD1, #draggableD1_1').css({
                    color: 'transparent',
                    paddingTop: '4px'
                });
            }
            jQuery('#draggableD12')
                .removeClass('hidden')
                .css({
                    opacity: 0.8,
                    transform: 'scale(1)',
                    background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat',
                    transform: 'rotate('+d12Val+'deg)',
                    borderColor: 'transparent'
                });;
            count_animation += 1;
            if(count_animation <= 100){
                cur_animation_val += 1.5;
                d12Val+= 9;
                jQuery('.box_rounded').css('transform', 'rotate(-'+cur_animation_val+'deg) scale(1)');
            } else {
                cur_animation_val -= 1.5;
                d12Val+= 9;
                jQuery('.box_rounded').css('transform', 'rotate(-'+cur_animation_val+'deg) scale(1)');
                jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
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
                paddingTop: '7px',
                zIndex: '1'
            });
            jQuery('#draggableD1, #draggableD1_1').text('D+');
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
            }, 250);
            jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
            jQuery('#draggableD12').css('transform', 'rotate(0deg)');
            jQuery('.chart').data('easyPieChart').update(15);
            jQuery('.chart').find('span').text('15');
//фаза 2
            reloadTime = 0;
            cur_animation_val = 0;
            d12Val = 0;
            count_animation = 1;
            phaseOne = setInterval(function(){
                if (count_animation <= 200){                                                                         //56
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
                            background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat',
                            transform: 'rotate('+d12Val+'deg)',
                            borderColor: 'transparent'
                        });;
                    count_animation += 1;
                    if(count_animation <= 100){
                        cur_animation_val += 1.5;
                        d12Val+= 9;
                        jQuery('.box_rounded').css('transform', 'rotate(-'+cur_animation_val+'deg) scale(1)');
                    } else {
                        cur_animation_val -= 1.5;
                        d12Val+= 9;
                        jQuery('.box_rounded').css('transform', 'rotate(-'+cur_animation_val+'deg) scale(1)');
                        jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
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
                    }, 250);
                    jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                    jQuery('#draggableD12').css('transform', 'rotate(0deg)');
                    jQuery('.chart').data('easyPieChart').update(30);
                    jQuery('.chart').find('span').text('30');
        //фаза 3
                    reloadTime = 0;
                    cur_animation_val = 0;
                    d12Val = 0;
                    count_animation = 1;
                    phaseOne = setInterval(function(){
                        if (count_animation <= 200){                                                                         //56
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
                                    background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat',
                                    transform: 'rotate('+d12Val+'deg)',
                                    borderColor: 'transparent'
                                });;
                            count_animation += 1;
                            if(count_animation <= 100){
                                cur_animation_val += 1.5;
                                d12Val+= 9;
                                jQuery('.box_rounded').css('transform', 'rotate(-'+cur_animation_val+'deg) scale(1)');
                            } else {
                                cur_animation_val -= 1.5;
                                d12Val+= 9;
                                jQuery('.box_rounded').css('transform', 'rotate(-'+cur_animation_val+'deg) scale(1)');
                                jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
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
                            }, 250);
                            jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                            jQuery('#draggableD12').css('transform', 'rotate(0deg)');
                            jQuery('.chart').data('easyPieChart').update(45);
                            jQuery('.chart').find('span').text('45');
                //фаза 4
                            reloadTime = 0;
                            cur_animation_val = 0;
                            d12Val = 0;
                            count_animation = 1;
                            phaseOne = setInterval(function(){
                                if (count_animation <= 200){                                                                         //56
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
                                            background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat',
                                            transform: 'rotate('+d12Val+'deg)',
                                            borderColor: 'transparent'
                                        });;
                                    count_animation += 1;
                                    if(count_animation <= 100){
                                        cur_animation_val += 1.5;
                                        d12Val+= 9;
                                        jQuery('.box_rounded').css('transform', 'rotate(-'+cur_animation_val+'deg) scale(1)');
                                    } else {
                                        cur_animation_val -= 1.5;
                                        d12Val+= 9;
                                        jQuery('.box_rounded').css('transform', 'rotate(-'+cur_animation_val+'deg) scale(1)');
                                        jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
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
                                    }, 250);
                                    jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                                    jQuery('#draggableD12').css('transform', 'rotate(0deg)');
                                    jQuery('.chart').data('easyPieChart').update(60);
                                    jQuery('.chart').find('span').text('60');
                        //фаза 5
                                    reloadTime = 0;
                                    cur_animation_val = 0;
                                    d12Val = 0;
                                    count_animation = 1;
                                    phaseOne = setInterval(function(){
                                        if (count_animation <= 200){                                                                         //56
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
                                                    background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat',
                                                    transform: 'rotate('+d12Val+'deg)',
                                                    borderColor: 'transparent'
                                                });;
                                            count_animation += 1;
                                            if(count_animation <= 100){
                                                cur_animation_val += 1.5;
                                                d12Val+= 9;
                                                jQuery('.box_rounded').css('transform', 'rotate(-'+cur_animation_val+'deg) scale(1)');
                                            } else {
                                                cur_animation_val -= 1.5;
                                                d12Val+= 9;
                                                jQuery('.box_rounded').css('transform', 'rotate(-'+cur_animation_val+'deg) scale(1)');
                                                jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                            }
                                        } else {
                                            clearInterval(phaseOne);
                                            tickSound.stop();
                                            count_animation = 1;
                                            jQuery('#draggableD5, #draggableD5_1').css({
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
                                            }, 250);
                                            jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                                            jQuery('#draggableD12').css('transform', 'rotate(0deg)');
                                            jQuery('.chart').data('easyPieChart').update(75);
                                            jQuery('.chart').find('span').text('75');
                                //фаза 6
                                            reloadTime = 0;
                                            cur_animation_val = 0;
                                            d12Val = 0;
                                            count_animation = 1;
                                            phaseOne = setInterval(function(){
                                                if (count_animation <= 200){                                                                         //56
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
                                                            background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat',
                                                            transform: 'rotate('+d12Val+'deg)',
                                                            borderColor: 'transparent'
                                                        });;
                                                    count_animation += 1;
                                                    if(count_animation <= 100){
                                                        cur_animation_val += 1.5;
                                                        d12Val+= 9;
                                                        jQuery('.box_rounded').css('transform', 'rotate(-'+cur_animation_val+'deg) scale(1)');
                                                    } else {
                                                        cur_animation_val -= 1.5;
                                                        d12Val+= 9;
                                                        jQuery('.box_rounded').css('transform', 'rotate(-'+cur_animation_val+'deg) scale(1)');
                                                        jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                    }
                                                } else {
                                                    clearInterval(phaseOne);
                                                    tickSound.stop();
                                                    count_animation = 1;
                                                    jQuery('#draggableD6, #draggableD6_1').css({
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
                                                    }, 250);
                                                    jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                                                    jQuery('#draggableD12').css('transform', 'rotate(0deg)');
                                                    jQuery('.chart').data('easyPieChart').update(90);
                                                    jQuery('.chart').find('span').text('90');
                                        //фаза 7
                                                    reloadTime = 0;
                                                    cur_animation_val = 0;
                                                    d12Val = 0;
                                                    count_animation = 1;
                                                    phaseOne = setInterval(function(){
                                                        if (count_animation <= 200){                                                                         //56
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
                                                                    background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/daemon.png) 0 0/100% no-repeat',
                                                                    transform: 'rotate('+d12Val+'deg)',
                                                                    borderColor: 'transparent'
                                                                });;
                                                            count_animation += 1;
                                                            if(count_animation <= 100){
                                                                cur_animation_val += 1.5;
                                                                d12Val+= 9;
                                                                jQuery('.box_rounded').css('transform', 'rotate(-'+cur_animation_val+'deg) scale(1)');
                                                            } else {
                                                                cur_animation_val -= 1.5;
                                                                d12Val+= 9;
                                                                jQuery('.box_rounded').css('transform', 'rotate(-'+cur_animation_val+'deg) scale(1)');
                                                                jQuery('#draggableD12').css('transform', 'rotate('+d12Val+'deg)');
                                                            }
                                                        } else {
                                                            clearInterval(phaseOne);
                                                            tickSound.stop();
                                                            count_animation = 1;
                                                            jQuery('#draggableD7, #draggableD7_1').css({
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
                                                                }
                                                            }, 250);
                                                            tickSound.stop();
                                                            jQuery('.chart').data('easyPieChart').update(100);
                                                            jQuery('.chart').find('span').text('100');
                                                            jQuery('#draggableD12').addClass('hidden');
                                                            jQuery('.box_rounded').css('transform', 'rotate(0deg) scale(1)');
                                                            jQuery('#draggableD12').css('transform', 'rotate(0deg)');
                                                            onEnd();
                                                        }
                                                    }, 250);
                                                }
                                            }, 250);
                                        }
                                    }, 250);
                                }
                            }, 250);
                        }
                    }, 250);
                }
            }, 250);
        }
    }, 250);
};
