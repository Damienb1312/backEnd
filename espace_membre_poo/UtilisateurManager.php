<?php

// la class manager interagit avec la base de données

class UtilisateurManager{

private $bddPDO;

//le constructeur va permettre de se connecter à la base de données

public function __construct(PDO $bddPDO){

    $this->bddPDO = $bddPDO;

}

//recuperer les informations du formulaire objet Utilisateurs en paramètre

public function inserer(Utilisateurs $utilisateur){

    $requete = $this->bddPDO->prepare('INSERT INTO espace_membre_poo.utilisateurs(nom,prenom,tel,mail) 
                                        VALUES(:nom,:prenom,:tel,:mail)');
    //correspondance du marqueur 'nom' au résultat du getter qu'on a implémenté
    //dans la class Utilisateurs qui est en paramètre dans la fonction 'inserer'
    $requete->bindValue(':nom', $utilisateur->getNom());

    $requete->bindValue(':prenom', $utilisateur->getPrenom());
    
    $requete->bindValue(':tel', $utilisateur->getTel());
    
    $requete->bindValue(':mail', $utilisateur->getMail());

    //execution de la requete

    $requete->execute();

}

//interroge la table utilisateur de la bdd et récupère la liste de TOUS les utilisateurs

public function getListeUtilisateurs(){

    $requete = $this->bddPDO->query('SELECT * FROM espace_membre_poo.utilisateurs ORDER BY nom ASC');

    // recupération avec le mode fetch
    //on veut retourner une nouvelle instance de la class demandée
    //FETCH_CLASS  serait une instance de notre class Utilisateurs
    //FETCH_PROPS_LATE-> indique que le constructeur de la class doit etre appelé avant que les
    //propriétés soient assignés par le PDO. Dans le cas contraire le constructeur écraserait
    // les valeurs qui ont été récupéré par la requete
    // + la class Utilisateurs attachée à notre table utilisateurs

    $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Utilisateurs');

    //recupération dans un tableau des lignes de la table utilisateurs

    $tousLesUtilisateurs = $requete->fetchAll();

    //fermeture de la requete

    $requete->closeCursor();

    //on retourne notre liste

    return $tousLesUtilisateurs;

}

//on récupère UN SEUL utilisateur
// avec un parametre ($id) on va préparer la requete
public function getUtilisateur($id){

    $requete = $this->bddPDO->prepare('SELECT * FROM espace_membre_poo.utilisateurs WHERE id = :id');

    $requete->bindValue(':id', (int) $id, PDO::PARAM_INT);

    $requete->execute();

    //mode de récupération

    $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Utilisateurs');

    $utilisateur = $requete->fetch();

    return $utilisateur;

}

//implémenter la method qui va permettre de mettre à jour un utilisateur

public function mettreAjour(Utilisateurs $utilisateur){
    // le where pour préciser pour quel utilisateur
    $requete = $this->bddPDO->prepare('UPDATE espace_membre_poo.utilisateurs 
                                    SET nom=:nom,prenom=:prenom,tel=:tel,mail=:mail
                                    WHERE id=:id');

    $requete->bindValue(':id', $utilisateur->getId(), PDO::PARAM_INT);
    
    $requete->bindValue(':nom', $utilisateur->getNom());
    $requete->bindValue(':prenom', $utilisateur->getPrenom());
    $requete->bindValue(':tel', $utilisateur->getTel());
    $requete->bindValue(':mail', $utilisateur->getMail());

    $requete->execute();

}

// exec permet d'exécuter directement la requêtre entre parenthèses
// dans la requete -> concaténation avec l'$id en paramètre
public function supprimer($id){

    $this->bddPDO->exec('DELETE FROM espace_membre_poo.utilisateurs
                        WHERE id =' . (int)$id);

    

}


































}
































?>