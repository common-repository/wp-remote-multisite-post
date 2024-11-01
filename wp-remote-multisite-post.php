<?php
 
/*
Plugin Name:  WP Remote Multisite Post
Plugin URI: http://www.luciaintelisano.it/wp-remote-multisite-post
Description: 
Version: 1.0.0
Author: Lucia Intelisano
Author URI: http://www.luciaintelisano.it
*/

/*  Copyright 2015  WP Remote Multisite Post  (email : lucia.intelisano@gmail.com) */

  	// init plugin
	wrmp_init();
	
  		
		
	/**
 	* Function for adding a link on main menu of wp
 	*/	
	function wrmp_plugin_setup_menu(){
       $hookPage = add_options_page('WP Remote Multisite Post', 'WP Remote Multisite Post', 'administrator', __FILE__, 'wrmp_settings_page',plugins_url('/images/icon.png', __FILE__));
	// add_action('load-'.$hookPage ,'wrmp_plugin_settings_save');
	}
 
 function wrmp_add_meta_box() {

	$screens = array( 'post', 'page' );

	foreach ( $screens as $screen ) {

		add_meta_box(
			'wrmp_metabox',
			__( 'WP remote multisite post', 'wrmp_metabox' ),
			'wrmp_metabox_callback',
			$screen,
			'wrmp_mb',
			'high'
		);
	}
}



 

