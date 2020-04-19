<?php
function users_redirect(){
wp_redirect(site_url('/'));
die();
}

// if(!current_user_can('manage_options')){
// add_action('admin_init','users_redirect');
  add_filter('login_redirect', 'users_redirect');
// }

//fix for cookie error while login.
setcookie(TEST_COOKIE, 'WP Cookie check', 0, COOKIEPATH, COOKIE_DOMAIN); 
if ( SITECOOKIEPATH != COOKIEPATH ) 
setcookie(TEST_COOKIE, 'WP Cookie check', 0, SITECOOKIEPATH, COOKIE_DOMAIN);

function is_user_role( $role, $user_id = null ) {
  $user = is_numeric( $user_id ) ? get_userdata( $user_id ) : wp_get_current_user();

  if( ! $user )
    return false;

  return in_array( $role, (array) $user->roles );
}

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
                            $exif = @exif_read_data($sTempFileName);
                            // print_r ($exif);

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

?>
