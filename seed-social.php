<?php
/*
Plugin Name: Seed Social Mod by Darkxee
*/

if (!class_exists('Seed_Social')) {
  class Seed_Social
  {
    /**
     * Construct the plugin object
     */
    public function __construct()
    {
    }

    /**
     * Activate the plugin
     */
    public static function activate()
    {
    }

    /**
     * Deactivate the plugin
     */

    public static function deactivate()
    {
    }
  } // END class Seed_Social
} // END if(!class_exists('Seed_Social'))

if (class_exists('Seed_Social')) {
  // Installation and uninstallation hooks
  register_activation_hook(__FILE__, ['Seed_Social', 'activate']);
  register_deactivation_hook(__FILE__, ['Seed_Social', 'deactivate']);

  // instantiate the plugin class
  $seed_social = new Seed_Social();
}

add_action('wp_head', 'seed_social_fb_og');

function seed_social_fb_og()
{
  $is_open_graph = get_option('seed_social_is_open_graph');

  if ($is_open_graph) {
    global $post;

    if (have_posts()):
      /* FB Open Graph */

      $fb_og = '';

      $large_image_url = wp_get_attachment_image_src(
        get_post_thumbnail_id($post->ID),
        'full'
      );

      $featured_image = '';

      if (!empty($large_image_url[0])) {
        $featured_image = esc_url($large_image_url[0]);
      }

      $fb_og .=
        '<meta property="og:url" content="' .
        home_url('/') .
        $post->post_name .
        '" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="' .
        htmlspecialchars($post->post_title) .
        '" />
		<meta property="og:description" content="' .
        htmlspecialchars($post->post_excerpt) .
        '" />
		<meta property="og:image" content="' .
        $featured_image .
        '" />';

      echo $fb_og;
    endif;
  }
}

add_action('wp_enqueue_scripts', 'seed_social_scripts');

function seed_social_scripts()
{
  if (!is_admin()) {
    wp_enqueue_script(
      'seed-social-mod',
      plugin_dir_url(__FILE__) . 'seed-social.js',
      ['jquery'],
      '2016-1',
      true
    );
    wp_enqueue_style(
      'seed-social-mod',
      plugin_dir_url(__FILE__) . 'seed-social.css',
      []
    );
  }
}