function wrmp_metabox_callback( $post ) {
 	wp_nonce_field( 'wrmp_save_meta_box_data', 'wrmp_meta_box_nonce' );
 	$options =  get_option('wrmp_options') ;
 	if (is_array($options) && count($options)>0) {
 		global $post;
    	
 		 
	//$value = get_post_meta( $post->ID, 'wrmp_my_meta_value_key', true );
    ?>
    <a href="options-general.php?page=wp-remote-multisite-post%2Fwp-remote-multisite-post.php" target="_blank">settings</a>
    <table class="form-table"  >
      <tr valign="top" id="row<?php echo $cc; ?>">
      <td> </td>
      <td><b>host name</b></td>
      <td><b>thumbnail</b></td>
 	  <td><b>publish date</b></td>
 	  <td><b>post status</b></td>
      <td><b>custom title</b></td>
      <td><b>categories</b></td>
      <td><b>custom featured image</b></td>
      </tr>
        
    <?php 
    	 include_once(get_home_path().'wp-includes/class-IXR.php');
    	foreach($options as $k => $val) { 
     			if ($options[$k]["wrmp_host"]!="") {
     				$chechedPost = "";
     				$h = $options[$k]["wrmp_host"];
					$u = $options[$k]["wrmp_username"];
					$p = $options[$k]["wrmp_pwd"];
					$metakey='wrmp_'.sanitize_title($h);
					$cats = "";
					$pday = "";
					$pmonth="";
					$pyear="";
					$phour="";
					$pmin="";
					$status="";
					$tumbPostExt = "";
					$titlePostExt = "";
					$client = new IXR_CLIENT($h.'/xmlrpc.php');
					$idPostExt = "";
					 
					$arrCategories = array();
					if ($client->query('wp.getCategories','1',$u,$p)) {
						$arrCategories = $client->getResponse();
					}	
     				if ($post->ID) {
						$idPostExt = get_post_meta($post->ID,$metakey,true);
					 	 
						if ($idPostExt!="" && $client->query('wp.getPost','1', $u,$p,$idPostExt)) {
						
							$arrPostExt = $client->getResponse();	 
							  
							if (count($arrPostExt)>0) {
								$chechedPost="checked";
								$titlePostExt = $arrPostExt["post_title"];
								$tumbPostExt = $arrPostExt["post_thumbnail"]["link"];
								$status = $arrPostExt["post_status"];
								$pday=$arrPostExt["post_date"]->day;
								$pmonth=$arrPostExt["post_date"]->month;
								$pyear=$arrPostExt["post_date"]->year;
								$phour=$arrPostExt["post_date"]->hour;
								$pmin=$arrPostExt["post_date"]->minute;
								foreach($arrPostExt["terms"] as $f => $val2) {
									if ($val2["taxonomy"]=="category") {
										$cats.=$val2["name"].",";
									}
								} 
									
								 
							} else {
								 
							}
						}
					
						 
						 
					}	
    ?>
        <tr valign="top" id="row<?php echo $i; ?>">
        <td><input   type="checkbox" value="<?php echo $options[$k]["wrmp_host"] ?>" name="wrmp_check_<?php echo $k; ?>"  <?php echo  $chechedPost; ?> /></td>
        <td> <?php echo $options[$k]["wrmp_host"] ?> </td>
        <td>
        <?php if ($tumbPostExt!="") {
        ?>
        <img src="<?php echo $tumbPostExt; ?>" width="32px" height="32px">
        <?php
        } ?>
        </td>
        	 <td>
        	 <select name="wrmp_pmonth_<?php echo $k; ?>">
        	 <option value=""></option>
        	 <?php
        	 	for($v=0;$v<12;$v++) {
        	 		$m=$v+1;
        	 		$selected = ($pmonth==($m)) ? "selected" : "";
        	 		?>
        	 		<option value="<?php echo $m; ?>" <?php echo $selected; ?>><?php echo substr(date("F", mktime(null, null, null, $m)),0,3)." ".$m; ?></option>
        	 		<?php
        	 	}
        	 ?>
        	 </select>
        	  
        	  <select name="wrmp_pday_<?php echo $k; ?>">
        	   <option value=""></option>
        	 <?php
        	 	for($v=0;$v<31;$v++) {
        	 		$d=$v+1;
        	 		$selected = ($pday==($d)) ? "selected" : "";
        	 		?>
        	 		<option value="<?php echo $d; ?>" <?php echo $selected; ?>><?php echo  $d; ?></option>
        	 		<?php
        	 	}
        	 ?>
        	 </select>
        	  <select name="wrmp_pyear_<?php echo $k; ?>">
        	 <?php
        	 	for($v=0;$v<10;$v++) {
        	 		$y=$v+date("Y", mktime(0,0,0,date("m"),date("d"),date("Y")-5));
        	 		$selected = ($pyear==($y)) ? "selected" : "";
        	 		?>
        	 		<option value="<?php echo $y; ?>" <?php echo $selected; ?>><?php echo  $y; ?></option>
        	 		<?php
        	 	}
        	 ?>
        	 </select>
        	 <br>@
        	 <input size="2" type="text" value="<?php echo $phour; ?>" name="wrmp_phour_<?php echo $k; ?>"   />:
        	 <input size="2" type="text" value="<?php echo $pmin; ?>" name="wrmp_pmin_<?php echo $k; ?>"   />
        	 
        	 
        	 </td>
        	 <td>
        	 <select name="wrmp_status_<?php echo $k; ?>">
        	 <option value=""></option>
        	  
        	<option value="publish" <?php echo ($status=="publish") ? "selected" : ""; ?>>Publish</option>
        	<option value="future" <?php echo ($status=="future") ? "selected" : ""; ?>>Future</option>
        	<option value="draft" <?php echo ($status=="draft") ? "selected" : ""; ?>>Draft</option>
        	<option value="pending" <?php echo ($status=="pending") ? "selected" : ""; ?>>Pending</option>
        	<option value="private" <?php echo ($status=="private") ? "selected" : ""; ?>>Private</option>
        	<option value="trash" <?php echo ($status=="trash") ? "selected" : ""; ?>>Trash</option>
        	 </select>
        	 </td>
        	<td><input size="75" type="text" value="<?php echo str_replace("\\","",$titlePostExt); ?>" name="wrmp_title_<?php echo $k; ?>"   /></td> 
        	<td><?php 
        	 
        	if (count($arrCategories)>0) {
        		 
        		?>
        		<select multiple id="wrmp_cat_<?php echo $k; ?>" name="wrmp_cat_<?php echo $k; ?>[]">
        		<?php
        			foreach($arrCategories as $l => $val) {
        			$selected = "";
        			if (strpos(" ".strtolower($cats),strtolower($val["description"]))>0) {
        				$selected = "selected";
        			}
        				?>
        				<option value="<?php echo $val["categoryId"]; ?>" <?php echo $selected; ?>><?php echo $val["description"]; ?></option>
        				<?php
        			}
        		?>
        		</select>
        		<?php
        	} ?></td> 
 <td> <input type="file" name="wrmp_file_<?php echo $k; ?>"> </td>
        </tr>
        <? } } ?>
     </table>
    <?php
	 }
}

 function wrmp_add_edit_form_multipart_encoding() {

    echo ' enctype="multipart/form-data"';

}
add_action('post_edit_form_tag', 'wrmp_add_edit_form_multipart_encoding');

