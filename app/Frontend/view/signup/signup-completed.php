<?php

defined( 'ABSPATH' ) or die();

use BookneticApp\Providers\Helpers\Helper;

/**
 * @var mixed $parameters
 */

?>

<div class="booknetic_signup" data-token="<?php echo htmlspecialchars($parameters['activation_token'])?>">
    <div class="booknetic_step_3">
        <div class="booknetic_signup_completed">
            <img src="<?php echo Helper::assets('images/signup-success2.svg', 'front-end')?>" alt="">
        </div>
        <div class="booknetic_signup_completed_title"><?php echo bkntc__('Congratulations!')?></div>
        <div class="booknetic_signup_completed_subtitle">
            <?php echo bkntc__('You have successfully signed up. Head over to your Dashboard!')?>
            <?php echo bkntc__( 'This window will be closed in ' ) . ' <span id="bkntc_timer">5</span>'?>

            <script>
                let timer = 5;
                setTimeout( () => { window.close() }, 5000 );
                setInterval( () => { timer -= 1; document.querySelector("#bkntc_timer").innerHTML = timer }, 1000 )
            </script>
        </div>
        <div class="booknetic_signup_completed_footer">
        </div>
    </div>
</div>
