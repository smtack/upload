<?php

use Core\Config;
use Core\Redirect;
use Core\File;
use Core\Hash;
use Core\Input;
use Core\Validate;

$user = new Models\User();
$upload = new Models\Upload();

if(!$user->loggedIn()) {
    Redirect::to(BASE_URL);
}

$page_title = "Upload - New Upload";

if(Input::exists($_POST, 'upload')) {
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
                'upload_description' => escape(Input::get('upload_description')),
                'upload_by' => $user->data()->user_id,
            ];

            if (!File::get('upload_file')) {
                $validation->addError("Select a file to upload");
            } else {
                $upload_file = File::get('upload_file');
                $upload_file_extension = File::getFileExtension($upload_file);
                $filename = File::createUniqueFilename() . "." . $upload_file_extension;

                if (!in_array($upload_file_extension, Config::get('allowed_upload_extensions'))) {
                    $validation->addError("This file type is not supported");
                } else {
                    $data['upload_file'] = $filename;

                    if (File::get('upload_thumbnail')) {
                        $upload_thumbnail = File::get('upload_thumbnail');
                        $upload_thumbnail_extension = File::getFileExtension($upload_thumbnail);
                        $thumbnail_filename = File::createUniqueFilename() . "." . $upload_thumbnail_extension;

                        if (!in_array($upload_thumbnail_extension, Config::get('allowed_upload_extensions'))) {
                            $validation->addError("This thumbnail file type is not supported");
                        } else if(!File::moveFile('upload_thumbnail', "thumbnails", $thumbnail_filename)) {
                            $validation->addError("Unable to upload thumbnail");
                        } else {
                            $data['upload_thumbnail'] = $thumbnail_filename;
                        }
                    } else {
                        is_video($upload_file) ? $data['upload_thumbnail'] = 'default.png' : $data['upload_thumbnail'] = null;
                    }

                    if(!File::moveFile('upload_file', "uploads", $filename)) {
                        $validation->addError("Unable to upload file");
                    } else {
                        if(!$upload->newUpload($data)) {
                            $validation->addError("Unable to make upload. Try again later.");
                        } else {
                            Redirect::to(BASE_URL);
                        }
                    }
                }
            }
        }
    }
}

require VIEW_ROOT . '/upload.php';
