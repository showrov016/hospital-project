<head>
    <meta charset="utf-8">
    <meta name="description" content="DIKKHA: Interactive learning- anywhere, anytime">
    <meta name="viewport" id="vp" content="width=device-width, initial-scale=1, maximum-scale=1">
    <?php echo link_tag('resources/images/favicon-64x64.png', 'icon', 'image/x-icon'); ?>
    <title><?= $pageTitle ?></title>

    <script type="text/javascript">var base_url = "<?php echo base_url(); ?>";</script>

    <?php
    /**
     * Library Resources
     */
    echo '<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,400i,500,700&amp;subset=latin-ext" rel="stylesheet">';
    echo '<link href="https://fonts.googleapis.com/css?family=Baloo&display=swap" rel="stylesheet">';

    echo link_tag('resources/lib/jquery/jquery-ui.min.css');
    echo SCRIPT . base_url('resources/lib/jquery/jquery-3.2.1.min.js') . END_SCRIPT;
    echo SCRIPT . base_url('resources/lib/jquery/jquery-ui.min.js') . END_SCRIPT;
    //sweetAlert2 library
    echo SCRIPT . base_url('resources/lib/jquery/swal.min.js') . END_SCRIPT;

    echo link_tag('https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
    echo SCRIPT . ('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js') . END_SCRIPT;
    echo SCRIPT . ('https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js') . END_SCRIPT;
    
    
//   echo SCRIPT . base_url('resources/lib/bootstrap-3.3.7/js/mdb.min.js') . END_SCRIPT;

    echo link_tag('resources/lib/fontawesome-5.11/css/all.min.css');
    echo SCRIPT . ('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js') . END_SCRIPT;
    echo link_tag('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css');

    // Bootsrap datetimepicker // Moment JS
    echo SCRIPT . base_url('resources/lib/clockpicker/js/moment.min.js') . END_SCRIPT;
    echo SCRIPT . ('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment-with-locales.min.js') . END_SCRIPT;
    echo link_tag('https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css');
    echo SCRIPT . base_url('resources/lib/js/fullcalendar.min.js') . END_SCRIPT;

    echo SCRIPT . ('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js') . END_SCRIPT;
    echo link_tag('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css');
    echo SCRIPT . base_url('resources/lib/clockpicker/js/bootstrap-material-datetimepicker.js') . END_SCRIPT;
    echo SCRIPT . 'https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js' . END_SCRIPT;
    echo '<link href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" rel="stylesheet">';
    echo link_tag('resources/lib/clockpicker/css/bootstrap-material-datetimepicker.css');

    echo SCRIPT . base_url('resources/lib/jquery/jquery.toaster.js') . END_SCRIPT;
    echo link_tag('https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css');
    echo SCRIPT . 'https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js' . END_SCRIPT;


    /**
     * User Defined Resources
     */
    echo link_tag('resources/users/css/admin.min.css?' . time());
//    echo link_tag('resources/user/css/main.css?' . time());
    echo SCRIPT . base_url('resources/users/js/admin.js?') . time() . END_SCRIPT;
    echo SCRIPT . base_url('resources/users/js/custom.js?') . time() . END_SCRIPT;
    ?>

</head>