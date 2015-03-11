<?php
/*
 Template Name: no sidebar
*/
?>

<?php get_header(); ?>

<div id="content">

    <div id="inner-content" class="wrap cf">

        <div id="main" class="m-all t-2of3 cf home-page-content" role="main">
        <?php $curr_user = wp_get_current_user(); ?>
            <h1 class="page-title" itemprop="headline" style="text-align: center"><?php the_title(); ?></h1>

            <div style="text-align: center;">
                <?php the_content(); ?>
            </div>

        </div>

    </div>

</div>


<?php get_footer(); ?>
