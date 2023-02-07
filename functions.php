<?php
error_reporting(0);
define('RAISELY_TOKEN','raisely-sk-ce02845d0cebf32f13ef14af9747a2e7');
function my_theme_enqueue_styles() { 
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_script('script', get_stylesheet_directory_uri() . '/script.js', array('jquery'), '20120206', true);
//     wp_enqueue_script('nav-script', get_stylesheet_directory_uri() . '/nav-script.js', array('jquery'), '20120208', true);
	wp_enqueue_style( 'font-style', 'http://fonts.cdnfonts.com/css/avenir');
	
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

	
// function mywptheme_child_deregister_styles()
// {

// 	if(is_page((array('home-new'))))
// 	{
// 		wp_dequeue_script('nav-script', get_stylesheet_directory_uri() . '/nav-script.js'); 
// // 		wp_enqueue_script('new-home-nav', get_stylesheet_directory_uri() . '/new-home-nav.js', array('jquery'), '20120209', true);
// 	}



// }
// add_action( 'wp_enqueue_scripts', 'mywptheme_child_deregister_styles', 9999 );

// Hide project post type
add_filter( 'et_project_posttype_args', 'mytheme_et_project_posttype_args', 10, 1 );
function mytheme_et_project_posttype_args( $args ) {
	return array_merge( $args, array(
		'public'              => false,
		'exclude_from_search' => false,
		'publicly_queryable'  => false,
		'show_in_nav_menus'   => false,
		'show_ui'             => false
	));
}
@ini_set( 'upload_max_size' , '64M' );
@ini_set( 'post_max_size', '64M');
@ini_set( 'max_execution_time', '300' );

// Allow SVG
add_filter( 'wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {
 
	global $wp_version;
	if ( $wp_version !== '4.7.1' ) {
	   return $data;
	}
   
	$filetype = wp_check_filetype( $filename, $mimes );
   
	return [
		'ext'             => $filetype['ext'],
		'type'            => $filetype['type'],
		'proper_filename' => $data['proper_filename']
	];
   
  }, 10, 4 );
   
  function cc_mime_types( $mimes ){
	$mimes['svg'] = 'image/svg';
	return $mimes;
  }
  add_filter( 'upload_mimes', 'cc_mime_types' );
   
  function fix_svg() {
	echo '<style type="text/css">
		  .attachment-266x266, .thumbnail img {
			   width: 100% !important;
			   height: auto !important;
		  }
		  </style>';
  }
  add_action( 'admin_head', 'fix_svg' );



