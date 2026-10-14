<?php

use Core\Config;
use Core\File;
use Core\Hash;
use Core\Input;
use Core\Redirect;
use Core\Router;
use Core\Validate;

$user = new Models\User();
$upload = new Models\Upload();

if(!$user->loggedIn()) {
    Redirect::to(BASE_URL);
}

if(!$id = Input::get('id')) {
    Redirect::to(BASE_URL);
}

if(!$upload_data = $upload->getUpload($id)) {
    Router::abort(404);
}

if($upload_data->upload_by !== $user->data()->user_id) {
    Redirect::to(BASE_URL);
}

$page_title = "Upload - Edit Post: " . $upload_data->upload_title;

if(Input::exists($_POST, 'edit')) {
    if(Hash::checkToken(Input::get('token'), 'token')) {
        $validate = new Validate();

        $validation = $validate->check($_POST, array(
            'upload_title' => array(
                'name' => 'Title',
                'required' => true,
                'min' => 1,
                'max' => 150
            ),
            'upload_description' => array(
                'name' => 'Description',
                'max' => 500
            )
        ));

        if($validation->passed()) {
            $data = [
                'upload_title' => escape(Input::get('upload_title')),
                'upload_description' => escape(Input::get('upload_description'))
            ];

            if (File::get('upload_thumbnail')) {
                $upload_thumbnail = File::get('upload_thumbnail');
                $upload_thumbnail_extension = File::getFileExtension($upload_thumbnail);
                $thumbnail_filename = File::createUniqueFilename() . "." . $upload_thumbnail_extension;

                if (!in_array($upload_thumbnail_extension, Config::get('allowed_upload_extensions'))) {
                    $validation->addError("This thumbnail file type is not supported");
                } else if (!File::moveFile('upload_thumbnail', "thumbnails", $thumbnail_filename)) {
                    $validation->addError("Unable to upload thumbnail");
                } else {
                    $data['upload_thumbnail'] = $thumbnail_filename;

                    if ($upload_data->upload_thumbnail) {
                        File::removeFile("uploads/thumbnails/{$upload_data->upload_thumbnail}");
                    }
                }
            }

            if($upload->editUpload($id, $data)) {
                Redirect::to(BASE_URL . '/your-uploads');
            } else {
                $validation->addError("Unable to edit upload");
            }
        }
    }
}

if(Input::exists($_POST, 'delete_upload')) {
    if(Hash::checkToken(Input::get('delete-token'), 'delete-token')) {
        $filename = $upload_data->upload_file;

        if($upload->deleteUpload($id)) {
            File::removeFile("uploads/uploads/$filename");

            if($upload_data->upload_thumbnail) {
                File::removeFile("uploads/thumbnails/{$upload_data->upload_thumbnail}");
            }

            Redirect::to(BASE_URL . '/your-uploads');
        }
    }
}

require VIEW_ROOT . '/edit.php';
