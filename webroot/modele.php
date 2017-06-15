<?php
function get_all_friends_links()
{
  $connexion=connect_db();
  $amis=Array();
  $sql="SELECT * from CARNET";
  $data=$connexion->query($sql);
  while($pers=$data->fetch(PDO::FETCH_ASSOC))
  {
    $res=Array();
    $res['NOM'] = $pers['NOM'];
    $res['URL']=$_SERVER["REQUEST_SCHEME"].'://'.
    $_SERVER['HTTP_HOST'].
    $_SERVER['CONTEXT_PREFIX'].
    '/silex/api/contact/'.$pers['ID'];
    $amis[] = $res;
  }
  return $amis;
}

function get_friend_by_id($id)
{
  $connexion=connect_db();
  $sql="SELECT * from CARNET where ID=:id";
  $stmt=$connexion->prepare($sql);
  $stmt->bindParam(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_OBJ);
}

function delete_friend_by_id($id)
{
  $connexion=connect_db();
  $sql="Delete from CARNET where ID=:id";
  $stmt=$connexion->prepare($sql);
  $stmt->bindParam(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_OBJ);
}

function add_friends($data)
{
  $connexion=connect_db();
  $sql="INSERT INTO CARNET(NOM,PRENOM,NAISSANCE,VILLE) values (?,?,?,?)";
  $stmt=$connexion->prepare($sql);
  return $stmt->execute(array($data['NOM'], $data['PRENOM'], $data['NAISSANCE'],$data['VILLE']));
}
