<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

spl_autoload_register(function($classname){
	if(file_exists("../controllers/".$classname.".php")){
		require("../controllers/".$classname.".php");
	}
	elseif(file_exists("../mappers/".$classname.".php")){
		require("../mappers/".$classname.".php");
	}
	elseif(file_exists("../entities/".$classname.".php")){
		require("../entities/".$classname.".php");
	}
});

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$config['db']['host']   = "localhost";
$config['db']['user']   = "root";
$config['db']['pass']   = "";
$config['db']['dbname'] = "meetups";

$app = new \Slim\App(["settings"=>$config]);

$app->add(function($request,$response,$next){
	$authorization_header = $request->getHeader("Authorization");

	if(empty($authorization_header) || ($authorization_header[0]!="duca")){
		
		header("HTTP/1.1 400 Access denied");
		exit;
	}

	$response = $next($request,$response);

	return $response;
});

$container = $app->getContainer();

$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO("mysql:host=".$db['host'].";dbname=".$db['dbname'],
        $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

$container["UserController"] = function($c){
	return new UserController($c->get("db"));
};

$container["GroupController"] = function($c){
	return new GroupController($c->get("db"));
};

$container["ContactRequestController"] = function($c){
	return new ContactRequestController($c->get("db"));
};

$container["ContactController"] = function($c){
	return new ContactController($c->get("db"));
};

$container["MeetingController"] = function($c){
	return new MeetingController($c->get("db"));
};

$container["GroupRequestController"] = function($c){
	return new GroupRequestController($c->get("db"));
};

$container["MeetingResponseController"] = function($c){
	return new MeetingResponseController($c->get("db"));
};

$container["TestController"] = function($c){
	return new TestController($c->get("db"));
};

$app->group("/test",function(){
	$this->post("/users",\TestController::class.":insertUsers");
	$this->delete("/users",\TestController::class.":deleteUsers");
	$this->post("/groups",\TestController::class.":insertGroups");
	$this->delete("/groups",\TestController::class.":deleteGroups");
	$this->post("/contacts",\TestController::class.":insertContacts");
	$this->delete("/contacts",\TestController::class.":deleteContacts");
	$this->post("/requests",\TestController::class.":insertRequests");
	$this->delete("/requests",\TestController::class.":deleteRequests");
	$this->delete("/all",\TestController::class.":deleteAll");
});

$app->group("/users", function(){
	$this->get("/{id}", \UserController::class.":getUserById");

	$this->get("/getByEmail/{email}", \UserController::class.":getUserByEmail");

	$this->get('/search/{query}', \UserController::class.":searchUsers");

	$this->post("",\UserController::class.":insertUser");

	$this->get("/{email}/checkExists",\UserController::class.":emailExists");

	$this->put("/{id}",\UserController::class.":updateUser");

	$this->get("/{id}/groups",\GroupController::class.":getGroupsOfUser");

	$this->post("/{id_user1}/contactRequests/{id_user2}",\ContactRequestController::class.":insertContactRequest");

	$this->delete("/{id_user1}/contactRequests/{id_user2}",\ContactRequestController::class.":deleteContactRequest");

	$this->get("/{id_user}/requests",\ContactRequestController::class.":getRequests");

	$this->post("/{id_user1}/contacts/{id_user2}",\ContactController::class.":insertContact");

	$this->get("/{id_user}/contacts",\ContactController::class.":getContactsOfUser");

	$this->get("/{id_user1}/relationship/{id_user2}",\UserController::class.":getRelationship");

	$this->delete("/{id_user1}/contacts/{id_user2}",\ContactController::class.":deleteContact");

});

$app->group("/groups", function(){
	$this->get("/{id}",\GroupController::class.":getGroup");

	$this->post("",\GroupController::class.":insertGroup");

	$this->post("/{id_group}/memberRequests/{id_user}",\GroupRequestController::class.":insertGroupRequest");

	$this->post("/{id_group}/addMember/{id_user}",\GroupController::class.":addMember");

	$this->get("/{id_group}/meetings",\MeetingController::class.":getMeetings");

	$this->post("/{id_group}/meetings",\MeetingController::class.":insertMeeting");

	$this->delete("/{id_group}",\GroupController::class.":deleteGroup");

});

$app->group("/meetings",function(){
	$this->delete("/{id_meeting}",\MeetingController::class.":deleteMeeting");

	$this->get("/{id_meeting}/responses",\MeetingResponseController::class.":getMeetingResponses");

	$this->post("/{id_meeting}/responses/{id_user}",\MeetingResponseController::class.":insertMeetingResponse");

});

$app->group("/responses",function(){
	$this->put("/{id_response}",\MeetingResponseController::class.":updateMeetingResponse");
});

$app->group("/memberRequests",function(){
	$this->delete("/{id_request}",\GroupRequestController::class.":deleteGroupRequest");
});

$app->group("/contactRequests",function(){
	
});


$app->run();