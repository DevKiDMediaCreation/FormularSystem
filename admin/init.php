<?php
define('DB_USER', 'root'); // Replace 'your_username' with your actual database username
define('DB_PASSWORD', 'root'); // Replace 'your_password' with your actual database password
define('DB_HOST', 'localhost');
define('DB_NAME', 'sys'); // Replace 'feedbacksys' with your actual database name

class Database {
    private $connection;

    public function connect() {
        $this->connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        if (!$this->connection) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    public function query($query) {
        return mysqli_query($this->connection, $query);
    }

    public function __destruct() {
        if ($this->connection) {
            mysqli_close($this->connection);
        }
    }
}

$database = new Database;
$database->connect();

// Check if the database stdFbs exists
$sql = "SHOW DATABASES LIKE 'stdFbs'";
$result = $database->query($sql);

if ($result->num_rows > 0) {
    echo "Database stdFbs already exists. TO change the configuration of the database, you need the rang 0.";
    die();
}

$config = parse_ini_file("config.ini");
$stdFbs = $config["stdFbs"];

$sql = "CREATE DATABASE IF NOT EXISTS `{$stdFbs}` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `{$stdFbs}`;";
$database->query($sql);


$sql = get_file_content("sql/init_stdFbs.sql");
$database->query($sql);

// Create new connection to the stdFbs database
$database->__destruct();

define('DB_NAME', 'stdFbs'); // Replace 'feedbacksys' with your actual database name
$database->connect();

$user = $config['user'];

#Split by ;
$user = explode(";", $user);

#Split the pass from the user by :
foreach ($user as $u) {
    $u = explode(":", $u);
    $username = $u[0];
    $password = $u[1];

    $sql = file_get_contents("sql/{$username}.sql");
    $sql = str_replace("pw", $password, $sql);
    $sql = str_replace("regstdFbs", $config["stdFbs"], $sql);

    $database->query($sql);
}

$db = $config['db'];
$sql = "INSERT INTO `information` (`name`, `value`) VALUES
	('database_name', '{$db}');";
$database->query($sql);

$sql = "CREATE DATABASE IF NOT EXISTS `{$db}` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `{$db}`;";
$database->query($sql);

$database->__destruct();
define('DB_NAME', $db);
$database->connect();

$sql = get_file_content("sql/init_.sql");
$database->query($sql);

echo "Database created successfully";