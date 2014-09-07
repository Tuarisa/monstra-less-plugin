<?php

// Admin Navigation: add new item
Navigation::add(__('Less', 'less'), 'content', 'less', 10);

// Add actions
Action::add('admin_themes_extra_index_template_actions','LessAdmin::formComponent');
Action::add('admin_themes_extra_actions','LessAdmin::formComponentSave');

include PLUGINS . DS . 'less/Less/Autoloader.php';
Less_Autoloader::register();


/**
 * Less admin class
 */
class LessAdmin extends Backend
{
    /**
     * Main Less admin function
     */
    public static function main()
    {
        $less_less_path = STORAGE . DS  . 'less' . DS;
        $less_css_path = ASSETS . DS  . 'css' . DS;
        $less_less_list = array();
        $less_css_list = array();
        $errors      = array();
        //$triggers = array();
        $triggers = new Table('less-trigger');
        // Check for get actions
        // -------------------------------------

        if (Request::get('action')) {

            // Switch actions
            // -------------------------------------
             
            switch (Request::get('action')) {

                // Plugin action
                // -------------------------------------
                
                case "add_less":
                    if (Request::post('add_less') || Request::post('add_less_and_exit')) {

                        if (Security::check(Request::post('csrf'))) {

                            if (trim(Request::post('name')) == '') $errors['less_empty_name'] = __('Required field', 'less');
                            if (file_exists($less_less_path.Security::safeName(Request::post('name')).'.less')) $errors['less_exists'] = __('This less file already exists', 'less');

                            if (count($errors) == 0) {

                                // Save less
                                File::setContent($less_less_path.Security::safeName(Request::post('name')).'.less', Request::post('content'));


                                Notification::set('success', __('Your changes to the less <i>:name</i> have been saved.', 'less', array(':name' => Security::safeName(Request::post('name')))));

                                if (Request::post('add_less_and_exit')) {
                                    Request::redirect('index.php?id=less');
                                } else {
                                    Request::redirect('index.php?id=less&action=edit_less&filename='.Security::safeName(Request::post('name')));
                                }
                            }

                        } else { die('Request was denied because it contained an invalid security token. Please refresh the page and try again.'); }
                    }
                    // Save fields
                    if (Request::post('name')) $name = Request::post('name'); else $name = '';
                    if (Request::post('content')) $content = Request::post('content'); else $content = '';

                    // Display view
                    View::factory('less/views/backend/add')
                            ->assign('content', $content)
                            ->assign('name', $name)
                            ->assign('errors', $errors)
                            ->display();
                break;
                case "edit_less":
                    // Save current less action
                    if (Request::post('edit_less') || Request::post('edit_less_and_exit') ) {

                        if (Security::check(Request::post('csrf'))) {

                            if (trim(Request::post('name')) == '') $errors['less_empty_name'] = __('Required field', 'less');
                            if ((file_exists($less_less_path.Security::safeName(Request::post('name')).'.less')) and (Security::safeName(Request::post('less_old_name')) !== Security::safeName(Request::post('name')))) $errors['less_exists'] = __('This less already exists', 'less');

                            // Save fields
                            if (Request::post('content')) $content = Request::post('content'); else $content = '';
                            if (count($errors) == 0) {

                                $less_old_filename = $less_less_path.Request::post('less_old_name').'.less';
                                $less_new_filename = $less_less_path.Security::safeName(Request::post('name')).'.less';
                                if ( ! empty($less_old_filename)) {
                                    if ($less_old_filename !== $less_new_filename) {
                                        rename($less_old_filename, $less_new_filename);
                                        $save_filename = $less_new_filename;
                                    } else {
                                        $save_filename = $less_new_filename;
                                    }
                                } else {
                                    $save_filename = $less_new_filename;
                                }

                                // Save less
                                File::setContent($save_filename, Request::post('content'));

                                try{

                                    $needToCompile = $triggers->select('[object="'.Request::post('name').'.less'.'"]');
                                    
                                    foreach ($needToCompile as $less) {
                                        $parser = new Less_Parser();
                                        $parser->parseFile($less_less_path.$less['subject'], 'http://monstra.uralproject.net/' );
                                        
                                        $content_css = $parser->getCss();
                                        
                                        File::setContent($less_css_path.str_replace('.less', '.css',$less['subject']), $content_css);
                                    }

                                }catch(Exception $e){
                                    $error['less_incorrect'] = $e->getMessage();
                                    
                                }

                                Notification::set('success', __('Your changess to the less <i>:name</i> have been saved.', 'less', array(':name' => basename($save_filename, '.less'))));

                                if (Request::post('edit_less_and_exit')) {
                                    Request::redirect('index.php?id=less');
                                } else {
                                    Request::redirect('index.php?id=less&action=edit_less&filename='.Security::safeName(Request::post('name')));
                                }
                            }

                        } else { die('Request was denied because it contained an invalid security token. Please refresh the page and try again.'); }
                    }
                    if (Request::post('name')) $name = Request::post('name'); else $name = File::name(Request::get('filename'));
                    $content = File::getContent($less_less_path.Request::get('filename').'.less');
                    $content_css = File::getContent($less_css_path.Request::get('filename').'.css');
                    // Display view
                    View::factory('less/views/backend/edit')
                            ->assign('content', $content)
                            ->assign('content_css', $content_css)
                            ->assign('name', $name)
                            ->assign('errors', $errors)
                            ->display();
                break;
                // Plugin action
                // -------------------------------------
                case "delete_less":
                    if (Security::check(Request::get('token'))) {

                        File::delete($less_less_path.Request::get('filename').'.less');
                        Notification::set('success', __('Less <i>:name</i> deleted', 'less', array(':name' => File::name(Request::get('filename')))));
                        Request::redirect('index.php?id=less');

                    } else { die('Request was denied because it contained an invalid security token. Please refresh the page and try again.'); }

                break;
            }

        } else {

            $object = '';
            $subject = '';

            if (Request::post('add_new_trigger')) {
                if (Security::check(Request::post('csrf'))) {
                        $object = Request::post('object');
                        $subject = Request::post('subject');
                        $triggers->insert(array('object'=>$object, 'subject'=>$subject));
                    } else { die('csrf detected!'); }
                
            }
            if (Request::get('delete_id')) {
                if (Security::check(Request::get('token'))) {
                    $triggers->delete((int)Request::get('delete_id'));
                    Request::redirect('index.php?id=less');
                }
            }

            $less_less_list = File::scanFullName($less_less_path, '.less');
            $less_array = array();

            foreach ($less_less_list as $less) {
                        $less_array[$less] = $less;
                    }

            
            $records = $triggers->select(null, 'all');

            // Display view
            View::factory('less/views/backend/index')->assign('less_less_list', $less_less_list)
            ->assign('object', $object)
            ->assign('subject', $subject)
            ->assign('triggers', $records)
            ->assign('less_array', $less_array)
            ->display();
        }

    }

    /**
     * Form Component Save
     */
    public static function formComponentSave()
    {
        if (Request::post('less_component_save')) {
            if (Security::check(Request::post('csrf'))) {
                Option::update('less_template', Request::post('less_form_template'));
                Request::redirect('index.php?id=themes');
            }
        }
    }

    /**
     * Form Component
     */
    public static function formComponent()
    {
        $_templates = Themes::getTemplates();
        foreach ($_templates as $template) {
            $templates[basename($template, '.template.php')] = basename($template, '.template.php');
        }

        echo (
            '<div class="col-xs-3">'.
            Form::open().
            Form::hidden('csrf', Security::token()).
            Form::label('less_form_template', __('Less template', 'less')).
            Form::select('less_form_template', $templates, Option::get('less_template'), array('class' => 'form-control')).
            Html::br().
            Form::submit('less_component_save', __('Save', 'less'), array('class' => 'btn btn-default')).
            Form::close().
            '</div>'
        );
    }

}
