<?php require_once('config.php');

$route = (isset($_GET['route'])) ? $_GET['route'] : "";

$routes_valid = ['pays', 'contacts', 'cities'];

require_once('token.php');

if ($route == "") :
	$response['message'] = "documentation";
	$response['contenu'] = "bla bla";
	echo json_encode($response);
	die();
endif;

if (!in_array($route, $routes_valid)) :
	$response['message'] = "access denied";
	$response['code'] = 'pas ok';
	echo json_encode($response);
	http_response_code(404);
	die();
endif;

//METHOD GET
if ($_SERVER['REQUEST_METHOD'] == 'GET') :
	$sql = "SELECT * FROM $route";
	$rq = $connect->prepare($sql);
	$rq->execute();
	$nb_users = $rq->rowCount();
	$users = $rq->fetchAll();

	if (count($users) > 0) :
		$response['content'] = $users;
		$response['message'] = "Liste $route";
		$response['code'] = 'ok';
		$response['nbhits'] = $nb_users;
	else:
		$response['message'] = "Pas de réponse";
		$response['code'] = "pas ok";
		http_response_code(404);
	endif;
endif;


//METHOD POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') :
	$data = json_decode(file_get_contents("php://input"), true);
	$sql = "INSERT INTO $route SET ";
	$args = [];
	$i = 0;
	unset($data['token']);
	foreach ($data as $field => $value):
		if ($i < count($data) - 1) :
			$sql .= "$field = :$field,";
			$args[$field] = $value;
		else :
			$sql .= "$field = :$field";
			$args[$field] = $value;
		endif;
		$i++;
	endforeach;
	$rq = $connect->prepare($sql);
	$rq->execute($args);
	$nb_hits = $rq->rowCount();

	if ($nb_hits > 0) :
		$response['message'] = "Ajout sur $route";
		$response['code'] = 'ok';
		$response['nbhits'] = $nb_hits;
	else:
		$response['message'] = "Errors server";
		$response['code'] = "pas ok";
		http_response_code(500);
	endif;
endif;

//METHOD DELETE
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') :
	if (!isset($_GET['id'])) :
		$response['message'] = "il manque un id";
		$response['code'] = "pas ok";
		http_response_code(302);
		echo json_encode($response);
		die();
	endif;
	$sql = "DELETE FROM $route WHERE id = :id";
	$rq = $connect->prepare($sql);
	$rq->execute([
		"id" => $_GET['id']
	]);
	$nb_hits = $rq->rowCount();

	if ($nb_hits > 0) :
		$response['message'] = "Supression sur $route";
		$response['code'] = 'ok';
		$response['nbhits'] = $nb_hits;
		$response['id'] = $_GET['id'];
	else:
		$response['message'] = "Errors server";
		$response['code'] = "pas ok";
		http_response_code(500);
	endif;
endif;


//METHOD PUT
if ($_SERVER['REQUEST_METHOD'] == 'PUT') :
	if (!isset($_GET['id'])) :
		$response['message'] = "il manque un id";
		$response['code'] = "pas ok";
		http_response_code(302);
		echo json_encode($response);
		die();
	endif;
	$data = json_decode(file_get_contents("php://input"), true);
	unset($data['token']);
	$sql = "UPDATE $route SET ";
	$args = [];
	$i = 0;
	foreach ($data as $field => $value):
		if ($i < count($data) - 1) :
			$sql .= "$field = :$field,";
			$args[$field] = $value;
		else :
			$sql .= "$field = :$field";
			$args[$field] = $value;
		endif;
		$i++;
	endforeach;
	$sql .= " WHERE id = {$_GET['id']}";
	$rq = $connect->prepare($sql);
	$rq->execute($args);
	$nb_hits = $rq->rowCount();

	if ($nb_hits > 0) :
		$response['message'] = "Modification sur $route";
		$response['code'] = 'ok';
		$response['nbhits'] = $nb_hits;
		$response['id'] = $GET['id'];
	else:
		$response['message'] = "Errors server";
		$response['code'] = "pas ok";
		http_response_code(500);
	endif;
endif;

echo json_encode($response);