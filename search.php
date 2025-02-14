<?php 
require 'partials/header.php';


if(isset($_GET['search']) && isset($_GET['submit'])){

$search = filter_var($_GET['search'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$query = "SELECT * FROM posts WHERE title  LIKE '%$search%' ORDER BY date_time DESC" ;
$posts = mysqli_query($connection, $query);
}else {
  header('location: ' .ROOT_URL.'blog.php');
  die();
}

?>


<!-- start posts  -->

<?php if(mysqli_num_rows($posts)>0) : ?>
  <section class="posts section_extra-margin">
    <div class="container posts_container">
      <?php while($post = mysqli_fetch_assoc($posts)):?>

      <article class="post">
        <div class="post_thumbnail">
          <img src="./Media/<?= $post['thumbnail']?>" height="167px" width="251px" alt="">
        </div>
        <div class="post_info">
        <?php 
      // fetch category from categories table using category_id of post
      $category_id = $post['category_id'];
      $category_query = "SELECT * FROM  categories WHERE  id = $category_id";
      $catgeory_result = mysqli_query($connection, $category_query);
      $category = mysqli_fetch_assoc($catgeory_result);
      ?>

          <a href="<?= ROOT_URL?>category_posts.php?id=<?=$post['category_id']?>" class="category_button"><?= $category['title'] ?></a>
          <h3 class="post_title">
            <a href="<?= ROOT_URL ?>post.php?id=<?= $post['id']?>"><?= $post['title']?></a></h3>
          <p class="post_body">
          <?= substr($post['body'], 0, 150) ?>...
          </p>
          <div class="post_seller">
          <?php
          
          // fetch seller from users table using the seller_id
          $seller_id = $post['seller_id'];
          $seller_query = "SELECT * FROM users WHERE id=$seller_id";
          $seller_result = mysqli_query($connection, $seller_query);
          $seller = mysqli_fetch_assoc($seller_result); 
          ?>
            <div class="post_seller-avatar">
              <img src="./Media/<?= $seller['avatar']?>" alt="">
            </div>
            <div class="post_seller-info">
            <h5>By: <?= "{$seller['first_name']} {$seller['last_name']} "?> </h5>
              <small><?= date("M d, Y - H:i" , strtotime($post['date_time']))?></small>
            </div>
          </div>
        </div>

      </article>
      <?php endwhile ?>
      
    </div>
  </section>

  <?php else :?>

    <div class="alert_message error lg section_extra-margin">
      <p>No post found for this search </p>
    </div>
    <?php endif?>



   <!-- start of category buttons -->
   <section class="category_buttons">
    <div class="container category_buttons-container">
      <?php 
      $all_categories_query = "SELECT  * FROM categories";
      $all_categories = mysqli_query($connection, $all_categories_query )
      ?>
      <?php while($category = mysqli_fetch_assoc($all_categories)):?>
      <a href="<?= ROOT_URL?>category_posts.php?id=<?= $category['id'] ?>" class="category_button"><?= $category['title']?></a>
      <?php endwhile ?>
    
    </div>
  </section>
  <!-- end of category buttons -->



  <!-- end of posts -->
  <?php
  
  include 'partials/footer.php'
  ?>