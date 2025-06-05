<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\CategoryModel;
use App\Models\BlogModel;
use App\Models\PasswordResetModel;

class Home extends BaseController
{
    protected $userModel;
    protected $categoryModel;
    protected $blogModel;
    
    public function __construct()
    {
        $this->userModel = model(UserModel::class);
        $this->categoryModel = model(CategoryModel::class);
        $this->blogModel = model(BlogModel::class);
    }
    
    public function index(): string
    {
        $data = ['categories' => $this->categoryModel->findAll()];
        return view('index', $data);
    }

    public function about()
    {
        return view('about');
    }

    public function dashboard()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userModel = new \App\Models\UserModel();
        $user = $userModel->find(session()->get('user_id'));

        if (!$user['is_enabled']) {
            return view('unverified');
        }

        if ($user['role'] !== 'admin') {
            return redirect()->to('/')
                ->with('error', 'You do not have permission to access this page.');
        }

        // Get active users (users who have logged in within the last 24 hours)
        $auditLogModel = new \App\Models\AuditLogModel();
        $activeUsers = $auditLogModel->select('users.*, audit_logs.created_at as last_activity')
            ->join('users', 'users.user_id = audit_logs.user_id')
            ->where('audit_logs.action_type', 'login')
            ->where('audit_logs.created_at >=', date('Y-m-d H:i:s', strtotime('-24 hours')))
            ->groupBy('users.user_id')
            ->orderBy('audit_logs.created_at', 'DESC')
            ->findAll();

        // Get recent activities for the bordered table
        $recentActivities = $auditLogModel->select('audit_logs.*, users.first_name, users.last_name')
            ->join('users', 'users.user_id = audit_logs.user_id')
            ->orderBy('audit_logs.created_at', 'DESC')
            ->limit(5)
            ->findAll();

