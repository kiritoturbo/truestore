<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="facebook-domain-verification" content="6jw4dta2jtjhqebloylo4ghq4g144w" />
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/assets/image/Screenshot_3.png" type="image/x-icon" />
    <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/assets/image/Screenshot_3.png" type="image/x-icon" />
    <title>Dropshipping | Web Application Development | True Software</title>
    <!-- <link rel="shortcut icon" href="https://truestore.vn/wp-content/themes/twentysixteen/assets/images/faces/face.jpg"> -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cabin:ital,wght@0,400..700;1,400..700&family=KoHo:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Montserrat:ital,wght@0,300;0,400;0,500;0,700;1,500&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" />

    <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/assets/image/Screenshot_3.png"
        type="image/x-icon">



    <?php wp_head(); ?>
    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 45px !important;
            height: 100% !important;
        }

        .select2-container .select2-selection--single {
            height: 45px !important;
        }

        .btn {
            height: 45px;
        }

        .copy_cookie {
            cursor: pointer;
        }

        .copy_nscookie {
            cursor: pointer;
        }

        .copy_ua {
            cursor: pointer;
        }

        .morecontent span {
            display: none;
        }

        .morelink {
            display: block;
        }

        .custom-control-inline {
            margin-right: 0 !important;
        }
    </style>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>