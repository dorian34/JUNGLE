package ihm;

import java.io.IOException;

public class Main {
	public static void main(String[] args){
		try {
			new Jungle();
		} catch (IOException e) {
			e.printStackTrace();
		}
	}
}
