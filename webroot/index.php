<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__.'/vendor/autoload.php';
require_once 'modele.php';

$app = new Silex\Application();
$app['debug']=true;

$app->get('/contact', function () {
  $content ='<ul>';
  $amis=get_all_friends();
  foreach ($amis as $ami){
    $content.='<li>'.$ami['NOM'].'</li>';
  }
  $content.='</ul>';
  return $content;
});

$app->get('/api/', function () {
  $amis=get_all_friends_links();
  return json_encode($amis);
});

$app->get('/api/contact', function () {
  $amis=get_all_friends();
  return json_encode($amis);
});

$app->get('/api/contact/{id}', function($id) use ($app) {
  $ami = get_friend_by_id($id);
  if (!$ami) $app->abort(404, "Contact inexistant");
  else return json_encode($ami,JSON_PRETTY_PRINT);
});


$app->delete('/api/contact/{id}', function($id) use ($app) {
  $ami = get_friend_by_id($id);
  if (!$ami)
  $app->abort(404, "Contact inexistant");
  else {
    delete_friend_by_id($id);
    return json_encode($ami,JSON_PRETTY_PRINT);
  }
});

$app->before(function (Request $request) {
  if (0 === strpos($request->headers->get('Content-Type'), 'application/json'))
  {
    $data = json_decode($request->getContent(), true);
    $request->request->replace(is_array($data) ? $data : array());
  }
});


$app->post('/api/contact', function (Request $request) use ($app) {
  $data = $request->request->all();
  add_friends($data);
  return new Response(json_encode($data), 200, array('Content-Type' => 'application/json'));
});