function seed_social($echo = true, $css_class = '')
{
  $is_facebook = get_option('seed_social_is_facebook', ['on']);
  $is_twitter = get_option('seed_social_is_twitter', ['on']);
  $is_line = get_option('seed_social_is_line', ['on']);
  $is_email = get_option('seed_social_is_email', ['on']);
  $is_fbmsg = get_option('seed_social_is_fbmsg', ['on']);
  $is_linkedin = get_option('seed_social_is_linkedin', ['on']);

  $facebook_text = get_option('seed_social_facebook_text', 'Facebook');
  $twitter_text = get_option('seed_social_twitter_text', 'Twitter');
  $line_text = get_option('seed_social_line_text', 'Line');
  $email_text = get_option('seed_social_email_text', 'Email');
  $fbmsg_text = get_option('seed_social_fbmsg_text', 'Email');
  $linkedin_text = get_option('seed_social_linkedin_text', 'Email');

  if ($facebook_text == '') {
    $facebook_text = 'Facebook';
  }
  if ($twitter_text == '') {
    $twitter_text = 'Twitter';
  }
  if ($line_text == '') {
    $line_text = 'Line';
  }
  if ($email_text == '') {
    $email_text = 'Email';
  }
  if ($fbmsg_text == '') {
    $fbmsg_text = 'Facebook Messager';
  }
  if ($linkedin_text == '') {
    $linkedin_text = 'LinkedIn';
  }

  global $post;

  $seed_social_echo = '';

  if (
    $is_facebook ||
    $is_twitter ||
    $is_line ||
    $is_email ||
    $fbmsg_text ||
    $linkedin_text
  ) {
    /* Facebook Button */
    if ($is_facebook) {
      $fbshare =
        '<a href="https://www.facebook.com/share.php?u=' .
        urlencode(get_the_permalink($post->ID)) .
        '" target="seed-social"><svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="facebook-f" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="svg-inline--fa fa-facebook-f fa-w-10 fa-2x"><path fill="currentColor" d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z" class=""></path></svg></a>';
    }

    /* Twitter Button */
    if ($is_twitter) {
      $tweet =
        '<a href="https://twitter.com/share?url=' .
        urlencode(get_the_permalink($post->ID)) .
        '&text=' .
        urlencode($post->post_title) .
        '" target="seed-social"><svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="twitter-square" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="svg-inline--fa fa-twitter-square fa-w-14 fa-2x"><path fill="currentColor" d="M400 32H48C21.5 32 0 53.5 0 80v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V80c0-26.5-21.5-48-48-48zm-48.9 158.8c.2 2.8.2 5.7.2 8.5 0 86.7-66 186.6-186.6 186.6-37.2 0-71.7-10.8-100.7-29.4 5.3.6 10.4.8 15.8.8 30.7 0 58.9-10.4 81.4-28-28.8-.6-53-19.5-61.3-45.5 10.1 1.5 19.2 1.5 29.6-1.2-30-6.1-52.5-32.5-52.5-64.4v-.8c8.7 4.9 18.9 7.9 29.6 8.3a65.447 65.447 0 0 1-29.2-54.6c0-12.2 3.2-23.4 8.9-33.1 32.3 39.8 80.8 65.8 135.2 68.6-9.3-44.5 24-80.6 64-80.6 18.9 0 35.9 7.9 47.9 20.7 14.8-2.8 29-8.3 41.6-15.8-4.9 15.2-15.2 28-28.8 36.1 13.2-1.4 26-5.1 37.8-10.2-8.9 13.1-20.1 24.7-32.9 34z" class=""></path></svg></a>';
    }

    /* Line */
    if ($is_line) {
      $line =
        '<a href="https://lineit.line.me/share/ui?url=' .
        urlencode(get_the_permalink($post->ID)) .
        '" target="seed-social"><svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="line" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="svg-inline--fa fa-line fa-w-14 fa-2x"><path fill="currentColor" d="M272.1 204.2v71.1c0 1.8-1.4 3.2-3.2 3.2h-11.4c-1.1 0-2.1-.6-2.6-1.3l-32.6-44v42.2c0 1.8-1.4 3.2-3.2 3.2h-11.4c-1.8 0-3.2-1.4-3.2-3.2v-71.1c0-1.8 1.4-3.2 3.2-3.2H219c1 0 2.1.5 2.6 1.4l32.6 44v-42.2c0-1.8 1.4-3.2 3.2-3.2h11.4c1.8-.1 3.3 1.4 3.3 3.1zm-82-3.2h-11.4c-1.8 0-3.2 1.4-3.2 3.2v71.1c0 1.8 1.4 3.2 3.2 3.2h11.4c1.8 0 3.2-1.4 3.2-3.2v-71.1c0-1.7-1.4-3.2-3.2-3.2zm-27.5 59.6h-31.1v-56.4c0-1.8-1.4-3.2-3.2-3.2h-11.4c-1.8 0-3.2 1.4-3.2 3.2v71.1c0 .9.3 1.6.9 2.2.6.5 1.3.9 2.2.9h45.7c1.8 0 3.2-1.4 3.2-3.2v-11.4c0-1.7-1.4-3.2-3.1-3.2zM332.1 201h-45.7c-1.7 0-3.2 1.4-3.2 3.2v71.1c0 1.7 1.4 3.2 3.2 3.2h45.7c1.8 0 3.2-1.4 3.2-3.2v-11.4c0-1.8-1.4-3.2-3.2-3.2H301v-12h31.1c1.8 0 3.2-1.4 3.2-3.2V234c0-1.8-1.4-3.2-3.2-3.2H301v-12h31.1c1.8 0 3.2-1.4 3.2-3.2v-11.4c-.1-1.7-1.5-3.2-3.2-3.2zM448 113.7V399c-.1 44.8-36.8 81.1-81.7 81H81c-44.8-.1-81.1-36.9-81-81.7V113c.1-44.8 36.9-81.1 81.7-81H367c44.8.1 81.1 36.8 81 81.7zm-61.6 122.6c0-73-73.2-132.4-163.1-132.4-89.9 0-163.1 59.4-163.1 132.4 0 65.4 58 120.2 136.4 130.6 19.1 4.1 16.9 11.1 12.6 36.8-.7 4.1-3.3 16.1 14.1 8.8 17.4-7.3 93.9-55.3 128.2-94.7 23.6-26 34.9-52.3 34.9-81.5z" class=""></path></svg></a>';
    }

    /* Email */
    if ($is_email) {
      $email =
        '
			<a href="mailto:?Subject=' .
        get_the_title() .
        '&amp;Body= ' .
        get_permalink() .
        '"><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="envelope" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-envelope fa-w-16 fa-2x"><path fill="currentColor" d="M502.3 190.8c3.9-3.1 9.7-.2 9.7 4.7V400c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V195.6c0-5 5.7-7.8 9.7-4.7 22.4 17.4 52.1 39.5 154.1 113.6 21.1 15.4 56.7 47.8 92.2 47.6 35.7.3 72-32.8 92.3-47.6 102-74.1 131.6-96.3 154-113.7zM256 320c23.2.4 56.6-29.2 73.4-41.4 132.7-96.3 142.8-104.7 173.4-128.7 5.8-4.5 9.2-11.5 9.2-18.9v-19c0-26.5-21.5-48-48-48H48C21.5 64 0 85.5 0 112v19c0 7.4 3.4 14.3 9.2 18.9 30.6 23.9 40.7 32.4 173.4 128.7 16.8 12.2 50.2 41.8 73.4 41.4z" class=""></path></svg></a>';
    }

    if ($is_linkedin) {
      $linkedin =
        '<a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=' .
        get_permalink() .
        '" target="_blank"><svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="linkedin" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="svg-inline--fa fa-linkedin fa-w-14 fa-2x"><path fill="currentColor" d="M416 32H31.9C14.3 32 0 46.5 0 64.3v383.4C0 465.5 14.3 480 31.9 480H416c17.6 0 32-14.5 32-32.3V64.3c0-17.8-14.4-32.3-32-32.3zM135.4 416H69V202.2h66.5V416zm-33.2-243c-21.3 0-38.5-17.3-38.5-38.5S80.9 96 102.2 96c21.2 0 38.5 17.3 38.5 38.5 0 21.3-17.2 38.5-38.5 38.5zm282.1 243h-66.4V312c0-24.8-.5-56.7-34.5-56.7-34.6 0-39.9 27-39.9 54.9V416h-66.4V202.2h63.7v29.2h.9c8.9-16.8 30.6-34.5 62.9-34.5 67.2 0 79.7 44.3 79.7 101.9V416z" class=""></path></svg></a>';
    }

    if ($is_fbmsg) {
      $fbmsg =
        '
			<a href="https://www.facebook.com/dialog/send?app_id=228295470977753&amp;link="' .
        get_permalink() .
        '
        "&amp;redirect_uri=https://facebook.com" title="" onclick="essb_window("https://www.facebook.com/dialog/send?app_id=228295470977753&amp;link=' .
        get_permalink() .
        '&amp;redirect_uri=https://facebook.com","messenger","542247251"); return false;" target="_blank" rel="nofollow"><svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="facebook-messenger" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-facebook-messenger fa-w-16 fa-2x"><path fill="currentColor" d="M256.55 8C116.52 8 8 110.34 8 248.57c0 72.3 29.71 134.78 78.07 177.94 8.35 7.51 6.63 11.86 8.05 58.23A19.92 19.92 0 0 0 122 502.31c52.91-23.3 53.59-25.14 62.56-22.7C337.85 521.8 504 423.7 504 248.57 504 110.34 396.59 8 256.55 8zm149.24 185.13l-73 115.57a37.37 37.37 0 0 1-53.91 9.93l-58.08-43.47a15 15 0 0 0-18 0l-78.37 59.44c-10.46 7.93-24.16-4.6-17.11-15.67l73-115.57a37.36 37.36 0 0 1 53.91-9.93l58.06 43.46a15 15 0 0 0 18 0l78.41-59.38c10.44-7.98 24.14 4.54 17.09 15.62z" class=""></path></svg></a>';
    }

    $seed_social_echo .=
      '<div id="seed-social" class="seed-social ' .
      $css_class .
      '"><div class="all-count"><div class="count" id="fb-count">0</div><div class="count-label">SHARE</div></div><div class="social-group">';

    if ($is_facebook) {
      $seed_social_echo .= '<div class="facebook">' . $fbshare . '</div>';
    }

    if ($is_twitter) {
      $seed_social_echo .= '<div class="twitter">' . $tweet . '</div>';
    }

    if ($is_line) {
      $seed_social_echo .= '<div class="line">' . $line . '</div>';
    }

    if ($is_email) {
      $seed_social_echo .= '<div class="email">' . $email . '</div>';
    }

    if ($is_fbmsg) {
      $seed_social_echo .= '<div class="messager">' . $fbmsg . '</div>';
    }

    if ($is_linkedin) {
      $seed_social_echo .= '<div class="linkedin">' . $linkedin . '</div>';
    }

    $seed_social_echo .= '</div></div>';
  }

  if ($echo) {
    echo $seed_social_echo;
  }

  return $seed_social_echo;
}

