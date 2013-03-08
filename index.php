<link href="look.css" rel="stylesheet" type="text/css" ></link>

<script type="text/javascript">
function rechargerPage() {
var tournee = document.getElementById("tournee").value;location.href = "index.php?t_choix=" + tournee + "&action=commencer&envoi=email";}
function voir_tournee() {
var tournee = document.getElementById("tournee").value;location.href = "index.php?t_choix=" + tournee + "&action=voir";}
</script>
     
<?php
include "07655lj/connexion_mobile.php";

$date_heure = date("d/m/Y H:i");$heure = date("H:i");$heure_perm=explode(":",$heure);

// CHOIX DE LA TOURNEE
if (!$_GET['t_choix'])
{
echo "<table><tr class='titre'><td><b>Metro Paris</td></tr><tr class='titre'><td align=right><a href='chargement.php' style='margin-right:70'>[Chargement]</a>$date_heure</b></td></tr></table><br>";
echo '<font size=2>Tournée<br></font>';
echo '<select name="types_choix" style="width:60" id="tournee">';
echo '<option selected>';
$sql="SELECT DISTINCT T FROM paris WHERE Date='00-00-0000' and presta='$prestataire' ORDER BY T";
$req = mysql_query($sql);
while($row = mysql_fetch_row($req)) {
$t_choix = $row[0];;
print "\t\t<option value=$t_choix>".$t_choix."\n";
}
echo '</select><br><br>';

echo "<input type='Submit' value='Voir' onclick='voir_tournee()'></input> <input type='Submit' value='GO' onclick='rechargerPage()'></input>";

$date_pag=date('d-m-Y');
$query_pag = "SELECT paris_pagination FROM date WHERE jour_choix='$date_pag'";
$result_pag = mysql_query($query_pag);;
while($rang_pag = mysql_fetch_array($result_pag)){$pagination = $rang_pag['paris_pagination'];}

if ($pagination>24){$PAQUETS=100;}else {$PAQUETS=200;}

echo "<br><br><font color=red><b>**ATTENTION AUX QUANTITES MODIFIEES**<br><br>PAQUETS DE $PAQUETS EX</b></font><br><br>Contact : Michael Host <br>06 74 97 69 59<br><br>[".$_SERVER['HTTP_USER_AGENT']."]";
}

//VOIR LA TOURNEE
if ($_GET['t_choix'] AND $_GET['action']=='voir')
{
$T=$_GET['t_choix'];

$query_total = "SELECT SUM(Quantité_prévue) AS TOTAL FROM paris WHERE Date='00-00-0000' AND T='$T'";
$result_total = mysql_query($query_total);;
while($rang_total = mysql_fetch_array($result_total)){$TOTAL = $rang_total['TOTAL'];}

echo "<table><tr class='titre'><td><b>Tournée $T : $TOTAL ex à  livrer</b></td></tr><tr class='titre'><td align=right>$date_heure</b></td></tr></table><a href='index.php'>Retours</a><br><br>";

$x=0;echo "<table>";
$query = "SELECT num,Types, Site,Quantité_prévue,Horaires_prévues FROM paris WHERE Date='00-00-0000' AND T=$T ORDER by `Horaires_prévues`";
$result = mysql_query($query);;
while($rang = mysql_fetch_array($result)){; $x=$x+5;$xder=substr($x,-1,1);

$num = $rang['num'];;
$Types = $rang['Types'];;
$Site = $rang['Site'];;
$Horaires_prévues= $rang['Horaires_prévues'];;
$Quantité_prévue= $rang['Quantité_prévue'];;

if ($xder==5){echo "<tr class='point1'>";}else {echo "<tr class='point2'>";}
echo "<td><b>$Types $Site</b></a><br>$Horaires_prévues / $Quantité_prévue ex</td></tr>";
}}
echo "</table>";

