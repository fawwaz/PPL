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
			'username'           => 'username',
			'tanggal pembayaran' => 'Tanggal Pembayaran',
			'bukti pembayaran'   => 'Bukti pembayaran',
			'status konfirmasi'  => 'Status Konfirmasi'
        );

        return $columns;
    }

    public function get_hidden_columns()
    {
        return array();
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
			'username'           =>'aku',
			'tanggal pembayaran' =>'2014-09-06',
			'bukti pembayaran'   =>'http://localhost/ppl/wp-content/uploads/2014/04/analisis-keuangan.jpg',
			'status konfirmasi'  =>'sudah'
        );

		
		$data[] = array(
        	'id'                 =>2,
			'username'           =>'aku',
			'tanggal pembayaran' =>'2014-09-06',
			'bukti pembayaran'   =>'<img>C:/xampp/htdocs/dokter/b.jpg</img>',
			'status konfirmasi'  =>'belum'
        );
        
        $data[] = array(
        	'id'                 =>3,
			'username'           =>'admin aja',
			'tanggal pembayaran' =>'2014-09-06',
			'bukti pembayaran'   =>'C:/xampp/htdocs/dokter/c.jpg',
			'status konfirmasi'  =>'belum'
        );

        $data[] = array(
        	'id'                 =>4,
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
            default:
                return print_r( $item, true ) ;
        }
    }

    private function sort_data( $a, $b )
    {
        // Set defaults
        $orderby = 'tanggal pembayaran';
        $order = 'asc';

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





