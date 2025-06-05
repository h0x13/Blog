<?php

namespace App\Controllers;

use App\Models\EmailVerificationModel;
use App\Models\UserModel;
use App\Libraries\EmailService;

class VerificationController extends BaseController
{
    protected $emailVerificationModel;
    protected $userModel;
    protected $emailService;

    public function __construct()
    {
        $this->emailVerificationModel = new EmailVerificationModel();
        $this->userModel = new UserModel();
        $this->emailService = new EmailService();
    }

    public function verify($token)
    {
        $userId = $this->emailVerificationModel->verifyToken($token);

        if ($userId) {
            $this->userModel->update($userId, ['is_enabled' => true]);
            
            // If it's an AJAX request, return JSON response
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['verified' => true]);
            }
            
            return redirect()->to('/verification/success');
        }

        // If it's an AJAX request, return JSON response
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['verified' => false]);
        }

        return redirect()->to('/login')->with('error', 'Invalid or expired verification link.');
    }

    public function success()
    {
        return view('verification_success');
    }

    public function checkStatus()
    {
        $email = session()->get('temp_email');
        
        if (!$email) {
            return $this->response->setJSON(['verified' => false]);
        }

        $user = $this->userModel->where('email', $email)->first();
        
        return $this->response->setJSON([
            'verified' => $user['is_enabled'] ?? false
        ]);
    }

    public function resendVerification()
    {
        $userId = session()->get('user_id');
        
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Please login first.');
        }

        $user = $this->userModel->find($userId);
        
        if ($user['is_enabled']) {
            return redirect()->to('/dashboard')->with('info', 'Your email is already verified.');
        }

        $token = $this->emailVerificationModel->createVerification($userId);
        $this->emailService->sendVerificationEmail($user['email'], $token);

        return redirect()->to('/dashboard')->with('success', 'Verification email has been resent.');
    }
} 