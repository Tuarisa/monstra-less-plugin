<div class="container">
    <div class="text-left row-phone">
        <h2><?php echo __('Less', 'less'); ?></h2>
    </div>
        <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <?php echo __('Less triggers', 'less'); ?>
                    </h3>
                </div>
                <div class="panel-body">
                    <?php 
                    echo (
                    Form::open().
                    Form::label('Object', __('Triggered', 'less')).
                    Form::select('object', $less_array, $object, array('class' => 'form-control')).
                    Form::label('Subject', __('Compiled', 'less')).
                    Form::select('subject', $less_array, $subject, array('class' => 'form-control')).
                    Form::hidden('csrf', Security::token()))
                    ;?>
                </div>
                <div class="panel-footer"><?php
                    echo(
                    Form::submit('add_new_trigger', __('Add trigger', 'less'), array('class' => 'btn btn-success')).
                    Form::close()
                    );
                    ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th><?php echo __('Less triggers', 'less'); ?></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (count($triggers) != 0) foreach ($triggers as $trigger) { ?>
                    <tr>
                        <td><?php echo $trigger['object'] ?></td>
                        <td><?php echo $trigger['subject'] ?></td>
                        <td><?php echo Html::anchor(__('Delete', 'less'),
                    'index.php?id=less&delete_id='.$trigger['id'].'&token='.Security::token(),
                    array('class' => 'btn btn-danger'));
                ?></td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
                </div>
        </div>
    <div>
    <div class="text-right row-phone">
        <?php
            echo (
                Html::anchor(__('Create New Less', 'less'), 'index.php?id=less&action=add_less', array('title' => __('Create New Less', 'less'), 'class' => 'btn btn-phone btn-primary'))
            );
        ?>
    </div>
</div>

<!-- Less_less_list -->
<table class="table table-bordered">
    <thead>
        <tr>
            <th><?php echo __('Less', 'less'); ?></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php if (count($less_less_list) != 0) foreach ($less_less_list as $less) { ?>
    <tr>
        <td><?php echo basename($less, '.less'); ?></td>
        <td>
            <div class="pull-right">            
                <div class="btn-group">
                    <?php echo Html::anchor(__('Edit', 'less'), 'index.php?id=less&action=edit_less&filename='.str_replace('.less', '', $less), array('class' => 'btn btn-primary')); ?>
                </div>   
                <?php echo Html::anchor(__('Delete', 'less'),
                    'index.php?id=less&action=delete_less&filename='.str_replace('.less', '', $less).'&token='.Security::token(),
                    array('class' => 'btn btn-danger', 'onclick' => "return confirmDelete('".__('Delete less: :less', 'less', array(':less' => str_replace('.less', '', $less)))."')"));
                ?>
            </div>
        </td>
    </tr>
    <?php } ?>
    </tbody>
</table>
<!-- /Less_less_list -->

<div class="modal fade" id="embedCodes"> 
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="close" data-dismiss="modal">&times;</div>
                <h4 class="modal-title"><?php echo __('Embed Code', 'less'); ?></h4>
            </div>
            <div class="modal-body">
                <b><?php echo __('Shortcode', 'less'); ?></b><br>
                <pre><code id="shortcode"></code></pre>
                <br>
                <b><?php echo __('PHP Code', 'less'); ?></b><br>
                <pre><code id="phpcode"></code></pre>
            </div>
        </div>
    </div>
</div>
</div>