add_shortcode( 'blog', 'blog' );
function blog() {
	$args = array(  
		'post_type' => 'post',
		'post_status' => 'publish',
		'posts_per_page' => 1, 
		'order' => 'DESC', 
		'orderby' => 'date',
		'cat' => 5
	);
	$loop = new WP_Query( $args );
	$html = '<div class="blog-container">';
	
		
	while ( $loop->have_posts() ) : $loop->the_post();

	$categories = get_the_category();
	
	


	if(has_post_thumbnail()){
		$thumb ='<a href="'.get_the_permalink().'">'.get_the_post_thumbnail().'</a>';
	}
	else{
		$thumb ='<a href="'.get_the_permalink().'"><img src="" /></a>';
	}
	
	 
	  if ( has_post_format('audio') ) {
		$readMore = "Listen";
	  }
	  elseif(has_post_format('video')){
		$readMore = "Watch Now";
	  }
	  else{
		$readMore = "Read More";
	  }
	
		
	
	if($categories[0]->term_id == 5){
		$bckColor = "#892094";
	}
	// else{
	// 	$bckColor = "#6BCABA";
	// }
	  
		$html .='<div class="hrf-blog">
					<div class="blog-img-container">
						'.$thumb.'
					</div>
					<div class="blog-details">
						<p><a class="category" style="background:'.$bckColor.'">
						'.$categories[0]->cat_name.'
						</a></p>
						<h2><a href="'.get_the_permalink().'">'.get_the_title().'</a></h2>';
						if (has_excerpt()) {
		
							$html .= '<p>'.strip_tags(get_the_excerpt()).'</p>';
							
						  } 
	  					$html .='<a href="'.get_the_permalink().'" class="readmore" />'.$readMore.'</a>
					</div>
				</div>';
	
	 

	
	endwhile;
	
	$html .='</div>';
	wp_reset_postdata();
	return $html;
}
add_shortcode( 'news', 'news' );
function news(){

	$args = array(  
		'post_type' => 'post',
		'post_status' => 'publish',
		'posts_per_page' => 3, 
		'order' => 'DESC', 
		'orderby' => 'date',
		'cat' => 6
	);
	$loop = new WP_Query( $args );
	
	$html = '<div class="news-container">';

	while ( $loop->have_posts() ) : $loop->the_post();

	 $categories = get_the_category();
	


	if(has_post_thumbnail()){
		$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ),'full',false,'' );
	}
	else{
		$thumb=array('');
	}
	
	 
	  if ( has_post_format('audio') ) {
		  $listenLink = get_field('add_listen_button_link');
		  $readMore = "Listen";
		  if($listenLink){
			   $thumbnail= '<a href="'.get_the_permalink().'"><div class="blog-img-container" style="background-image:url(' .$thumb[0].');    background-position: center center;background-size: cover;
					background-repeat: no-repeat;width:100%;height:250px;">
						
					</div></a>';
			  $readMoreLink = '<a href="'.$listenLink.'" target="_blank" class="readmore">'.$readMore.'</a>';
		  }else{
			   $thumbnail= '<div class="blog-img-container" style="background-image:url(' .$thumb[0].');    background-position: center center;background-size: cover;
					background-repeat: no-repeat;width:100%;height:250px;">
						
					</div>';
			  $readMoreLink = '<a href="'.get_the_permalink().'" class="readmore">'.$readMore.'</a>';
		  }
		
	  }
	  elseif(has_post_format('video')){
		  $watchLink = get_field('add_video_link');
		  $readMore = "Watch Now";
		  preg_match('/(?:\/|=)(.{11})(?:$|&|\?)/', $watchLink, $matches);
		  $youtubeId=  $matches[1];
		  $one = "https://img.youtube.com/vi/";
		  $two = "/hqdefault.jpg";
			$youtubeimg = $one.$youtubeId.$two;
		 
		  if($watchLink){
			   $thumbnail= '<a href="'.get_the_permalink().'"><div class="blog-img-container" style="background-image:url('.$youtubeimg.');    background-position: center center;background-size: cover;
					background-repeat: no-repeat;width:100%;height:250px;">
						
					</div></a>';
			   $readMoreLink = '<a href="'.$watchLink.'" target="_blank" class="readmore">'.$readMore.'</a>';
		  }else{
			  $thumbnail= '<div class="blog-img-container" style="background-image:url("' .$thumb[0].'");    background-position: center center;background-size: cover;
					background-repeat: no-repeat;width:100%;height:250px;">
						
					</div>';
		  $readMoreLink = '<a href="'.get_the_permalink().'" class="readmore">'.$readMore.'</a>';
		  }
	  }
	  else{
		$readMore = "Read More";
		   $thumbnail= '<a href="'.get_the_permalink().'"><div class="blog-img-container" style="background-image:url(' .$thumb[0].');    background-position: center center;background-size: cover;
					background-repeat: no-repeat;width:100%;height:250px;">
						
					</div></a>';
		  $readMoreLink = '<a href="'.get_the_permalink().'" class="readmore">'.$readMore.'</a>';
	  }
	
		$date=get_the_date( 'F jS, Y');
		$postDate = '<p class="date">'.$date.'</p>';
	
	if($categories[0]->term_id == 6){
		$bckColor = "#6BCABA";
	}
	  
		$html .='<div class="hrf-news">
					'.$thumbnail.'
					<div class="blog-details">
						<p><a class="category" style="background:'.$bckColor.'">
						'.$categories[0]->cat_name.'
						</a></p>
						<a href="'.get_the_permalink().'"><h2>'.get_the_title().'</h2></a>
						'.$postDate.'';
						if (has_excerpt()) {
		
							$html .= '<p class="news-container-excerpt">'.strip_tags(get_the_excerpt()).'</p>';
							
						  } 
	  					
					$html .='</div>';
					$html .=''.$readMoreLink.'</div>';
	
	 

	
	endwhile;
	$html .='</div>';
	wp_reset_postdata();
	return $html;
}

