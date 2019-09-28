<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

// For profile sharing on social platforms
$profile_url    = site_url($this->user['username']);

$share_hashtag  = strtolower(str_replace(' ', '_', $this->settings->site_name));
$share_quote    = lang('sm_review_me');

$facebook_url   = "https://www.facebook.com/sharer.php?u=$profile_url&quote=$share_quote&hashtag=%23$share_hashtag";

$twitter_url    = "https://twitter.com/intent/tweet/?text=".urlencode("$share_quote #$share_hashtag $profile_url");

$whatsapp_url   = "https://api.whatsapp.com/send?text=$share_quote #$share_hashtag $profile_url";

?>

<main class="profile-page" id="send-message">

<!-- Profile header background -->
<section class="section-profile-cover section-shaped my-0">
    <!-- Circles background -->
    <div class="shape shape-style-1 shape-primary alpha-4">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
    <!-- SVG separator -->
    <div class="separator separator-bottom">
        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
        <polygon class="seperator-polygon-color" points="2560 0 2560 100 0 100"></polygon>
        </svg>
    </div>
</section>

<!-- Profile & Info -->
<section class="section pb-1" style="z-index: 9;">
    <div class="container">
        <div class="card card-profile shadow-sm mt--300">
            <div class="px-4">
                
                <div class="row justify-content-center">
                    <div class="col-4">
                        <div class="card-profile-image">
                            <a role="button" data-toggle="modal" data-target="#modal-photo" href="#modal-photo">
                                <img src="<?php echo $this->user['image'] ? base_url('upload/users/images/').image_to_thumb($this->user['image']) : base_url('themes/default/img/avatar.png'); ?>" class="rounded-circle" style="top: -5.5rem;">
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-sm-5 mt-5">
                    <h3><?php echo $this->user['fullname'] ?></h3>
                    <div class="h6 font-weight-300">
                        <i class="fas fa-share-alt mr-2"></i> <?php echo get_domain().'/'.$this->user['username']; ?>
                    </div>
                    <div class="h6 mt-3 mb-0">
                        Share Profile On
                    </div>
                    <div class="d-flex justify-content-center mb-2">
                        <div class="p-3">
                            <a title="Facebook" href="<?php echo $facebook_url; ?>" target="_blank"><small><i class="fab fa-facebook-square fa-2x"></i></small></a>
                        </div>
                        <div class="p-3">
                            <a title="Twitter" href="<?php echo $twitter_url ?>" target="_blank"><small><i class="fab fa-twitter fa-2x"></i></small></a>
                        </div>
                        <div class="p-3">
                            <a title="Whatsapp" href="<?php echo $whatsapp_url ?>" target="_blank"><small><i class="fab fa-whatsapp fa-2x"></i> </small></a>
                        </div>
                    </div>
                </div>
                

            </div>
        </div>
    </div>
</section>

<!-- Message tab -->
<section class="section section-components pt-0">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">

                <!-- Tab Navigation start-->
                <div class="nav-wrapper">
                    <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                        <!-- Received -->
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0 <?php echo $parent_tab == 'received' ? 'active' : ''; ?>" href="<?php echo $parent_tab == 'received' ? '#' : site_url('messages'); ?>">
                                <i class="fas fa-inbox mr-2"></i> <?php echo lang('sm_received') ?> 
                                <?php 
                                    if(count($this->notifications)) { 
                                        
                                        $not_count = 0;
                                        foreach($this->notifications as $val)
                                            if($val->n_type === 'messages')
                                                $not_count += $val->total;
                                            
                                        if($not_count)
                                            echo '<i class="nav-link-notification text-white">'.$not_count.'</i>';

                                    } 
                                ?>
                            </a>
                        </li>
                        <!-- Sent -->
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0 <?php echo $parent_tab == 'sent' ? 'active' : ''; ?>" href="<?php echo $parent_tab == 'sent' ? '#' : site_url('messages/sent'); ?>">
                                <i class="fas fa-paper-plane mr-2"></i> <?php echo lang('sm_sent') ?>
                            </a>
                        </li>
                        <!-- Favourites -->
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0 <?php echo $parent_tab == 'favorites' ? 'active' : ''; ?>" href="<?php echo $parent_tab == 'favorites' ? '#' : site_url('messages/favorites'); ?>">
                                <i class="fas fa-heart mr-2"></i> <?php echo lang('sm_favorites') ?>
                            </a>
                        </li>
                        
                    </ul>
                </div>
                <!-- Tab Navigation end-->

                <!-- Tab Content start-->
                <div class="card shadow-sm">
                    <div class="card-body">

                        <div class="tab-content">

                            <!-- Received -->
                            <div class="tab-pane fade <?php echo $parent_tab == 'received' ? 'show active' : ''; ?>" id="received">

                                <!-- Received Tab Child Content start-->
                                <div class="tab-content mt-4">
                                    <!-- Received Messages Ajax loaded -->
                                    <div class="tab-pane fade <?php echo $child_tab == 'messages' ? 'show active' : ''; ?>" id="received_messages">
                                        <div class="list-group" id="received_messages_items">
                                            <!-- Ajax content-->
                                        </div>
                                    </div>
                                    
                                </div>
                                <!-- Received Tab Child Content end-->

                            </div>

                            <!-- Sent -->
                            <div class="tab-pane fade <?php echo $parent_tab == 'sent' ? 'show active' : ''; ?>" id="sent">

                                <!-- Send Tab Child Content start-->
                                <div class="tab-content mt-4">
                                    <!-- Send Messages Ajax loaded -->
                                    <div class="tab-pane fade <?php echo $child_tab == 'messages' ? 'show active' : ''; ?>" id="sent_messages">
                                        <div class="list-group" id="sent_messages_items">
                                            <!-- Ajax content-->
                                        </div>
                                    </div>
                                    
                                </div>
                                <!-- Send Tab Child Content end-->

                            </div>
                            
                            <!-- Favorite -->
                            <div class="tab-pane fade <?php echo $parent_tab == 'favorites' ? 'show active' : ''; ?>" id="favorite">
                                <!-- Favorite Tab Content start-->
                                <div class="list-group" id="favorite_items">
                                    <!-- Ajax content-->
                                </div>
                                <!-- Favorite Tab Content end-->
                            </div>

                        </div>

                    </div>
                    <div class="card-footer text-center">
                        <small class="text-muted pointer-elem-hover" id="load_more_loader" onclick="loadMore()"><?php echo lang('action_load_more') ?></small>
                    </div>
                </div>
                <!-- Tab Content end-->

            </div>
        </div>
    </div>
</section>

</main>

<!-- Photo modal -->
<div class="modal fade" id="modal-photo" tabindex="-1" role="dialog" aria-labelledby="modal-photo" aria-hidden="true">
    <div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
        <div class="modal-content bg-gradient-danger">
            <div class="modal-body p-0">
                <div class="text-center">
                    <a role="button" data-dismiss="modal" aria-label="Close">
                        <img src="<?php echo $this->user['image'] ? base_url('upload/users/images/').$this->user['image'] : base_url('themes/default/img/avatar.png'); ?>" class="img-fluid rounded shadow w-100" alt="<?php echo $this->user['fullname'] ?>">
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
// active tab data
var parent_tab  = '<?php echo $parent_tab; ?>';
var child_tab   = '<?php echo $child_tab; ?>';
</script>