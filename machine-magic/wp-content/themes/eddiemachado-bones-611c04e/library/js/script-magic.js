jQuery(function() {
    jQuery( ".draggable" ).draggable({ snap: true });

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
        jQuery('.error').html('Please select a crop region and then press Upload').show();
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
            jQuery('.error').html('Please select a valid image file (jpg and png are allowed)').show();
            return;
        }

        // check for file size
        if (oFile.size > 250 * 1024) {
            jQuery('.error').html('You have selected too big file, please select a one smaller image file').show();
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
                // jQuery('.step2').fadeIn(500);
                jQuery('.btn__crop').removeClass('hidden');
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
