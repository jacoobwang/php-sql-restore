<?php
class DB
{
    public $charset = 'utf8';

    private $_pdo = null;

    private $db_config = array(
        'dbhost' => '127.0.0.1',
        'dbuser' => 'root',
        'dbpass' => '123456',
        'dbname' => 'test',
        'dbport' => 3306,
    );

    private function createPdoInstance()
    {
        $dbhost = $this->db_config['dbhost'];
        $dbuser = $this->db_config['dbuser'];
        $dbpass = $this->db_config['dbpass'];
        $dbname = $this->db_config['dbname'];
        $dbport = $this->db_config['dbport'];
        try
        {
            $this->_pdo = new PDO("mysql:host=$dbhost;dbname=$dbname;port=$dbport", $dbuser, $dbpass);
            $this->initConnection($this->_pdo);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    protected function initConnection($pdo)
    {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

        if ($this->charset !== null) {
            $driver = strtolower($pdo->getAttribute(PDO::ATTR_DRIVER_NAME));
            if (in_array($driver, array('pgsql', 'mysql', 'mysqli'))) {
                $pdo->exec('SET NAMES ' . $pdo->quote($this->charset));
            }
        }
    }

    public function _executeSQLFile($sFileName)
    {
        $aMessages = array();
        $sCommand = '';

        if (!is_readable($sFileName)) {
            return false;
        } else {
            $aLines = file($sFileName);
        }
        foreach ($aLines as $sLine) {
            $sLine = rtrim($sLine);
            $iLineLength = strlen($sLine);
            $_letter = substr($sLine, 0, 2);

            if ($iLineLength && $_letter != '/*' && $sLine[0] != '#' && $_letter != '--') {
                if (substr($sLine, $iLineLength - 1, 1) == ';') {
                    $line = substr($sLine, 0, $iLineLength - 1);
                    $sCommand .= $sLine;
                    echo $sCommand;
                    echo "\r\n";
                    echo "----------------------------------------------------------------------------------------------";
                    echo "\r\n";
                    try {
                        $this->createPdoInstance();
                        $st = $this->_pdo->prepare($sCommand)->execute();
                        if ($st) {
                            echo "\033[36m 执行成功......... \033[0m";
                            echo "\r\n";
                        }
                    } catch (Exception $e) {
                        $aMessages[] = "Executing: " . $sCommand . " failed! Reason: " . $e;
                        var_dump($e);
                        die();
                    }
                    $sCommand = '';
                    sleep(1);
                } else {
                    $sCommand .= $sLine;
                }
            }
        }
        return $aMessages;
    }
}
