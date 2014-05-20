<?php
/*
Plugin Name: Divusi Open Course
Plugin URI: http://facebook.com/divusi
Description: Plugin custom untuk divusi open Course
Author: Fawwaz, Yogi, Ridho
Version: 1.0
Author URI: 
License: GPLv2 or later
*/

/*
TODO LIST
1. Get user role for custom post type
2. Custom template for displaying custom post type
3. Create nwe user role (add role)
4. Create new user capability
5. Upload forms image and list it
6. Custom user meta field like active date untill..
7. Member get member  mungkin dari url ada username referral nya 



*/

/**
* CUSTOM PAGE
*

*
*
*/



define ( 'DIVUSI_PLUGIN_URL', plugin_dir_url(__FILE__)); // with forward slash (/).


/**
* Fungsi fungsi selama aktivasi ()
*
*/
//===============================================================
register_activation_hook( __FILE__, 'divusi_course_activate' );
register_deactivation_hook(__FILE__, 'divusi_course_deactivate' );

function divusi_course_activate(){    
    $premium = add_role('premium_member','Premium member',array(
                            'upload_files'=>true,
                            'read'=>true
                        ));
    if($premium!==null){
        $role = get_role('administrator');
        $role->add_cap('payment_menu');
    }
}

function divusi_course_deactivate(){
    remove_role('premium_member');
}





add_action('admin_menu', 'divusi_course_wpmut_plugin_menu');

function divusi_course_wpmut_plugin_menu(){
    add_menu_page( 'Payment_menu', 'Payment menu', 'read', 'payment-menu', 'divusi_course_wpmut_plugin_page' );
}

 function divusi_course_wpmut_plugin_page()
 {
 		echo '<div class="wrap">';
		echo '<div id="icon-options-general" class="icon32"></div><h2>Konfirmasi pembayaran</h2>';
		global $current_user;
		get_currentuserinfo();



		?>
		<table class="widefat">
			<thead>
				<tr><th colspan="2">Upload Scan/Foto bukti pembayaran</th></tr>
			</thead>
			<tr>
				<td width="150"><span class="field_name">username</span></td>
				<td>
					<input type="text" name="username" value="<?php echo $current_user->user_login; ?>" disabled>
				</td>
			</tr>
			<tr>
				<td width="150"><span class="field_name">Tanggal pembayran (format YYYY-MM-DD)</span></td>
				<td>
					<input type="text" value="<?php echo "hello"; ?>">
				</td>
			</tr>
			<tr>
				<td width="150"><span class="field_name">Image Location</span></td>
				<td>
					<input type="text"  name="image_location" value="" size="40" />
					<input type="button" class="divusi_course-upload-button button" value="Upload Image" />
					<br /><span>Enter the image location or upload an image from your computer.</span>
				</td>
			</tr>
			
		</table>
		


		<?php
		echo "</div>";
 
 
 }


    
function divusi_course_wpmut_admin_scripts() 
{
 if (isset($_GET['page']) && $_GET['page'] == 'payment-menu')
     {
         wp_enqueue_script('jquery');
         wp_enqueue_script('media-upload');
         wp_enqueue_script('thickbox');
         wp_register_script('my_script_upload', DIVUSI_PLUGIN_URL.'divusi_course-wpmut-admin-script.js', array('jquery','media-upload','thickbox'));
         wp_enqueue_script('my_script_upload');
     }
}

function divusi_course_wpmut_admin_styles()
{
 if (isset($_GET['page']) && $_GET['page'] == 'payment-menu')
     {
         wp_enqueue_style('thickbox');
     }
}
add_action('admin_print_scripts', 'divusi_course_wpmut_admin_scripts');
add_action('admin_print_styles', 'divusi_course_wpmut_admin_styles');
 
 




