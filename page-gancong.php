<?php
/*
 Template Name: page-gancong
 */
?>
<style>
    .logobackhome {
        position: fixed;
        top: 20%;
        left: 30px;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .imglogobackhome{
        width: 181px;

    }
    @media (max-width: 576px) {
        .info-namecheap{
            flex-direction: column !important;
        }
        .content-xangdau{
            display: none;
        }
        .card{
            flex-direction: column !important;
        }
        .logobackhome{
            position: static !important;
        }
    }
</style>

<?php if (is_user_logged_in()) {
    $user_id = get_current_user_id();
    $current_user = wp_get_current_user();
    $nameUser = $current_user->user_login;
    $profile_url = get_author_posts_url($user_id);
    $edit_profile_url  = get_edit_profile_url($user_id);
?>

    <?php get_header();
    ini_set("max_execution_time", 30000000);
    error_reporting(E_ERROR | E_PARSE);
    ?>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- loader Start -->
    <script src="https://www.gstatic.com/firebasejs/5.4.0/firebase.js"></script>

    <script type="text/javascript">
        var myAjax = {
            ajaxurl: "<?php echo admin_url('admin-ajax.php'); ?>"
        };
    </script>
    <script src="<?php echo get_template_directory_uri(); ?>/assets/js/myjs.js"></script>
    <!-- để tải bản js mới nhất mà ko bị dính cache  -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <a class="logobackhome" href="<?php echo home_url(); ?>">
        <img src="<?php echo get_template_directory_uri() ?>/assets/image/logo1.png" alt="logo image" class="imglogobackhome">
        <span>Back home</span>
    </a>
    
    <div id="loading">
        <div id="loading-center"></div>
    </div>
    <div class="wrapper">
        <div id="content-page" class="content-page">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="iq-card">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">Update Cổng cho Store</h4>
                                </div>
                            </div>
                            <div class="card-header">
                                Gắn list GA
                            </div>
                            <div style="display: flex; padding: 15px;flex-direction: row; margin-bottom:20px;" class="card" >

                                <div style="display: flex;gap:20px;" class="card-body">
                                    <span id="left-textare"></span>
                                    <span id="right-textare"></span>
                                    <textarea rows="4" cols="50" class="form-control " id="domainGAinput" aria-describedby="domainGAinput" placeholder="example: familytrue.com"></textarea>
                                    <textarea rows="4" cols="50" class="form-control " id="gaInput" aria-describedby="gaInput" placeholder="GA của bạn"></textarea>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-success btn-lg ga_update_002_button">Gắn 002</button>
                                    <button type="button" class="btn btn-danger btn-lg ga_update_003_button">Gắn 003</button>
                                    <button type="button" class="btn btn-warning btn-lg ga_update_222_button">Gắn 222</button>

                                    <br>

                                    <button style="margin-top: 10px;" type="button" class="btn btn-primary btn-lg ga_update_666_button">Gắn 666</button>
                                    <button style="margin-top: 10px;" type="button" class="btn btn-success btn-lg ga_update_888_button">Gắn 888</button>
                                    <button style="margin-top: 10px;" type="button" class="btn btn-danger btn-lg ga_update_DTH_button">Gắn DTH</button>

                                    <br>

                                    <button style="margin-top: 10px;" type="button" class="btn btn-warning btn-lg ga_update_NKH_button">Gắn NKH</button>
                                </div>
                            </div>
                            <div class="iq-card-body ">
                                <div class="table-responsive">

                                    <div style="padding-left: 10px;padding-right: 10px;" class="col-md-7 form-group">
                                        <label>Store:</label>
                                        <input type="text" class="form-control" id="store_value" value="">


                                        <button style="margin-top: 10px;" type="button" class="btn btn-primary btn-lg clear_cache_button">Xoá Cache</button>
                                        <br>

                                        <label style="margin-top:20px;">Imgproxy_url:</label>
                                        <input type="text" class="form-control" id="imgproxy_url_value" value="https://img.kyccdn.com">
                                        <br>

                                        <label>Pixel ID: (Nhập dấu | ngăn cách để thêm đc nhiều pixel ID)</label>
                                        <input type="text" class="form-control" id="pixel_value" value="">
                                        <br>

                                        <label>GA: (Nhập dấu | ngăn cách để thêm đc nhiều GA ID)</label>
                                        <input type="text" class="form-control" id="ga_value" value="">
                                        <br>

                                        <button type="button" class="btn btn-success btn-lg update_002_button">Gắn 002</button>
                                        <button type="button" class="btn btn-danger btn-lg update_003_button">Gắn 003</button>
                                        <button type="button" class="btn btn-warning btn-lg update_222_button">Gắn 222</button>

                                        <br>

                                        <button style="margin-top: 10px;" type="button" class="btn btn-primary btn-lg update_666_button">Gắn 666</button>
                                        <button style="margin-top: 10px;" type="button" class="btn btn-success btn-lg update_888_button">Gắn 888</button>
                                        <button style="margin-top: 10px;" type="button" class="btn btn-danger btn-lg update_DTH_button">Gắn DTH</button>

                                        <br>

                                        <button style="margin-top: 10px;" type="button" class="btn btn-warning btn-lg update_NKH_button">Gắn NKH</button>

                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    </body>

    </html>

    <?php get_footer(); ?>
<?php } else {
    $login_page  = home_url('/login/');
    wp_redirect($login_page);
} ?>
</div>
</div>
</main>