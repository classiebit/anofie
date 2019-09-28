<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*Common Table View*/
?>

<div class="container-fluid mt--8">
    <div class="row mt-3 index-page">
        <div class="col">
            <div class="card shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo lang('action_listing') ?></h3>
                        </div>
                        <div class="col-4 text-right">
                            <!-- Add Button -->
                            <?php if($this->uri->segment(2) == 'users' || $this->uri->segment(2) == 'groups') { ?>
                            <a href="<?php echo site_url().'admin/'.$this->uri->segment(2).'/form'; ?>" class="btn btn-sm btn-primary btn-icon">
                                <span class="btn-inner--text"><i class="fas fa-plus"></i>&nbsp; <?php echo lang('menu_add_new') ?></span>
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table id="table" class="table align-items-center table-flush table-hover dataTable">
                        <thead class="thead-light">
                            <tr>
                                <?php foreach($t_headers as $val) { echo '<th>'.$val.'</th>'; } ?>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
