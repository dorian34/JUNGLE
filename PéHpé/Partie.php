<?php
//!/usr/local/bin/php -q
error_reporting(E_ALL);

set_time_limit (0);

define("MAX_CLIENT", 4);

	/*----------------------------------------------------------------PARTIE--------------------------------------------------------------------------------*/
	
	class Partie{		
		public $socketServer;
		private $joueurActif;
		private $joueurSuivant;
		private $joueurs = array();
		private $address = '127.0.0.1';
		private $port = 6666;
		private $cartes = array();
		private $message;
			
		public function __construct(){
			$this->joueurActif = 0;
			$this->joueurSuivant = 1;
			$this->waitClient();
		}
		
		public function __destruct(){
	
		}	
		
		/*
		*	Demarage du serveur
		*/
		public function startServer(){

			if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
				echo "socket_create() a échoué : raison : " . socket_strerror(socket_last_error()) . "\n";
				exit(0);
			} 

			if (socket_bind($sock, $this->address, $this->port) === false) { 
				echo "socket_bind() a échoué : raison : " . socket_strerror(socket_last_error($sock)) . "\n";
				exit(0);
			} 
			
			if (socket_listen($sock, 5) === false) {
				echo "socket_listen() a échoué : raison : " . socket_strerror(socket_last_error($sock)) . "\n";
				exit(0);
			}
			
			return $sock;
		}
		
		/*
		* Attente de connection des clients
		*/
		function waitClient(){					
			set_time_limit(0);
			$clients = array();
			
			/*demarage du serveur*/
			$this->socketServer = $this->startServer();
			
			/*Accepte client */
			for($i=0;$i<MAX_CLIENT;$i++){
			
				if(($client = socket_accept($this->socketServer)) === false){
					 echo "socket_accept() a échoué : raison : " . socket_strerror(socket_last_error($this->socketServer)) . "\n";
					 break;
				}else echo "<br/>Client $client[$i] has connected\n";
				
				/*on stock les clients(socket) dans un membre de l'objet joueur*/
				$this->joueurs[$i] = new Joueur($client,$i);
				
				//socket_close($client);
			}
			
			$this->game();
		}
		
		/*******************************************************************GAME***************************************************************************/
		
		/*
		* Game
		*/
		public function game(){
			$start=true;
			$this->initDeck();
			$this->addCardToPlayers();
			$this->writeToPlayers(0,"","");
			
			/*debut du jeu*/
			while($start){
					$messageClient="";
					
					//$numChanged = socket_select($clients, $write, $except, 0);
				for($i=0;$i<MAX_CLIENT;$i++){	
					
					$socket = $this->joueurs[$i]->getSocket();
					
					/*on set les options necessaire au non bloquage du read*/
					socket_set_option($socket,SOL_SOCKET, SO_RCVTIMEO, array("sec"=>1, "usec"=>0));
					socket_set_nonblock($socket);
					
					/*lit les données du client*/
					$messageClient = socket_read($socket, 1024);
					
					/*si le message du client est vide (improbable) on traite*/
					if($messageClient != ""){					
						
						/*si c'est un catch*/
						if($this->hasMessageClientCatch($messageClient)){
							$lstJoueursCatcher=array();
							$lstJoueursCatch;
							$requeteC="";
							
							/*on recupere le num du client*/
							$j = $this->getJoueurCatcheur($messageClient);
							
							/*Lande le module c et retourne un tableau de joueurs catché*/
							$lstJoueursCatcher = $this->runModuleC($this->joueurs[$j]);
							
							/*envoie la mise a jour aux joueurs en fonction de la onse du C en donnant l'etat 2 : catch*/
							$this->writeToPlayers(2,$this->joueurs[$j],$lstJoueursCatcher);
						
						/*sinon c'est forcement pour tiré une carte*/
						}elseif($this->hasMessageClientPlay($messageClient)){
							/*envoie mise a jour aux joueurs en donnant l'etat 1 : tirer carte*/
							$this->writeToPlayers(1,"","");
							
							/*On met à jour le nouveau joueur actif et le joueur suivant*/
							$this->majJoueurActif();
						}else{
							//TODO message chat
						}
					}
				}
			}
		}
		/*
		* envoie un message particulier aux joueurs
		*/
		public function writeToPlayers($etat=0,$joueurCatcheur,$lstJoueursCatcher){
			$message="";
			
			switch($etat){
				/*init*/
				case 0 :
						/*pour chaque joueurs on envoie le message lui etant dédié*/			
						foreach($this->joueurs as $joueur){
							$message = $joueur->getMessageInit($this->joueurActif);
							socket_write($joueur->getSocket(),$message."\n",strlen($message."\n"));
						}
					break;
				/*tirage carte*/
				case 1 :
						/*mise à jours des cartes du joueur actif*/
						$this->joueurs[$this->joueurActif]->drawCard();
						
						/*pour chaque joueurs on envoie le message lui etant dédié*/			
						foreach($this->joueurs as $joueur){
							$message="";
							$message = $joueur->getMessageDrawCard($this->joueurSuivant,$this->joueurs);
							socket_write($joueur->getSocket(),$message."\n",strlen($message."\n"));
						}
					break;
				/*catch*/
				case 2 :
						$message = $joueurCatcheur->getMessageCatch($lstJoueursCatcher,$this->joueurs);
						
						//echo "<br/>message ==> ".$message."<br/>";
						//$this->debug($this->joueurs);
						//exit(1);
						
						foreach($this->joueurs as $joueur){
							socket_write($joueur->getSocket(),$message."\n",strlen($message."\n"));
						}						
					break;
				default : echo "!!!!!!!!!!!!! erreur !!!!!!!!!!!!!";
			}		
		}

		/***************************************************************************************************************************************************/
		
		/*
		* traduit la requete envoyer par le C
		* param : requete de type : "numjoueur-numjoueur"
		* return : renvoie une string ou un array de joueur(s) catché (traduction de la reponse C)
		*/
		public function tradRequeteC($req="", $joueurs=array()){
			$rep;
			$tabJ = array();
			
			if($req == ""){
				return array();
				echo "<br/>--->".$req;
				exit(1);
			}
			$rep = explode("-",$req);
			
			switch(count($rep)){
				case 0 : 
					return array();
					break;
				case 1 : 
					return $joueurs[intVal($rep[0])];
					break;
				case 2 : 
					array_push($tabJ,$joueurs[intVal($rep[0])]);
					array_push($tabJ,$joueurs[intVal($rep[1])]);
					return $tabJ;
					break;
				case 3 : 
					array_push($tabJ,$joueurs[intVal($rep[0])]);
					array_push($tabJ,$joueurs[intVal($rep[1])]);
					array_push($tabJ,$joueurs[intVal($rep[2])]);
					return $tabJ;
					break;
			}  
			
		}
		
		/*
		* Lance le module c
		* param : le joueurCatcheur
		*/			
		public function runModuleC($joueurCatcheur){
			
			$return="0";
			$reponseC = "";
			$nbJoueur=0;
			$tab = array();
			$tabReponseC = array();
			
			/*compte le nombre de joueur restant et remplit le tableau des joueurs catché*/
			foreach($this->joueurs as $joueur){
				if($joueur->getNom() != $joueurCatcheur->getNom()){
					array_push($tab, $joueur);
				}
			}
			
			$nbJoueur = $this->getNbPlayers();
			
			array_push($tab, $joueurCatcheur);
			
			if($nbJoueur == 2){
				$comand = "./jungle ".$tab[0]->getActualCard()." ".$tab[1]->getActualCard();
				$reponseC = system($comand, $return);
			}else if($nbJoueur == 3){
				$comand = "./jungle ".$tab[0]->getActualCard()." ".$tab[1]->getActualCard()." ".$tab[2]->getActualCard();
				$reponseC = system($comand, $return);
			}else if($nbJoueur == 4){
				$comand = "./jungle ".$tab[0]->getActualCard()." ".$tab[1]->getActualCard()." ".$tab[2]->getActualCard()." ".$tab[3]->getActualCard();
				$reponseC = system($comand, $return);
			}
			return $this->tradRequeteC($reponseC, $tab);
		}
		
		/*
		* Retour : un entier representent le nombre de joueurs
		*/
		public function getNbPlayers(){
			return count($this->joueurs);
		}
		
		/*
		* verifie si le message provenant du client et de type PLAY
		* retour : vrai si c'est un play, faux si non
		*/
		public function hasMessageClientPlay($message=""){
			$resultat = explode("-",$message);
			if($resultat[0] == "PLAY"){
				return true;
			}else return false;
		}
		
		/*
		* verifie si le message provenant du client et du au catch
		* retour : vrai si c'est un catch, faux si non
		*/
		public function hasMessageClientCatch($message=""){
			$resultat = explode("-",$message);
			if($resultat[0] == "CATCH"){
				return true;
			}else return false;
		}
		
		/*
		* Renvoie le joueur qui a catcher
		* param : le message renvoyé par read_socket
		*/
		public function getJoueurCatcheur($messageClient){
			$resultat = array();
			$resultat = explode("-",$messageClient);
			return intval($resultat[1]);
		}
		
		/*
		* Met à jour le joueur actif et le joueur suivant
		*/
		public function majJoueurActif(){
			$this->joueurActif = $this->joueurSuivant;
			$this->joueurSuivant = ($this->joueurActif == MAX_CLIENT-1 ? 0 : $this->joueurActif+1);
		}
		
		/*----------------------------------------------------------Fonction lié a la gestion des cartes-----------------------------------------------*/
		
		/*
		* Initialisation du paquet de cartes
		*/
		public function initDeck(){
			$j=1;
			for($i=1;$i<81;$i++){
				$this->cartes[$i] = $j;
				if ($j == 20){
					$j = 1;
				}else $j++;
			}
		}
		
		/*
		* Ajout des cartes aux joueurs
		*/
		public function addCardToPlayers(){
		
			/*On melange les cartes*/
			shuffle($this->cartes);			
			
			/*Affectation des cartes aux joueurs*/
			$this->joueurs[0]->setCardsHide(array_slice($this->cartes,0,-60));
			$this->joueurs[1]->setCardsHide(array_slice($this->cartes,21,-39));
			$this->joueurs[2]->setCardsHide(array_slice($this->cartes,41,-19));
			$this->joueurs[3]->setCardsHide(array_slice($this->cartes,60));
			
			/*carte retourné*/
			$this->joueurs[0]->setActualCard(0);
			$this->joueurs[1]->setActualCard(0);
			$this->joueurs[2]->setActualCard(0);
			$this->joueurs[3]->setActualCard(0);
		}
		
		/*
		* Fonction de debug
		*/
		public function debug($var){
			echo "<br/>------------------------------------------------------------------<br/>";
			print_r($var);
			echo "<br/>------------------------------------------------------------------<br/>";
		}
	}
?>