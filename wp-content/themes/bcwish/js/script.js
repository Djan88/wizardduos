jQuery(function() {
  var croppedImg,
      croppedImgTwo,
      photo_left,
      photo_right,
      cur_protocol,
      returned_img,
      nextSound = new Howl({
          urls: ['/sounds/Cancel_2.mp3'],
          autoplay: false,
          loop: false,
          buffer: true
      }),
      prevSound = new Howl({
          urls: ['/sounds/button.mp3'],
          autoplay: false,
          loop: false,
          buffer: true
      }),
      supportsStorage = function(){
          try {
              return 'localStorage' in window && window['localStorage'] !== null;
          } catch (e) {
              return false;
          }
      };
  jQuery('.toLogin').on('click', function(event) {
    jQuery('.register_form').addClass('hidden').removeClass('bounceInUp');
    jQuery('.login_form').removeClass('hidden').addClass('bounceInUp');
  });
  jQuery('.toRegistration').on('click', function(event) {
    jQuery('.login_form').addClass('hidden').removeClass('bounceInUp');
    jQuery('.register_form').removeClass('hidden').addClass('bounceInUp');
  });


  jQuery('.btn-get-started').on('click', function(event) {
    localStorage.removeItem('paused');
    localStorage.removeItem('pausedPhoto'); 
    localStorage.removeItem('pausedPhoto2'); 
    pausedStatus = false;
  });

  jQuery('.to_home, .wizard_to_start').on('click', function(event) {
    localStorage.removeItem('croppedImg');
    localStorage.removeItem('croppedImgTwo');
  });

//Скрываем возможно загруженное изображение
  jQuery('.wizard_returned').find('img:first-child').addClass('returned hidden');
  croppedImg = jQuery('.wizard_returned').children()[0];

  // Dragging knife
  jQuery('.draggable_photo').draggable({
    containment: '#main',
    drag: function() {
      jQuery('.wizard_heading').addClass('unvisible');
      if (jQuery(this).hasClass('uploaded_pics_wrapper_1')) {
        photo_left = parseFloat(jQuery(this).css('left'));
        photo_top = parseFloat(jQuery(this).css('top'));
        jQuery('.uploaded_pics_wrapper_2').css('top', -photo_top+'px');
        jQuery('.uploaded_pics_wrapper_2').css('left', -photo_left+'px');
      } else if (jQuery(this).hasClass('uploaded_pics_wrapper_2')) {} {
        photo_left = parseFloat(jQuery(this).css('left'));
        photo_top = parseFloat(jQuery(this).css('top'));
        jQuery('.uploaded_pics_wrapper_1').css('top', -photo_top+'px');
        jQuery('.uploaded_pics_wrapper_1').css('left', -photo_left+'px');
      }
    },
    stop: function () {
      jQuery('.faq_item').removeClass('faq_item_disabled');
    }
  });

  jQuery('.faq_item_closed').tooltip();

  if(croppedImg && croppedImg.hasAttribute('src')){
    if((supportsStorage && localStorage.getItem('croppedImg'))){
      croppedImg = localStorage.getItem('croppedImg');
      jQuery('.uploaded_pics_1').attr('src', croppedImg);
      localStorage.setItem('croppedImgTwo', jQuery('.wizard_returned').children().attr('src'));
      croppedImgTwo = jQuery('.wizard_returned').children().attr('src');
      jQuery('.uploaded_pics_2').attr('src', croppedImgTwo);
      console.log(croppedImg);
      console.log(croppedImgTwo);
      // jQuery('.step_img div').text('Фото загружено');
      jQuery('.machine_screen, #intro').addClass('hidden');
      jQuery('.wizard_way').removeClass('hidden');
      jQuery('.wizard_returned').attr('src', croppedImg.src);
      jQuery('.wizard_heading').text('Диагностика');
      jQuery('.wizard_to_start').fadeIn(500).removeClass('hidden');
      jQuery('.wm_start').removeClass('unopacity');
      jQuery('.wm_start').removeAttr('style');
      jQuery('.wizard_heading').text('Диагностика');
    } else {
      localStorage.setItem('croppedImg', jQuery('.wizard_returned').children().attr('src'));
      jQuery('.wizard_heading').text('Загрузите второе фото');
      jQuery('.wm_init').click();
      jQuery('.wm_start').removeClass('unopacity');
    }
  }

// Вторая кнопка обрезки
  jQuery('.wizard_crop').on('click', function(event) {
    jQuery('.crop_photo').click();
  });

  jQuery('.photo_upload').on('click', function(event) {
    jQuery('.template_load').addClass('hidden');
  });


  // НАЧАТЬ
  jQuery('.wm_init').on('click', function(event) {
    jQuery('.wm_start').removeClass('unopacity');
    nextSound.play();
  });


  jQuery('.mobile-nav-toggle, .mobile-nav a, .photo_upload, .crop_photo, .btn_diag, .btn_prot_choice, .btn_reset, .wizard_clean_graf, .btn_prot_choice_fromDiag, #faq-list li a, .wizard_protocol, .wizard_play, .wizard_starter_alt, .wizard_stop, body .cancel, body .confirm, .wizard_continue, .mobile-nav select, .wpcf7-submit, .btn-get-started').on('click', function(event) {
    nextSound.play();
  });
  jQuery('.wizard_to_protList, .wizard_to_what_way, .wizard_to_start').on('click', function(event) {
    prevSound.play();
  });

  jQuery('.btn_reset').on('click', function(event) {
    jQuery('html, body').animate({scrollTop: jQuery('#main').offset().top}, 300);
    jQuery('.uploaded_pics_wrapper').attr('style', 'position: relative; visibility: visible; animation-duration: 1.4s; animation-delay: 0.4s; animation-name: bounceInUp;');
  });

  // К протоколам
  jQuery('.btn_prot_choice').on('click', function(event) {
    jQuery('.wizard_way').removeClass('col-sm-12 col-md-12').addClass('col-sm-6 col-md-6');
    jQuery('.wizard_prots').removeClass('col-sm-1 col-md-1 hidden').addClass('col-sm-6 col-md-6');
    jQuery('.wizard_to_protDiag').removeClass('hidden');
    jQuery('.wizard_to_start, .btn_prot_choice, .btn_reset').addClass('hidden');
    jQuery('.btn_prot_choice, .btn_reset').removeClass('wow bounceInUp').removeAttr('style');
    jQuery('.wizard_heading').text('Выберите протокол').removeClass('unvisible');
    jQuery('.uploaded_pics_wrapper').addClass('slow_top');
    // jQuery('.uploaded_pics_wrapper').draggable( "disable");
    var img_1 = parseFloat(jQuery('.uploaded_pics_wrapper_1').css('top'));
    var img_2 = parseFloat(jQuery('.uploaded_pics_wrapper_2').css('top'));
    if (img_1 < 0) {
      jQuery('.uploaded_pics_wrapper_1').css('top', 0);
      jQuery('.uploaded_pics_wrapper_2').css('top', (img_2+img_2)+'px');
    } else if (img_2 < 0) {
      jQuery('.uploaded_pics_wrapper_2').css('top', 0);
      jQuery('.uploaded_pics_wrapper_1').css('top', (img_1+img_1)+'px');
    }
    setTimeout(function(){
      jQuery('.uploaded_pics_wrapper').removeClass('slow_top');
    },1000);
  });

  //Назад. К выбору режимов
  jQuery('.wizard_to_what_way').on('click', function(event) {
    jQuery('.wizard_prots, .wizard_diag').addClass('hidden');
    jQuery('.wizard_to_what_way').addClass('hidden');
    jQuery('.wizard_to_start').fadeIn(500).removeClass('hidden');
    jQuery('.wizard_way').fadeIn(500).removeClass('hidden');
    jQuery('.wizard_heading').text('Диагностика').removeClass('unvisible');
  });
  //Назад. К диагностике
  jQuery('.wizard_to_protDiag').on('click', function(event) {
    jQuery('.wizard_prots').addClass('hidden');
    jQuery('.wizard_to_protDiag').addClass('hidden');
    jQuery('.wizard_to_start, .btn_prot_choice, .btn_reset').removeClass('hidden');
    jQuery('.faq-list').removeClass('wow bounceInUp').removeAttr('style');
    jQuery('.wizard_prots').fadeOut(500).removeClass('col-sm-6 col-md-6').addClass('col-sm-1 col-md-1 hidden');
    jQuery('.wizard_way').removeClass('col-sm-6 col-md-6').addClass('col-sm-12 col-md-12');
    jQuery('.wizard_heading').text('Диагностика').removeClass('unvisible');
    // jQuery('.uploaded_pics_wrapper').draggable( "enable");
  });

  //К переносу зон
  jQuery('.faq_item').on('click', function(event) {
    if (jQuery(this).hasClass('faq_item_disabled')) {
      jQuery('.wizard_way').addClass('animated shake');
      setTimeout(function(){
        jQuery('.faq_item_disabled').tooltip('hide');
        jQuery('.wizard_way').removeClass('animated shake');
      },2000);
    } else {
      if (!jQuery(this).hasClass('active')) {
        jQuery('.faq_item').removeClass('active');
        jQuery(this).addClass('active');
        jQuery('.zone').removeAttr('style');
        jQuery('.faq_item span').removeClass('canRepeat');
      }
      nextSound.play();
      jQuery('.uploaded_pics_wrapper').draggable( "disable");
      jQuery('.wizard_prots, .wizard_to_protDiag').addClass('hidden');
      jQuery('.ring, .wizard_templates, .wizard_to_protList').removeClass('hidden');
      jQuery('.ring, .zone_ring').css('transform', 'rotate(0deg)');
      jQuery('.wizard_to_what_way, .wizard_to_protDiag').addClass('hidden');
      jQuery('.wizard_to_protList, .wizard_play, .wizard_starter_alt').fadeIn(500).removeClass('hidden');
      jQuery('.wizard_templates').fadeIn(500);
      jQuery('.wizard_template').addClass('hidden');
      jQuery('.uploaded_pics_wrapper').addClass('slow_top');
      var img_1 = parseFloat(jQuery('.uploaded_pics_wrapper_1').css('top'));
      var img_2 = parseFloat(jQuery('.uploaded_pics_wrapper_2').css('top'));
      if (img_1 < 0) {
        jQuery('.uploaded_pics_wrapper_1').css('top', 0);
        jQuery('.uploaded_pics_wrapper_2').css('top', (img_2+img_2)+'px');
      } else if (img_2 < 0) {
        jQuery('.uploaded_pics_wrapper_2').css('top', 0);
        jQuery('.uploaded_pics_wrapper_1').css('top', (img_1+img_1)+'px');
      }
      setTimeout(function(){
        jQuery('.uploaded_pics_wrapper').removeClass('slow_top');
      },1000);
      if (jQuery(this).hasClass('faq_item_1')) {
        cur_protocol = 'un';
        jQuery('.wizard_template_4').removeClass('hidden');
        jQuery('.wizard_templates').removeClass('wizard_templates_mw wizard_templates_ww wizard_templates_mm').addClass('wizard_templates_un');
        jQuery('.wizard_heading').removeClass('unvisible').text('Протокол "Инверсный". Перенесите зоны на фото');
      } else if (jQuery(this).hasClass('faq_item_2')) {
        cur_protocol = 'mw';
        jQuery('.wizard_template_1').removeClass('hidden');
        jQuery('.wizard_templates').removeClass('wizard_templates_un wizard_templates_ww wizard_templates_mm').addClass('wizard_templates_mw');
        jQuery('.wizard_heading').removeClass('unvisible').text('Протокол "Классический". Перенесите зоны на фото');
      } else if (jQuery(this).hasClass('faq_item_3')) {
        cur_protocol = 'ww';
        jQuery('.wizard_template_2').removeClass('hidden');
        jQuery('.wizard_templates').removeClass('wizard_templates_un wizard_templates_mw wizard_templates_mm').addClass('wizard_templates_ww');
        jQuery('.wizard_heading').removeClass('unvisible').text('Протокол "Женский". Перенесите зоны на фото');
      } else if (jQuery(this).hasClass('faq_item_4')) {
        cur_protocol = 'mm';
        jQuery('.wizard_template_3').removeClass('hidden');
        jQuery('.wizard_templates').removeClass('wizard_templates_un wizard_templates_mw wizard_templates_ww').addClass('wizard_templates_mm');
        jQuery('.wizard_heading').removeClass('unvisible').text('Протокол "Мужской". Перенесите зоны на фото');
      }
      localStorage.setItem('cur_protocol', cur_protocol);
      console.log(cur_protocol);
      jQuery('.faq_item').tooltip('destroy');
    }
  });

   //Назад. К списку протоколов
  jQuery('.wizard_to_protList').on('click', function(event) {
    if (!jQuery(this).hasClass('prot_in_progress')) {
      jQuery(this).addClass('hidden');
      jQuery('.wizard_way').removeClass('col-sm-12 col-md-12').addClass('col-sm-6 col-md-6');
      jQuery('.wizard_prots').removeClass('col-sm-1 col-md-1 hidden').addClass('col-sm-6 col-md-6');
      jQuery('.wizard_templates, .wizard_play').addClass('hidden');
      jQuery('.wizard_to_protDiag, .wizard_prots').removeClass('hidden');
      jQuery('.wizard_heading').text('Выберите протокол').removeClass('unvisible');
      jQuery('.uploaded_pics_wrapper').draggable( "enable");
    }
  });

  jQuery('.wizard_clean_graf').on('click', function(event) {
    jQuery('.knife_rate').detach();
    jQuery(this).addClass('hidden');
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
                  jQuery('.wm_start').removeAttr('style');
                  jQuery('.wizard_crop').fadeIn(500);
                  jQuery('.wizard_crop').removeClass('hidden');
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
                      console.log(jQuery('.step2').width());
                      jQuery('#preview').Jcrop({
                          minSize: [32, 32],// keep aspect ratio 1:1
                          bgFade: true, // use fade effect
                          bgOpacity: .3, // fade opacity
                          aspectRatio: 1/1.5,
                          boxWidth: jQuery('.step2').width(),
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
});
