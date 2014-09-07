<h2 class="margin-bottom-1"><?php echo __('Edit Less', 'less'); ?></h2>


<?php
    if ($content !== null) {

        if (isset($errors['less_empty_name']) or isset($errors['less_exists'])) $error_class = 'error'; else $error_class = '';

        echo (Form::open(null, array('class' => 'form-horizontal')));

        echo (Form::hidden('csrf', Security::token()));

        echo (Form::hidden('less_old_name', Request::get('filename')));

?>

    <?php echo (Form::label('name', __('Name', 'less'))); ?>

        <div class="input-group">
            <?php echo (Form::input('name', $name, array('class' => (isset($errors['less_empty_name']) || isset($errors['less_exists'])) ? 'form-control error-field' : 'form-control'))); ?><span class="input-group-addon">.less/.css</span>
        </div>

        <?php
            if (isset($errors['less_empty_name'])) echo '<span class="error-message">'.$errors['less_empty_name'].'</span>';
            if (isset($errors['less_exists'])) echo '<span class="error-message">'.$errors['less_exists'].'</span>';
        ?>
<ul class="nav nav-tabs">
    <li class = 'active'><a href="#less-content-tab" data-toggle="tab"><?php echo __('Less content', 'less'); ?></a></li>
    <li><a href="#css-content-tab" data-toggle="tab"><?php echo __('Css content', 'less'); ?></a></li>
</ul>
  <div class = 'tab-content tab-page'>
    <div class = 'tab-pane active' id='less-content-tab'>
      <div class="margin-top-2 margin-bottom-2">
        <?php
        echo (Form::textarea('content', Html::toText($content), array('style' => 'width:100%;height:400px;', 'class' => 'source-editor form-control'))
        );
        ?>
      </div>
      </div>
      <div class = 'tab-pane' id = 'css-content-tab'>
      <div class="margin-top-2 margin-bottom-2">
        <?php
        echo (
        
        Form::textarea('content_css', Html::toText($content_css), array('style' => 'width:100%;height:400px;', 'class' => 'source-editor form-control'))
        );
        ?>
      </div>
    </div>
  </div>
<?php
        echo (
           Form::submit('edit_less_and_exit', __('Save and Exit', 'less'), array('class' => 'btn btn-phone btn-primary')).Html::nbsp(2).
           Form::submit('edit_less', __('Save', 'less'), array('class' => 'btn btn-phone btn-primary')). Html::nbsp(2).
           Html::anchor(__('Cancel', 'less'), 'index.php?id=less', array('title' => __('Cancel', 'less'), 'class' => 'btn btn-phone btn-default')).
           Form::close()
        );

    } else {
        echo '<div class="message-error">'.__('This less does not exist', 'less').'</div>';
    }
?>
