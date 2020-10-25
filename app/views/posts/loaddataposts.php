
      <div class="columns  is-multiline">
          <?php foreach ($data as $info): ?>
              <div class="column is-one-quarter">
                            <div class='card'>
                                  <div class='card-image'>
                                    <figure class='image is-square'>
                                      <img src='<?php echo URLROOT . "/public/img/posts/" . $info->post->picture_path; ?>' alt='<?php echo $info->post->title; ?>'>
                                    </figure>
                                  </div>
                                  <div class='card-content'>
                                            <div class='media'>
                                                <div class='media-content'>
                                                      <a class='level-item' aria-label='like'>
                                                      <?php echo $info->countlike->nb . "   like"; ?>
                                                      </a>
                                                      <a class='level-item' >
                                                      <?php echo $info->countcomment->nb . "   comment"; ?>
                                                      </a>
                                                      <?php if ($info->user): ?>
                                                        <p class='title is-4'><?php echo $info->user->username; ?></p>
                                                        <p class='subtitle is-6'>@<?php echo $info->user->fullname; ?></p>
                                                        <?php endif;?>
                                                        <br>
                                                </div>
                                            </div>

                                            <div class='content'>
                                              <?php if (isset($_SESSION['user_id'])): ?> 
                                                  <footer class="card-footer">
                                                  <?php if ($info->like): ?>
                                                  <a class="card-footer-item" href='<?php echo URLROOT; ?>/posts/deletelike?id_post=<?php echo $info->post->id_post; ?>'><button id='unlike' name='unlike' value='Like' class='button is-info is-medium'>Unlike</button></a>
                                                <?php else: ?>
                                                  <a class="card-footer-item" href='<?php echo URLROOT; ?>/posts/addlike?id_post=<?php echo $info->post->id_post; ?>'><button id='like' name='like' value='Like' class='button is-info is-medium'>like</button></a>
                                                  <?php endif;?>
                                              <?php endif;?>
                                                  <a class="card-footer-item" href='<?php echo URLROOT; ?>/posts/viewpost?id_post=<?php echo $info->post->id_post; ?>'><button class='button  is-success is-medium'>View Post</button></a>
                                                  <time ><?php $info->post->creation_date;?></time>
                                                  </footer>
                                            </div>
                                    </div>
                            </div>
                </div>
          <?php endforeach;?>





