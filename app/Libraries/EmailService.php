<?php

namespace App\Libraries;

use CodeIgniter\Email\Email;

class EmailService
{
    protected $email;

    public function __construct()
    {
        $this->email = \Config\Services::email();
    }

    public function sendVerificationEmail($userEmail, $token)
    {
        $verificationLink = base_url("verify-email/{$token}");
        
        $this->email->setFrom('noreply@yourblog.com', 'Your Blog');
        $this->email->setTo($userEmail);
        $this->email->setSubject('Verify Your Email Address');
        
        $message = view('emails/verification', [
            'verificationLink' => $verificationLink
        ]);
        
        $this->email->setMessage($message);
        
        return $this->email->send();
    }
} 