/**
 * Check if WooCommerce plugin is installed and activated.
 * @return bool
 */
if (!function_exists('is_woo_activated')) {
  function is_woo_activated()
  {
    if (class_exists('woocommerce')) {
      return true;
    } else {
      return false;
    }
  }
}

function seed_social_auto($content)
{
  $is_disable = get_post_meta(get_the_ID(), '_seed_social_disable', true);

  if ($is_disable != 'on') {
    $positions = get_option('seed_social_positions', ['bottom']);
    $post_types = get_option('seed_social_post_types', ['post', 'page']);

    if (
      !empty($positions) &&
      !empty($post_types) &&
      in_array(get_post_type(), $post_types) &&
      !is_front_page() &&
      is_singular()
    ) {
      if ($GLOBALS['post']->ID == get_the_ID()) {
        if (in_array('top', $positions)) {
          $content = seed_social(false, '-top') . $content;
        }

        if (in_array('bottom', $positions)) {
          $content .= seed_social(false, '-bottom');
        }
      }
    }
  }

  return $content;
}

add_filter('the_content', 'seed_social_auto', 15);

function seed_social_bbpress_auto_bottom()
{
  $is_disable = get_post_meta(get_the_ID(), '_seed_social_disable', true);

  if ($is_disable != 'on') {
    $positions = get_option('seed_social_positions', ['bottom']);
    $post_types = get_option('seed_social_post_types', ['post', 'page']);

    if (
      !empty($positions) &&
      in_array(get_post_type(), $post_types) &&
      !is_front_page() &&
      is_singular()
    ) {
      if ($GLOBALS['post']->ID == get_the_ID()) {
        if (in_array('bottom', $positions)) {
          seed_social(true, '-bbpress-bottom');
        }
      }
    }
  }
}