function wrmp_save_meta_box_data( $post_id ) {

	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */

	// Check if our nonce is set.
	if ( ! isset( $_POST['wrmp_meta_box_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['wrmp_meta_box_nonce'], 'wrmp_save_meta_box_data' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}
 
	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	/* OK, it's safe for us to save the data now. */

	
	/* 
	if ( ! isset( $_POST['myplugin_new_field'] ) ) {
		return;
	}
	$my_data = sanitize_text_field( $_POST['myplugin_new_field'] );
	update_post_meta( $post_id, '_my_meta_value_key', $my_data );
	*/
	$options =  get_option('wrmp_options') ;
	$emailTo =  get_option('wrmp_email') ;
 	if (is_array($options) && count($options)>0) {
 	 
 		if (is_object($post_id)) {
				$post_id = $post_id->ID;
			 
		 }
 		$post = get_post($post_id);
 		  		 $status = $post->post_status;
 		 
 		$post_categories = wp_get_post_categories( $post_id );
	 	$post_tags = wp_get_post_tags( $post_id );
	 	
	 	 
		$listcat = array();
		$listtag = array();
		if (count($post_categories)>0) {
			foreach($post_categories as $c) {
				$cat = get_category( $c );
			 
				$listcat[] = $cat->name;	 
			}
			 	
		}
		if (count($post_tags)>0) {
			foreach($post_tags as $t) {
			  
				$listtag[] =$t->name;	 
			}
			 
		}
		 
		$base64 = "";
			$domain = get_site_url();		 
			$path_to_www_folder = get_home_path();
		if ( has_post_thumbnail()) {
		 
			$medium_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'medium');
			$path = str_replace( $domain . '', $path_to_www_folder, $medium_image_url[0] );	   
			$arrName = split("/",$path);
			$filenameImageThumb = $arrName[count($arrName)-1];
			 
			$typeImagehumb = pathinfo($path, PATHINFO_EXTENSION);
	  		$dataImageThumb = file_get_contents($medium_image_url[0]);
	  		 
	   }
	   include_once($path_to_www_folder.'wp-includes/class-IXR.php');
	  
 		$dateP = new IXR_Date(strtotime( $post->post_date ) ); 
 
 		foreach($options as $k => $val) { 
     			if ($options[$k]["wrmp_host"]!="") {
     				if ($_POST["wrmp_check_".$k]!="") {
     					$h = $options[$k]["wrmp_host"];
     					$u = $options[$k]["wrmp_username"];
     					$p = $options[$k]["wrmp_pwd"];
     					if ($_POST["wrmp_title_".$k]!="") {
     						$title = $_POST["wrmp_title_".$k];
     					} else {
     						$title = $post->post_title;
     					}
     					$pday = $_POST["wrmp_pday_".$k];
     					$pmonth = $_POST["wrmp_pmonth_".$k];
     					$pyear = $_POST["wrmp_pyear_".$k];
     					$phour = $_POST["wrmp_phour_".$k];
     					$pmin = $_POST["wrmp_pmin_".$k];
     					$typeImage = "";
     					$dataImage = "";
     					$filenameImage ="";
     					if ($_POST["wrmp_status_".$k]!="") {
     						$status = $_POST["wrmp_status_".$k];
     					}
     					if ($pday!="" && $pmonth!="" && $pyear!="" && $pmin!="" && $phour!="") {
     					$dateP = $dateP = new IXR_Date(strtotime($pmonth."/".$pday."/".$pyear." ".$phour.":".$pmin) ); 
     					}
     					
     				 	$metakey='wrmp_'.sanitize_title($h);
     				 	 
     				 	$updatePost=0;
						$client = new IXR_CLIENT($h.'/xmlrpc.php');
						 
						$ID = get_post_meta($post_id,$metakey,true);
					 
						if ($ID!="" && $client->query('wp.getPost','1', $u,$p,$ID)) {
							$arrVal = $client->getResponse();	 
							if (count($arrVal)>0) {
								 $updatePost=1;
							} else {
								delete_post_meta($post_id, $metakey, $ID);
							}
						}			
						 
					 	if ($updatePost==0) {
					 		$content = array();
					 			$content['post_status'] = $status;
								$content['post_title'] = $title;
								$content['post_excerpt'] = substr($post->post_content,0,250);
							 	$content['post_date'] = $dateP;
								$content['post_content'] = $post->post_content;
								$content['custom_fields'] = array( array('key' => 'wrmpPostID','value'=>$post_id) );
								if (count($_POST["wrmp_cat_".$k])>0) {
     				 				$listcat = $_POST["wrmp_cat_".$k];
     				 				$content['terms'] = array(  'category' =>  $listcat);
     				 				$content['terms_names'] = array(   'post_tag' =>  $listtag );
     				 			} else {
     				 				$content['terms_names'] = array(  'category' =>  $listcat , 'post_tag' =>  $listtag );
     				 			}
								 
								 
								if (!$client->query('wp.newPost','', $u,$p, $content) ) { 
									wrmp_sendEmail($email,"WP remote multisite post Plugin","Error creation new post: ".$client->getErrorMessage(),"wrmp_plugin@"); 
									 
								} else {
									$ID =  $client->getResponse();
								 
							 		add_post_meta( $post_id, $metakey, $ID,true);
							 		 
						 		}
					 	
					 	} else {
					 		$ID = get_post_meta( $post_id, $metakey, $ID);
							 
     				 		$content = array();
     				 		$content['post_status'] = $status;
							$content['post_title'] = $title;
							$content['post_date'] = $dateP;
							$content['post_excerpt'] = substr($post->post_content,0,250);
							$content['post_content'] = $post->post_content;
							$content['custom_fields'] = array( array('key' => 'wrmpPostID','value'=>$post_id) );
							
							if (count($_POST["wrmp_cat_".$k])>0) {
     				 				$listcat = $_POST["wrmp_cat_".$k];
     				 				$content['terms'] = array(  'category' =>  $listcat);
     				 				$content['terms_names'] = array(   'post_tag' =>  $listtag );
     				 			} else {
     				 				$content['terms_names'] = array(  'category' =>  $listcat , 'post_tag' =>  $listtag );
     				 			}
							 

							if (!$client->query('wp.editPost', "1", $u,$p,$ID, $content, true)) {
									wrmp_sendEmail($email,"WP remote multisite post Plugin","Error edit post: ".$client->getErrorMessage(),"wrmp_plugin@"); 
								 
							}
					 	
					 	
					 	}
					 	 
					 	$filenameImage = $filenameImageThumb;
					 	$typeImage = $typeImageThumb;
					 	$dataImage = $dataImageThumb;
						if ($_FILES["wrmp_file_".$k]['name']!="") {
						 
 							$typeImage =strtolower( pathinfo($_FILES["wrmp_file_".$k]['name'],PATHINFO_EXTENSION));
 							 
							if ($_FILES["wrmp_file_".$k]["size"] < 500000) {
							 
								if(!($typeImage != "jpg" && $typeImage != "png" && $typeImage != "jpeg" && $typeImage != "gif") ) {
									$dataImage = file_get_contents($_FILES["wrmp_file_".$k]['tmp_name']);
									$filenameImage = $_FILES["wrmp_file_".$k]['name'];
									 
								}
							}
						} else {
							if ($updatePost==1) {
							 
								$client->query('wp.getPost','1', $u,$p,$ID);
								$arrt = $client->getResponse();
								 
								if (count($arrt["post_thumbnail"])>0 && $arrt["post_thumbnail"]["thumbnail"]!="") {
								 
									$filenameImage = "";
									 
								}
								 
							}
						}
						 
						if ($ID!="" && $filenameImage!="") {
							 
							 
								$image = array(
									'name'  => $filenameImage,
									'type'  => 'image/'.typeImage,
									'bits' => new IXR_Base64($dataImage),
									'overwrite' => 1 ,
									'post_parent' => $ID);

								$status = $client->query(
									'wp.uploadFile',
									1,
									$u,
									$p,
									$image);

								if(!$status){
									wrmp_sendEmail($email,"WP remote multisite post Plugin","Error creation featured image: ".$client->getErrorMessage(),"wrmp_plugin@");  
								} else {
								 
									 $media = $client->getResponse();
									$content = array(
										'post_status' => 'publish',
								 
										'wp_post_thumbnail' => $media['id']
									);
									$client->query('metaWeblog.editPost', $ID, $u, $p, $content, true);
								}
								 
						
						}
						
     					 
     					
     				  		
     			 } 
     			}
 		}
 	}
 return;
}
	
function wrmp_add_meta_box_move() {
        # Get the globals:
        global $post, $wp_meta_boxes;

        # Output the "advanced" meta boxes:
        do_meta_boxes( get_current_screen(), 'wrmp_mb', $post );

        # Remove the initial "advanced" meta boxes:
        unset($wp_meta_boxes['post']['wrmp_mb']);
    }	
	
	/**
 	* Function for init plugin
 	*/
	function wrmp_init(){
		  	add_action( 'admin_enqueue_scripts', 'wrmp_admin_enqueue' );
			add_action('admin_menu', 'wrmp_plugin_setup_menu');
	 		add_action( 'admin_init', 'wrmp_register_mysettings' ); 
	 		
	 		add_action( 'add_meta_boxes', 'wrmp_add_meta_box' );
	 		add_action('edit_form_after_title', 'wrmp_add_meta_box_move');
	 		add_action('publish_post',  'wrmp_save_meta_box_data');
			add_action('edit_page_form',   'wrmp_save_meta_box_data');  	
			add_action('draft_to_publish',   'wrmp_save_meta_box_data'  );
			add_action('new_to_publish',   'wrmp_save_meta_box_data' );
			add_action('pending_to_publish',    'wrmp_save_meta_box_data');
			add_action('future_to_publish',    'wrmp_save_meta_box_data');
	 		add_action( 'save_post', 'wrmp_save_meta_box_data' );
	 		 
	}	
 
 function wrmp_admin_enqueue() {
 	wp_enqueue_style('default_admin_style_wrmp_1', plugins_url('css/wrmp_admin_style.css', __FILE__), false, time());
 
 	
	wp_enqueue_script('default_admin_scripts_wrmp_2', plugins_url('js/wrmp_mimic.js', __FILE__), array(), time(), false );
 	wp_enqueue_script('default_admin_scripts_wrmp_3', plugins_url('js/wrmp_wordpress.js', __FILE__), array(), time(), true );
	wp_enqueue_script('default_admin_scripts_wrmp_4', plugins_url('js/wrmp_connectwp.js', __FILE__), array(), time(), true );
}
 
	/**
 * Function for register settings
 */
function wrmp_register_mysettings() {
	register_setting( 'wrmp-settings-group', 'wrmp_options' );
 	register_setting( 'wrmp-settings-group', 'wrmp_email' );
}

	/**
 * Function for view settings page 
 */
function wrmp_settings_page() {
 
 ?>
 
<div class="wrap">
<h2>WP Remote Multisite Post</h2>
 
<form id="wrmpform" name="wrmpform" method="post" action="options.php"  >
    <?php 
     	$cc = 0;
    	settings_fields( 'wrmp-settings-group' );  
    	do_settings_sections( 'wrmp-settings-group' ); 
 		$options =  get_option('wrmp_options') ;
 		$email =  get_option('wrmp_email') ;
 		if (is_array($options)) {
 			$cc = count($options);
 		}
     ?>
     <br><br>
     <input  type="hidden" name='wrmp_plugin_path' id="wrmp_plugin_path"    value="<?php echo plugins_url( 'img/loading.gif', __FILE__ ); ?>" />
    Error sending email:<input  size=75 type="text" name='wrmp_email' id="wrmp_email_111111"    value="<?php echo $email; ?>" />
    <hr>
    <table class="form-table" style="width:70%">
      <tr valign="top" id="row<?php echo $cc; ?>">
        <td  >Add new host</td>
        	<td>host:<input class="required" type="text" name='wrmp_options[<?php echo $cc; ?>][wrmp_host]' id="wrmp_host_111111"  value=""  size=75 /></td>
     		<td>username:<input class="required"  type="text" name='wrmp_options[<?php echo $cc; ?>][wrmp_username]' id="wrmp_username_111111" value=""  /></td>
     		<td>password:<input class="required"  type="text" name='wrmp_options[<?php echo $cc; ?>][wrmp_pwd]' id="wrmp_pwd_111111" value=""  /></td>
     		<td><input type="button" value="test" name="wrmp_test_<?php echo $cc; ?>" onclick='testSite(111111)' /></td>
        </tr>
         <tr valign="top" id="row<?php echo $cc; ?>">
        <td  >  </td>
        	<td><b>host name </b></td>
     		<td><b>username</b></td>
     		<td><b>password</b> </td>
     		<td> </td>
        </tr>
    <?php 
    	$i=0;
    	foreach($options as $k => $val) { 
     	if ($options[$k]["wrmp_host"]!="") {
    ?>
        <tr valign="top" id="row<?php echo $i; ?>">
        	<td><input type="button" value="delete" name="wrmp_delete_<?php echo $i; ?>" onclick=" delEvent=true;(function($) { $('#row<?php echo $i; ?>').remove();  })(jQuery);" /></td>
        	<td>host:<input type="text" size=75 name='wrmp_options[<?php echo $i; ?>][wrmp_host]' id="wrmp_host_<?php echo $i; ?>"  value="<?php echo $options[$k]["wrmp_host"]; ?>" /></td>
     		<td>username:<input type="text" name='wrmp_options[<?php echo $i; ?>][wrmp_username]' id="wrmp_username_<?php echo $i; ?>" value="<?php echo $options[$k]["wrmp_username"]; ?>" /></td>
     		<td>password:<input type="text" name='wrmp_options[<?php echo $i; ?>][wrmp_pwd]' id="wrmp_pwd_<?php echo $i; ?>" value="<?php echo $options[$k]["wrmp_pwd"]; ?>" /></td>
     		<td><input type="button" value="test" name="wrmp_test_<?php echo $i; ?>" onclick='testSite(<?php echo $i; ?>)' /></td>
 
        </tr>
        <? 
        	$i++;
        	} 
        
        } ?>
     </table>
    <input type="hidden" name="cf" id="cf" value="<?php echo ($cc); ?>">
    <input type="button" name="btnsubmit" id="btnsubmit" class="button button-primary" value="Save Changes"  onclick="wrmpvalidateForm();"  />
 
</form>
</div>
  
<?php
}
 
 
function wrmp_sendEmail($to,$subject,$message,$from) {
	  
$headers = "From: ".$from. "\r\n" ."Reply-To: ".$from. "\r\n" .'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);
} 
?>