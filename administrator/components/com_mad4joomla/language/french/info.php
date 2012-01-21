<?PHP 
	/**
	* @version $Id: mad4joomla 10041 2008-03-18 21:48:13Z fahrettinkutyol $
	* @package joomla
	* @copyright Copyright (C) 2008 Mad4Media. All rights reserved.
	* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
	* Joomla! is free software. This version may have been modified pursuant
	* to the GNU General Public License, and as distributed it includes or
	* is derivative of works licensed under the GNU General Public License or
	* other free or open source software licenses.
	* See COPYRIGHT.php for copyright notices and details.
	* @copyright (C) mad4media , www.mad4media.de
	*/
	
	/**  ENGLISH VERSION. NEEDS TO BE TRANSLATED */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' ); 
?>


<table width="944" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>
	
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="400" height="309" align="left" valign="top"><img src="components/com_mad4joomla/images/mad4media-3d.png" width="400" height="309"></td>
        <td align="left" valign="top"><h3>Mad4Joomla Mailforms V <?PHP echo M4J_VERSION_NO; ?></h3>
          Ce composant a été construit par Dipl. Informatiker (similaire au MSc) Fahrettin Kutyol pour Mad4Media. <br>
           Mad4Media développe des logiciels sous les aspects de la User Centered Design. Nos produits et projets sont conçus en pensant a l'utilisateur pour atteindre un maximum d'ergonomie (utilisabilité). En plus du codage en Java et PHP, nous proposons l'extension du développement individuel pour Joomla ou osCommerce à nos clients. Si vous êtes intéressés par nos services, vous pouvez nous contacter par notre page d'accueil: <a href="http://www.mad4media.de" target="_blank">Mad4Media</a><br>
          <br>
          <strong>License and Guarantee</strong><br>
          Mad4Joomla Mailforms est publié sous la licence GNU GPL. Il n'existe aucune garantie à la fonctionnalité ou l'exhaustivité du produit. Mad4Media n'assume aucune responsabilité pour les dommages causés par ce composant..<br>
          <br>
          <strong>Mise en oeuvre de composants Open Sources:</strong><br>
          <a href="http://www.dynarch.com/projects/calendar/" target="_blank">jsCalendar</a> - LGPL<br>
          Icons from <a href="http://www.dryicons.com" target="_blank">dryicons.com</a> - Creative Commons Attribution 3.0 License<br>
          <a href="http://www.dhtmlgoodies.com" target="_blank">Balloontip</a> - LGPL <br>
          <br>
          <br></td>
      </tr>
    </table>
	
	    <table width="100%" border="0" cellspacing="10" cellpadding="0">
          <tr>
            <td width="50%" align="left" valign="top"><h3>A propos de Mad4Joomla Mailforms</h3>
		      <p align="left">Mad4Joomla est un composant  facile a utliser pour créer des formulaires. <br />
Ce produit represente une plus grande facilité d'utilisation, classification en catégories, la possibilite de travailler avec des templates, textes d'intro à champs de formulaires, des techniques de routage à destination adresses email, intégration de contenu dans les pages des formulaires et une nouvelle  et très spéciale technique Captcha.Alors il est possible de construire d'énormes systemes de contacts complexes. Exemples: l'emploi, réservation etc. </p>
		      <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="100%" height="100%" align="left" valign="top"><h3>Accueil Mad4Joomla</h3>
                  <p>Ci-après, vous obtiendrez des informations explicites su site mad4media.
                    <br />
                    Toutes vos traductions sont les bienvenues. Vous pouvez télécharger le séparés sur les fichiers de langues a
                     l'adresse suivante. Envoyez-nous vos traductions. Nous allons les joindre au paquet du composant et les rendre publics.<br />
                    You can get to the project page through: <a href="http://www.mad4media.de/mad4joomla-mailforms.html" target="_blank">www.mad4media.de/mad4joomla-mailforms.html
                  </a></p>
                  <p>Les traducteurs obtiendront un lien Backlink (comme ci-dessous) à leur page d'accueil. <br />
                  <h3>Traductions</h3>
                    English, German by <a href="www.mad4media.de" target="_blank">mad4media</a><br />
                    French by <a href="www.thestamfordian.co.uk">chawki</a><br /> 
                  <br />
                    <br />  
                    <br />
                  </p>
                </td>
              </tr>
              </table>			<h3>&nbsp;</h3></td>
            <td width="52%" align="left" valign="top"><h3>Guide d'utilisation <br />
            </h3>
              <p><strong>Etape 1:</strong><br />
                Avez-vous besoin d'une catégorie? <br />
                 Par exemple, si vous voulez publier plusieurs offres d'emploi, il est conseillé de créer une catégorie intitulée "Des emplois". Comme l'ajout d'une catégorie,vous pouvez ajouter un contenu qui sera affiché à l'en-tête d'une page de catégorie. Si un formulaire n'a pas une propre adresse e-mail, l'adresse de la catégorie sera utilisé à la place. Si vous ne s'ajoutez pas d'adresse email à une catégorie, l' adresse e-mail principale (configuration) sera utilisée à la place.. 
                <br />
                <strong>Etape 2:</strong><br />
              Utiliser une ou plusieures template(s).<br />
              Vous pouvez entrer une brève description à la base de données. Ceci est pour reconnaitre la template. Il est important d'appliquer la largeur et l'hauteur des colonnes du tableau du formulaire. Dans la prochaine étape, vous avez besoin d'ajouter les champs du formulaire. Vous pouvez ajouterun texte d'aide à des champs qui seront affichés dans l'interface du site par la souris. <br />
              <br />
                <strong>Etape 3</strong><br />
                Ajout du Formulaire.<br />
                	Insérez un titre et assignez le a une catégorie. Si vous ne souhaitez pas attribuer une catégorie choisissez &quot;Sans categorie&quot;.<br />
                Puis, vous devez attribuer une template. Si vous n'ajoutez une adresse e-mail, le courrier sera envoyé à l'adresse des catégories. S'il n'y a pas d'adresse e-mail de catégories, l'adresse principale sera utilisée..
                <br />
                Sous &quot;captcha&quot; vous pouvez choisir si vous voulez utiliser un contrôle de sécurité pour éviter les abus et le spam bot. <br />
                Un texte d'intro ne sera montré qu'à la liste des catégories. <br />
                Le texte principal ne sera affiché que sur les pages des formulaires<br />
                Le texte d'intro d'email est pour vous pour votre rappel. Il n'est visible que sur l'email de reponse.
                <br />
                <br />
                <strong>Etape 4</strong><br />
                Assigner des liens.<br />
                À la page de résumé à l'arrière-plan vous pouvez lier les formes et les catégories à un menu. Vous pouvez également lier "tous les formulaires" et "Sans catégorie« à un menu.</p>
            </td>
          </tr>
      </table>      
      <p>&nbsp;</p></td>
  </tr>
</table>