add_action('init', 'register_cpt_our_partners');

function register_cpt_our_partners() {
  register_post_type('our-partners', array(
    'labels' => array(
      'name' => _x('Our Partners', 'our-partners'),
      'singular_name' => _x('Our Partners', 'our-partners'),
      'add_new' => _x('Add New', 'our-partners'),
      'add_new_item' => _x('Add New Our Partners', 'our-partners'),
      'edit_item' => _x('Edit Our Partners', 'our-partners'),
      'new_item' => _x('New Our Partners', 'our-partners'),
      'view_item' => _x('View Our Partners', 'our-partners'),
      'search_items' => _x('Search Our Partners', 'our-partners'),
      'not_found' => _x('No Our Partners found', 'our-partners'),
      'not_found_in_trash' => _x('No Our Partners found in Trash', 'our-partners'),
      'parent_item_colon' => _x('Parent Our Partners:', 'our-partners'),
      'menu_name' => _x('Our Partners', 'our-partners')
    ),
    'hierarchical' => true,
    'description' => '',
    'supports' => array('title', 'editor', 'thumbnail', 'trackbacks'),
    'taxonomies' => array(),
    'public' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'menu_icon' => 'dashicons-superhero',
    'menu_position' => '5',
    'show_in_nav_menus' => true,
    'publicly_queryable' => true,
    'exclude_from_search' => false,
    'has_archive' => false,
    'query_var' => true,
    'can_export' => true,
    'capability_type' => 'post',
    'rewrite' => true
  ));
}

add_shortcode( 'our-partners', 'ourPartners' );
function ourPartners(){

	$args = array(  
		'post_type' => 'our-partners',
		'post_status' => 'publish',
		'posts_per_page' => -1, 
		'order' => 'ASC', 
		'orderby' => 'date'
	);
	$loop = new WP_Query( $args );

	$html = '<div class="partner-logo-div">';

	while ( $loop->have_posts() ) : $loop->the_post();

	
		if(has_post_thumbnail()){
			$thumb =wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ),'full');
		}
		else{
			$thumb =array('https://placehold.jp/150x150.png');
		}
		$url = get_field('our_partners_url');

		$html .='<a href="'.$url.'" target="_blank">
			<div class="container">
				<img src="'.$thumb[0].'" alt="Avatar" class="image">
				<div class="overlay">
					<div class="text">
						<p class="icon-heading">'.get_the_title().'</p>
						<p class="icon-text">'.get_the_content().'</p>
					</div>
				</div>
			</div>
		</a>';

	endwhile;
	
	$html .='</div>';
	wp_reset_postdata();
	return $html;

}
// Shortcode to display last updated date of particular page or post
add_shortcode('last-updated','last_updated');
function last_updated(){
	$date= date("d F, Y", strtotime(get_the_modified_date()));
	$html.='
	<p>Last updated:'.$date.' </p>
	';
	return $html;
}

function dd_add_datepicker_to_cf7() {
    $load_scripts = false;
    if ( is_singular() ) {
        $post = get_post();
        if ( has_shortcode( $post->post_content, 'contact-form-7' ) ) {
            $load_scripts = true;
        }
    }
    if ( $load_scripts ) {
        wp_enqueue_script( 'jquery-ui-datepicker' ); // included with WordPress
        wp_enqueue_style( 'ui-datepicker-style', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css' );
        // Choose a different theme is you want https://cdnjs.com/libraries/jqueryui
        wp_enqueue_style( 'ui-datepicker-theme', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/theme.min.css' );
        add_action( 'wp_print_footer_scripts', function () {
            // add different options to datepicker if you want https://api.jqueryui.com/datepicker/
            echo '<script type="text/javascript">jQuery(function($){$(".use-datepicker").datepicker()});</script>';
        } );
    }

}

add_action( 'wp_enqueue_scripts', 'dd_add_datepicker_to_cf7', 99 );

function hrf_create_my_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'raisely';
    $charset_collate = $wpdb->get_charset_collate();
 
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
 
    if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {
       $sql = "CREATE TABLE $table_name (
                id int(11) NOT NULL AUTO_INCREMENT,
                form_id int(16) NOT NULL,
                uuid varchar(256) NOT NULL,
                email varchar(256) NOT NULL,
                PRIMARY KEY (id)
       ) $charset_collate;";
 
       dbDelta( $sql );
    }
 }
 