add_action(
  'bbp_template_after_single_topic',
  'seed_social_bbpress_auto_bottom',
  15,
  0
);

function seed_social_bbpress_auto_top()
{
  $is_disable = get_post_meta(get_the_ID(), '_seed_social_disable', true);

  if ($is_disable != 'on') {
    $positions = get_option('seed_social_positions', ['bottom']);
    $post_types = get_option('seed_social_post_types', ['post', 'page']);

    if (
      !empty($positions) &&
      in_array(get_post_type(), $post_types) &&
      !is_front_page() &&
      is_singular()
    ) {
      if ($GLOBALS['post']->ID == get_the_ID()) {
        if (in_array('top', $positions)) {
          seed_social(true, '-bbpress-top');
        }
      }
    }
  }
}

add_action(
  'bbp_template_before_single_topic',
  'seed_social_bbpress_auto_top',
  15,
  0
);

function seed_social_woocommerce_after_product_content()
{
  $is_disable = get_post_meta(get_the_ID(), '_seed_social_disable', true);

  if ($is_disable != 'on') {
    $woocommerce = get_option('seed_social_woocommerce', ['after-summary']);

    if (!empty($woocommerce)) {
      if (in_array('after-product-content', $woocommerce)) {
        seed_social(true, '-product-content');
      }
    }
  }
}

add_action(
  'woocommerce_after_single_product',
  'seed_social_woocommerce_after_product_content',
  10
);

