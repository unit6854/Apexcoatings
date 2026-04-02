<?php get_header(); ?>

<div class="page-wrap">
    <div class="container">
        <?php if (have_posts()): while (have_posts()): the_post(); ?>
        <article style="margin-bottom:48px;padding-bottom:48px;border-bottom:1px solid var(--apex-gray-light);">
            <h2 style="font-size:1.8rem;margin-bottom:8px;">
                <a href="<?php the_permalink(); ?>" style="color:var(--apex-black);"><?php the_title(); ?></a>
            </h2>
            <p style="color:var(--apex-gray-mid);font-size:0.85rem;margin-bottom:16px;"><?php the_date(); ?></p>
            <p><?php the_excerpt(); ?></p>
            <a href="<?php the_permalink(); ?>" class="btn btn-outline-dark btn-sm" style="margin-top:12px;">Read More</a>
        </article>
        <?php endwhile;
        the_posts_pagination();
        else: ?>
        <p style="color:var(--apex-gray-dark);">No content found. <a href="<?php echo home_url('/'); ?>">Go home</a>.</p>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
