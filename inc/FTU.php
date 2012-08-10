<?php 
/*
Description: 42U Class.
Author: Rick Bush | 42U
Author URI: http://www.42umbrellas.com/rick/
Version: 0.1

Copyright (c) 2012 42Umbrellas (http://www.42umbrellas.com)

BY USING THIS SOFTWARE, YOU AGREE TO THE TERMS OF THE PLUGIN LICENSE AGREEMENT. 
IF YOU DO NOT AGREE TO THESE TERMS, DO NOT USE THE SOFTWARE.

*/
if (!class_exists("FTU")) {
    class FTU {

        public function __construct(){
            /* Custom Post Type Icon for Admin Menu & Post Screen */
            add_action( 'admin_head', array($this,'custom_post_type_icon'));
            add_action('admin_menu', array($this,'register_42U_mainpage'));
            
        }
        
        public function register_42U_mainpage() {
            $main_page = add_menu_page('42 Umbrellas', '42 Umbrellas', 'administrator', '42-Umbrellas', array($this,'fortytwou_init'),'div');//plugins_url('myplugin/images/icon.png')
            wp_register_style( 'ftUStylesheet', plugins_url('../css/style.css', __FILE__) );
            add_action( 'admin_print_styles-' . $main_page, array($this, 'add_42U_stylesheet') );
        }
          
        public function fortytwou_init() {
            include_once('splash.php');
        }
        
        public function add_42U_stylesheet() {
            wp_enqueue_style( 'ftUStylesheet' );
        }
        
        public function show_42_message($msg,$class) {
            $class = (empty($class)) ? 'updated' : $class;
            ?>
            <div class="<?php echo $class ?>">
                <p>
                <?php echo $msg ?>
                </p>
            </div>
            <?php 
        }
        
        public function smartCopy($source, $dest, $options=array('folderPermission'=>0755,'filePermission'=>0755)) {
            
            $result=false;
           
            if (is_file($source)) {
                if ($dest[strlen($dest)-1]=='/') {
                    if (!file_exists($dest)) {
                        cmfcDirectory::makeAll($dest,$options['folderPermission'],true);
                    }
                    $__dest=$dest."/".basename($source);
                } else {
                    $__dest=$dest;
                }
                $result=copy($source, $__dest);
                chmod($__dest,$options['filePermission']);
               
            } elseif(is_dir($source)) {
                if ($dest[strlen($dest)-1]=='/') {
                    if ($source[strlen($source)-1]=='/') {
                        //Copy only contents
                    } else {
                        //Change parent itself and its contents
                        $dest=$dest.basename($source);
                        @mkdir($dest);
                        chmod($dest,$options['filePermission']);
                    }
                } else {
                    if ($source[strlen($source)-1]=='/') {
                        //Copy parent directory with new name and all its content
                        @mkdir($dest,$options['folderPermission']);
                        chmod($dest,$options['filePermission']);
                    } else {
                        //Copy parent directory with new name and all its content
                        @mkdir($dest,$options['folderPermission']);
                        chmod($dest,$options['filePermission']);
                    }
                }
    
                $dirHandle=opendir($source);
                while($file=readdir($dirHandle)) {
                
                    if($file!="." && $file!="..") {
                         if(!is_dir($source."/".$file)) {
                            $__dest=$dest."/".$file;
                        } else {
                            $__dest=$dest."/".$file;
                        }
                        //echo "$source/$file ||| $__dest<br />";
                        $result=FTU::smartCopy($source."/".$file, $__dest, $options);
                    }
                }
                closedir($dirHandle);
               
            } else {
                $result=false;
            }
            return $result;
        } 
        
        public function custom_post_type_icon() {
    ?>
            <style>
                /* Admin Menu - 16px */
                #toplevel_page_42-Umbrellas .wp-menu-image {
                    background: url(<?php echo plugins_url('images/42U_adminmenu16-sprite.png', __FILE__) ?>) no-repeat 6px 6px !important;
                }
                #toplevel_page_42-Umbrellas:hover .wp-menu-image, #menu-posts-HMG.wp-has-current-submenu .wp-menu-image {
                    background-position: 6px -26px !important;
                }
                /* Post Screen - 32px */
                .icon32-posts-42-Umbrellas {
                    background: url(<?php echo plugins_url('images/42U_adminpage32.png', __FILE__) ?>) no-repeat left top !important;
                }
                @media
                only screen and (-webkit-min-device-pixel-ratio: 1.5),
                only screen and (   min--moz-device-pixel-ratio: 1.5),
                only screen and (     -o-min-device-pixel-ratio: 3/2),
                only screen and (        min-device-pixel-ratio: 1.5),
                only screen and (                min-resolution: 1.5dppx) {
                     
                    /* Admin Menu - 16px @2x */
                    #toplevel_page_42-Umbrellas .wp-menu-image {
                        background-image: url(<?php echo plugins_url('images/42U_adminmenu16-sprite_2x.png', __FILE__) ?>) !important;
                        -webkit-background-size: 16px 48px;
                        -moz-background-size: 16px 48px;
                        background-size: 16px 48px;
                    }
                    /* Post Screen - 32px @2x */
                    .icon32-posts-42-Umbrellas {
                        background-image: url(<?php echo plugins_url('images/42U_adminpage32_2x.png', __FILE__) ?>) !important;
                        -webkit-background-size: 32px 32px;
                        -moz-background-size: 32px 32px;
                        background-size: 32px 32px;
                    }        
                }
            </style>
    <?php 
    
        }
      
    }
    
    $fortytwou = new FTU();
    
}

?>