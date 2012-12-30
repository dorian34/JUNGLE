#include <stdio.h>
#include <stdlib.h>
#include <time.h>
#include <string.h>

char* verifCatch2(char* j1, char* jCatcheur);
char* verifCatch3(char* j1,char* j2,char* jCatcheur);
char* verifCatch4(char* j1,char* j2,char* j3,char* jCatcheur);

/*
*Fonction main 
* Param : char*, exemple : 10-25-0-0;
*/
void main(int argc, char** argv) {
	
	char* val;
	
	if(argc == 3){
		/*compare les cartes entre elles*/
		val = malloc(30*sizeof(char*));
		val = verifCatch2(argv[1],argv[2]);
	}if(argc == 4){
		/*compare les cartes entre elles*/
		val = malloc(30*sizeof(char*));
		val = verifCatch3(argv[1],argv[2],argv[3]);
	}if(argc == 5){
		/*compare les cartes entre elles*/
		val = malloc(30*sizeof(char*));
		val = verifCatch4(argv[1],argv[2],argv[3],argv[4]);
	}
	printf("%s", val);
}

/*
* Verifie si le catcheur a eu raison de catcher
* Param : carte des joueurs char*
*/
char* verifCatch2(char* j1, char* jCatcheur){
	
	char *valRet;
	int cardJ1 = atoi(j1);
	int catcheur = atoi(jCatcheur);
	
	valRet = malloc(30*sizeof(char*));
	
	if(cardJ1 == catcheur){
		strcat(valRet, "0");
	}else valRet ="";
	return valRet;
}

/*
* Verifie si le catcheur a eu raison de catcher
* Param : carte des joueurs char*
*/
char* verifCatch3(char* j1,char* j2,char* jCatcheur){
	
	char* valRet;
	int cardJ1 = atoi(j1);
	int cardJ2 = atoi(j2);
	int catcheur = atoi(jCatcheur);
	
	valRet = malloc(30*sizeof(char*));
	
	if(catcheur == cardJ1){
		strcat(valRet, "0");
		if(catcheur == cardJ2){
			strcat(valRet, "-1");
		}
	}else if(catcheur == cardJ2){
		strcat(valRet, "1");
	}else{
		valRet="";
	}
	return valRet;
}

/*
* Verifie si le catcheur a eu raison de catcher
* Param : carte des joueurs char*
*/
char* verifCatch4(char* j1,char* j2,char* j3,char* jCatcheur){
	
	char *valRet;
	int cardJ1 = atoi(j1);
	int cardJ2 = atoi(j2);
	int cardJ3 = atoi(j3);
	int catcheur = atoi(jCatcheur);
	
	valRet = malloc(30*sizeof(char*));
	
	if(catcheur == cardJ1){
		strcat(valRet, "0"); 		
		if(catcheur == cardJ2){
			strcat(valRet, "-1");
			if(catcheur == cardJ3){
				strcat(valRet, "-2");
			}	
		}
	}else if(catcheur == cardJ2){
		strcat(valRet, "1");
		if(catcheur == cardJ3){
			strcat(valRet, "-2");
		}
	}else if(catcheur == cardJ3){
		strcat(valRet, "3");
	}else valRet ="";
	
	return valRet;
}