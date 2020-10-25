<?php

class User
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    // Register user

    public function register($data)
    {
        $token = md5(microtime(true) * 1000000);
        $this->db->query('insert into users (fullname,username,email,password,token) values(:fullname,:username,:email,:password,:token)');
        //bind values
        $this->db->bind(':fullname', $data['fullname']);
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':token', $token);
        // Execute

        if ($this->db->execute()) {
            // send mail confirmation
            $this->confirm_account_mail($data['email'], $data['username'], $token);
            return true;
        } else {
            return false;
        }

    }

    public function confirm_account_mail($email, $username, $token)
    {
        $to = $email;
        $subject = "Account verification";
        $header = "From: no-reply@camagru.fr";
        $content = 'Welcome to Camagru.
            To validate your account, please click on the link below or copy it.
            http://165.227.175.72/camagru/authentification/activation?token=' . urlencode($token) . '
            ---------------
            This mail was send automatically, please do not reply.';
        mail($to, $subject, $content, $header);
    }

    // forgot password mail

    public function reset_password_mail($email, $token)
    {
        $to = $email;
        $subject = "Password Reset";
        $header = "From: no-reply@camagru.fr";
        $content = 'Reset your password.
            if you reply for a new password, please click on the link below or copy it.
            http://165.227.175.72/camagru/authentification/resetpassword?token=' . urlencode($token) . '
            ---------------
            This mail was send automatically, please do not reply.';
        mail($to, $subject, $content, $header);
    }

     // if email already exists

    public function findUserByEmail($email)
    {
        $this->db->query('select * from users where email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        // Check row
        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

        // if username already exists

        public function findUserByUsername($username)
        {
            $this->db->query('select * from users where username = :username');
            $this->db->bind(':username', $username);
    
            $row = $this->db->single();
    
            // Check row
            if ($this->db->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        }
    // activation
    public function check_account_by_token($token)
    {
        $this->db->query('select * from users where token LIKE :token LIMIT 1');
        $this->db->bind(':token', $token, PDO::PARAM_STR);
        $row = $this->db->single();
        // Check row
        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    //
    public function check_account_by_token_pwd($token_pwd)
    {
        $this->db->query('select * from users where token_pwd LIKE :token_pwd LIMIT 1');
        $this->db->bind(':token_pwd', $token_pwd, PDO::PARAM_STR);
        $row = $this->db->single();
        // Check row
        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }


    // activation account

    public function activation_account($data)
    {
        
        if ($this->check_account_by_token($data['token'])) {
            $this->db->query("update users set active=1 WHERE token LIKE :token LIMIT 1");
            $this->db->bind(':token', $data['token'], PDO::PARAM_STR);
            if ($this->db->execute()) {    
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    // vider token apres activation
    public function deleteToken($data)
    {  
            $this->db->query("update users set token='' WHERE token LIKE :token LIMIT 1");
            $this->db->bind(':token', $data['token'], PDO::PARAM_STR);
            if ($this->db->execute()) {     
                return true;
            } else {
                return false;
            }
    }
    // login user

    public function login($email, $password)
    {
        $this->db->query('select * from users where email = :email ');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        $hashed_password = $row->password;
        if ($password == $hashed_password && $row->active == 1) {
            return $row;
        } else {
            return false;
        }
    }

    // reset password

    public function forgotpassword($email)
    {
        $this->db->query('select * from users where email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        // Check row
        if ($this->db->rowCount() > 0) {
            $token_pwd = md5(microtime(true) * 1000000);
            if($this->UpdateTokenPwd($email,$token_pwd))
                $this->reset_password_mail($email, $token_pwd);
            return true;
        } else {
            return false;
        }
    }

    // insert new token pwd in database

    public function UpdateTokenPwd($email,$token_pwd)
    {
        
        $this->db->query("update users set token_pwd=:token_pwd where email = :email");
        $this->db->bind(':token_pwd',$token_pwd, PDO::PARAM_STR);
        $this->db->bind(':email', $email);
        
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }



    public function updatepassword($data)
    {
        $this->db->query('update users set password =:new_password where token_pwd LIKE :token_pwd LIMIT 1');
        $this->db->bind(':new_password', $data['new_password']);
        $this->db->bind(':token_pwd', $data['token'], PDO::PARAM_STR);
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//                                     PART USERS
    // edit profile

    public function editprofile($data)
    {
        
        $this->db->query('update users set fullname =:fullname , username=:username , email=:email ,send_notif=:send_notif where id_user=:id_user');
        $this->db->bind(':fullname', $data['fullname']);
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':send_notif', $data['send_notif']);
        $this->db->bind(':id_user', $_SESSION['user_id']);
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }    
    // Find user by email

    public function findUserByEmail2($email)
    {
        $this->db->query('select * from users where email = :email and id_user !=:id_user');
        $this->db->bind(':email', $email);
        $this->db->bind(':id_user', $_SESSION['user_id']);
        $row = $this->db->single();

        // Check row
        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

     //check password with id_user if correct to reset it
     public function checkpassword_id_user($old_password)
     {
            $this->db->query('select * from users where id_user =:id_user and password=:old_password');
            $this->db->bind(':id_user', $_SESSION['user_id']);
            $this->db->bind(':old_password', $old_password);
            $row = $this->db->single();
            // Check row
            if ($this->db->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
      }

      public function updatepassword_id_user($data)
      {
          $this->db->query('update users set password =:new_password where id_user=:id_user');
          $this->db->bind(':new_password', $data['new_password']);
          $this->db->bind(':id_user', $_SESSION['user_id']);
          if ($this->db->execute()) {
              return true;
          } else {
              return false;
          }
      }
}



