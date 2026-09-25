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

    Redirect::to(BASE_URL . '/login');
}

if(!$id = Input::get('id')) {
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Not found']);
        exit;
    }

    Redirect::to(BASE_URL);
}

$upload_to_rate = $upload->getUpload($id);
$rating = Input::get('star-input');

$data = [
    'rating_user' => $user->data()->user_id,
    'rating_upload' => $upload_to_rate->upload_id,
    'rating_number' => $rating
];

$success = $upload->rate($data);

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => (bool) $success,
        'rating' => (int) $rating,
        'message' => $success ? 'Rating saved' : 'Could not save rating'
    ]);
    exit;
}

Redirect::to(BASE_URL . '/view?id=' . $upload_to_rate->upload_id);