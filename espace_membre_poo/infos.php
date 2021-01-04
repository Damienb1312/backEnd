<?php

// 4 fichiers

// class Utilisateur.php (entité)-> table Utilisateurs
// -> role est d'instancier des objets de type utilisateur

// Class UtilisateursManager.php(manager):
// CRUD->Créer,Read,Update et Delete
// -> on va implémenter les 4 method CRUD

// Inscription.php
// ->pour s'inscrire

// Administration.php
// -> pour ajouter ou supprimer un utilisateur

//class Utilisateur
// __construct(valeur:array):void
// setId
// setNom(nom:string):void
// setPrenom(prenom:string):void
// setTel(tel:string):void
// setMail(mail:string):void

// getId:int
// getNom:string
// getPrenom:string
// getTel:string
// GetMail:string
// hydrater():void
// IsUserValide():bool Const

//class UtilisateursManager qui va gérer la class Utilisateur
//Inserer(utilisateur:Utilisateurs)
//mettreAjour(utilisateur:Utilisateurs)
// getListeUtilisateurs()
// getUtilisateur(id:int)
// supprimer(id:int)
// count(id:int)