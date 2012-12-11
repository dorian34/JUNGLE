package reseau;

import java.io.PrintWriter;

/**
 * Class emission to Server
 * 
 * @author PC-dorian
 *
 */
public class Emission implements Runnable{

	private PrintWriter print;
	private String message;
	
	/**
	 * Construct
	 * @param out
	 * @param message
	 */
	public Emission(PrintWriter out,String message){
		this.print = out;
		this.message = message;
	}
	
	/**
	 * 
	 */
	@Override
	public void run() {
		try {
			print.println(this.message);
			print.flush();
			
			System.out.println("vous avez envoyé au serveur : "+message);
			
		} catch (Exception e) {
			e.getMessage();
			e.getStackTrace();
		}
	}
}