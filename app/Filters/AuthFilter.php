<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Check if user is verified
        if (session()->get('user_id')) {
            $userModel = new \App\Models\UserModel();
            $user = $userModel->find(session()->get('user_id'));

            // If user not found, log them out
            if (!$user) {
                session()->destroy();
                return redirect()->to('/login')->with('error', 'Your account no longer exists.');
            }

            if (!$user['is_enabled'] && $request->uri->getPath() !== 'resend-verification') {
                return redirect()->to('/dashboard')->with('warning', 'Please verify your email address first.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
} 