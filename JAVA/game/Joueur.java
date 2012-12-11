package game;

import java.awt.Component;

import javax.swing.JLabel;

public class Joueur extends Component{

	private static final long serialVersionUID = -7032321802175775417L;
	private int num;
	private JLabel card;
	private int carteRestante;
	
	
	/**
	 * 
	 * @return carteRestante
	 */
	public int getCarteRestante() {
		return carteRestante;
	}

	/**
	 * 
	 * @param carteRestante
	 */
	public void setCarteRestante(int carteRestante) {
		this.carteRestante = carteRestante;
	}

	public Joueur(int nom){
		this.num = nom;
	}
	
	/**
	 * get nom
	 * @return entier quie st le nom du joueur
	 */
	public int getNum(){
		return this.num;
	}

	/**
	 * Recupere l'image de la carte du joueur
	 */
	public JLabel getCard(){
		return card;
	}
	
	/**
	 * Modifie l'image de la carte du joueur
	 */
	public void setCard(JLabel card){
		this.card = card;
	}
}