//DEMARRAGE DE LA TOURNEE
if ($_GET['t_choix'] AND $_GET['action']=='commencer')
{
$today= date("d/m/Y");$heure = date("H:i");
$T=$_GET['t_choix'];

if ($_GET['envoi'])
{
$header .="";
$header .="From: <Paris@distripaq.com>\n";
$header .="MIME-Version: 1.0\n";
$header .= "Content-Type: multipart/alternative; boundary=HJ87687678\n";
$email .="je.philippe.lambotte@publications-metro.fr";
$fp .="\nThis is a multi-part message in MIME format.";
$fp .="\n--HJ87687678\nContent-Type: text/html; charset=\"iso-8859-1\"\n\n";
$fp .= "<font face=arial>Cookie mobile pour accès Paris<br>IP : $IP<br>Tournée : $T</font>";
$fp .= "\n--HJ87687678--\n end of the multi-part";
mail($email,"Cookie mobile Paris $T",$fp,$header);
}

$query_total = "SELECT SUM(Quantité_prévue) AS TOTAL FROM paris WHERE Date='00-00-0000' AND T=$T AND Date_PDA!='$today'";
$result_total = mysql_query($query_total);;
while($rang_total = mysql_fetch_array($result_total)){$TOTAL = $rang_total['TOTAL'];}

echo "<table><tr class='titre'><td><b>Paris / Tournée $T</td></tr><tr class='titre'><td align=right>$date_heure</b></td></tr></table>A livrer : $TOTAL<br><br>";

//AND Date_PDA!='$today' AND Horaires_prévues LIKE '$heure_perm[0]%' 
$x=0;echo "<table>";
if ($T=='7500'){$query = "SELECT num,Types, Site,Horaires_prévues,Codiff FROM paris WHERE (Date='00-00-0000' AND T=$T AND Date_PDA!='$today' AND Types='GDR') OR (Date='00-00-0000' AND T=$T AND Date_PDA!='$today' AND Types='PRINT') OR (Date='00-00-0000' AND T=$T AND Date_PDA!='$today') ORDER by `Horaires_prévues` ";}
else {
$query = "SELECT num,Types, Site,Horaires_prévues,Codiff FROM paris WHERE (Date='00-00-0000' AND T=$T AND Date_PDA!='$today' AND Types='GDR') OR (Date='00-00-0000' AND T=$T AND Date_PDA!='$today' AND Types='PRINT') OR (Date='00-00-0000' AND T=$T AND Date_PDA!='$today' AND Quantité_prévue!=0) ORDER by `Horaires_prévues` ";}
$result = mysql_query($query);;
while($rang = mysql_fetch_array($result)){; $x=$x+5;$xder=substr($x,-1,1);

$num = $rang['num'];;
$Types = $rang['Types'];;
$Site = $rang['Site'];;
$Horaires_prévues= $rang['Horaires_prévues'];;
$Codiff= $rang['Codiff'];;

if ($Codiff>0){$font="<font color=red>";}else {$font="<font color=blue>";}

if ($xder==5){echo "<tr class='point1'>";}else {echo "<tr class='point2'>";}
echo "<td><a href='mobile_record_point.php?num=$num&tournee=$T'>$font$Types/ $Site</a><br>$Horaires_prévues</font></td></tr>";
}

$query = "SELECT num,Types, Site,Horaires_prévues,Codiff FROM paris WHERE Date='00-00-0000' AND T=$T AND Date_PDA='$today' AND Quantité_prévue!=0 ORDER by `Horaires_prévues` ";
$result = mysql_query($query);;
while($rang = mysql_fetch_array($result)){; $x=$x+5;$xder=substr($x,-1,1);

$num = $rang['num'];;
$Types = $rang['Types'];;
$Site = $rang['Site'];;
$Horaires_prévues= $rang['Horaires_prévues'];;
$Codiff= $rang['Codiff'];;

if ($Codiff>0){$font="<font color=red>";}else {$font="<font color=blue>";}

if ($xder==5){echo "<tr class='point1'>";}else {echo "<tr class='point2'>";}
echo "<td><a href='mobile_record_point.php?num=$num&tournee=$T'>$font$Types/ $Site</a><br>$Horaires_prévues</font></td></tr>";
}

echo "</table>";

echo "<br><br><a href='index.php?t_choix=$T&action=commencer'>[ Actualiser ]</a> [ 0606060606 ]";
}
  ?>
