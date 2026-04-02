<?php get_header(); ?>

<div style="background:var(--apex-black);padding:140px 0 60px;">
    <div class="container">
        <h1 class="section-title" style="color:var(--apex-white);"><?php the_title(); ?></h1>
    </div>
</div>

<div class="page-wrap">
    <div class="container">
        <?php while (have_posts()): the_post(); ?>
        <div class="entry-content" style="max-width:800px;line-height:1.85;color:var(--apex-gray-dark);">
            <?php the_content(); ?>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<?php get_footer(); ?>