/**
* Registers a new post type
* @uses $wp_post_types Inserts new post type object into the list
*

* @param string  Post type key, must not exceed 20 characters
* @param array|string  See optional args description above.
* @return object|WP_Error the registered post type object, or an error object
*/
function divusi_course_create_post_type() {

	$labels = array(
		'name'                => "Materi",
		'singular_name'       => "materi",
		'add_new'             => "Tambah materi",
		'add_new_item'        => "Tambah materi",
		'edit_item'           => "Ubah materi",
		'new_item'            => "materi Baru",
		'view_item'           => "Lihat materi",
		'search_items'        => "Cari Materi",
		'not_found'           => "Materi Tidak ditemukan",
		'not_found_in_trash'  => "Materi tidak ditemukan di trash",
		'parent_item_colon'   => "materi",
		'menu_name'           => "Materi",
	);

	$args = array(
		'labels'                   => $labels,
		'public'              => true,
		'menu_position'       => 15,
		'menu_icon'           => 'dashicons-format-video',
		'show_in_nav_menus'   => true,
		'publicly_queryable'  => true,
		'exclude_from_search' => false,
		'has_archive'         => true,
		'query_var'           => true,
		'can_export'          => true,
		'rewrite'             => true,
		'capability_type'     => 'post',
		'supports'            => array(
			'title','editor'
			)
	);

	register_post_type( 'divusi_materi', $args );
}


add_action( 'init', 'divusi_course_create_post_type');

/**
* MEMBUAT TAXONOMY
*

*
*
*
*
*
*/

add_action( 'init', 'register_taxonomy_divusi_course' );

function register_taxonomy_divusi_course() {

    $labels = array( 
        'name' => _x( 'Course', 'divusi_course' ),
        'singular_name' => _x( 'Courses', 'divusi_course' ),
        'search_items' => _x( 'Search Course', 'divusi_course' ),
        'popular_items' => _x( 'Popular Course', 'divusi_course' ),
        'all_items' => _x( 'All Course', 'divusi_course' ),
        'parent_item' => _x( 'Parent Courses', 'divusi_course' ),
        'parent_item_colon' => _x( 'Parent Courses:', 'divusi_course' ),
        'edit_item' => _x( 'Edit Courses', 'divusi_course' ),
        'update_item' => _x( 'Update Courses', 'divusi_course' ),
        'add_new_item' => _x( 'Add New Courses', 'divusi_course' ),
        'new_item_name' => _x( 'New Courses', 'divusi_course' ),
        'separate_items_with_commas' => _x( 'Separate course with commas', 'divusi_course' ),
        'add_or_remove_items' => _x( 'Add or remove course', 'divusi_course' ),
        'choose_from_most_used' => _x( 'Choose from the most used course', 'divusi_course' ),
        'menu_name' => _x( 'Course', 'divusi_course' ),
    );

    $args = array( 
        'labels' => $labels,
        'public' => true,
        'show_in_nav_menus' => true,
        'show_ui' => true,
        'show_tagcloud' => true,
        'show_admin_column' => false,
        'hierarchical' => true,

        'rewrite' => true,
        'query_var' => true
    );

    register_taxonomy( 'divusi_course', array('divusi_materi'), $args );
}


/**
* MEMBUAT META FIELDS
*

*
*
*
*
**/


function divusi_materi_meta_box() {
	add_meta_box( 'divusi_materi_meta_box',
		'Konten Berbayar',
		'display_divusi_materi_meta_box',
		'divusi_materi', 'normal', 'high'
	);
}

function display_divusi_materi_meta_box($divusi_materi){
	$berbayar = esc_html( get_post_meta( $divusi_materi->ID, 'divusi_course_berbayar', true ) );
	?>
	<table>
		<p>Setiap materi harus di assign apakah materi tersebut materi berbayar atau materi tak berbayar(gratis). Secara default, materi dianggap materi gratis </p>
		<tr>
			<td style="width: 150px">Konten Berbayar ?</td>
			<td>
				<select style="width: 100px" name="divusi_course_berbayar">
					<option value="tidak" <?php echo selected("tidak",$berbayar)?>>tidak</option>
					<option value="ya" <?php echo selected("ya",$berbayar)?>>ya</option>
				</select>
			</td>
		</tr>
	</table>
	<?php
}


function add_divusi_lesson_fields( $divusi_materi_id, $divusi_materi ) {
	if ( $divusi_materi->post_type == 'divusi_materi' ) {
		// Store data in post meta table if present in post data
		if ( isset( $_POST['divusi_course_berbayar'] ) && $_POST['divusi_course_berbayar'] != '' ) {
			update_post_meta( $divusi_materi_id, 'divusi_course_berbayar', $_POST['divusi_course_berbayar'] );
		}
		
	}
}



