<?php
        // Initialize variables
    $found            = 0;
    $file             = "server.php";
    $commands    = array();

    // Get running processes.
    exec("ps w", $commands);

        // If processes are found
    if (count($commands) > 0) {

        foreach ($commands as $command) {
            if (strpos($command, $file) === false) {
                               // Do nothin'
            }
            else {
                               // Let's count how many times the file is found.
                $found++;
            }
        }
    }

    // If the instance of the file is found more than once.
    if ($found == 1) {        
        die("Server process is running checked by ".date("F j, Y, g:i a").".\n");
    }else{       
        exec("nohup php server.php >/dev/null 2>&1 &");
        die("Server process is start by ".date("F j, Y, g:i a")."\n");
    }

        /**
        * 
        * Regular process here...
        * 
        */
?>