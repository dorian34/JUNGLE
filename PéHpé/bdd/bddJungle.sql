drop table if exists Carte;

drop table if exists Joueur;

drop table if exists Carte;

drop table if exists Joueur_his_carte;

drop table if exists Joueur_his_partie;

drop table if exists Partie;

CREATE TABLE Carte (
  idCarte INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  valeurCarte INTEGER UNSIGNED NOT NULL,
  PRIMARY KEY(idCarte)
);

CREATE TABLE Joueur (
  idJoueur INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  NbCatchGagnant INTEGER UNSIGNED NULL,
  NbCatchPerdant INTEGER UNSIGNED NULL,
  PRIMARY KEY(idJoueur)
);

CREATE TABLE joueur_his_carte (
  idJoueur INTEGER UNSIGNED NOT NULL,
  idCarte INTEGER UNSIGNED NOT NULL,
  PRIMARY KEY(idJoueur, idCarte),
  INDEX index_Joueur_Carte(idJoueur),
  INDEX index_Carte_Joueur(idCarte)
);

CREATE TABLE Joueur_his_Partie (
  idJoueur INTEGER UNSIGNED NOT NULL,
  idPartie INTEGER UNSIGNED NOT NULL,
  PRIMARY KEY(idJoueur, idPartie),
  INDEX index_Partie_Joueur(idJoueur),
  INDEX index_Joueur_Partie(idPartie)
);

CREATE TABLE Partie (
  idPartie INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  PRIMARY KEY(idPartie)
);

  alter table joueur_his_partie add constraint FK_joueur_partie foreign key (idJoueur)
      references Joueur (idJoueur) on delete restrict on update restrict;

  alter table joueur_his_partie add constraint FK_partie_joueur foreign key (idPartie)
      references Partie (idPartie) on delete restrict on update restrict;

  alter table joueur_his_carte add constraint FK_joueur_carte foreign key (idJoueur)
      references Joueur (idJoueur) on delete restrict on update restrict;

  alter table joueur_his_carte add constraint FK_carte_joueur foreign key (idCarte)
      references Carte (idCarte) on delete restrict on update restrict;
  
