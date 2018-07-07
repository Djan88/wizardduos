<?php
/*
Author: Eddie Machado
URL: htp://themble.com/bones/

This is where you can drop your custom functions or
just edit things like thumbnail sizes, header images,
sidebars, comments, ect.
*/


// LOAD BONES CORE (if you remove this, the theme will break)
require_once( 'library/bones.php' );

// CUSTOMIZE THE WORDPRESS ADMIN (off by default)
// require_once( 'library/admin.php' );

/*********************
LAUNCH BONES
Let's get everything up and running.
*********************/

function bones_ahoy() {

  //Allow editor style.
  add_editor_style();

  // let's get language support going, if you need it
  load_theme_textdomain( 'bonestheme', get_template_directory() . '/library/translation' );

  // USE THIS TEMPLATE TO CREATE CUSTOM POST TYPES EASILY
  require_once( 'library/custom-post-type.php' );

  // launching operation cleanup
  add_action( 'init', 'bones_head_cleanup' );
  // A better title
  add_filter( 'wp_title', 'rw_title', 10, 3 );
  // remove WP version from RSS
  add_filter( 'the_generator', 'bones_rss_version' );
  // remove pesky injected css for recent comments widget
  add_filter( 'wp_head', 'bones_remove_wp_widget_recent_comments_style', 1 );
  // clean up comment styles in the head
  add_action( 'wp_head', 'bones_remove_recent_comments_style', 1 );
  // clean up gallery output in wp
  add_filter( 'gallery_style', 'bones_gallery_style' );

  // enqueue base scripts and styles
  add_action( 'wp_enqueue_scripts', 'bones_scripts_and_styles', 999 );
  // ie conditional wrapper

  // launching this stuff after theme setup
  bones_theme_support();

  // adding sidebars to Wordpress (these are created in functions.php)
  add_action( 'widgets_init', 'bones_register_sidebars' );

  // cleaning up random code around images
  add_filter( 'the_content', 'bones_filter_ptags_on_images' );
  // cleaning up excerpt
  add_filter( 'excerpt_more', 'bones_excerpt_more' );

} /* end bones ahoy */

// let's get this party started
add_action( 'after_setup_theme', 'bones_ahoy' );


/************* OEMBED SIZE OPTIONS *************/

if ( ! isset( $content_width ) ) {
	$content_width = 640;
}

/************* THUMBNAIL SIZE OPTIONS *************/

// Thumbnail sizes
add_image_size( 'bones-thumb-600', 600, 150, true );
add_image_size( 'bones-thumb-300', 300, 100, true );

/*
to add more sizes, simply copy a line from above
and change the dimensions & name. As long as you
upload a "featured image" as large as the biggest
set width or height, all the other sizes will be
auto-cropped.

To call a different size, simply change the text
inside the thumbnail function.

For example, to call the 300 x 100 sized image,
we would use the function:
<?php the_post_thumbnail( 'bones-thumb-300' ); ?>
for the 600 x 150 image:
<?php the_post_thumbnail( 'bones-thumb-600' ); ?>

You can change the names and dimensions to whatever
you like. Enjoy!
*/

add_filter( 'image_size_names_choose', 'bones_custom_image_sizes' );

function bones_custom_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'bones-thumb-600' => __('600px by 150px'),
        'bones-thumb-300' => __('300px by 100px'),
    ) );
}

//fix for cookie error while login.
setcookie(TEST_COOKIE, 'WP Cookie check', 0, COOKIEPATH, COOKIE_DOMAIN); 
if ( SITECOOKIEPATH != COOKIEPATH ) 
setcookie(TEST_COOKIE, 'WP Cookie check', 0, SITECOOKIEPATH, COOKIE_DOMAIN); Источник: http://jkeks.ru/jkeks.ru/archives/8175

/*
The function above adds the ability to use the dropdown menu to select
the new images sizes you have just created from within the media manager
when you add media to your content blocks. If you add more image sizes,
duplicate one of the lines in the array and name it according to your
new image size.
*/

/************* THEME CUSTOMIZE *********************/

/* 
  A good tutorial for creating your own Sections, Controls and Settings:
  http://code.tutsplus.com/series/a-guide-to-the-wordpress-theme-customizer--wp-33722
  
  Good articles on modifying the default options:
  http://natko.com/changing-default-wordpress-theme-customization-api-sections/
  http://code.tutsplus.com/tutorials/digging-into-the-theme-customizer-components--wp-27162
  
  To do:
  - Create a js for the postmessage transport method
  - Create some sanitize functions to sanitize inputs
  - Create some boilerplate Sections, Controls and Settings
*/

function bones_theme_customizer($wp_customize) {
  // $wp_customize calls go here.
  //
  // Uncomment the below lines to remove the default customize sections 

  // $wp_customize->remove_section('title_tagline');
  // $wp_customize->remove_section('colors');
  // $wp_customize->remove_section('background_image');
  // $wp_customize->remove_section('static_front_page');
  // $wp_customize->remove_section('nav');

  // Uncomment the below lines to remove the default controls
  // $wp_customize->remove_control('blogdescription');
  
  // Uncomment the following to change the default section titles
  // $wp_customize->get_section('colors')->title = __( 'Theme Colors' );
  // $wp_customize->get_section('background_image')->title = __( 'Images' );
}

