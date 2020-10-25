<?php
class Authentification extends Controller
{
    public function __construct()
    {
        $this->userModel = $this->model('User'); 
    }
    public function index()
    {
        redirect('pages/index');
    }

    public function activation()
    {
        
        if (isset($_GET['token']) && !empty($_GET['token'])  ) {
            $data = [
                'token' => $_GET['token'],
            ];
            if ($this->userModel->activation_account($data)) {
                flash('activation_success', 'Your account is now acctivated you can log in now');
                redirect('Authentification/login');
            } else {
                redirect('authentification/error');
            }
        }
    }

    public function error()
    {
        $this->view('authentification/error');
    }

    public function register()
    {
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['fullname']) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm_password'])) {
            // Process form

            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data = [
                'fullname' => trim($_POST['fullname']),
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'fullname_err' => '',
                'username_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
            ];

            // Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Pleae enter email';
            } else {
                //check email
                if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $data['email_err'] = "Invalid email format";
                  }
                elseif ($this->userModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = 'Email is already taken';
                }                
                elseif  (strlen($data['email']) < 16 || strlen($data['email'] > 100))
                    $data['email_err'] = 'Email lenght not valid ';
            }

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
                //Checks if name only contains letters 
                if(strlen($data['username']) < 4 || strlen($data['username']) > 20)
                    $data['username_err'] = "username must at least 4 and less than 20";
                elseif (!preg_match("/^[a-zA-Z0-9_~\-!@#\$%\^&\*\(\)]*$/", $data['username'])) {
                    $data['username_err'] = "Only letters and number and some special characters allowed";
                }
            }
            // Validate Password
            if (empty($data['password'])) {
                $data['password_err'] = 'Pleae enter password';
            } else {
                $password = $this->test_input($data['password']);
                if (strlen($password) < 8 || strlen($password) > 150) {
                    $data['password_err'] = "Your Password Must Contain At Least 8 Characters and not depassing 150 Characters!";
                }elseif (!preg_match("/^[a-zA-Z0-9_~\-!@#\$%\^&\*\(\)]*$/", $password)) {
                    $data['password_err'] = "Only letters and number and some special characters allowed";
                } elseif (!preg_match("#[0-9]+#", $password)) {
                    $data['password_err'] = "Your Password Must Contain At Least 1 Number!";
                } elseif (!preg_match("#[A-Z]+#", $password)) {
                    $data['password_err'] = "Your Password Must Contain At Least 1 Capital Letter!";
                } elseif (!preg_match("#[a-z]+#", $password)) {
                    $data['password_err'] = "Your Password Must Contain At Least 1 Lowercase Letter!";
                } 
            }
            // Validate Confirm Password
            if (empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Please confirm password';
            } else {
                $data['confirm_password'] = $this->test_input($data['confirm_password']);
                if (strlen($data['confirm_password']) < 8 || strlen($data['confirm_password']) > 150) 
                    $data['confirm_password_err'] = "Your Password Must Contain At Least 8 Characters!";
                elseif (!preg_match("/^[a-zA-Z0-9_~\-!@#\$%\^&\*\(\)]*$/", $data['confirm_password'])) {
                    $data['confirm_password_err'] = "Only letters and number and some special characters allowed";
                }
                elseif ($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Passwords do not match';
                }
            }

            // Make sure errors are empty
            if (empty($data['email_err']) && empty( $data['fullname_err']) && empty($data['password_err']) && empty($data['confirm_password_err']) && empty($data['username_err'])) {
                // Validated

                // Hash Password
                $data['password'] = hash('whirlpool', $data['password']);

                // Register user
                if ($this->userModel->register($data)) {
                    flash('register_success', 'You are registred , An email was sent to you to confirm your account plz check you email before log in');
                    redirect('Authentification/login');
                } else {
                    redirect('authentification/error');
                }

            } else {
                // Load view with errors
                $this->view('Authentification/register', $data);
            }

        } else {
            // Init data
            $data = [
                'fullname' => '',
                'username' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'fullname_err' => '',
                'username_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
            ];

            // Load view
            $this->view('Authentification/register', $data);
        }
    }

