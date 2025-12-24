<?php
$logo = get_option('cgl_maintenance_logo') ?: get_option('cgl_login_logo');
$bg_color = get_option('cgl_maintenance_bg_color','#f2f2f2');
$text_color = get_option('cgl_maintenance_text_color','#444');
$maintenance_text = get_option('cgl_maintenance_text','We are working on the site. Come back later.');
$end_date = get_option('cgl_maintenance_end_date','');
$maintenance_title = get_option('cgl_maintenance_title','Maintenance Mode');

$allowed_roles = ['administrator','editor'];
$current_user = wp_get_current_user();
if(array_intersect($allowed_roles,$current_user->roles)) return;
?>
<!DOCTYPE html>
<html>
<head>
<title>Maintenance</title>
<style>
body
{display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
    background:<?php echo esc_html($bg_color); ?>;
    font-family:sans-serif;
    margin:0;
    color:<?php echo esc_html($text_color); ?>;
}
.box{
    text-align:center;
}
.box img{
    max-width:200px;
    margin-bottom:20px;
}
#countdown{
    font-size:24px;
    margin-top:15px;
}
.container-box{
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
    max-width: 100%;
}
</style>
</head>
<body>
  <div class="container-box">  
<div class="box">
<?php if($logo): ?><img src="<?php echo esc_url($logo); ?>" alt="Logo"><?php endif; ?>
<h1><?php echo esc_html($maintenance_title); ?></h1>
<p><?php echo esc_html($maintenance_text); ?></p>
<?php if ($end_date): ?>
    <div id="countdown"></div>
<?php endif; ?>
</div>
</div>

<?php if ($end_date): ?>
<script>
const countDownDate = new Date("<?php echo esc_js($end_date); ?>").getTime();
const countdownEl = document.getElementById('countdown');

const x = setInterval(function () {
    const now = new Date().getTime();
    const distance = countDownDate - now;

    if (distance <= 0) {
        clearInterval(x);
        countdownEl.innerHTML = "We’ll be back shortly 👋";
        return;
    }

    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    countdownEl.innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;
}, 1000);
</script>
<?php endif; ?>

</body>
</html>