        $data = [
            'users_count' => $this->userModel->countAllResults(),
            'categories_count' => $this->categoryModel->countAllResults(),
            'published_blogs_count' => $this->blogModel->where('visibility', 'public')->countAllResults(),
            'unpublished_blogs_count' => $this->blogModel->where('visibility', 'private')->countAllResults(),
            'active_users' => $activeUsers,
            'recent_activities' => $recentActivities
        ];
        return view('dashboard', $data);
    }

    public function register() 
    {
        return view('register');
    }

    public function login() 
    {
        return view('login');
    }

    public function processRegistration()
    {
        $rules = [
            'first_name' => 'required|min_length[2]|max_length[50]',
            'last_name' => 'required|min_length[2]|max_length[50]',
            'middle_name' => 'permit_empty|min_length[2]|max_length[50]',
            'email' => 'required|valid_email',
            'password' => 'required|min_length[8]',
            'gender' => 'permit_empty|in_list[male,female]',
            'birthdate' => 'permit_empty|valid_date'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', $this->validator->getErrors());
        }

        try {
            $userId = $this->userModel->register($this->request->getPost());

            if ($userId) {
                // Store email in session for verification page
                session()->set('temp_email', $this->request->getPost('email'));
                return redirect()->to('/verification-pending');
            }

            // If registration failed because email is already verified
            $existingUser = $this->userModel->where('email', $this->request->getPost('email'))->first();
            if ($existingUser && $existingUser['is_enabled']) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'This email is already registered and verified. Please login instead.');
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Registration failed. Please try again.');
        } catch (\Exception $e) {
            log_message('error', '[Home::processRegistration] Error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred during registration. Please try again.');
        }
    }

    public function verificationPending()
    {
        if (!session()->has('temp_email')) {
            return redirect()->to('/login');
        }
        return view('verification_pending', [
            'success' => 'Registration successful! Please verify your email to continue.'
        ]);
    }

    public function processLogin()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', $this->validator->getErrors());
        }

        $userModel = new \App\Models\UserModel();
        $user = $userModel->where('email', $this->request->getPost('email'))->first();

        // Debug user data
        log_message('debug', 'Login Attempt - User Data: ' . print_r($user, true));
        log_message('debug', 'Login Attempt - Input Password: ' . $this->request->getPost('password'));

        if (!$user) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Invalid email or password');
        }

        // Convert to array if it's an object
        $userData = is_object($user) ? $user->toArray() : $user;

        // Debug password verification
        $passwordVerified = password_verify($this->request->getPost('password'), $userData['password']);
        log_message('debug', 'Login Attempt - Password Verification Result: ' . ($passwordVerified ? 'true' : 'false'));
        log_message('debug', 'Login Attempt - Stored Password Hash: ' . $userData['password']);

        if (!$passwordVerified) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Invalid email or password');
        }

        if (!$userData['is_enabled']) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Please verify your email address first');
        }

        session()->set([
            'user_id' => $userData['user_id'],
            'isLoggedIn' => true,
            'user_name' => $userData['first_name'] . ' ' . $userData['last_name'],
            'email' => $userData['email'],
            'image' => $userData['image'],
            'user_role' => $userData['role']
        ]);

        // Log login action
        $auditLogModel = new \App\Models\AuditLogModel();
        $auditLogModel->logUserAction(
            $userData['user_id'],
            'login',
            'user',
            null,
            'User logged in'
        );

        // Redirect based on role
        if ($userData['role'] === 'admin') {
            return redirect()->to('/dashboard');
        } else {
            return redirect()->to('/blogs');
        }
    }

    public function logout()
    {
        if (session()->get('isLoggedIn')) {
            // Log logout action before destroying session
            $auditLogModel = new \App\Models\AuditLogModel();
            $auditLogModel->logUserAction(
                session()->get('user_id'),
                'logout',
                'user',
                null,
                'User logged out'
            );
        }

        session()->destroy();
        return redirect()->to('/login');
    }

    public function forgotPassword()
    {
        return view('forgot_password');
    }

    public function processForgotPassword()
    {
        $email = $this->request->getPost('email');
        $userModel = new UserModel();
        $passwordResetModel = new PasswordResetModel();

        $user = $userModel->where('email', $email)->first();

        if ($user) {
            $token = $passwordResetModel->createReset($user['user_id']);
            
            // Send email with reset link
            $email = \Config\Services::email();
            $email->setTo($user['email']);
            $email->setSubject('Password Reset Request');
            $email->setMessage(view('emails/reset_password', ['token' => $token]));
            $email->send();

            return redirect()->to('/login')->with('success', 'Password reset instructions have been sent to your email.');
        }

        return redirect()->back()->with('error', 'No account found with that email address.');
    }

    public function resetPassword($token)
    {
        if (!$token) {
            return redirect()->to('/login')->with('error', 'Invalid reset token.');
        }

        $passwordResetModel = new PasswordResetModel();
        // Only check if token exists and is not expired
        $reset = $passwordResetModel->where('token', $token)
                                  ->where('expires_at >', date('Y-m-d H:i:s'))
                                  ->first();

        if (!$reset) {
            return redirect()->to('/login')->with('error', 'Invalid or expired reset token.');
        }

        // Show the view if the token is valid
        return view('reset_password', ['token' => $token]);
    }

    public function processResetPassword()
    {
        $rules = [
            'token' => 'required',
            'password' => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $token = $this->request->getPost('token');
        $password = $this->request->getPost('password');
        $passwordResetModel = new PasswordResetModel();
        $userId = $passwordResetModel->verifyToken($token);

        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Invalid or expired reset token.');
        }

        $userModel = new UserModel();
        
        // Debug log the password reset
        log_message('debug', 'Password Reset - User ID: ' . $userId);
        log_message('debug', 'Password Reset - Original Password: ' . $password);
        
        // Get the user's current password hash before update
        $user = $userModel->find($userId);
        log_message('debug', 'Password Reset - Current Password Hash: ' . $user['password']);
        
        $updateResult = $userModel->update($userId, [
            'password' => $password
        ]);
        
        // Get the user's new password hash after update
        $user = $userModel->find($userId);
        log_message('debug', 'Password Reset - New Password Hash: ' . $user['password']);
        log_message('debug', 'Password Reset - Update Result: ' . ($updateResult ? 'true' : 'false'));

        return redirect()->to('/login')->with('success', 'Your password has been reset successfully.');
    }

    public function search()
    {
        $query = $this->request->getGet('query');
        $blogModel = new BlogModel();
        
        // Search in title and content
        $blogs = $blogModel->like('title', $query)
                          ->orLike('content', $query)
                          ->where('visibility', 'public')
                          ->findAll(5); // Limit to 5 results
        
        return $this->response->setJSON($blogs);
    }
}
