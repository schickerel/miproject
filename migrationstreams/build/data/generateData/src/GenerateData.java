package generate;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.text.ParseException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;
import java.util.Random;

public class GenerateData {

	Connection conn;
	
	public static void main(String[] args) throws ParseException, ClassNotFoundException, SQLException {
		GenerateData obj = new GenerateData();
		obj.connection();
		obj.run();

	}

	public void connection() throws ClassNotFoundException, SQLException {
		  String myDriver = "com.mysql.jdbc.Driver";
	      String myUrl = "jdbc:mysql://localhost:3306/migrationstreams";
	      Class.forName(myDriver);
	      conn = DriverManager.getConnection(myUrl, "root", "");
	  }
	
	public void run() throws SQLException {
		int personId = 0;
		int numberOfPeople = 600;
		
		while (numberOfPeople != 0) {
			Map<String, String> person = getPerson();
			String firstName = person.get("firstName");
			String lastName = person.get("lastName");
			String birthday = person.get("birthday");
			String dayOfDeath = person.get("dayOfDeath");
			int denominationId = Integer.valueOf(person.get("denominationId"));
			int professionalCategoryId = Integer.valueOf(person.get("professionalCategoryId"));
			String profession = "";
			int countryOfBirthId = Integer.valueOf(person.get("countryOfBirth"));
			String placeOfBirth = "";
			
			String insertPerson = "INSERT INTO persons(first_name, last_name, birthday, day_of_death, denomination_id, professional_category_id, profession, country_of_birth_id, place_of_birth) VALUES (?, ?, STR_TO_DATE(?,'%d.%m.%Y'), STR_TO_DATE(?,'%d.%m.%Y'), ?, ?, ?, ?, ?)";
	  	  	PreparedStatement prepareInsertPerson = conn.prepareStatement(insertPerson, PreparedStatement.RETURN_GENERATED_KEYS);
	  	  	prepareInsertPerson.setString(1, firstName);
	  	  	prepareInsertPerson.setString(2, lastName);
	  	  	prepareInsertPerson.setString(3, birthday);
	  	  	prepareInsertPerson.setString(4, dayOfDeath);
	  	  	prepareInsertPerson.setInt(5, denominationId);
	  	  	prepareInsertPerson.setInt(6, professionalCategoryId);
	  	  	prepareInsertPerson.setString(7, profession);
	  	  	prepareInsertPerson.setInt(8, countryOfBirthId);
	  	  	prepareInsertPerson.setString(9, placeOfBirth);
	  	  	prepareInsertPerson.executeUpdate();
	  	  	ResultSet resultPerson = prepareInsertPerson.getGeneratedKeys();
	  	  	if(resultPerson.next()) {
	  	  		personId = resultPerson.getInt(1);
	  	  	}
			
			int numberOfMigrations = getRandomNumber(1,5);
			String year = "1933";
			String countryId = "7";
			for(int i = 1; i <= numberOfMigrations; i ++){
				Map<String, String> migration = getMigration(year, countryId);
				countryId = migration.get("countryId");
				year = migration.get("year");
				String migrationCity = "";
				int migrationCountryId = Integer.valueOf(migration.get("countryId"));
				int migrationMonth = Integer.valueOf(migration.get("month"));
				int migrationYear = Integer.valueOf(migration.get("year"));
	        	
				String insertMigration = "INSERT INTO migrations(city, country_id, month, year, person_id) VALUES (?, ?, ?, ?, ?)";
	      	  	PreparedStatement prepareInsertMigration = conn.prepareStatement(insertMigration);
	      	  	prepareInsertMigration.setString(1, migrationCity);
	      	  	prepareInsertMigration.setInt(2, migrationCountryId);
	      	  	prepareInsertMigration.setInt(3, migrationMonth);
	      	  	prepareInsertMigration.setInt(4, migrationYear);
	      	  	prepareInsertMigration.setInt(5, personId);
	      	  	prepareInsertMigration.executeUpdate();
			}
			numberOfPeople--;
		}
	}
	
	public Map<String, String> getMigration (String preYear, String preCountry) throws SQLException {
		Map<String, String> migration = new HashMap<String, String>();
		int month = getRandomNumber(1, 12);
		int minYear = Integer.valueOf(preYear) + 1;
		int maxYear = minYear + 5;
		int preCountryId = Integer.valueOf(preCountry);
		int year = getRandomNumber(minYear, maxYear);
		ArrayList<Integer> countryIds = getCountryIds();
		int countryIdMax = countryIds.size();
		int countryId = getRandomNumber(1, countryIdMax);
		while((year <= 1945 && countryId == 7) || countryId == preCountryId){
			countryId = getRandomNumber(1, countryIdMax);
		}
		migration.put("countryId", String.valueOf(countryId));
		migration.put("month", String.valueOf(month));
		migration.put("year", String.valueOf(year));
		return migration;
	}
	
