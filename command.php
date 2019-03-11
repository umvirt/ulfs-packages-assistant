<?php

include "inc/main.site.php";

echo "<h1>UmVirt LFS Assistant command info</h1>";


$release=@addslashes($_REQUEST['release']);
$command=@addslashes($_REQUEST['command']);

$sql="select c.id, r.`release`, name, commands, info from commands c
left join releases r on c.release=r.id
where r.`release`=\"$release\" and c.name=\"$command\"";

//var_dump($sql);
$db->execute($sql);

$x=$db->dataset;

$pkgs=array();
foreach ($x as $k=>$v){

echo "<h2>".$v['name']."</h2>";
echo "Info: ".$v['info']."<br>";

echo "Commands: 
<br><pre>".configuration_script($v['commands'])."</pre><br>";

}
include "inc/template.php";

