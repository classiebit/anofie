<nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="<?php echo site_url('admin/'.$this->uri->segment(2)) ?>">
            <?php echo $page_header ?>
        </a>
        
        <!-- User -->
        <ul class="navbar-nav align-items-center d-none d-md-flex">
            <li class="nav-item dropdown">
                <a class="nav-link nav-link-icon" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-bell"></i> <strong><sup><?php echo !empty($this->notifications) ? count($this->notifications) : '' ?></sup></strong>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right" aria-labelledby="navbar-default_dropdown_1">
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0"><?php echo lang('notifications') ?></h6>
                    </div>

                    <?php if(!empty($this->notifications)) { ?>
                        <?php foreach($this->notifications as $key => $val) { ?>
                        <a href="javascript:readNotification(`<?php echo $val->n_type ?>`);" class="dropdown-item dropdown-item-unread">
                            <?php if($val->n_type == 'users') { ?>
                            <i class="fas fa-user text-teal"></i>
                            <?php } else if($val->n_type == 'messages') { ?>
                            <i class="fas fa-envelope text-blue"></i>
                            <?php } else if($val->n_type == 'contacts') { ?>
                            <i class="fas fa-phone text-green"></i>
                            <?php } ?>
                            
                            <span class="ml--2"><?php echo $val->total.' '.lang('noti_new').' '.lang(''.$val->n_content.''); ?></span>
                            <br>
                            <small class="ml-4 text-muted"><?php echo time_elapsed_string($val->date_added); ?></small>
                        </a>
                        <?php } ?>
                    <?php } else { ?>
                        <a href="#" class="dropdown-item"><?php echo lang('noti_no'); ?></a>
                    <?php } ?>

                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
                        <span class="avatar avatar-sm rounded-circle">
                            <img src="<?php echo $this->user['image'] ? base_url('upload/users/images/').image_to_thumb($this->user['image']) : base_url('themes/default/img/avatar.png'); ?>" class="rounded-circle">
                        </span>
                        <div class="media-body ml-2 d-none d-lg-block">
                            <span class="mb-0 text-sm  font-weight-bold"><?php echo $_SESSION['logged_in']['fullname']; ?></span>
                        </div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <a href="<?php echo site_url('admin/users/view/').$_SESSION['logged_in']['id']; ?>" class="dropdown-item">
                        <i class="fas fa-user-circle"></i>
                        <span><?php echo lang('action_profile'); ?></span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="<?php echo site_url('logout'); ?>" class="dropdown-item">
                        <i class="fas fa-sign-out-alt"></i>
                        <span><?php echo lang('action_logout'); ?></span>
                    </a>
                </div>
            </li>
        </ul>

    </div>
</nav>