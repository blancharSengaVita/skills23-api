<?php

$tokenError = false;

if ($_SERVER['REQUEST_METHOD'] != 'GET') :
	//on a besoin d'un token valide
	$data = json_decode(file_get_contents("php://input"), true);

	if (!isset($_SESSION['token']) or !isset($data['token'])) :
		$tokenError = true;
	else :
		$now = time();
		if ($_SESSION['expiration'] < $now) :
			$tokenError = true;
		elseif ($_SESSION['token'] != $data['token']) :
			$tokenError = true;
		endif;
	endif;
endif;

if ($tokenError) :
	$response['message'] = "Acess denied";
	$response['code'] = "pas ok";
	http_response_code(403);
	echo json_encode($response);
	die();
endif;