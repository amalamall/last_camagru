<?php

class Users extends Controller
{
    public function __construct()
    {
        if (!isLoggedIn()) {
            redirect('authentification/login');
        }
        $this->userModel = $this->model('User');
    }

    public function index()
    {
        $this->view('users/index');
    }

    public function test_input($data = '')
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            redirect('posts/error');
        }
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }


    public function editprofile()
    {

        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST'  && isset($_POST['fullname'])  && isset($_POST['username'])  && isset($_POST['email'])) {
            // Process form

            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data = [
                'fullname' => trim($_POST['fullname']),
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'fullname_err' => '',
                'username_err' => '',
                'email_err' => ''
            ];
            if (isset($_POST['send_notif'])) {
                $data['send_notif'] = 1;
            } else {
                $data['send_notif'] = 0;
            }


            // Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            } else {
                //check email
                if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $data['email_err'] = "Invalid email format";
                } elseif (strlen($data['email']) < 16 || strlen($data['email'] > 100))
                    $data['email_err'] = 'Email lenght not valid';
                elseif ($this->userModel->findUserByEmail2($data['email'])) {
                    $data['email_err'] = 'Email is already taken';
                }
            }
            //echo strlen($data['fullname']);
    
            // Validate Name
            if (empty($data['fullname'])) {
                $data['fullname_err'] = 'Pleae enter name';
            } else {
                $data['fullname'] = $this->test_input($data['fullname']);
                //Checks if name only contains letters and whitespace
                if(strlen($data['fullname']) < 4 || strlen($data['fullname']) > 40)
                {                
                    $data['fullname_err'] = "fullname must at least 4 and less than 20";
                }   
                elseif (!preg_match("/^[a-zA-Z ]*$/", $data['fullname'])) {
                    $data['fullname_err'] = "Only letters and white space allowed";
                }
            }
            
            // validate Username
            if (empty($data['username'])) {
                $data['username_err'] = 'Pleae enter username';
            } else {
                $data['username'] = $this->test_input($data['username']);
                //Checks if name only contains letters and whitespace
                if(strlen($data['username']) < 4 || strlen($data['username']) > 40) 
                    $data['username_err'] = "username must at least 4 and less than 20";
                elseif (!preg_match("/^[a-zA-Z0-9_~\-!@#\$%\^&\*\(\)]*$/", $data['username'])) {
                    $data['username_err'] = "Only letters and number and some special characters allowed";
                }
            }

            // Make sure errors are empty
            if (empty($data['email_err']) && empty($data['fullname_err']) && empty($data['name_err']) && empty($data['username_err'])) {
                // edit profile
                if ($this->userModel->editprofile($data)) {
                    unset($_SESSION['user_email']);
                    unset($_SESSION['user_fullname']);
                    unset($_SESSION['user_username']);
                    unset($_SESSION['send_notif']);
                    $_SESSION['user_email'] = $data['email'];
                    $_SESSION['user_fullname'] = $data['fullname'];
                    $_SESSION['user_username'] = $data['username'];
                    $_SESSION['send_notif'] = $data['send_notif'];
                    flash('profile_edit_success', 'Your Profile was Edit Succesfuly !');
                    redirect('users/editprofile');
                } else {
                    redirect('users/error');
                }
            } else {
                // Load view with errors
                $this->view('users/editprofile', $data);
            }
        } else {
            // Init data
            $data = [
                'fullname' => '',
                'username' => '',
                'email' => '',
                'fullname_err' => '',
                'username_err' => '',
                'email_err' => ''
            ];

            // Load view
            $this->view('users/editprofile', $data);
        }
    }

    public function error()
    {
        $this->view('users/error');
    }

    public function changepassword()
    {
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['old_password'])  && isset($_POST['new_password'])  && isset($_POST['confirm_new_password'])) {
            // Process form

            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data['old_password'] = trim($_POST['old_password']);
            $data['new_password'] = trim($_POST['new_password']);
            $data['confirm_new_password'] = trim($_POST['confirm_new_password']);
            $data['old_password_err'] = '';
            $data['new_password_err'] = '';
            $data['confirm_new_password_err'] = '';
            // Validate Old Password
            if (empty($data['old_password'])) 
                $data['old_password_err'] = 'Pleae enter you old password';
            else if (strlen($data['old_password']) < 8 || strlen($data['old_password']) > 150) 
                $data['old_password_err'] = "Your Password Must Contain At Least 8 Characters!";
            elseif (!preg_match("/^[a-zA-Z0-9_~\-!@#\$%\^&\*\(\)]*$/",$data['old_password'])) {
                $data['old_password_err'] = "Only letters and number and some special characters allowed";
            }
            // Validate New Password
            if (empty($data['new_password'])) {
                $data['new_password_err'] = 'Pleae enter password';
            } else {
                $new_password = $this->test_input($data['new_password']);
                if (strlen($new_password) < 8 || strlen($new_password) > 150) {
                    $data['new_password_err'] = "Your Password Must Contain At Least 8 Characters!";
                }  elseif (!preg_match("/^[a-zA-Z0-9_~\-!@#\$%\^&\*\(\)]*$/",$new_password)) {
                    $data['new_password_err'] = "Only letters and number and some special characters allowed";
                } elseif (!preg_match("#[0-9]+#", $new_password)) {
                    $data['new_password_err'] = "Your Password Must Contain At Least 1 Number!";
                } elseif (!preg_match("#[A-Z]+#", $new_password)) {
                    $data['new_password_err'] = "Your Password Must Contain At Least 1 Capital Letter!";
                } elseif (!preg_match("#[a-z]+#", $new_password)) {
                    $data['new_password_err'] = "Your Password Must Contain At Least 1 Lowercase Letter!";
                }
            }
            // Validate New Confirm Password
            if (empty($data['confirm_new_password'])) {
                $data['confirm_new_password_err'] = 'Please confirm password';
            } else {
                $data['confirm_new_password'] = $this->test_input($data['confirm_new_password']);
                if (strlen($data['confirm_new_password']) < 8 || strlen($data['confirm_new_password']) > 150) 
                    $data['confirm_new_password_err'] = "Your Password Must Contain At Least 8 Characters!";
                elseif (!preg_match("/^[a-zA-Z0-9_~\-!@#\$%\^&\*\(\)]*$/",$data['confirm_new_password'])) {
                    $data['confirm_new_password_err'] = "Only letters and number and some special characters allowed";
                }
                elseif ($data['confirm_new_password'] != $data['new_password']) {
                    $data['confirm_new_password_err'] = 'Passwords do not match';
                }
            }

            // Make sure errors are empty
            if (empty($data['old_password_err']) && empty($data['new_password_err']) && empty($data['confirm_new_password_err'])) {
                // Validated

                // Hash Password
                $data['old_password'] = hash('whirlpool', $data['old_password']);
                $data['new_password'] = hash('whirlpool', $data['new_password']);

                // Check if user old password is correct

                if ($this->userModel->checkpassword_id_user($data['old_password'])) {
                    if ($this->userModel->updatepassword_id_user($data)) {
                        flash('password_change_success', 'You Password is Updated Succesfully ');
                        redirect('users/changepassword');
                    } else {
                        flash('password_change_error', 'Your Password  was not updated Succesfully', 'notification is-danger is-light');
                        redirect('users/changepassword');
                    }
                } else {
                    flash('password_change_error', 'Your old Password  does not match', 'notification is-danger is-light');
                    redirect('users/changepassword');
                }
            } else {
                // Load view with errors
                $this->view('users/changepassword', $data);
            }
        } else {
            // Init data
            $data = [
                'old_password' => '',
                'new_password' => '',
                'confirm_new_password' => '',
                'old_password_err' => '',
                'new_password_err' => '',
                'confirm_new_password_err' => '',
            ];

            // Load view
            $this->view('users/changepassword', $data);
        }
    }




    public function logout()
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_fullname']);
        unset($_SESSION['user_username']);
        unset($_SESSION['send_notif']);
        session_destroy();
        redirect('Authentification/login');
    }
}
