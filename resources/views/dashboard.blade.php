
@extends('layouts.back')

@section('content')

<?php

?>
<style>
	h5,b{
		width:100%;
	}
	h5{
		color:black;
	}
</style>
<div class="" style="padding-left:5%;padding-right:5%;padding-top:2%">
	<div class="row">
Bienvenue  {{ auth()->user()->name }} {{ auth()->user()->lastname }} sur votre nouvel outil CRM ! Nous sommes ravis de vous présenter les fonctionnalités actuellement disponibles pour optimiser votre gestion commerciale.<br>
<br>
<h5>Fonctionnalités disponibles :</h5>
<b>Mes statistiques</b>Accédez à une vue d'ensemble de vos performances commerciales. Consultez vos statistiques personnelles pour suivre vos objectifs.<br><br>
<b>Recherche Client</b>Trouvez facilement des informations sur un client spécifique. Vous pouvez accéder à :<br>
<ul style="width:100%">
<li>Ses informations : Détails de contact, historique, et autres données pertinentes.</li>
<li>Ses documents : Accès rapide aux fichiers liés, tels que offres et ouverture de compte.</li>
<li>Ses commandes : Historique des commandes en cours.</li>
<li>Ses statistiques.</li>
<li>Ses événements : Suivi des rendez-vous, et autres interactions importantes.</li>
<li>Ses contacts : Liste des personnes clés et des points de contact au sein de l'entreprise cliente.</li>
<li>Ses réclamations : Historique des réclamations et leur état de résolution.</li>
</ul>
<br><br>
<h5>Fonctionnalités à venir :</h5>Nous travaillons activement pour enrichir votre expérience utilisateur. Bientôt, vous pourrez profiter des nouvelles fonctionnalités suivantes :<br>
<br>
<b>Dépôt de documents</b>Soumettez facilement des documents tels que des offres de prix ou des demandes d'ouverture de compte directement depuis le CRM.<br>
<b>Tableau de bord personnalisé</b>Une vue d'ensemble de vos prochains rendez-vous, suivis de contacts et autres événements clés pour mieux gérer votre planning et priorités.<br>
<b>Historique des appels</b>Vous aurez la possibilité de voir si un client a contacté une personne au sein de l'entreprise Saamp.<br>
<b>Guide utilisateurs</b>Pour une prise en main optimale de votre CRM, consultez la page "Guide utilisateurs" où vous trouverez tous les guides nécessaires pour vous familiariser avec les différentes fonctionnalités.<br>
<br>
Merci de votre confiance et bonne exploration de votre nouveau CRM !<br><br>
	</div>
</div>

@endsection
