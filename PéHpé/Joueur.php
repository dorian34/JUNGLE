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
		* AJoute un tableau de carte
		*/
		public function addCardHide($cards){
			$this->cardsHide = array_merge($this->cardsHide,$cards);
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
		* Getteur
		* return : renvoie le nombre de cartes correspondant au chiffre passé en parametre ou -1 si impossible
		* Param : Un entier, le nb de cartes
		*/
		public function getCardsKnownWithNumber($nbCartes){
			$tab = array();
			if(!empty($this->cardsKnown)){
				for($i=0;$i<$nbCartes;$i++){
					array_push($tab,array_shift($this->cardsKnown));
				}
			}else return -1;
			
			return $tab;
		}
		
		/*
		* Setteur carte connnu
		*/
		public function setCardsKnown($cardKnown){
			for($i=0;$i<count($cardKnown);$i++){
					array_push($this->cardsKnown,$cardKnown);
				}
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
		
		/*
		* getNbHideCard
		* retourne le nb total de carde hide
		*/
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
		* param : soit une tableau de tableau de carte, soit un tableau de carte
		*/
		public function setAfterCatchGagnant($var){
			$cartes = array();
		
			if(count($var) > 1){
				foreach($var as $joueur){
					$nbRand = rand(1,3);
					
					if($cartes = ($this->getCardsKnownWithNumber($nbRand)) != -1){
						$joueur->addCardHide(array_merge($joueur->getCardsKnown(),$cartes));
						$joueur->setActualCard(0);
					}
				}
			}else{
				$var->addCardHide($var->getCardsKnown());
				$var->addCardHide($this->getCardsKnown());
				$var->setActualCard(0);
				$this->setActualCard(0);
			}
			//TODO gagnant
		}
		
		/*
		* met à jours les cartes des joueurs;
		* param : soit une tableau de tableau de carte, soit un tableau de carte
		*/
		public function setAfterCatchPerdant($joueurs){
			$i=0;
			foreach($joueurs as $joueur){
				if($this->getNom() != $joueur->getNom()){
					$this->addCardHide($joueur->getCardsKnown());
					$joueur->setActualCard(0);
				}
			}
			$this->setActualCard(0);
			$this->addCardHide((array)$this->getCardsKnown());
			
			//TODO gagnant
		}
		
		/*****************************************************************************************************/
		
		/**
		* Tire une Carte et met a jours le paquet des cartes connu, supprime la carte tiré du paquet caché
		* return Renvoie la carte tiré
		*/
		public function drawCard(){
			$carte="";
			/*si on encore des carte dans notre paquet de carte caché*/
			if(!empty($this->cardsHide)){
			
				/*on met la premiere carte caché dans le paquet de carte devoilé et on met a jours la carte actuelement devoilé*/
				$carte = array_shift($this->cardsHide);
				
				array_push($this->cardsKnown,$carte);
				$this->setActualCard($carte);
			}elseif(!empty($this->cardsKnow)){
				
				/*on met les cartes connu dans les cartes caché vu qu'on a plus de carte a tiré*/
				$this->cardsHide = array();
				$this->cardsHide = $this->cardsKnow;
				$this->cardsKnow = array();
				
				/*on met la premiere carte caché dans le paquet de carte devoilé et on met a jours la carte actuelement devoilé*/
				$carte = array_shift($this->cardsHide);
				
				array_push($this->cardsKnown,$carte);
				$this->setActualCard($carte);
			}else{
				// il n'a plus de carte il a gagné TODO...
			}
		}
		
		/*
		* renvoie le message à envoyer
		* Param : le joueurActif
		*/
		public function getMessageInit($joueurActif=0){
			$message="";
			if($this->getNom() == $joueurActif){
				$message=$this->getNom()."-PLAY-INIT";
			}else $message=$this->getNom()."-INIT";
			return $message.";";
		}
		
		/*
		* renvoie le message à envoyer
		* Param : joueurSuivant = 0,1,2,3 ; et la collection de tous les autres joueurs
		* Return String, chaine à envoyer au client
		*/
		public function getMessageDrawCard($joueurSuivant=0,$joueurs=array()){
			$message="";
			
			for($i=0;$i<count($joueurs);$i++){
				/*pour chaque joueur on met sa carte actuel, et sont nombre total de carte*/
				$message .= $joueurs[$i]->getNom()."-".$joueurs[$i]->getActualCard()."-".$joueurs[$i]->getAllCards().
				($joueurSuivant == $joueurs[$i]->getNom() ? (isset($joueurs[$i+1]) ? "-PLAY;" : "-PLAY") : (isset($joueurs[$i+1]) ? ";" : ""));
			}
			return $message;
		}
		
		/*
		* Renvoie le message correspondant de type : 
		* Param la liste de carte des joueurs
		*/
		public function getMessageCatch($joueursCatcher=array(),$joueurs){
			$tab=array();
			$message="";
			
			/*si le catch et gagné*/
			if(!empty($joueursCatcher)){
				$this->setAfterCatchGagnant($joueursCatcher);
			/*Si le catch est raté*/
			}else{
				$this->setAfterCatchPerdant($joueurs);
			}
			
			/*pour chaque joueur on met sa carte actuel, et sont nombre total de carte*/
			for($i=0;$i<count($joueurs);$i++){
				$message .= $joueurs[$i]->getNom()."-".$joueurs[$i]->getActualCard()."-".$joueurs[$i]->getAllCards().";";
			}
			return $message;
		}
	}
	
?>




















