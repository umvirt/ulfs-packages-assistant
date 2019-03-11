<?php

include "inc/main.site.php";

echo "<h1>UmVirt LFS Assistant</h1>";
//var_dump($_SERVER);
echo "<h2>About</h2>";
echo "<p>Every GNU/Linux distro is provide helper software. Umvirt LFS is not exception.</p>";
echo "<p>Main purpose of \"UmVirt LFS Assistant\" service is running various comands inside operating system. Commands stored not in application or script file but in remote database.";
echo "<p>Runing scripts from Internet server is not good idea. So you can deploy \"UmVirt LFS Assistant\" service in local network machine to prevent hacker atacks and downtimes.</p>";
$release=@addslashes($_REQUEST['release']);


$format=@addslashes($_REQUEST['format']);

$sql="select id, `release` from releases";

$db->execute($sql);

$x=$db->dataset;

$releases=array();




foreach ($x as $k=>$v){

$s=$v['release'];
if($release==$v['release']){
$s="<b>".$v['release']."</b>";
}

$releases[]="<a href=".dirname($_SERVER['SCRIPT_NAME'])."/".$v['release'].">".$s."</a>";
}


if(!$release){

if($format=="json"){
$result=array();
ob_end_clean();
foreach ($x as $k=>$v){
$result['releases'][]=$v['release'];
}
header("Content-type: text/plain");
echo json_encode($result);
exit;
}

if($format=="xml"){
$dom = new DOMDocument('1.0', 'utf-8');
$root = $dom->createElement('ulfspackages');
$releases_element = $dom->createElement('releases');
$result=array();
ob_end_clean();
foreach ($x as $k=>$v){
$release_element = $dom->createElement('release', $v['release']);
$releases_element->appendChild($release_element);
}
$root->appendChild($releases_element);
$dom->appendChild($root);
header("Content-type: text/xml");
echo $dom->saveXML();
exit;
}
echo "<h2>Commands list</h2>";
echo "Please select release to get commands list: ".join ($releases,', ');

echo "<h2>Assistant installation</h2>";
echo "<ol>";
echo "<li>According to Chapter 24 of ULFS book add XORG_CONFIG & XORG_PREFIX environment variables</li>";
echo "<li>In file /etc/profile add followed strings:<br>
<tt>export ASSISTANT_URL=https://umvirt.com/linux/assistant/<br>
export PACKAGES_URL=https://umvirt.com/linux/packages/</tt>
</li>";
echo "<li>Relogin</li>";
echo "<li>Download chimp script:<br>
<tt>wget --no-check-cerificate https://umvirt.com/linux/assistant/chimp -O /bin/chimp</tt>
</li>";
echo "<li>Make it executable:<br>
<tt>chmod +x /bin/chimp</tt></li>";

echo "<li>Run it:<br>
<tt>chimp</tt><br>
If everything is ok next message should appear:<br>
<tt>CHIMP IS READY TO ACCEPT COMMANDS</tt>";
echo "</ol>";


echo "<h2>Usage</h2>

<h3>Install package</h3>

<p>Package installation become more simple (mc for example):<br>
<tt>chimp install mc</tt></p>

<h3>Running commands from assistant service</h3>
<p>Running comands is also simple (check_environment for example):<br>
<tt>chimp check_environment</tt></p>



";



/*echo "<h2>See also</h2>";
echo "<ol>
<li><a href=\"howitworks.html\">How it works?</a>
<li><a href=\"api.html\">Application Programming interface (API)</a>
</ol>";
*/

echo  <<<EOL
<h3>Keep this service working</h3>
<p>We rent a physical servers in order to host our services. We need money to keep they working.</p> 
<p>If you like to use our services please donate us some money as many as you wish.</p>
<ul>
<li><b>BTC:</b> 3JegVULRiijjcxDwx1YbKCk8mWvxANTGGo [ <a href="qr/3JegVULRiijjcxDwx1YbKCk8mWvxANTGGo.png">QR-code</a> | <a href="https://www.blockchain.com/btc/address/3JegVULRiijjcxDwx1YbKCk8mWvxANTGGo" target="_blank">Check transaction</a> ]
<li><b>BCH:</b> qr9c2n9ujxmyh8knxaz6rv4eh73pz3m5ruk7wgunxk [ <a href="qr/qr9c2n9ujxmyh8knxaz6rv4eh73pz3m5ruk7wgunxk.png">QR-code</a> | <a href="https://blockdozer.com/address/qr9c2n9ujxmyh8knxaz6rv4eh73pz3m5ruk7wgunxk" target="_blank">Check transaction</a> ]
<li><b>LTC:</b> LMx39BwUwaYZeeLn7DyYoGvuRhXFx5i3SW [ <a href="qr/LMx39BwUwaYZeeLn7DyYoGvuRhXFx5i3SW.png">QR-code</a> | <a href="https://bchain.info/LTC/addr/LMx39BwUwaYZeeLn7DyYoGvuRhXFx5i3SW" target="_blank">Check transaction</a> ]
<li><b>Monero:</b> 87yTheNHaxNdmSBcHJZFfR4a9WT8CKwTJMDe7L1JaEFCf3eE2mN2GpzAiDqn9YAasgHymTwk4KJ4VToZkvnMmuFDLBF3FUD [ <a href="qr/87yTheNHaxNdmSBcHJZFfR4a9WT8CKwTJMDe7L1JaEFCf3eE2mN2GpzAiDqn9YAasgHymTwk4KJ4VToZkvnMmuFDLBF3FUD
.png">QR-code</a> ]
</ul>
</p>
EOL;





}else{
echo "Current releases: ".join ($releases,', ');

//----


$sql="select c.id,c.name, c.info from commands c
inner join releases r on r.id=c.`release`
where r.`release`=\"".$release."\"";
//echo $sql;
$db->execute($sql);

$x=$db->dataset;

if($format=="json"){
$result=array();
$result['release']=$release;
ob_end_clean();
foreach ($x as $k=>$v){
$result['packages'][]=$v['code'];
}
header("Content-type: text/plain");
echo json_encode($result);
exit;
}

if($format=="xml"){
$dom = new DOMDocument('1.0', 'utf-8');
$root = $dom->createElement('packages');
$release_element = $dom->createElement('release',$release);
$root->appendChild($release_element);
$result=array();
ob_end_clean();
$packages_element = $dom->createElement('packages');
foreach ($x as $k=>$v){
$package_element = $dom->createElement('package', $v['code']);
$packages_element->appendChild($package_element);
}
$root->appendChild($packages_element);
$dom->appendChild($root);
header("Content-type: text/xml");
echo $dom->saveXML();
exit;
}


$pkgs=array();
foreach ($x as $k=>$v){

$s=$v['name'];

$pkgs[]="<tr><td><a href=".dirname($_SERVER['SCRIPT_NAME'])."/$release/".$v['name'].">".$s."</a></td><td>".$v['info']."</td></tr>";
}

echo "<h2>Packages(".count($x).")</h2>";
echo "Available packages: <table>".join ($pkgs)."</table>";

}

include "inc/template.php";
