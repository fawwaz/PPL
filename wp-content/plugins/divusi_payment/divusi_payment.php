<?php
/*
Plugin Name: Divusi Payment
Plugin URI: http://facebook.com/fawwazmuhammad
Description: Payment system for divusi
Version: 1.0
License: GPLv2
Author: Fawwaz Muhammad, Yogi Sinaga, Ridho Akbarisanto
Author URI: http://twitter.com/fawwaz_muhammad
*/

define('MAX_UPLOAD_SIZE', 200000);
define('TYPE_WHITELIST', serialize(array(
  'image/jpeg',
  'image/jpg',
  'image/png',
  'image/gif'
  )));


add_action('admin_menu', 'divusi_payment_add_dashboard_menu');

function divusi_payment_add_dashboard_menu(){

	add_menu_page( 'Pembayaran', 'Pembayaran', 'read', 'Pembayaran', 'render_divusi_payment_dashboard_menu', 'dashicons-products', 99 );
}

function render_divusi_payment_dashboard_menu(){
	if(isset( $_POST['divusi_payment_submitted'] ) && wp_verify_nonce($_POST['divusi_payment_submitted'], 'divusi_payment_form') ){  		
		$result = divusi_payment_parse_file_errors($_FILES['bukti_transfer'], $_POST['keterangan'] );
		if($result['error']){
			echo '<p>ERROR: ' . $result['error'] . '</p>';
		}else{
			$data_pembayaran = array(
				'post_title' => $result['caption'],
			 	'post_status' => 'pending',
			 	'post_author' => $current_user->ID,
			 	'post_type' => 'pembayaran'
			 );

			if($post_id=wp_insert_post($data_pembayaran)){
				// bar proses gambar
				divusi_payment_process_image('bukti_transfer',$post_id,$result['caption']);
				// set object terms kategori nya
			}



		}
//		lakukan processing form jika diupload
		$tgl_lahir      = $_POST['tanggal_bayar'];
		$waktu_bayar    = $_POST['waktu_bayar'];
		// handle file processing here
		$bukti_transfer = $_FILES['bukti_transfer'];
	}
	

	?>
	<div class="wrap">
		jangan lupa render dulu status submission yang udah pernah
		 <?php screen_icon('dashicons-products'); ?> <h2>Front page elements</h2>
		 
		 <form action="" method="POST" enctype="multipart/form-data">
		 	<table class="form-table">
		 		<tr>
		 			<td>Tanggal Pembayaran (Format: DD/MM/YYYY)</td>
		 			<td>
		 				<input type="text" name="tanggal_bayar" col>
		 			</td>
		 		</tr>
		 		<tr>
		 			<td>Waktu Pembayaran (Format 24 jam: HH:SS)</td>
		 			<td>
		 				<input type="text" name="waktu_bayar" >
		 			</td>
		 		</tr>
		 		<tr>
		 			<td>Bukti transfer</td>
		 			<td>
		 				<input type="file" name="bukti_transfer" id="bukti_transfer">
		 			</td>
		 		</tr>
		 		<tr>
		 			<td>Keterangan </td>
		 			<td><textarea name="keterangan"></textarea>
		 			</td>
		 		</tr>
		 		<tr>
		 			<td><input type="hidden" name="username" value="<?php echo get_current_user_id();?>" disabled></td>
		 			<td><input type="submit" id="divusi_payment_submit" name="divusi_payment_submit" value="Kirim Konfirmasi Pembayaran" class="button button-primary button-large">
		 				<?php wp_nonce_field('divusi_payment_form', 'divusi_payment_submitted'); ?></td>
		 		</tr>
		 	</table>
		 </form>
	</div>
	<?php
}

