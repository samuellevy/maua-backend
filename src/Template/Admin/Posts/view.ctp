<?php //die(debug($post));?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="header">
          <h4 class="title"><?=$post->title?></h4>
          <p class="category"><strong>Descrição: </strong><?=$post->description?></p>
          <p class="category"><strong>Categoria: </strong><?=$post->blog_category->category?></p>
          <p class="category"><strong>Importância: </strong><?=$post->important?></p>
          <p class="category"><strong>Author_id: </strong><?=$post->author_id?></p>
          <p class="category"><strong>Publish date: </strong><?=$post->publish_date?></p>
          <p class="category"><strong>Tags: </strong>
            <?php foreach($post->blog_post_tags as $post_tag):
              echo($post_tag->tag->name . " | ");
            endforeach;?>
          </p>
          <p class="category"><strong>Files: </strong>
            <?php foreach($post->files as $file):
              echo("<br/>" . $file->filename);
            endforeach;?>
          </p>
        </div>
        <div class="content">
          <div class="row">
            <div class="col-md-4">
              <strong>Content: </strong>
              <p><?=$post->content?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
