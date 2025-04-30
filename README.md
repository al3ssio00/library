# library
CRUD project of a management system of a library 

# 📚 Library Database Project

This project defines and initializes a MySQL relational database for managing a library system. It includes data structures for branches, book availability, and potentially other related entities (e.g., books, authors, loans).

The main purpose of this SQL-based project is to set up a complete, working database using a single SQL dump file (`biblioteca.sql`) which includes both the schema (tables, relationships) and sample data entries.

## 📦 Contents

- `biblioteca.sql`: A full SQL dump that contains the schema definitions and initial data inserts for the `biblioteca` database.

## 🛠 Requirements

- MySQL Server 8.0+
- (Optional) phpMyAdmin for graphical database management
- PHP 8.2.28 (used during development and testing)

## 🧱 Database Structure

The SQL file creates and populates a database named `biblioteca`, which includes tables such as:

### `bibliotecaL`
Contains library branch information:
- `id`: primary key
- `filiale`: branch name
- `cap`: ZIP/postal code
- `indirizzo`: address

### `disponibilita`
Tracks the availability of books per branch:
- `id`: primary key
- `ref_biblioteca`: foreign key referencing `bibliotecaL`
- `ref_libro`: foreign key (likely referencing a book table)
- `count`: number of available copies

*(More tables are included in the SQL file.)*

## ⚙️ How to Use

You can import or execute the SQL file in two ways:

### 1. Using phpMyAdmin (GUI)

- Log into phpMyAdmin
- Create a new database named `biblioteca`
- Go to the **Import** tab
- Select `biblioteca.sql` and execute the import

### 2. Using MySQL terminal (CLI)

You can run the SQL file directly from the terminal:

```bash
mysql -u your_username -p -e "source path/to/biblioteca.sql"

Or after entering the MySQL shell:
mysql -u your_username -p

Then, inside the shell:
SOURCE path/to/biblioteca.sql;