function divusi_payment_parse_file_errors($file = '', $image_caption){

	$result = array();
	$result['error'] = 0;
	
	if($file['error']){	
  		$result['error'] = "Coba periksa kembali, apakah anda sudah memasukan gambar bukti trasnfer yang ingin diupload?";
  		return $result;
	}

  	$image_caption = trim(preg_replace('/[^a-zA-Z0-9\s]+/', ' ', $image_caption));
  
  	if($image_caption == ''){
	  	  $result['error'] = "Keterngan yang dapat anda berikan hanya bisa mengandung angka , huruf dan spasi saja";
	  	  return $result;
  	}
  
  	$result['caption'] = $image_caption;

  	var_dump($file);
	
	$image_data = getimagesize($file['tmp_name']);
	  	
	if(!in_array($image_data['mime'], unserialize(TYPE_WHITELIST))){	
		$result['error'] = 'Gambar anda harus berupa file dengan extensi jpeg, png atau gif!';
  	}elseif(($file['size'] > MAX_UPLOAD_SIZE)){	
    	$result['error'] = 'Bukti Trasnfer anda ' . $file['size'] . ' bytes! Ini melebihi batas maksimal yaitu' . MAX_UPLOAD_SIZE . ' bytes.';
  	}	
	return $result;
}


function divusi_payment_process_image($file, $post_id, $caption){
	require_once(ABSPATH . "wp-admin" . '/includes/image.php');
	require_once(ABSPATH . "wp-admin" . '/includes/file.php');
	require_once(ABSPATH . "wp-admin" . '/includes/media.php');
	
	$attachment_id = media_handle_upload($file, $post_id);
	
	update_post_meta($post_id, '_thumbnail_id', $attachment_id);
	
	$attachment_data = array(
		'ID' => $attachment_id,
		'post_excerpt' => $caption
	);
	
	wp_update_post($attachment_data);
	
	return $attachment_id;
}

function divusi_payment_display_user_payment($user_id){
	$args = array(
			'author' => $user_id,
			'post-type' => 'pembayaran',
			'post_status' => 'pending'
	);

	$pembayarans = new WP_Query($args);

	// check if empty
	if(!$pembayarans->post_count){
		$out ='';
		$out .='<p>Anda belum pernah menfkonfirmasi pembayaran apapun</p>';
	}else{
		$out = '';
		$out .='Berikut adalah konfirmasi yang pernah anda lakukan yang masih dalam proses verifikasi oleh staff kami';
		$out .='<table>';
		foreach ($pembayarans->posts as $pembayaran) {
					# code...
		}		
		$out .= '</table>';
	}

	return $out;
}

/**
* Registers a new post type
* @uses $wp_post_types Inserts new post type object into the list
*
* @param string  Post type key, must not exceed 20 characters
* @param array|string  See optional args description above.
* @return object|WP_Error the registered post type object, or an error object
*/
function divusi_payment() {

	$labels = array(
		'name'                => 'Pembayaran',
		'singular_name'       => 'Pembayaran',
		'add_new'             => 'Buat Pembayaran', 'text-domain',
		'add_new_item'        => 'Tambah Pembayaran',
		'edit_item'           => 'Edit Pembayaran',
		'new_item'            => 'Pembayaran Baru',
		'view_item'           => 'Lihat Pembayaran',
		'search_items'        => 'Cari Pembayaran',
		'not_found'           => 'Pembayaran Tidak Ditemukan',
		'not_found_in_trash'  => 'Pembayaran Tidak Ditemukan',
		'menu_name'           => 'Pembayaran',
	);

	$args = array(
		'labels'                   => $labels,
		'hierarchical'        => false,
		'description'         => 'description',
		'taxonomies'          => array(),
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => null,
		'menu_icon'           => null,
		'show_in_nav_menus'   => true,
		'publicly_queryable'  => true,
		'exclude_from_search' => false,
		'has_archive'         => true,
		'query_var'           => true,
		'can_export'          => true,
		'rewrite'             => true,
		'capability_type'     => 'post',
		'supports'            => array(
			'custom-fields','thumbnail'
			)
	);

	register_post_type( 'pembayaran', $args );
}

add_action( 'init', 'divusi_payment' );



/**

* Terkait dengan registrasi user
*
*
*/