add_action( 'save_post', 'add_divusi_lesson_fields', 10, 2 );
add_action( 'admin_init', 'divusi_materi_meta_box' );	

/**
* ROLE MANAGEMENT
*

*
*
*
*
*/









/**
* CUSTOM PAGE
*

*
*
*
*/




// add_action('admin_menu','register_payment_menu_Page');

// function register_payment_menu_Page(){
// 	add_menu_page('My Menu', 'My Menu', 'payment-menu','view-stats','show_payment_menu');
// }

// function show_payment_menu(){
// 	echo "test";
// }


/**
* PAYMENT
*

*
*
*/


if(is_admin()){
	new Divusi_payment();
}

class Divusi_payment{
	public function __construct(){
		add_action('admin_menu', array($this, 'add_menu_payment'));
	}

	public function add_menu_payment(){
		add_menu_page( 'Payment', 'Payment', 'manage_options', 'payment-table.php', array($this, 'payment_table_page') );
	}

	public function payment_table_page(){
        if($_GET['action']=='perpanjang'){
            $user_id        = $_GET['user_id'];
            $tgl_pembayaran = $_GET['tgl_pembayaran'];
            $tgl_baru       = date('Y-m-d H:i:s',strtotime('+1 month'));


            if(get_user_meta( $user_id, 'active_untill',true)!=''){
                update_usermeta($user_id,'active_untill',$tgl_baru);
            }else{
                add_usermeta($user_id,'active_untill',$tgl_baru);
            }


            echo "ini diperpanjang lho do here usernamenya yang akan diperpanjang".$username.'tgl_baru'.$tgl_baru;
        }else{
    		$Payment_table = new Payment_List_Table();
            $Payment_table->prepare_items();
        ?>
            <div class="wrap">
                <div id="icon-users" class="icon32"></div>
                <h2>Example List Table Page</h2>
                <?php $Payment_table->display(); ?>
            </div>
        <?php
        }
	}

}


if(!class_exists('WP_List_Table')){
	require_once(ABSPATH.'wp-admin/includes/class-wp-list-table.php');
}

class Payment_List_Table extends WP_List_Table{
	public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $data = $this->table_data();
        usort( $data, array( &$this, 'sort_data' ) );

        $perPage = 2;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);

        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );

        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }

    public function get_columns()
    {
        $columns = array(
			'id'                 => 'ID',
            'user_id'            => 'User_id',
			'username'           => 'username',
			'tanggal pembayaran' => 'Tanggal Pembayaran',
			'bukti pembayaran'   => 'Bukti pembayaran',
			'status konfirmasi'  => 'Status Konfirmasi',
            'aktifkan'           => 'Aktifkan'
        );

        return $columns;
    }

    public function get_hidden_columns()
    {
        return array('user_id');
    }

    public function get_sortable_columns()
    {
        return array(
        	'tanggal pembayaran' => array('tanggal pembayaran', false),
        	'status konfirmasi' => array('status konfirmasi', false)
        	);
    }

    private function table_data()
    {
        $data = array();

        $data[] = array(
        	'id'                 =>1,
            'user_id'            => 1,
			'username'           =>'aku',
			'tanggal pembayaran' =>'2014-09-06',
			'bukti pembayaran'   =>'http://localhost/ppl/wp-content/uploads/2014/04/analisis-keuangan.jpg',
			'status konfirmasi'  =>'sudah'
        );

		
		$data[] = array(
        	'id'                 => 2,
            'user_id'            => 2,
			'username'           =>'aku',
			'tanggal pembayaran' =>'2014-09-06',
			'bukti pembayaran'   =>'<img>C:/xampp/htdocs/dokter/b.jpg</img>',
			'status konfirmasi'  =>'belum'
        );
        
        $data[] = array(
        	'id'                 =>3,
            'user_id'            => 2,
			'username'           =>'admin aja',
			'tanggal pembayaran' =>'2014-09-06',
			'bukti pembayaran'   =>'C:/xampp/htdocs/dokter/c.jpg',
			'status konfirmasi'  =>'belum'
        );

        $data[] = array(
        	'id'                 =>4,
            'user_id'            => 2,
			'username'           =>'akunp2',
			'tanggal pembayaran' =>'2014-09-06',
			'bukti pembayaran'   =>'C:/xampp/htdocs/dokter/d.jpg',
			'status konfirmasi'  =>'sudah'
        );

        return $data;
    }

    public function column_id($item)
	{
	    return $item['id'];
	}

	public function column_default( $item, $column_name )
    {
        switch( $column_name ) {
            case 'id':
            case 'username':
            case 'tanggal pembayaran':
            case 'status konfirmasi':
                return $item[ $column_name ];
            case 'bukti pembayaran':
            	return '<img class="attachment-80x60" width="80" height="60" src="'.$item[$column_name].'">';
            case 'aktifkan':
                return sprintf('<a href="?page=%s&action=%s&tgl_pembayaran=%s&user_id=%s">Perpanjang Massa Aktif Akun!</a>',$_REQUEST['page'],'perpanjang',$item['tanggal pembayaran'],$item['user_id']);
            default:
                return print_r( $item, true ) ;
        }
    }

    private function sort_data( $a, $b )
    {
        // Set defaults
        $orderby = 'tanggal pembayaran';
        $order = 'desc';

        // If orderby is set, use this as the sort column
        if(!empty($_GET['orderby']))
        {
            $orderby = $_GET['orderby'];
        }

        // If order is set use this as the order
        if(!empty($_GET['order']))
        {
            $order = $_GET['order'];
        }

        $result = strcmp( $a[$orderby], $b[$orderby] );

        if($order === 'asc')
        {
            return $result;
        }

        return -$result;
    }


}



