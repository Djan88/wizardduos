jQuery(function() {
    var count_animation = 1,
        cur_animation_val = 0,
        cur_screen = 0,
        nextScreen,
        changing;

    jQuery( ".draggable" ).draggable({ snap: false });
    jQuery( ".select_program" ).accordion({ active: 1 });

    //поиск картинки
    jQuery('#main img:first-child').addClass('returned')


    
    jQuery('.show_form').on('click', function(event) {
        console.log('ololo');
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

// ШАГ 1 (К загрузке фото)
    jQuery( ".btn_choice" ).on('click', function(event) {
        if(jQuery(this).hasClass('btn_choice__choiced')){
            jQuery(this)
                .removeClass('btn_choice__choiced')
                .text('Выбрать');
        } else {
            cur_screen += 1;
            jQuery(".btn_choice")
                .removeClass('btn_choice__choiced')
                .text('Выбрать');
            jQuery(this)
                .addClass('btn_choice__choiced')
                .text('Выбрано')
                .append('<pre> ✓</pre>');
                nextScreen()
                jQuery('.step').eq(cur_screen-1).addClass('step_done');
                jQuery('.step').eq(cur_screen).addClass('step_now');
                jQuery('.btn_back')
                    .removeClass('invisible')
                    .addClass('animated')
                    .addClass('fadeIn');
        }
    });

// ШАГ 2 (переход к магии)

// Возврат на предыдущий шаг
    jQuery('.btn_back').on('click', function(event) {
        console.log(cur_screen);
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
        jQuery('.step').eq(cur_screen-1).addClass('step_now');
        if(cur_screen >= 2){
            jQuery('.step').eq(cur_screen-2).addClass('step_done');
        };
        cur_screen -= 1;
    });

    jQuery('.itemlist_item').on('click', function(event) {
        count_animation = 1;
        changing = setInterval(function(){
            // console.log(event.target);
            if (count_animation >= 60){
                clearInterval(changing);
            }
            cur_animation_val += 6;
            jQuery(event.target).css('transform', 'rotate(-'+cur_animation_val+'deg)');
            // jQuery(this).css('top', cur_animation_val+'px');
            count_animation += 1;
        }, 1000)
    });

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
        console.log(oFile);
        // hide all errors
        jQuery('.error').hide();

        // check for image type (jpg and png are allowed)
        var rFilter = /^(image\/jpeg|image\/png)$/i;
        if (! rFilter.test(oFile.type)) {
            jQuery('.error').html('Доспустимы изображения только в формате ".jpg" и ".png"').show();
            return;
        }

        // check for file size
        if (oFile.size > 250 * 1024) {
            jQuery('.error').html('Вы выбрали слишком большой файл, пожалуйста выберите изображение меньшего размера.').show();
            return;
        }

        // preview element
        var oImage = document.getElementById('preview');

        // prepare HTML5 FileReader
        var oReader = new FileReader();
            oReader.onload = function(e) {

            // e.target.result contains the DataURL which we can use as a source of the image
            oImage.src = e.target.result;
            oImage.onload = function () { // onload event handler

                // display step 2
                jQuery('.step2').fadeIn(500);

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
        };

        // read selected file as DataURL
        oReader.readAsDataURL(oFile);
    }
    jQuery('#image_file').on('change', fileSelectHandler);
});
