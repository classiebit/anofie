<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Content Header BG Gradient -->
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-7">
    <div class="container-fluid">
        <div class="header-body">
            <!-- Card stats -->
            <div class="row">
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0"><?php echo lang('menu_users') ?></h5>
                                    <span class="h2 font-weight-bold mb-0"><?php echo number_format($total_users); ?></span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-teal text-white rounded-circle shadow">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <?php $is_noti = false; if(!empty($this->notifications)) { ?>
                                    <?php foreach($this->notifications as $key => $val) { ?>
                                        <?php if($val->n_type == 'users') { $is_noti = true; ?>
                                        <span class="text-success mr-2"><i class="fas fa-plus"></i> <?php echo number_format($val->total).' '.lang('noti_new').' '.lang('menu_users') ?></span>
                                        <span class="text-nowrap"><?php echo time_elapsed_string($val->date_added); ?></span>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>

                                <?php if(!$is_noti) { ?>
                                <span class="text-danger mr-2"><i class="fas fa-battery-empty"></i> <?php echo '0 '.lang('noti_new').' '.lang('menu_users') ?></span>
                                <span class="text-nowrap">&nbsp;</span>
                                <?php } ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0"><?php echo lang('menu_messages') ?></h5>
                                    <span class="h2 font-weight-bold mb-0"><?php echo $total_messages ?></span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-blue text-white rounded-circle shadow">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <?php $is_noti = false; if(!empty($this->notifications)) { ?>
                                    <?php foreach($this->notifications as $key => $val) { ?>
                                        <?php if($val->n_type == 'messages') { $is_noti = true; ?>
                                        <span class="text-success mr-2"><i class="fas fa-plus"></i> <?php echo number_format($val->total).' '.lang('noti_new').' '.lang('menu_messages') ?></span>
                                        <span class="text-nowrap"><?php echo time_elapsed_string($val->date_added); ?></span>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>

                                <?php if(!$is_noti) { ?>
                                <span class="text-danger mr-2"><i class="fas fa-battery-empty"></i> <?php echo '0 '.lang('noti_new').' '.lang('menu_messages') ?></span>
                                <span class="text-nowrap">&nbsp;</span>
                                <?php } ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--8">

    <div class="row mt-4">
        <div class="col-xl-12  mb-5 mb-xl-0">
            <div class="card bg-gradient-default shadow">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-uppercase text-light ls-1 mb-1"><?php echo lang('dashboard_top_receivers_eg') ?></h6>
                            <h2 class="text-white mb-0"><?php echo lang('dashboard_top_receivers') ?></h2>
                        </div>
                        <div class="col text-right">
                            <a href="<?php echo site_url('admin/messages') ?>" class="btn btn-sm btn-primary"><?php echo lang('action_view_all') ?></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Chart -->
                    <div class="chart">
                    <!-- Chart wrapper -->
                    <canvas id="chart-sales" class="chart-canvas"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
</div>

<script type="text/javascript">
    var top_receivers      = '<?php echo !empty($top_receivers) ? json_encode($top_receivers) : json_encode([]); ?>';
</script>