<?php 
use app\assets\ViboonAsset;
use yii\helpers\Html;

ViboonAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/assets/images/brand/favicon.ico')]);

$checkSessionUrl = \yii\helpers\Url::to(['/site/check-session']);
$loginUrl = \yii\helpers\Url::to(['/site/login']);
$csrfToken = Yii::$app->request->getCsrfToken();
?>
<?php $this->beginPage() ?>
<!doctype html>
<html lang="vi" dir="ltr">
<head>
    <link rel="shortcut icon" type="image/x-icon" href="<?= Yii::getAlias('@web') ?>/assets/images/brand/favicon.ico" >
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/dropzone.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/dropzone.min.js"></script>
 
    <!-- <script src="<?= Yii::getAlias('@web') ?>/js/webcam.min.js"></script>
    <script src="<?= Yii::getAlias('@web') ?>/css/dropzone.min.css"></script>
    <script src="<?= Yii::getAlias('@web') ?>/js/dropzone.min.js"></script> -->

    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
<!--     <meta name="csrf-token" content="<?= Yii::$app->request->csrfToken ?>"> -->
</head>
<body class="app sidebar-mini">
<?php $this->beginBody() ?>

<!-- Loader -->
<div id="global-loader">
    <img src="<?= Yii::getAlias('@web') ?>/assets/images/svgs/loader1.svg" alt="loader">
</div>

<!-- Page -->
<div class="page">
    <?= $this->render('_top') ?>        
    <?= $this->render('_left') ?>
    <!-- Main Content-->
    <div class="main-content side-content pt-0">
        <div class="side-app">
            <div class="main-container container-fluid">
                <?= $content ?>
            </div>
        </div>
    </div>
    <!-- End Main Content-->      
    <?= $this->render('_slidebar') ?>        
    <?= $this->render('_footer') ?>
</div>

<!-- Back to top -->
<a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

<?php $this->endBody() ?>

<script>
function checkSession() {
    $.ajax({
        url: <?= json_encode($checkSessionUrl) ?>,
        type: 'POST',
        data: { _csrf: <?= json_encode($csrfToken) ?> },
        success: function(res) {
            if(res.logged_out) {
                alert("Phiên làm việc đã hết hạn!");
                window.location.href = <?= json_encode($loginUrl) ?>;
            }
        },
        error: function(xhr) {
            console.error("Session check error:", xhr.status, xhr.responseText);
        }
    });
}

// Check session ngay khi load page
checkSession();

// Check session mỗi 30 giây
setInterval(checkSession, 30000);
</script>

</body>
</html>
<?php $this->endPage() ?>
