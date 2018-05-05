<section id="pricePlans">
    <ul id="plans">

        <?php foreach($tariffs as $tariff){
            echo rcl_get_include_template('wau-tariff-single.php',__FILE__, array(
                'tariff' => $tariff
            ));
        } ?>

    </ul>
</section>