function seed_social_woocommerce_after_summary()
{
  $is_disable = get_post_meta(get_the_ID(), '_seed_social_disable', true);

  if ($is_disable != 'on') {
    $woocommerce = get_option('seed_social_woocommerce', ['after-summary']);
    if (!empty($woocommerce)) {
      if (in_array('after-summary', $woocommerce)) {
        seed_social(true, '-product-summary');
      }
    }
  }
}

add_action('woocommerce_share', 'seed_social_woocommerce_after_summary', 10);

/* [seed_social] */
function seed_social_shortcode($atts)
{
  return seed_social(false, '-shortcode');
}

add_shortcode('seed_social', 'seed_social_shortcode');

function seed_social_setup_menu()
{
  $seed_social_page = add_submenu_page(
    'options-general.php',
    __('Seed Social', 'seed-social'),
    __('Seed Social', 'seed-social'),
    'manage_options',
    'seed-social',
    'seed_social_init'
  );
}

add_action('admin_menu', 'seed_social_setup_menu');

function seed_social_init()
{
  ?><style>
form label {
  display: inline-block;
  min-width: 60px;
  margin-right: 10px;
}

.form-table th,
.form-table td {
  padding: 0;
  line-height: 4em;
}

.form-table td p.description {
  margin-top: -10px;
}

input#seed-social-facebook-text,
input#seed-social-twitter-text,
input#seed-social-line-text,
input#seed-social-email-text,
input#seed-social-fbmsg-text,
input#seed-social-linkedin-text {
  position: absolute;
  margin: -3em 0 0 24px;
  width: 100px;
}
</style>
<div class="wrap">
  <div class="icon32" id="icon-options-general"></div>
  <h2><?php esc_html_e('Seed Social', 'seed-social'); ?></h2>
  <p>
    <?php printf(
      wp_kses(
        __(
          'For more information, please visit <a href="%1s" target="_blank">FAQ on WordPress.org</a>.',
          'seed-social'
        ),
        ['a' => ['href' => [], 'target' => []]]
      ),
      esc_url('https://wordpress.org/plugins/seed-social/#faq')
    ); ?>
  </p>
  <form action="<?php echo admin_url(
    'options.php'
  ); ?>" method="post" id="seed-social-form">
    <?php
    settings_fields('seed-social');
    do_settings_sections('seed-social');
    submit_button();?>
  </form>
</div>
<?php
}

/**
 * Quick helper function that prefixes an option ID
 *
 * This makes it easier to maintain and makes it super easy to change the options prefix without breaking the options
 * registered with the Settings API.
 *
 * @since 0.10.0
 *
 * @param string $name Unprefixed name of the option
 *
 * @return string
 */
function seed_social_get_option_id($name)
{
  return 'seed_social_' . $name;
}

