<div class="featured-auctions">
    <div class="container">
        <div class="title_block">
            <h2>Featured Auctions</h2>
        </div>
        <div class="featured-slider-sec">
                <div class="featured-slider">
                    <?php 
                  $border = get_option('auction_ui_border', '2px solid #5d86b1');
                  $border_radius = get_option('auction_ui_border_radius', '10px');
                  $bg_color = get_option('auction_ui_bg_color', '#e9ecf1');
                  $btn_color = get_option('auction_ui_btn_color', '#ec5f2a');
                  $btn_hover_color = get_option('auction_ui_btn_hover_color', '#ec5027');
                  $auction_ui_title = get_option('auction_ui_title', '#000');
                  $auction_ui_title_street = get_option('auction_ui_title_street', '#5d6065');
                  $auction_ui_button_width = get_option('auction_ui_button_width', '100%');
                  $auction_ui_bg_opacity = get_option('auction_ui_bg_opacity', '100%');
                    while ($query->have_posts()) {  $query->the_post();
                        $post_id = get_the_ID();

                        $auction_name = get_the_title();
                        $stored_data_unseriale = get_post_meta($post_id, '_auction_list_data', true);
                        $_auction_list_id = get_post_meta($post_id, 'auction_list_id', true);
                        $stored_data = maybe_unserialize($stored_data_unseriale);
                      
                        $_auction_list_option = get_post_meta($post_id, '_auction_list_option', true);
                        $_auction_list_data_total_count = get_post_meta($post_id, '_auction_list_data_total_count', true);
                        $auction_title_sub_text = get_post_meta($post_id, 'auction_title_sub_text', true);
                        $end_date_text_text = get_post_meta($post_id, 'end_date_text_text', true);
                        $time_remain = getTimeRemaining($end_date_text_text);                       

                        $date = DateTime::createFromFormat('l, m/d/y @ h:i a T', $end_date_text_text);
                        if ($date) {
                            $dayName = strtoupper($date->format('D'));
                            $dateNumber = $date->format('d');
                            $timeWithTimezone = substr($end_date_text_text, strpos($end_date_text_text, '@') + 2);
                        } 
                        
                        $date_part = explode('@', $end_date_text_text)[0];
                        $date_part = trim($date_part);
                        $date_obj = DateTime::createFromFormat('l, m/d/y', $date_part);
                        $formatted_date = $date_obj->format('l, n/j');
                        $street_address_text = get_post_meta($post_id, 'street_address_text', true);
                        $_auction_city = get_post_meta($post_id, '_auction_city', true);
                        $url_text = get_post_meta($post_id, 'url_text', true);
                        $_auction_state = get_post_meta($post_id, '_auction_state', true);
                        $city_state = esc_html($_auction_city . ' ' . $_auction_state);

                        
                        if (strtotime($formatted_date) > strtotime($current_time)) {
                            $formatted_date = '<span class="green">' . $formatted_date . '</span>';
                        } else {
                            $formatted_date = '<span class="red">Results</span>';
                        }
                            
                            if (!empty($stored_data) && is_array($stored_data) && count($stored_data) > 0) {
                                $random_images = [];
                            
                                $auction_title = get_the_title();
                                $lot_count = count($stored_data);

                                foreach ($stored_data as $key => $auction_data) {
                                    $thumb_url = $auction_data['thumb_url'];
                                    $random_images[] = esc_url($thumb_url);
                                }
                            }
                          
                            
                          
                            if(!empty($_auction_city)){   
                                
                            if (!empty($random_images)) {   
                                    ?>
                                    <div class="featured-slid-inner"  style="border: <?php echo $border; ?> !important;border-radius: <?php echo $border_radius; ?> !important;background-color: rgba(<?php echo hexToRgb($bg_color); ?>, <?php echo esc_attr($auction_ui_bg_opacity/100); ?>) !important;">
                                        <div class="featured-slid-box">
                                            <a target="_blank" href="https://localauctions.com/auction_details/<?php echo esc_attr($_auction_list_id); ?>" class="featured-items">
                                                <?php 
                                                if (!empty($random_images)) {    
                                                    $random_images = array_slice($random_images, 0,4);                                           
                                                    foreach ($random_images as $image) {
                                                        echo '<img class="test-class" src="' . esc_url($image) . '" alt="">';
                                                    }
                                                }
                                                ?>
                                            </a>
                                            <div class="featured-content-main">
                                                <div class="featured-content">
                                                    <div class="title-with-calender">
                                                        <div class="auction-title">
                                                            <h5 style="color:<?php echo $auction_ui_title; ?>"><?php echo $city_state . ' - ' . $formatted_date; ?></h5>
                                                            <?php if (!empty($street_address_text)) { ?>
                                                                <span style="color: <?php echo $auction_ui_title_street; ?>;"><?php echo esc_html($street_address_text); ?></span>
                                                            <?php } ?>
                                                            <div class="time-remain" style="font-size:14px;">
                                                            <?php  
                                                            echo '<strong>Time Remaining: </strong>';
                                                            if (!empty($time_remain)) {
                                                                echo '<span class="countdown-timer" data-time="'.$time_remain.'">'.$time_remain.'</span>';
                                                            }
                                                            ?>
                                                        </div>
                                                        </div>

                                                        
                                                        
                                                        <div class="auction-calendar ">
                                                            <div class="calenda-head">
                                                                <svg class="MuiSvgIcon-root MuiSvgIcon-fontSizeMedium css-vubbuv" focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="TodayIcon"><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"></path></svg>
                                                                <span>END</span>
                                                            </div>
                                                            <div class="date-box">
                                                                <span class="month"><?php echo $dayName; ?></span>
                                                                <span class="date"><?php echo $dateNumber; ?></span>
                                                            </div>
                                                            <div class="time">
                                                                <span style="text-transform: lowercase;"><?php echo $timeWithTimezone; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- <a href="javascript:void(0);">Auction Yard</a> -->
                                                    <?php if (!empty($auction_name)) { ?>
                                                        <h2 style="color:<?php echo $auction_ui_title; ?>"><?php echo esc_html($auction_name); ?></h2>
                                                    <?php } ?>
                                                    <span class="title-stub" style="color:<?php echo $auction_ui_title_street; ?>;"><?php echo esc_html($auction_title_sub_text); ?></span>
                                                    
                                                </div>
                                                <div class="featured-bottom-link">
                                                    <!-- <a target="_blank" href="https://localauctions.com/auction_details/<?php echo esc_attr($_auction_list_id); ?>" class="featured-logo">
                                                        <?php if (has_post_thumbnail(get_the_ID())): ?>
                                                            <img src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID())); ?>" alt="">
                                                        <?php endif; ?>
                                                    </a> -->
                                                    <!-- <a target="_blank" href="https://localauctions.com/auction_details/<?php echo esc_attr($_auction_list_id); ?>" class="featured-details">Details</a> -->
                                                    <a target="_blank" href="<?php echo esc_attr($url_text); ?>" class="featured-view" style="background-color:<?php echo $btn_color; ?> !important;text-align:center;width:<?php echo $auction_ui_button_width; ?> !important;border-radius:10px !important;text-transform:uppercase !important;" onmouseover="this.style.backgroundColor='<?php echo $btn_hover_color; ?>'"onmouseout="this.style.backgroundColor='<?php echo $btn_color; ?>'">Bid Now</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php 
                            }
                        }
                    }
                    ?>
                </div>
                <div class="progress" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <span class="slider__label sr-only"></span>
                </div>
        </div>
    </div>
</div>
