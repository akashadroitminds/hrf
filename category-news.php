<?php
/**
 * Template name: News Page
 */

get_header();

$args = array(  
		'post_type' => 'post',
		'post_status' => 'publish',
		'posts_per_page' => -1, 
		'order' => 'DESC', 
		'orderby' => 'date',
        'cat' => 6
	);
	$loop = new WP_Query( $args );
 ?>
<style>
    .section_title {
        padding-top: 13rem;
        padding-bottom: 5rem;
        background-color: #12203B !important;
    }

    .section_title h1 {
        font-weight: 700;
        font-size: 48px;
        color: #FFFFFF !important;
        line-height: 56px;
    }

    .news_row {
        display: flex;
        flex-wrap: wrap;
        margin: 0px -15px;
    }

    .news_row .news_col {
        max-width: 33.33%;
        flex: 33.33%;
        padding: 0px 15px;
        margin-bottom: 30px;
    }

    .news_row .news_col .news_content {
        border-radius: 12px 12px 12px 12px;
        overflow: hidden;
        border: 1px solid #e0e0e0;
        height: 100%;

    }

    .news_row .news_col .news_content .wf-block-media {
        height: 200px;
    }

    .news_row .news_col .news_content .linkwrapp {
        display: block;
        width: 100%;
        height: 100%;
    }

    .news_row .news_col .news_content .linkwrapp img {
        width: 100%;
        display: block;
        height: 100%;
        object-fit: cover;
    }

    .news_row .news_col .news_content .news_details {
        padding: 25px 15px;
        text-align: center;
    }

    .news_row .news_col .news_content .news_details h2 {
        font-size: 22px;
        line-height: 32px;
        font-weight: bold;
        color: #892094;
    }

    .news_row .news_col .news_content .news_details .news_publish_date {
        text-transform: uppercase;
    }

    .news_row .news_col .news_content .news_details .courtesy {
        display: block;
        color: #464646;
        /*        margin-top: 10px;*/
        opacity: 0.7;
        /*        margin-bottom: 15px;*/
    }

    .news_row .news_col .news_content .news_details .download {
        margin-top: 15px;
    }

    .et_pb_section .et_pb_row {
        width: 85%;
        max-width: 1200px;
    }

    @media only screen and (min-width: 981px) {
        .et_pb_section .et_pb_row {
            width: 85%;
            max-width: 1200px;
        }
    }

    @media only screen and (max-width: 991px) {
        .news_row .news_col {
            max-width: 50%;
            flex: 50%;
        }
    }

    @media only screen and (max-width: 767px) {
        .section_title {
            padding-top: 8rem;
            padding-bottom: 2rem;
        }

        .news_row .news_col {
            max-width: 100%;
            flex: 100%;
        }
    }

</style>
<div id="main-content">
    <div class="section_title et_pb_section">
        <div class="et_pb_row">
            <div class="et_pb_column">
                <h1>HRF In the Media</h1>
            </div>
        </div>
    </div>
    <div class="et_pb_section">
        <div class="et_pb_row">
            <div class="news_row">


                <?php while ( $loop->have_posts() ) : $loop->the_post();
	
       if(has_post_thumbnail()){
		   $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ),'full',false,'' );
	    }
        else{
            $thumb=array('');
        }
        $date=get_the_date( 'F jS, Y');
		 if (has_post_format('audio') ) {
             $listenLink = get_field('add_listen_button_link');
             if($listenLink){
                 $readmoreLink=$listenLink;
             }
             else{
                 $readmoreLink=get_the_permalink();
             }
              $imageLink=$thumb[0];
              $readMoreTitle='Listen';
         }
         else if (has_post_format('video') ) {         
              $watchLink = get_field('add_video_link');
              preg_match('/(?:\/|=)(.{11})(?:$|&|\?)/', $watchLink, $matches);
              $youtubeId=  $matches[1];
              $one = "https://img.youtube.com/vi/";
              $two = "/hqdefault.jpg";
              $youtubeimg = $one.$youtubeId.$two;
             
               if($watchLink){
                 $readmoreLink=$watchLink;
                 $imageLink=$youtubeimg;
				   if($youtubeId){
					    $imageLink=$youtubeimg;
					   if($youtubeId=='wnE5QDFmJjE'){
					    	$imageLink=$thumb[0];
				  		 }
				   }
				   else{
					    $imageLink=$thumb[0];
				   }
             }
             else{
                 $readmoreLink=get_the_permalink();
                 $imageLink=$thumb[0];
             }
             $readMoreTitle='Watch Now';
         }
        else{
           $pdfLink = get_field('add_news_pdf_link');
            if($pdfLink){
                $readmoreLink=$pdfLink;
            }
            else{
                $readmoreLink= get_the_permalink();
            }
           
           $imageLink=$thumb[0];
            $readMoreTitle='Read More';
        }
    
    $courtesyText = get_field('add_news_courtesy');
    
    echo '  <div class="news_col"><div class="news_content">
                    <div class="wf-block-media">
                        <a href="'.$readmoreLink.'" target="_blank" class="linkwrapp">
                            <img src="'.$imageLink.'"/>
                        </a>
                    </div>
                    <div class="news_details">
                    <a href="'.get_the_permalink().'"><h2>'.get_the_title().'</h2></a>
                    <p class="news_publish_date">'.$date.'</p>
                    <p>'.strip_tags(get_the_excerpt()).'</p>
                    <span class="courtesy">'.$courtesyText.'</span>
                    <div class="download"><a href="'.$readmoreLink.'" class="readmore">'.$readMoreTitle.'</a></div>
                    </div>
               </div> </div>';
       ?>
                <?php endwhile; ?>

            </div>
        </div>
    </div>
</div>

<?php

get_footer();
?>
