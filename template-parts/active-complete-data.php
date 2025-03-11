<?php //if(is_user_logged_in()){
     $auction_name= get_the_title($post_id);
     ?>
<section class="auction-sec">
          <?php if(!empty($auction_name)){ ?>
               <div class="auction_name">
                    <h2 class="auction_main_name" style="text-align:center;">
                         <?php echo $auction_name; ?>
                    </h2>
               </div>
          <?php } ?>
          <div class="auction-tab">
               <ul>
                    <li class="active">Active</li>
                    <li >Complete</li>
               </ul>
          </div>
        <div class="auction-tabcontent">
          <div class="auction-tabcontent-list">
               <div class="auction-tabcontent-wraper">
                    <?php foreach($url_sorted_data_active as $active_auction_data){
                         $auction_id = $active_auction_data['id']; 
                         $auction_name = $active_auction_data['name'];
                         $auction_count = $active_auction_data['items_count'];
                         $auction_status= str_replace('_' , ' ' , $active_auction_data['status']);
                         $auction_end_time = $active_auction_data['formatted_scheduled_end_time'];
                         $simple_description = wp_trim_words( $active_auction_data['simple_description'] , 20 , '...' );
                         $description        = $active_auction_data['description'];
                         $featured_images    = $active_auction_data['featured_images'];

                         preg_match('/(\w{3}) (\d{1,2}) (\d{4}) @ (\d{1,2}:\d{2}(?:AM|PM)) (\w+)/', $auction_end_time, $matches);

                         $month = $matches[1];
                         $date = $matches[2];
                         $year = $matches[3];
                         $time = $matches[4];
                         $timezone = $matches[5];
                         ?>
                         <div class="auction-tabcontent-box">
                              <div class="auction-tabcontent-inner">
                                   <div class="auction-slider">
                                        <div class="auction-slider-for">
                                             <?php foreach($featured_images as $images_data){
                                                  $large_url = $images_data['large_url'];

                                                  if(!empty($large_url)){
                                                  ?>
                                                  <div class="auction-items">
                                                       <img src="<?php echo $large_url; ?>" alt="">
                                                  </div>
                                             <?php } } ?>
                                        </div>
                                        <div class="auction-slider-nav">
                                             <?php foreach($featured_images as $images_data){
                                                  $thumbnail_url = $images_data['thumbnail_url'];

                                                  if(!empty($thumbnail_url)){
                                                  ?>
                                                  <div class="auction-items">
                                                       <img src="<?php echo $thumbnail_url; ?>" alt="">
                                                  </div>
                                             <?php } } ?>
                                        </div>
                                   </div>
                                   <div class="auction-content-box">
                                        <div class="title-with-calender">
                                             <div class="auction-title">
                                                  <h2><a target="_blank" href="https://bidnow.auctionaz.com/ui/auctions/<?php echo $auction_id; ?>"><?php echo $auction_name; ?></a></h2>
                                                  <h3>by AuctionAZ.com, LLC</h3>
                                                  <ul class="auction-active">
                                                       <li><?php echo $auction_count.' '; ?>items</li>
                                                       <li style="text-transform: uppercase;"><?php echo $auction_status; ?></li>
                                                  </ul>
                                             </div>
                                             <div class="auction-calendar">
                                                  <div class="calenda-head">
                                                       <svg class="MuiSvgIcon-root MuiSvgIcon-fontSizeMedium css-vubbuv" focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="TodayIcon"><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"></path></svg>
                                                       <span>END</span>
                                                  </div>
                                                  <div class="date-box">
                                                       <span class="month"><?php echo $month; ?></span>
                                                       <span class="date"><?php echo $date; ?></span>
                                                  </div>
                                                  <div class="time">
                                                       <span style="text-transform: lowercase;"><?php echo $time; ?></span>
                                                       <span style="text-transform: lowercase;"><?php echo $timezone; ?></span>
                                                  </div>
                                             </div>
                                        </div>
                                        <div class="auction-decp">
                                             <?php echo $simple_description;  ?>
                                             <span class="show-more trigger"> Show more</span>
                                             <div class="show_more_content" style="display:none;">
                                                  <?php echo $description; ?>
                                             </div>
                                        </div>
                                        <div class="view-auction-btn">
                                        <a target="_blank" href="https://bidnow.auctionaz.com/ui/auctions/<?php echo $auction_id; ?>" class="view-auction">
                                             <?php _e('VIEW AUCTION' , ''); ?> 
                                        </a>
                                        </div>
                                   </div> 
                              </div>
                         </div>
                    <?php } ?>
               </div> 
          </div>
          <div class="auction-tabcontent-list">
               <div class="auction-tabcontent-wraper">
                    <?php foreach($url_sorted_data_complete as $active_auction_data){
                         $auction_id = $active_auction_data['id']; 
                         $auction_name = $active_auction_data['name'];
                         $auction_count = $active_auction_data['items_count'];
                         $auction_status= str_replace('_' , ' ' , $active_auction_data['status']);
                         $auction_end_time = $active_auction_data['formatted_scheduled_end_time'];
                         $simple_description = wp_trim_words( $active_auction_data['simple_description'] , 20 , '...' );
                         $description        = $active_auction_data['description'];
                         $featured_images    = $active_auction_data['featured_images'];

                         preg_match('/(\w{3}) (\d{1,2}) (\d{4}) @ (\d{1,2}:\d{2}(?:AM|PM)) (\w+)/', $auction_end_time, $matches);

                         $month = $matches[1];
                         $date = $matches[2];
                         $year = $matches[3];
                         $time = $matches[4];
                         $timezone = $matches[5];
                         ?>
                         <div class="auction-tabcontent-box">
                              <div class="auction-tabcontent-inner">
                                   <div class="auction-slider">
                                        <div class="auction-slider-for">
                                             <?php foreach($featured_images as $images_data){
                                                  $large_url = $images_data['large_url'];

                                                  if(!empty($large_url)){
                                                  ?>
                                                  <div class="auction-items">
                                                       <img src="<?php echo $large_url; ?>" alt="">
                                                  </div>
                                             <?php } } ?>
                                        </div>
                                        <div class="auction-slider-nav">
                                             <?php foreach($featured_images as $images_data){
                                                  $thumbnail_url = $images_data['thumbnail_url'];

                                                  if(!empty($thumbnail_url)){
                                                  ?>
                                                  <div class="auction-items">
                                                       <img src="<?php echo $thumbnail_url; ?>" alt="">
                                                  </div>
                                             <?php } } ?>
                                        </div>
                                   </div>
                                   <div class="auction-content-box">
                                        <div class="title-with-calender">
                                             <div class="auction-title">
                                                  <h2><a target="_blank" href="https://bidnow.auctionaz.com/ui/auctions/<?php echo $auction_id; ?>"><?php echo $auction_name; ?></a></h2>
                                                  <h3>by AuctionAZ.com, LLC</h3>
                                                  <ul class="auction-complete">
                                                       <li><?php echo $auction_count.' '; ?>items</li>
                                                       <li style="text-transform: uppercase;"><?php echo $auction_status; ?></li>
                                                  </ul>
                                             </div>
                                             <div class="auction-calendar">
                                             <div class="calenda-head">
                                                  <svg class="MuiSvgIcon-root MuiSvgIcon-fontSizeMedium css-vubbuv" focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="TodayIcon"><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"></path></svg>
                                                  <span>END</span>
                                             </div>
                                             <div class="date-box">
                                                  <span class="month"><?php echo $month; ?></span>
                                                  <span class="date"><?php echo $date; ?></span>
                                             </div>
                                             <div class="time">
                                                  <span style="text-transform: lowercase;"><?php echo $time; ?></span>
                                                  <span style="text-transform: lowercase;"><?php echo $timezone; ?></span>
                                             </div>
                                             </div>
                                        </div>
                                        <div class="auction-decp">
                                             <?php echo $simple_description;  ?>
                                             <span class="show-more trigger"> Show more</span>
                                             <div class="show_more_content" style="display:none;">
                                                  <?php echo $description; ?>
                                             </div>
                                        </div>
                                        <div class="view-auction-btn">
                                        <a target="_blank" href="https://bidnow.auctionaz.com/ui/auctions/<?php echo $auction_id; ?>" class="view-auction">
                                             <?php _e('VIEW AUCTION' , ''); ?> 
                                        </a>
                                        </div>
                                   </div> 
                              </div>
                         </div>
                    <?php } ?>
               </div> 
          </div>
        </div>
</section>
<?php //} ?>

<div class="modal auction-modal">
    <div class="modal-content">
          <div class="modal-header">
             
               <h2>Glendale Used Auto Bankruptcy Auction - Jan29</h2>  
          </div>
        <div class="modal-body">
           Hello world
        </div>
        <div class="modal-footer">
            <span class="close-button">ok</span>
        </div>
    </div>
</div>