/**
*
*
*
*/
//============================

/**
* SUGGESTION COURSE
*
*
*/

add_filter('the_content','suggestion_module');

function suggestion_module($content){
    // $id = get_the_id();
    // if(!is_singular()){
    //     return $content;
    // }
    // $terms = get_the_terms($id,'category');
    // $cats = array();

    // foreach ($terms as $term) {
    //     $cats[] = $term->ID;
    // }

    // $loop = new WP_Query(
    //     array(
    //         'posts_per_page' =>3,
    //         'category_in' =>$cats
    //         )
    //     );

    // if($loop->have_post()){
    //     $content.='Coba juga tutorial ini :';
    //     $content.='<ul class="related-category-posts">';

    //     while($loop->have_posts()){
    //         $loop->the_post();
    //         $content.=  '<li>'.
    //                     '<a href="'.get_permalink().'">'.get_the_title( ).'</a>'
    //                     .'</li>';
    //     }
    //     $content.="</ul>";
    //     wp_reset_query();
    // }
    // return $content;

    $custom_taxterms = wp_get_object_terms( get_the_id(), 'divusi_course');    
    
    // arguments
    $args = array(
    'post_type' => 'divusi_materi',
    'post_status' => 'publish',
    'posts_per_page' => 3, // you may edit this number
    'orderby' => 'rand',
    'tax_query' => array(
        array(
            'taxonomy' => 'divusi_course',
            'field' => 'id',
            'terms' => $custom_taxterms[0]->term_id
        )
    ),
    'post__not_in' => array (get_the_id()),
    );
    $related_items = new WP_Query( $args );
    // loop over query
    if ($related_items->have_posts()) :
    echo '<ul>';
    while ( $related_items->have_posts() ) : $related_items->the_post();
    ?>
        <li><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
    <?php
    endwhile;
    echo '</ul>';
    endif;
    // Reset Post Data
    wp_reset_postdata();
    return $content;
}

/**
*
* TUTORIAL
*
*/
//=======================================


function wptuts_get_default_options() {
    $options = array(
        'logo' => ''
    );
    return $options;
}


function wptuts_options_init() {
     $wptuts_options = get_option( 'theme_wptuts_options' );
     
     // Are our options saved in the DB?
     if ( false === $wptuts_options ) {
          // If not, we'll save our default options
          $wptuts_options = wptuts_get_default_options();
          add_option( 'theme_wptuts_options', $wptuts_options );
     }
     
     // In other case we don't need to update the DB
}
// Initialize Theme options
add_action( 'after_setup_theme', 'wptuts_options_init' );

