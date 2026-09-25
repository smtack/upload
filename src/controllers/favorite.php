<?php

use Core\Redirect;
use Core\Input;

$user = new Models\User();
$upload = new Models\Upload();

if(!$user->loggedIn()) {
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Not logged in']);
        exit;
    }

    Redirect::to(BASE_URL);
}

if(!$id = Input::get('id')) {
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Not found']);
        exit;
    }

    Redirect::to(BASE_URL);
}

$favorite_data = $upload->getFavoritesData($id);

$upload_to_favorite = $upload->getUpload($id);

$data = [
    'favorite_user' => $user->data()->user_id,
    'favorite_upload' => $id
];

$is_favorited = (bool) findValue($favorite_data, 'favorite_user', $user->data()->user_id);

if ($is_favorited) {
    $success = $upload->unfavorite($data);
    $new_status = false;
} else {
    $success = $upload->favorite($data);
    $new_status = true;
}

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => (bool) $success,
        'is_favorited' => $new_status,
        'message' => $success ? ($new_status ? 'Upload favorited' : 'Upload unfavorited') : 'Could not update favorite'
    ]);
    exit;
}

Redirect::to(BASE_URL . '/view?id=' . $upload_to_favorite->upload_id);