/*Each $_POST variable with be checked by the function*/
    public function test_input($data = '')
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET' ) {
            redirect('Authentification/error');
          }
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public function login()
    {
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email']) && isset($_POST['password'])) {
            // Process form
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            // check input data
            $email = $this->test_input($_POST['email']);
            $password = $this->test_input($_POST['password']);
            // Init data
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => '',
            ];

            // Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Pleae enter email';
            }
            elseif  (strlen($data['email']) < 16 || strlen($data['email'] > 100))
                $data['email_err'] = 'Email lenght not valid ';
            elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $data['email_err'] = "Invalid email format";
                  }

            // Validate Password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            }                
            elseif (strlen($data['password']) < 8 || strlen($data['password']) > 150) 
                $data['password_err'] = "Your Password  Contain At Least 8 Characters and not depassing 150 Characters!";
            elseif (!preg_match("/^[a-zA-Z0-9_~\-!@#\$%\^&\*\(\)]*$/", $data['password'])) {
                    $data['password_err'] = "Only letters and number and some special characters allowed";
            }
            // check for user/email
            if ($this->userModel->findUserbyEmail($data['email'])) {
                // user found
            } else {
                // user not found
                $data['email_err'] = 'No user found';
            }

            // Make sure errors are empty
            if (empty($data['email_err']) && empty($data['password_err'])) {
                // Validated
                // Hash Password
                $password = hash('whirlpool', $data['password']);
                // Check and set logged in user
                $loggedInUser = $this->userModel->login($data['email'], $password);

                if ($loggedInUser) {
                    // Create session
                    $this->createAuthentificationession($loggedInUser);

                } else {
                    //$data['password_err'] = 'Password incorrect';
                    flash('error_login', 'Plz check your password or try to active your account by checking your email');
                    $this->view('Authentification/login', $data);
                }
            } else {
                // Load view with errors
                $this->view('Authentification/login', $data);
            }

        } else {
            // Init data
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => '',
            ];

            // Load view
            $this->view('Authentification/login', $data);
        }
    
}

    public function createAuthentificationession($user = '')
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET' ) {
            redirect('Authentification/error');
          }
        $_SESSION['user_id'] = $user->id_user;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_fullname'] = $user->fullname;
        $_SESSION['user_username'] = $user->username;
        $_SESSION['send_notif'] = $user->send_notif;
        redirect('posts/mygalery');
    }





    public function forgotpassword()
    {
      
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])) {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            // Init data
            $data = [
                'email' => trim($_POST['email']),
                'email_err' => '',
            ];
            // Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Pleae enter email';
            } 
            else if  (strlen($data['email']) < 16 || strlen($data['email'] > 100))
                $data['email_err'] = 'Email lenght not valid ';
            elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $data['email_err'] = "Invalid email format";
                  }

            if (empty($data['email_err'])) {
                // Register user
                if ($this->userModel->forgotpassword($data['email'])) {
                    flash('password_mail_rest_success', 'An email was sent to you to reset your password plz check you email to change your password');
                    redirect('Authentification/forgotpassword');
                } else {
                    redirect('authentification/error');
                }
            } else {
                $this->view('Authentification/forgotpassword', $data);
            }
        } else {
            // Init data
            $data = [
                'email' => '',
                'email_err' => '',
            ];
            $this->view('Authentification/forgotpassword', $data);
        }

    }

    public function resetpassword()
    {
        if(isset($_GET['token']) && $_SERVER['REQUEST_METHOD'] == 'GET'){
            if(strlen($_GET['token']) == 0 || !$this->userModel->check_account_by_token($_GET['token']))
                redirect('Authentification/error');
        }
        // Check for POST
        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['token']) && !empty($_POST['token']) && (strlen($_POST['token']) > 100 || !$this->userModel->check_account_by_token($_POST['token'])))
                redirect('Authentification/login');
        else {
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['token']) && !empty($_POST['token']) && isset($_POST['new_password']) && isset($_POST['confirm_new_password'])) {
            // Process form

            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data['token'] = $_POST['token'];
            $data['new_password'] = trim($_POST['new_password']);
            $data['confirm_new_password'] = trim($_POST['confirm_new_password']);
            $data['new_password_err'] = '';
            $data['confirm_new_password_err'] = '';

            // Validate New Password
            if (empty($data['new_password'])) {
                $data['new_password_err'] = 'Pleae enter password';
            } else {
                $new_password = $this->test_input($data['new_password']);
                if (strlen($new_password) < 8 || strlen($new_password) > 150) {
                    $data['new_password_err'] = "Your Password Must Contain At Least 8 Characters and not depassing 150 Characters!";
                }elseif (!preg_match("/^[a-zA-Z0-9_~\-!@#\$%\^&\*\(\)]*$/",$new_password)) {
                    $data['new_password_err'] = "Only letters and number and some special characters allowed";
                }  elseif (!preg_match("#[0-9]+#", $new_password)) {
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
            if (empty($data['new_password_err']) && empty($data['confirm_new_password_err'])) {
                // Validated

                // Hash Password
                
                $data['new_password'] = hash('whirlpool', $data['new_password']);

                // Check if user old password is correct

                   
                    if ($this->userModel->updatepassword($data)) {
                        flash('password_reset_success', 'You Password is Reset Succesfully you can now log in with you new password');
                        redirect('authentification/resetpassword?token='.$data['token']);
                    }
                    else
                    {
                        flash('password_reset_error', 'Your Password  was not updated Succesfully', 'notification is-danger is-light');
                        redirect('authentification/resetpassword?token='.$data['token']);
                    }
            }
            else 
            { 
                // Load view with errors
                flash('password_reset_error', 'Your New password is not valid !!!!', 'notification is-danger is-light');
                redirect('authentification/resetpassword?token='.$data['token']);
            }

        } else {
            // Init data
            $data = [
                'token' => '',
                'old_password' => '',
                'new_password' =>'',
                'confirm_new_password' => '',
                'old_password_err' => '',
                'new_password_err' => '',
                'confirm_new_password_err' => '',
            ];
            // Load view
            $this->view('Authentification/resetpassword', $data);
        }
        }

    }

}
