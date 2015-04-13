<?php
/*
 Template Name: payments form robokassa
*/
?>

<?php get_header(); ?>

<div id="content">

    <div id="inner-content" class="wrap cf">

        <div id="main" class="m-all t-2of3 cf home-page-content" role="main">
        <?php $curr_user = wp_get_current_user(); ?>
            <h1 class="page-title" itemprop="headline" style="text-align: center"><?php the_title(); ?></h1>

            <div style="margin-bottom: 30px">
                <?=$post->post_content?>
            </div>

            <?php
                $sMerchantLogin = 'wizardduos';
                $nOutSum = '55000.00';
                $nInvId = '0';
                $sMerchantPass1 = 'romashka1';
                $sInvDesc = 'Доступ к WizardDuos';
            ?>

            <form action="https://merchant.roboxchange.com/Handler/MrchSumPreview.ashx" method="POST" class="pay-form">
                <input type="hidden" name="MrchLogin" value="<?=$sMerchantLogin?>">
                <input type="hidden" name="OutSum" value="<?=$nOutSum?>">
                <input type="hidden" name="InvId" value="<?=$nInvId?>">
                <input type="hidden" name="Desc" value="<?=$sInvDesc?>">
                <input type="hidden" name="SignatureValue" value="<?=md5($sMerchantLogin.':'.$nOutSum.':'.$nInvId.':'.$sMerchantPass1)?>">
<!--                <input type="email" required="required" name="spUserEmail" class="pay-form__email" placeholder="Введите свой E-mail">-->
                <input type="submit" value="оплатить" class="pay-form__submit">
              </form>

        </div>

    </div>

</div>


<?php get_footer(); ?>
