import xml.etree.ElementTree as ET

'''
Check data and detect mistakes in names and surnames of the born people.
Arrange these mistakes.

Fechas erroneas -> nacimientos en dia 31 etc con meses que no tienen 31 dias
Nombres parecidos se han cambiado al que mayor tienen

Nombres:
    
Catalina 10 -> Cathalina 268
Jaime 4 -> Jayme 118
Bartolome 7 -> Bartholome 106
Anna 3 -> Ana 403
Sense nom -> ""
Josef 56 Joseph 3 -> Jose 60
Margharita 6 -> Margarita 142
Rafel 2 -> Rafael 42
Mateo 2 Matheo 31 -> Matheu 49

Apellido:
    
Llompart 15 -> Llompard 88  
Beltran 10 -> Bertran 108
Desconegut 100 -> ""
x 1 -> ""
'''

# Parse the XML file
tree = ET.parse('AllPeople.xml')
root = tree.getroot()

# Open a file to write SQL statements
with open('people_data.sql', 'w') as sql_file:
    # Create table definition SQL statement
    table_definition = (
        "CREATE TABLE people_table ("
        "id INT AUTO_INCREMENT PRIMARY KEY, "
        "gender CHAR(1), "
        "name VARCHAR(255), "
        "alter_name VARCHAR(255), "
        "surname1 VARCHAR(255), "
        "surname2 VARCHAR(255), "
        "birth DATE, "
        "fathername VARCHAR(255), "
        "fathersurname1 VARCHAR(255), "
        "fathersurname2 VARCHAR(255), "
        "mothername VARCHAR(255), "
        "mothersurname1 VARCHAR(255), "
        "mothersurname2 VARCHAR(255), "
        "father_grandfathername VARCHAR(255), "
        "father_grandmothername VARCHAR(255), "
        "mother_grandfathername VARCHAR(255), "
        "mother_grandmothername VARCHAR(255)"
        ");\n"
    )
    sql_file.write(table_definition)

    # Iterate through each person in the XML
    for person in root.findall('person'):
        # Extract data for each person
        gender = person.find('gender').text
        name = person.find('name').text
        alter = person.find('alter').text if person.find('alter') is not None else ''
        surname1 = person.find('surname1').text
        surname2 = person.find('surname2').text
        birth = person.find('birth').text
        fathername = person.find('fathername').text
        fathersurname1 = person.find('fathersurname1').text
        fathersurname2 = person.find('fathersurname2').text
        mothername = person.find('mothername').text
        mothersurname1 = person.find('mothersurname1').text
        mothersurname2 = person.find('mothersurname2').text
        father_grandfathername = person.find('father_grandfathername').text
        father_grandmothername = person.find('father_grandmothername').text
        mother_grandfathername = person.find('mother_grandfathername').text
        mother_grandmothername = person.find('mother_grandmothername').text

        # Generate SQL insert statement
        sql_insert = (
            f"INSERT INTO people_table (gender, name, alter_name, surname1, surname2, birth, "
            f"fathername, fathersurname1, fathersurname2, mothername, mothersurname1, mothersurname2, "
            f"father_grandfathername, father_grandmothername, mother_grandfathername, mother_grandmothername) "
            f"VALUES ('{gender}', '{name}', '{alter}', '{surname1}', '{surname2}', '{birth}', "
            f"'{fathername}', '{fathersurname1}', '{fathersurname2}', '{mothername}', '{mothersurname1}', '{mothersurname2}', "
            f"'{father_grandfathername}', '{father_grandmothername}', '{mother_grandfathername}', '{mother_grandmothername}');\n"
        )

        # Write the SQL insert statement to the file
        sql_file.write(sql_insert)
