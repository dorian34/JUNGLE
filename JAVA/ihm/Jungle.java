package ihm;

import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Dimension;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.io.IOException;
import java.io.PrintWriter;
import java.net.Socket;
import java.net.UnknownHostException;
import java.util.ArrayList;

import javax.swing.ImageIcon;
import javax.swing.JButton;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JMenu;
import javax.swing.JMenuBar;
import javax.swing.JMenuItem;
import javax.swing.JPanel;
import javax.swing.border.LineBorder;

import reseau.Emission;

/**
 * Class Jungle
 * 
 * @author PC-dorian
 * 
 */

public class Jungle extends JFrame implements ActionListener {

	/* Membre */
	private static final long serialVersionUID = 1L;
	public static int numJoueur;
	private JPanel panGlobalS, panGlobalN, panGlobalW, panGlobalE, panGlobalC, header;
	private JMenuItem nouvellePartie = new JMenuItem("Nouvelle Partie");
	private JMenuItem quitterPartie = new JMenuItem("Quitter Partie");
	private JMenuItem aide = new JMenuItem("Aide");
	private JMenu menu1 = new JMenu("Fichier");
	private JMenu menu2 = new JMenu("?");
	private JMenuBar menuBar;
	private String addr;
	private String port;
	private JButton btnCatch,btnJouer;
	
	private JLabel carteJ1,carteJ2,carteJ3,carteJ4;
	private JLabel nbCarteRestanteJ1,nbCarteRestanteJ2,nbCarteRestanteJ3,nbCarteRestanteJ4;
	private ArrayList<JLabel> cartes;
	private ArrayList<JLabel> coupRestant;
	