	public Map<String, String> getPerson () throws SQLException {
		Map<String, String> person = new HashMap<String, String>();
		Map<String, String> dates = getDates();
		ArrayList<Integer> denominationIds = getDenominationIds();
		ArrayList<Integer> professionalCategoryIds = getProfessionalCategoryIds();
		int denominationIdMax = denominationIds.size();
		int professionalCategoryIdMax = professionalCategoryIds.size();
		int gender = getRandomNumber(0,1);
		if(gender == 0){
			person.put("firstName", getMaleFirstName());
		} else {
			person.put("firstName", getFemaleFirstName());
		}
		person.put("lastName", getLastName());
		person.put("birthday", dates.get("birthday"));
		person.put("dayOfDeath", dates.get("dayOfDeath"));
		person.put("denominationId", String.valueOf(getRandomNumber(1, denominationIdMax)));
		person.put("professionalCategoryId", String.valueOf(getRandomNumber(1, professionalCategoryIdMax)));
		person.put("countryOfBirth", String.valueOf(7));
		return person;
	}
	
	
	public String getLastName () {
		String[] lastNames = {"Müller", "Schmidt", "Schneider", "Fischer", "Weber", "Meyer", "Wagner", "Becker", "Schulz", "Hoffmann", "Schäfer", "Koch", "Bauer", "Richter", "Klein", "Wolf", "Schröder", "Neumann", "Schwarz", "Zimmermann", "Braun", "Krüger", "Hofmann", "Hartmann", "Lange", "Schmitt", "Werner", "Schmitz", "Krause", "Meier", "Lehmann", "Schmid", "Schulze", "Maier", "Köhler", "Herrmann", "König", "Huber", "Kaiser", "Fuchs", "Peters", "Lang", "Scholz", "Weiß", "Jung", "Hahn", "Schubert", "Vogel", "Friedrich", "Berger", "Winkler", "Roth", "Beck", "Lorenz", "Baumann", "Franke", "Schuster", "Ludwig", "Böhm", "Winter", "Kraus", "Schumacher", "Krämer", "Vogt", "Stein", "Jäger", "Sommer", "Groß", "Seidel", "Heinrich", "Brandt", "Haas", "Schreiber", "Graf", "Schulte", "Dietrich", "Ziegler", "Kuhn", "Engel", "Pohl", "Busch", "Bergman", "Voigt", "Sauer", "Arnold", "Pfeiffer"};
		int index = getRandomNumber(0, lastNames.length - 1);
		return lastNames[index];
	}
	
	public String getFemaleFirstName () {
		String[] firstNames = {"Anna", "Emma", "Lana", "Marie", "Mia", "Hanna", "Sofia", "Emilia", "Lena", "Sara", "Julia", "Elena", "Eva", "Madgalena", "Michaela", "Alice", "Andrea", "Karina", "Olivia", "Lea", "Nina", "Olivia", "Ines", "Lisa", "Mila", "Amelie", "Jessica", "Ida", "Martha", "Elisabeth", "Luise", "Dora", "Minna", "Elise", "Helene", "Dorothea", "Anni", "Clara", "Käthe", "Elsa", "Johanna", "Elfriede", "Margarethe", "Frieda", "Edith", "Irma", "Charlotte", "Minna", "Therese", "Angela", "Lina", "Agnes", "Karoline", "Victoria", "Melanie", "Leoni", "Kristin", "Nadine", "Sabine", "Sina", "Uta", "Birgit", "Carola", "Regina", "Corinna", "Dörte", "Frida", "Gaby", "Helga", "Heike", "Heidi", "Irene", "Jutta", "Karla", "Katrin", "Katja", "Linda", "Maja", "Maike", "Miriam", "Natalie", "Nele", "Nina", "Rebecca", "Viola", "Tina"};
		int index = getRandomNumber(0, firstNames.length - 1);
		return firstNames[index];
	}
	
