add_action("manage_posts_extra_tablenav","admin_post_list_add_export_button");
function admin_post_list_add_export_button( $which ) {
    global $typenow;
  
    if ( 'used_machinery' === $typenow && 'top' === $which ) {
        ?>
        <input type="submit" name="export_used_machinery"  class="button button-primary" value="<?php _e('Export Used Machinery'); ?>" />
        <?php
    }
}



	
function export_used_machinery() {
    if(isset($_GET['export_used_machinery'])) {
        $args = array(
            'post_type' => 'used_machinery',
            'post_status' => 'publish',
			'posts_per_page' => -1,
        );
 
       /* if ( isset($_GET['post']) ) {
            $args['post__in'] = $_GET['post'];
        } else {
            $args['posts_per_page'] = -1;
        }*/
  
        global $post;
        $arr_post = get_posts($args);
        if ($arr_post) {
  
            header('Content-type: text/csv');
            header('Content-Disposition: attachment; filename="used-machinery.csv"');
            header('Pragma: no-cache');
            header('Expires: 0');
  
            $file = fopen('php://output', 'w');
  
            fputcsv($file, array('Post ID', 'Post Title','Post Content','Featured','Listing Tag','Condition','Product Price (Now)','Brand','Model Number','Depot','Product Reference','Model','Horsepower','Mileage','Engine Hours','Year','Stock Number','Track Size','PTO','Guidance-ready','Hydraulic Pump','Product Details','Machinery Images','Machinery Popup Video URL','used_machinery_attachments','Popup Video URL','URL', 'Machine Category'));
			
            foreach ($arr_post as $post) {
                setup_postdata($post);
               $imagelist='';
               $imagelist_atta='';
               $vid_url_list='';
               $category_detail='';

                $mach_listing_tag = get_post_meta($post->ID, 'mach_listing_tag', true);
                $um_condition = get_post_meta($post->ID, 'um_condition', true);
                $um_pro_price = get_post_meta($post->ID, 'um_pro_price', true);
                $um_pro_price_was = get_post_meta($post->ID, 'um_pro_price_was', true);
                $um_brand = get_post_meta($post->ID, 'um_brand', true);
                $um_model_num = get_post_meta($post->ID, 'um_model_num', true);
                $um_depot = get_post_meta($post->ID, 'um_depot', true);
                $um_ref = get_post_meta($post->ID, 'um_ref', true);
                $um_video = get_post_meta($post->ID, 'um_video', true);
                $um_model = get_post_meta($post->ID, 'um_model', true);
                $um_horsepower = get_post_meta($post->ID, 'um_horsepower', true);
                $um_mileage = get_post_meta($post->ID, 'um_mileage', true);
                $um_engine_hours = get_post_meta($post->ID, 'um_engine_hours', true);
                $um_year = get_post_meta($post->ID, 'um_year', true);
                $um_stock_number = get_post_meta($post->ID, 'um_stock_number', true);
                $um_track_size = get_post_meta($post->ID, 'um_track_size', true);
                $um_pto = get_post_meta($post->ID, 'um_pto', true);
                $um_guidance_ready = get_post_meta($post->ID, 'um_guidance_ready', true);
                $um_hydraulic_pump = get_post_meta($post->ID, 'um_hydraulic_pump', true);
                $used_product_details = get_post_meta($post->ID, 'used_product_details', true);
                $machinery_images = get_post_meta($post->ID,'machinery_images',true);
				$category_list='';
				$imagelist='';
				$videolist='';
                if($machinery_images){
					               
					if ( count( $machinery_images ) > 0 ) 
					{
					
						foreach( $machinery_images as $row ) 
						{
							
							
							
							
							if ($row['mach_img']) 
							{
								if($imagelist)
								{
									$imagelist.='|'.$row['mach_img'];
								}else{
										$imagelist=$row['mach_img'];
									}
							}
							
							if($row['vid_url']) 
							{
								if($videolist)
								{
									$videolist.='|'.$row['vid_url'];
								}else{
										$videolist=$row['vid_url'];
									}
							}
								
								
								
								
							
							
						
								
							
						}
						
						
					}
					
				}
				
				  $attachments = new Attachments( 'used_machinery_attachments' ); 
				  $imagelist_atta='';
				  $vid_url_list='';
				  if( $attachments->exist() )
				  {
					while( $attachments->get() )
					{
						
							if($imagelist_atta)
							{
								$imagelist_atta.='|'.$attachments->url();
							}else{
								$imagelist_atta= $attachments->url();
							}
						     //$img_alt = $attachments->field( 'caption' );
						     
						     
						      $vid_url = $attachments->field( 'vid_url' );
						     if( $vid_url)
						     {
								  if($vid_url_list)
								  {
									 $vid_url_list.='|'.$vid_url;
									
									}else
									{
										 $vid_url_list=$vid_url;
									}
							}
					}  
				
					}
					
					
                 $category_detail=get_the_terms($post->ID, 'machine-category' );
                 $category_list='';
				foreach($category_detail as $k=>$v)
				{
					if($category_list)
					{
						$category_list .=$v->name.'|';
					}else
					{
						$category_list =$v->name;
					}
					
					
					
				}   
               
  
                fputcsv($file, array(get_the_ID(), get_the_title(),get_the_content(),'',$mach_listing_tag,$um_condition,$um_pro_price,$um_brand,$um_model_num,$um_depot,$um_ref,$um_model,$um_horsepower,$um_mileage,$um_engine_hours,$um_year,$um_stock_number,$um_track_size,$um_pto,$um_guidance_ready,$um_hydraulic_pump,$used_product_details,$imagelist,$videolist,$imagelist_atta,$vid_url_list,get_the_permalink(), $category_list));
            }
  
            exit();
        }
    }
}
 
add_action( 'admin_init', 'export_used_machinery' );
