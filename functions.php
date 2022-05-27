<?php

/* Enqueue Child Theme Styles */
function baxel_child_embed_resources()
{

   wp_enqueue_style('baxel-parent', get_template_directory_uri() . '/style.css', array('normalize'));
}
add_action('wp_enqueue_scripts', 'baxel_child_embed_resources');
/* */

/* ***************** */
/* DO NOT EDIT ABOVE */
/* ***************** */
add_shortcode('woocommerce_notices', function ($attrs) {
   if (wc_notice_count() > 0) {
      //wc_add_notice( 'This is a Error notice', 'error' );
?>
      <div class="woocommerce-notices-shortcode woocommerce">
         <?php return wc_print_notices(); ?>
      </div>
   <?php

   }
});

// WooCommerce User Login Shortcode
add_shortcode('woo_login_form', 'nm_woo_login_form');
function nm_woo_login_form()
{
   ob_start(); ?>
   <div class="nm-custom-login">
      <?php
      woocommerce_login_form();
      ?>
   </div>
<?php
   return ob_get_clean();
}

// WooCommerce User Registration Shortcode
add_shortcode('woo_registration_form', 'nm_woo_sep_reg_form');

function nm_woo_sep_reg_form()
{
   if (is_admin()) return;
   if (is_user_logged_in()) return;
   ob_start();

   do_action('woocommerce_before_customer_login_form');

?>
   <div class="nm-custom-login">
      <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action('woocommerce_register_form_tag'); ?>>

         <?php do_action('woocommerce_register_form_start'); ?>

         <?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
               <label for="reg_username"><?php esc_html_e('Username', 'woocommerce'); ?> <span class="required">*</span></label>
               <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" />
            </p>

         <?php endif; ?>

         <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="reg_email"><?php esc_html_e('Email address', 'woocommerce'); ?> <span class="required">*</span></label>
            <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo (!empty($_POST['email'])) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>" />
         </p>

         <?php if ('no' === get_option('woocommerce_registration_generate_password')) : ?>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
               <label for="reg_password"><?php esc_html_e('Password', 'woocommerce'); ?> <span class="required">*</span></label>
               <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" />
            </p>

         <?php else : ?>

            <p><?php esc_html_e('A password will be sent to your email address.', 'woocommerce'); ?></p>

         <?php endif; ?>

         <?php do_action('woocommerce_register_form'); ?>

         <p class="woocommerce-FormRow form-row">
            <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
            <button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit" name="register" value="<?php esc_attr_e('Register', 'woocommerce'); ?>"><?php esc_html_e('Register', 'woocommerce'); ?></button>
         </p>

         <?php do_action('woocommerce_register_form_end'); ?>

      </form>
   </div>
<?php

   return ob_get_clean();
}

// Login redirection
add_filter('woocommerce_login_redirect', 'nm_login_redirect');
function nm_login_redirect($redirect_to)
{
   return get_permalink(get_option('woocommerce_myaccount_page_id'));
}

// Registration redirection
add_action('woocommerce_registration_redirect', 'nm_registration_redirect', 2);
function nm_registration_redirect()
{
   // wp_redirect(get_permalink(get_option('woocommerce_myaccount_page_id')));
   wp_logout();
   wp_redirect(get_theme_mod('nm_login_url'));
   exit();
}

// Logout redirection
add_action('wc_logout', 'nm_logout_redirect');
function nm_logout_redirect()
{
   wp_redirect(home_url());
   exit;
}

// Resrt password redirection
add_action('woocommerce_customer_reset_password', 'nm_woo_pass_redirect');
function nm_woo_pass_redirect($user)
{
   wp_redirect(get_theme_mod('nm_login_url'));
   exit;
}

// Customizer

