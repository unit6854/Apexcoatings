<?php get_header(); ?>

<section class="hero" style="min-height:80vh;">
    <div class="hero-bg"><div class="hero-grid"></div></div>
    <div class="container" style="position:relative;z-index:2;text-align:center;padding:160px 0 80px;">
        <div style="font-family:'Barlow Condensed',sans-serif;font-size:8rem;font-weight:900;line-height:1;background:linear-gradient(135deg,#FFAD00,#F5831F);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;margin-bottom:16px;" aria-hidden="true">404</div>
        <h1 style="font-family:'Barlow Condensed',sans-serif;font-size:2.5rem;font-weight:900;text-transform:uppercase;color:var(--apex-white);margin-bottom:16px;">Page Not Found</h1>
        <p style="color:rgba(255,255,255,0.55);margin-bottom:40px;max-width:420px;margin-left:auto;margin-right:auto;line-height:1.7;">Looks like this page got lost in the finish booth. Let's get you back on track.</p>
        <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;">
            <a href="<?php echo esc_url( home_url('/') ); ?>" class="btn btn-primary">Go Home</a>
            <a href="<?php echo esc_url( home_url('/products') ); ?>" class="btn btn-outline">Browse Products</a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
