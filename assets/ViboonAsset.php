<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */
namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ViboonAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'assets/css/style.css?v=6',
        'assets/css/plugins.css',
        'assets/css/icons.css',
        'assets/switcher/css/switcher.css',
        'assets/switcher/demo.css',
        'css/site.css?v=1.3',
        'assets/fontawesome-free-6.4.0-web/css/all.min.css',

        'assets/css/sweetalert2.min.css',
        //'css/dropzone.min.css',
        'assets/css/sidebar-custom.css',
    ];
    public $js = [
        'assets/plugins/bootstrap/popper.min.js',
        'assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js',
        'assets/plugins/perfect-scrollbar/p-scroll-1.js',
        'assets/plugins/sidemenu/sidemenu.js',
        'assets/plugins/sidebar/sidebar.js',
        'assets/js/sticky.js',
        'assets/plugins/notify/js/jquery.growl.js',
        'assets/plugins/notify/js/notifIt.js',
        
        //'assets/plugins/calendar/underscore-min.js',
        'assets/plugins/fullcalendar/moment.min.js',
        'assets/plugins/fullcalendar/fullcalendar.min.js',
        'assets/plugins/fullcalendar/locales/vi.js',
        //'assets/js/fullcalendar.js',
        'assets/js/custom.js',
        //'assets/js/custom-switcher.js',
        //'assets/switcher/js/switcher.js',        
        'assets/js/tooltip.js',
        'assets/plugins/owl-carousel/owl.carousel.js',
        'assets/plugins/multislider/multislider.js',
        'assets/js/carousel.js',
        'js/print-this/printThis.js',
        'js/ModalRemote.js?v=2.3',
        'js/ajaxcrud.js?v=2',        
        'js/custom.js?v=1',
        'js/tinymce/tinymce_5.10.7.min.js',

        'assets/js/sweetalert2.all.min.js',
       
        //'assets/plugins/tabs/jquery.multipurpose_tabcontent.js',
        //'assets/plugins/tabs/tab-content.js'
        // Datatable scripts
        'assets/plugins/datatable/js/jquery.dataTables.min.js',
        'assets/plugins/datatable/js/dataTables.bootstrap5.js',
        'assets/plugins/datatable/js/dataTables.buttons.min.js',
        'assets/plugins/datatable/js/buttons.bootstrap5.min.js',
        'assets/plugins/datatable/js/jszip.min.js',
        'assets/plugins/datatable/pdfmake/pdfmake.min.js',
        'assets/plugins/datatable/pdfmake/vfs_fonts.js',
        'assets/plugins/datatable/js/buttons.html5.min.js',
        'assets/plugins/datatable/js/buttons.print.min.js',
        'assets/plugins/datatable/js/buttons.colVis.min.js',
        'assets/plugins/datatable/dataTables.responsive.min.js',
        'assets/plugins/datatable/responsive.bootstrap5.min.js',
        // Uncomment the following lines if you want to use DataTables

        //'js/webcam.min.js',
        //'js/dropzone.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
        'yii\bootstrap5\BootstrapPluginAsset',
        'kartik\grid\GridViewAsset',
        'kartik\select2\Select2Asset'
    ];

}

