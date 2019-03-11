<?php
include "inc/main.site.php";
ob_end_clean();
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

header("Content-type: text/plain");
echo "#!/bin/bash\n";
echo "#UMVIRT LINUX FROM SCRATCH\n";
echo "#=========================\n";
echo "#Assistant command script\n";
echo "#=========================\n\n";
echo "#command: ".$v['name']."\n";
echo "#release: ".$release."\n\n";
echo command_script($v['commands'])."\n";

}
