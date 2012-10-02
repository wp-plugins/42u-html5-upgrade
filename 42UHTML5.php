<?php
/*
Plugin Name: 42U HTML5
Plugin URI: http://www.42umbrellas.com/42u-html5-upgrade/
Description: Transparently upgrade your site to be HTML5 compatible. This plugin loads modernizr [www.modernizr.com] and sets the TinyMCE Editor Schema to HTML5
Author: Rick Bush | 42U
Author URI: http://www.42umbrellas.com/author/rick/
Version: 1.2
License: GPLv2 or later

Copyright (c) 2012 42Umbrellas (http://www.42umbrellas.com)

BY USING THIS SOFTWARE, YOU AGREE TO THE TERMS OF THE PLUGIN LICENSE AGREEMENT. 
IF YOU DO NOT AGREE TO THESE TERMS, DO NOT USE THE SOFTWARE.

*/

include_once('inc/FTU.php');

class _42UHTML5 {

    public $options;

    public function __construct(){
    
        $this->options = get_option('ftu_html5_options');
        
        /* actions */
        add_action( 'admin_init', array($this,'check_tinyMCE'));
        add_action( 'admin_head', array($this,'custom_post_type_icon'));
        
        /* queue frontend scripts */
		add_action('wp_enqueue_scripts', array($this,'script_init_42U'));
		
		/* queue admin scripts */
		add_action('admin_enqueue_scripts', array($this,'admin_script_init_42U'));
		
        add_action('admin_menu', array($this,'register_42U_page'));  
        /* filters */
        if ($this->options['disable_tinyMCE'] !='1') {
            add_filter('tiny_mce_before_init', array($this, 'register_42U_TinyMCE')); 
        }

    }
    
    public function check_tinyMCE() {
        // check for visualblocks plugin
        // wp root /wp-includes/js/tinymce/plugins/visualblocks
        $theme_root = get_theme_root();
        $root = str_replace('wp-content/themes','', $theme_root);
        $visualblocks_src = plugin_dir_path( __FILE__ ) . 'inc/tinyMCE/plugins/visualblocks';
        $icons_src = plugin_dir_path( __FILE__ ) . 'inc/tinyMCE/themes/advanced/img/icons.gif';
        $tinymce_plugins = $root. 'wp-includes/js/tinymce/plugins/';
        $tinymce_icons = $root . 'wp-includes/js/tinymce/themes/advanced/img/icons.gif';
        
        if (is_dir($tinymce_plugins . 'visualblocks')) {
            // echo 'rejoice, all is well';
        } else {
            // copy in the visualblocks plugin
            $result = FTU::smartCopy($visualblocks_src, $tinymce_plugins);
            // copy in the updated images
            $result = FTU::smartCopy($icons_src, $tinymce_icons);
        }
        
    }
    
    public function register_42U_TinyMCE($in) {
        $in['schema']='html5';
        $in['end_container_on_empty_block'] = 'true';
        $in['plugins'] .= ",visualblocks";
        $in['visualblocks_default_state'] = 'false';
        // HTML5 formats
        $in['style_formats'] = "[
                    {title : 'Heading 1', block : 'h1'},
                    {title : 'Heading 2', block : 'h2'},
                    {title : 'Heading 3', block : 'h3'},
                    {title : 'Heading 4', block : 'h4'},
                    {title : 'Heading 5', block : 'h5'},
                    {title : 'Heading 6', block : 'h6'},
                    {title : 'Paragraph', block : 'p'},
                    {title : 'div', block : 'div'},
                    {title : 'pre', block : 'pre'},
                    {title : 'section', block : 'section', wrapper: true, merge_siblings: false},
                    {title : 'article', block : 'article', wrapper: true, merge_siblings: false},
                    {title : 'hgroup', block : 'hgroup', wrapper: true},
                    {title : 'aside', block : 'aside', wrapper: true},
                    {title : 'figure', block : 'figure', wrapper: true},
                    {title : 'figcaption', block : 'figcaption', wrapper: true}        
        ]";
        
        $in['theme_advanced_buttons2'] = "styleselect,underline,justifyfull,forecolor,|,pastetext,pasteword,removeformat,|,charmap,|,outdent,indent,|,undo,redo,wp_help,|,visualblocks";
        
