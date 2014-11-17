<?php
/*
 Template Name: Upload Template
*/
?>

<?php get_header(); ?>

<div id="content">

    <div id="inner-content" class="wrap cf">

        <div id="main" class="m-all t-2of3 cf home-page-content" role="main">
        <?php if(is_user_logged_in()){ ?>

            <div class="bbody">

                <!-- upload form -->

                <form id="upload_form" action="" enctype="multipart/form-data" method="post"><!-- hidden crop params -->
                <input id="x1" name="x1" type="hidden" />
                <input id="y1" name="y1" type="hidden" />
                <input id="x2" name="x2" type="hidden" />
                <input id="y2" name="y2" type="hidden" />
                <h2>Step1: Please select image file</h2>
                <div><input id="image_file" name="image_file" type="file" /></div>
                <div class="error"></div>
                <div class="step2">
                <h2>Step2: Please select a crop region</h2>
                <img id="preview" alt="" />
                <div class="info"><label>File size</label> <input id="filesize" name="filesize" type="text" />
                <label>Type</label> <input id="filetype" name="filetype" type="text" />
                <label>Image dimension</label> <input id="filedim" name="filedim" type="text" />
                <label>W</label> <input id="w" name="w" type="text" />
                <label>H</label> <input id="h" name="h" type="text" /></div>
                <input type="submit" value="Upload" name="magic" />

                </div>
                </form>
            
            </div>



            <div id="snaptarget" class="wrapper">
            <ul class="itemlist itemlist-one">
                <li id="draggable0" class="itemlist_item item_list__mid draggable" style="left: 180px; top: 15px;">V0</li>
                <li id="draggable1" class="itemlist_item item_list__mid draggable" style="left: 180px; top: 60px;">V1</li>
                <li id="draggable2" class="itemlist_item item_list__mid draggable" style="left: 180px; top: 120px;">V2</li>
                <li id="draggable3" class="itemlist_item item_list__mid draggable" style="left: 180px; top: 170px;">V3</li>
                <li id="draggable4" class="itemlist_item item_list__mid draggable" style="left: 180px; top: 200px;">V4</li>
                <li id="draggable5" class="itemlist_item item_list__mid draggable" style="left: 180px; top: 235px;">V5</li>
                <li id="draggableD1" class="itemlist_item item_list__mid draggable" style="left: 135px; top: 80px;">D+</li>
                <li id="draggableClean" class="itemlist_item item_list__mid itemlist_item__clear draggable" style="left: 45px; top: 215px;"></li>
                <li id="draggableS2" class="itemlist_item item_list__mid draggable" style="left: 225px; top: 110px;">S2</li>
                <li id="draggableS2_1" class="itemlist_item item_list__mid draggable" style="left: 220px; top: 110px;">S2</li>
                <li id="draggableS3" class="itemlist_item item_list__mid draggable" style="left: 215px; top: 110px;">S3</li>
                <li id="draggableS4" class="itemlist_item item_list__mid draggable" style="left: 215px; top: 110px;">S4</li>
                <li id="draggableS5" class="itemlist_item item_list__mid draggable" style="left: 205px; top: 135px;">S5</li>
                <li id="draggableS6" class="itemlist_item item_list__mid draggable" style="left: 230px; top: 400px;">S6</li>
                <li id="draggableV-" class="itemlist_item item_list__mid draggable" style="left: 180px; top: 470px;">V-</li>
            </ul>
            <ul class="itemlist itemlist-two"><!--
                <li class="itemlist_item">1</li>
                <li class="itemlist_item">2</li>
                <li class="itemlist_item">3</li>
                <li class="itemlist_item">4</li>
                <li class="itemlist_item">5</li>
            --></ul>
            </div>
        <?php } else { ?>
            <div class="login-area">
               <p>Пожалуйста <a href="/registration">Зарегестрируйтесь</a> сайте</br> или <a href="/admin">Авторизуйтесь</a></p> 
            </div>
        <?php } ?>

        </div>

    </div>

</div>


<?php get_footer(); ?>
