<?php

include "inc/main.php";

$release=@addslashes($_REQUEST['release']);
$format=@addslashes($_REQUEST['format']);
$command=@addslashes($_REQUEST['command']);

$sql="select c.id, r.`release`, name, commands, info from commands c
left join releases r on c.release=r.id
where r.`release`=\"$release\" and c.name=\"$command\"";

//var_dump($sql);
$db->execute($sql);

$x=$db->dataset;

$pkgs=array();
foreach ($x as $k=>$v){


if($format=="json"){
$arr=array(
'name'=>$command,
'release'=>$release,
'commands'=>base64_encode($v['commands']),
'info'=>base64_encode($v['info'])
);
$result=json_encode($arr);
}

if($format=="xml"){

//header("Content-type: text/xml");
$dom = new DOMDocument('1.0', 'utf-8');
$dom->formatOutput=true;
$root = $dom->createElement('command');
$release = $dom->createElement('release',$release);
$name= $dom->createElement('name',$command);

$info = $dom->createElement('info',base64_encode($v['info']));
$commands = $dom->createElement('commands',base64_encode($v['commands']));


$root->appendChild($release);
$root->appendChild($name);
$root->appendChild($info);
$root->appendChild($commands);

$dom->appendChild($root);
$result=$dom->saveXML();
}

}


if($format=="xml"){
header("Content-type: text/xml");
echo $result;
}

if($format=="json"){
header("Content-type: text/plain");
echo $result;
}

