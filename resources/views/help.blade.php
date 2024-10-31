@extends('layouts.back')

@section('content')

<?php

?>
<style>
	h2{
		width:100%;
		margin-bottom:20px;
		color:black;

	}
	h5{
		color:black;
		margin-top:20px;
	}
	h4{
		color:black;
	}
	.help{
		padding:30px 30px 30px 30px;
		color:#4e504d;
	}
</style>


<div class="row">

	<!-- Content Column -->
	<div class="col-lg-12 mb-4">

		<!-- Project Card Example -->
		<div class="card shadow mb-4">
			<div class="card-header py-3">
				<h6 class="m-0 font-weight-bold text-primary">{{__('msg.User Guide')}}</h6>
			</div>
			<div class="card-body">
				<h2 style="margin-left:30px">Sommaire</h2>
				<ul style="margin-left:20px" class="myist">
					<li><a href="#prospect">Création d'un prospect</a></li>
					<li><a href="#client">Fiche client</a></li>
					<li><a href="#rendezvous">Créer un rendez vous</a></li>
					<li><a href="#contact">Créer une prise de contact</a></li>
					<li><a href="#offre">Créer une offre</a></li>
					<li><a href="#informations">Visualiser les informations financières</a></li>
					<li><a href="#reclamations">Gestion des réclamations</a></li>
					<li><a href="#fiche">Présentation de la fiche client</a></li>
				</ul>


				<div id="prospect" class="help">
				<h4>Création d'un prospect</h4>
				Différence entre un client et un prospect<br>
				Un <b>client</b> est un utilisateur qui possède un identifiant unique (ID) dans le système AS400. Ce dernier est créé exclusivement dans l’AS400. En revanche, un prospect est un utilisateur potentiel, créé directement dans le CRM, et ne dispose pas d'un ID AS400.<br>
				Un prospect devient client uniquement lorsque son dossier est complet et qu'il est créé dans l’AS400. Une fois cela fait, l'information remonte automatiquement dans le CRM, et le prospect est alors converti en client dans le système.<br>
				<h5>Comment créer un prospect ?</h5>
				<b>1.	Accéder à l'onglet "Clients" :</b><br>
					Connectez-vous à votre CRM et naviguez vers l'onglet "Clients".<br>
					<b>2.	Ajouter un prospect :</b><br>
					Cliquez sur le bouton "Ajouter un prospect".<br>
					<b>3.	Saisir l'adresse :</b><br>
					Dans le premier champ, entrez l'adresse du prospect. Le reste des informations d'adresse se remplira automatiquement.<br>
					<b>4.	Saisir le SIRET :</b><br>
					Entrez le numéro SIRET du prospect.<br>
					<b>5.	Compléter les informations complémentaires :</b><br>
					Ajoutez les autres informations comme le nom, le téléphone, l'email, etc.<br>
					<b>6.	Finaliser la création :</b><br>
					Cliquez sur "Ajouter" et félicitations, votre prospect est maintenant créé !<br>


				</div>
				<div id="client" class="help">
				<h4>Recherche d'un client</h4>
				<h5>Comment rechercher un client ?</h5>
				<b>1.	Accéder à l'onglet "Clients" :</b><br>
					Connectez-vous à votre CRM et allez dans l'onglet "Clients".<br>
					<b>2.	Utiliser les filtres de recherche :</b><br>
					Dans cet onglet, vous avez la possibilité de rechercher un client en utilisant plusieurs filtres : son nom, adresse, ville, département, etc.<br>
					<b>3.	Exemple de recherche :</b><br>
					Supposons que vous cherchez le client "Vernet Dray".<br>
					Il vous suffit de taper "Vernet Dray" dans le champ "Partie du nom".<br>
					Le client apparaîtra alors dans la liste des résultats.<br>
					<b>4.	Accéder à la fiche du client :</b><br>
					Une fois le client trouvé dans la liste, cliquez sur son nom pour accéder à sa fiche complète.<br>

				</div>
				<div id="rendezvous" class="help">
				<h4>Création d'un rendez-vous</h4>
				<h5>Comment créer un rendez-vous avec un client ?</h5>
				<b>1.	Accéder à l'onglet "Clients" :</b><br>
					Connectez-vous à votre CRM et allez dans l'onglet "Clients".<br>
					<b>2.	Rechercher votre client :</b><br>
					Recherchez le client avec qui vous avez un rendez-vous en utilisant les filtres de recherche (par nom, adresse, etc.).<br>
					Cliquez sur le nom du client pour accéder à sa fiche.<br>
					<b>3.	Ajouter un rendez-vous :</b><br>
					Une fois sur la fiche du client, vous verrez un bouton "Rendez-vous".<br>
					Cliquez sur ce bouton pour ouvrir le formulaire de création de rendez-vous.<br>
					<b>4.	Entrer les informations du rendez-vous :</b><br>
					Remplissez les différentes informations nécessaires (date, heure, lieu, etc.) dans le formulaire.<br>
					<b>5.	Valider le rendez-vous :</b><br>
					Cliquez sur "Ajouter" pour enregistrer le rendez-vous.<br>
					Le rendez-vous est maintenant validé !<br>
					<b>6.	Où retrouver le rendez-vous ?</b><br>
					Le rendez-vous apparaîtra dans l'encadré "Événements" de la fiche client.<br>
					Vous le retrouverez également dans votre agenda, accessible via l'onglet "Mon tableau de bord".<br>

				</div>
				<div id="contact" class="help">
				<h4>Enregistrement d'une prise de contact</h4>
				<h5>Comment enregistrer une prise de contact avec un client ?</h5>
				<b>1.	Accéder à l'onglet "Clients" :</b><br>
					Connectez-vous à votre CRM et allez dans l'onglet "Clients".<br>
					<b>2.	Rechercher votre client :</b><br>
					Recherchez le client avec lequel vous avez eu une prise de contact en utilisant les filtres de recherche (par nom, adresse, etc.).<br>
					Cliquez sur le nom du client pour accéder à sa fiche.<br>
					<b>3.	Ajouter une prise de contact :</b><br>
					Une fois sur la fiche du client, repérez le bouton "Prise de contact".<br>
					Cliquez sur ce bouton pour enregistrer les détails de votre interaction.<br>
					<b>4.	Valider la prise de contact :</b><br>
					Après avoir rempli les informations nécessaires, cliquez sur "Ajouter" pour valider la prise de contact.<br>
					<b>5.	Où retrouver la prise de contact ?</b><br>
					La prise de contact sera uniquement visible dans l'onglet "Prises de contact" de la fiche client.<br>
					Cet onglet résume toutes vos interactions avec ce client.<br>

				</div>
				<div id="offre" class="help">
				<h4>Création d'une offre</h4>
				<h5>Comment créer une offre pour un client ?</h5>
				<b>1.	Accéder à l'onglet "Clients" :</b><br>
					Connectez-vous à votre CRM et rendez-vous dans l'onglet "Clients".<br>
					<b>2.	Rechercher votre client :</b><br>
					Recherchez le client pour lequel vous souhaitez créer une offre en utilisant les filtres de recherche (par nom, adresse, etc.).<br>
					Cliquez sur le nom du client pour accéder à sa fiche.<br>
					<b>3.	Ajouter une offre :</b><br>
					Une fois sur la fiche du client, cliquez sur le bouton "Offres". Vous verrez alors la liste des différentes offres existantes pour ce client.<br>
					<b>4.	Créer une nouvelle offre :</b><br>
					En haut à droite de l'écran, cliquez sur le bouton "Ajouter".<br>
					Remplissez les champs requis : nom de l'offre, date, produit/service concerné, description, et éventuellement, une pièce jointe.<br>
					<b>5.	Valider l'offre :</b><br>
					Cliquez sur "Ajouter" pour enregistrer l'offre.<br>
					L'offre sera alors ajoutée au dossier du client et apparaîtra dans la liste des offres.<br>


				</div>
				<div id="informations" class="help">
				<h4>Visualisation des informations financières</h4>
				<h5>Comment visualiser les informations financières d'un client ?</h5>
				<b>1.	Accéder à l'onglet "Clients" :</b><br>
					Connectez-vous à votre CRM et allez dans l'onglet "Clients".<br>
					<b>2.	Rechercher votre client :</b><br>
					Recherchez le client dont vous souhaitez consulter les informations financières en utilisant les filtres de recherche (par nom, adresse, etc.).<br>
					Cliquez sur le nom du client pour accéder à sa fiche.<br>
					<b>3.	Accéder aux informations financières :</b><br>
					Une fois sur la fiche du client, cliquez sur le bouton "Finance".<br>
					Vous serez redirigé vers un onglet dédié qui résume différents points financiers du client, tels que le solde des comptes, les transactions récentes, les échéances, et d'autres informations pertinentes.<br>

				</div>

				<div id="reclamations" class="help">
					<h4>Gestion des réclamations</h4>
					<h5>Comment gérer les réclamations d'un client ?</h5>
					<b>1.	Accéder à l'onglet "Clients" :</b><br>
					Connectez-vous à votre CRM et allez dans l'onglet "Clients".<br>
					<b>2.	Rechercher votre client :</b><br>
					Recherchez le client pour lequel vous souhaitez consulter ou ajouter une réclamation en utilisant les filtres de recherche (par nom, adresse, etc.).<br>
					Cliquez sur le nom du client pour accéder à sa fiche.<br>
					<b>3.	Visualiser les réclamations :</b><br>
					Sur la fiche du client, repérez l'encart "Réclamations". Cet encart résume toutes les réclamations existantes pour ce client.><br>
					<b>4.	Ajouter une réclamation :</b><br>
					Pour ajouter une nouvelle réclamation, cliquez sur le bouton "Ajouter" dans l'encart "Réclamations".<br>
					Remplissez les informations nécessaires concernant la réclamation (description du problème, date, etc.).<br>
					Une fois la réclamation créée, un email sera automatiquement envoyé au directeur qualité et à l'agence concernée.<br>
					<b>5.	Suivi et clôture de la réclamation :</b><br>
					Seul le directeur qualité a la possibilité de clôturer une réclamation après vérification et résolution du problème.<br>

				</div>


				<div id="fiche" class="help">
					<h4>Explication de la fiche client</h4>
					<h5>Présentation de la fiche client</h5>
					La fiche client dans votre CRM est un tableau de bord complet regroupant toutes les informations essentielles concernant un client. Voici un aperçu des principales sections et fonctionnalités disponibles :<br>
					<b>1.	Informations générales :</b><br>
					Cette section affiche les informations de base sur le client, telles que le nom, l'adresse, le numéro de téléphone, et l'email.<br>
					<b>2.	Statistiques :</b><br>
					Vous y trouverez les statistiques sur 4ans pour voir l’évolution de ce client...<br>
					<b>3.	Commandes en cours :</b><br>
					Cette section liste toutes les commandes en cours pour ce client…<br>
					<b>4.	Réclamations :</b><br>
					L'encart "Réclamations" résume toutes les réclamations actives ou passées pour ce client. Il permet également d'ajouter de nouvelles réclamations via le bouton "Ajouter".<br>
					<b>5.	Rendez-vous :</b><br>
					Vous pouvez visualiser les prochains rendez-vous planifiés avec le client, ainsi que l'historique des anciens rendez-vous. Cela permet un suivi précis de toutes les interactions en face à face.<br>
					<b>6.	Contacts du client :</b><br>
					Cette section recense les différents contacts associés à ce client, avec leurs coordonnées, permettant de savoir rapidement qui contacter pour chaque besoin spécifique.<br>
					<b>7.	Dossier du client :</b><br>
					Vous pouvez consulter le dossier d'ouverture de compte, qui contient tous les documents administratifs relatifs au client. Il est également possible de mettre à jour ces documents si nécessaire.<br>
					<b>8.	Offres de prix :</b><br>
					Cette section présente toutes les offres de prix proposées au client, avec la possibilité de créer de nouvelles offres. Chaque offre est détaillée avec le nom, la date, le produit/service concerné, et la description.<br>

				</div>

			</div>
		</div>



	</div>

</div>

@endsection