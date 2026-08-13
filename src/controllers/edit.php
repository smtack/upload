<?php

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
            if($upload->editUpload($id, array(
                'upload_title' => escape(Input::get('upload_title')),
                'upload_description' => escape(Input::get('upload_description'))
            ))) {
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

            Redirect::to(BASE_URL . '/your-uploads');
        }
    }
}

require VIEW_ROOT . '/edit.php';
