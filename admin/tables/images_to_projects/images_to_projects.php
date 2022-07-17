<?php

class tables_images_to_projects {  

    function afterSave(&$record){
        if ( $record->valueChanged('image') ){
            $fieldDef =& $record->_table->getField('image');
            $savepath = $fieldDef['savepath'];
            $project = df_get_record('projects', array('id' => $record->val('projects_id')));
            $project_dir = preg_replace('/\s+/', '-', $project->val('name'));
            $fieldDef['savepath'] .= DIRECTORY_SEPARATOR;
            $fieldDef['savepath'] .= $project_dir;

            $filename = basename($record->val('image'));
            $updated_savepath = $savepath.DIRECTORY_SEPARATOR.$project_dir;

            if(!is_dir($updated_savepath)) {
                mkdir($updated_savepath);
                chmod($updated_savepath, 0775);
            }

            /**
             * Renaming the file into a project directory works, but requires some updates to 
             * how the ajax_upload widget handles the new location.
             * 
             * The module calls basename on the filename, which strips out any leading directory
             * designation, so there are 2 updates to the module code which remove basename, in
             * order to display the image and size correctly in the form:
             * admin/xataface/modules/ajax_upload/actions/ajax_upload_get_temp_file_details.php:57
             * admin/xataface/modules/ajax_upload/actions/ajax_upload_get_thumbnail.php:68
             */
            rename($savepath.DIRECTORY_SEPARATOR.$filename, $updated_savepath.DIRECTORY_SEPARATOR.$filename);

            // $record->save() does not work here, so we need to update the DB manually
            df_q("UPDATE `images_to_projects` SET `image` = '" . addslashes($project_dir.DIRECTORY_SEPARATOR.$filename) . "' where id = " . $record->val('id'));
        }
    }
}