<?php

$var1 = "10";
$var2 = "12";
$var3 = "14";
$var4 = "12";

$tab = array();

array_push($tab,$var3);
array_push($tab,$var2);
array_push($tab,$var1);
array_push($tab,$var4);

$return="0";
$reponseC = "";

$comand = "./jungle ".$var1." ".$var2." ".$var3." ".$var4;
$reponseC = system($comand, $return);

echo $reponseC;

?>