function wptuts_options_setup() {
    global $pagenow;
    if ('media-upload.php' == $pagenow || 'async-upload.php' == $pagenow) {
        // Now we'll replace the 'Insert into Post Button inside Thickbox' 
        add_filter( 'gettext', 'replace_thickbox_text' , 1, 2 );
    }
}
add_action( 'admin_init', 'wptuts_options_setup' );

function replace_thickbox_text($translated_text, $text ) {  
    if ( 'Insert into Post' == $text ) {
        $referer = strpos( wp_get_referer(), 'wptuts-settings' );
        if ( $referer != '' ) {
            return __('I want this to be my logo!', 'wptuts' );
        }
    }

    return $translated_text;
}

// Add "WPTuts Options" link to the "Appearance" menu
function wptuts_menu_options() {
    //add_theme_page( $page_title, $menu_title, $capability, $menu_slug, $function);
     add_theme_page('Promosi', 'Promosi', 'read', 'wptuts-settings', 'wptuts_admin_options_page');
}
// Load the Admin Options page
add_action('admin_menu', 'wptuts_menu_options');

function wptuts_admin_options_page() {
    ?>
        <!-- 'wrap','submit','icon32','button-primary' and 'button-secondary' are classes 
        for a good WP Admin Panel viewing and are predefined by WP CSS -->
        
        
        
        <div class="wrap">
            
            <div id="icon-themes" class="icon32"><br /></div>
        
            <h2><?php _e( 'Promosi bulan ini', 'Promosi' ); ?></h2>
            
            <!-- If we have any error by submiting the form, they will appear here -->
            <?php settings_errors( 'wptuts-settings-errors' ); ?>
            
            <form id="form-wptuts-options" action="options.php" method="post" enctype="multipart/form-data">
            
                <?php
                    settings_fields('theme_wptuts_options');
                    do_settings_sections('wptuts');
                ?>
            
                <p class="submit">
                <?php if(current_user_can('view-stats')){?>
                    <input name="theme_wptuts_options[submit]" id="submit_options_form" type="submit" class="button-primary" value="<?php esc_attr_e('Save Settings', 'wptuts'); ?>" />
                    <input name="theme_wptuts_options[reset]" type="submit" class="button-secondary" value="<?php esc_attr_e('Reset Defaults', 'wptuts'); ?>" />        
                <?php }?>
                </p>
            
            </form>
            
        </div>
    <?php
}

function wptuts_options_validate( $input ) {
    $default_options = wptuts_get_default_options();
    $valid_input = $default_options;
    
    $wptuts_options = get_option('theme_wptuts_options');
    
    $submit = ! empty($input['submit']) ? true : false;
    $reset = ! empty($input['reset']) ? true : false;
    $delete_logo = ! empty($input['delete_logo']) ? true : false;
    
    if ( $submit ) {
        if ( $wptuts_options['logo'] != $input['logo']  && $wptuts_options['logo'] != '' )
            delete_image( $wptuts_options['logo'] );
        
        $valid_input['logo'] = $input['logo'];
    }
    elseif ( $reset ) {
        delete_image( $wptuts_options['logo'] );
        $valid_input['logo'] = $default_options['logo'];
    }
    elseif ( $delete_logo ) {
        delete_image( $wptuts_options['logo'] );
        $valid_input['logo'] = '';
    }
    
    return $valid_input;
}

function delete_image( $image_url ) {
    global $wpdb;
    
    // We need to get the image's meta ID..
    $query = "SELECT ID FROM wp_posts where guid = '" . esc_url($image_url) . "' AND post_type = 'attachment'";  
    $results = $wpdb -> get_results($query);

    // And delete them (if more than one attachment is in the Library
    foreach ( $results as $row ) {
        wp_delete_attachment( $row -> ID );
    }   
}

/********************* JAVASCRIPT ******************************/
function wptuts_options_enqueue_scripts() {
        wp_register_script( 'wptuts-upload', DIVUSI_PLUGIN_URL .'/js/wptuts-upload.js', array('jquery','media-upload','thickbox') );      
        if ( 'appearance_page_wptuts-settings' == get_current_screen() -> id ) {
            wp_enqueue_script('jquery');
            
            wp_enqueue_script('thickbox');
            wp_enqueue_style('thickbox');
            
            wp_enqueue_script('media-upload');
            wp_enqueue_script('wptuts-upload');
            
        }
    
}
add_action('admin_enqueue_scripts', 'wptuts_options_enqueue_scripts');