function nm_theme_custom_panel($wp_customize)
{
   // Create custom panel.
   $wp_customize->add_panel('nm_text_blocks', array(
      'priority'       => 20,
      'theme_supports' => '',
      'title'          => __('Modification', 'theme_name'),
      //  'description'    => __( 'Set editable text for certain content.', 'theme_name' ),
   ));

   // Add section - HERO.
   $wp_customize->add_section('nm_hero_section', array(
      'title'    => __('Hero Section', 'baxel-child'),
      'panel'    => 'nm_text_blocks',
      'priority' => 10
   ));

   // Add setting - Header Text
   $wp_customize->add_setting('nm_hero_main_txt', array(
      'default'           => __('Is The 7 Figure Break through System a Scam! Know more!', 'baxel-child'),
      //   'sanitize_callback' => 'sanitize_text'
   ));

   // Add control
   $wp_customize->add_control(
      new WP_Customize_Control(
         $wp_customize,
         'nm_hero_main_txt',
         array(
            'label'    => __('Header Text', 'baxel-child'),
            'section'  => 'nm_hero_section',
            'settings' => 'nm_hero_main_txt',
            'type'     => 'text'
         )
      )
   );

   // Add setting - List
   $wp_customize->add_setting('nm_hero_list', array(
      'default'           => __('Is The 7 Figure Break through System.', 'baxel-child'),
      //   'sanitize_callback' => 'sanitize_text'
   ));

   // Add control
   $wp_customize->add_control(
      new WP_Customize_Control(
         $wp_customize,
         'nm_hero_list',
         array(
            'label'    => __('Header List', 'baxel-child'),
            'section'  => 'nm_hero_section',
            'settings' => 'nm_hero_list',
            'type'     => 'textarea'
         )
      )
   );

   // Add setting - Button
   $wp_customize->add_setting('nm_hero_btn_txt', array(
      'default'           => __('Free Sing Up', 'baxel-child'),
      //   'sanitize_callback' => 'sanitize_text'
   ));

   // Add control
   $wp_customize->add_control(
      new WP_Customize_Control(
         $wp_customize,
         'nm_hero_btn_txt',
         array(
            'label'    => __('Button Text', 'baxel-child'),
            'section'  => 'nm_hero_section',
            'settings' => 'nm_hero_btn_txt',
            'type'     => 'text'
         )
      )
   );

   // Add setting - Final Text
   $wp_customize->add_setting('nm_hero_final_txt', array(
      'default'           => __('Free Sing Up', 'baxel-child'),
      //   'sanitize_callback' => 'sanitize_text'
   ));

   // Add control
   $wp_customize->add_control(
      new WP_Customize_Control(
         $wp_customize,
         'nm_hero_final_txt',
         array(
            'label'    => __('Final Text', 'baxel-child'),
            'section'  => 'nm_hero_section',
            'settings' => 'nm_hero_final_txt',
            'type'     => 'textarea'
         )
      )
   );

   // Add setting - Video
   $wp_customize->add_setting('nm_hero_video', array(
      'default'           => __('', 'baxel-child'),
      //   'sanitize_callback' => 'sanitize_text'
   ));

   // Add control
   $wp_customize->add_control(
      new WP_Customize_Control(
         $wp_customize,
         'nm_hero_video',
         array(
            'label'    => __('Video', 'baxel-child'),
            'section'  => 'nm_hero_section',
            'settings' => 'nm_hero_video',
            'type'     => 'text'
         )
      )
   );

   // Add setting - Image
   $wp_customize->add_setting('nm_hero_img', array(
      'default'           => get_stylesheet_directory_uri() . '/hero_image.png',
      //   'sanitize_callback' => 'sanitize_text'
   ));

   $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'nm_hero_img', array(
      'label' => __('Image', 'baxel-child'),
      'section' => 'nm_hero_section',
      'mime_type' => 'image',
   )));

   // Add control
   // $wp_customize->add_control(
   //    new WP_Customize_Control(
   //       $wp_customize,
   //       'nm_hero_img',
   //       array(
   //          'label'    => __('Image', 'baxel-child'),
   //          'section'  => 'nm_hero_section',
   //          'settings' => 'nm_hero_img',
   //          'type'     => 'media'
   //       )
   //    )
   // );

   // Add section - WOO Auth.
   $wp_customize->add_section('nm_woo_section', array(
      'title'    => __('Login & Registration', 'baxel-child'),
      'panel'    => 'nm_text_blocks',
      'priority' => 11
   ));

   // Add setting
   $wp_customize->add_setting('nm_login_url', array(
      'default'           => __('#', 'baxel-child'),
      //   'sanitize_callback' => 'sanitize_text'
   ));

   // Add control
   $wp_customize->add_control(
      new WP_Customize_Control(
         $wp_customize,
         'nm_login_url',
         array(
            'label'    => __('Login Url', 'baxel-child'),
            'section'  => 'nm_woo_section',
            'settings' => 'nm_login_url',
            'type'     => 'text'
         )
      )
   );

   // Add setting
   $wp_customize->add_setting('nm_register_url', array(
      'default'           => __('#', 'baxel-child'),
      //   'sanitize_callback' => 'sanitize_text'
   ));

   // Add control
   $wp_customize->add_control(
      new WP_Customize_Control(
         $wp_customize,
         'nm_register_url',
         array(
            'label'    => __('Register Url', 'baxel-child'),
            'section'  => 'nm_woo_section',
            'settings' => 'nm_register_url',
            'type'     => 'url'
         )
      )
   );

   // Sanitize text
   // function sanitize_text( $text ) {
   //     return sanitize_text_field( $text );
   // }
}
add_action('customize_register', 'nm_theme_custom_panel');

// Header Hero Image

add_shortcode('header_hero_section', 'nm_header_hero_section');

function nm_header_hero_section()
{ ?>
   <div class="baxel-slider-container" style="box-shadow: none;">
      <div class="nm-header">
         <div class="nm-header-left">
            <h1><?php echo get_theme_mod('nm_hero_main_txt');  ?></h1>
            <ul>
               <?php echo get_theme_mod('nm_hero_list');  ?>
            </ul>
            <a href="<?php echo get_theme_mod('nm_register_url'); ?>"><?php echo get_theme_mod('nm_hero_btn_txt'); ?></a>
            <p><?php echo get_theme_mod('nm_hero_final_txt'); ?></p>
         </div>
         <div class="nm-header-right">
            <?php
            if (!empty(get_theme_mod('nm_hero_video'))) { ?>
               <iframe src="https://www.youtube.com/embed/<?php echo get_theme_mod('nm_hero_video'); ?>" title="YouTube video player" frameborder="0" allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <?php  } else { ?>
               <img src="<?php echo get_theme_mod('nm_hero_img'); ?>" alt="Hero Image">
            <?php } ?>
         </div>
      </div>
   </div>
<?php
}

// {4~X9$?UYWk^ bdsoftcr_nm_baxel