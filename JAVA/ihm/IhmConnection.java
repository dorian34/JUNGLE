package ihm;

import java.awt.BorderLayout;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.KeyEvent;
import java.net.Socket;
import java.util.concurrent.ExecutionException;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;
import java.util.concurrent.Future;

import javax.swing.JButton;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JPanel;
import javax.swing.JTextField;
import javax.swing.KeyStroke;

import reseau.Connection;

public class IhmConnection extends JFrame implements ActionListener{

	private static final long serialVersionUID = -1441509586809258957L;
	
	JButton buttonConect;
	JTextField txtPort;
	JTextField txtAddr;
	JLabel lblConect;
	JLabel lblPort;
	JLabel lblAddr;
	JPanel panelConGauche;
	JPanel panelConDroite;
	JPanel panel;
	Thread t;
	MajJungle majJungle;
	static Socket socketClient;
	
	/**
	 * 
	 */
	public IhmConnection(final MajJungle majJungle){
		this.setTitle("Connection");
		this.setSize(600, 200);
		setResizable(false);
		
		this.majJungle = majJungle;
		
		lblConect = new JLabel("Connection");
		txtAddr = new JTextField(10);
		lblAddr = new JLabel("adresse :");
		txtPort = new JTextField(10);
		lblPort = new JLabel("Port");
		buttonConect = new JButton("Connection");
		buttonConect.getInputMap().put(KeyStroke.getKeyStroke("ENTER"), "pressed");
		panelConGauche = new JPanel();
		panelConDroite = new JPanel();
		panel = new JPanel();
		
		panelConGauche.add(lblPort);
		panelConGauche.add(txtPort);
		panelConDroite.add(lblAddr);
		panelConDroite.add(txtAddr);
		
		panel.add(panelConDroite,BorderLayout.SOUTH);
		panel.add(panelConGauche,BorderLayout.WEST);
		panel.add(buttonConect,BorderLayout.CENTER);
		
		this.getContentPane().add(panel,BorderLayout.CENTER);
	    
	    this.setLocationRelativeTo(null);
		this.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		this.setVisible(true);
		this.setDefaultCloseOperation(EXIT_ON_CLOSE);
		
		buttonConect.addActionListener(new ActionListener() {
			
			@Override
			public void actionPerformed(ActionEvent arg0) {
				
				ExecutorService execute = Executors.newSingleThreadExecutor();
				
				//On récupère un objet Future<V>	
				Future<Socket> future = execute.submit(new Connection(txtPort.getText(),txtAddr.getText(),majJungle));
				
				try {
					socketClient = future.get();
				} catch (InterruptedException | ExecutionException e) {
					e.printStackTrace();
				}
				
				/*fermeture de la fenetre de connection*/
				dispose();
			}
		});
	}
	
	@Override
	public void actionPerformed(ActionEvent e) {
		// TODO Auto-generated method stub
	}	
	
	public static Socket getSocket(){
		return socketClient;
	}
}