add_action( init, 'hrf_create_my_table' );

// function delete_custom_table() {
//     global $wpdb;
//     $table_name = $wpdb->prefix . 'raisely';
//     $sql = "DROP TABLE IF EXISTS $table_name";
//     $wpdb->query($sql);
// }
// add_action( init, 'delete_custom_table' );

function save_cf7_data($WPCF7_ContactForm) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'raisely';
    $wpcf7 = WPCF7_ContactForm::get_current();
    $form_id = $wpcf7->id;
    $submission = WPCF7_Submission::get_instance();
    $headers = array( 'Content-Type' => 'application/json',
                      'Accept' => 'application/json',
                      'Authorization' => 'Bearer ' . RAISELY_TOKEN );
    $data = $submission->get_posted_data();
    $emailCheck = $data['email'];
    $results = $wpdb->get_results( "SELECT id,uuid,email FROM $table_name WHERE email = '$emailCheck'", ARRAY_A );
    $localuuid = $results[0]['uuid'];
	$localemail = $results[0]['email'];
    $localid = $results[0]['id'];
    // Newsletter
    if ( $submission && $form_id == 328 ) {
        $arr['data'] = array(
            'firstName' => $data['first-name'],  
            'lastName' => $data['last-name'],
            'email' => $data['email'],
            'allowExists'=> true    
            ); 
        $formData = json_encode($arr);
        $args = array(
            'method' => 'POST',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => $headers,
            'body' =>  $formData , 
            'cookies' => array()
        );
        $response = wp_remote_post( 'https://api.raisely.com/v3/users', $args );
        $responseData = json_decode(wp_remote_retrieve_body($response), true);
        $uuidPeople = $responseData['data']['uuid'];
        if($uuidPeople){
           
            if(empty($localemail)){
                $data = array(
                    'form_id' => 328,
                    'uuid' => $uuidPeople,
                    'email' => $responseData['data']['email']
                    );
                $wpdb->insert( $table_name, $data );
           }else{
            $data = array(
                'uuid' => $uuidPeople,
                'email' => $responseData['data']['email'],
            );
            $where = array(
                'id' => $localid,
            );
            $wpdb->update( $table_name, $data, $where );
           }
        }
        if ( is_wp_error( $response ) ) {
            $error_message = $response->get_error_message();
            echo "Something went wrong: $error_message";
        } else {
            if ($responseData['errors'][0]['status'] == '409') {
                $args = array(
                    'method' => 'PATCH',
                    'timeout' => 45,
                    'redirection' => 5,
                    'httpversion' => '1.0',
                    'blocking' => true,
                    'headers' => $headers,
                    'body' =>  $formData , 
                    'cookies' => array()
                );
                $response = wp_remote_post( 'https://api.raisely.com/v3/users/'.$localuuid.'', $args );
                add_filter('wpcf7_ajax_json_echo', 'my_custom_response_message',10,2);
                function my_custom_response_message($response, $result) {
                    $response["message"] = "You are subscribed";            
                    return $response;
                }
            }else{
                add_filter('wpcf7_ajax_json_echo', 'my_custom_response_message',10,2);
                function my_custom_response_message($response, $result) {
                    $response["message"] = "You are subscribed";    
                    return $response;
                }
            }
        }
		
        if($uuidPeople){
            $uuidNewsletter = 'e4cb04a0-829d-11ed-b187-5310bc2784d3';
            $arrTag['data'][] = array(
                'uuid' => $uuidPeople,      
                ); 
            $formTagData = json_encode($arrTag);
            $tagargs = array(
                    'method' => 'POST',
                    'timeout' => 45,
                    'redirection' => 5,
                    'httpversion' => '1.0',
                    'blocking' => true,
                    'headers' => $headers,
                    'body' =>  $formTagData , 
                    'cookies' => array()
                );
            $tagResponse = wp_remote_post( 'https://api.raisely.com/v3/tags/'.$uuidNewsletter.'/records', $tagargs );
            $responseTagData = json_decode(wp_remote_retrieve_body($tagResponse), true);
        }
	
    }
    // Contact
    if ( $submission && $form_id == 1228 ) {

        $data = $submission->get_posted_data();
        $acc = $data['acceptance-101'];
        $enquiry = $data['c-enquiry'];
        $arr['data'] = array(
            'public' => array('message'=>$data['c-message'],'companyName' => $data['c-company-name'],'enquiryInRelationTo'=> $enquiry[0],'othersPleaseSpecify'=>$data['c-others']),
            'firstName' => $data['c-name'],  
            'lastName' => $data['c-lastname'],  
            'email' => $data['email'],
            'allowExists'=> true    
            ); 
        $formData = json_encode($arr);
        $args = array(
            'method' => 'POST',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => $headers,
            'body' =>  $formData , 
            'cookies' => array()
        );
        $response = wp_remote_post( 'https://api.raisely.com/v3/users', $args );
        $responseData = json_decode(wp_remote_retrieve_body($response), true);
        $uuidPeople = $responseData['data']['uuid'];
        if($uuidPeople){
             if(empty($localemail)){
                $data = array(
                    'form_id' => 1228,
                    'uuid' => $uuidPeople,
                    'email' => $responseData['data']['email']
                    );
                $wpdb->insert( $table_name, $data );
           }else{
            $data = array(
                'uuid' => $uuidPeople,
                'email' => $responseData['data']['email'],
            );
            $where = array(
                'id' => $localid,
            );
            $wpdb->update( $table_name, $data, $where );
           }
        }
        if ( is_wp_error( $response ) ) {
            $error_message = $response->get_error_message();
            echo "Something went wrong: $error_message";
        } else {
            $responseData = json_decode(wp_remote_retrieve_body($response), true);
            if ($responseData['errors'][0]['status'] == '409') {
                $args = array(
                    'method' => 'PATCH',
                    'timeout' => 45,
                    'redirection' => 5,
                    'httpversion' => '1.0',
                    'blocking' => true,
                    'headers' => $headers,
                    'body' =>  $formData , 
                    'cookies' => array()
                );
                $response = wp_remote_post( 'https://api.raisely.com/v3/users/'.$localuuid.'', $args );
                if($localuuid){
                    $uuidGeneral = 'ebf742c0-829d-11ed-a02b-e5500cb95cf2';
                    $arrTag['data'][] = array(
                        'uuid' => $localuuid,      
                        ); 
                    $formTagData = json_encode($arrTag);
                    $tagargs = array(
                            'method' => 'POST',
                            'timeout' => 45,
                            'redirection' => 5,
                            'httpversion' => '1.0',
                            'blocking' => true,
                            'headers' => $headers,
                            'body' =>  $formTagData , 
                            'cookies' => array()
                        );
                    $tagResponse = wp_remote_post( 'https://api.raisely.com/v3/tags/'.$uuidGeneral.'/records', $tagargs );
                    $responseTagData = json_decode(wp_remote_retrieve_body($tagResponse), true);
                }
                if($acc == 1){
                    
                    if($localuuid){
                        $uuidNewsletter = 'e4cb04a0-829d-11ed-b187-5310bc2784d3';
                        $arrTag['data'][] = array(
                            'uuid' => $localuuid,      
                            ); 
                        $formTagData = json_encode($arrTag);
                        $tagargs = array(
                                'method' => 'POST',
                                'timeout' => 45,
                                'redirection' => 5,
                                'httpversion' => '1.0',
                                'blocking' => true,
                                'headers' => $headers,
                                'body' =>  $formTagData , 
                                'cookies' => array()
                            );
                        $tagResponse = wp_remote_post( 'https://api.raisely.com/v3/tags/'.$uuidNewsletter.'/records', $tagargs );
                        $responseTagData = json_decode(wp_remote_retrieve_body($tagResponse), true);
                    }
                }else{
                    
                        $uuidNewsletter = 'e4cb04a0-829d-11ed-b187-5310bc2784d3';
                       
                        $tagargs = array(
                                'method' => 'DELETE',
                                'timeout' => 45,
                                'redirection' => 5,
                                'httpversion' => '1.0',
                                'blocking' => true,
                                'headers' => $headers,
                                'cookies' => array()
                            );
                           
                        $tagResponse = wp_remote_post( 'https://api.raisely.com/v3/tags/'.$uuidNewsletter.'/records/'.$localuuid.'', $tagargs );
                        $responseTagData = json_decode(wp_remote_retrieve_body($tagResponse), true);
                    
                }
                add_filter('wpcf7_ajax_json_echo', 'my_custom_response_message',10,2);
                function my_custom_response_message($response, $result) {
                    $response["message"] = "You have successfully submitted";         
                    return $response;
                }
            }else{
                
                if($acc == 1){
                    
                    if($uuidPeople){
                        $uuidNewsletter = 'e4cb04a0-829d-11ed-b187-5310bc2784d3';
                        $arrTag['data'][] = array(
                            'uuid' => $uuidPeople,      
                            ); 
                        $formTagData = json_encode($arrTag);
                        $tagargs = array(
                                'method' => 'POST',
                                'timeout' => 45,
                                'redirection' => 5,
                                'httpversion' => '1.0',
                                'blocking' => true,
                                'headers' => $headers,
                                'body' =>  $formTagData , 
                                'cookies' => array()
                            );
                        $tagResponse = wp_remote_post( 'https://api.raisely.com/v3/tags/'.$uuidNewsletter.'/records', $tagargs );
                        $responseTagData = json_decode(wp_remote_retrieve_body($tagResponse), true);
                    }
                }
                if($uuidPeople){
                    $uuidGeneral = 'ebf742c0-829d-11ed-a02b-e5500cb95cf2';
                    $arrTag['data'][] = array(
                        'uuid' => $uuidPeople,      
                        ); 
                    $formTagData = json_encode($arrTag);
                    $tagargs = array(
                            'method' => 'POST',
                            'timeout' => 45,
                            'redirection' => 5,
                            'httpversion' => '1.0',
                            'blocking' => true,
                            'headers' => $headers,
                            'body' =>  $formTagData , 
                            'cookies' => array()
                        );
                    $tagResponse = wp_remote_post( 'https://api.raisely.com/v3/tags/'.$uuidGeneral.'/records', $tagargs );
                    $responseTagData = json_decode(wp_remote_retrieve_body($tagResponse), true);
                }
                add_filter('wpcf7_ajax_json_echo', 'my_custom_response_message',10,2);
                function my_custom_response_message($response, $result) {
                    $response["message"] = "You are submitted successfully";    
                    return $response;
                }
            }
        }
        
    }
    // School program form
    if ( $submission && $form_id == 472 ) {
        $data = $submission->get_posted_data();
        $programState = $data['program-state'];
        $arr['data'] = array(
            'public' => array('programMessage'=>$data['program-message'],'schoolName'=>$data['program-school-name'],'positionTitle' => $data['program-position-title']),
            'state' => $programState[0],
            'fullName' => $data['program-first-name'], 
            'phoneNumber'=>$data['program-contact-number'],
            'email' => $data['email'],
            'allowExists'=> true  
            ); 
        $formData = json_encode($arr);
        $args = array(
            'method' => 'POST',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => $headers,
            'body' =>  $formData , 
            'cookies' => array()
        );
        $response = wp_remote_post( 'https://api.raisely.com/v3/users', $args );
        $responseData = json_decode(wp_remote_retrieve_body($response), true);
        $uuidPeople = $responseData['data']['uuid'];
        if($uuidPeople){
             if(empty($localemail)){
                $data = array(
                    'form_id' => 472,
                    'uuid' => $uuidPeople,
                    'email' => $responseData['data']['email']
                    );
                $wpdb->insert( $table_name, $data );
           }else{
            $data = array(
                'uuid' => $uuidPeople,
                'email' => $responseData['data']['email'],
            );
            $where = array(
                'id' => $localid,
            );
            $wpdb->update( $table_name, $data, $where );
           }
        }
        if ( is_wp_error( $response ) ) {
            $error_message = $response->get_error_message();
            echo "Something went wrong: $error_message";
        } else {
            $responseData = json_decode(wp_remote_retrieve_body($response), true);
            if ($responseData['errors'][0]['status'] == '409') {
                $args = array(
                    'method' => 'PATCH',
                    'timeout' => 45,
                    'redirection' => 5,
                    'httpversion' => '1.0',
                    'blocking' => true,
                    'headers' => $headers,
                    'body' =>  $formData , 
                    'cookies' => array()
                );
                $response = wp_remote_post( 'https://api.raisely.com/v3/users/'.$localuuid.'', $args );
                if($localuuid){
                    $uuidGeneral = 'c472fd20-829d-11ed-a02b-e5500cb95cf2';
                    $arrTag['data'][] = array(
                        'uuid' => $localuuid,      
                        ); 
                    $formTagData = json_encode($arrTag);
                    $tagargs = array(
                            'method' => 'POST',
                            'timeout' => 45,
                            'redirection' => 5,
                            'httpversion' => '1.0',
                            'blocking' => true,
                            'headers' => $headers,
                            'body' =>  $formTagData , 
                            'cookies' => array()
                        );
                    $tagResponse = wp_remote_post( 'https://api.raisely.com/v3/tags/'.$uuidGeneral.'/records', $tagargs );
                    $responseTagData = json_decode(wp_remote_retrieve_body($tagResponse), true);
                }
                add_filter('wpcf7_ajax_json_echo', 'my_custom_response_message',10,2);
                function my_custom_response_message($response, $result) {
                    $response["message"] = "You have successfully submitted";         
                    return $response;
                }
            }else{
                if($uuidPeople){
                    $uuidGeneral = 'c472fd20-829d-11ed-a02b-e5500cb95cf2';
                    $arrTag['data'][] = array(
                        'uuid' => $uuidPeople,      
                        ); 
                    $formTagData = json_encode($arrTag);
                    $tagargs = array(
                            'method' => 'POST',
                            'timeout' => 45,
                            'redirection' => 5,
                            'httpversion' => '1.0',
                            'blocking' => true,
                            'headers' => $headers,
                            'body' =>  $formTagData , 
                            'cookies' => array()
                        );
                    $tagResponse = wp_remote_post( 'https://api.raisely.com/v3/tags/'.$uuidGeneral.'/records', $tagargs );
                    $responseTagData = json_decode(wp_remote_retrieve_body($tagResponse), true);
                }
                add_filter('wpcf7_ajax_json_echo', 'my_custom_response_message',10,2);
                function my_custom_response_message($response, $result) {
                    $response["message"] = "You have successfully submitted";    
                    return $response;
                }
            }
           
        }
        
    }
    // Fundraising form
    if ( $submission && $form_id == 4026 ) {
        $data = $submission->get_posted_data();
		$enquiry = $data['c-enquiry'];
		$programState = $data['supporter-state'];
        $arr['data'] = array(
            'public' => array('nameOfOrganization'=>$data['name-of-organisation'],'enquiryInRelationTo'=> $enquiry[0],'othersPleaseSpecify'=>$data['c-others']),
            'firstName' => $data['name-of-organisation'],
			'lastName' => $data['supporter-representative'],
            'phoneNumber'=>$data['representative-telephone'],
            'email' => $data['email'],
			'state' => $programState[0],
			'address1' => $data['supporter-address'],
			'suburb' => $data['supporter-city'],
			'postcode' => $data['supporter-postcode'],
            'allowExists'=> true  
            ); 
        $formData = json_encode($arr);
        $args = array(
            'method' => 'POST',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => $headers,
            'body' =>  $formData , 
            'cookies' => array()
        );
        $response = wp_remote_post( 'https://api.raisely.com/v3/users', $args );
        $responseData = json_decode(wp_remote_retrieve_body($response), true);
        $uuidPeople = $responseData['data']['uuid'];
        if($uuidPeople){
             if(empty($localemail)){
                $data = array(
                    'form_id' => 4026,
                    'uuid' => $uuidPeople,
                    'email' => $responseData['data']['email']
                    );
                $wpdb->insert( $table_name, $data );
           }else{
            $data = array(
                'uuid' => $uuidPeople,
                'email' => $responseData['data']['email'],
            );
            $where = array(
                'id' => $localid,
            );
            $wpdb->update( $table_name, $data, $where );
           }
        }
        
        if ( is_wp_error( $response ) ) {
            $error_message = $response->get_error_message();
            echo "Something went wrong: $error_message";
        } else {
            $responseData = json_decode(wp_remote_retrieve_body($response), true);
            if ($responseData['errors'][0]['status'] == '409') {
                $args = array(
                    'method' => 'PATCH',
                    'timeout' => 45,
                    'redirection' => 5,
                    'httpversion' => '1.0',
                    'blocking' => true,
                    'headers' => $headers,
                    'body' =>  $formData , 
                    'cookies' => array()
                );
                $response = wp_remote_post( 'https://api.raisely.com/v3/users/'.$localuuid.'', $args );
                if($localuuid){
                    $uuidGeneral = '69762040-9568-11ed-9193-5f2ace94f41e';
                    $arrTag['data'][] = array(
                        'uuid' => $localuuid,      
                        ); 
                    $formTagData = json_encode($arrTag);
                    $tagargs = array(
                            'method' => 'POST',
                            'timeout' => 45,
                            'redirection' => 5,
                            'httpversion' => '1.0',
                            'blocking' => true,
                            'headers' => $headers,
                            'body' =>  $formTagData , 
                            'cookies' => array()
                        );
                    $tagResponse = wp_remote_post( 'https://api.raisely.com/v3/tags/'.$uuidGeneral.'/records', $tagargs );
                    $responseTagData = json_decode(wp_remote_retrieve_body($tagResponse), true);
                }
                add_filter('wpcf7_ajax_json_echo', 'my_custom_response_message',10,2);
                function my_custom_response_message($response, $result) {
                    $response["message"] = "You have successfully submitted";         
                    return $response;
                }
            }else{
                if($uuidPeople){
                    $uuidGeneral = '69762040-9568-11ed-9193-5f2ace94f41e';
                    $arrTag['data'][] = array(
                        'uuid' => $uuidPeople,      
                        ); 
                    $formTagData = json_encode($arrTag);
                    $tagargs = array(
                            'method' => 'POST',
                            'timeout' => 45,
                            'redirection' => 5,
                            'httpversion' => '1.0',
                            'blocking' => true,
                            'headers' => $headers,
                            'body' =>  $formTagData , 
                            'cookies' => array()
                        );
                    $tagResponse = wp_remote_post( 'https://api.raisely.com/v3/tags/'.$uuidGeneral.'/records', $tagargs );
                    $responseTagData = json_decode(wp_remote_retrieve_body($tagResponse), true);
                }
                add_filter('wpcf7_ajax_json_echo', 'my_custom_response_message',10,2);
                function my_custom_response_message($response, $result) {
                    $response["message"] = "You have successfully submitted";    
                    return $response;
                }
            }
           
        }
        
    }
}
add_action('wpcf7_before_send_mail', 'save_cf7_data', 10, 1);

// popup trigger on offline fundraiser lead page

function enqueue_custom_scripts_styles() {
    if ( is_page( 4022 ) ) {
        wp_enqueue_script( 'my-custom-script', 'https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js', '1.0.0', true );
        wp_enqueue_style( 'my-custom-style', 'https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css', array(), '1.0.0' );
    }
}
add_action( 'wp_enqueue_scripts', 'enqueue_custom_scripts_styles' );


