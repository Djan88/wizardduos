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
                    <h3 class="heading heading_dashboard">Переместить элементы</h3>
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
                        <input type="submit" value="Редактировать фото" name="mci_magic" />
                        </div>
                        </form>
                    
                    </div>
                </div>

                <div class="machine_screen work-area clearfix hidden">
                    <div id="snaptarget" class="wrapper">
                        <ul class="itemlist itemlist-one" id="itemlist-one">
                            <li id="draggable0" class="itemlist_item item_list__mid draggable" style="left: 168px; top: 16px;">V0</li>
                            <li id="draggable1" class="itemlist_item item_list__mid draggable" style="left: 168px; top: 54px;">V1
                                <svg id="triangle_1" class="triangle hidden"
                                xmlns:dc="http://purl.org/dc/elements/1.1/"
                                xmlns:cc="http://creativecommons.org/ns#"
                                xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
                                xmlns:svg="http://www.w3.org/2000/svg"
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
                                xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
                                version="1.1"
                                width="360.26767"
                                height="318.20004"
                                id="svg2816"
                                xml:space="preserve"
                                inkscape:version="0.48.4 r9939"
                                sodipodi:docname="AJAX.svg"><metadata
                                  id="metadata81"><rdf:RDF><cc:Work
                                      rdf:about=""><dc:format>image/svg+xml</dc:format><dc:type
                                        rdf:resource="http://purl.org/dc/dcmitype/StillImage" /><dc:title></dc:title></cc:Work></rdf:RDF></metadata><sodipodi:namedview
                                  pagecolor="#ffffff"
                                  bordercolor="#666666"
                                  borderopacity="1"
                                  objecttolerance="10"
                                  gridtolerance="10"
                                  guidetolerance="10"
                                  inkscape:pageopacity="0"
                                  inkscape:pageshadow="2"
                                  inkscape:window-width="1920"
                                  inkscape:window-height="1057"
                                  id="namedview79"
                                  showgrid="false"
                                  fit-margin-top="0.1"
                                  fit-margin-left="0.1"
                                  fit-margin-right="0.1"
                                  fit-margin-bottom="0.1"
                                  inkscape:zoom="1.4142137"
                                  inkscape:cx="81.708362"
                                  inkscape:cy="105.66257"
                                  inkscape:window-x="-8"
                                  inkscape:window-y="-8"
                                  inkscape:window-maximized="1"
                                  inkscape:current-layer="svg2816"
                                  showguides="true"
                                  inkscape:guide-bbox="true" /><defs
                                  id="defs2820"><clipPath
                                    id="clipPath2832"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path2834"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath2840"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path2842"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath2858"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path2860"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath2864"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path2866"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3306"><path
                                      d="m 199.238,472.977 0,-29.74 29.744,0 0,29.74"
                                      id="path3308"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3322"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path3324"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3330"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path3332"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3344"><path
                                      d="m 88.233,387.221 0,-28.335 32.08,0 0,28.335"
                                      id="path3346"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3462"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path3464"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3470"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path3472"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3490"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path3492"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3496"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path3498"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3520"><path
                                      d="m 254.788,326.215 0,-28.309 28.326,0 0,28.309"
                                      id="path3522"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3544"><path
                                      d="m 145.049,326.142 0,-28.333 28.342,0 0,28.333"
                                      id="path3546"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3682"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path3684"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3690"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path3692"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3980"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path3982"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3988"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path3990"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4010"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path4012"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4016"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path4018"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4062"><path
                                      d="m 200.163,177.773 0,-28.284 28.283,0 0,28.284"
                                      id="path4064"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4090"><path
                                      d="m 34.886,177.515 0,-51.632 30.367,0 0,51.632"
                                      id="path4092"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4106"><path
                                      d="m 34.886,177.515 0,-51.632 30.367,0 0,51.632"
                                      id="path4108"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4272"><path
                                      d="m 34.886,177.515 0,-51.632 30.367,0 0,51.632"
                                      id="path4274"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4304"><path
                                      d="m 254.653,176.442 0,-48.167 28.317,0 0,48.167"
                                      id="path4306"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4310"><path
                                      d="m 253.565,177.983 31.181,0 0,-49.183 -31.181,0 0,49.183 z"
                                      id="path4312"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4316"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path4318"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4322"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path4324"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4456"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path4458"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4464"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path4466"
                                      inkscape:connector-curvature="0" /></clipPath></defs><path
                                  inkscape:connector-curvature="0"
                                  sodipodi:nodetypes="scccscscccss" /><path
                                  inkscape:connector-curvature="0"
                                  style="fill:#ffffff;fill-opacity:1;fill-rule:evenodd;stroke:none"
                                  id="path3121"
                                  d="m 322.51281,286.28317 -284.757805,0 L 180.13391,39.806513 z"
                                  sodipodi:nodetypes="cccc" /></svg>
                            </li>
                            <li id="draggable2" class="itemlist_item item_list__mid draggable" style="left: 168px; top: 105px;">V2
                                <svg id="triangle_2" class="triangle hidden"
                                xmlns:dc="http://purl.org/dc/elements/1.1/"
                                xmlns:cc="http://creativecommons.org/ns#"
                                xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
                                xmlns:svg="http://www.w3.org/2000/svg"
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
                                xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
                                version="1.1"
                                width="360.26767"
                                height="318.20004"
                                id="svg2816"
                                xml:space="preserve"
                                inkscape:version="0.48.4 r9939"
                                sodipodi:docname="AJAX.svg"><metadata
                                  id="metadata81"><rdf:RDF><cc:Work
                                      rdf:about=""><dc:format>image/svg+xml</dc:format><dc:type
                                        rdf:resource="http://purl.org/dc/dcmitype/StillImage" /><dc:title></dc:title></cc:Work></rdf:RDF></metadata><sodipodi:namedview
                                  pagecolor="#ffffff"
                                  bordercolor="#666666"
                                  borderopacity="1"
                                  objecttolerance="10"
                                  gridtolerance="10"
                                  guidetolerance="10"
                                  inkscape:pageopacity="0"
                                  inkscape:pageshadow="2"
                                  inkscape:window-width="1920"
                                  inkscape:window-height="1057"
                                  id="namedview79"
                                  showgrid="false"
                                  fit-margin-top="0.1"
                                  fit-margin-left="0.1"
                                  fit-margin-right="0.1"
                                  fit-margin-bottom="0.1"
                                  inkscape:zoom="1.4142137"
                                  inkscape:cx="81.708362"
                                  inkscape:cy="105.66257"
                                  inkscape:window-x="-8"
                                  inkscape:window-y="-8"
                                  inkscape:window-maximized="1"
                                  inkscape:current-layer="svg2816"
                                  showguides="true"
                                  inkscape:guide-bbox="true" /><defs
                                  id="defs2820"><clipPath
                                    id="clipPath2832"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path2834"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath2840"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path2842"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath2858"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path2860"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath2864"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path2866"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3306"><path
                                      d="m 199.238,472.977 0,-29.74 29.744,0 0,29.74"
                                      id="path3308"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3322"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path3324"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3330"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path3332"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3344"><path
                                      d="m 88.233,387.221 0,-28.335 32.08,0 0,28.335"
                                      id="path3346"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3462"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path3464"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3470"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path3472"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3490"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path3492"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3496"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path3498"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3520"><path
                                      d="m 254.788,326.215 0,-28.309 28.326,0 0,28.309"
                                      id="path3522"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3544"><path
                                      d="m 145.049,326.142 0,-28.333 28.342,0 0,28.333"
                                      id="path3546"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3682"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path3684"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3690"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path3692"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3980"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path3982"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3988"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path3990"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4010"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path4012"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4016"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path4018"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4062"><path
                                      d="m 200.163,177.773 0,-28.284 28.283,0 0,28.284"
                                      id="path4064"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4090"><path
                                      d="m 34.886,177.515 0,-51.632 30.367,0 0,51.632"
                                      id="path4092"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4106"><path
                                      d="m 34.886,177.515 0,-51.632 30.367,0 0,51.632"
                                      id="path4108"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4272"><path
                                      d="m 34.886,177.515 0,-51.632 30.367,0 0,51.632"
                                      id="path4274"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4304"><path
                                      d="m 254.653,176.442 0,-48.167 28.317,0 0,48.167"
                                      id="path4306"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4310"><path
                                      d="m 253.565,177.983 31.181,0 0,-49.183 -31.181,0 0,49.183 z"
                                      id="path4312"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4316"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path4318"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4322"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path4324"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4456"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path4458"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4464"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path4466"
                                      inkscape:connector-curvature="0" /></clipPath></defs><path
                                  inkscape:connector-curvature="0"
                                  sodipodi:nodetypes="scccscscccss" /><path
                                  inkscape:connector-curvature="0"
                                  style="fill:#ffffff;fill-opacity:1;fill-rule:evenodd;stroke:none"
                                  id="path3121"
                                  d="m 322.51281,286.28317 -284.757805,0 L 180.13391,39.806513 z"
                                  sodipodi:nodetypes="cccc" /></svg>
                            </li>
                            <li id="draggable3" class="itemlist_item item_list__mid draggable" style="left: 168px; top: 166px;">V3
                                <svg id="triangle_3" class="triangle hidden"
                                xmlns:dc="http://purl.org/dc/elements/1.1/"
                                xmlns:cc="http://creativecommons.org/ns#"
                                xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
                                xmlns:svg="http://www.w3.org/2000/svg"
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
                                xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
                                version="1.1"
                                width="360.26767"
                                height="318.20004"
                                id="svg2816"
                                xml:space="preserve"
                                inkscape:version="0.48.4 r9939"
                                sodipodi:docname="AJAX.svg"><metadata
                                  id="metadata81"><rdf:RDF><cc:Work
                                      rdf:about=""><dc:format>image/svg+xml</dc:format><dc:type
                                        rdf:resource="http://purl.org/dc/dcmitype/StillImage" /><dc:title></dc:title></cc:Work></rdf:RDF></metadata><sodipodi:namedview
                                  pagecolor="#ffffff"
                                  bordercolor="#666666"
                                  borderopacity="1"
                                  objecttolerance="10"
                                  gridtolerance="10"
                                  guidetolerance="10"
                                  inkscape:pageopacity="0"
                                  inkscape:pageshadow="2"
                                  inkscape:window-width="1920"
                                  inkscape:window-height="1057"
                                  id="namedview79"
                                  showgrid="false"
                                  fit-margin-top="0.1"
                                  fit-margin-left="0.1"
                                  fit-margin-right="0.1"
                                  fit-margin-bottom="0.1"
                                  inkscape:zoom="1.4142137"
                                  inkscape:cx="81.708362"
                                  inkscape:cy="105.66257"
                                  inkscape:window-x="-8"
                                  inkscape:window-y="-8"
                                  inkscape:window-maximized="1"
                                  inkscape:current-layer="svg2816"
                                  showguides="true"
                                  inkscape:guide-bbox="true" /><defs
                                  id="defs2820"><clipPath
                                    id="clipPath2832"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path2834"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath2840"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path2842"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath2858"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path2860"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath2864"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path2866"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3306"><path
                                      d="m 199.238,472.977 0,-29.74 29.744,0 0,29.74"
                                      id="path3308"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3322"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path3324"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3330"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path3332"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3344"><path
                                      d="m 88.233,387.221 0,-28.335 32.08,0 0,28.335"
                                      id="path3346"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3462"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path3464"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3470"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path3472"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3490"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path3492"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3496"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path3498"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3520"><path
                                      d="m 254.788,326.215 0,-28.309 28.326,0 0,28.309"
                                      id="path3522"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3544"><path
                                      d="m 145.049,326.142 0,-28.333 28.342,0 0,28.333"
                                      id="path3546"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3682"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path3684"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3690"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path3692"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3980"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path3982"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3988"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path3990"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4010"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path4012"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4016"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path4018"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4062"><path
                                      d="m 200.163,177.773 0,-28.284 28.283,0 0,28.284"
                                      id="path4064"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4090"><path
                                      d="m 34.886,177.515 0,-51.632 30.367,0 0,51.632"
                                      id="path4092"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4106"><path
                                      d="m 34.886,177.515 0,-51.632 30.367,0 0,51.632"
                                      id="path4108"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4272"><path
                                      d="m 34.886,177.515 0,-51.632 30.367,0 0,51.632"
                                      id="path4274"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4304"><path
                                      d="m 254.653,176.442 0,-48.167 28.317,0 0,48.167"
                                      id="path4306"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4310"><path
                                      d="m 253.565,177.983 31.181,0 0,-49.183 -31.181,0 0,49.183 z"
                                      id="path4312"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4316"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path4318"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4322"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path4324"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4456"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path4458"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4464"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path4466"
                                      inkscape:connector-curvature="0" /></clipPath></defs><path
                                  inkscape:connector-curvature="0"
                                  sodipodi:nodetypes="scccscscccss" /><path
                                  inkscape:connector-curvature="0"
                                  style="fill:#ffffff;fill-opacity:1;fill-rule:evenodd;stroke:none"
                                  id="path3121"
                                  d="m 322.51281,286.28317 -284.757805,0 L 180.13391,39.806513 z"
                                  sodipodi:nodetypes="cccc" /></svg>
                            </li>
                            <li id="draggable4" class="itemlist_item item_list__mid draggable" style="left: 168px; top: 210px;">V4
                                <svg id="triangle_4" class="triangle hidden"
                                xmlns:dc="http://purl.org/dc/elements/1.1/"
                                xmlns:cc="http://creativecommons.org/ns#"
                                xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
                                xmlns:svg="http://www.w3.org/2000/svg"
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
                                xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
                                version="1.1"
                                width="360.26767"
                                height="318.20004"
                                id="svg2816"
                                xml:space="preserve"
                                inkscape:version="0.48.4 r9939"
                                sodipodi:docname="AJAX.svg"><metadata
                                  id="metadata81"><rdf:RDF><cc:Work
                                      rdf:about=""><dc:format>image/svg+xml</dc:format><dc:type
                                        rdf:resource="http://purl.org/dc/dcmitype/StillImage" /><dc:title></dc:title></cc:Work></rdf:RDF></metadata><sodipodi:namedview
                                  pagecolor="#ffffff"
                                  bordercolor="#666666"
                                  borderopacity="1"
                                  objecttolerance="10"
                                  gridtolerance="10"
                                  guidetolerance="10"
                                  inkscape:pageopacity="0"
                                  inkscape:pageshadow="2"
                                  inkscape:window-width="1920"
                                  inkscape:window-height="1057"
                                  id="namedview79"
                                  showgrid="false"
                                  fit-margin-top="0.1"
                                  fit-margin-left="0.1"
                                  fit-margin-right="0.1"
                                  fit-margin-bottom="0.1"
                                  inkscape:zoom="1.4142137"
                                  inkscape:cx="81.708362"
                                  inkscape:cy="105.66257"
                                  inkscape:window-x="-8"
                                  inkscape:window-y="-8"
                                  inkscape:window-maximized="1"
                                  inkscape:current-layer="svg2816"
                                  showguides="true"
                                  inkscape:guide-bbox="true" /><defs
                                  id="defs2820"><clipPath
                                    id="clipPath2832"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path2834"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath2840"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path2842"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath2858"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path2860"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath2864"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path2866"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3306"><path
                                      d="m 199.238,472.977 0,-29.74 29.744,0 0,29.74"
                                      id="path3308"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3322"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path3324"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3330"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path3332"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3344"><path
                                      d="m 88.233,387.221 0,-28.335 32.08,0 0,28.335"
                                      id="path3346"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3462"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path3464"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3470"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path3472"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3490"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path3492"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3496"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path3498"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3520"><path
                                      d="m 254.788,326.215 0,-28.309 28.326,0 0,28.309"
                                      id="path3522"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3544"><path
                                      d="m 145.049,326.142 0,-28.333 28.342,0 0,28.333"
                                      id="path3546"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3682"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path3684"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3690"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path3692"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3980"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path3982"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath3988"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path3990"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4010"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path4012"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4016"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path4018"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4062"><path
                                      d="m 200.163,177.773 0,-28.284 28.283,0 0,28.284"
                                      id="path4064"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4090"><path
                                      d="m 34.886,177.515 0,-51.632 30.367,0 0,51.632"
                                      id="path4092"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4106"><path
                                      d="m 34.886,177.515 0,-51.632 30.367,0 0,51.632"
                                      id="path4108"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4272"><path
                                      d="m 34.886,177.515 0,-51.632 30.367,0 0,51.632"
                                      id="path4274"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4304"><path
                                      d="m 254.653,176.442 0,-48.167 28.317,0 0,48.167"
                                      id="path4306"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4310"><path
                                      d="m 253.565,177.983 31.181,0 0,-49.183 -31.181,0 0,49.183 z"
                                      id="path4312"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4316"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path4318"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4322"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path4324"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4456"><path
                                      d="m -0.24,595.138 317.48,0 0,-595.276 -317.48,0 0,595.276 z"
                                      id="path4458"
                                      inkscape:connector-curvature="0" /></clipPath><clipPath
                                    id="clipPath4464"><path
                                      d="m 0.03,595.32 317.46,0 0,-595.32 -317.46,0 0,595.32 z"
                                      id="path4466"
                                      inkscape:connector-curvature="0" /></clipPath></defs><path
                                  inkscape:connector-curvature="0"
                                  sodipodi:nodetypes="scccscscccss" /><path
                                  inkscape:connector-curvature="0"
                                  style="fill:#ffffff;fill-opacity:1;fill-rule:evenodd;stroke:none"
                                  id="path3121"
                                  d="m 322.51281,286.28317 -284.757805,0 L 180.13391,39.806513 z"
                                  sodipodi:nodetypes="cccc" /></svg>
                            </li>
                            <li id="draggable5" class="itemlist_item item_list__mid draggable" style="left: 168px; top: 252px;">V5</li>
                            <li id="draggableD1" class="itemlist_item item_list__mid draggable" style="left: 121px; top: 64px;">D+</li>
                            <li id="draggableClean" class="itemlist_item item_list__mid itemlist_item__clear draggable" style="left: 20px; top: 262px;"></li>
                            <li id="draggableS2" class="itemlist_item item_list__mid draggable" style="left: 216px; top: 110px;">S2</li>
                            <li id="draggableS2_1" class="itemlist_item item_list__mid draggable" style="left: 212px; top: 163px;">S2</li>
                            <li id="draggableS3" class="itemlist_item item_list__mid draggable" style="left: 203px; top: 196px;">S3</li>
                            <li id="draggableS4" class="itemlist_item item_list__mid draggable" style="left: 201px; top: 244px;">S4</li>
                            <li id="draggableS5" class="itemlist_item item_list__mid draggable" style="left: 218px; top: 298px;">S5</li>
                            <li id="draggableS6" class="itemlist_item item_list__mid draggable" style="left: 239px; top: 429px;">S6</li>
                            <li id="draggableVD" class="itemlist_item item_list__mid draggable" style="left: 168px; top: 514px;">V-</li>
                            <li id="draggableS1" class="itemlist_item item_list__mid draggable" style="left: 215px; top: 64px;">S+</li>
                            <li id="draggableD2" class="itemlist_item item_list__mid draggable" style="left: 115px; top: 110px;">D2</li>
                            <li id="draggableD3" class="itemlist_item item_list__mid draggable" style="left: 131px; top: 197px;">D3</li>
                            <li id="draggableD5" class="itemlist_item item_list__mid draggable" style="left: 116px; top: 298px;">D5</li>
                            <li id="draggableD4" class="itemlist_item item_list__mid draggable" style="left: 134px; top: 244px;">D4</li>
                            <li id="draggableClean_2" class="itemlist_item item_list__mid itemlist_item__clear draggable inopaciti" style="left: 535px; top: 80px;"></li>
                            <li id="draggableClean_3" class="itemlist_item item_list__mid itemlist_item__clear draggable inopaciti" style="left: 535px; top: 80px;"></li>
                            <li id="draggableClean_4" class="itemlist_item item_list__mid itemlist_item__clear draggable inopaciti" style="left: 535px; top: 80px;"></li>
                            <li id="draggableClean_5" class="itemlist_item item_list__mid itemlist_item__clear draggable inopaciti" style="left: 535px; top: 80px;"></li>
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