	public Jungle() throws IOException {
		
		this.setTitle("Jungle");
		this.setSize(800, 600);
		this.setMaximumSize(new Dimension(800,600));
		this.setMinimumSize(new Dimension(800,600));
		setResizable(false);
		
		/*------------------CARTE--------------------*/
		
		this.carteJ1 = new JLabel(new ImageIcon("img/0.PNG"));
		this.carteJ2 = new JLabel(new ImageIcon("img/0.PNG"));
		this.carteJ3 = new JLabel(new ImageIcon("img/0.PNG"));
		this.carteJ4 = new JLabel(new ImageIcon("img/0.PNG"));
		this.nbCarteRestanteJ1 = new JLabel("nombre restant de carte : 20");
		this.nbCarteRestanteJ2 = new JLabel("nombre restant de carte : 20");
		this.nbCarteRestanteJ3 = new JLabel("nombre restant de carte : 20");
		this.nbCarteRestanteJ4 = new JLabel("nombre restant de carte : 20");
		
		cartes = new ArrayList<>();
		coupRestant = new ArrayList<>();
		
		cartes.add(this.carteJ1);
		cartes.add(this.carteJ2);
		cartes.add(this.carteJ3);
		cartes.add(this.carteJ4);
		
		coupRestant.add(this.nbCarteRestanteJ1);
		coupRestant.add(this.nbCarteRestanteJ2);
		coupRestant.add(this.nbCarteRestanteJ3);
		coupRestant.add(this.nbCarteRestanteJ4);

		/*------------------Button------------------*/
		this.btnCatch = new JButton("catch");
		this.btnCatch.setMaximumSize(new Dimension(50,10));
		this.btnCatch.setEnabled(false);
		
		this.btnJouer = new JButton("jouer");
		this.btnJouer.setMaximumSize(new Dimension(50,10));
		this.btnJouer.setEnabled(false);
		
		/*------------------PANEL HEADER------------------*/
		this.header = new JPanel();
		header = new JPanel();
		header.setBorder(new LineBorder(Color.BLACK));
		header.setBackground(new Color(49, 134, 76));
		
		/*-----------------PANEL CENTER---------------------*/
		this.panGlobalC = new JPanel();
		panGlobalC = new JPanel();
		panGlobalC.setBorder(new LineBorder(Color.BLACK));
		panGlobalC.setBackground(new Color(49, 134, 76));
		panGlobalC.add(this.btnCatch);
		panGlobalC.add(this.btnJouer);
		
		/*------------------PANEL GLOBAL SOUTH------------------*/
		panGlobalS = new JPanel(new BorderLayout());
		panGlobalS.setBorder(new LineBorder(Color.BLACK));
		panGlobalS.setBackground(Color.WHITE);
		
		/*------------------PANEL GLOBAL NORTH------------------*/
		panGlobalN = new JPanel(new BorderLayout());
		panGlobalN.setBorder(new LineBorder(Color.BLACK));
		panGlobalN.setBackground(Color.WHITE);
		
		/*------------------PANEL GLOBAL EST------------------*/
		panGlobalE = new JPanel(new BorderLayout());
		panGlobalE.setBorder(new LineBorder(Color.BLACK));
		panGlobalE.setBackground(Color.WHITE);
		
		/*------------------PANEL GLOBAL WEST------------------*/
		panGlobalW = new JPanel(new BorderLayout());
		panGlobalW.setBorder(new LineBorder(Color.BLACK));
		panGlobalW.setBackground(Color.WHITE);
		
		/*-----------------MenuBar-------------------*/
		this.menuBar = new JMenuBar();
		
		/*----------------label----------------------*/
		panGlobalS.add(this.carteJ1,BorderLayout.NORTH);
		panGlobalS.add(this.nbCarteRestanteJ1,BorderLayout.CENTER);
		panGlobalE.add(this.carteJ2,BorderLayout.NORTH);
		panGlobalE.add(this.nbCarteRestanteJ2,BorderLayout.CENTER);
		panGlobalN.add(this.carteJ3,BorderLayout.NORTH);
		panGlobalN.add(this.nbCarteRestanteJ3,BorderLayout.CENTER);
		panGlobalW.add(this.carteJ4,BorderLayout.NORTH);
		panGlobalW.add(this.nbCarteRestanteJ4,BorderLayout.CENTER);
		
		/*------------------Set Menu bar------------------*/
		setJMenuBar(menuBar);
		menuBar.add(menu1);
		menuBar.add(menu2);
		menu1.add(nouvellePartie);
		menu1.add(quitterPartie);
		menu2.add(aide);
		
		/*------------add main panel------------------*/
		getContentPane().add(panGlobalW,BorderLayout.WEST);
		getContentPane().add(panGlobalE,BorderLayout.EAST);
		getContentPane().add(panGlobalN,BorderLayout.NORTH);
		getContentPane().add(panGlobalS,BorderLayout.SOUTH);
		getContentPane().add(panGlobalC,BorderLayout.CENTER);
		
		/*---------------------------------------------*/
		this.setLocationRelativeTo(null);
		this.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		this.setVisible(true);
		this.setDefaultCloseOperation(EXIT_ON_CLOSE);

		
		/***********************************************CATCH******************************************************/
		
		btnCatch.addActionListener(new ActionListener() {
			
			@Override
			public void actionPerformed(ActionEvent arg0) {
				
				try {
					String message = "CATCH-"+numJoueur+"\n";
					
					PrintWriter out = new PrintWriter(IhmConnection.getSocket().getOutputStream());
					new Thread(new Emission(out,message)).start();
				
				} catch (IOException e) {
					e.printStackTrace();
				}
			}
		});
		
		/***********************************************JOUER*******************************************************/
		
		btnJouer.addActionListener(new ActionListener() {
			
			@Override
			public void actionPerformed(ActionEvent arg0) {
				
				String message = "PLAY-"+numJoueur+"\n";

				PrintWriter out;
				try {
					out = new PrintWriter(IhmConnection.getSocket().getOutputStream());
					new Thread(new Emission(out,message)).start();
					
				} catch (IOException e) {
					e.printStackTrace();
				}				
			}
		});
		
		/************************************************NEW PARTIE***********************************************/
		
		/* Starting game */
		nouvellePartie.addActionListener(new ActionListener() {

			@Override
			public void actionPerformed(ActionEvent arg0) {
				
				/*Starting parameters Connection*/
				MajJungle majJungle = new MajJungle(getContentPane(),cartes,coupRestant,btnCatch,btnJouer);
				new IhmConnection(majJungle);
			}
		});

		/***********************************************QUITTER PARTIE***********************************************/
		
		/* close socket plus app */
		quitterPartie.addActionListener(new ActionListener() {
			@Override
			public void actionPerformed(ActionEvent arg0) {
				dispose();
			}
		});
		
		/************************************************************************************************************/
	}

	@Override
	public void actionPerformed(ActionEvent e) {

	}

	/**
	 * Connection server
	 */
	public Socket ConnectionToServer(Socket clientSocket) throws UnknownHostException, IOException {

		/* connection */
		clientSocket = new Socket(this.addr, Integer.valueOf(this.port));
		return clientSocket;
	}
}