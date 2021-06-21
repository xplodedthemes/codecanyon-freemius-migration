<script>
    (function( $ ) {
        $(function() {
            $('#xt-plugins').on('change', function() {

                $(this).closest('.control').find('.select').removeClass('is-info').addClass('is-loading');
                $(this).closest('form').find('.conditionnal-field').hide();

                var url = '<?php echo home_url('/codecanyon-license-migration'); ?>' + '?id='+$(this).val();

                if(location.href  !== url) {
                    location.href = url;
                }
            });

            $('#ctf-form').on('submit', function(evt){

                var button = $(this).find('.button');

                if(button.hasClass('is-loading')) {
                    evt.preventDefault();
                }

                button.addClass('is-loading');
            });

            <?php if(!empty($_POST)): ?>

            $("html, body").animate({ scrollTop: $('.ctf-right').offset().top }, 500);

            <?php endif; ?>
        });
    })( jQuery );
</script>

<?php

$plugins = array(
    '2907' => 'Woo Floating Cart',
    '2905' => 'Woo Quick View',
    '2908' => 'Woo Variation Swatches'
);

$current_id = !empty($atts['id']) ? $atts['id'] : null;
$current_plugin = !empty($current_id) ? $plugins[$current_id] : null;
$messages = $this->messages->get_error_messages();
$title = sprintf($atts['form_title'], !empty($current_plugin) ? '<span>'.$current_plugin.'</span>' : __('Your', 'np-ctf'), '<strong>CodeCanyon</strong>', '<strong>XplodedThemes</strong>');
$success = !empty($_GET['ctf_success']);
?>

<div class="ctf-wrapper">

    <div class="ctf-middle">
        <div class="ctf-left">

            <h1 class="entry-title">Our CodeCanyon Plugins <strong>Have Retired!</strong></h1>
            <p>
                If you purchased one of our plugins from CodeCanyon, please note that the CodeCanyon version has been retired, and will no longer be maintained or updated.
            </p>
            <p>
                To continue receiving new updates and security patches, please migrate your current CodeCanyon license to our new licensing system and get <strong>3 more months for FREE</strong>!
            </p>
            <p>
                As a thanks for your support, we are offering <strong>40% OFF</strong> any new license you purchase! Use the code <strong>BYECANYON</strong>
            </p>
            <p>
                If you have any issues while migrating, please <a href="<?php echo home_url('/support');?>">contact us</a>
            </p>

            <br>

            <h3 class="faq-title">It feels like I am being forced to migrate? Do I have to migrate?</h3>
            <p>Absolutely not! You can choose to stay on the old plugin version, however, please note that it will no longer be updated, supported or maintained.</p>

            <h3 class="faq-title">Why are we leaving CodeCanyon?</h3>
            <p>All our plugins use Freemius for billing and license management and we prefer to have consistency across all our products and not having to release 2 different versions for each product.</p>

            <h3 class="faq-title">It’s cool that we get 3 months free usage, but what happens after 3 months?</h3>

            <p>You can choose do nothing and keep using the plugin normally, however, you will no longer have access to new updates or support. Or, you can either purchase a annual or a lifetime license at a discounted price using the promo code above.</p>
        </div>

        <div class="ctf-right">

            <h2 class="entry-title"><?php echo $title;?></h2>

            <form method="post" id="ctf-form" class="ctf-form<?php echo !empty($messages) ? ' ctf-form-shake': ''; ?>">

                <?php do_action('ctf_success_message', $atts['form_success_message']); ?>

                <?php if ($messages) : ?>
                    <div class="notification is-danger">
                        <?php echo implode('<br>', $messages); ?>
                    </div>
                <?php endif; ?>

                <?php if(empty($success)): ?>
                <div class="field">
                    <label class="label" for="np-ctf-panel-email"><?php echo $atts['form_id_label']; ?></label>
                    <div class="control">
                        <div class="select is-info">
                            <select id="xt-plugins" name="freemius_id">
                                <option value="">-- Select Plugin --</option>
                                <?php foreach($plugins as $id => $plugin): ?>
                                    <option <?php selected($current_id, $id);?> value="<?php echo $id;?>"><?php echo $plugin;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if(!empty($atts['id'])): ?>

                    <?php
                    $email = !empty($_POST['email']) ? $_POST['email'] : '';
                    $license_key = !empty($_POST['license_key']) ? $_POST['license_key'] : '';
                    ?>
                    <div class="field conditionnal-field">
                        <label class="label" for="np-ctf-panel-email"><?php echo $atts['form_email_label']; ?></label>
                        <div class="control">
                            <input class="input is-info" type="email" name="email" value="<?php echo $email; ?>" placeholder="youremail@company.com" />
                        </div>
                    </div>

                    <div class="field conditionnal-field">
                        <label class="label" for="np-ctf-panel-license-key"><?php echo $atts['form_license_label']; ?></label>
                        <div class="control">
                            <input class="input is-info" type="text" name="license_key" value="<?php echo $license_key; ?>" placeholder="XXXXXXXX-XXXXXXXX-XXXXXXXX-XXXXXXXX" />
                        </div>
                    </div>

                    <div class="field conditionnal-field">
                        <div class="control">
                            <button class="button is-link" type="submit">
                                <?php echo $atts['form_button_label']; ?>
                            </button>
                        </div>
                    </div>

                    <input type="hidden" name="action" value="np-ctf" />

                    <?php wp_nonce_field('np-ctf-nonce'); ?>

                <?php endif; ?>

            </form>

        </div>
    </div>
</div>
