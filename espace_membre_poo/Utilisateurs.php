<?php

    class Utilisateurs{

        //variable qui gérer les erreurs sous forme de tableau

        private     $erreurs=[],
                    $id,
                    $nom,
                    $prenom,
                    $tel,
                    $mail;

        //constantes qui vont gérer les erreurs

        //si caractères invalides
        const   NOM_INVALIDE    = 1,
                PRENOM_INVALIDE = 2,
                MAIL_INVALIDE   = 3;

        // nos method
        //assigner un tableau vide à la fonction donnees
        // les attributs vont etre initialiser à l'aide du constructeur
        //le constructeur est exécuter dés la création d'un objet

        public function __construct($donnees = []){

            //voir si les parametres de la variable ne sont pas vides et
            //on va appeler la function hydrater pour appeler les setters

            if(!empty($donnees)){
                $this->hydrater($donnees);
            }

        }

        //hydrater un objet : lui apporter ce dont il a besoin pour fonctionner
        // revient à lui fournir des données correspondant à ses attributs pour qu'il assigne des valeurs souhaitées
        // à ces derniers.= assignement des valeurs aux attributs des objets instanciés

        public function hydrater($donnees){

            foreach ($donnees as $attribut => $valeur) {
                //pour appeler les setters adéquats set+attribut avec la 1ere lettre en majuscule
                $methodSetters = 'set' . ucfirst($attribut);
                // on va appeler cette method
                $this->$methodSetters($valeur);
            }

        }

        //SETTERS

        public function setId($id){

            //verifier si il est vide ou non avec une condition

            if(!empty($id)){

                // (int) sert à forcer le typage

                $this->id = (int) $id;
            }

        }

        public function setNom($nom){

            if (!is_string($nom) || empty($nom)) {

                //const n'appartiennent pas à l'objet mais à la class
                $this->erreurs[] = self::PRENOM_INVALIDE;
            }else{
                $this->nom = $nom;
            }

        }

        public function setPrenom($prenom){

            if (!is_string($prenom) || empty($prenom)) {

                //const n'appartiennent pas à l'objet mais à la class
                $this->erreurs[] = self::NOM_INVALIDE;
            }else{
                $this->prenom = $prenom;
            }

        }

        public function setTel($tel){

            $this->tel = $tel;

        }

        public function setMail($mail){

            // verification de la syntaxe selon RFC822
            if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                $this->mail = $mail;
            }else{
                $this->erreurs[] = self::MAIL_INVALIDE;
            }

        }

        // GETTERS

        public function getId(){
            return $this->id;
        }

        public function getNom(){
            return $this->nom;
        }

        public function getPrenom(){
            return $this->prenom;
        }

        public function getTel(){
            return $this->tel;
        }

        public function getMail(){
            return $this->mail;
        }

        //Pour récupérer les erreurs

        public function getErreurs(){
            return $this->erreurs;
        }

        // function booleenne pour tester si un utilisateur est valide ou non

        public function isUserValide(){
            //va retourner si 'nom' 'prenom' et 'mail' de l'objet ne sont pas vides 
            return !(empty($this->nom) || empty($this->prenom) || empty($this->mail));
        }



    }

?>