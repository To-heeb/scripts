<?php


/**
 * Switch between two php versions in xampp
 *
 * @author     Oyekola Toheeb <https://github.com/To-heeb>
 * @license    MIT License
 * @copyright  2023 Oyekola Toheeb
 * @link       
 */

class SwitchVersion
{

    private $directory;
    private $curr_apache;
    private $curr_php;
    private $php_version;

    public function __construct($args)
    {
        $this->directory = dirname(__FILE__);
        $this->curr_apache  = $this->directory . DIRECTORY_SEPARATOR . 'apache';
        $this->curr_php = $this->directory . DIRECTORY_SEPARATOR . 'php';

        $php_version = phpversion();
        $php_version = explode('.', $php_version);
        $this->php_version = $php_version[0];

        // testing purpose only
        // if (explode('-', $args[1])[1] == 8) {
        //     $this->php_version = 7;
        // }

        // echo ('to install: ' . explode('-', $args[1])[1]) . PHP_EOL;
        // echo ('current: ' . $this->php_version);
        // die();

        $this->stop_xampp();
        $this->parse_args($args);
        $this->start_xampp();
    }

    private function parse_args(array $args)
    {

        if (explode('-', $args[1])[1] === $this->php_version) {
            echo 'php version ' . $this->php_version . ' is currently installed' .  PHP_EOL;
            return;
        }
        switch ($args[1]) {

                // php switch.php -7
            case '-7':
                $this->switch_php_versions('seven', 7);
                break;

                // php switch.php -8 
            case '-8':
                $this->switch_php_versions('eight', 8);
                break;
            default:
                throw new Exception('Unknown argument passed: ' . $args[1]);
        }
    }


    private function switch_php_versions($version_name, $version_number)
    {
        $php_version = 'php_' . $version_name;
        $apache_version = 'apache_' . $version_name;
        ${'php_' . $version_name} = $this->directory . DIRECTORY_SEPARATOR . 'php_' . $version_number;
        ${'apache_' . $version_name} = $this->directory . DIRECTORY_SEPARATOR . 'apache_' . $version_number;
        if (is_dir(${'apache_' . $version_name}) && is_dir(${'php_' . $version_name}) && is_dir($this->curr_apache) && is_dir($this->curr_php)) {

            if ($this->php_version == $version_number) {
                echo "The php version in use is php " . phpversion() .  PHP_EOL;
            } else {

                try {
                    $installed_version = $version_number;
                    //code...
                    $rename_current_apache = rename($this->curr_apache, $this->directory . DIRECTORY_SEPARATOR . 'apache' . '_' . $this->php_version);
                    $rename_current_php =  rename($this->curr_php, $this->directory . DIRECTORY_SEPARATOR . 'php' . '_' . $this->php_version);
                    ${'make_php_' . $version_name . '_current'} =  rename($this->directory . DIRECTORY_SEPARATOR . 'php' . '_' . $installed_version, $this->curr_php);
                    ${'make_apache_' . $version_name . '_current'} = rename($this->directory . DIRECTORY_SEPARATOR . 'apache' . '_' . $installed_version, $this->curr_apache);

                    if ($rename_current_apache && $rename_current_php &&  ${'make_apache_' . $version_name . '_current'} && ${'make_php_' . $version_name . '_current'}) {
                        echo "php " . $version_number . " successfully installed."  . PHP_EOL;
                    } else {

                        // revert renamings;
                        if (${'make_php_' . $version_name . '_current'}) rename($this->curr_php, $this->directory . DIRECTORY_SEPARATOR . 'php' . '_' . $this->php_version);
                        if (${'make_apache_' . $version_name . '_current'})  rename($this->curr_php, $this->directory . DIRECTORY_SEPARATOR . 'apache' . '_' . $this->php_version);

                        if ($rename_current_apache) rename($this->directory . DIRECTORY_SEPARATOR . 'apache' . '_' . $this->php_version, $this->curr_apache);
                        if ($rename_current_php)   rename($this->directory . DIRECTORY_SEPARATOR . 'php' . '_' . $this->php_version, $this->curr_php);


                        echo "Error installing.";
                    }
                } catch (\Throwable $th) {
                    throw new Exception($th);
                }
            }
        } else {
            //echo $php_version . " " . $ ${'php_' . $version_name};
            throw new Exception('No such php version in xampp directory: ' . $version_number);
        }
    }

    public function start_xampp()
    {
        echo "Starting xampp...." . PHP_EOL;
        shell_exec($this->directory . "/xampp_start.exe");
        echo "xampp running..." . PHP_EOL;
    }

    private function stop_xampp()
    {
        echo "Stoping xampp...." . PHP_EOL;
        shell_exec($this->directory . "/xampp_stop.exe");
        echo "xampp down." . PHP_EOL;
    }
}



$switch = new SwitchVersion($argv);


/**************************************
 * 
 * 
 * Command to run script; $ php switch.php -version_first number
 *                      e.g $ php switch.php -8
 * 
 * 
 **************************************/

/**
 * TODO
 * 
 * Improve script to bw able to swicth between multiple versions e.g three and above
 * fix xampp error
 * check if xampp is running before putting it on.
 * 
 */
