<?php

namespace app\core\db;

use app\core\Application;

class Database
{
    public \PDO $pdo;
    public function __construct(array $config)
    {
        $dsn = $config['dsn'] ?? '';
        $user = $config['username'] ?? '';
        $password = $config['password'] ??' ';
        $this->pdo = new \PDO($dsn, $user, $password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION); //turning on error mode
    }

    public function applyMigration()
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();
        $files = scandir(Application::$ROOT_DIR.'/migrations');
        $toApplyMigrations = array_diff($files, $appliedMigrations);
        $newMigrations =[];

        foreach ($toApplyMigrations as $migration){
            if($migration ==='.' || $migration ==='..'){
                continue;
            }
            require_once Application::$ROOT_DIR."/migrations/$migration";
            $className = pathinfo($migration,PATHINFO_FILENAME);

            $instance = new $className;
            $this->log("applying migration $migration");
            $instance->up();
            $this->log("applied migration $migration");

            $newMigrations[] = $migration;
        }
        if(!empty($newMigrations)){
            $this->saveMigrations($newMigrations);
        }else{
            $this->log('All migrations are applied');
        }

    }

    public function createMigrationsTable()
    {
        $statement ="CREATE TABLE IF NOT EXISTS migrations(
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        migration VARCHAR (255),
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
                    ) ENGINE=INNODB;";
        $this->pdo->exec($statement);
    }

    public function getAppliedMigrations()
    {
        $statement = $this->pdo->prepare("SELECT migration FROM migrations  ");
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    private function saveMigrations(array $migrations)
    {
       $str = implode(",", array_map(fn($m)=>"('$m')", $migrations));
       $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $str");
       $statement->execute();
    }
    public function prepare($sql)
    {
        return Application::$app->db->pdo->prepare($sql);
    }

    protected function log($message)
    {
        echo '['. date('Y-m-d H:i:s') . '] - '. $message . PHP_EOL;
    }
}