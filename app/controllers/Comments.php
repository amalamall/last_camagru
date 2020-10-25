<?php
class Comments extends Controller
{
  public function __construct()
  {
    if (!isLoggedIn()) {
      redirect('authentification/login');
    }
    $this->commentModel = $this->model('Comment');
  }

  public function index()
  {
    $data = [
      'title' => 'Camagru',
      'description' => 'Camagru Project is loding.......'
    ];

    $this->view('pages/index', $data);
  }

  public function addcomment()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
      $comment = trim(preg_replace('/\s+/', ' ', $_POST['comment']));
      if (isset($comment) && !empty($comment) && isset($_POST['id_post']) && !empty($_POST['id_post'])) {

        if (strlen($comment) >= 10 && strlen($comment) <= 150) {
          if ($this->commentModel->addcomment($comment, $_POST['id_post'], $_SESSION['user_id'])) {
            redirect('posts/viewpost?id_post=' . $_POST['id_post']);
          } else {
            flash('error-comment', 'Your Comment was not created plz retry again', 'notification is-warning is-light');
            redirect('posts/viewpost?id_post=' . $_POST['id_post']);
          }
        } else {
          flash('error-comment', 'Your Comment length is not valid', 'notification is-warning is-light');
          redirect('posts/viewpost?id_post=' . $_POST['id_post']);
        }
      } else {
        flash('error-comment', 'Your Comment was not created plz retry again and be sure to fill you comment', 'notification is-warning is-light');
        redirect('posts/viewpost?id_post=' . $_POST['id_post']);
      }
    } else {
      redirect('posts/error');
    }
  }

  public function deletecomment()
  {
    if (isset($_GET['id_comment']) && !empty($_GET['id_comment']) && isset($_GET['id_post']) && !empty($_GET['id_post'])) {
      if ($this->commentModel->deletecomment($_GET['id_comment'])) {
        redirect('posts/viewpost?id_post=' . $_GET['id_post']);
      }
    } else {
      redirect('posts/error');
    }
  }
}
