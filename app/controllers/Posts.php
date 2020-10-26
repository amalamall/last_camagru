<?php
class Posts extends Controller
{

  public function __construct()
  {

    $this->postModel = $this->model('Post');

  }

  public function index()
  {
    $data = [
      'title' => 'Welcome',
      'description' => 'Welcome In Camagru Enjoy !!'
    ];

    $this->view('posts/index', $data);
  }

  public function studio()
  {
    if (!isLoggedIn()) {
      redirect('authentification/login');
    } else {
      if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
        $posts = $this->postModel->getPostByUserId($_SESSION['user_id']);
        $data = [
          'posts' => $posts
        ];
        $this->view('posts/studio', $data);
      } else
        redirect('posts/error');
    }
  }


  //return memory size in B, KB, MB
  public function getBase64ImageSize($base64Image = '')
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      redirect('posts/error');
    }
    $size_in_bytes = (int) (strlen(rtrim($base64Image, '=')) * 3 / 4);
    $size_in_kb    = $size_in_bytes / 1024;
    $size_in_mb    = $size_in_kb / 1024;
    if ($size_in_kb <= 1.2)
      return 0;
    else if ($size_in_mb >= 10)
      return 0;
    else
      return 1;
  }

  public function merge_img($img='', $filter='', $type='')
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      redirect('posts/error');
    }
    $path = '/var/www/html/camagru';
    if(file_exists($path))
      echo "erguer7";
      die($path);
    //echo mime_content_type($filter);
    // if ($type == 'image/png') {
    //   $decodedData = base64_decode($img);
    //   file_put_contents($path . '/public/img/posts/image_canvas.png', $decodedData);
    //   $decodedatafilter = base64_decode($filter);
    //   file_put_contents($path . '/public/img/posts/filter.png', $decodedatafilter);
    //   $sourceImage = $path . "/public/img/posts/filter.png";
    //   $destImage = $path . "/public/img/posts/image_canvas.png";
    //   //echo $sourceImage;
    //   $src = imagecreatefrompng($sourceImage);
    //   $dest = imagecreatefrompng($destImage);


    //   list($width, $height) = getimagesize($sourceImage);

    //   $src_xPosition = 0;
    //   $src_yPosition = 0;


    //   $src_cropXposition = 0;
    //   $src_cropYposition = 0;
    //   // id_image
    //   $id_image = uniqid();
    //   imagecopy($dest, $src, $src_xPosition, $src_yPosition, $src_cropXposition, $src_cropYposition, $width, $height);

    //   // Assign unique id to img
    //   $uniqid = $path . "/public/img/posts/" . $id_image . ".png";
    //   imagepng($dest, $uniqid, 9);
    //   $image_id = $id_image . ".png";
    //   //Destroy temp img
    //   imagedestroy($dest);

    //   return $image_id;
    // } else if ($type == 'image/jpeg') {
    //   $decodedData = base64_decode($img);
    //   $uniqid = uniqid();

    //   file_put_contents($path . '/public/img/posts/image_canvas.jpg', $decodedData);
    //   if (file_exists($path . '/public/img/posts/image_canvas.jpg')) {
    //     $new_img = imagecreatefromjpeg($path . '/public/img/posts/image_canvas.jpg');
    //     imagepng($new_img, $path . '/public/img/posts/image_canvas.png');
    //   }
    //   $decodedatafilter = base64_decode($filter);
    //   file_put_contents($path . '/public/img/posts/filter.png', $decodedatafilter);
    //   $sourceImage = $path . "/public/img/posts/filter.png";
    //   $destImage = $path . "/public/img/posts/image_canvas.png";
    //   $src = imagecreatefrompng($sourceImage);
    //   $dest = imagecreatefrompng($destImage);


    //   list($width, $height) = getimagesize($sourceImage);
    //   // Start x & y
    //   $src_xPosition = 0;
    //   $src_yPosition = 0;

    //   // Whwe to crop
    //   $src_cropXposition = 0;
    //   $src_cropYposition = 0;
    //   // id_image
    //   $id_image = uniqid();
    //   imagecopy($dest, $src, $src_xPosition, $src_yPosition, $src_cropXposition, $src_cropYposition, $width, $height);

    //   // Assig unique id for img
    //   $uniqid = $path . "/public/img/posts/" . $id_image . ".jpg";
    //   imagejpeg($dest, $uniqid, 100);
    //   $image_id = $id_image . ".jpg";
    //   //Destroy temp img
    //   imagedestroy($dest);

    //   return $image_id;
    // }
  }

  public function saveimage()
  {
    die("here");
    if ($_SERVER['REQUEST_METHOD'] == 'GET' ) {
      redirect('posts/error');
    }
    if (!isLoggedIn()) {
      redirect('authentification/login');
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
      if (isset($_POST['picture']) && isset($_POST['description']) && isset($_POST['filter']) && isset($_POST['title'])) {
        $title = trim(preg_replace('/\s+/', ' ', $_POST['title']));
        $description = trim(preg_replace('/\s+/', ' ', $_POST['description']));
        //$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
        if (!empty($_POST['picture']) && !empty($_POST['filter']) && !empty($description) && !empty($title)) {
          if ($this->getBase64ImageSize($_POST['picture']) && $this->getBase64ImageSize($_POST['filter'])) {

            if ((strlen($_POST['title']) != 0 &&  strlen($_POST['title']) < 100) || (strlen($_POST['description']) !=  0 &&  strlen($_POST['description']) < 200)) {

              $type = mime_content_type($_POST['picture']);

              if ($type == "image/png" || $type == "image/jpeg") {

                $img = preg_replace('/^data:image\/(png|jpeg);base64,/', "", $_POST['picture']);
                $filter = preg_replace('/^data:image\/(png|jpeg);base64,/', "", $_POST['filter']);
                $new_image = $this->merge_img($img, $filter, $type);
                if ($this->postModel->create_post($new_image, $title, $description))
                  redirect('posts/studio');
                else
                  flash('error-post', 'Your Post was not created plz retry again', 'notification is-danger is-light');
              } else
                flash('error-post', 'Your Picture type is not valid plz retry again', 'notification is-danger is-light');
            } else
              flash('error-post', 'title/description lenght not valid !!', 'notification is-danger is-light');
          } else
            flash('error-post', 'the size of your picture is not valid', 'notification is-danger is-light');
        } else
          flash('error-post', 'plz be sure to fill all the form proposed', 'notification is-danger is-light');
      } else  if (isset($_POST['picture']) && isset($_POST['description']) && isset($_POST['filterupload']) && isset($_POST['titleupload'])) {
        $title = trim(preg_replace('/\s+/', ' ', $_POST['titleupload']));
        $description = trim(preg_replace('/\s+/', ' ', $_POST['description']));

        if (!empty($_POST['picture']) && !empty($_POST['filterupload']) && !empty($title) && !empty($description)) {

          if ($this->getBase64ImageSize($_POST['picture']) && $this->getBase64ImageSize($_POST['filterupload'])) {
            if ((strlen($_POST['title']) != 0 &&  strlen($_POST['title']) < 100) || (strlen($_POST['description']) !=  0 &&  strlen($_POST['description']) < 200)) {
              $type = mime_content_type($_POST['picture']);
              if ($type == "image/png" || $type == "image/jpeg") {
                $img = preg_replace('/^data:image\/(png|jpeg);base64,/', "", $_POST['picture']);
                $filter = preg_replace('/^data:image\/(png|jpeg);base64,/', "", $_POST['filterupload']);
                //$new_image = $this->merge_img($img, $filter, $type);
                $this->merge_img($img, $filter, $type);
                // if ($this->postModel->create_post($new_image, $title, $description))
                //   redirect('posts/upload');
                // else
                //   flash('error-post', 'Your Post was not created plz retry again', 'notification is-danger is-light');
              } else
                flash('error-post', 'Your Picture type is not valid plz retry again', 'notification is-danger is-light');
            } else
              flash('error-post', 'title/description lenght not valid !!', 'notification is-danger is-light');
          } else
            flash('error-post', 'the size of your picture is not valid', 'notification is-danger is-light');
        } else
          flash('error-post', 'plz be sure to fill all the form proposed', 'notification is-danger is-light');
      } else {
        flash('error-post', 'OOPS RETRY AGAIN', 'notification is-danger is-light');
      }
    }
  }

  public function upload()
  {
    if (!isLoggedIn()) {
      redirect('authentification/login');
    }
    if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
      $posts = $this->postModel->getPostByUserId($_SESSION['user_id']);
      $data = [
        'posts' => $posts
      ];

      $this->view('posts/upload', $data);
    }
  }

  public function deleteimage()
  {
    if (!isLoggedIn()) {
      redirect('authentification/login');
    }
    if (isset($_GET['path']) && isset($_GET['id_post']) && !empty($_GET['id_post']) && !empty($_GET['path'])) {
      $path = $_GET['path'];
      $id_post = $_GET['id_post'];
      if (!file_exists($path) || !unlink($path)) {
        redirect('posts/error');
      } else {
        if ($this->postModel->delete_post($id_post))
          redirect('posts/studio');
        else
          redirect('posts/error');
      }
    } else if (isset($_GET['pathupload']) && isset($_GET['id_post']) && !empty($_GET['id_post']) && !empty($_GET['pathupload'])) {
      $path = $_GET['pathupload'];
      $id_post = $_GET['id_post'];
      if (!file_exists($path) || !unlink($path)) {
        redirect('posts/error');
      } else {
        if ($this->postModel->delete_post($id_post))
          redirect('posts/upload');
        else
          redirect('posts/error');
      }
    } else {
      redirect('posts/error');
    }
  }


  public function getposts()
  {

    if (isset($_GET['offset']) && isset($_GET['limit'])) {
      $posts = $this->postModel->getallposts($_GET['limit'], $_GET['offset']);
      $data = [];
      foreach ($posts as $post) {
        $info = new ArrayObject();
        if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
          $like = $this->postModel->issetlike($post->id_post, $_SESSION['user_id']);
          $info->like = $like;
        }

        $countlike = $this->postModel->countlike($post->id_post);
        $countcomment = $this->postModel->countcomment($post->id_post);
        $user = $this->postModel->getuserinfo($post->id_user);

        $info->post = $post;

        $info->countlike = $countlike;
        $info->countcomment = $countcomment;

        $info->user = $user;
        array_push($data, $info);
      }

      if ($data)
        $this->view('posts/loaddataposts', $data);
    } else
      redirect('posts/error');
  }

  public function mygalery()
  {
    $this->view('posts/mygalery');
  }



  public function addlike()
  {
    if (!isLoggedIn()) {
      redirect('authentification/login');
    }
    if (isset($_GET['id_post']) && !empty($_GET['id_post']) && isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
      if ($this->postModel->addlike($_GET['id_post'], $_SESSION['user_id'])) {
        if (isset($_GET['view']))
          redirect('posts/viewpost?id_post=' . $_GET['id_post']);
        else
          redirect('posts/mygalery');
      } else
        redirect('posts/error');
    } else
      redirect('posts/error');
  }

  public function deletelike()
  {
    if (!isLoggedIn()) {
      redirect('authentification/login');
    }
    if (isset($_GET['id_post']) && !empty($_GET['id_post']) && isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
      if ($this->postModel->deletelike($_GET['id_post'], $_SESSION['user_id'])) {
        if (isset($_GET['view']))
          redirect('posts/viewpost?id_post=' . $_GET['id_post']);
        else
          redirect('posts/mygalery');
      } else
        redirect('posts/error');
    }
    else
    redirect('posts/error');
  }

  public function viewpost()
  {
    if (isset($_GET['id_post']) && !empty($_GET['id_post'])) {
      //$data = []; 
      $post = $this->postModel->getpost($_GET['id_post']);
      if ($post) {
        $data = new ArrayObject();
        if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
          $like = $this->postModel->issetlike($post->id_post, $_SESSION['user_id']);
          $data->like = $like;
        }
        $countlike = $this->postModel->countlike($post->id_post);
        $countcomment = $this->postModel->countcomment($post->id_post);
        $comments = $this->postModel->getcomments($post->id_post);
        $user = $this->postModel->getuserinfo($post->id_user);

        $data->post = $post;

        $data->countlike = $countlike;
        $data->countcomment = $countcomment;
        $data->user = $user;
        $data->comments = $comments;
        //array_push($data,$info);
        $this->view('posts/viewpost', $data);
      } else
        redirect('posts/error');
    }
    else
    redirect('posts/error');
  }

  public function error()
  {
    $this->view('posts/error');
  }

}
