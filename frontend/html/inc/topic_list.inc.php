<?php


  $topics = $this->getAllTopics();
  $curr_category = 0;

  if ($topics) {
    foreach ($topics as $row) {

        $timestamp = passed($this->getLastTopicPostTime($row['id']));
        $author = $this->getUserNameByID($this->getLastPostAuthorID($row['id']));

        #
        # Печатаем названия категорий в списке тем
        if ($curr_category != $row['category']) {

            $category_name = $this->getCategoryName($row['category']);
            $curr_category = $row['category'];
            echo "<h3 class='ps-2 pt-4 ps-sm-4 pt-sm-3 fw-bold text-muted text-uppercase'>$category_name</h3>";

        }
        ?>

        <div class="list-group">
          <a href="read_topic.php?topic_id=<?=$row['id']?><?php if ($this->isNewMessages($row['id'], $_SESSION['USER']['last_login']) && empty($_SESSION['TOPIC_READ'][$row['id']])) print '&read=true'; ?>" class="list-group-item list-group-item-action d-flex gap-2" aria-current="true">
            <img src="<?php !empty($row['cover']) ? print 'uploads/covers/thumbs/' . $row['cover'] : print 'https://via.placeholder.com/150' ?>" alt="<?=$row['name'] . ' обложка'; ?>" class="flex-shrink-0 rounded" width="94" height="94">
            <div class="d-flex flex-column w-100">
                <h6 class="d-flex mb-md-3">
                    <span class="fw-bold"><?=$row['name']?></span>
                    <span class="d-none d-md-block badge text-secondary text-bg-light ms-2"><?=$this->getCountTopicPosts($row['id'])?></span>

                </h6>
                <p class="d-block d-sm-none small p-0 m-0"><?=mb_strimwidth(strip_tags($this->getLastTopicPost($row['id']), '<br>'), 0, 24, '…')?></p>
                <p class="d-none d-sm-block d-md-none small p-0 m-0"><?=mb_strimwidth(strip_tags($this->getLastTopicPost($row['id']), '<br>'), 0, 96, '…')?></p>
                <p class="d-none d-md-block p-0 m-0"><?=mb_strimwidth(strip_tags($this->getLastTopicPost($row['id']), '<br>'), 0, 128, '…')?></p>

                <span class="small d-md-none text-muted"><?=$timestamp . ', ' . $author?>
                </span>
            </div>
            <div class="position-absolute text-end pe-2 end-0">

                <span class="d-none d-md-inline text-muted" style="font-size: 0.7em"><?=$timestamp . ', ' . $author?></span>

            </div>
            <?php
              #
              # Считаем количество новых сообщений,
              # с момента посдней авторизации пользователя,
              # и выводим счётчик
              $marker = $this->isNewMessages($row['id'], $_SESSION['USER']['last_login']);

              if ($marker && empty($_SESSION['TOPIC_READ'][$row['id']]))
              {

            ?>

                  <span class="d-block d-md-none position-absolute top-0 p-2 bg-danger border border-light rounded-circle" style="left: 98%">
                      <span class="visually-hidden">Есть новые сообщения</span>
                  </span>
                  <span class="d-none d-md-block d-xl-none position-absolute top-0 p-2 bg-danger border border-light rounded-circle" style="left: 98%">
                      <span class="visually-hidden">Есть новые сообщения</span>
                  </span>
                  <span class="d-none d-xl-block position-absolute top-0 p-2 bg-danger border border-light rounded-circle" style="left: 99%">
                      <span class="visually-hidden">Есть новые сообщения</span>
                  </span>

            <?php } ?>
        </a>
      </div>
    <?php }
  } else echo '<h1>Нет созданных тем!</h1>'; ?>