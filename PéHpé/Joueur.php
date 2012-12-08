<?php
	/*----------------------------------------------------------------JOUEUR--------------------------------------------------------------------------------*/
	
	class Joueur{
		
		private $nom;//le nom definitif affecté à ce joueurs sur le serveur comme le client (0,1,2,3)
		private $socket;
		private $cardsHide = array();
		private $cardsKnown = array();
		private $actualCard = 0;
		
		public function __construct($sock,$nom){
			$this->socket = $sock;
			$this->nom = $nom;
		}
		
		/****************************************SETTEUR / GETTEUR******************************************************/
		
		public function getNom(){
			return $this->nom;
		}
		
		/*
		* Setteur carte
		*/
		public function setCardsHide($carte = array()){
			$this->cardsHide = $carte;
		}
		
		/*
		* Getteur carte
		*/
		public function getCardsHide(){
			return $this->cardsHide;
		}
		
		/*
		* Setteur carte connnu
		*/
		public function setCardsKnown($cardKnown){
			if(is_array($cardKnown)){
				for($i=0;$i>count($cardKnown);$i++){
					array_push($this->cardsKnown,$cardKnown);
				}
			}else array_push($this->cardsKnown,$cardKnown);
		}
		
			/*
		* Getteur carte connnu
		*/
		public function getCardsKnown(){
			$cards = array();
			$cards =  $this->cardsKnown;
			
			/*on supprime les carte retourné*/
			$this->cardsKnown = array();
			return $cards;
		}
		
		/*
		* Getteur socket
		*/
		public function getSocket(){
			return $this->socket;
		}
		
		/*
		* getCarteDecouverte
		* retourne le nombre de carte decouverte
		*/
		public function getNbDiscoverCard(){
			return count($this->cardsKnown);
		}
		
		public function getNbHideCard(){
			return count($this->cardsHide);
		}
		
		/**
		* Met à jours la carte actuelement devoilé
		* param : la carte qui vient d'etre tiré
		*/
		public function setActualCard($card){
			$this->actualCard = $card;
		}
		
		/**
		* Renvoie la carte actuelement devoilé
		* retour : la carte actuelement devoilé 
		*/
		public function getActualCard(){
			return $this->actualCard;
		}
		
		/*
		* renvoie le nb total de carte
		*/
		public function getAllCards(){
			return $this->getNbDiscoverCard() + $this->getNbHideCard();
			
		}
		
		/*
		* met à jours les cartes des joueurs;
		*/
		public function setAfterCatch($cardKnown){
			$this->setCardsHide(array_merge($this->getCardsKnown(),$cardKnown));
		}
		
		/*****************************************************************************************************/
		
		/**
		* Tire une Carte et met a jours le paquet des cartes connu, supprime la carte tiré du paquet caché
		* return Renvoie la carte tiré
		*/
		public function drawCard(){
			$carte="";
			/*si on encore des carte dans notre paquet de carte caché*/
			if(!empty($this->cards)){
				/*on met la premiere carte caché dans le paquet de carte devoilé*/
				$carte = array_shift($this->cards);
				array_push($this->cardsKnown,$carte);
				$this->setActualCard($carte);
			}
		}
		
		/*
		* renvoie le message à envoyer
		* Param : le joueurActif
		*/
		public function getMessageInit($joueurActif=0){
			$message="";
			if($this->getNom() == $joueurActif){
				$message=$this->getNom()."-PLAY";
			}else $message=$this->getNom();
			return $message;
		}
		
		/*
		* renvoie le message à envoyer
		* Param : joueurSuivant = 0,1,2,3 ; et la collection de tous les autres joueurs
		* Return String, chaine à envoyer au client
		*/
		public function getMessageDrawCard($joueurSuivant=0,$joueurs=array()){
			$message="";
		
			foreach($joueurs as $joueur){
				if($joueur->getNom()!= $this->getNom()){
					/*pour chaque joueur on met sa carte actuel, et sont nombre total de carte*/
					$message += $joueur->getActualCard()."-".$joueur->getAllCards()."-";
				}
				/*si je suis le joueur suivant*/
				if($joueurSuivant == $this->getNom()){
					/*vu que c'est le joueur suivant ou lui affecte PLAY pour qu'il puisse jouer*/
					$message+="-PLAY";
				}
			}
			return $message;
		}
		
		/*
		* Renvoie le message correspondant de type : 
		* Param la liste de carte des joueurs
		*/
		public function getMessageCatch($joueurCatcheur,$joueursCatcher,$joueurs){
			$tab=array();
			$message="";
			/*si le catch et gagné*/
			if($joueursCatcher != ""){
				/*si il y a plus d'un joueur*/
				if(count($joueursCatcher) > 1){
					/*foreach($joueursCatcher as $joueur){
						array_push($tab,$joueur->getCardsKnown());
					}
					$joueurCatcheur->setAfterCatch($tab);
					foreach($joueurs as $joueur){}*/
					
				}else{
					$joueursCatcher->setAfterCatch($joueurCatcheur->getCardsKnown());
					foreach($joueurs as $joueur){
						$message += $joueur->getActualCard()."-".$joueur->getAllCards()."-";
					}
				}
			/*Si le catch est raté*/
			}else{
				/*foreach($joueursCatcher as $joueur){
					array_push($tab,$joueur->getCardsKnown());
				}*/
				$joueurCatcheur->setAfterCatch($joueursCatcher->getCardsKnown());
				
				foreach($joueurs as $joueur){
					$message += $joueur->getActualCard()."-".$joueur->getAllCards()."-";
				}
			}
		}
	}
	
?>




















