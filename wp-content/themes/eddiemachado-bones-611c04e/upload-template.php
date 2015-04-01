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



<?php
    if($_POST['mci_magic']){
        $sImage = uploadImageFile();
        echo '<img src="'.$sImage.'" />';
    }
?>



            <?php if(current_user_can('contributor') || current_user_can('administrator')) { ?>
                
                <div class="fast-protocol-wrap clearfix hidden">
                  <div class="btn btn_sm btn_warning fast-protocol" data-fast="v2">V2</div> 
                  <div class="btn btn_sm btn_warning fast-protocol" data-fast="v3">V3</div> 
                  <div class="btn btn_sm btn_warning fast-protocol" data-fast="v4">V4</div> 
                  <div class="btn btn_sm btn_warning fast-protocol" data-fast="v5">V5</div>
                </div>

                <div class="steps clearfix">
                    <!-- <div class="steps-center"><hr></div>
                    <div class="step step_choice step_now">1 <div></div> </div>
                    <div class="step step_img">2 <div></div> </div>
                    <div class="step step_procedure">3 <div></div>  </div> -->
                    <h3 class="heading heading_dashboard">Загрузите фото</h3>
                </div>
                <!-- <div class="btn btn_back invisible"><span>‹</span> Назад</div> -->

                <!-- <div class="machine_screen clearfix">
                    <div id="accordion" class="select_program">
                      <h3><span class="protocol_acent">(V2)</span>ЛЮДИ И ОТНОШЕНИЯ. ВЕЩИ, ПРЕДМЕТЫ И ДОСТИЖЕНИЕ ЦЕЛЕЙ</h3>
                      <div>"Забыть ее/его не могу; простить ее/его не могу; только все мысли о нем/ней; хочу ее/его;" "хочу достичь/стать/получить качества и статус"; либо "хочу заработать/купить/продать/выгодную сделку/кредит";<div class="btn btn_lg btn_success btn_choice" data-protocol ="v2">Выбрать</div></div>
                      <h3><span class="protocol_acent">(V3)</span>РАЗДРАЖЕНИЕ И НЕПРИЯТИЕ</h3>
                      <div>"Не могу с этим смириться/ это все не правильно/ раздражение от чьих-то действий, чаще бытовых,внешнего вида или слов/ грусть-тоска зеленая/ усталость от жизни;"<div class="btn btn_lg btn_success btn_choice" data-protocol ="v3">Выбрать</div></div>
                      <h3><span class="protocol_acent">(V4)</span>СУЕТА, БЕСПОКОЙСТВО И НАПРЯЖЕНИЕ</h3>
                      <div>«… опоздаю не успею… тороплюсь, не успеваю… напряжение в теле и голове… а что будет… а кто что скажет/подумает про меня… а как я буду выглядеть в глазах окружающих… я стараюсь все лучшим образом делать… и  далее, в этом роде…"<div class="btn btn_lg btn_success btn_choice" data-protocol ="v4">Выбрать</div></div>
                      <h3><span class="protocol_acent">(V5)</span>ОДНИ И ТЕ ЖЕ "ГРАБЛИ»: РЕЦИВЫ ПРОБЛЕМНЫХ СИТУАЦИЙ В ЛИЧНОЙ ЖИЗНИ</h3>
                      <div>Насильственное следование некой жизненной стратегии, стойкие убеждения и ценности, основанные на опасениях и самоограничениях, навязчивые мысли и действия  по типу "частого мытья рук» или чего иного, постоянное беспокойство по поводу «курса валют»; ситуации по проверке себя с вовлечением окружающих «…ой, а не забыл ли я выключить чайник…утюг…газовый кран.. .дверь дома», «…проверь ты, а то я не уверен/на..»; страх высоты или нанесения себе повреждения ножиком или другим опасным предметом; создание больших запасов еды по типу «битком забитого холодильника, мешка сахара и муки»<div class="btn btn_lg btn_success btn_choice" data-protocol ="v5">Выбрать</div></div>
                    </div>
                </div> -->
                <div class="machine_screen clearfix">
                    <div class="protList">
                        <button class="btn btn_lg btn_success btn_choice" data-protocol="mw" style="background: url(../images/mw.png) right center/100%">М/Ж</button>
                        <button class="btn btn_lg btn_success btn_choice" data-protocol="ww">Назад</button>
                        <button class="btn btn_lg btn_success btn_choice" data-protocol="mm">Назад</button>
                    </div>
                </div>
                <div class="machine_screen clearfix hidden">
                    <div class="bbody">

                        <!-- upload form -->

                        <form id="upload_form" action="/wizard/" enctype="multipart/form-data" method="post"><!-- hidden crop params -->
                        <input id="x1" name="mci_x1" type="hidden" />
                        <input id="y1" name="mci_y1" type="hidden" />
                        <input id="x2" name="mci_x2" type="hidden" />
                        <input id="y2" name="mci_y2" type="hidden" />
                        <!-- <h2>Выберите изображение</h2> -->
                        <div><input id="image_file" name="mci_image_file" type="file" /></div>
                        <div class="error"></div>
                        <div class="step2">
                        <h3>Выделите область для обрезки</h3>
                        <img id="preview" alt="" />
                        <!--<canvas id="preview-canvas" style="border: 3px red solid;/*position: absolute; visibility: hidden; /*left: -20000px*/"></canvas>-->
                        <div class="info"><label>Размер файла</label> <input id="filesize" name="mci_filesize" type="text" />
                        <label>Тип</label> <input id="filetype" name="mci_filetype" type="text" />
                        <label>Разрешение изображения</label> <input id="filedim" name="mci_filedim" type="text" />
                        <label>Ширина</label> <input id="w" name="mci_w" type="text" />
                        <label>Высота</label> <input id="h" name="mci_h" type="text" /></div>
                        <input type="submit" class="crop_photo" value="Редактировать фото" name="mci_magic" />
                        </div>
                        </form>
                    
                    </div>
                </div>

                <div class="machine_screen work-area clearfix hidden">
                    <div id="snaptarget" class="wrapper">
                        <ul class="itemlist itemlist-one" id="itemlist-one">
                            <svg id="example" height="700" version="1.0" xmlns="http://www.w3.org/2000/svg"
                             width="200.000000pt" height="241.000000pt" viewBox="0 0 200.000000 241.000000"
                             preserveAspectRatio="xMidYMid meet">
                            <metadata>
                            <!-- Created by potrace 1.10, written by Peter Selinger 2001-2011 -->
                            </metadata>
                            <g transform="translate(0.000000,241.000000) scale(0.100000,-0.100000)"
                            fill="#000000" stroke="none">
                            <path d="M579 2286 c-49 -28 -63 -95 -46 -218 8 -55 7 -58 -20 -74 -94 -58
                            -140 -96 -169 -140 -19 -28 -36 -166 -22 -175 13 -8 -27 -122 -60 -169 -53
                            -75 -94 -110 -128 -110 -34 0 -52 -14 -34 -25 6 -4 3 -16 -10 -32 -28 -36 -26
                            -63 4 -63 14 0 28 5 31 10 4 6 11 8 16 4 6 -3 29 14 52 38 23 24 65 59 94 77
                            81 51 120 100 135 170 14 72 34 83 24 14 -39 -276 -42 -384 -34 -1034 4 -260
                            2 -370 -6 -380 -6 -8 -23 -20 -38 -26 -16 -7 -28 -19 -28 -28 0 -12 18 -14
                            113 -12 l112 2 8 90 c4 50 5 148 3 220 -3 72 -1 159 4 195 5 36 13 127 16 203
                            4 75 10 137 14 137 10 0 18 -158 14 -305 -6 -263 -6 -533 0 -544 9 -15 236
                            -15 236 -1 0 6 -16 20 -36 32 l-37 22 7 195 c3 108 8 271 10 362 2 92 8 175
                            13 185 12 25 -2 400 -16 444 -11 35 -19 266 -9 275 5 5 25 -58 32 -95 6 -36
                            41 -131 73 -198 32 -65 35 -78 29 -126 -8 -60 17 -144 34 -117 7 12 11 12 25
                            -2 27 -28 54 -21 71 18 8 19 12 46 8 59 -4 18 13 61 64 163 72 141 90 189 113
                            301 17 83 26 63 45 -98 5 -44 1 -71 -20 -135 -14 -44 -31 -120 -37 -170 -12
                            -102 -7 -284 11 -365 7 -33 11 -142 11 -275 0 -121 4 -260 9 -310 11 -102 1
                            -129 -51 -147 -49 -17 -29 -33 37 -31 105 5 99 -3 100 120 0 59 2 181 5 270 4
                            147 6 163 22 163 15 0 17 -12 19 -107 1 -100 -2 -346 -5 -421 l-2 -33 78 -4
                            c42 -2 77 -1 77 3 0 4 -15 17 -34 29 -25 16 -35 30 -39 60 -7 46 0 130 16 198
                            6 28 11 101 11 163 l1 112 57 0 c31 0 69 5 84 10 26 10 26 11 8 25 -18 13 -17
                            14 15 15 18 0 50 10 69 22 l36 22 -32 8 c-45 12 -40 19 13 17 35 -2 48 2 60
                            17 13 18 11 20 -31 40 -53 25 -84 52 -84 71 0 8 -16 33 -35 55 l-34 41 19 25
                            c24 30 26 69 4 93 -8 9 -15 25 -15 35 0 11 3 153 6 317 5 257 4 301 -10 320
                            -14 19 -15 -1 -16 -228 -1 -162 -5 -231 -9 -195 -5 30 -8 119 -9 197 -1 151
                            -9 175 -43 134 -25 -32 -58 -144 -58 -200 0 -25 11 -84 24 -131 13 -47 29
                            -110 35 -141 7 -31 19 -65 27 -75 9 -11 13 -14 10 -8 -3 7 -8 29 -11 50 -3 22
                            -8 57 -11 79 -3 22 -15 71 -25 110 -10 38 -19 91 -19 118 0 47 39 197 51 197
                            3 0 3 -22 -2 -49 -15 -98 8 -469 33 -515 8 -14 7 -17 -4 -12 -7 4 -3 -1 10
                            -10 26 -19 28 -41 6 -72 -19 -28 -11 -72 17 -98 12 -10 26 -32 33 -49 8 -17
                            28 -43 46 -58 18 -15 31 -29 29 -30 -2 -1 -13 -7 -24 -13 -11 -6 -20 -20 -20
                            -30 0 -14 -9 -20 -35 -24 -24 -3 -38 -12 -45 -27 -8 -20 -17 -23 -60 -23 -88
                            0 -93 -8 -99 -168 -15 -375 -17 -392 -33 -392 -10 0 -13 6 -8 17 11 30 7 500
                            -5 523 -12 22 -52 27 -67 8 -6 -7 -13 -73 -17 -145 -19 -413 -17 -393 -38
                            -393 -11 0 -18 3 -16 8 2 4 10 20 18 35 11 22 11 33 3 47 -8 12 -12 108 -13
                            297 0 153 -5 303 -11 333 -17 88 -22 291 -10 384 6 46 21 111 32 143 29 83 32
                            131 14 220 -18 85 -39 120 -61 102 -13 -10 -20 -33 -61 -179 -15 -54 -94 -222
                            -102 -215 -4 5 39 135 50 150 22 31 62 163 75 250 8 55 16 100 17 101 1 1 31
                            17 65 36 35 18 71 45 80 60 15 25 15 28 -3 47 -10 12 -26 21 -36 21 -23 0 -32
                            32 -30 105 1 51 5 63 27 81 28 23 96 30 137 15 28 -11 117 -96 117 -112 0 -6
                            23 -35 51 -65 50 -52 53 -54 106 -54 46 0 58 -4 78 -26 27 -29 31 -49 14 -60
                            -6 -3 -9 -21 -7 -40 3 -26 0 -34 -15 -38 -11 -3 -30 2 -42 10 -26 17 -125 16
                            -125 -2 0 -26 73 -63 86 -43 3 5 -1 9 -9 9 -8 0 -21 8 -28 17 -12 15 -12 16 6
                            9 11 -5 29 -9 40 -11 12 -1 21 -10 23 -24 3 -17 7 -20 17 -11 9 8 14 7 19 -5
                            3 -8 16 -15 28 -15 l22 0 -22 16 c-12 8 -22 19 -22 23 0 15 47 -1 75 -25 15
                            -13 30 -24 31 -24 7 0 -10 36 -24 53 -10 11 -11 17 -4 17 7 0 12 5 12 10 0 6
                            -5 10 -11 10 -15 0 -38 30 -39 49 0 12 3 12 12 3 16 -16 31 -15 22 1 -5 6 8
                            -1 27 -18 20 -16 34 -32 32 -34 -2 -3 -14 6 -28 19 -14 13 -25 19 -25 13 0
                            -20 38 -45 59 -38 l21 6 -21 18 c-13 10 -23 24 -25 32 -5 36 -10 44 -29 49
                            -11 3 -24 16 -30 28 -5 12 -18 25 -27 28 -29 9 -21 27 8 20 18 -5 24 -3 19 5
                            -5 7 -23 9 -51 5 -31 -4 -44 -2 -44 6 0 9 -3 9 -12 0 -18 -18 -44 -14 -73 11
                            l-27 22 49 -2 48 -2 -46 9 c-68 12 -122 44 -66 40 44 -4 51 2 14 11 -28 6 -55
                            26 -95 68 -64 66 -87 76 -168 69 -46 -4 -61 -10 -87 -37 -29 -29 -32 -37 -32
                            -96 0 -36 4 -65 8 -65 5 0 6 -4 3 -9 -3 -5 1 -22 9 -38 12 -25 20 -29 41 -25
                            21 4 25 1 22 -14 -2 -12 -28 -31 -68 -51 -36 -18 -70 -40 -77 -50 -7 -10 -19
                            -63 -27 -118 -14 -90 -46 -203 -65 -230 -4 -5 -27 -63 -50 -127 -24 -65 -47
                            -116 -51 -113 -14 9 -28 65 -44 177 -9 59 -23 126 -32 150 -36 99 -38 109 -27
                            127 9 14 4 36 -21 103 -45 118 -55 131 -136 169 -38 18 -70 37 -70 41 0 4 18
                            8 40 8 42 0 47 5 52 50 2 14 6 30 9 35 3 6 5 39 5 74 -1 60 -3 65 -36 92 -44
                            35 -130 48 -171 25z m149 -46 c30 -18 32 -23 32 -75 0 -30 -4 -63 -9 -73 -5
                            -9 -12 -24 -14 -33 -3 -9 -19 -19 -35 -22 -35 -7 -51 -40 -31 -63 7 -9 43 -31
                            80 -50 l67 -33 31 -81 c23 -60 30 -89 25 -117 -4 -27 2 -57 20 -106 22 -58 56
                            -230 56 -284 0 -30 -58 103 -91 208 -17 54 -39 109 -50 121 -19 23 -19 23 -35
                            3 -13 -17 -15 -42 -10 -155 2 -74 9 -157 15 -185 13 -63 13 -296 1 -490 -18
                            -295 -24 -629 -11 -653 12 -22 11 -22 -55 -22 l-67 0 5 338 c6 371 -5 512 -40
                            512 -33 0 -68 -287 -64 -535 1 -88 -2 -191 -6 -230 l-7 -70 -57 0 c-57 0 -57
                            0 -42 22 12 20 13 86 4 505 -6 265 -8 500 -5 523 2 22 7 76 11 120 3 44 10
                            116 15 160 14 121 12 175 -6 182 -25 9 -51 -24 -64 -82 -13 -58 -42 -91 -131
                            -147 -25 -15 -58 -43 -75 -62 -16 -19 -40 -37 -53 -41 -22 -6 -22 -5 -10 14 7
                            12 20 21 28 21 17 0 104 86 139 137 41 60 63 142 64 240 2 87 3 93 32 123 17
                            17 60 52 97 76 37 25 70 53 73 63 3 11 5 64 4 120 -2 85 0 104 16 121 25 27
                            107 28 153 0z m296 -1031 c11 9 13 3 9 -36 -7 -74 -8 -75 -43 -55 -36 21 -44
                            40 -35 93 l7 40 24 -26 c19 -21 28 -24 38 -16z"/>
                            <path d="M1868 1933 c7 -3 16 -2 19 1 4 3 -2 6 -13 5 -11 0 -14 -3 -6 -6z"/>
                            </g>
                            </svg>
                            <li id="draggable0" class="itemlist_item itemZone item_list__mid draggable" style="left: 101px; top: 21px;">V0</li>
                            <li id="draggable1" class="itemlist_item itemZone item_list__mid draggable" style="left: 100px; top: 66px;">V1</li>
                            <li id="draggable2" class="itemlist_item itemZone item_list__mid draggable" style="left: 94px; top: 121px;">V2</li>
                            <li id="draggable3" class="itemlist_item itemZone item_list__mid draggable" style="left: 93px; top: 176px;">V3</li>
                            <li id="draggable4" class="itemlist_item itemZone item_list__mid draggable" style="left: 90px; top: 234px;">V4</li>
                            <li id="draggable5" class="itemlist_item itemZone item_list__mid draggable" style="left: 90px; top: 282px;">V5</li>
                            <li id="draggableD1" class="itemlist_item itemZone item_list__mid draggable" style="left: 52px; top: 65px;">D+</li>
                            <li id="draggableD11" class="itemlist_item itemZone item_list__mid draggable" style="left: 53px; top: 20px; font-size: 22px; padding-top: 10px;">D++</li>
                            <!-- <li id="draggableClean" class="itemlist_item itemZone item_list__mid itemlist_item__clear draggable" style="left: 20px; top: 262px;"></li> -->
                            <!-- <li id="draggableS2" class="itemlist_item itemZone item_list__mid draggable" style="left: 216px; top: 110px;">S2</li> -->
                            <!-- <li id="draggableS2_1" class="itemlist_item itemZone item_list__mid draggable" style="left: 212px; top: 163px;">S2</li> -->
                            <!-- <li id="draggableS3" class="itemlist_item itemZone item_list__mid draggable" style="left: 203px; top: 196px;">S3</li> -->
                            <!-- <li id="draggableS4" class="itemlist_item itemZone item_list__mid draggable" style="left: 201px; top: 244px;">S4</li> -->
                            <!-- <li id="draggableS5" class="itemlist_item itemZone item_list__mid draggable" style="left: 218px; top: 298px;">S5</li> -->
                            <!-- <li id="draggableS6" class="itemlist_item itemZone item_list__mid draggable" style="left: 239px; top: 429px;">S6</li> -->
                            <li id="draggable6" class="itemlist_item itemZone item_list__mid draggable" style="left: 89px; top: 355px;">V6</li>
                            <li id="draggableD2" class="itemlist_item itemZone item_list__mid draggable" style="left: 43px; top: 122px;">D2</li>
                            <li id="draggableD22" class="itemlist_item itemZone item_list__mid draggable" style="left: 63px; top: 175px;">D2</li>
                            <li id="draggableD3" class="itemlist_item itemZone item_list__mid draggable" style="left: 59px; top: 205px;">D3</li>
                            <li id="draggableD5" class="itemlist_item itemZone item_list__mid draggable" style="left: 55px; top: 328px;">D5</li>
                            <li id="draggableD4" class="itemlist_item itemZone item_list__mid draggable" style="left: 57px; top: 260px;">D4</li>
                            <li id="draggableD6" class="itemlist_item itemZone item_list__mid draggable" style="left: 51px; top: 404px;">D6</li>
                            <li id="draggableD7" class="itemlist_item itemZone item_list__mid draggable" style="left: 51px; top: 450px;">D7</li>
                            <!-- <li id="draggableClean_2" class="itemlist_item itemZone item_list__mid itemlist_item__clear draggable inopaciti" style="left: 535px; top: 80px;"></li> -->
                            <!-- <li id="draggableClean_3" class="itemlist_item itemZone item_list__mid itemlist_item__clear draggable inopaciti" style="left: 535px; top: 80px;"></li> -->
                            <!-- <li id="draggableClean_4" class="itemlist_item itemZone item_list__mid itemlist_item__clear draggable inopaciti" style="left: 535px; top: 80px;"></li> -->
                            <!-- <li id="draggableClean_5" class="itemlist_item itemZone item_list__mid itemlist_item__clear draggable inopaciti" style="left: 535px; top: 80px;"></li> -->

                            <li id="draggable0_1" class="itemlist_item itemZone item_list__mid draggable" style="left: 248px; top: 30px;">V0</li>
                            <li id="draggable1_1" class="itemlist_item itemZone item_list__mid draggable" style="left: 247px; top: 81px;">V1</li>
                            <li id="draggable2_1" class="itemlist_item itemZone item_list__mid draggable" style="left: 247px; top: 127px;">V2</li>
                            <li id="draggable3_1" class="itemlist_item itemZone item_list__mid draggable" style="left: 247px; top: 183px;">V3</li>
                            <li id="draggable4_1" class="itemlist_item itemZone item_list__mid draggable" style="left: 245px; top: 242px;">V4</li>
                            <li id="draggable5_1" class="itemlist_item itemZone item_list__mid draggable" style="left: 245px; top: 293px;">V5</li>
                            <li id="draggableD1_1" class="itemlist_item itemZone item_list__mid draggable" style="left: 194px; top: 81px;">D+</li>
                            <li id="draggableD11_1" class="itemlist_item itemZone item_list__mid draggable" style="left: 197px; top: 29px; font-size: 22px; padding-top: 10px;">D++</li>
                            <!-- <li id="draggableClean_1" class="itemlist_item itemZone item_list__mid itemlist_item__clear draggable" style="left: 20px; top: 262px;"></li> -->
                            <!-- <li id="draggableS2_1" class="itemlist_item itemZone item_list__mid draggable" style="left: 216px; top: 110px;">S2</li> -->
                            <!-- <li id="draggableS2_1_1" class="itemlist_item itemZone item_list__mid draggable" style="left: 212px; top: 163px;">S2</li> -->
                            <!-- <li id="draggableS3_1" class="itemlist_item itemZone item_list__mid draggable" style="left: 203px; top: 196px;">S3</li> -->
                            <!-- <li id="draggableS4_1" class="itemlist_item itemZone item_list__mid draggable" style="left: 201px; top: 244px;">S4</li> -->
                            <!-- <li id="draggableS5_1" class="itemlist_item itemZone item_list__mid draggable" style="left: 218px; top: 298px;">S5</li> -->
                            <!-- <li id="draggableS6_1" class="itemlist_item itemZone item_list__mid draggable" style="left: 239px; top: 429px;">S6</li> -->
                            <li id="draggable6_1" class="itemlist_item itemZone item_list__mid draggable" style="left: 242px; top: 362px;">V6</li>
                            <li id="draggableD2_1" class="itemlist_item itemZone item_list__mid draggable" style="left: 209px; top: 129px;">D2</li>
                            <li id="draggableD22_1" class="itemlist_item itemZone item_list__mid draggable" style="left: 227px; top: 167px;">D2</li>
                            <li id="draggableD3_1" class="itemlist_item itemZone item_list__mid draggable" style="left: 224px; top: 205px;">D3</li>
                            <li id="draggableD5_1" class="itemlist_item itemZone item_list__mid draggable" style="left: 220px; top: 331px;">D5</li>
                            <li id="draggableD4_1" class="itemlist_item itemZone item_list__mid draggable" style="left: 213px; top: 261px;">D4</li>
                            <li id="draggableD6_1" class="itemlist_item itemZone item_list__mid draggable" style="left: 215px; top: 408px;">D6</li>
                            <li id="draggableD7_1" class="itemlist_item itemZone item_list__mid draggable" style="left: 214px; top: 452px;">D7</li>
                            <!-- <li id="draggableClean_2_1" class="itemlist_item itemZone item_list__mid itemlist_item__clear draggable inopaciti" style="left: 535px; top: 80px;"></li> -->
                            <!-- <li id="draggableClean_3_1" class="itemlist_item itemZone item_list__mid itemlist_item__clear draggable inopaciti" style="left: 535px; top: 80px;"></li> -->
                            <!-- <li id="draggableClean_4_1" class="itemlist_item itemZone item_list__mid itemlist_item__clear draggable inopaciti" style="left: 535px; top: 80px;"></li> -->
                            <!-- <li id="draggableClean_5_1" class="itemlist_item itemZone item_list__mid itemlist_item__clear draggable inopaciti" style="left: 535px; top: 80px;"></li> -->
                        </ul>
                        <ul class="itemlist itemlist-two" id="itemlist-two">
                        <!--
                            <li class="itemlist_item">1</li>
                            <li class="itemlist_item">2</li>
                            <li class="itemlist_item">3</li>
                            <li class="itemlist_item">4</li>
                            <li class="itemlist_item">5</li>
                        -->
                        <li class="itemlist-two-li"></li>
                        </ul>
                        <div class="box_rounded">
                            <li id="draggableD12" class="itemlist_item item_list__mid draggable hidden" style="left: 45%; top: 5px;"></li>
                        </div>
                        <div class="contentAlignCenter">
                            <!-- <div class="btn btn_lg btn_trans_action btn__wizard" >Выполнить</div> -->
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <div style="text-align: center">Вы получили это письмо, потому, что зарегистрировались на сайте <a href="http://wizardmachine.ru/">wizardmachine</a>. Администрация сайта  доводит до вашего сведения информацию о том, что получение доступа на сайт происходит после предварительного обучения пользователя. Обучение будет проходить в виде очного либо дистантного семинара. По всем вопросам обращаться к Роману <a href="mailto:info@bablosstudio.ru">info@bablosstudio.ru</a></div>
            <?php } ?>
        <?php } else { ?>
            <div class="login__form">
                <form name="loginform" id="loginform" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" method="post">
                    <p>
                        <label for="user_login"><?php _e('Username') ?><br />
                        <input type="text" name="log" id="user_login" class="input" value="<?php echo esc_attr($user_login); ?>" size="20" /></label>
                    </p>
                    <p>
                        <label for="user_pass"><?php _e('Password') ?><br />
                        <input type="password" name="pwd" id="user_pass" class="input" value="" size="20" /></label>
                    </p>
                    <?php
                    /**
                     * Fires following the 'Password' field in the login form.
                     *
                     * @since 2.1.0
                     */
                    do_action( 'login_form' );
                    ?>
                    <!-- <p class="note_small">Что бы получить доступ </p> -->
                    <p class="forgetmenot"><label for="rememberme"><input name="rememberme" type="checkbox" id="rememberme" value="forever" <?php checked( $rememberme ); ?> /> <?php esc_attr_e('Remember Me'); ?></label></p>
                    <p class="submit">
                        <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e('Log In'); ?>" />
                <?php   if ( $interim_login ) { ?>
                        <input type="hidden" name="interim-login" value="1" />
                <?php   } else { ?>
                        <input type="hidden" name="redirect_to" value="<?php echo esc_attr($redirect_to); ?>" />
                <?php   } ?>
                <?php   if ( $customize_login ) : ?>
                        <input type="hidden" name="customize-login" value="1" />
                <?php   endif; ?>
                        <input type="hidden" name="testcookie" value="1" />
                    </p>
                </form>
            </div>
        <?php } ?>

        </div>

    </div>

</div>


<?php get_footer(); ?>
