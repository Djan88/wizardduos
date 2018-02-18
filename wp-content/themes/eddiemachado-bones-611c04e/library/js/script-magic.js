jQuery(function() {
    //Скрываем возможно загруженное изображение
    jQuery('#main img:first-child').addClass('returned hidden');
    // jQuery('.itemlist-one').find('img').removeClass('returned hidden');
    var cur_screen = 0,
        nextScreen,
        croppedImg,
        croppedImgTwo,
        curChoice,
        protocol,
        checkPoints,
        main_heading,
        set_prot,
        pointsStatus = true,
        supportsStorage = function(){
            try {
                return 'localStorage' in window && window['localStorage'] !== null;
            } catch (e) {
                return false;
            }
        };

    //Функция проверки положения точек
    checkPoints = function(){
        jQuery('.itemZone').each(function() {
            if(parseFloat(jQuery(this).css('left')) < 350){
                pointsStatus = false;
                console.log(jQuery(this)+ ' status '+pointsStatus);
            }
        });
    }
    // Текст заголовка
    main_heading = function(){
        // console.log(cur_screen);
        if(cur_screen == 0){
            jQuery('.heading_dashboard').text('Выберите протокол работы');
            jQuery('.btn_back').addClass('hidden');
        } else if (cur_screen == 1){
            jQuery('.heading_dashboard').text('Загрузите фото');
            jQuery('.btn_back').removeClass('hidden');
        }
    }
    jQuery('.prot-item').on('click', function() {
        set_prot();
    });
    //Получение данных из локального хранилища
    if(supportsStorage && localStorage.getItem('curChoice')){
        curChoice = localStorage.getItem('curChoice');
        protocol = localStorage.getItem('protocol');
        jQuery('.step_choice div').text(curChoice);
    }
    //Перетягивание элементов
    jQuery( ".draggable, .box_rounded" ).draggable({ 
        snap: false
    });
    //Изменение размера круга
    jQuery( ".box_rounded" ).resizable({
      aspectRatio: 1/ 1
    });
    //Аккордион
    jQuery( ".select_program" ).accordion({ active: 100 });

    jQuery('.show_form').on('click', function(event) {
        jQuery('.login__form')
            .removeClass('hidden')
            .addClass('animated zoomIn');
    });

    nextScreen = function(){
        jQuery('.machine_screen')
            .addClass('hidden')
            .removeClass('fadeIn')
            .eq(cur_screen)
            .removeClass('hidden')
            .addClass('animated')
            .addClass('fadeIn')
    }

    jQuery('.homelink').on('click', function(event) {
        localStorage.removeItem('croppedImgTwo');
        localStorage.removeItem('croppedImg');
    });

// ШАГ 1 (К загрузке фото)
    jQuery( ".btn_choice" ).on('click', function(event) {
        protocol = jQuery(this).data('protocol');
        localStorage.setItem('protocol', protocol);
        if(jQuery(this).hasClass('btn_choice__choiced')){
            jQuery(this)
                .removeClass('btn_choice__choiced')
                .text('Выбрать');
        } else {
            curChoice = jQuery('.ui-state-active').text();
            localStorage.setItem('curChoice', curChoice);
            jQuery('.step_choice div').text(curChoice);
            cur_screen += 1;
                nextScreen()
                jQuery('.step')
                    .eq(cur_screen-1)
                    .addClass('step_done');
                jQuery('.step')
                    .eq(cur_screen)
                    .addClass('step_now');
                jQuery('.btn_back')
                    .removeClass('invisible')
                    .addClass('animated')
                    .addClass('fadeIn');
        }
        main_heading()
    });
    jQuery('.btn__crop').on('click', function(event) {
        jQuery('.crop_photo').click();
    });

// ШАГ 2 (переход к магии)
//Если фото уже обрезано
    jQuery('.step_img:after').css('content', curChoice);
    croppedImg = jQuery('#main').children()[0];
    if(croppedImg.hasAttribute('src')){
        if(supportsStorage && localStorage.getItem('croppedImg')){
            localStorage.setItem('croppedImgTwo', jQuery('#main').children().attr('src'));
            croppedImg = localStorage.getItem('croppedImg');
            croppedImgTwo = jQuery('#main').children().attr('src');
            console.log(croppedImg)
            console.log(croppedImgTwo)
            protocol = localStorage.getItem('protocol');
            console.log('protocol: '+protocol);
            if(protocol == 'mw'){
                jQuery('.itemlist-mw').removeClass('hidden');
                jQuery('.itemlist-ww, .itemlist-mm').remove();
                jQuery('.itemlist-mw').find('.example_non_anim').removeClass('hidden');
            } else if(protocol == 'mm'){
                jQuery('.itemlist-mm').removeClass('hidden');
                jQuery('.itemlist-mw, .itemlist-ww').remove();
                jQuery('.itemlist-mm').find('.example_non_anim').removeClass('hidden');
                jQuery('.itemlist-one').css({
                    background: 'url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/mm_1_2.jpg) center -1px/100% no-repeat',
                    height: '1000px'
                });
            } else if(protocol == 'ww'){
                jQuery('.itemlist-ww').removeClass('hidden');
                jQuery('.itemlist-mw, .itemlist-mm').remove();
                jQuery('.itemlist-ww').find('.example_non_anim').removeClass('hidden');
                jQuery('.itemlist-one').css({
                    background: 'url(/wp-content/themes/eddiemachado-bones-611c04e/library/images/women.jpg) 50% 2px / 97% no-repeat',
                    height: '1000px'
                });
            } else {
                console.log('нет протокола с id '+ protocol)
            };
            // jQuery('.step_img div').text('Фото загружено');
            jQuery('.heading_dashboard').text('Перенесите зоны с шаблона на фото клиентов')
            cur_screen = 2;
            jQuery('.step').eq(cur_screen-1).addClass('step_done');
            jQuery('.step').eq(cur_screen-2).addClass('step_done');
            jQuery('.step').eq(cur_screen).addClass('step_now');
            nextScreen();
            jQuery('.btn_back')
                .removeClass('invisible')
                .addClass('animated')
                .addClass('fadeIn');
            jQuery('.btn__wizard').removeClass('hidden');
            jQuery('.itemlist-two').append(croppedImg);
            jQuery('.work-area').find('.returned').draggable();
        } else {
            localStorage.setItem('croppedImg', jQuery('#main').children().attr('src'));
            jQuery('.machine_screen').addClass('hidden');
            jQuery('.machine_screen_load')
                .removeClass('hidden')
                .addClass('animated')
                .addClass('fadeIn');
        }
    }

//ШАГ 3 (Старт процедуры)
jQuery( ".btn__wizard" ).on('click', function(event) {
    pointsStatus = true;
    checkPoints();
    // if(pointsStatus == false){
    //     swal("Не все зоны перенесены", "Перед началом процедуры необходимо перенести все зоны", "info")
    // } else {
        jQuery(this)
            .addClass('btn__wizard_inAction')
            .text('Выполняется');
            // jQuery('.step_procedure div').text('Процедура выполняется');
            jQuery('.heading_dashboard').text('Процедура выполняется')
            jQuery('.btn_back').addClass('invisible');
            // localStorage.setItem('protocol', 'duos');
            protocol = localStorage.getItem('protocol');
            console.log(protocol);
            if(protocol == 'mw'){
                mw();
            } else if(protocol == 'ww'){
                ww();
            } else if(protocol == 'mm'){
                mm();
            } else{
                console.log('нет протокола с id '+ protocol)
            }
    // }
    main_heading()
});
//Быстрая смена протокола
jQuery('#main').on('click', '.fast-protocol', function() {
    protocol = jQuery(this).data('fast');
    localStorage.setItem('protocol', protocol);
    jQuery('.fast-protocol-wrap')
        .addClass('hidden')
        .removeClass('fadeIn');
});

// Возврат на предыдущий шаг
    jQuery('.btn_back').on('click', function(event) {
        // console.log(cur_screen);
        jQuery('.btn__crop, .btn__wizard').addClass('hidden');
        jQuery('.machine_screen')
            .addClass('hidden')
            .removeClass('fadeIn')
            .eq(cur_screen-1)
            .removeClass('hidden')
            .addClass('animated')
            .addClass('fadeIn')
        jQuery('.step')
            .removeClass('step_done')
            .removeClass('step_now');
        jQuery('.step')
            .eq(cur_screen-1)
            .addClass('step_now')
            .find(jQuery('div')).text(' ');
        if(cur_screen >= 2){
            jQuery('.step')
                .eq(cur_screen-2)
                .addClass('step_done');
        };
        cur_screen -= 1;
        main_heading()
    });

//CROPPING SCRIPT
    // convert bytes into friendly format
    function bytesToSize(bytes) {
        var sizes = ['Bytes', 'KB', 'MB'];
        if (bytes == 0) return 'n/a';
        var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
        return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
    };

    // check for selected crop region
    function checkForm() {
        if (parseInt(jQuery('#w').val())) return true;
        jQuery('.error').html('Пожалуйста выделите область').show();
        return false;
    };

    // update info by cropping (onChange and onSelect events handler)
    function updateInfo(e) {
        jQuery('#x1').val(e.x);
        jQuery('#y1').val(e.y);
        jQuery('#x2').val(e.x2);
        jQuery('#y2').val(e.y2);
        jQuery('#w').val(e.w);
        jQuery('#h').val(e.h);
    };

    // clear info by cropping (onRelease event handler)
    function clearInfo() {
        jQuery('.info #w').val('');
        jQuery('.info #h').val('');
    };

    // Create variables (in this scope) to hold the Jcrop API and image size
    var jcrop_api, boundx, boundy;

    function fileSelectHandler() {

        // get selected file
        var oFile = jQuery('#image_file')[0].files[0];
        // console.log(oFile);
        // hide all errors
        jQuery('.error').hide();

        // check for image type (jpg and png are allowed)
        var rFilter = /^(image\/jpeg|image\/png)$/i;
        if (! rFilter.test(oFile.type)) {
            jQuery('.error').html('Доспустимы изображения только в формате ".jpg" и ".png"').show();
            return;
        }

        // check for file size
        if (oFile.size > 15 * 1024 * 1024) {
            jQuery('.error').html('Вы выбрали слишком большой файл, пожалуйста выберите изображение меньшего размера.').show();
            return;
        }

        // preview element
        var oImage = document.getElementById('preview');

        // prepare HTML5 FileReader
        var oReader = new FileReader();

        oReader.onload = function(e) {

            EXIF.getData(oFile, function(){

                var ort = this.exifdata.Orientation;

                // e.target.result contains the DataURL which we can use as a source of the image
                oImage.src = e.target.result;
                oImage.onload = function () {

                    var rotateImg = function(rad, rotateCanvas, cx, cy){
                        var canvas = document.createElement('canvas'),
//                        var canvas = document.getElementById('preview-canvas'),
                            ctx = canvas.getContext('2d');

                        if(rotateCanvas){
                            canvas.setAttribute('width', oImage.naturalHeight);
                            canvas.setAttribute('height', oImage.naturalWidth);
                        }else{
                            canvas.setAttribute('width', oImage.naturalWidth);
                            canvas.setAttribute('height', oImage.naturalHeight);
                        }

                        ctx.rotate(rad);
                        ctx.drawImage(oImage, cx, cy);

                        ort = 1;

                        oImage.src = canvas.toDataURL("image/png");
                    };

                    switch(ort){
                       case 6:
                           rotateImg(90 * Math.PI / 180, true, 0, oImage.naturalHeight * -1);
                           break;
                       case 3:
                           rotateImg(180 * Math.PI / 180, false, oImage.naturalWidth * -1, oImage.naturalHeight * -1);
                           break;
                       case 8:
                           rotateImg(-90 * Math.PI / 180, true, oImage.naturalWidth * -1, 0);
                           break;
                    }


                    // display step 2
                    jQuery('.step2').fadeIn(500);
                    jQuery('.btn__crop').removeClass('hidden');
                    jQuery('.btn__crop').addClass('btn_alert');
                    setTimeout("jQuery('.btn__crop').removeClass('btn_alert')", 3000);
                    // display some basic image info
                    var sResultFileSize = bytesToSize(oFile.size);
                    jQuery('#filesize').val(sResultFileSize);
                    jQuery('#filetype').val(oFile.type);
                    jQuery('#filedim').val(oImage.naturalWidth + ' x ' + oImage.naturalHeight);

                    // destroy Jcrop if it is existed
                    if (typeof jcrop_api != 'undefined') {
                        jcrop_api.destroy();
                        jcrop_api = null;
                        jQuery('#preview').width(oImage.naturalWidth);
                        jQuery('#preview').height(oImage.naturalHeight);
                    }

                    setTimeout(function(){
                        // initialize Jcrop
                        jQuery('#preview').Jcrop({
                            minSize: [32, 32],// keep aspect ratio 1:1
                            bgFade: true, // use fade effect
                            bgOpacity: .3, // fade opacity
                            onChange: updateInfo,
                            onSelect: updateInfo,
                            onRelease: clearInfo
                        }, function(){

                            // use the Jcrop API to get the real image size
                            var bounds = this.getBounds();
                            boundx = bounds[0];
                            boundy = bounds[1];

                            // Store the Jcrop API in the jcrop_api variable
                            jcrop_api = this;
                        });
                    },3000);
                };
            });
        };

        // read selected file as DataURL
        oReader.readAsDataURL(oFile);
    }
    jQuery('#image_file').on('change', fileSelectHandler);
    var a = new Vivus('example', {type: 'delayed', duration: 400});
    setTimeout(jQuery(".paranja").animate({
        opacity: 0,
        zIndex: -1
      }, 1500 ), 5000);
});
