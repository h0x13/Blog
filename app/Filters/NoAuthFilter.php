<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class NoAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // If user is logged in, redirect to appropriate page
        if (session()->get('isLoggedIn')) {
            $userRole = session()->get('user_role');
            
            // Redirect admin to dashboard, regular users to blogs
            if ($userRole === 'admin') {
                return redirect()->to('/dashboard');
            } else {
                return redirect()->to('/blogs');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
} 