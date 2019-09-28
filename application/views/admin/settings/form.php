<?php defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container-fluid mt--8">

    <div class="row mt-3 index-page">
        <div class="col">
            <div class="card shadow bg-secondary">

                <div class="card-body">

                    <!-- Settings Tabs -->
                    <div>
                        <ul class="nav nav-pills mb-4">
                        
                        <?php $t_count = 0; foreach($settings as $k => $v) { $t_count++; ?>
                            <li class="nav-item">
                                <a  class="nav-link <?php echo $k == 'GENERAL' ? 'active' : ''; ?>" href="#tab_<?php echo $t_count; ?>" data-toggle="tab" class="text-capitalize">
                                    <?php echo str_replace("_", " ", $k); ?>
                                </a>
                            </li>
                        <?php } ?>
                        </ul>
                        <div class="tab-content">
                        <?php $tab_count = 0; foreach($settings as $key => $val) { $tab_count++; ?>    
                            <div class="tab-pane <?php echo $key == 'GENERAL' ? 'active' : ''; ?>" id="tab_<?php echo $tab_count; ?>">
                            <?php echo form_open_multipart('', array('role'=>'form')); ?>
                            <?php foreach ($val as $setting) : ?>

                            <?php // prepare field settings
                            $field_data = array();

                            if ($setting['is_numeric'])
                            {
                                $field_data['type'] = "number";
                                $field_data['step'] = "any";
                            }

                            if ($setting['options'])
                            {
                                $field_options = array();
                                if ($setting['input_type'] == "dropdown")
                                {
                                    $field_options[''] = lang('admin input select');
                                }
                                $lines = explode("\n", $setting['options']);
                                foreach ($lines as $line)
                                {
                                    $option = explode("|", $line);
                                    $field_options[$option[0]] = $option[1];
                                }
                            }

                            switch ($setting['input_size'])
                            {
                                case "small":
                                    $col_size = "col-sm-3";
                                    break;
                                case "medium":
                                    $col_size = "col-sm-6";
                                    break;
                                case "large":
                                    $col_size = "col-sm-9";
                                    break;
                                default:
                                    $col_size = "col-sm-6";
                            }

                            if ($setting['input_type'] == 'textarea')
                            {
                                $col_size = "col-sm-12";
                            }
                            ?>

                                    <?php // no translations
                                    $field_data['name']  = $setting['name'];
                                    $field_data['id']    = $setting['name'];
                                    $field_data['class'] = "form-control" . (($setting['show_editor']) ? " tinymce" : "") . (($setting['input_type'] == 'dropdown') ? " show-tick form-control" : "");
                                    $field_data['value'] = $setting['value'];
                                    ?>

                                    <div class="form-row">
                                        <div class="form-group <?php echo $col_size; ?><?php echo form_error($setting['name']) ? ' has-error' : ''; ?>">
                                            <?php echo form_label($setting['label'], $setting['name'], array('class'=>'control-label')); ?>
                                            <?php if (strpos($setting['validation'], 'required') !== FALSE) : ?>
                                                <span class="required">*</span>
                                            <?php endif; ?>

                                            <?php // render the correct input method
                                            if ($setting['input_type'] == 'input')
                                            {
                                                
                                                $demo_view = ['smtp_password', 'g_client_secret', 'fb_app_secret', 'insta_client_secret', 'twitter_secret_key'];

                                                if (DEMO_MODE === 1 && in_array($setting['name'], $demo_view)) 
                                                    echo '<br><small>'.lang('common_demo').'</small><br><br>';
                                                else
                                                    echo form_input($field_data);
                                                
                                            }
                                            elseif ($setting['input_type'] == 'file')
                                            { ?>
                                                <div class="picture-container">
                                                    <div class="picture picture-square width-72">
                                                        <img id="i_<?php echo $field_data['id'] ?>" src="<?php echo base_url('upload/'.strtolower($key).'/'.$field_data['value']); ?>" class="mt-1 mb-3 rounded-circle img-fluid shadow shadow-lg--hover" width="64px">
                                                        <?php echo form_upload($field_data);?>
                                                    </div>
                                                </div>
                                            <?php }
                                            elseif ($setting['input_type'] == 'email')
                                            {
                                                
                                                echo form_input_custom($field_data, 'email');
                                                
                                            }
                                            elseif ($setting['input_type'] == 'textarea')
                                            {
                                                
                                                echo form_textarea($field_data);
                                                
                                            }
                                            elseif ($setting['input_type'] == 'radio')
                                            {
                                            
                                                foreach ($field_options as $value=>$label)
                                                {
                                                    echo form_radio(array('name'=>$field_data['name'], 'id'=>$field_data['id'] . "-" . $value, 'value'=>$value, 'checked'=>(($value == $field_data['value']) ? 'checked' : FALSE)));
                                                    echo $label;
                                                }
                                                
                                            }
                                            elseif ($setting['input_type'] == 'dropdown')
                                            {   
                                                
                                                echo form_dropdown($setting['name'], $field_options, $field_data['value'], 'id="' . $field_data['id'] . '" class="' . $field_data['class'] . '"');
                                                
                                            }
                                            elseif ($setting['input_type'] == 'timezones')
                                            {
                                                echo timezone_menu($field_data['value'], 'show-tick form-control');
                                                
                                            }
                                            elseif ($setting['input_type'] == 'languages')
                                            {
                                            
                                                echo language_menu($field_data['value'], 'show-tick form-control', $field_data['name']);
                                                
                                            }
                                            ?>

                                            <?php if ($setting['help_text']) : ?>
                                                <span class="help-block"><?php echo $setting['help_text']; ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                
                            <?php endforeach; ?>

                                        <button type="submit" name="submit" class="btn btn-primary text-capitalize"><?php echo lang('action_save').' ('.$key.')'; ?></button>
                                        <a class="btn btn-secondary" href="<?php echo site_url('admin/'); ?>"><?php echo lang('action_cancel'); ?></a>
                                
                            <?php echo form_close(); ?>
                            
                            </div>
                            <!-- /.tab-pane -->
                        <?php } ?> <!-- End top most foreach loop -->
                        </div>
                    <!-- /.tab-content -->
                    </div>
                    <!-- nav-tabs-custom -->

                </div> 
                

            </div>
        </div>
    </div>
</div>