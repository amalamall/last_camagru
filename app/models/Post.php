<?php

class Post
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }



    public function create_post($image, $title, $description)
    {
        $this->db->query('insert into posts (picture_path,title,description,id_user) values(:image,:title,:description,:id_user)');
        $this->db->bind(':image', $image);
        $this->db->bind(':title', $title);
        $this->db->bind(':description', $description);
        $this->db->bind(':id_user', $_SESSION['user_id']);
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getPostByUserId($id_user)
    {
        $this->db->query('select * from posts where id_user = :id_user order by creation_date desc');
        $this->db->bind(':id_user', $_SESSION['user_id']);
        $posts = $this->db->resultSet();
        return $posts;
    }

    public function delete_post($id_post)
    {
        $this->db->query('delete from posts where id_post = :id_post');
        $this->db->bind(':id_post', $id_post);
        if ($this->db->execute()) {
            if ($this->deleteallcomment($id_post) && $this->deletealllike($id_post)) {
                return true;
            } else
                return false;
        } else {
            return false;
        }
    }

    public function getallposts($limit, $offset)
    {
        //$this->db->query('select p.*,l.*,u.username,u.fullname from posts p inner join likes l on l.id_post=p.id_post inner join users u on u.id_user=l.id_user  order by p.creation_date desc LIMIT :limit OFFSET :offset ');
        $this->db->query('select p.* from posts p   order by p.creation_date desc LIMIT :limit OFFSET :offset ');
        $this->db->bind(':limit', (int) $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', (int) $offset, PDO::PARAM_INT);
        $posts = $this->db->resultSet();
        return $posts;
    }

    public function getuserinfo($user_id)
    {

        $this->db->query('select fullname,username from users  where id_user=:id_user');
        $this->db->bind(':id_user', $user_id);
        $row = $this->db->single();
        // Check row
        if ($this->db->rowCount() > 0) {
            return $row;
        } else {
            return false;
        }
    }

    public function issetlike($id_post, $id_user)
    {
        $this->db->query('select * from likes where id_user=:id_user and id_post=:id_post');
        $this->db->bind(':id_user', $id_user);
        $this->db->bind(':id_post', $id_post);
        $row = $this->db->single();
        // Check row
        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function countlike($id_post)
    {
        $this->db->query('select count(*) as nb from likes where id_post=:id_post');
        $this->db->bind(':id_post', $id_post);
        $row = $this->db->single();
        // Check row
        if ($this->db->rowCount()) {
            return $row;
        } else {
            return false;
        }
    }

    public function countcomment($id_post)
    {
        $this->db->query('select count(*) as nb from comments where id_post=:id_post');
        $this->db->bind(':id_post', $id_post);
        $row = $this->db->single();
        // Check row
        if ($this->db->rowCount()) {
            return $row;
        } else {
            return false;
        }
    }

    public function addlike($id_post, $id_user)
    {
        if (!$this->issetlike($id_post, $id_user)) {
            $this->db->query('insert into likes (id_user,id_post) values(:id_user,:id_post)');
            $this->db->bind(':id_user', $id_user);
            $this->db->bind(':id_post', $id_post);
            if ($this->db->execute()) {
                $owner_info = $this->get_info_owner_post($id_post);
                $user_info = $this->getuserinfo($id_user);
                if ($owner_info && $owner_info->send_notif)
                    $this->notification_like_mail($owner_info->email, $id_post, $user_info);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function deletelike($id_post, $id_user)
    {
        $this->db->query('delete from likes where id_user=:id_user and id_post=:id_post');
        $this->db->bind(':id_user', $id_user);
        $this->db->bind(':id_post', $id_post);
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    //delete all like by id_post

    public function deletealllike($id_post)
    {
        $this->db->query('delete from likes where  id_post=:id_post');
        $this->db->bind(':id_post', $id_post);
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    //delete all comments by id_post

    public function deleteallcomment($id_post)
    {
        $this->db->query('delete from comments where id_post=:id_post');
        $this->db->bind(':id_post', $id_post);
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getpost($id_post)
    {
        $this->db->query('select p.* from posts p  where id_post=:id_post');
        $this->db->bind(':id_post', $id_post);
        $post = $this->db->single();
        // Check row
        if ($this->db->rowCount()) {
            return $post;
        } else {
            return false;
        }
    }

    public function getcomments($id_post)
    {
        $this->db->query('select c.*,u.fullname,u.username from comments c inner join users u on u.id_user=c.id_user where id_post=:id_post');
        $this->db->bind(':id_post', $id_post);
        $comments = $this->db->resultSet();
        return $comments;
    }

    //send mail notification like
    public function notification_like_mail($email, $id_post, $user_info)
    {
        $to = $email;
        $subject =  $user_info->username . " liked your post";
        $header = "From: no-reply@camagru.fr";
        $content = 'click here to view lasted comment and like to your post.
            http://165.227.175.72/camagru/posts/viewpost?id_post=' . $id_post . '
            ---------------
            This mail was send automatically, please do not reply.';
        mail($to, $subject, $content, $header);
    }

    public function get_info_owner_post($id_post)
    {
        $this->db->query('select u.email,u.send_notif from users u inner join posts p on p.id_user=u.id_user where p.id_post=:id_post');
        $this->db->bind(':id_post', $id_post);
        $row = $this->db->single();
        // Check row
        if ($this->db->rowCount() > 0) {
            return $row;
        } else {
            return false;
        }
    }
}
