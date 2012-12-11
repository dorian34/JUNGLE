package ihm;


import java.awt.Container;
import java.util.ArrayList;
import java.util.Scanner;

import javax.swing.ImageIcon;
import javax.swing.JButton;
import javax.swing.JLabel;


public class MajJungle{
	
	private Scanner sc;
	private ArrayList<JLabel> cartes;
	private ArrayList<JLabel> coupRestant;
	private JButton btnCatch;
	private JButton btnJouer;
	private String messageServer;
	
	/**
	 * 
	 * @param plat
	 * @param l
	 * @param c
	 * @param btnCatch
	 * @param btnJouer
	 */
	public MajJungle(Container plat, ArrayList<JLabel> l, ArrayList<JLabel> c, JButton btnCatch, JButton btnJouer){
		this.cartes = l;
		this.coupRestant = c;
		this.btnCatch = btnCatch;
		this.btnJouer = btnJouer;
	}

	/**
	 * majJungle
	 */
	public void majJungle(String messageServer) {
		this.messageServer = messageServer;

		/*si c'est une initialisation*/
		if (messageServer.indexOf("INIT") >= 0) {
			
			String[] s = messageServer.split("-");
			Jungle.numJoueur = Integer.valueOf(s[0]);
			
		   	System.out.println("num joueur : "+Jungle.numJoueur);
		   	/*si c'est a moi de jouer*/
			if (messageServer.indexOf("PLAY") >= 0) {
				setCarte(Jungle.numJoueur,-1,-1);
				this.enableClique();
			}else{
				this.disableClique();
			}
		}else{/*si c'est un retour catch ou dans le cas du retour tirage de carte */
			String[] mess = messageServer.split(";");
			
			String[] joueur1 = mess[0].split("-");
			String[] joueur2 = mess[1].split("-");
			String[] joueur3 = mess[2].split("-");
			String[] joueur4 = mess[3].split("-");
			
			int numJ1 = Integer.valueOf(joueur1[0]);
			int numJ2 = Integer.valueOf(joueur2[0]);
			int numJ3 = Integer.valueOf(joueur3[0]);
			int numJ4 = Integer.valueOf(joueur4[0]);
			
			int ActualCardJ1 = Integer.valueOf(joueur1[1]);
			int ActualCardJ2 = Integer.valueOf(joueur2[1]);
			int ActualCardJ3 = Integer.valueOf(joueur3[1]);
			int ActualCardJ4 = Integer.valueOf(joueur4[1]);
			
			System.out.println("cartej1 => "+ActualCardJ1);
			System.out.println("cartej2 => "+ActualCardJ2);
			System.out.println("carteJ3 => "+ActualCardJ3);
			System.out.println("carteJ4 => "+ActualCardJ4);
			
			int nbCarteRestantJ1 = Integer.valueOf(joueur1[2]);
			int nbCarteRestantJ2 = Integer.valueOf(joueur2[2]);
			int nbCarteRestantJ3 = Integer.valueOf(joueur3[2]);
			int nbCarteRestantJ4 = Integer.valueOf(joueur4[2]);
			
			setCarte(numJ1,ActualCardJ1,nbCarteRestantJ1);
			setCarte(numJ2,ActualCardJ2,nbCarteRestantJ2);
			setCarte(numJ3,ActualCardJ3,nbCarteRestantJ3);
			setCarte(numJ4,ActualCardJ4,nbCarteRestantJ4);
			
			/*0-11-20;1-0-20-PLAY;2-0-20;3-0-20;*/
			if(mess[0].indexOf("PLAY") >= 0){
				if(Integer.valueOf(joueur1[0]) == Jungle.numJoueur){
					this.enableClique();
					System.out.println("on est dans PLAY pour enable clique");
				}else{
					this.disableClique();
				}
			}else if(mess[1].indexOf("PLAY") >= 0){
				if(Integer.valueOf(joueur2[0]) == Jungle.numJoueur){
					this.enableClique();
					System.out.println("on est dans PLAY pour enable clique");
				}else{
					this.disableClique();
				}	
			}else if(mess[2].indexOf("PLAY") >= 0){
				if(Integer.valueOf(joueur3[0]) == Jungle.numJoueur){
					this.enableClique();
					System.out.println("on est dans PLAY pour enable clique");
				}else{
					this.disableClique();
				}
			}else if(mess[2].indexOf("PLAY") >= 0){
				if(Integer.valueOf(joueur4[0]) == Jungle.numJoueur){
					this.enableClique();
					System.out.println("on est dans PLAY pour enable clique");
				}else{
					this.disableClique();
				}
			}
		}
	}
	
	/**
	 * 
	 */
	public void disableClique(){
		System.out.println("disableClique");
		this.btnJouer.setEnabled(false);
	}
	
	/**
	 * 
	 */
	public void enableClique(){
		System.out.println("enableClique");
		this.btnJouer.setEnabled(true);
		this.btnCatch.setEnabled(true);
	}
	
	/**
	 * 
	 */
	public void setCarte(int numJoueur, int carte,int nbCarteRestant){
		
		if(carte != -1){
			switch(numJoueur){
			/*joueur 1*/
			case 0 :
				if(nbCarteRestant != -1){
					this.coupRestant.get(0).setText("nombre de cartes restante : "+nbCarteRestant);
				}
				this.cartes.get(0).setIcon(new ImageIcon("img/"+carte+".PNG"));
				break;
			/*joueur 2*/
			case 1 :
				if(nbCarteRestant != -1){
					this.coupRestant.get(1).setText("nombre de cartes restante : "+nbCarteRestant);
				}
				this.cartes.get(1).setIcon(new ImageIcon("img/"+carte+".PNG"));
				break;
			/*joueur 3*/
			case 2 :
				if(nbCarteRestant != -1){
					this.coupRestant.get(2).setText("nombre de cartes restante : "+nbCarteRestant);
				}
				this.cartes.get(2).setIcon(new ImageIcon("img/"+carte+".PNG"));
				break;
			/*joueur 4*/
			case 3 :
				if(nbCarteRestant != -1){
					this.coupRestant.get(3).setText("nombre de cartes restante : "+nbCarteRestant);
				}
				this.cartes.get(3).setIcon(new ImageIcon("img/"+carte+".PNG"));
				break;
			}
		}
	}
}