function seed_social_get_settings()
{
  $settings = [
    [
      'id' => 'seed_social_settings',
      'title' => __('Social Sharing Buttons', 'seed-social'),
      'options' => [
        [
          'id' => seed_social_get_option_id('post_types'),
          'title' => esc_html__('Post Type to show:', 'seed-social'),
          'type' => 'checkbox',
          'options' => seed_social_get_post_types_option_list(),
          'default' => ['post', 'page'],
        ],
        [
          'id' => seed_social_get_option_id('positions'),
          'title' => esc_html__('Position to show:', 'seed-social'),
          'type' => 'checkbox',
          'options' => [
            'top' => esc_html__('Top', 'seed-social'),
            'bottom' => esc_html__('Bottom', 'seed-social'),
          ],
          'default' => ['bottom'],
        ],
        [
          'id' => seed_social_get_option_id('woocommerce'),
          'title' => esc_html__('WooCommerce', 'seed-social'),
          'type' => 'checkbox',
          'options' => [
            'after-summary' => esc_html__('Show after summary', 'seed-social'),
            'after-product-content' => esc_html__(
              'Show after product content',
              'seed-social'
            ),
          ],
          'default' => ['after-product-content'],
        ],
        [
          'id' => seed_social_get_option_id('is_facebook'),
          'title' => esc_html__('Facebook', 'seed-social'),
          'type' => 'checkbox',
          'options' => ['on' => esc_html__('', 'seed-social')],
          'default' => ['on'],
        ],
        [
          'id' => seed_social_get_option_id('facebook_text'),
          'title' => esc_html__('', 'seed-social'),
          'type' => 'text',
          'default' => 'Facebook',
        ],
        [
          'id' => seed_social_get_option_id('is_twitter'),
          'title' => esc_html__('Twitter', 'seed-social'),
          'type' => 'checkbox',
          'options' => ['on' => esc_html__('', 'seed-social')],
          'default' => ['on'],
        ],
        [
          'id' => seed_social_get_option_id('twitter_text'),
          'title' => esc_html__('', 'seed-social'),
          'type' => 'text',
          'default' => 'Twitter',
        ],

        [
          'id' => seed_social_get_option_id('is_line'),
          'title' => esc_html__('Line', 'seed-social'),
          'type' => 'checkbox',
          'options' => ['on' => esc_html__('', 'seed-social')],
          'default' => ['on'],
        ],
        [
          'id' => seed_social_get_option_id('line_text'),
          'title' => esc_html__('', 'seed-social'),
          'type' => 'text',
          'default' => 'Line',
        ],

        [
          'id' => seed_social_get_option_id('is_email'),
          'title' => esc_html__('Email', 'seed-social'),
          'type' => 'checkbox',
          'options' => ['on' => esc_html__('', 'seed-social')],
          'default' => ['on'],
        ],

        [
          'id' => seed_social_get_option_id('email_text'),
          'title' => esc_html__('', 'seed-social'),
          'type' => 'text',
          'default' => 'Email',
        ],

        [
          'id' => seed_social_get_option_id('is_fbmsg'),
          'title' => esc_html__('Facebook Messager', 'seed-social'),
          'type' => 'checkbox',
          'options' => ['on' => esc_html__('', 'seed-social')],
          'default' => ['on'],
        ],

        [
          'id' => seed_social_get_option_id('fbmsg_text'),
          'title' => esc_html__('', 'seed-social'),
          'type' => 'text',
          'default' => 'Facebook Messager',
        ],

        [
          'id' => seed_social_get_option_id('is_linkedin'),
          'title' => esc_html__('LinkedIn', 'seed-social'),
          'type' => 'checkbox',
          'options' => ['on' => esc_html__('', 'seed-social')],
          'default' => ['on'],
        ],

        [
          'id' => seed_social_get_option_id('linkedin_text'),
          'title' => esc_html__('', 'seed-social'),
          'type' => 'text',
          'default' => 'LinkedIn',
        ],

        [
          'id' => seed_social_get_option_id('is_open_graph'),
          'title' => esc_html__('Share featured image?', 'seed-social'),
          'desc' => esc_html__(
            'This will add Open Graph meta tags. Do not check this if SEO plugin is installed.',
            'seed-social'
          ),
          'type' => 'checkbox',
          'options' => ['on' => esc_html__('Yes', 'seed-social')],
        ],
      ],
    ],
  ];

  if (!is_woo_activated()) {
    unset($settings[0]['options'][2]);
  }
  return $settings;
}

add_action('admin_init', 'seed_social_register_plugin_settings');

/**
 * Register plugin settings
 *
 * This function dynamically registers plugin settings.
 *
 * @since 0.10.0
 * @see   seed_social_get_settings
 * @return void
 */
function seed_social_register_plugin_settings()
{
  $settings = seed_social_get_settings();

  foreach ($settings as $key => $section) {
    /* We add the sections and then loop through the corresponding options */
    add_settings_section(
      $section['id'],
      $section['title'],
      false,
      'seed-social'
    );

    /* Get the options now */
    foreach ($section['options'] as $k => $option) {
      $field_args = [
        'name' => $option['id'],
        'title' => $option['title'],
        'type' => $option['type'],
        'desc' => isset($option['desc']) ? $option['desc'] : '',
        'default' => isset($option['default']) ? $option['default'] : '',
        'options' => isset($option['options']) ? $option['options'] : [],
        'group' => 'seed-social',
      ];

      register_setting('seed-social', $option['id']);
      add_settings_field(
        $option['id'],
        $option['title'],
        'seed_social_output_settings_field',
        'seed-social',
        $section['id'],
        $field_args
      );
    }
  }
}

/**
 * Generate the option field output
 *
 * @since 0.10.0
 *
 * @param array $option The current option array
 *
 * @return void
 */
