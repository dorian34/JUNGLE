package reseau;

import ihm.MajJungle;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.Socket;
import java.util.concurrent.Callable;


/**
 * 
 * @author PC-dorian
 *
 *Class Connection to Server
 *
 */
public class Connection implements Callable<Socket>{

	private String port;
	private String addr;
	private BufferedReader in = null;
	private MajJungle majJungle;
	
	
	public Connection(String port, String addr, MajJungle majJungle){ 		
		this.addr = addr;
		this.port = port;
		this.majJungle = majJungle;
	}

	@Override
	public Socket call() throws Exception {
		Socket socket = null;
		try {
			socket = new Socket(this.addr, Integer.valueOf(this.port));
			
			in = new BufferedReader(new InputStreamReader(socket.getInputStream()));

			/*Lancement du thread reception*/
			new Thread(new Reception(in,majJungle)).start();
			
		} catch (NumberFormatException | IOException e) {
			e.printStackTrace();
		}
		return socket;
	}
}