	public String getMaleFirstName () {
		String[] firstNames = {"Ben", "August", "Andre", "Daniel", "Heinrich", "Paul", "Otto", "Franz", "Hans", "Walter", "Gustav", "Richard", "Emil", "Robert", "Karl", "Johannes", "Erich", "Josef", "Albert", "Alfred", "Kurt", "Fritz", "Georg", "Rudolf", "Willi", "Max", "Ernst", "Fabian", "Florian", "Frank", "Gerhard", "Siegfried", "Gustav", "Ingo", "Jörg", "Jürgen", "Günter", "Heinz", "Edward", "Michael", "Wolfgang", "Tom", "Norman", "Marvin", "Alex", "Markus", "Moritz", "Niko", "Roland", "Thomas", "Harry", "Adrian", "Bob", "David", "Dirk", "Dorian", "Edmund", "Thorsten", "Wilhelm", "Erhard", "Eugen", "Felix", "Sebastian", "Tobias", "Phillip", "Gero", "Martin", "Stefan", "Manuel", "Holger", "Volker", "Isaak", "Jan", "Julian", "Karsten", "Linus", "Marcel", "Marc", "Norbert", "Patrick", "Rainer", "Reinhold", "Simon", "Tilmaan", "Tobias", "Oliver"};
		int index = getRandomNumber(0, firstNames.length - 1);
		return firstNames[index];
	}
	
	public Map<String, String> getDates () {
		Map <String, String> dates = new HashMap<String, String>();
		int dayMax = 31;
		int birthYear = getRandomNumber(1873, 1931);
		int birthMonth = getRandomNumber(1, 12);
		if(birthMonth == 2){
			dayMax = 28;
		} else if (birthMonth == 4 || birthMonth == 6 || birthMonth == 9 || birthMonth == 11) { 
			dayMax = 30;
		}
		int birthDay = getRandomNumber(1, dayMax);
		
		int deathRange = getRandomNumber(65, 75);
		int deathYear = birthYear + deathRange;
		int deathMonth = getRandomNumber(1, 12);
		dayMax = 31;
		if(deathMonth == 2){
			dayMax = 28;
		} else if (deathMonth == 4 || deathMonth == 6 || deathMonth == 9 || deathMonth == 11) { 
			dayMax = 30;
		}
		int deathDay = getRandomNumber(1, dayMax);
		
		String birthday = getDate(birthDay, birthMonth, birthYear);
		String dayOfDeath = getDate(deathDay, deathMonth, deathYear);
		dates.put("birthday", birthday);
		dates.put("dayOfDeath", dayOfDeath);
		return dates;
	}
	
	public String getDate (int day, int month, int year) {
		String date = "";
		if(day < 10) {
			date += "0" + String.valueOf(day);
		} else {
			date += String.valueOf(day);
		}
		
		if(month < 10) {
			date += "." + "0" + String.valueOf(month);
		} else {
			date += "." + String.valueOf(month);
		}
		
		date += "." + String.valueOf(year);
		
		return date;
	}
	
	public int getRandomNumber(int min, int max) {
		Random random = new Random();
		int randomNumber = random.nextInt((max - min) + 1) + min;
		return randomNumber;
	}
	
	public ArrayList<Integer> getCountryIds () throws SQLException {
		ArrayList<Integer> countryIds = new ArrayList<Integer>();
		String queryGetCountryIds = "SELECT * FROM countries";
		PreparedStatement prepareGetCountryIds = conn.prepareStatement(queryGetCountryIds);
		ResultSet resultGetCountryIds = prepareGetCountryIds.executeQuery();
		while(resultGetCountryIds.next()) {
			int id  = resultGetCountryIds.getInt("id");
			countryIds.add(id);
		}
		return countryIds;
	}
	
	public ArrayList<Integer> getDenominationIds () throws SQLException {
		ArrayList<Integer> denominationIds = new ArrayList<Integer>();
		String queryGetDenominationIds = "SELECT * FROM denominations";
		PreparedStatement prepareGetDenominationIds = conn.prepareStatement(queryGetDenominationIds);
		ResultSet resultGetDenominationIds = prepareGetDenominationIds.executeQuery();
		while(resultGetDenominationIds.next()) {
			int id  = resultGetDenominationIds.getInt("id");
			denominationIds.add(id);
		}
		return denominationIds;
	}
	
	public ArrayList<Integer> getProfessionalCategoryIds () throws SQLException {
		ArrayList<Integer> professionalCategoryIds = new ArrayList<Integer>();
		String queryGetProfessionalCategoryIds = "SELECT * FROM professional_categories";
		PreparedStatement prepareGetProfessionalCategoryIds = conn.prepareStatement(queryGetProfessionalCategoryIds);
		ResultSet resultGetProfessionalCategoryIds = prepareGetProfessionalCategoryIds.executeQuery();
		while(resultGetProfessionalCategoryIds.next()) {
			int id  = resultGetProfessionalCategoryIds.getInt("id");
			professionalCategoryIds.add(id);
		}
		return professionalCategoryIds;
	}
}
