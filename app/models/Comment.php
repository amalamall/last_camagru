<?php

class Comment
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
	}

	public function addcomment($comment,$id_post,$id_user)
	{
		$this->db->query('insert into comments (comment,id_post,id_user) values(:comment,:id_post,:id_user)');
		$this->db->bind(':comment', $comment);
		$this->db->bind(':id_post', $id_post);
        $this->db->bind(':id_user',$_SESSION['user_id'] );
		if ($this->db->execute()) {
			$owner_info = $this->get_info_owner_post($id_post);
            $user_info = $this->getuserinfo($id_user);
            if($owner_info->send_notif)
                $this->notification_comment_mail($owner_info->email,$id_post,$user_info);
			return true;
		}
		else {
			return false;
		}
	}
	public function deletecomment($id_comment)
	{
		$this->db->query('delete from comments where id_comment=:id_comment and id_user=:id_user');
		$this->db->bind(':id_comment', $id_comment);
		$this->db->bind(':id_user',$_SESSION['user_id'] );
		if ($this->db->execute()) {
			return true;
		}
		else {
			return false;
		}
	}
	public function notification_comment_mail($email, $id_post,$user_info)
    {
        $to = $email;
        $subject =  $user_info->username." commented your post";
        $header = "From: no-reply@camagru.fr";
        $content = 'click here to view lasted comment and like to your post.
            http://165.227.175.72/camagru/posts/viewpost?id_post=' .$id_post. '
            ---------------
            This mail was send automatically, please do not reply.';
        mail($to, $subject, $content, $header);
    }

    public function get_info_owner_post($id_post)
    {
        $this->db->query('select u.email,u.send_notif,u.id_user from users u inner join posts p on p.id_user=u.id_user where p.id_post=:id_post');
        $this->db->bind(':id_post',$id_post);
        $row = $this->db->single();
        // Check row
        if ($this->db->rowCount() > 0) {
            return $row;
        } else {
            return false;
        }
    }
	public function getuserinfo($user_id)
    {
        $this->db->query('select u.fullname,u.username from users u where id_user=:id_user');
        $this->db->bind(':id_user', $user_id);
        $row = $this->db->single();
        // Check row
        if ($this->db->rowCount() > 0) {
            return $row;
        } else {
            return false;
        }
    }
}