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
        v3;

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
    }

    duos = function(){
    //фаза 1
        cur_animation_val = 0;
        count_animation = 1;
        phaseOne = setInterval(function(){
            if (count_animation <= 130){                                                                         //130
                jQuery('#draggableD11, #draggableD11_1').css({
                    color: 'transparent',
                    borderColor: 'transparent',
                    opacity: 0.8,
                    transform: 'scale(1)',
                    borderWidth: '1px',
                    paddingTop: '4px',
                    background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/drenag.png) 0 0/100% no-repeat',
                    zIndex: '1000'
                });
                jQuery('#draggableD12')
                    .removeClass('hidden')
                    .css({
                        opacity: 0.8,
                        transform: 'scale(1)',
                        background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat',
                        borderColor: 'transparent'
                    });;
                count_animation += 1;
                if(count_animation <= 30){
                    cur_animation_val += 6;
                    jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                } else {
                    cur_animation_val -= 6;
                    jQuery('.box_rounded').css('transform', 'rotate('+cur_animation_val+'deg) scale(1)');
                }
            } else {
                clearInterval(phaseOne);
                count_animation = 1;
                jQuery('#draggable3').css({
                    background: 'transparent',
                    color: 'red',
                    borderColor: 'red',
                    opacity: 1,
                    transform: 'scale(0.5)',
                    borderWidth: '2px',
                    paddingTop: '2px',
                    zIndex: '1'
                });
                jQuery('#draggableD12').addClass('hidden');
                onEnd();
                jQuery( ".btn__wizard" )
                    .text('Выполнить')
                    .removeClass('btn__wizard_inAction');                
            }
        }, 1000);
    };
    v3 = function(){
    //фаза 1
        cur_animation_val = 0;
        count_animation = 1;
    //анимация против часовой стрелки
        jQuery('.triangle').css({
            transform: 'scale(0.2) rotateY(180deg) rotateZ(120deg)',
            left: '-180px',
            top: '-120px'
        });
        //анимация первого треугольника
        jQuery('#draggable1').addClass('transparent');
        firstTriangleAnimation = new Vivus('triangle_1', {type: 'delayed', duration: 150}, function(){
        //анимация второго треугольника
            setTimeout(function(){
                jQuery('#draggable2').addClass('transparent');
                secondTriangleAnimation = new Vivus('triangle_2', {type: 'delayed', duration: 150}, function(){
        //анимация третьего треугольника
                    setTimeout(function(){
                        jQuery('#draggable3').addClass('transparent');
                        thirdTriangleAnimation = new Vivus('triangle_3', {type: 'delayed', duration: 150}, function(){
        //анимация четвертого треугольника
                            setTimeout(function(){
                                jQuery('#draggable4').addClass('transparent');
                                fourthTriangleAnimation = new Vivus('triangle_4', {type: 'delayed', duration: 150}, function(){
                                    jQuery('.itemlist_item').removeClass('transparent');
                                //анимация по часовой стрелке
                                    jQuery('.triangle').css({
                                        transform: 'scale(0.2) rotateY(0deg) rotateZ(120deg)',
                                        left: '-170px',
                                        top: '-120px'
                                    });
                                    //анимация первого треугольника
                                    jQuery('#draggable1').addClass('transparent');                                                                    
                                    firstTriangleAnimation = new Vivus('triangle_1', {type: 'delayed', duration: 150}, function(){
                                    //анимация второго треугольника
                                        setTimeout(function(){
                                            jQuery('#draggable2').addClass('transparent');
                                            secondTriangleAnimation = new Vivus('triangle_2', {type: 'delayed', duration: 150}, function(){
                                    //анимация третьего треугольника
                                                setTimeout(function(){
                                                    jQuery('#draggable3').addClass('transparent');
                                                    thirdTriangleAnimation = new Vivus('triangle_3', {type: 'delayed', duration: 150}, function(){
                                    //анимация четвертого треугольника
                                                        setTimeout(function(){
                                                            jQuery('#draggable4').addClass('transparent');
                                                            fourthTriangleAnimation = new Vivus('triangle_4', {type: 'delayed', duration: 150}, function(){
                                                                jQuery('.itemlist_item').removeClass('transparent');
    //фаза 2
                                                                count_animation = 1;
                                                                phaseOne = setInterval(function(){
                                                                    if (count_animation <= 120){                                                                         //120
                                                                        cur_animation_val += 6;
                                                                        jQuery('#draggable1, #draggable4, #draggable5').css({
                                                                            background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/disfunction.jpg) 0 0/100% no-repeat'
                                                                        });
                                                                        jQuery('#draggableD1, #draggableD2, #draggableD4, #draggableD5').css({
                                                                            background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/drenag.png) 0 0/100% no-repeat'
                                                                        });
                                                                        jQuery('#draggable1, #draggable4, #draggable5, #draggableD1, #draggableD2, #draggableD4, #draggableD5').css({
                                                                            color: 'transparent',
                                                                            borderColor: 'transparent',
                                                                            opacity: 0.8,
                                                                            transform: 'scale(1)',
                                                                            borderWidth: '1px',
                                                                            paddingTop: '4px',
                                                                            zIndex: '1000'
                                                                        });
                                                                        count_animation += 1;

                                                                    } else {
                                                                        clearInterval(phaseOne);
                                                                        count_animation = 1;
                                                                        jQuery('#draggable1, #draggable4, #draggable5, #draggableD1, #draggableD2, #draggableD4, #draggableD5').css({
                                                                            background: 'transparent',
                                                                            color: 'red',
                                                                            borderColor: 'red',
                                                                            opacity: 1,
                                                                            transform: 'scale(0.5)',
                                                                            borderWidth: '2px',
                                                                            paddingTop: '2px',
                                                                            zIndex: '1'
                                                                        });
    //фаза 3
                                                                        count_animation = 1;
                                                                        jQuery('#draggableClean_2').css({
                                                                                left: parseFloat(jQuery('#draggableS3').css('left'))+70+'px',
                                                                                top: parseFloat(jQuery('#draggableS3').css('top'))-561+'px'
                                                                        });
                                                                        jQuery('#draggableClean_3').css({
                                                                                left: parseFloat(jQuery('#draggableS4').css('left'))+70+'px',
                                                                                top: parseFloat(jQuery('#draggableS4').css('top'))-561+'px'
                                                                        });
                                                                        jQuery('#draggableClean_4').css({
                                                                                left: parseFloat(jQuery('#draggableS5').css('left'))+70+'px',
                                                                                top: parseFloat(jQuery('#draggableS5').css('top'))-561+'px'
                                                                        });
                                                                        jQuery('#draggableClean_2, #draggableClean_3, #draggableClean_4').removeClass('inopaciti');
                                                                        phaseOne = setInterval(function(){
                                                                            if (count_animation <= 120){                                                                         //120
                                                                                cur_animation_val += 6;
                                                                                jQuery('#draggable3, #draggableD3, #draggableD1, #draggableClean_2, #draggableClean_3, #draggableClean_4, #draggableVD').css({
                                                                                    color: 'transparent',
                                                                                    borderColor: 'transparent',
                                                                                    opacity: 0.8,
                                                                                    transform: 'scale(1)',
                                                                                    borderWidth: '1px',
                                                                                    paddingTop: '4px',
                                                                                    zIndex: '1000'
                                                                                });
                                                                                jQuery('#draggableD3, #draggable3').css({
                                                                                    background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/disfunction.jpg) 0 0/100% no-repeat'
                                                                                });
                                                                                jQuery('#draggableClean_2, #draggableClean_3, #draggableClean_4, #draggableVD, #draggableD1').css({
                                                                                    transform: 'rotate(-'+cur_animation_val+'deg) scale(1)',
                                                                                    background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat'
                                                                                });
                                                                                count_animation += 1;
                                                                            } else {
                                                                                clearInterval(phaseOne);
                                                                                count_animation = 1;
                                                                                jQuery('#draggable3, #draggableD3, #draggableS3, #draggableS4, #draggableS5, #draggableVD, #draggableD1').css({
                                                                                    background: 'transparent',
                                                                                    color: 'red',
                                                                                    borderColor: 'red',
                                                                                    opacity: 1,
                                                                                    transform: 'scale(0.5)',
                                                                                    borderWidth: '2px',
                                                                                    paddingTop: '2px',
                                                                                    zIndex: '1'
                                                                                });
                                                                                jQuery('#draggableClean_2, #draggableClean_3, #draggableClean_4').addClass('inopaciti');
    //фаза 4
                                                                                count_animation = 1;
                                                                                cur_animation_val = 0;
                                                                                phaseOne = setInterval(function(){
                                                                                    if (count_animation <= 120){                                                                         //120
                                                                                        jQuery('#draggableS3, #draggableS4, #draggableS5, #draggableS6').css({
                                                                                            transform: 'scale(1)',
                                                                                            borderWidth: '1px',
                                                                                            paddingTop: '4px',
                                                                                            zIndex: '1000',
                                                                                            color: 'transparent'
                                                                                        });
                                                                                        jQuery('#draggableS3').css({
                                                                                            background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/travma.jpg) 0 0/100% no-repeat'
                                                                                        });
                                                                                        jQuery('#draggableS4, #draggableS5, #draggableS6').css({
                                                                                            background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/povregdenie_demona.jpg) 0 0/100% no-repeat'
                                                                                        });
                                                                                        if (count_animation >= 60 && count_animation <= 120){                               //60
                                                                                            jQuery('#draggableS3, #draggableS4, #draggableS5, #draggableS6').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/filtr.jpg) 0 0/100% no-repeat');
                                                                                        }
                                                                                        count_animation += 1;

                                                                                    } else {
                                                                                        clearInterval(phaseOne);
                                                                                        count_animation = 1;
                                                                                        jQuery('#draggableS3, #draggableS4, #draggableS5, #draggableS6').css({
                                                                                            background: 'transparent',
                                                                                            color: 'red',
                                                                                            borderColor: 'red',
                                                                                            opacity: 1,
                                                                                            transform: 'scale(0.5)',
                                                                                            borderWidth: '2px',
                                                                                            paddingTop: '2px',
                                                                                            zIndex: '1'
                                                                                        });
                                                                                        jQuery('#draggableVD').css({
                                                                                            transform: 'rotate(-'+cur_animation_val+'deg) scale(1)',
                                                                                            background: 'transparent',
                                                                                            borderWidth: '2px',
                                                                                            paddingTop: '2px',
                                                                                            transform: 'scale(0.5)',
                                                                                            zIndex: '1',
                                                                                            color: 'red'
                                                                                        });
    //фаза 5
                                                                                        count_animation = 1;
                                                                                        cur_animation_val = 0;
                                                                                        phaseOne = setInterval(function(){
                                                                                            if (count_animation <= 1){                                                                         //1
                                                                                                count_animation += 1;

                                                                                            } else {
                                                                                                clearInterval(phaseOne);
    //Фаза 6
                                                                                                cur_animation_val = 0;
                                                                                                count_animation = 1;
                                                                                            //анимация против часовой стрелки
                                                                                                jQuery('.triangle').css({
                                                                                                    transform: 'scale(0.2) rotateY(180deg) rotateZ(120deg)',
                                                                                                    left: '-180px',
                                                                                                    top: '-120px'
                                                                                                });
                                                                                                //анимация первого треугольника
                                                                                                jQuery('#draggable1').addClass('transparent');
                                                                                                firstTriangleAnimation = new Vivus('triangle_1', {type: 'delayed', duration: 150}, function(){
                                                                                                //анимация второго треугольника
                                                                                                    setTimeout(function(){
                                                                                                        jQuery('#draggable2').addClass('transparent');
                                                                                                        secondTriangleAnimation = new Vivus('triangle_2', {type: 'delayed', duration: 150}, function(){
                                                                                                //анимация третьего треугольника
                                                                                                            setTimeout(function(){
                                                                                                                jQuery('#draggable3').addClass('transparent');
                                                                                                                thirdTriangleAnimation = new Vivus('triangle_3', {type: 'delayed', duration: 150}, function(){
                                                                                                //анимация четвертого треугольника
                                                                                                                    setTimeout(function(){
                                                                                                                        jQuery('#draggable4').addClass('transparent');
                                                                                                                        fourthTriangleAnimation = new Vivus('triangle_4', {type: 'delayed', duration: 150}, function(){
                                                                                                                            jQuery('.itemlist_item').removeClass('transparent');
                                                                                                                        //анимация по часовой стрелке
                                                                                                                            jQuery('.triangle').css({
                                                                                                                                transform: 'scale(0.2) rotateY(0deg) rotateZ(120deg)',
                                                                                                                                left: '-170px',
                                                                                                                                top: '-120px'
                                                                                                                            });
                                                                                                                            //анимация первого треугольника
                                                                                                                            jQuery('#draggable1').addClass('transparent');                                                                    
                                                                                                                            firstTriangleAnimation = new Vivus('triangle_1', {type: 'delayed', duration: 150}, function(){
                                                                                                                            //анимация второго треугольника
                                                                                                                                setTimeout(function(){
                                                                                                                                    jQuery('#draggable2').addClass('transparent');
                                                                                                                                    secondTriangleAnimation = new Vivus('triangle_2', {type: 'delayed', duration: 150}, function(){
                                                                                                                            //анимация третьего треугольника
                                                                                                                                        setTimeout(function(){
                                                                                                                                            jQuery('#draggable3').addClass('transparent');
                                                                                                                                            thirdTriangleAnimation = new Vivus('triangle_3', {type: 'delayed', duration: 150}, function(){
                                                                                                                            //анимация четвертого треугольника
                                                                                                                                                setTimeout(function(){
                                                                                                                                                    jQuery('#draggable4').addClass('transparent');
                                                                                                                                                    fourthTriangleAnimation = new Vivus('triangle_4', {type: 'delayed', duration: 150}, function(){
                                                                                                                                                        jQuery('.itemlist_item').removeClass('transparent');
    //Фаза 7
                                                                                                                                            //Этап 7-1
                                                                                                                                                        cur_animation_val = 30;
                                                                                                                                                        count_animation = 1;
                                                                                                                                                        phaseSeven_one = setInterval(function(){
                                                                                                                                                            if (count_animation <= 36){                                                                         //18
                                                                                                                                                                cur_animation_val += 1.5;
                                                                                                                                                                jQuery('#draggable5').css({
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

                                                                                                                                                            } else if(count_animation <= 96) {                                                         //156
                                                                                                                                                                count_animation += 1;

                                                                                                                                                                // console.log('7-1'+ count_animation+ '/138');
                                                                                                                                                            } else {    
                                                                                                                                                                clearInterval(phaseSeven_one);
                                                                                                                                                                count_animation = 1;
                                                                                                                                                                jQuery('#draggable5').css({
                                                                                                                                                                    transform: 'rotate(-'+0+'deg) scale(0.5)',
                                                                                                                                                                    background: 'transparent',
                                                                                                                                                                    color: 'red',
                                                                                                                                                                    borderColor: 'red',
                                                                                                                                                                    opacity: 1,
                                                                                                                                                                    borderWidth: '2px',
                                                                                                                                                                    paddingTop: '2px',
                                                                                                                                                                    zIndex: '1'
                                                                                                                                                                });
                                                                                                                                            //Этап 7-2
                                                                                                                                                                cur_animation_val = 90;
                                                                                                                                                                count_animation = 1;
                                                                                                                                                                phaseSeven_one = setInterval(function(){
                                                                                                                                                                    if (count_animation <= 28){                                                                         //14
                                                                                                                                                                        cur_animation_val -= 3;
                                                                                                                                                                        jQuery('#draggable5').css({
                                                                                                                                                                            transform: 'rotate('+cur_animation_val+'deg) scale(1)',
                                                                                                                                                                            background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/mo_right.png) 0 0/100% no-repeat',
                                                                                                                                                                            color: 'transparent',
                                                                                                                                                                            borderColor: 'transparent',
                                                                                                                                                                            opacity: 0.8,
                                                                                                                                                                            borderWidth: '1px',
                                                                                                                                                                            paddingTop: '4px',
                                                                                                                                                                            zIndex: '1000'
                                                                                                                                                                        });
                                                                                                                                                                        count_animation += 1;

                                                                                                                                                                    } else if(count_animation <= 88) {                                                         //148
                                                                                                                                                                        count_animation += 1;

                                                                                                                                                                        console.log('7-2'+ count_animation+ '/134');
                                                                                                                                                                    } else {    
                                                                                                                                                                        clearInterval(phaseSeven_one);
                                                                                                                                                                        count_animation = 1;
                                                                                                                                                                        jQuery('#draggable5').css({
                                                                                                                                                                            transform: 'rotate(-'+0+'deg) scale(0.5)',
                                                                                                                                                                            background: 'transparent',
                                                                                                                                                                            color: 'red',
                                                                                                                                                                            borderColor: 'red',
                                                                                                                                                                            opacity: 1,
                                                                                                                                                                            borderWidth: '2px',
                                                                                                                                                                            paddingTop: '2px',
                                                                                                                                                                            zIndex: '1'
                                                                                                                                                                        });
                                                                                                                                            //Этап 7-3
                                                                                                                                                                        cur_animation_val = 10;
                                                                                                                                                                        count_animation = 1;
                                                                                                                                                                        phaseSeven_one = setInterval(function(){
                                                                                                                                                                            if (count_animation <= 26){                                                                         //13
                                                                                                                                                                                cur_animation_val += 3;
                                                                                                                                                                                jQuery('#draggable5').css({
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

                                                                                                                                                                            } else if(count_animation <= 86) {                                                         //146
                                                                                                                                                                                count_animation += 1;

                                                                                                                                                                                jQuery('#draggable5').css({
                                                                                                                                                                                    transform: 'rotate('+90+'deg) scale(1)',
                                                                                                                                                                                });
                                                                                                                                                                                console.log('7-3'+ count_animation+ '/133');
                                                                                                                                                                            } else {    
                                                                                                                                                                                clearInterval(phaseSeven_one);
                                                                                                                                                                                count_animation = 1;
                                                                                                                                                                                jQuery('#draggable5').css({
                                                                                                                                                                                    transform: 'rotate(-'+0+'deg) scale(0.5)',
                                                                                                                                                                                    background: 'transparent',
                                                                                                                                                                                    color: 'red',
                                                                                                                                                                                    borderColor: 'red',
                                                                                                                                                                                    opacity: 1,
                                                                                                                                                                                    borderWidth: '2px',
                                                                                                                                                                                    paddingTop: '2px',
                                                                                                                                                                                    zIndex: '1'
                                                                                                                                                                                });
                                                                                                                                            //Этап 7-4
                                                                                                                                                                                cur_animation_val = 90;
                                                                                                                                                                                count_animation = 1;
                                                                                                                                                                                phaseSeven_one = setInterval(function(){
                                                                                                                                                                                    if (count_animation <= 30){                                                                         //15
                                                                                                                                                                                        cur_animation_val -= 3;
                                                                                                                                                                                        jQuery('#draggable3').css({
                                                                                                                                                                                            transform: 'rotate(-'+cur_animation_val+'deg) scale(1)',
                                                                                                                                                                                            background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/mo_left.png) 0 0/100% no-repeat',
                                                                                                                                                                                            color: 'transparent',
                                                                                                                                                                                            borderColor: 'transparent',
                                                                                                                                                                                            opacity: 0.8,
                                                                                                                                                                                            borderWidth: '1px',
                                                                                                                                                                                            paddingTop: '4px',
                                                                                                                                                                                            zIndex: '1000'
                                                                                                                                                                                        });
                                                                                                                                                                                        count_animation += 1;

                                                                                                                                                                                    } else if(count_animation <= 90) {                                                         //150
                                                                                                                                                                                        count_animation += 1;

                                                                                                                                                                                        console.log('7-4'+ count_animation+ '/135');
                                                                                                                                                                                    } else {    
                                                                                                                                                                                        clearInterval(phaseSeven_one);
                                                                                                                                                                                        count_animation = 1;
                                                                                                                                                                                        jQuery('#draggable3').css({
                                                                                                                                                                                            transform: 'rotate(-'+0+'deg) scale(0.5)',
                                                                                                                                                                                            background: 'transparent',
                                                                                                                                                                                            color: 'red',
                                                                                                                                                                                            borderColor: 'red',
                                                                                                                                                                                            opacity: 1,
                                                                                                                                                                                            borderWidth: '2px',
                                                                                                                                                                                            paddingTop: '2px',
                                                                                                                                                                                            zIndex: '1'
                                                                                                                                                                                        });
                                                                                                                                            //Этап 7-5
                                                                                                                                                                                        cur_animation_val = 30;
                                                                                                                                                                                        count_animation = 1;
                                                                                                                                                                                        phaseSeven_one = setInterval(function(){
                                                                                                                                                                                            if (count_animation <= 36){                                                                         //18
                                                                                                                                                                                                cur_animation_val += 1.5;
                                                                                                                                                                                                jQuery('#draggable1').css({
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

                                                                                                                                                                                            } else if(count_animation <= 96) {                                                         //156
                                                                                                                                                                                                count_animation += 1;

                                                                                                                                                                                                console.log('7-5'+ count_animation+ '/122');
                                                                                                                                                                                            } else {    
                                                                                                                                                                                                clearInterval(phaseSeven_one);
                                                                                                                                                                                                count_animation = 1;
                                                                                                                                                                                                jQuery('#draggable1').css({
                                                                                                                                                                                                    transform: 'rotate(-'+0+'deg) scale(0.5)',
                                                                                                                                                                                                    background: 'transparent',
                                                                                                                                                                                                    color: 'red',
                                                                                                                                                                                                    borderColor: 'red',
                                                                                                                                                                                                    opacity: 1,
                                                                                                                                                                                                    borderWidth: '2px',
                                                                                                                                                                                                    paddingTop: '2px',
                                                                                                                                                                                                    zIndex: '1'
                                                                                                                                                                                                });
                                                                                                                                            //Этап 7-6
                                                                                                                                                                                                cur_animation_val = 10;
                                                                                                                                                                                                count_animation = 1;
                                                                                                                                                                                                phaseSeven_one = setInterval(function(){
                                                                                                                                                                                                    if (count_animation <= 26){                                                                         //13
                                                                                                                                                                                                        cur_animation_val += 3;
                                                                                                                                                                                                        jQuery('#draggable1').css({
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

                                                                                                                                                                                                    } else if(count_animation <= 96) {                                                         //156
                                                                                                                                                                                                        count_animation += 1
                                                                                                                                                                                                        jQuery('#draggable1').css({
                                                                                                                                                                                                            transform: 'rotate('+90+'deg) scale(1)'
                                                                                                                                                                                                        });
                                                                                                                                                                                                        console.log('7-6'+ count_animation+ '/133');
                                                                                                                                                                                                    } else {    
                                                                                                                                                                                                        clearInterval(phaseSeven_one);
                                                                                                                                                                                                        count_animation = 1;
                                                                                                                                                                                                        jQuery('#draggable1').css({
                                                                                                                                                                                                            transform: 'rotate(-'+0+'deg) scale(0.5)',
                                                                                                                                                                                                            background: 'transparent',
                                                                                                                                                                                                            color: 'red',
                                                                                                                                                                                                            borderColor: 'red',
                                                                                                                                                                                                            opacity: 1,
                                                                                                                                                                                                            borderWidth: '2px',
                                                                                                                                                                                                            paddingTop: '2px',
                                                                                                                                                                                                            zIndex: '1'
                                                                                                                                                                                                        });
    //Фаза 8
                                                                                                                                                                                                        cur_animation_val = 0;
                                                                                                                                                                                                        count_animation = 1;
                                                                                                                                                                                                    //анимация против часовой стрелки
                                                                                                                                                                                                        jQuery('.triangle').css({
                                                                                                                                                                                                            transform: 'scale(0.2) rotateY(180deg)rotateZ(120deg)',
                                                                                                                                                                                                            left: '-180px',
                                                                                                                                                                                                            top: '-120px'
                                                                                                                                                                                                        });
                                                                                                                                                                                                        //анимация первого треугольника
                                                                                                                                                                                                        jQuery('#draggable1').addClass('transparent');
                                                                                                                                                                                                        firstTriangleAnimation = new Vivus('triangle_1', {type: 'delayed', duration: 150}, function(){
                                                                                                                                                                                                        //анимация второго треугольника
                                                                                                                                                                                                            setTimeout(function(){
                                                                                                                                                                                                                jQuery('#draggable2').addClass('transparent');
                                                                                                                                                                                                                secondTriangleAnimation = new Vivus('triangle_2', {type: 'delayed', duration: 150}, function(){
                                                                                                                                                                                                        //анимация третьего треугольника
                                                                                                                                                                                                                    setTimeout(function(){
                                                                                                                                                                                                                        jQuery('#draggable3').addClass('transparent');
                                                                                                                                                                                                                        thirdTriangleAnimation = new Vivus('triangle_3', {type: 'delayed', duration: 150}, function(){
                                                                                                                                                                                                        //анимация четвертого треугольника
                                                                                                                                                                                                                            setTimeout(function(){
                                                                                                                                                                                                                                jQuery('#draggable4').addClass('transparent');
                                                                                                                                                                                                                                fourthTriangleAnimation = new Vivus('triangle_4', {type: 'delayed', duration: 150}, function(){
                                                                                                                                                                                                                                    jQuery('.itemlist_item').removeClass('transparent');


                                                                                                                                                                                                                                //анимация по часовой стрелке
                                                                                                                                                                                                                                    jQuery('.triangle').css({
                                                                                                                                                                                                                                        transform: 'scale(0.2) rotateY(0deg)rotateZ(120deg)',
                                                                                                                                                                                                                                        left: '-170px',
                                                                                                                                                                                                                                        top: '-120px'
                                                                                                                                                                                                                                    });
                                                                                                                                                                                                                                    //анимация первого треугольника
                                                                                                                                                                                                                                    jQuery('#draggable1').addClass('transparent');                                                                    
                                                                                                                                                                                                                                    firstTriangleAnimation = new Vivus('triangle_1', {type: 'delayed', duration: 150}, function(){
                                                                                                                                                                                                                                    //анимация второго треугольника
                                                                                                                                                                                                                                        setTimeout(function(){
                                                                                                                                                                                                                                            jQuery('#draggable2').addClass('transparent');
                                                                                                                                                                                                                                            secondTriangleAnimation = new Vivus('triangle_2', {type: 'delayed', duration: 150}, function(){
                                                                                                                                                                                                                                    //анимация третьего треугольника
                                                                                                                                                                                                                                                setTimeout(function(){
                                                                                                                                                                                                                                                    jQuery('#draggable3').addClass('transparent');
                                                                                                                                                                                                                                                    thirdTriangleAnimation = new Vivus('triangle_3', {type: 'delayed', duration: 150}, function(){
                                                                                                                                                                                                                                    //анимация четвертого треугольника
                                                                                                                                                                                                                                                        setTimeout(function(){
                                                                                                                                                                                                                                                            jQuery('#draggable4').addClass('transparent');
                                                                                                                                                                                                                                                            fourthTriangleAnimation = new Vivus('triangle_4', {type: 'delayed', duration: 150}, function(){
                                                                                                                                                                                                                                                                jQuery('.itemlist_item').removeClass('transparent');
                                                                        //фаза 9
                                                                                                                                                                                                                                                                count_animation = 1;
                                                                                                                                                                                                                                                                phaseOne = setInterval(function(){
                                                                                                                                                                                                                                                                    if (count_animation <= 120){                                                                         //120
                                                                                                                                                                                                                                                                        jQuery('#draggable3').css({
                                                                                                                                                                                                                                                                            color: 'transparent',
                                                                                                                                                                                                                                                                            borderColor: 'transparent',
                                                                                                                                                                                                                                                                            opacity: 0.8,
                                                                                                                                                                                                                                                                            transform: 'scale(1)',
                                                                                                                                                                                                                                                                            borderWidth: '1px',
                                                                                                                                                                                                                                                                            paddingTop: '4px',
                                                                                                                                                                                                                                                                            background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/edinenie_s_tvorcom.jpg) 0 0/100% no-repeat',
                                                                                                                                                                                                                                                                            zIndex: '1000'
                                                                                                                                                                                                                                                                        });
                                                                                                                                                                                                                                                                        count_animation += 1;

                                                                                                                                                                                                                                                                    } else {
                                                                                                                                                                                                                                                                        clearInterval(phaseOne);
                                                                                                                                                                                                                                                                        count_animation = 1;
                                                                                                                                                                                                                                                                        jQuery('#draggable3').css({
                                                                                                                                                                                                                                                                            background: 'transparent',
                                                                                                                                                                                                                                                                            color: 'red',
                                                                                                                                                                                                                                                                            borderColor: 'red',
                                                                                                                                                                                                                                                                            opacity: 1,
                                                                                                                                                                                                                                                                            transform: 'scale(0.5)',
                                                                                                                                                                                                                                                                            borderWidth: '2px',
                                                                                                                                                                                                                                                                            paddingTop: '2px',
                                                                                                                                                                                                                                                                            zIndex: '1'
                                                                                                                                                                                                                                                                        });
                                                                                                                                                                                                                                                                        onEnd();
                                                                                                                                                                                                                                                                        jQuery( ".btn__wizard" )
                                                                                                                                                                                                                                                                            .text('Выполнить')
                                                                                                                                                                                                                                                                            .removeClass('btn__wizard_inAction');        
                                                                                                                                                                                                                                                                    }
                                                                                                                                                                                                                                                                }, 1000);
                                                                                                                                                                                                                                                            });
                                                                                                                                                                                                                                                            fourthTriangleAnimation.play();
                                                                                                                                                                                                                                                        }, 250)
                                                                                                                                                                                                                                                    });
                                                                                                                                                                                                                                                    thirdTriangleAnimation.play();
                                                                                                                                                                                                                                                }, 250)
                                                                                                                                                                                                                                            });
                                                                                                                                                                                                                                            secondTriangleAnimation.play();
                                                                                                                                                                                                                                        }, 250)
                                                                                                                                                                                                                                    });
                                                                                                                                                                                                                                    firstTriangleAnimation.play();
                                                                                                                                                                                                                                });
                                                                                                                                                                                                                                fourthTriangleAnimation.play();
                                                                                                                                                                                                                            }, 250)
                                                                                                                                                                                                                        });
                                                                                                                                                                                                                        thirdTriangleAnimation.play();
                                                                                                                                                                                                                    }, 250)
                                                                                                                                                                                                                });
                                                                                                                                                                                                                secondTriangleAnimation.play();
                                                                                                                                                                                                            }, 250)
                                                                                                                                                                                                        });
                                                                                                                                                                                                        firstTriangleAnimation.play();
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
                                                                                                                                                    });
                                                                                                                                                    fourthTriangleAnimation.play();
                                                                                                                                                }, 250)
                                                                                                                                            });
                                                                                                                                            thirdTriangleAnimation.play();
                                                                                                                                        }, 250)
                                                                                                                                    });
                                                                                                                                    secondTriangleAnimation.play();
                                                                                                                                }, 250)
                                                                                                                            });
                                                                                                                            firstTriangleAnimation.play();
                                                                                                                        });
                                                                                                                        fourthTriangleAnimation.play();
                                                                                                                    }, 250)
                                                                                                                });
                                                                                                                thirdTriangleAnimation.play();
                                                                                                            }, 250)
                                                                                                        });
                                                                                                        secondTriangleAnimation.play();
                                                                                                    }, 250)
                                                                                                });
                                                                                                firstTriangleAnimation.play();
                                                                                            }
                                                                                        }, 1000);
                                                                                    }
                                                                                }, 1000);
                                                                            }
                                                                        }, 1000);
                                                                    }
                                                                }, 1000);
                                                            });
                                                            fourthTriangleAnimation.play();
                                                        }, 250)
                                                    });
                                                    thirdTriangleAnimation.play();
                                                }, 250)
                                            });
                                            secondTriangleAnimation.play();
                                        }, 250)
                                    });
                                    firstTriangleAnimation.play();
                                });
                                fourthTriangleAnimation.play();
                            }, 250)
                        });
                        thirdTriangleAnimation.play();
                    }, 250)
                });
                secondTriangleAnimation.play();
            }, 250)
        });
        firstTriangleAnimation.play();
    }
    v4 = function(){
    //фаза 1
        count_animation = 1;
        phaseOne = setInterval(function(){
            if (count_animation <= 120){                                                                         //120
                cur_animation_val += 6;
                jQuery('#draggableClean, #draggableS1, #draggableS2_1, #draggableS4, #draggableD1, #draggableS5').css({
                    transform: 'scale(1)',
                    borderWidth: '1px',
                    color: 'transparent',
                    borderColor: 'transparent',
                    opacity: 0.8,
                    paddingTop: '4px',
                    zIndex: '1000'
                });
                jQuery('#draggableClean, #draggableS1, #draggableS2_1, #draggableS4, #draggableD1, #draggableS5').css({
                    transform: 'rotate(-'+cur_animation_val+'deg) scale(1)',
                    background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat'
                });
                count_animation += 1;
            } else {
                clearInterval(phaseOne);
                count_animation = 1;
                jQuery('#draggableClean, #draggableS1, #draggableS2_1, #draggableS4, #draggableD1, #draggableS5').css({
                    background: 'transparent',
                    transform: 'scale(0.5)',
                    color: 'red',
                    borderColor: 'red',
                    opacity: 1,
                    borderWidth: '2px',
                    paddingTop: '2px',
                    zIndex: '1'
                });
                count_animation = 1;
    //фаза 2
                count_animation = 1;
                cur_animation_val = 0;
        //2-1
                phaseOne = setInterval(function(){
                    if (count_animation <= 9){                                                                         //9
                        jQuery('#draggable1').css({
                            transform: 'scale(1)',
                            borderWidth: '1px',
                            color: 'transparent',
                            borderColor: 'transparent',
                            opacity: 0.8,
                            paddingTop: '4px',
                            zIndex: '1000'
                        });
                        if (count_animation >= 0 && count_animation <= 1){
                            jQuery('#draggable1').css( 'background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/simvoly.001.jpg) center center/100% no-repeat');
                        } else if (count_animation > 1 && count_animation <= 2) {
                            jQuery('#draggable1').css( 'background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/simvoly.002.jpg) center center/100% no-repeat');
                        } else if (count_animation > 2 && count_animation <= 3) {
                            jQuery('#draggable1').css( 'background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/simvoly.004.jpg) center center/100% no-repeat');
                        } else if (count_animation > 3 && count_animation <= 4) {
                            jQuery('#draggable1').css( 'background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/simvoly.005.jpg) center center/100% no-repeat');
                        } else if (count_animation > 4 && count_animation <= 5) {
                            jQuery('#draggable1').css( 'background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/simvoly.006.jpg) center center/100% no-repeat');
                        } else if (count_animation > 5 && count_animation <= 6) {
                            jQuery('#draggable1').css( 'background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/simvoly.007.jpg) center center/100% no-repeat');
                        } else if (count_animation > 6 && count_animation <= 7) {
                            jQuery('#draggable1').css( 'background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/simvoly.008.jpg) center center/100% no-repeat');
                        } else {
                            jQuery('#draggable1').css( 'background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/simvoly.009.jpg) center center/100% no-repeat');
                        };
                        count_animation += 1;
                    } else {
                        clearInterval(phaseOne);
                        count_animation = 1;
                        jQuery('#draggable1').css({
                            background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/disfunction.jpg) 0 0/100% no-repeat'
                        });
                //2-2
                        cur_animation_val = 0;                                                                       
                        count_animation = 1;
                        phaseOne = setInterval(function(){
                            if (count_animation <= 120){  
                                cur_animation_val += 6;                                                                       //120
                                jQuery('#draggable4').css({
                                    transform: 'rotate(-'+cur_animation_val+'deg) scale(1)',
                                    background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat',
                                    borderWidth: '1px',
                                    color: 'transparent',
                                    borderColor: 'transparent',
                                    opacity: 0.8,
                                    paddingTop: '4px',
                                    zIndex: '1000'
                                });
                                count_animation += 1;
                            } else {
                                clearInterval(phaseOne);
                                count_animation = 1;
                                jQuery('#draggable4, #draggable1').css({
                                    background: 'transparent',
                                    transform: 'scale(0.5)',
                                    color: 'red',
                                    borderColor: 'red',
                                    opacity: 1,
                                    borderWidth: '2px',
                                    paddingTop: '2px',
                                    zIndex: '1'
                                });
    //Фаза 3
                                cur_animation_val = 0;
                                count_animation = 1;
                            //анимация против часовой стрелки
                                jQuery('.triangle').css({
                                    transform: 'scale(0.2) rotateY(180deg)rotateZ(120deg)',
                                    left: '-180px',
                                    top: '-120px'
                                });
                                //анимация первого треугольника
                                jQuery('#draggable1').addClass('transparent');
                                firstTriangleAnimation = new Vivus('triangle_1', {type: 'delayed', duration: 150}, function(){
                                //анимация второго треугольника
                                    setTimeout(function(){
                                        jQuery('#draggable2').addClass('transparent');
                                        secondTriangleAnimation = new Vivus('triangle_2', {type: 'delayed', duration: 150}, function(){
                                //анимация третьего треугольника
                                            setTimeout(function(){
                                                jQuery('#draggable3').addClass('transparent');
                                                thirdTriangleAnimation = new Vivus('triangle_3', {type: 'delayed', duration: 150}, function(){
                                //анимация четвертого треугольника
                                                    setTimeout(function(){
                                                        jQuery('#draggable4').addClass('transparent');
                                                        fourthTriangleAnimation = new Vivus('triangle_4', {type: 'delayed', duration: 150}, function(){
                                                            jQuery('.itemlist_item').removeClass('transparent');


                                                        //анимация по часовой стрелке
                                                            jQuery('.triangle').css({
                                                                transform: 'scale(0.2) rotateY(0deg)rotateZ(120deg)',
                                                                left: '-170px',
                                                                top: '-120px'
                                                            });
                                                            //анимация первого треугольника
                                                            jQuery('#draggable1').addClass('transparent');                                                                    
                                                            firstTriangleAnimation = new Vivus('triangle_1', {type: 'delayed', duration: 150}, function(){
                                                            //анимация второго треугольника
                                                                setTimeout(function(){
                                                                    jQuery('#draggable2').addClass('transparent');
                                                                    secondTriangleAnimation = new Vivus('triangle_2', {type: 'delayed', duration: 150}, function(){
                                                            //анимация третьего треугольника
                                                                        setTimeout(function(){
                                                                            jQuery('#draggable3').addClass('transparent');
                                                                            thirdTriangleAnimation = new Vivus('triangle_3', {type: 'delayed', duration: 150}, function(){
                                                            //анимация четвертого треугольника
                                                                                setTimeout(function(){
                                                                                    jQuery('#draggable4').addClass('transparent');
                                                                                    fourthTriangleAnimation = new Vivus('triangle_4', {type: 'delayed', duration: 150}, function(){
                                                                                        jQuery('.itemlist_item').removeClass('transparent');
    // Фаза 4
                                                                                        count_animation = 1;
                                                                                        phaseOne = setInterval(function(){
                                                                                            if (count_animation <= 180){                                                             //180
                                                                                                jQuery('#draggableD5, #draggableD4, #draggable4, #draggableS4, #draggableS5, #draggableS6').css({
                                                                                                    transform: 'scale(1)',
                                                                                                    borderWidth: '1px',
                                                                                                    color: 'transparent',
                                                                                                    borderColor: 'transparent',
                                                                                                    opacity: 0.8,
                                                                                                    paddingTop: '4px',
                                                                                                    zIndex: '1000'
                                                                                                });
                                                                                                jQuery('#draggableD5, #draggableD4, #draggable4').css({
                                                                                                    background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/disfunction.jpg) 0 0/100% no-repeat'
                                                                                                });
                                                                                                jQuery('#draggableS4, #draggableS5, #draggableS6').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/travma.jpg) 0 0/100% no-repeat');
                                                                                                if (count_animation >= 60 && count_animation <= 120) {                               //60-120
                                                                                                    jQuery('#draggableS4, #draggableS5, #draggableS6').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/povregdenie_demona.jpg) 0 0/100% no-repeat');
                                                                                                } else if (count_animation >= 120 && count_animation <= 180) {                               //120-180
                                                                                                    jQuery('#draggableS4, #draggableS5, #draggableS6').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/filtr.jpg) 0 0/100% no-repeat');
                                                                                                }
                                                                                                count_animation += 1;
                                                                                            } else {
                                                                                                clearInterval(phaseOne);
                                                                                                count_animation = 1;
                                                                                                jQuery('#draggableD5, #draggableD4, #draggable4, #draggableS4, #draggableS5, #draggableS6').css({
                                                                                                    background: 'transparent',
                                                                                                    transform: 'scale(0.5)',
                                                                                                    color: 'red',
                                                                                                    borderColor: 'red',
                                                                                                    opacity: 1,
                                                                                                    borderWidth: '2px',
                                                                                                    paddingTop: '2px',
                                                                                                    zIndex: '1'
                                                                                                });
    //Фаза 7
                                                                        //7-1
                                                                                                cur_animation_val = 30;
                                                                                                count_animation = 1;
                                                                                                phaseSeven_one = setInterval(function(){
                                                                                                    if (count_animation <= 36){                                                                         //18
                                                                                                        cur_animation_val += 1.5;
                                                                                                        jQuery('#draggable4').css({
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

                                                                                                    } else if(count_animation <= 96) {                                                         //156
                                                                                                        count_animation += 1;

                                                                                                        console.log('7-1'+ count_animation+ '/138');
                                                                                                    } else {    
                                                                                                        clearInterval(phaseSeven_one);
                                                                                                        count_animation = 1;
                                                                                                        jQuery('#draggable4').css({
                                                                                                            transform: 'rotate(-'+0+'deg) scale(0.5)',
                                                                                                            background: 'transparent',
                                                                                                            color: 'red',
                                                                                                            borderColor: 'red',
                                                                                                            opacity: 1,
                                                                                                            borderWidth: '2px',
                                                                                                            paddingTop: '2px',
                                                                                                            zIndex: '1'
                                                                                                        });
                                                                        //7-2
                                                                                                        cur_animation_val = 10;
                                                                                                        count_animation = 1;
                                                                                                        phaseSeven_one = setInterval(function(){
                                                                                                            if (count_animation <= 26){                                                                         //13
                                                                                                                cur_animation_val += 3;
                                                                                                                jQuery('#draggable4').css({
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

                                                                                                            } else if(count_animation <= 86) {                                                         //146
                                                                                                                count_animation += 1;

                                                                                                                jQuery('#draggable4').css({
                                                                                                                    transform: 'rotate('+90+'deg) scale(1)',
                                                                                                                });
                                                                                                                console.log('7-3'+ count_animation+ '/133');
                                                                                                            } else {    
                                                                                                                clearInterval(phaseSeven_one);
                                                                                                                count_animation = 1;
                                                                                                                jQuery('#draggable4').css({
                                                                                                                    transform: 'rotate(-'+0+'deg) scale(0.5)',
                                                                                                                    background: 'transparent',
                                                                                                                    color: 'red',
                                                                                                                    borderColor: 'red',
                                                                                                                    opacity: 1,
                                                                                                                    borderWidth: '2px',
                                                                                                                    paddingTop: '2px',
                                                                                                                    zIndex: '1'
                                                                                                                });
                                                                        //7-3
                                                                                                                cur_animation_val = 90;
                                                                                                                count_animation = 1;
                                                                                                                phaseSeven_one = setInterval(function(){
                                                                                                                    if (count_animation <= 30){                                                                         //15
                                                                                                                        cur_animation_val -= 3;
                                                                                                                        jQuery('#draggable4').css({
                                                                                                                            transform: 'rotate(-'+cur_animation_val+'deg) scale(1)',
                                                                                                                            background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/mo_left.png) 0 0/100% no-repeat',
                                                                                                                            color: 'transparent',
                                                                                                                            borderColor: 'transparent',
                                                                                                                            opacity: 0.8,
                                                                                                                            borderWidth: '1px',
                                                                                                                            paddingTop: '4px',
                                                                                                                            zIndex: '1000'
                                                                                                                        });
                                                                                                                        count_animation += 1;

                                                                                                                    } else if(count_animation <= 90) {                                                         //150
                                                                                                                        count_animation += 1;

                                                                                                                        console.log('7-4'+ count_animation+ '/135');
                                                                                                                    } else {    
                                                                                                                        clearInterval(phaseSeven_one);
                                                                                                                        count_animation = 1;
                                                                                                                        jQuery('#draggable4').css({
                                                                                                                            transform: 'rotate(-'+0+'deg) scale(0.5)',
                                                                                                                            background: 'transparent',
                                                                                                                            color: 'red',
                                                                                                                            borderColor: 'red',
                                                                                                                            opacity: 1,
                                                                                                                            borderWidth: '2px',
                                                                                                                            paddingTop: '2px',
                                                                                                                            zIndex: '1'
                                                                                                                        });
                                                                        //7-4
                                                                                                                        cur_animation_val = 90;
                                                                                                                        count_animation = 1;
                                                                                                                        phaseSeven_one = setInterval(function(){
                                                                                                                            if (count_animation <= 28){                                                                         //14
                                                                                                                                cur_animation_val -= 3;
                                                                                                                                jQuery('#draggable4').css({
                                                                                                                                    transform: 'rotate('+cur_animation_val+'deg) scale(1)',
                                                                                                                                    background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/mo_right.png) 0 0/100% no-repeat',
                                                                                                                                    color: 'transparent',
                                                                                                                                    borderColor: 'transparent',
                                                                                                                                    opacity: 0.8,
                                                                                                                                    borderWidth: '1px',
                                                                                                                                    paddingTop: '4px',
                                                                                                                                    zIndex: '1000'
                                                                                                                                });
                                                                                                                                count_animation += 1;

                                                                                                                            } else if(count_animation <= 80) {                                                         //140
                                                                                                                                count_animation += 1;

                                                                                                                                console.log('7-2'+ count_animation+ '/134');
                                                                                                                            } else {    
                                                                                                                                clearInterval(phaseSeven_one);
                                                                                                                                count_animation = 1;
                                                                                                                                jQuery('#draggable4').css({
                                                                                                                                    transform: 'rotate(-'+0+'deg) scale(0.5)',
                                                                                                                                    background: 'transparent',
                                                                                                                                    color: 'red',
                                                                                                                                    borderColor: 'red',
                                                                                                                                    opacity: 1,
                                                                                                                                    borderWidth: '2px',
                                                                                                                                    paddingTop: '2px',
                                                                                                                                    zIndex: '1'
                                                                                                                                });
                                                                        //7-5
                                                                                                                                cur_animation_val = 30;
                                                                                                                                count_animation = 1;
                                                                                                                                phaseSeven_one = setInterval(function(){
                                                                                                                                    if (count_animation <= 36){                                                                         //18
                                                                                                                                        cur_animation_val += 1.5;
                                                                                                                                        jQuery('#draggable1').css({
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

                                                                                                                                    } else if(count_animation <= 96) {                                                         //156
                                                                                                                                        count_animation += 1;

                                                                                                                                        console.log('7-1'+ count_animation+ '/138');
                                                                                                                                    } else {    
                                                                                                                                        clearInterval(phaseSeven_one);
                                                                                                                                        count_animation = 1;
                                                                                                                                        jQuery('#draggable1').css({
                                                                                                                                            transform: 'rotate(-'+0+'deg) scale(0.5)',
                                                                                                                                            background: 'transparent',
                                                                                                                                            color: 'red',
                                                                                                                                            borderColor: 'red',
                                                                                                                                            opacity: 1,
                                                                                                                                            borderWidth: '2px',
                                                                                                                                            paddingTop: '2px',
                                                                                                                                            zIndex: '1'
                                                                                                                                        });
                                                                        //7-6
                                                                                                                                        cur_animation_val = 90;
                                                                                                                                        count_animation = 1;
                                                                                                                                        phaseSeven_one = setInterval(function(){
                                                                                                                                            if (count_animation <= 28){                                                                         //14
                                                                                                                                                cur_animation_val -= 3;
                                                                                                                                                jQuery('#draggable4').css({
                                                                                                                                                    transform: 'rotate('+cur_animation_val+'deg) scale(1)',
                                                                                                                                                    background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/mo_right.png) 0 0/100% no-repeat',
                                                                                                                                                    color: 'transparent',
                                                                                                                                                    borderColor: 'transparent',
                                                                                                                                                    opacity: 0.8,
                                                                                                                                                    borderWidth: '1px',
                                                                                                                                                    paddingTop: '4px',
                                                                                                                                                    zIndex: '1000'
                                                                                                                                                });
                                                                                                                                                count_animation += 1;

                                                                                                                                            } else if(count_animation <= 88) {                                                         //148
                                                                                                                                                count_animation += 1;

                                                                                                                                                console.log('7-2'+ count_animation+ '/134');
                                                                                                                                            } else {    
                                                                                                                                                clearInterval(phaseSeven_one);
                                                                                                                                                count_animation = 1;
                                                                                                                                                jQuery('#draggable4').css({
                                                                                                                                                    transform: 'rotate(-'+0+'deg) scale(0.5)',
                                                                                                                                                    background: 'transparent',
                                                                                                                                                    color: 'red',
                                                                                                                                                    borderColor: 'red',
                                                                                                                                                    opacity: 1,
                                                                                                                                                    borderWidth: '2px',
                                                                                                                                                    paddingTop: '2px',
                                                                                                                                                    zIndex: '1'
                                                                                                                                                });
            //Фаза 6
                                                                                                                                                cur_animation_val = 0;
                                                                                                                                                count_animation = 1;
                                                                                                                                            //анимация против часовой стрелки
                                                                                                                                                jQuery('.triangle').css({
                                                                                                                                                    transform: 'scale(0.2) rotateY(180deg)rotateZ(120deg)',
                                                                                                                                                    left: '-180px',
                                                                                                                                                    top: '-120px'
                                                                                                                                                });
                                                                                                                                                //анимация первого треугольника
                                                                                                                                                jQuery('#draggable1').addClass('transparent');
                                                                                                                                                firstTriangleAnimation = new Vivus('triangle_1', {type: 'delayed', duration: 150}, function(){
                                                                                                                                                //анимация второго треугольника
                                                                                                                                                    setTimeout(function(){
                                                                                                                                                        jQuery('#draggable2').addClass('transparent');
                                                                                                                                                        secondTriangleAnimation = new Vivus('triangle_2', {type: 'delayed', duration: 150}, function(){
                                                                                                                                                //анимация третьего треугольника
                                                                                                                                                            setTimeout(function(){
                                                                                                                                                                jQuery('#draggable3').addClass('transparent');
                                                                                                                                                                thirdTriangleAnimation = new Vivus('triangle_3', {type: 'delayed', duration: 150}, function(){
                                                                                                                                                //анимация четвертого треугольника
                                                                                                                                                                    setTimeout(function(){
                                                                                                                                                                        jQuery('#draggable4').addClass('transparent');
                                                                                                                                                                        fourthTriangleAnimation = new Vivus('triangle_4', {type: 'delayed', duration: 150}, function(){
                                                                                                                                                                            jQuery('.itemlist_item').removeClass('transparent');


                                                                                                                                                                        //анимация по часовой стрелке
                                                                                                                                                                            jQuery('.triangle').css({
                                                                                                                                                                                transform: 'scale(0.2) rotateY(0deg)rotateZ(120deg)',
                                                                                                                                                                                left: '-170px',
                                                                                                                                                                                top: '-120px'
                                                                                                                                                                            });
                                                                                                                                                                            //анимация первого треугольника
                                                                                                                                                                            jQuery('#draggable1').addClass('transparent');                                                                    
                                                                                                                                                                            firstTriangleAnimation = new Vivus('triangle_1', {type: 'delayed', duration: 150}, function(){
                                                                                                                                                                            //анимация второго треугольника
                                                                                                                                                                                setTimeout(function(){
                                                                                                                                                                                    jQuery('#draggable2').addClass('transparent');
                                                                                                                                                                                    secondTriangleAnimation = new Vivus('triangle_2', {type: 'delayed', duration: 150}, function(){
                                                                                                                                                                            //анимация третьего треугольника
                                                                                                                                                                                        setTimeout(function(){
                                                                                                                                                                                            jQuery('#draggable3').addClass('transparent');
                                                                                                                                                                                            thirdTriangleAnimation = new Vivus('triangle_3', {type: 'delayed', duration: 150}, function(){
                                                                                                                                                                            //анимация четвертого треугольника
                                                                                                                                                                                                setTimeout(function(){
                                                                                                                                                                                                    jQuery('#draggable4').addClass('transparent');
                                                                                                                                                                                                    fourthTriangleAnimation = new Vivus('triangle_4', {type: 'delayed', duration: 150}, function(){
                                                                                                                                                                                                        jQuery('.itemlist_item').removeClass('transparent');
                //фаза 7
                                                                                                                                                                                                        count_animation = 1;
                                                                                                                                                                                                        phaseOne = setInterval(function(){
                                                                                                                                                                                                            if (count_animation <= 120){                                                                         //120
                                                                                                                                                                                                                jQuery('#draggable3').css({
                                                                                                                                                                                                                    color: 'transparent',
                                                                                                                                                                                                                    borderColor: 'transparent',
                                                                                                                                                                                                                    opacity: 0.8,
                                                                                                                                                                                                                    transform: 'scale(1)',
                                                                                                                                                                                                                    borderWidth: '1px',
                                                                                                                                                                                                                    paddingTop: '4px',
                                                                                                                                                                                                                    background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/edinenie_s_tvorcom.jpg) 0 0/100% no-repeat',
                                                                                                                                                                                                                    zIndex: '1000'
                                                                                                                                                                                                                });
                                                                                                                                                                                                                count_animation += 1;

                                                                                                                                                                                                            } else {
                                                                                                                                                                                                                clearInterval(phaseOne);
                                                                                                                                                                                                                count_animation = 1;
                                                                                                                                                                                                                jQuery('#draggable3').css({
                                                                                                                                                                                                                    background: 'transparent',
                                                                                                                                                                                                                    color: 'red',
                                                                                                                                                                                                                    borderColor: 'red',
                                                                                                                                                                                                                    opacity: 1,
                                                                                                                                                                                                                    transform: 'scale(0.5)',
                                                                                                                                                                                                                    borderWidth: '2px',
                                                                                                                                                                                                                    paddingTop: '2px',
                                                                                                                                                                                                                    zIndex: '1'
                                                                                                                                                                                                                });
                                                                                                                                                                                                                onEnd();
                                                                                                                                                                                                                jQuery( ".btn__wizard" )
                                                                                                                                                                                                                    .text('Выполнить')
                                                                                                                                                                                                                    .removeClass('btn__wizard_inAction');        
                                                                                                                                                                                                            }
                                                                                                                                                                                                        }, 1000);
                                                                                                                                                                                                    });
                                                                                                                                                                                                    fourthTriangleAnimation.play();
                                                                                                                                                                                                }, 250)
                                                                                                                                                                                            });
                                                                                                                                                                                            thirdTriangleAnimation.play();
                                                                                                                                                                                        }, 250)
                                                                                                                                                                                    });
                                                                                                                                                                                    secondTriangleAnimation.play();
                                                                                                                                                                                }, 250)
                                                                                                                                                                            });
                                                                                                                                                                            firstTriangleAnimation.play();
                                                                                                                                                                        });
                                                                                                                                                                        fourthTriangleAnimation.play();
                                                                                                                                                                    }, 250)
                                                                                                                                                                });
                                                                                                                                                                thirdTriangleAnimation.play();
                                                                                                                                                            }, 250)
                                                                                                                                                        });
                                                                                                                                                        secondTriangleAnimation.play();
                                                                                                                                                    }, 250)
                                                                                                                                                });
                                                                                                                                                firstTriangleAnimation.play();
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
                                                                                    });
                                                                                    fourthTriangleAnimation.play();
                                                                                }, 250)
                                                                            });
                                                                            thirdTriangleAnimation.play();
                                                                        }, 250)
                                                                    });
                                                                    secondTriangleAnimation.play();
                                                                }, 250)
                                                            });
                                                            firstTriangleAnimation.play();
                                                        });
                                                        fourthTriangleAnimation.play();
                                                    }, 250)
                                                });
                                                thirdTriangleAnimation.play();
                                            }, 250)
                                        });
                                        secondTriangleAnimation.play();
                                    }, 250)
                                });
                                firstTriangleAnimation.play();
                            }
                        }, 1000);
                    }
                }, 500);
            }
        }, 1000);
    }
v5 = function(){
//фаза 1
    count_animation = 1;
    jQuery('#draggableClean_2').css({
            left: parseFloat(jQuery('#draggable0').css('left'))-60+'px',
            top: parseFloat(jQuery('#draggable0').css('top'))+'px'
    });
    jQuery('#draggableClean_3').css({
            left: parseFloat(jQuery('#draggable5').css('left'))+'px',
            top: parseFloat(jQuery('#draggable5').css('top'))+30+'px'
    });
    jQuery('#draggableClean_2, #draggableClean_3').removeClass('inopaciti');
    phaseOne = setInterval(function(){
        if (count_animation <= 120){                                                                         //120
            cur_animation_val += 6;
            jQuery('#draggableClean_2, #draggableClean_3, #draggable0, #draggableD1, #draggableVD').css({
                transform: 'scale(1)',
                borderWidth: '1px',
                color: 'transparent',
                borderColor: 'transparent',
                opacity: 0.8,
                paddingTop: '4px',
                zIndex: '1000'
            });
            jQuery('#draggableClean_2, #draggableClean_3, #draggable0, #draggableD1, #draggableVD').css({
                transform: 'rotate(-'+cur_animation_val+'deg) scale(1)',
                background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat'
            });
            count_animation += 1;
        } else {
            clearInterval(phaseOne);
            count_animation = 1;
            jQuery('#draggableClean_2, #draggableClean_3, #draggable0, #draggableD1, #draggableVD').css({
                background: 'transparent',
                transform: 'scale(0.5)',
                color: 'red',
                borderColor: 'red',
                opacity: 1,
                borderWidth: '2px',
                paddingTop: '2px',
                zIndex: '1'
            });
            count_animation = 1;
            jQuery('#draggableClean_2, #draggableClean_3').addClass('inopaciti');
//фаза 2
            count_animation = 1;
            jQuery('#draggableClean_2').css({
                    left: parseFloat(jQuery('#draggableS5').css('left'))+70+'px',
                    top: parseFloat(jQuery('#draggableS5').css('top'))+'px'
            });
            jQuery('#draggableClean_3').css({
                    left: parseFloat(jQuery('#draggableS6').css('left'))+70+'px',
                    top: parseFloat(jQuery('#draggableS6').css('top'))+'px'
            });
            jQuery('#draggableClean_2, #draggableClean_3').removeClass('inopaciti');
            phaseOne = setInterval(function(){
                if (count_animation <= 150){                                                                         //150
                    cur_animation_val += 6;
                    jQuery('#draggableClean_2, #draggableClean_3, #draggableS4, #draggableS5, #draggableS6, #draggableD5, #draggable5, #draggableVD').css({
                        transform: 'scale(1)',
                        borderWidth: '1px',
                        color: 'transparent',
                        borderColor: 'transparent',
                        opacity: 0.8,
                        paddingTop: '4px',
                        zIndex: '1000'
                    });
                    jQuery('#draggableClean_2, #draggableClean_3').css({
                        transform: 'rotate(-'+cur_animation_val+'deg) scale(1)',
                        background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat'
                    });
                    jQuery('#draggableD5, #draggable5').css({
                        background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/disfunction.jpg) 0 0/100% no-repeat'
                    });
                    jQuery('#draggableS4, #draggableS5, #draggableS6').css({
                        background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/povregdenie_demona.jpg) 0 0/100% no-repeat'
                    });
                    jQuery('#draggableVD').css({
                        background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/vig.png) 0 0/100% no-repeat'
                    });
                    if (count_animation >= 120 && count_animation <= 150){                               //150
                        jQuery('#draggableS4, #draggableS5, #draggableS6').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/filtr.jpg) 0 0/100% no-repeat');
                        jQuery('#draggableClean_2, #draggableClean_3').addClass('inopaciti');
                    }
                    count_animation += 1;
                } else {
                    clearInterval(phaseOne);
                    count_animation = 1;
                    jQuery('#draggableClean_2, #draggableClean_3, #draggableS4, #draggableS5, #draggableS6, #draggableD5, #draggable5, #draggableVD').css({
                        background: 'transparent',
                        transform: 'scale(0.5)',
                        color: 'red',
                        borderColor: 'red',
                        opacity: 1,
                        borderWidth: '2px',
                        paddingTop: '2px',
                        zIndex: '1'
                    });
                    count_animation = 1;
                    jQuery('#draggableClean_2, #draggableClean_3').addClass('inopaciti');

//Фаза 3
                    cur_animation_val = 0;
                    count_animation = 1;
                //анимация против часовой стрелки
                    jQuery('.triangle').css({
                        transform: 'scale(0.2) rotateY(180deg)rotateZ(120deg)',
                        left: '-180px',
                        top: '-120px'
                    });
                    //анимация первого треугольника
                    jQuery('#draggable1').addClass('transparent');
                    firstTriangleAnimation = new Vivus('triangle_1', {type: 'delayed', duration: 150}, function(){
                    //анимация второго треугольника
                        setTimeout(function(){
                            jQuery('#draggable2').addClass('transparent');
                            secondTriangleAnimation = new Vivus('triangle_2', {type: 'delayed', duration: 150}, function(){
                    //анимация третьего треугольника
                                setTimeout(function(){
                                    jQuery('#draggable3').addClass('transparent');
                                    thirdTriangleAnimation = new Vivus('triangle_3', {type: 'delayed', duration: 150}, function(){
                    //анимация четвертого треугольника
                                        setTimeout(function(){
                                            jQuery('#draggable4').addClass('transparent');
                                            fourthTriangleAnimation = new Vivus('triangle_4', {type: 'delayed', duration: 150}, function(){
                                                jQuery('.itemlist_item').removeClass('transparent');
                                            //анимация по часовой стрелке
                                                jQuery('.triangle').css({
                                                    transform: 'scale(0.2) rotateY(0deg)rotateZ(120deg)',
                                                    left: '-170px',
                                                    top: '-120px'
                                                });
                                                //анимация первого треугольника
                                                jQuery('#draggable1').addClass('transparent');                                                                    
                                                firstTriangleAnimation = new Vivus('triangle_1', {type: 'delayed', duration: 150}, function(){
                                                //анимация второго треугольника
                                                    setTimeout(function(){
                                                        jQuery('#draggable2').addClass('transparent');
                                                        secondTriangleAnimation = new Vivus('triangle_2', {type: 'delayed', duration: 150}, function(){
                                                //анимация третьего треугольника
                                                            setTimeout(function(){
                                                                jQuery('#draggable3').addClass('transparent');
                                                                thirdTriangleAnimation = new Vivus('triangle_3', {type: 'delayed', duration: 150}, function(){
                                                //анимация четвертого треугольника
                                                                    setTimeout(function(){
                                                                        jQuery('#draggable4').addClass('transparent');
                                                                        fourthTriangleAnimation = new Vivus('triangle_4', {type: 'delayed', duration: 150}, function(){
                                                                            jQuery('.itemlist_item').removeClass('transparent');
    //Фаза 4
                                                    //4-1
                                                                            cur_animation_val = 30;
                                                                            count_animation = 1;
                                                                            phaseSeven_one = setInterval(function(){
                                                                                if (count_animation <= 36){                                                                         //18
                                                                                    cur_animation_val += 1.5;
                                                                                    jQuery('#draggable5').css({
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

                                                                                } else if(count_animation <= 96) {                                                        //156
                                                                                    count_animation += 1;

                                                                                    console.log('7-1'+ count_animation+ '/138');
                                                                                } else {    
                                                                                    clearInterval(phaseSeven_one);
                                                                                    count_animation = 1;
                                                                                    jQuery('#draggable5').css({
                                                                                        transform: 'rotate(-'+0+'deg) scale(0.5)',
                                                                                        background: 'transparent',
                                                                                        color: 'red',
                                                                                        borderColor: 'red',
                                                                                        opacity: 1,
                                                                                        borderWidth: '2px',
                                                                                        paddingTop: '2px',
                                                                                        zIndex: '1'
                                                                                    });
                                                    //4-2
                                                                                    cur_animation_val = 90;
                                                                                    count_animation = 1;
                                                                                    phaseSeven_one = setInterval(function(){
                                                                                        if (count_animation <= 28){                                                                         //14
                                                                                            cur_animation_val -= 3;
                                                                                            jQuery('#draggable4').css({
                                                                                                transform: 'rotate('+cur_animation_val+'deg) scale(1)',
                                                                                                background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/mo_right.png) 0 0/100% no-repeat',
                                                                                                color: 'transparent',
                                                                                                borderColor: 'transparent',
                                                                                                opacity: 0.8,
                                                                                                borderWidth: '1px',
                                                                                                paddingTop: '4px',
                                                                                                zIndex: '1000'
                                                                                            });
                                                                                            count_animation += 1;

                                                                                        } else if(count_animation <= 80) {                                                         //140
                                                                                            count_animation += 1;

                                                                                            console.log('7-2'+ count_animation+ '/134');
                                                                                        } else {    
                                                                                            clearInterval(phaseSeven_one);
                                                                                            count_animation = 1;
                                                                                            jQuery('#draggable4').css({
                                                                                                transform: 'rotate(-'+0+'deg) scale(0.5)',
                                                                                                background: 'transparent',
                                                                                                color: 'red',
                                                                                                borderColor: 'red',
                                                                                                opacity: 1,
                                                                                                borderWidth: '2px',
                                                                                                paddingTop: '2px',
                                                                                                zIndex: '1'
                                                                                            });
    //фаза 5
                                                                                            cur_animation_val = 0;
                                                                                            phaseTwo = setInterval(function(){
                                                                                                if (count_animation <= 120){                                                        //120     
                                                                                                    cur_animation_val += 6;
                                                                                                    jQuery('#draggableD2, #draggable2, #draggableS1, #draggableS2, #draggableS2_1, #draggableClean').css({
                                                                                                        borderWidth: '1px',
                                                                                                        paddingTop: '4px',
                                                                                                        zIndex: '1000',
                                                                                                        transform: 'scale(1)',
                                                                                                        color: 'transparent'
                                                                                                    });
                                                                                                    jQuery('#draggableClean, #draggableS1').css({
                                                                                                        transform: 'rotate(-'+cur_animation_val+'deg) scale(1)',
                                                                                                        background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/lovushka.jpg) 0 0/100% no-repeat'
                                                                                                    });
                                                                                                    jQuery('#draggable2').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/disfunction.jpg) 0 0/100% no-repeat');
                                                                                                    jQuery('#draggableD2').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/drenag.png) 0 0/100% no-repeat');
                                                                                                    jQuery('#draggableS2, #draggableS2_1').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/travma.jpg) 0 0/100% no-repeat');
                                                                                                    if (count_animation >= 60 && count_animation <= 120){                               //60-120
                                                                                                        jQuery('#draggableS2, #draggableS2_1').css('background', '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/filtr.jpg) 0 0/100% no-repeat');
                                                                                                    }
                                                                                                    count_animation += 1;
                                                                                                } else {
                                                                                                    clearInterval(phaseTwo);
                                                                                                    count_animation = 1;
                                                                                                    jQuery('#draggableD2, #draggable2, #draggableS1, #draggableS2, #draggableS2_1, #draggableClean').css({
                                                                                                        background: 'transparent',
                                                                                                        transform: 'scale(0.5)',
                                                                                                        color: 'red',
                                                                                                        borderColor: 'red',
                                                                                                        opacity: 1,
                                                                                                        borderWidth: '2px',
                                                                                                        paddingTop: '2px',
                                                                                                        zIndex: '1'
                                                                                                    });   
    //Фаза 6
                                                                            //6-1
                                                                                                    cur_animation_val = 90;
                                                                                                    count_animation = 1;
                                                                                                    phaseSeven_one = setInterval(function(){
                                                                                                        if (count_animation <= 30){                                                                         //15
                                                                                                            cur_animation_val -= 3;
                                                                                                            jQuery('#draggable2').css({
                                                                                                                transform: 'rotate(-'+cur_animation_val+'deg) scale(1)',
                                                                                                                background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/mo_left.png) 0 0/100% no-repeat',
                                                                                                                color: 'transparent',
                                                                                                                borderColor: 'transparent',
                                                                                                                opacity: 0.8,
                                                                                                                borderWidth: '1px',
                                                                                                                paddingTop: '4px',
                                                                                                                zIndex: '1000'
                                                                                                            });
                                                                                                            count_animation += 1;

                                                                                                        } else if(count_animation <= 90) {                                                         //150
                                                                                                            count_animation += 1;

                                                                                                            console.log('7-4'+ count_animation+ '/135');
                                                                                                        } else {    
                                                                                                            clearInterval(phaseSeven_one);
                                                                                                            count_animation = 1;
                                                                                                            jQuery('#draggable2').css({
                                                                                                                transform: 'rotate(-'+0+'deg) scale(0.5)',
                                                                                                                background: 'transparent',
                                                                                                                color: 'red',
                                                                                                                borderColor: 'red',
                                                                                                                opacity: 1,
                                                                                                                borderWidth: '2px',
                                                                                                                paddingTop: '2px',
                                                                                                                zIndex: '1'
                                                                                                            });
                                                                            //6-2
                                                                                                            cur_animation_val = 30;
                                                                                                            count_animation = 1;
                                                                                                            phaseSeven_one = setInterval(function(){
                                                                                                                if (count_animation <= 36){                                                                         //18
                                                                                                                    cur_animation_val += 1.5;
                                                                                                                    jQuery('#draggable1').css({
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

                                                                                                                } else if(count_animation <= 96) {                                                         //156
                                                                                                                    count_animation += 1;

                                                                                                                    console.log('7-1'+ count_animation+ '/138');
                                                                                                                } else {    
                                                                                                                    clearInterval(phaseSeven_one);
                                                                                                                    count_animation = 1;
                                                                                                                    jQuery('#draggable1').css({
                                                                                                                        transform: 'rotate(-'+0+'deg) scale(0.5)',
                                                                                                                        background: 'transparent',
                                                                                                                        color: 'red',
                                                                                                                        borderColor: 'red',
                                                                                                                        opacity: 1,
                                                                                                                        borderWidth: '2px',
                                                                                                                        paddingTop: '2px',
                                                                                                                        zIndex: '1'
                                                                                                                    });
//фаза 7
                                                                                                                    count_animation = 1;
                                                                                                                    phaseOne = setInterval(function(){
                                                                                                                        if (count_animation <= 120){                                                                         //120
                                                                                                                            jQuery('#draggable3').css({
                                                                                                                                color: 'transparent',
                                                                                                                                borderColor: 'transparent',
                                                                                                                                opacity: 0.8,
                                                                                                                                transform: 'scale(1)',
                                                                                                                                borderWidth: '1px',
                                                                                                                                paddingTop: '4px',
                                                                                                                                background: '#fff url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/edinenie_s_tvorcom.jpg) 0 0/100% no-repeat',
                                                                                                                                zIndex: '1000'
                                                                                                                            });
                                                                                                                            count_animation += 1;

                                                                                                                        } else {
                                                                                                                            clearInterval(phaseOne);
                                                                                                                            count_animation = 1;
                                                                                                                            jQuery('#draggable3').css({
                                                                                                                                background: 'transparent',
                                                                                                                                color: 'red',
                                                                                                                                borderColor: 'red',
                                                                                                                                opacity: 1,
                                                                                                                                transform: 'scale(0.5)',
                                                                                                                                borderWidth: '2px',
                                                                                                                                paddingTop: '2px',
                                                                                                                                zIndex: '1'
                                                                                                                            });
                                                                                                                            onEnd();
                                                                                                                            jQuery( ".btn__wizard" )
                                                                                                                                .text('Выполнить')
                                                                                                                                .removeClass('btn__wizard_inAction');  
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
                                                                        });
                                                                        fourthTriangleAnimation.play();
                                                                    }, 250)
                                                                });
                                                                thirdTriangleAnimation.play();
                                                            }, 250)
                                                        });
                                                        secondTriangleAnimation.play();
                                                    }, 250)
                                                });
                                                firstTriangleAnimation.play();
                                            });
                                            fourthTriangleAnimation.play();
                                        }, 250)
                                    });
                                    thirdTriangleAnimation.play();
                                }, 250)
                            });
                            secondTriangleAnimation.play();
                        }, 250)
                    });
                    firstTriangleAnimation.play();
                }
            }, 1000);
        }
    }, 1000);
}

v6 = function(){
    alert('v6');
    onEnd();
    jQuery( ".btn__wizard" )
        .text('Выполнить')
        .removeClass('btn__wizard_inAction'); 
}
v7 = function(){
    alert('v7');
    onEnd();
    jQuery( ".btn__wizard" )
        .text('Выполнить')
        .removeClass('btn__wizard_inAction'); 
}
