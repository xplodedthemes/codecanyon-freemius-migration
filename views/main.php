<script>
    (function( $ ) {
        $(function() {
            $('#xt-plugins').on('change', function() {

                $(this).closest('.control').find('.select').removeClass('is-info').addClass('is-loading');

                var url = '<?php echo home_url('/codecanyon-to-xt-migration'); ?>' + '?id='+$(this).val();

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
            })
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
?>

<h1 class="entry-title"><?php echo $title;?></h1>
<form method="post" id="ctf-form" class="ctf-form<?php echo !empty($messages) ? ' ctf-form-shake': ''; ?>">

    <?php do_action('ctf_success_message', $atts['form_success_message']); ?>

    <?php if ($messages) : ?>
        <div class="notification is-danger">
            <button class="delete"></button>
            <?php echo implode('<br>', $messages); ?>
        </div>
    <?php endif; ?>

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

    <?php if(!empty($atts['id'])): ?>

    <?php
        $email = !empty($_POST['email']) ? $_POST['email'] : '';
        $license_key = !empty($_POST['license_key']) ? $_POST['license_key'] : '';
    ?>
    <div class="field">
        <label class="label" for="np-ctf-panel-email"><?php echo $atts['form_email_label']; ?></label>
        <div class="control">
            <input class="input is-info" type="email" name="email" value="<?php echo $email; ?>" placeholder="youremail@company.com" />
        </div>
    </div>

    <div class="field">
        <label class="label" for="np-ctf-panel-license-key"><?php echo $atts['form_license_label']; ?></label>
        <div class="control">
            <input class="input is-info" type="text" name="license_key" value="<?php echo $license_key; ?>" placeholder="XXXXXXXX-XXXXXXXX-XXXXXXXX-XXXXXXXX" />
        </div>
    </div>

    <div class="field">
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