        return $in;
    }
    
    public function custom_post_type_icon() {
        ?>
        <style>
            /* Admin Menu - 16px */
            #menu-posts-HTML5 .wp-menu-image {
                background: url(<?php echo plugins_url('images/HTML5_adminmenu16-sprite.png', __FILE__) ?>) no-repeat 6px 6px !important;
            }
            #menu-posts-HTML5:hover .wp-menu-image, #menu-posts-HMG.wp-has-current-submenu .wp-menu-image {
                background-position: 6px -26px !important;
            }
            /* Post Screen - 32px */
            .icon32-posts-HTML5 {
                background: url(<?php echo plugins_url('images/HTML5_adminpage32.png', __FILE__) ?>) no-repeat left top !important;
            }
            @media
            only screen and (-webkit-min-device-pixel-ratio: 1.5),
            only screen and (   min--moz-device-pixel-ratio: 1.5),
            only screen and (     -o-min-device-pixel-ratio: 3/2),
            only screen and (        min-device-pixel-ratio: 1.5),
            only screen and (                min-resolution: 1.5dppx) {
                 
                /* Admin Menu - 16px @2x */
                #menu-posts-HTML5 .wp-menu-image {
                    background-image: url(<?php echo plugins_url('images/HTML5_adminmenu16-sprite_2x.png', __FILE__) ?>) !important;
                    -webkit-background-size: 16px 48px;
                    -moz-background-size: 16px 48px;
                    background-size: 16px 48px;
                }
                /* Post Screen - 32px @2x */
                .icon32-posts-HTML5 {
                    background-image: url(<?php echo plugins_url('images/HTML5_adminpage32_2x.png', __FILE__) ?>) !important;
                    -webkit-background-size: 32px 32px;
                    -moz-background-size: 32px 32px;
                    background-size: 32px 32px;
                }        
            }
        </style>
        <?php 

    }
    
    public function register_42U_page() {
        
        /* set up this 42U plugin admin page*/
        
        // queue backend scripts
        add_action( 'admin_init', array($this,'script_init_42U') );
        
        /* create nav and load page styles */
        $page = add_submenu_page('42-Umbrellas', '42U HTML5 Options','HTML5 Options','administrator',__FILE__, array($this,'html5_opts_42U'));
        
        /* register options */
        register_setting('ftu_html5_options','ftu_html5_options'); // 3rd param = optional callback
        add_settings_section('ftu_html5_main_section','HTML5 Settings',array($this,'ftu_html5_main_section_cb'),__FILE__);
        add_settings_field('disable_tinyMCE',"Disable the HTML5 Schema in TinyMCE, I'd rather use HTML4",array($this,'disable_tinyMCE_setting'),__FILE__,'ftu_html5_main_section');
        add_settings_field('disable_modernizr',"Skip Modernizr, I don't need it!",array($this,'disable_modernizr_setting'),__FILE__,'ftu_html5_main_section');
        
        add_settings_section('ftu_html5_polyfill_section','Polyfill Settings',array($this,'ftu_html5_main_section_cb'),__FILE__);
        
    }
    
    public function script_init_42U() {
        /* Register our scripts. */
        if (!$this->options['disable_modernizr']) {
            wp_deregister_script('modernizr'); // deregister
            wp_register_script( 'modernizr', plugins_url('js/modernizr.custom.js',__FILE__,false) );        
            wp_enqueue_script( 'modernizr' );
        }
    }
    
    public function admin_script_init_42U() {
        wp_register_style( 'HTML5Stylesheet', plugins_url('css/style.css', __FILE__) );
        wp_enqueue_style( 'HTML5Stylesheet' );
    }
    
    public function html5_opts_42U() {
        ?> 
        
        <div class="wrap">
            <div id="HTML5" class="icon32 icon32-posts-HTML5"></div>
            <h2>HTML5 Options</h2>
            <?php
                if (isset($_GET['settings-updated'])) { 
                    FTU::show_42_message('<strong>Updated</strong>');
                } 
            ?>
            <p>
                The 42U HTML5 Upgrade prepares your older site for HTML5 and allows you to start using HTML5 structure tags right now in your posts and pages (section, aside, article, etc), regardless of the browser viewing them.
             </p>
             <p>   
                The 42U HTML5 Upgrade plugin loads <a href="http://www.modernizr.com/" target="_blank">Modernizr</a>, enables the HTML5 Schema in the <a href="http://www.tinymce.com/" target="_blank">TinyMCE Editor</a> and adds HTML5 elements to the TinyMCE style list. The custom Modernizr build includes all CSS3, HTML5 and Misc tests, as well as the html5shiv w/printshiv and Modernizr.load(). See the options we used <a href="<?php echo plugins_url('images/modernizer.build.png', __FILE__) ?>" target="_blank">here</a>.
             </p>
             <p>
                You can turn these functions off individually if you wish!
            </p>
           
            <form method="POST" action="options.php" enctype="multipart/form-data">
                
                <?php settings_fields('ftu_html5_options'); ?>
                <?php do_settings_sections(__FILE__); ?>
                
                <p class="submit">
                    <input type="submit" name="submit" class="button-primary" value="Save Changes" />
                </p>
            </form>
                
        </div>
        
        <?php
    }
    
    public function ftu_html5_main_section_cb() {
    
    }
    
    public function disable_modernizr_setting() {
        $checked = ( $this->options['disable_modernizr'] == '1' ) ? 'checked' : '';
        echo "<input type='checkbox' name='ftu_html5_options[disable_modernizr]' value='1' $checked />";
    }
    
	public function disable_tinyMCE_setting() {
	    $checked = ( $this->options['disable_tinyMCE'] == '1' ) ? 'checked' : '';
        echo "<input type='checkbox' name='ftu_html5_options[disable_tinyMCE]' value='1' $checked />";
    }
}

$HTML542U = new _42UHTML5();
