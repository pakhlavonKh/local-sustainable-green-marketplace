<?php require_once 'lang_config.php'; ?>

<!-- Smooth Parallax Transition Section -->
<div class="parallax-container">
    <div class="parallax-content">
        <!-- TRANSLATED TEXT -->
        <h2><?php echo $text['banner_title']; ?></h2>
        <p><?php echo $text['banner_sub']; ?></p>
    </div>
</div>

<style>
/* Keep your existing CSS for parallax here */
.parallax-container {
    background-image: url('https://images.unsplash.com/photo-1542838132-92c53300491e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
    width: 100%;
    min-height: 400px;
    margin: 60px 0;
    background-attachment: fixed;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}
.parallax-container::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0, 0, 0, 0.4);
}
.parallax-content {
    position: relative; text-align: center; color: white; padding: 20px; max-width: 800px;
}
.parallax-content h2 {
    font-size: 3.5rem; font-family: 'Times New Roman', serif; margin-bottom: 15px; text-transform: uppercase;
}
.parallax-content p { font-size: 1.2rem; }
@media (max-width: 768px) {
    .parallax-container { background-attachment: scroll; min-height: 300px; }
    .parallax-content h2 { font-size: 2rem; }
}
</style>