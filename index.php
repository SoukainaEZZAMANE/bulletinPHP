<?php

include('config.php');


//_____________________faireDate________________________

//Recoit une date du genre: 2007-07-06
	function faireDate($pDate)
			{
				$jour = substr($pDate,8);
				$mois = substr($pDate,5);
				$annee = substr($pDate,0,4);
				$tabMois = array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
				$nvlDate = $jour." ".$tabMois[$mois-1]." ".$annee;
				return $nvlDate;
			}

//======================= AFFICHAGE DES TABLEAUX ========================
echo "<meta charset='utf-8'/>";

print "<TABLE><TR><TD valign=\"top\">";

//___________________Tableau des cours______________________

print "<U>Liste des cours</U><BR><BR>";
print "<TABLE BORDER=\"1\">";

$requete = $db->query("SELECT id, nom FROM cours");

	while($objet = $requete->fetch())
	{
		print "<TR>";
		print "<TD><A href=\"?afficherCours=".$objet['id']."&nom=".$objet['nom']."\">".$objet['nom']."</A>";
	}
print "</TABLE>";

print "<TD>";

//___________________Tableau des éleves______________________

print "<U>Liste des éleves</U><BR><BR>";
print "<TABLE BORDER=\"1\">";
$requete = $db->query("SELECT id, nom, prenom FROM eleve");

	while($objet = $requete->fetch())
	{
		$prenom = ($objet['prenom'])? "(".$objet['prenom'].")":"";
		print "<TR>";
		print "<TD><A href=\"?afficherEleve=".$objet['id']."&nom=".$objet['nom']."&prenom=".$objet['prenom']."\">".$objet['nom']."$prenom</A>";
	}
print "</TABLE>";
print "</TABLE>";

//======================= AFFICHAGE DES NOTES ========================

//____________________________Un lien a été seléctionné___________________________

if (!empty($_GET))
	{

		//___________________________Affichage des notes par cours___________________________

		if (isset($_GET['afficherCours']))
		{
			//Copie de variables GET pour faciliter la lecture:
			$afficher = $_GET['afficherCours'];
			$nom = $_GET['nom'];

			//__________Présentation du cours

			print "<BR>Notes du cours : $nom<BR><BR>";

			//__________Requéte qui récupére toutes les notes du cours mentionné par la
			//variable $_GET

			$requete = $db->query("SELECT e.nom, e.prenom, n.date, n.note 
			FROM eleve e, cours c, note n 
			WHERE e.id=n.id_eleve AND n.id_cours=c.id AND c.id=$afficher ORDER BY n.date");

			//Le résultat de la requéte est placé dans un tableau tel que:
			//$tableau['Toto'][20070610]=12;
			//$tableau['Toto'][20070520]=13;
			//$tableau['Titi'][20070610]=15;
			//$tableau['Titi'][20070520]=14;
			while($objet = $requete->fetch())
			{
				$prenom = ($objet['prenom'])?" (".$objet['prenom'].")":"";
				$nom = $objet['nom'].$prenom;
				$tableau [$nom][$objet['date']] = $objet['note'];
			}

			//__________Affichage du résultat:

			print "<TABLE border=\"1\">";

			//----Premiére ligne du tableau avec en-tétes de colonnes:

			print "<TR><TD>Nom et prénom";
			foreach($tableau[$nom] as $date => $note)
			{
				print "<TD>".faireDate($date); //Affichage des dates
			}
				print "<TD>Moyenne";

				//----Lignes suivantes avec nom de l'éléve puis ses notes

			foreach($tableau as $nom => $tabNotes)
			{
					print "<TR><TD>$nom";
					$somme = 0; //Sert à faire la moyenne à la fin de chaque ligne
					foreach($tabNotes as $note)
					{
						print "<TD>$note";
						$somme += $note;
					}
					print "<TD>";
					//__________Calcul et afficahge de la moyenne:
					printf ("%.2f", $somme/count($tabNotes)); //Affichage formaté à  deux décimales aprés la virgule
			}
			print "</TABLE>";
		}

		//___________________________Affichage des notes par éléve___________________________

		elseif (isset($_GET['afficherEleve']))
		{
			//Copie de variables GET pour faciliter la lecture:
			$idEleve = $_GET['afficherEleve'];
			$nom = $_GET['nom'];
			$prenom = ($_GET['prenom'])?" (".$_GET['prenom'].")":"";

			//__________Présentation du cours

			print "<BR><U>Notes de $nom$prenom</U><BR><BR>";

			//__________RequÃªte qui récupére toutes les notes du cours mentionné par la
			//variable $_GET
			$requete = $db->query("SELECT c.nom, n.date, n.note FROM eleve e, cours c, note n WHERE e.id=n.id_eleve AND n.id_cours=c.id AND e.id=$idEleve ORDER BY n.date");

			//Le résultat de la requéte est placé dans un tableau tel que:
			//$tableau['Cours1'][20070610]=12;
			//$tableau['Cours1'][20070520]=13;
			//$tableau['Cours2'][20070610]=15;
			//$tableau['Cours2'][20070520]=14;
			while($objet = $requete->fetch())
			{
			$cours = $objet['nom'];
			$tableau [$cours][$objet['date']] = $objet['note'];
			}

			//__________Affichage du résultat:

			//----PremiÃ¨re ligne du tableau avec en-tétes de colonnes:

			print "<TABLE><TR>";
			foreach($tableau as $cours => $sstableau)
			{
			print "<TD valign=\"top\">";
			print "<TABLE border=\"1\">";
			print "<TR><TD>$cours<TD>Note";

			//----Lignes suivantes avec date et note:

			foreach($sstableau as $date => $note)
			{
			print "<TR><TD>".faireDate($date);
			print "<TD>$note";
			}
			print "</TABLE>";
			}
			print "</TABLE>";
		}
	}
?>