<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter PHPMailer Class
 *
 * This class enables SMTP email with PHPMailer
 *
 * @category    Libraries
 * @author      CodexWorld
 * @link        https://www.codexworld.com
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class PHPMailer_Lib
{
    public function __construct(){
        log_message('Debug', 'PHPMailer class is loaded.');
    }

    public function load(){
        // Include PHPMailer library files
        require_once APPPATH.'third_party/PHPMailer/Exception.php';
        require_once APPPATH.'third_party/PHPMailer/PHPMailer.php';
        require_once APPPATH.'third_party/PHPMailer/SMTP.php';
        
        $mail = new PHPMailer();

        $mail->isSMTP();
        $mail->SMTPDebug = 3;           
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAutoTLS = false;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;                              
        $mail->Port       = 587;                     
        $mail->SMTPAuth   = true;                                 
        $mail->Username   = 'f9281056@gmail.com';              
        $mail->Password   = 'hsiao841023a5289017';
        
        
        $mail->Timeout  =   10;

        $mail->isHTML(true);

        return $mail;
    }
}