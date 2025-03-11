<?php 
    $auction_name= get_the_title($post_id);
?>

<section class="auctions-listing-sec">
    <?php if(!empty($auction_name)){ ?>
    <div class="auction_name">
        <h2 class="auction_main_name">
            <?php echo $auction_name; ?>
        </h2>
    </div>
    <div class="auctions-container">
    <?php }

    $items_per_page = 10;
    $total_items = count($stored_data);
    $total_pages = ceil($total_items / $items_per_page);
    $current_index = 0;


    foreach ($stored_data as $auction_data) {
                
        $item_id = isset($auction_data['id']) ? esc_html($auction_data['id']) : __('Unknown ID', 'auction-list');
        $item_title = isset($auction_data['title']) ? esc_html($auction_data['title']) : __('No Title', 'auction-list');
        $item_url = isset($auction_data['item_url']) ? esc_html($auction_data['item_url']) : __('No URL', 'auction-list');
        $thumb_url = isset($auction_data['thumb_url']) ? esc_html($auction_data['thumb_url']) : __('No Thumb URL', 'auction-list');
        $extra_info = isset($auction_data['extra_info']) ? wp_kses_post($auction_data['extra_info']) : __('No Extra Info', 'auction-list');
        $description = isset($auction_data['description']) ? wp_kses_post($auction_data['description']) : __('No Extra Info', 'auction-list');
        $current_bid = isset($auction_data['current_bid']) ? esc_html($auction_data['current_bid']) : __('N/A', 'auction-list');
        $bids = isset($auction_data['bid_count']) ? esc_html($auction_data['bid_count']) : 0;
        $time_remaining = isset($auction_data['time_remaining']) ? esc_html($auction_data['time_remaining']) : __('N/A', 'auction-list');
        $high_bidder = isset($auction_data['high_bidder']) ? esc_html($auction_data['high_bidder']) : __('N/A', 'auction-list');
        $minimum_bid = isset($auction_data['minimum_bid']) ? esc_html($auction_data['minimum_bid']) : __('N/A', 'auction-list');
        $current_increment = isset($auction_data['current_increment']) ? esc_html($auction_data['current_increment']) : __('N/A', 'auction-list');
        $display_end_time = isset($auction_data['end_time']) ? esc_html($auction_data['end_time']) - esc_html($auction_data['timezone']) : __('N/A', 'auction-list');

        $remaining_time = format_time_remaining($display_end_time);

    ?>
    <div class="auction-item" data-item="<?php echo $current_index; ?>" style="display: none;">
        <div class="auctions-listing-box">
            <?php if(!empty($thumb_url)){ ?>
            <div class="auctions-listing-item">
                <img src="<?php echo $thumb_url; ?>" alt="">
            </div>
            <?php } ?>
            <div class="auctions-listing-details">
                <?php if(!empty($item_title) || !empty($item_url)){ ?>
                    <div class="title">
                        <h2><a target="_blank" href="<?php echo ($item_url)? $item_url:'javascript:void(0);'; ?>"><?php echo $item_title; ?></a></h2>
                    </div>
                <?php }
                    if(!empty($extra_info)){
                    ?>
                        <div class="extra_info">
                            <?php echo $extra_info ; ?>
                        </div>
                    <?php } ?>
                <div class="accordian">
                    <div class="accordian-box">
                        <div class="question"> Details</div>
                        <div class="answer">
                            <?php echo $description; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="auctions-bidding-block-wraper">
                <div class="auctions-bidding-info">
                    <ul>
                        <li>
                            <?php 
                                if(!empty($current_bid)){ echo '<strong>Current Bid:</strong>'; }
                                if(!empty($current_bid)){ echo '<span>'.$current_bid.'</span>'; }
                                if(!empty($current_bid)){ echo '<span>(bids: '.$bids.'</span>'; }
                            ?>
                        </li>
                        <li>
                        <?php
                            echo '<strong>Time Remaining: </strong>';
                            if (!empty($remaining_time)) {
                                echo '<span class="countdown-timer" data-time="'.$remaining_time.'">'.$remaining_time.'</span>';
                            }
                        ?>
                        </li>
                        <li>
                            <?php  
                                echo '<strong>High Bidder:</strong>';
                                if(!empty($high_bidder)){ echo '<span>'.$high_bidder.'</span>'; }
                            ?>
                        </li>
                        <li>
                            <?php  
                                echo '<strong>Min Bid:</strong>';
                                if(!empty($minimum_bid)){ echo '<span>'.$minimum_bid.'</span>'; }
                            ?>
                        </li>
                        <li>
                            <?php  
                                echo '<strong>Bid Increment:</strong>';
                                if(!empty($current_increment)){ echo '<span>'.$current_increment.'</span>'; }
                            ?>
                        </li>
                    </ul>
                </div>
                <div class="auctions-bidding-block">
                    <a target="_blank" href="<?php echo ($item_url)? $item_url:'javascript:void(0);'; ?>" class="btn-black">Bid $<?php echo $minimum_bid; ?></a>
                    <!-- <h6>Your Highest Bid</h6>
                        <div class="auctions-bid-form">
                            <form action="/action_page.php">
                                <input type="text"  value="$"><br>
                                <input type="submit" value="Submit Max Bid">
                            </form> 
                        </div> -->
                        <div class="auctions-tooltips">
                        <div class="tooltip">(PLACE MAX BID ?)
                            <span class="tooltiptext">
                                <p>Place a bid using the form above.</p>
                                <p>You can enter the minimum bid of $150.00 or a greater amount.</p>
                                <p>If you bid more than the minimum bid you will get a max bid that the system will use to bid for you against other bidders, up to the amount you enter.  So if you enter a bid of $150.00 for this item, the system will place bids on your behalf in response to bids from other users until someone bids more than $150.00.</p>
                                <p>Only LocalAuctions.com Employees have access to your max bid amount.</p>
                        </div>
                        <div class="tooltip">(WITH RESERVE)
                            <span class="tooltiptext"><p>
                                <b>Notice of Reserves.</b> Pursuant to UCC 2-328 and applicable state law, this is a reserve auction. The reserve price for most items is the starting bid price. If the reserve price is greater than the starting bid, LocalAuctions.com and its affiliates, if necessary, may bid on behalf of the seller, using one or more bidder numbers, up to the reserve price. If we have an interest in an offered lot other than our commissions, we may bid in the same manner therefore to protect such interest. As a bidder, It is your responsibility to stop bidding when you have reached the limit you are willing to pay for a particular lot. For more information about the LocalAuctions.com reserve policy, <a href='https://localauctions.com/reserve-notice' target='_blank'>visit our Reserves Page by Clicking Here</a>.
                            </p></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $current_index++; } ?>
</div>
<div class="pagination">
    <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
        <button class="pagination-btn" data-page="<?php echo $i; ?>"><?php echo $i; ?></button>
    <?php } ?>
</div>
</section>