add_action( 'customize_register', 'bones_theme_customizer' );

/************* ACTIVE SIDEBARS ********************/

// Sidebars & Widgetizes Areas
function bones_register_sidebars() {
	register_sidebar(array(
		'id' => 'sidebar1',
		'name' => __( 'Sidebar 1', 'bonestheme' ),
		'description' => __( 'The first (primary) sidebar.', 'bonestheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));

	/*
	to add more sidebars or widgetized areas, just copy
	and edit the above sidebar code. In order to call
	your new sidebar just use the following code:

	Just change the name to whatever your new
	sidebar's id is, for example:

	register_sidebar(array(
		'id' => 'sidebar2',
		'name' => __( 'Sidebar 2', 'bonestheme' ),
		'description' => __( 'The second (secondary) sidebar.', 'bonestheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));

	To call the sidebar in your template, you can just copy
	the sidebar.php file and rename it to your sidebar's name.
	So using the above example, it would be:
	sidebar-sidebar2.php

	*/
} // don't remove this bracket!


/************* COMMENT LAYOUT *********************/

// Comment Layout
function bones_comments( $comment, $args, $depth ) {
   $GLOBALS['comment'] = $comment; ?>
  <div id="comment-<?php comment_ID(); ?>" <?php comment_class('cf'); ?>>
    <article  class="cf">
      <header class="comment-author vcard">
        <?php
        /*
          this is the new responsive optimized comment image. It used the new HTML5 data-attribute to display comment gravatars on larger screens only. What this means is that on larger posts, mobile sites don't have a ton of requests for comment images. This makes load time incredibly fast! If you'd like to change it back, just replace it with the regular wordpress gravatar call:
          echo get_avatar($comment,$size='32',$default='<path_to_url>' );
        */
        ?>
        <?php // custom gravatar call ?>
        <?php
          // create variable
          $bgauthemail = get_comment_author_email();
        ?>
        <img data-gravatar="http://www.gravatar.com/avatar/<?php echo md5( $bgauthemail ); ?>?s=40" class="load-gravatar avatar avatar-48 photo" height="40" width="40" src="<?php echo get_template_directory_uri(); ?>/library/images/nothing.gif" />
        <?php // end custom gravatar call ?>
        <?php printf(__( '<cite class="fn">%1$s</cite> %2$s', 'bonestheme' ), get_comment_author_link(), edit_comment_link(__( '(Edit)', 'bonestheme' ),'  ','') ) ?>
        <time datetime="<?php echo comment_time('Y-m-j'); ?>"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time(__( 'F jS, Y', 'bonestheme' )); ?> </a></time>

      </header>
      <?php if ($comment->comment_approved == '0') : ?>
        <div class="alert alert-info">
          <p><?php _e( 'Your comment is awaiting moderation.', 'bonestheme' ) ?></p>
        </div>
      <?php endif; ?>
      <section class="comment_content cf">
        <?php comment_text() ?>
      </section>
      <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
    </article>
  <?php // </li> is added by WordPress automatically ?>
<?php
} // don't remove this bracket!


/*
This is a modification of a function found in the
twentythirteen theme where we can declare some
external fonts. If you're using Google Fonts, you
can replace these fonts, change it in your scss files
and be up and running in seconds.
*/
function bones_fonts() {
  wp_enqueue_style('googleFonts', 'http://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic');
}

add_action('wp_enqueue_scripts ', 'bones_fonts');

/* Отключаем админ панель для всех, кроме администраторов. */
if (!current_user_can('administrator')):
  show_admin_bar(false);
endif;


/* редирект с login на /wp-login.php  и с admin на /wp-admin */
add_action('template_redirect', 'kama_login_redirect');
function kama_login_redirect(){
    if(!is_user_logged_in()){
	if( strpos($_SERVER['REQUEST_URI'], 'login')!==false )
		$loc = '/wp-login.php';
	elseif( strpos($_SERVER['REQUEST_URI'], 'admin')!==false )
		$loc = '/wp-admin/';
        elseif( strpos($_SERVER['REQUEST_URI'], 'registration')!==false )
		$loc = 'wp-login.php?action=register';
	if( $loc ){
		header( 'Location: '.get_option('site_url').$loc, true, 303 );
		exit;
	}
    }
}

add_filter("login_redirect", "sp_login_redirect", 10, 3);

function sp_login_redirect($redirect_to, $request, $user){
    if(is_array($user->roles))
        if(in_array('administrator', $user->roles))
            return home_url('/wp-admin/');
    return home_url();
}

function my_custom_login_logo(){
	echo '<style type="text/css">
	h1 a { background-image:url('.get_bloginfo('template_directory').'/library/images/test-logo.png) !important; }
	</style>';
}
add_action('login_head', 'my_custom_login_logo');

function uploadImageFile() { // Note: GD library is required for this function

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //$iWidth = $iHeight = 200; // desired image result dimensions
        $iJpgQuality = 90;

        if ($_FILES) {

            // if no errors and size less than 250kb
            if (! $_FILES['mci_image_file']['error'] && $_FILES['mci_image_file']['size'] < 15 * 1024 * 1024) {
                if (is_uploaded_file($_FILES['mci_image_file']['tmp_name'])) {

                    // new unique filename
                    $sTempFileName = 'wp-content/uploads/cropped/' . md5(time().rand());

                    // move uploaded file into cache folder
                    move_uploaded_file($_FILES['mci_image_file']['tmp_name'], $sTempFileName);



                    // change file permission to 644
                    @chmod($sTempFileName, 0644);

                    if (file_exists($sTempFileName) && filesize($sTempFileName) > 0) {
                        $aSize = getimagesize($sTempFileName); // try to obtain image info
                        if (!$aSize) {
                            @unlink($sTempFileName);
                            return;
                        }

                        // check for image type
                        switch($aSize[2]) {
                            case IMAGETYPE_JPEG:
                                $sExt = '.jpg';

                                // create a new image from file 
                                $vImg = @imagecreatefromjpeg($sTempFileName);
                                break;
                            case IMAGETYPE_PNG:
                                $sExt = '.png';

                                // create a new image from file 
                                $vImg = @imagecreatefrompng($sTempFileName);
                                break;
                            default:
                                @unlink($sTempFileName);
                                return;
                        }

                        //exif only supports jpg in our supported file types
                        if ($sExt == ".jpg") {
                            $exif = exif_read_data($sTempFileName);

                            //get the orientation
                            if(isset($exif['Orientation'])) $orientation = $exif['Orientation'];
                            elseif(isset($exif['COMPUTED']) && isset($exif['COMPUTED']['Orientation'])) $orientation = $exif['COMPUTED']['Orientation'];
                            elseif(isset($exif['IFD0']) && isset($exif['IFD0']['Orientation'])) $orientation = $exif['IFD0']['Orientation'];

                            switch($orientation){
                                case 8:
                                    $vImg = imagerotate($vImg, 90, 0);
                                    $tmp = $aSize[0];
                                    $aSize[0] = $aSize[1];
                                    $aSize[1] = $tmp;
                                    break;
                                case 3:
                                    $vImg = imagerotate($vImg, 180, 0);
                                    break;
                                case 6:
                                    $vImg = imagerotate($vImg, -90, 0);
                                    $tmp = $aSize[0];
                                    $aSize[0] = $aSize[1];
                                    $aSize[1] = $tmp;
                                    break;
                            }
                        }

                        if($aSize[0] <= 800 && $_POST['mci_w']){
                            $k = 1;
                        }else{
                            $k = $aSize[0] / 800;
                        }

                        if(!$_POST['mci_x1']) $_POST['mci_x1'] = 0;
                        if(!$_POST['mci_y1']) $_POST['mci_y1'] = 0;
                        if(!$_POST['mci_w']) $_POST['mci_w'] = 800;
                        if(!$_POST['mci_h']) $_POST['mci_h'] = $aSize[1] / $k;

                        $iWidth = (int)$_POST['mci_w'];
                        $iHeight = (int)$_POST['mci_h'];

                        // create a new true color image
                        $vDstImg = @imagecreatetruecolor( $iWidth, $iHeight );

                        // copy and resize part of an image with resampling
                        imagecopyresampled($vDstImg, $vImg, 0, 0, (int)($_POST['mci_x1'] * $k), (int)($_POST['mci_y1'] * $k), $iWidth, $iHeight, (int)($_POST['mci_w'] * $k), (int)($_POST['mci_h'] * $k));

                        // define a result image filename
                        $sResultFileName = $sTempFileName . '.jpg';

                        // output image to file
                        imagejpeg($vDstImg, $sResultFileName, $iJpgQuality);
                        @unlink($sTempFileName);

                        return '/' . $sResultFileName;
                    }
                }
            }
        }
    }
}
//
//function image_flip(&$image, $x = 0, $y = 0, $width = null, $height = null) {
//    if ($width  < 1) $width  = imagesx($image);
//    if ($height < 1) $height = imagesy($image);
//
//    // Truecolor provides better results, if possible.
//    if (function_exists('imageistruecolor') && imageistruecolor($image)) {
//        $tmp = imagecreatetruecolor(1, $height);
//    } else {
//        $tmp = imagecreate(1, $height);
//    }
//
//    $x2 = $x + $width - 1;
//
//    for ($i = (int)floor(($width - 1) / 2); $i >= 0; $i--) {
//
//        // Backup right stripe.
//        imagecopy($tmp, $image, 0, 0, $x2 - $i, $y, 1, $height);
//
//        // Copy left stripe to the right.
//        imagecopy($image, $image, $x2 - $i, $y, $x + $i, $y, 1, $height);
//
//        // Copy backuped right stripe to the left.
//        imagecopy($image, $tmp, $x + $i,  $y, 0, 0, 1, $height);
//
//    }
//
//    imagedestroy($tmp);
//
//    return true;
//}

/* DON'T DELETE THIS CLOSING TAG */ ?>
