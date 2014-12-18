import java.io.BufferedReader;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.text.*;
import java.util.Date;
 
public class ReadCVS {
 
	Connection conn;
	
  public static void main(String[] args) throws ParseException, ClassNotFoundException, SQLException {
 
    ReadCVS obj = new ReadCVS();
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
	// change path  
    String csvFile = "..\\..\\data\\data.csv";
    
    BufferedReader br = null;
    String line = "";
    String cvsSplitBy = ",";
    
    
    try {
    	
        br = new BufferedReader(new InputStreamReader(new FileInputStream(csvFile), "UTF8"));
        br.readLine();
        int personId = 0;
        int countryOfBirthId;
        int denominationId;
        int professionalCategoryId;
        while ((line = br.readLine()) != null) {
            String[] splittedRow = line.split(cvsSplitBy);

            String firstName = splittedRow[0];
            String lastName = splittedRow[1];
            String birthday = splittedRow[2];
            String dayOfDeath = splittedRow[3];
            String denomination = splittedRow[4];
            String professionalCategory = splittedRow[5];
            String profession = splittedRow[6];
            String placeOfBirth = splittedRow[7];
            String countryOfBirth = splittedRow[8];
            
            denominationId = getDenominationId(denomination);
            
            if(denominationId == -1) {
            	createNewDenomination(denomination);
            	denominationId = getDenominationId(denomination);
            }
            
            professionalCategoryId = getProfessionalCategoryId(professionalCategory);
        	
            if(professionalCategoryId == -1) {
            	createNewProfessionalCategory(professionalCategory);
            	professionalCategoryId = getProfessionalCategoryId(professionalCategory);
            }
            
            countryOfBirthId = getCountryId(countryOfBirth);
        	
            if(countryOfBirthId == -1) {
            	createNewCountry(countryOfBirth);
            	countryOfBirthId = getCountryId(countryOfBirth);
            }
            
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

            for(int j = 9; j < splittedRow.length; j=j+4) {
            	int migrationCountryId;
            	String migrationCountry = splittedRow[j];
            	String migrationCity = splittedRow[j+1];
            	String migrationMonth  = splittedRow[j+2];
            	String migrationYear  = splittedRow[j+3];
            	
            	migrationCountryId = getCountryId(migrationCountry);
            	
                if(migrationCountryId == -1) {
                	createNewCountry(migrationCountry);
                	migrationCountryId = getCountryId(migrationCountry);
                }
            	
            	String insertMigration = "INSERT INTO migrations(city, country_id, month, year, person_id) VALUES (?, ?, ?, ?, ?)";
          	  	PreparedStatement prepareInsertMigration = conn.prepareStatement(insertMigration);
          	  	prepareInsertMigration.setString(1, migrationCity);
          	  	prepareInsertMigration.setInt(2, migrationCountryId);
          	  	prepareInsertMigration.setInt(3, Integer.parseInt(migrationMonth));
          	  	prepareInsertMigration.setInt(4, Integer.parseInt(migrationYear));
          	  	prepareInsertMigration.setInt(5, personId);
          	  	prepareInsertMigration.executeUpdate();
            }
        }
 
    } catch (FileNotFoundException e) {
        e.printStackTrace();
    } catch (IOException e) {
        e.printStackTrace();
    } catch (Exception e) {
    	e.printStackTrace();
    } finally {
        if (br != null) {
            try {
                br.close();
            } catch (IOException e) {
                e.printStackTrace();
            }
        }
    }
 
  }
  
  public int getCountryId (String country) throws SQLException {
	  String queryCheckCountry = "SELECT * FROM countries WHERE country = ?";
	  PreparedStatement prepareCheckCountry = conn.prepareStatement(queryCheckCountry);
	  prepareCheckCountry.setString(1, country);
	  ResultSet resultCheckCountry = prepareCheckCountry.executeQuery();
	  if(!resultCheckCountry.next()) {
		  return -1;
	  } else {
		  return resultCheckCountry.getInt(1);
	  }
  }
 
  public void createNewCountry (String country) throws SQLException {
	  String insertCountry = "INSERT INTO countries(country) VALUES (?)";
	  PreparedStatement prepareInsertCountry = conn.prepareStatement(insertCountry);
	  prepareInsertCountry.setString(1, country);
  	  prepareInsertCountry.executeUpdate();
  }
  
  public int getDenominationId (String denomination) throws SQLException {
	  String queryCheckDenomination = "SELECT * FROM denominations WHERE denomination = ?";
	  PreparedStatement prepareCheckDenomination = conn.prepareStatement(queryCheckDenomination);
	  prepareCheckDenomination.setString(1, denomination);
	  ResultSet resultCheckDenomination = prepareCheckDenomination.executeQuery();
	  if(!resultCheckDenomination.next()) {
		  return -1;
	  } else {
		  return resultCheckDenomination.getInt(1);
	  }
  }
 
  public void createNewDenomination (String denomination) throws SQLException {
	  String insertDenomination = "INSERT INTO denominations(denomination) VALUES (?)";
	  PreparedStatement prepareInsertDenomination = conn.prepareStatement(insertDenomination);
	  prepareInsertDenomination.setString(1, denomination);
  	  prepareInsertDenomination.executeUpdate();
  }
  
  public int getProfessionalCategoryId (String professionalCategory) throws SQLException {
	  String queryCheckProfessionalCategory = "SELECT * FROM professional_categories WHERE professional_category = ?";
	  PreparedStatement prepareCheckProfessionalCategory = conn.prepareStatement(queryCheckProfessionalCategory);
	  prepareCheckProfessionalCategory.setString(1, professionalCategory);
	  ResultSet resultCheckDenomination = prepareCheckProfessionalCategory.executeQuery();
	  if(!resultCheckDenomination.next()) {
		  return -1;
	  } else {
		  return resultCheckDenomination.getInt(1);
	  }
  }
 
  public void createNewProfessionalCategory (String professionalCategory) throws SQLException {
	  String insertProfessionalCategory = "INSERT INTO professional_categories(professional_category) VALUES (?)";
	  PreparedStatement prepareInsertProfessionalCategory = conn.prepareStatement(insertProfessionalCategory);
	  prepareInsertProfessionalCategory.setString(1, professionalCategory);
  	  prepareInsertProfessionalCategory.executeUpdate();
  }
  
}