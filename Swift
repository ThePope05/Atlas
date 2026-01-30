<?php
define("TS_Reset", "\033[0m");

$terminalColors = [
    "red" => "\033[31m",
    "green" => "\033[32m",
    "yellow" => "\033[33m",
    "blue" => "\033[34m",
    "magenta" => "\033[35m",
    "cyan" => "\033[36m",
    "white" => "\033[37m",
];

$terminalStyles = [
    "bold" => "\033[1m",
    "underline" => "\033[4m",
    "blink" => "\033[5m",
    "reverse" => "\033[7m",
    "hidden" => "\033[8m",
];

writeTerminalLine([["bold", "underline", "reverse"], "magenta"], "Swift activated");

if (isset($argv[1])) {
    if (strtoupper($argv[1]) == "CREATE") {
        if (isset($argv[2])) {
            $model = $argv[2] . "Model";
            $controller = $argv[2];

            if (in_array("-m", $argv) || in_array("-a", $argv)) {
                writeTerminalLine(["bold", "green"], "Creating new model");

                //Create the model
                $modelFile = fopen("./app/libraries/components/newModel.php", "r") or die("Unable to open file!");
                $modelContent = fread($modelFile, filesize("./app/libraries/components/newModel.php"));
                fclose($modelFile);

                $modelContent = str_replace("MODEL_NAME", $model, $modelContent);

                $newModelFile = fopen("./app/models/" . $model . ".php", "w") or die("Unable to open file!");
                fwrite($newModelFile, $modelContent);
                fclose($newModelFile);
            }

            if (in_array("-c", $argv) || in_array("-a", $argv)) {
                writeTerminalLine(["bold", "green"], "Creating new controller");

                //Create the controller
                $controllerFile = fopen("./app/libraries/components/newController.php", "r") or die("Unable to open file!");
                $controllerContent = fread($controllerFile, filesize("./app/libraries/components/newController.php"));
                fclose($controllerFile);

                $controllerContent = str_replace("CONTROLLER_NAME", $controller, $controllerContent);

                $newControllerFile = fopen("./app/controllers/" . $controller . ".php", "w") or die("Unable to open file!");
                fwrite($newControllerFile, $controllerContent);
                fclose($newControllerFile);
            }
            if (in_array("-v", $argv) || in_array("-a", $argv)) {
                writeTerminalLine(["bold", "green"], "Creating new view and folder");

                //Create the view
                $viewFile = fopen("./app/libraries/components/newView.php", "r") or die("Unable to open file!");
                $viewContent = fread($viewFile, filesize("./app/libraries/components/newView.php"));
                fclose($viewFile);

                mkdir("./app/views/" . $controller, 0777, true);

                $newViewFile = fopen("./app/views/" . $controller . "/index.php", "w") or die("Unable to open file!");
                fwrite($newViewFile, $viewContent);
                fclose($newViewFile);
            }
            if (in_array("-d", $argv)) {
                writeTerminalLine(["bold", "green"], "Creating new database file");

                //Create the database
                $dbFile = fopen("./app/libraries/components/newDatabaseScript.sql", "r") or die("Unable to open file!");
                $dbContent = fread($dbFile, filesize("./app/libraries/components/newDatabaseScript.sql"));
                fclose($dbFile);

                $allDbFiles = glob('./app/db/*.sql');
                $fileCount = count($allDbFiles) + 1;
                $fileNumber = ($fileCount < 10) ? (string)('0' . $fileCount) : $fileCount;

                $newDbFile = fopen("./app/db/" . $fileNumber . "_" . $controller . ".sql", "w") or die("Unable to open file!");
                fwrite($newDbFile, $dbContent);
                fclose($newDbFile);
            }

            writeTerminalLine(["bold", "green"], "Finished task...");
            cancelScript();
        }
    } else if (strtoupper($argv[1]) == "DATABASE") {
        writeTerminalLine(["bold", "green"], "Executing database");

        require_once './app/config/config.php';

        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            writeTerminalLine(["bold", "green"], "Connected to database");

            if (!isset($argv[2])) {
                writeTerminalLine(["bold", "yellow"], "Database already exists, this could remove all data. Proceed? (y/n):");

                $proceed = readline('');

                if ($proceed == 'y') {
                    writeTerminalLine(["bold", "green"], "Proceeding...");
                } else {
                    cancelScript();
                }
            }
        } catch (PDOException $e) {
            writeTerminalLine(["bold", "red"], "Connection failed: " . $e->getMessage());
            writeTerminalLine(["bold", "yellow"], "Possible database creation required");

            writeTerminalLine(["bold", "yellow"], "Do you want to create database? (y/n):");
            $createDb = readline('');

            if ($createDb == 'y') {
                try {
                    $pdo = new PDO('mysql:host=' . DB_HOST, DB_USER, DB_PASS);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $sql = 'CREATE DATABASE ' . DB_NAME . '; USE ' . DB_NAME;
                    $pdo->exec($sql);

                    writeTerminalLine(["bold", "green"], "Database created successfully");
                } catch (PDOException $e) {
                    writeTerminalLine(["bold", "red"], "Database creation failed: " . $e->getMessage());
                    cancelScript();
                }
            } else {
                cancelScript();
            }
        }

        if (isset($argv[2])) {
            $sqlFile = glob('./app/db/' . $argv[2] . '*.sql');
            $sql = file_get_contents($sqlFile[0]);
            $pdo->exec($sql);

            $cleanName = str_replace('.sql', '', $sqlFile[0]);
            $cleanName = str_replace('./app/db/', '', $cleanName);
            writeTerminalLine(["bold", "green"], $cleanName . ' executed successfully' . PHP_EOL);
            cancelScript();
        } else {
            $allSqlFiles = glob('./app/db/*.sql');

            foreach ($allSqlFiles as $sqlFile) {
                $sql = file_get_contents($sqlFile);
                $pdo->exec($sql);

                $cleanName = str_replace('.sql', '', $sqlFile);
                $cleanName = str_replace('./app/db/', '', $cleanName);
                writeTerminalLine(["bold", "green"], $cleanName . ' executed successfully' . PHP_EOL);
            }

            writeTerminalLine(["bold", "green"], "Finished task...");
            cancelScript();
        }
    } else if (strtoupper($argv[1]) == "LOCALHOST") {
        include_once 'app/config/config.php';

        $server = "localhost:" . PORT;

        removeAllTerminalStyles();
        shell_exec(sprintf('php -S %s router.php', $server) . " -c app/config/php.ini");
    } else {
        writeTerminalLine(["bold", "red"], "Command not found" . PHP_EOL);
        writeTerminalLine(["bold", "yellow"], "Available commands:");
        writeTerminalLine(["bold", "yellow"], " - create -m -c -v -a -d");
        writeTerminalLine(["bold", "yellow"], " - database [filenumber]");
        writeTerminalLine(["bold", "yellow"], " - localhost");
        cancelScript();
    }
}

function writeTerminalLine($style = [["bold"], "red"], $text = "No Text Specified")
{
    global $terminalColors;
    global $terminalStyles;

    echo "\n";

    if (!is_array($style[0])) {
        $style[0] = [$style[0]];
    }
    foreach ($style[0] as $styleName) {
        echo $terminalStyles[$styleName];
    }


    echo $terminalColors[$style[1]] . $text . "\n" . TS_Reset;
    echo $terminalStyles["bold"] . $terminalColors["red"];
}

function removeAllTerminalStyles()
{
    echo TS_Reset;
}

function cancelScript($message = "Stopping Swift")
{
    echo TS_Reset . "\n";
    writeTerminalLine([["bold", "underline", "reverse"], "magenta"], $message);
    echo TS_Reset . "\n";
    exit();
}
