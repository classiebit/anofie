<?php defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container-fluid mt--8">

    <div class="row mt-3">
        <div class="col">
            <div class="card shadow bg-secondary">

                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo lang('action_view'); ?></h3>
                        </div>
                    </div>
                </div>
                    
                <div class="card-body table-responsive">
                    <table id="table" class="table align-items-center table-flush">
                        <tbody>
                            <tr>
                                <th>
                                    <?php echo lang('users_fullname'); ?>
                                </th>
                                <td class="text-capitalize">
                                    <?php echo $users->fullname; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php echo lang('users_username'); ?>
                                </th>
                                <td class="text-capitalize">
                                    <?php echo $users->username; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php echo lang('users_email'); ?>
                                </th>
                                <td class="text-capitalize">
                                    <?php echo $users->email; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php echo lang('common_image'); ?>
                                </th>
                                <td>
                                    <?php if(! empty($users->image)) { ?>
                                        <img src="<?php echo base_url('upload/users/images/'.$users->image); ?>" class="img-responsive col-sm-2 thumbnail" width="64px">
                                        <?php } else { ?>
                                            N/A
                                            <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php echo lang('common_status'); ?>
                                </th>
                                <td>
                                    <?php echo ($users->active) ? '<span class="label label-success">'.lang('common_status_active').'</span>' : '<span class="label label-default">'.lang('common_status_inactive').'</span>'; ?></td>
                            </tr>
                            <tr>
                                <th>
                                    <?php echo lang('common_added'); ?>
                                </th>
                                <td>
                                    <?php echo date("F j, Y g:i A ", strtotime($users->date_added)) ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php echo lang('common_updated'); ?>
                                </th>
                                <td>
                                    <?php echo date("F j, Y g:i A ", strtotime($users->date_updated)) ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    <!-- Back Button -->
                    <a href="<?php echo site_url('admin/'.$this->uri->segment(2)) ?>" class="btn btn-secondary">
                        <?php echo lang('action_back') ?>
                    </a>
                </div>

            </div>
        </div>
    </div>
    
</div>