package reseau;

import ihm.MajJungle;

import java.io.BufferedReader;
import java.io.IOException;

public class Reception implements Runnable{

	private String repServeur;
	private BufferedReader buffer;
	private MajJungle majJungle;
	
	public Reception(BufferedReader r,final MajJungle majJungle){ 		
		this.buffer = r;
		this.majJungle = majJungle;
	}

	@Override
	public synchronized void run() {
		
		while(true){
			try {
				
				repServeur = buffer.readLine();
				System.out.println("JE VIEN DE RECEVOIR CE MESSAGE -------->"+repServeur);
				majJungle.majJungle(repServeur);
				
			} catch (IOException e) {
				e.printStackTrace();
			}
		}	
	}
}