function wptuts_options_settings_init() {
    register_setting( 'theme_wptuts_options', 'theme_wptuts_options', 'wptuts_options_validate' );
    
    // Add a form section for the Logo
    add_settings_section('wptuts_settings_header', __( 'Logo Options', 'wptuts' ), 'wptuts_settings_header_text', 'wptuts');
    
    if(current_user_can('view-stats' )){
        // Add Logo uploader
        add_settings_field('wptuts_setting_logo',  __( 'Logo', 'wptuts' ), 'wptuts_setting_logo', 'wptuts', 'wptuts_settings_header');
    }    
        // Add Current Image Preview 
        add_settings_field('wptuts_setting_logo_preview',  __( 'Promo :', 'wptuts' ), 'wptuts_setting_logo_preview', 'wptuts', 'wptuts_settings_header');
}   
add_action( 'admin_init', 'wptuts_options_settings_init' );

function wptuts_setting_logo_preview() {
    $wptuts_options = get_option( 'theme_wptuts_options' );  ?>
    <div id="upload_logo_preview" style="min-height: 100px;">
        <img style="max-width:100%;" src="<?php echo esc_url( $wptuts_options['logo'] ); ?>" />
    </div>
    <?php
}

function wptuts_settings_header_text() {
    ?>
        <p><?php _e( 'Berikut promo bulan ini ', 'wptuts' ); ?></p>
    <?php
}

function wptuts_setting_logo() {
    $wptuts_options = get_option( 'theme_wptuts_options' );
    if(current_user_can('view-stats' )){
    ?>
        <input type="hidden" id="logo_url" name="theme_wptuts_options[logo]" value="<?php echo esc_url( $wptuts_options['logo'] ); ?>" />
        <input id="upload_logo_button" type="button" class="button" value="<?php _e( 'Upload Logo', 'wptuts' ); ?>" />
        <?php if ( '' != $wptuts_options['logo'] ): ?>
            <input id="delete_logo_button" name="theme_wptuts_options[delete_logo]" type="submit" class="button" value="<?php _e( 'Delete Logo', 'wptuts' ); ?>" />
        <?php endif; ?>
        <span class="description"><?php _e('Silahkan masukan promosi.', 'wptuts' ); ?></span>
    <?php }
}



/**

*/



function add_active_untill_column($columns){
    $columns['active_untill'] = 'Aktif Hingga';
    $columns['twitter'] = 'Akun twitter';
    return $columns;
}

add_filter('manage_users_columns','add_active_untill_column');

function show_active_untill_data($value,$column_name,$user_id){
    if('active_untill' == $column_name){
        return get_user_meta( $user_id, 'active_untill', true);
    }   

    if('twitter'== $column_name){
        return get_user_meta($user_id, 'oneTarek_twitter',true);
    }
}

add_action( 'show_user_profile', 'oneTarek_extra_user_profile_fields' );
add_action( 'edit_user_profile', 'oneTarek_extra_user_profile_fields' );
add_action( 'personal_options_update', 'oneTarek_save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'oneTarek_save_extra_user_profile_fields' );
 
function oneTarek_save_extra_user_profile_fields( $user_id )
 {
 if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }
 update_user_meta( $user_id, 'oneTarek_twitter', $_POST['oneTarek_twitter'] );
 }
#Developed By oneTarek , http://oneTarek.com
function oneTarek_extra_user_profile_fields( $user ){ 

    if(current_user_can('edit_users' )){
    ?>
    <h3>Extra Custom Meta Fields</h3>
    
    <table class="form-table">
    <tr>
    <th><label for="oneTarek_twitter">Twitter User Name</label></th>
    <td>
    <input type="text" id="oneTarek_twitter" name="oneTarek_twitter" size="20" value="<?php echo esc_attr( get_the_author_meta( 'oneTarek_twitter', $user->ID )); ?>">
    <span class="description">Please enter your Twitter Account User name, eg: oneTarek</span>
    </td>
    </tr>
    </table>
    <?php 
    }
}?><?php