function seed_social_get_post_types_option_list()
{
  $list = [];

  $list['post'] = 'Posts';
  $list['page'] = 'Pages';

  foreach (
    get_post_types(['_builtin' => false, 'public' => true], 'objects')
    as $_slug => $_post_type
  ) {
    if (
      (!is_woo_activated() || $_post_type->name != 'product') &&
      $_post_type->name != 'seed_confirm_log'
    ) {
      $list[$_slug] = $_post_type->labels->name;
    }
  }

  return $list;
}

/**
 * Generate the option field output
 *
 * @since 0.10.0
 *
 * @param array $option The current option array
 *
 * @return void
 */
function seed_social_output_settings_field($option)
{
  $current = get_option($option['name'], $option['default']);
  $field_type = $option['type'];
  $id = str_replace('_', '-', $option['name']);

  switch ($field_type): case 'text': ?>
<input type="text" name="<?php echo $option[
  'name'
]; ?>" id="<?php echo $id; ?>" value="<?php echo $current; ?>" class="regular-text" />
<?php break;case 'checkbox':
      foreach ($option['options'] as $val => $choice):

        if (count($option['options']) > 1) {
          $id = "{$id}_{$val}";
        }

        $selected =
          is_array($current) && in_array($val, $current)
            ? 'checked="checked"'
            : '';
        ?>
<label for="<?php echo $id; ?>">
  <input type="checkbox" name="<?php echo $option[
    'name'
  ]; ?>[]" value="<?php echo $val; ?>" id="<?php echo $id; ?>" <?php echo $selected; ?> />
  <?php echo $choice; ?>
</label>
<?php
      endforeach;
      break;

    case 'dropdown': ?>
<label for="<?php echo $option['name']; ?>">
  <select name="<?php echo $option['name']; ?>" id="<?php echo $id; ?>">
    <?php foreach ($option['options'] as $val => $choice):
      if ($val == $current) {
        $selected = 'selected="selected"';
      } else {
        $selected =
          ''; ?><option value="<?php echo $val; ?>" <?php echo $selected; ?>><?php echo $choice; ?></option><?php
      }
    endforeach; ?>
  </select>
</label>
<?php break;case 'textarea':
      if (!$current && isset($option['std'])) {
        $current = $option['std'];
      } ?>
<textarea name="<?php echo $option['name']; ?>" id="<?php echo $id; ?>" rows="8"
  cols="70"><?php echo $current; ?></textarea>
<?php break;

    case 'textarea_code':
      if (!$current && isset($option['std'])) {
        $current = $option['std'];
      } ?>
<textarea name="<?php echo $option[
  'name'
]; ?>" id="<?php echo $id; ?>" rows="4" cols="60" class="code" readonly><?php echo $current; ?></textarea>
<?php break;
  endswitch;

  if (isset($option['desc']) && $option['desc'] != '') {
    echo wp_kses_post(
      sprintf('<p class="description">%1$s</p>', $option['desc'])
    );
  }
}

function seed_social_box()
{
  if (is_woo_activated()) {
    $screens = ['post', 'page', 'product'];
  } else {
    $screens = ['post', 'page'];
  }
  foreach ($screens as $screen) {
    add_meta_box(
      'seed_social_box' /* Unique ID */,
      'Seed Social' /* Box title */,
      'seed_social_custom_box_html' /* Content callback, must be of type callable */,
      $screen /* Post type */,
      'advanced',
      'low'
    );
  }
}
add_action('add_meta_boxes', 'seed_social_box');

function seed_social_custom_box_html($post)
{
  $value = get_post_meta(get_the_ID(), '_seed_social_disable', true); ?>
<input type="checkbox" name="seed_social_disable" id="seed_social_disable" class="postbox"
  <?php checked($value, 'on'); ?> />
<label for="seed_social_disable">Disable social sharing button</label>
<?php
}

function seed_social_save_postdata($post_id)
{
  if (array_key_exists('seed_social_disable', $_POST)) {
    update_post_meta(
      $post_id,
      '_seed_social_disable',
      $_POST['seed_social_disable']
    );
  } else {
    delete_post_meta($post_id, '_seed_social_disable');
  }
}
add_action('save_post', 'seed_social_save_postdata');

load_plugin_textdomain(
  'seed-social',
  false,
  basename(dirname(__FILE__)) . '/languages'
);