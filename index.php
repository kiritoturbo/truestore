<?php
if (!is_user_logged_in()) {
    // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập của WordPress
    $login_url = wp_login_url();
    wp_redirect($login_url);
    exit;
}
if (is_user_logged_in()) {
    $current_user = wp_get_current_user();
    $nickname = get_user_meta(wp_get_current_user()->ID, 'nickname', true);
    echo '<div class="content container">
        Welcome, logged in ' . $current_user->user_login . ' ' . $nickname . '. <a href="' . wp_logout_url() . '">Click here to logout</a>
    </div>';
} else {
    echo 'Please login by <a href="' . wp_login_url() . '">clicking here</a>.';
}
?>

<?php get_header() ?>





<style>
    body {
        overflow-y: auto;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
        margin: 20px;
    }

    .switch input {
        display: none;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    .logo-image {
        width: auto;
        height: 50px;

    }

    .logo-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    input[type="color"] {
        -webkit-appearance: none;
        border: none;
        width: 32px;
        height: 32px;
    }

    input[type="color"]::-webkit-color-swatch-wrapper {
        padding: 0;
    }

    input[type="color"]::-webkit-color-swatch {
        border: none;
    }

    .info-namecheap {
        background-color: white;
    }

    a {
        text-decoration: none;
        color: #000;
    }

    .overlay {
        position: fixed;
        /* Sit on top of the page content */
        display: none;
        /* Hidden by default */
        width: 100%;
        /* Full width (cover the whole page) */
        height: 100%;
        /* Full height (cover the whole page) */
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        /* Black background with opacity */
        z-index: 2;
        /* Specify a stack order in case you're using a different order for other elements */
        cursor: pointer;
        /* Add a pointer on hover */
    }

    .loading-spinner {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .animated-text {
        position: fixed;
        top: 30%;
        left: 50%;
        z-index: 10000;
        color: white;
        font-size: 30px;
        transform: translate(-50%, -50%);
        overflow: hidden;
        white-space: nowrap;
        border-right: .15em solid white;
        animation: typing 5s steps(30, end) 1s infinite alternate,
            blink-caret .75s step-end infinite;
    }

    @keyframes typing {
        from {
            width: 0;
        }

        to {
            width: 100%;
        }
    }

    @keyframes untyping {
        from {
            width: 100%;
        }

        to {
            width: 0;
        }
    }

    @keyframes retyping {
        from {
            width: 0;
        }

        to {
            width: 100%;
        }
    }

    @keyframes blink-caret {

        from,
        to {
            border-color: transparent;
        }

        50% {
            border-color: white;
        }
    }

    .gancongGA {
        position: fixed;
        top: 30%;
        left: 30px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #2196F3;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .gancongGA a {
        color: #fff;
    }

    /* nút ring  */
    a.devvn_animation_zoom {
        color: #fff;
        display: block;
        background: #1cb2ed;
        position: fixed;
        left: 35px;
        top: 10%;
        border-radius: 50%;
        -moz-border-radius: 50%;
        -webkit-border-radius: 50%;
        padding: 15px;
        z-index: 9999;
        -webkit-animation: devvn_zoom 1.5s infinite linear;
        -moz-animation: devvn_zoom 1.5s infinite linear;
        -o-animation: devvn_zoom 1.5s infinite linear;
        animation: devvn_zoom 1.5s infinite linear;
        box-shadow: 0 0 0 0 #1cb2ed;
    }

    a.devvn_animation_zoom img {
        width: 32px;
        height: 32px;
        -webkit-animation: calltrap_spin 3s infinite linear;
        -moz-animation: calltrap_spin 3s infinite linear;
        -o-animation: calltrap_spin 3s infinite linear;
        animation: calltrap_spin 3s infinite linear
    }

    @-webkit-keyframes devvn_zoom {
        70% {
            box-shadow: 0 0 0 15px transparent
        }

        100% {
            box-shadow: 0 0 0 0 transparent
        }
    }

    @keyframes devvn_zoom {
        70% {
            box-shadow: 0 0 0 15px transparent
        }

        100% {
            box-shadow: 0 0 0 0 transparent
        }
    }

    @-webkit-keyframes calltrap_spin {
        0% {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg)
        }

        30% {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg)
        }

        33% {
            -webkit-transform: rotate(-10deg);
            transform: rotate(-10deg)
        }

        36% {
            -webkit-transform: rotate(10deg);
            transform: rotate(10deg)
        }

        39% {
            -webkit-transform: rotate(-10deg);
            transform: rotate(-10deg)
        }

        42% {
            -webkit-transform: rotate(10deg);
            transform: rotate(10deg)
        }

        45% {
            -webkit-transform: rotate(-10deg);
            transform: rotate(-10deg)
        }

        48% {
            -webkit-transform: rotate(10deg);
            transform: rotate(10deg);
            opacity: 1
        }

        51% {
            -webkit-transform: rotate(-10deg);
            transform: rotate(-10deg)
        }

        54% {
            -webkit-transform: rotate(10deg);
            transform: rotate(10deg)
        }

        57% {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg)
        }
    }

    @-moz-keyframes calltrap_spin {
        0% {
            -moz-transform: rotate(0deg);
            transform: rotate(0deg)
        }

        30% {
            -moz-transform: rotate(0deg);
            transform: rotate(0deg)
        }

        33% {
            -moz-transform: rotate(-10deg);
            transform: rotate(-10deg)
        }

        36% {
            -moz-transform: rotate(10deg);
            transform: rotate(10deg)
        }

        39% {
            -moz-transform: rotate(-10deg);
            transform: rotate(-10deg)
        }

        42% {
            -moz-transform: rotate(10deg);
            transform: rotate(10deg)
        }

        45% {
            -moz-transform: rotate(-10deg);
            transform: rotate(-10deg)
        }

        48% {
            -moz-transform: rotate(10deg);
            transform: rotate(10deg);
            opacity: 1
        }

        51% {
            -moz-transform: rotate(-10deg);
            transform: rotate(-10deg)
        }

        54% {
            -moz-transform: rotate(10deg);
            transform: rotate(10deg)
        }

        57% {
            -moz-transform: rotate(0deg);
            transform: rotate(0deg)
        }
    }

    @-o-keyframes calltrap_spin {
        0% {
            -o-transform: rotate(0deg);
            transform: rotate(0deg)
        }

        30% {
            -o-transform: rotate(0deg);
            transform: rotate(0deg)
        }

        33% {
            -o-transform: rotate(-10deg);
            transform: rotate(-10deg)
        }

        36% {
            -o-transform: rotate(10deg);
            transform: rotate(10deg)
        }

        39% {
            -o-transform: rotate(-10deg);
            transform: rotate(-10deg)
        }

        42% {
            -o-transform: rotate(10deg);
            transform: rotate(10deg)
        }

        45% {
            -o-transform: rotate(-10deg);
            transform: rotate(-10deg)
        }

        48% {
            -o-transform: rotate(10deg);
            transform: rotate(10deg);
            opacity: 1
        }

        51% {
            -o-transform: rotate(-10deg);
            transform: rotate(-10deg)
        }

        54% {
            -o-transform: rotate(10deg);
            transform: rotate(10deg)
        }

        57% {
            -o-transform: rotate(0deg);
            transform: rotate(0deg)
        }
    }

    @keyframes calltrap_spin {
        0% {
            -webkit-transform: rotate(0deg);
            -moz-transform: rotate(0deg);
            -o-transform: rotate(0deg);
            transform: rotate(0deg)
        }

        30% {
            -webkit-transform: rotate(0deg);
            -moz-transform: rotate(0deg);
            -o-transform: rotate(0deg);
            transform: rotate(0deg)
        }

        33% {
            -webkit-transform: rotate(-10deg);
            -moz-transform: rotate(-10deg);
            -o-transform: rotate(-10deg);
            transform: rotate(-10deg)
        }

        36% {
            -webkit-transform: rotate(10deg);
            -moz-transform: rotate(10deg);
            -o-transform: rotate(10deg);
            transform: rotate(10deg)
        }

        39% {
            -webkit-transform: rotate(-10deg);
            -moz-transform: rotate(-10deg);
            -o-transform: rotate(-10deg);
            transform: rotate(-10deg)
        }

        42% {
            -webkit-transform: rotate(10deg);
            -moz-transform: rotate(10deg);
            -o-transform: rotate(10deg);
            transform: rotate(10deg)
        }

        45% {
            -webkit-transform: rotate(-10deg);
            -moz-transform: rotate(-10deg);
            -o-transform: rotate(-10deg);
            transform: rotate(-10deg)
        }

        48% {
            -webkit-transform: rotate(10deg);
            -moz-transform: rotate(10deg);
            -o-transform: rotate(10deg);
            transform: rotate(10deg);
            opacity: 1
        }

        51% {
            -webkit-transform: rotate(-10deg);
            -moz-transform: rotate(-10deg);
            -o-transform: rotate(-10deg);
            transform: rotate(-10deg)
        }

        54% {
            -webkit-transform: rotate(10deg);
            -moz-transform: rotate(10deg);
            -o-transform: rotate(10deg);
            transform: rotate(10deg)
        }

        57% {
            -webkit-transform: rotate(0deg);
            -moz-transform: rotate(0deg);
            -o-transform: rotate(0deg);
            transform: rotate(0deg)
        }
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
<div class="overlay">
    <span class="animated-text">Bảnh chờ tý nghen.....</span>
    <div class="loading-spinner" style="display: block;margin:0 auto;">

        <!-- Hình ảnh quay quay -->
        <img src="https://www.sunlife.com.vn/content/dam/sunlife/legacy/assets/id/images/loadingimg.gif" alt="Loading..." />
        <img src="https://images.viblo.asia/a87852d0-d60c-4a7c-ae42-0bfb6679ecb3.gif" alt="image">
    </div>
</div>
<!-- <div class="gancongGA">
    <a href="<?php echo home_url(); ?>/page-gan-cong">GA</a>
</div> -->
<!-- <a href="tel:xxx" title="Hotline" class="devvn_animation_zoom">
    <img width="32" height="32" src="https://levantoan.com/wp-content/uploads/2017/10/phone-call.png" alt="" loading="lazy">
</a> -->
<a href="<?php echo home_url(); ?>/page-gan-cong" title="Hotline" class="devvn_animation_zoom">
    <!-- <img width="32" height="32" src="https://levantoan.com/wp-content/uploads/2017/10/phone-call.png" alt="" loading="lazy"> -->
    GA
</a>
<div class="content container">
	<div class="row">
		<iframe frameborder="0" width="80%" height="300px" src="https://webtygia.com/api/xang-dau?bgheader=9c27b0&colorheader=ffffff&padding=5&fontsize=13&hienthi=trongnuoc&"></iframe>
	</div>
    <div class="row">
        <!-- <iframe frameborder="0" marginwidth="0" marginheight="0" src="http://thienduongweb.com/tool/weather/?r=1&w=1&g=1&d=0" width="100%" height="370" scrolling="yes"></iframe> -->

        <div class="info-namecheap d-flex justify-content-between align-items-center">
            <div class="d-flex justify-content-between align-items-center">
                <label for="favcolor">Select your favorite color:</label>
                <input type="color" id="favcolor" value="#ff0000"><br><br>
            </div>
            <div class="logo-image">
                <img src="<?php echo get_template_directory_uri() ?>/assets/image/logo1.png" alt="logo image">
            </div>

            <div>
                <div class="domain_cuonglv">
                    <label for="" class="form-text">Tổng tiền site sạch:</label>
                    <span id="item-info-namecheap"></span>
                </div>
                <div class="domain_TM">
                    <label for="" class="form-text">Tổng tiền site TM:</label>
                    <span id="item-info-namecheap_tm"></span>
                </div>
            </div>
        </div>
        <div class="card mb-3 custom-card">
            <div class="content-widget">
                <div class="mb-3 mt-3">
                    <label for="exampleInputEmail1" class="form-label card-title">Nhập domain</label>
                    <textarea rows="4" cols="50" class="form-control domain_post" id="domainInput" aria-describedby="domainNamecheap" placeholder="example: familytrue.com"></textarea>
                    <label for="" class="total_domain_input"></label>
                    <!-- <div id="domainNamecheap" class="form-text">Nhập domain namecheap</div> -->

                    <div>
                        <label for="idServerHost"> Select you server</label>
                        <select class="form-select" id="idServerHost" name="idServerHost">
                            <option value="45.77.98.179" selected>45.77.98.179</option>
                            <option value="113.192.8.160">113.192.8.160</option>
                            <option value="42.96.58.83" >42.96.58.83</option>
                            <option value="42.96.58.20">42.96.58.20</option>
                            <option value="42.96.58.231">42.96.58.231</option>
                        </select>
                    </div>
                    <div>
                        <label for="idHostingcustom">Nhập ID server</label>
                        <input type="text" class="form-control" id="idHostingcustom" placeholder="Nhập ID server">
                    </div>
                    <div class="d-flex align-items-center">
                        <label for="tm-site-web">Site TM </label>
                        <label class="switch">
                            <input type="checkbox" id="tm-site-web" />
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="d-flex align-items-center">
                        <label for="tm-site-web">Site hệ thống mới-NodeJS</label>
                        <label class="switch">
                            <input type="checkbox" checked id="nodejs-site-web" />
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <button class="call-ajax btn btn-primary">Tạo một shop mới</button>
                <button class="set-server-namecheap btn btn-warning">Set custom server namecheap</button>
                <button class="set-dns-namecheap btn btn-success">Set dns cloudfalre</button>
                <button class="set-null-textarea btn btn-danger">Xóa trắng</button>
                <button class="open-tab-brower btn btn-info">Mở nhiều tab</button>
                <button class="convert-hosting-custom btn btn-secondary">Chuyển dns server</button>
                <div class="display-post mb-4 " style="text-align: center; font-size: 20px;"></div>
            </div>

        </div>
    </div>
</div>
	

<div class="content-xangdau">

    <?php
    $url = "https://vnexpress.net/chu-de/gia-xang-dau-3026";

    // Sử dụng cURL để lấy nội dung trang web
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $html = curl_exec($ch);
    curl_close($ch);

    // Tạo đối tượng DOM từ nội dung HTML
    $dom = new DOMDocument();
    @$dom->loadHTML($html);

    // Lấy các phần tử table trong trang web
    $tables = $dom->getElementsByTagName('table');

    foreach ($tables as $table) {
        // Bạn có thể thực hiện xử lý dữ liệu ở đây, ví dụ:
        $rows = $table->getElementsByTagName('tr');
        $message = ""; // Biến để lưu trữ thông tin từ vòng lặp

        foreach ($rows as $row) {
            $cells = $row->getElementsByTagName('td');
            $rowData = array();

            foreach ($cells as $cell) {
                $rowData[] = trim($cell->nodeValue); // Trim để loại bỏ khoảng trắng thừa
            }

            if (!empty($rowData) && count($rowData) == 3) {
                $matHang = $rowData[0];
                $gia = $rowData[1];
                $soVoiKyTruoc = $rowData[2];

                if ($matHang !== "Mặt hàng") {
                    $message .= "$matHang: $gia đồng. (Tăng $soVoiKyTruoc đồng)\n\n";
                }
            }
        }

        // Echo thông tin sau khi kết thúc vòng lặp
        echo nl2br($message); // Sử dụng nl2br để xuống dòng trong HTML
        echo "Cập nhật ngày " . date("d-m-Y") . " từ VnExpress."; // Thêm ngày hiện tại vào thông báo
        break; // Thoát khỏi vòng lặp sau khi xử lý xong bảng đầu tiên
    }
    ?>
</div>

<style>
    .content-xangdau {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: white;
        padding: 12px 24px;
        border-radius: 10px;
        font-size: 12px;
        box-shadow: 0px 4px 20px 0px rgba(0, 0, 0, 0.10);
        z-index: 10;
    }
</style>

<?php get_footer() ?>