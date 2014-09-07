<h2 class="margin-bottom-1"><?php echo __('New Less', 'less'); ?></h2>

<?php if (isset($errors['less_empty_name']) or isset($errors['less_exists'])) $error_class = 'error'; else $error_class = ''; ?>

<?php echo (Form::open(null, array('class' => 'form-horizontal'))); ?>

<?php echo (Form::hidden('csrf', Security::token())); ?>

<?php echo (Form::label('name', __('Name', 'less'))); ?>
<div class="input-group">
    <?php echo (Form::input('name', $name, array('class' => (isset($errors['less_empty_name']) || isset($errors['less_exists'])) ? 'form-control error-field' : 'form-control'))); ?><span class="input-group-addon">.less</span>
</div>

<?php
    if (isset($errors['less_empty_name'])) echo '<span class="error-message">'.$errors['less_empty_name'].'</span>';
    if (isset($errors['less_exists'])) echo '<span class="error-message">'.$errors['less_exists'].'</span>';
?>

<div class="margin-top-2 margin-bottom-2">
<?php
    echo (
       Form::label('content', __('Less content', 'less')).
       Form::textarea('content', $content, array('style' => 'width:100%;height:400px;', 'class'=>'source-editor form-control'))
    );
?>
</div>

<?php
    echo (
       Form::submit('add_less_and_exit', __('Save and Exit', 'less'), array('class' => 'btn btn-phone btn-primary')).Html::nbsp(2).
       Form::submit('add_less', __('Save', 'less'), array('class' => 'btn btn-phone btn-primary')).Html::nbsp(2).
       Html::anchor(__('Cancel', 'less'), 'index.php?id=less', array('title' => __('Cancel', 'less'), 'class' => 'btn btn-phone btn-default')).
       Form::close()
    );
?>