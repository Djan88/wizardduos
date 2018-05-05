<?php global $WAU_User; ?>
<li class="plan">
    <ul class="planContainer">
        <li class="title">
            <span <?php echo $tariff->is_best? 'class="bestPlanTitle"': ''; ?>><?php echo $tariff->tariff_name; ?></span>
        </li>
        <li class="price">
            <p <?php echo $tariff->is_best? 'class="bestPlanPrice"': ''; ?>>
                <?php if($tariff->tariff_price != $discount = wau_get_tariff_price($tariff->tariff_id)): ?>
                    <s><?php echo $tariff->tariff_price; ?></s> <?php echo $discount; ?>
                <?php else: ?>
                    <?php echo $tariff->tariff_price; ?>
                <?php endif; ?>
                <span><?php echo rcl_get_primary_currency(1); ?></span>
            </p>
        </li>
        <li>
            <ul class="options">
                <?php if($tariff->tariff_desc): ?>
                    <li><?php echo $tariff->tariff_desc; ?></li>
                <?php endif; ?>
                
            </ul>
        </li>
        <li class="button preloader-parent">
            <?php if($WAU_User->user_id): ?>   

                <a href="#" onclick="wau_get_payment_form(<?php echo $tariff->tariff_id; ?>,this);return false;" <?php echo $tariff->is_best? 'class="bestPlanButton"': ''; ?>>
                    <?php if($WAU_User->is_access($tariff->account_id)): ?>
                        <?php _e('Продлить'); ?>
                    <?php else: ?>
                        <?php _e('Приобрести'); ?>
                    <?php endif; ?>
                </a>

            <?php else: ?>
                <a href="<?php echo rcl_get_loginform_url('login'); ?>" class="rcl-login <?php echo $tariff->is_best? 'bestPlanButton': ''; ?>">
                    <?php _e('Приобрести'); ?>
                </a>
            <?php endif; ?>
        </li>
    </ul>
</li>
