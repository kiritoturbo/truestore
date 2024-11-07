<?php
function custom_login_redirect()
{
    return 'home_url()';
}
add_filter('login_redirect', 'custom_login_redirect');

add_filter('show_admin_bar', '__return_false');
// bỏ topbar khi đăng nhập admin
add_action('after_setup_theme', 'remove_admin_bar');

function remove_admin_bar()
{
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}





// cron zingserver
// function schedule_daily_cloud_check() {
//     if (!wp_next_scheduled('daily_cloud_check_event')) {
//         wp_schedule_event(time(), 'daily', 'daily_cloud_check_event');
//     }
// }
// add_action('wp', 'schedule_daily_cloud_check');

// add_action('daily_cloud_check_event', 'check_clouds_expiry_and_notify_telegram');

// function custom_cron_schedules($schedules) {
//     $schedules['daily'] = [
//         'interval' => 86400,
//         'display'  => __('Once Daily'),
//     ];
//     return $schedules;
// }
// add_filter('cron_schedules', 'custom_cron_schedules');
function schedule_twice_daily_cloud_check() {
    if (!wp_next_scheduled('twice_daily_cloud_check_event')) {
        wp_schedule_event(time(), 'twice_daily', 'twice_daily_cloud_check_event');
    }
}
add_action('wp', 'schedule_twice_daily_cloud_check');

add_action('twice_daily_cloud_check_event', 'check_clouds_expiry_and_notify_telegram');

function custom_cron_schedules($schedules) {
    $schedules['twice_daily'] = [
        'interval' => 43200, // 12 hours in seconds
        'display'  => __('Twice Daily (Every 12 Hours)'),
    ];
    return $schedules;
}
add_filter('cron_schedules', 'custom_cron_schedules');



function check_clouds_expiry_and_notify_telegram() {
    $api_url = 'https://api.zingserver.com/cloud/list/running';
    $access_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOiI2NGNkMmUzMGMxZjhhOTlmNTFhOGJhMDciLCJpYXQiOjE3MzA1NTgyOTQsImV4cCI6MTczMzE1MDI5NH0.jvBlwMmD2InNw-ddWCpEAVh2P2dkHs12ND01yzKo_rg'; // Thay bằng accessToken thực tế

    // Thêm header Authorization vào yêu cầu API
    $args = [
        'headers' => [
            'Authorization' => 'Bearer ' . $access_token,
        ],
    ];
    
    $response = wp_remote_get($api_url, $args);
    
    if (is_wp_error($response)) {
        error_log('Failed to call API: ' . $response->get_error_message());
        return;
    }
    
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    
    if ($data['status'] !== 'success' || empty($data['clouds'])) {
        error_log('API returned unexpected response.');
        return;
    }
    
    $current_date = new DateTime();
    $warning_days = 2;
    
    foreach ($data['clouds'] as $cloud) {
        $date_end = new DateTime($cloud['dateEnd']);
        $interval = $current_date->diff($date_end);
        $days_left = $interval->days;
        
        if ($days_left <= $warning_days && $interval->invert === 0) {
            $message = "@TruongDev25 @thuy6620 Cảnh báo! Máy chủ {$cloud['sourceName']} - IP:{$cloud['ip']} sẽ hết hạn vào ngày {$cloud['dateEnd']}.";
            send_telegram_notification($message);
        }
    }
}

function send_telegram_notification($message) {
    $bot_token = '7728333125:AAHDGiolyVZNfPwKQbep_JRhbv1NkN3pICM';
    $chat_id = '-4546512856';
    $telegram_url = "https://api.telegram.org/bot$bot_token/sendMessage";
    
    $args = [
        'body' => [
            'chat_id' => $chat_id,
            'text' => $message,
        ],
    ];
    
    wp_remote_post($telegram_url, $args);
}





//end cron zing servẻr










// get total price 
add_action('wp_ajax_getTotalDollarAccount', 'getTotalDollarAccount_function');
add_action('wp_ajax_nopriv_getTotalDollarAccount', 'getTotalDollarAccount_function');
function getTotalDollarAccount_function()
{
    $apiUser = "cuonglv";
    $apiKey  = "a880dd0536b14e218e2ac63120425dea";
    $userName = "cuonglv";
    $clientIp = "118.71.35.244";
    $apiUser_tm = "hjyulxttadjanie";
    $apiKey_tm  = "b71a26e052fb45b498ea98dd47dee62f";
    $userName_tm = "hjyulxttadjanie";
    $clientIp_tm = "118.71.35.244";
    $response_array = array(
        'total_dollar_cuonglv' => check_total_dollar_namecheap($apiUser, $apiKey, $userName, $clientIp),
        'total_dollar_tm' => check_total_dollar_namecheap($apiUser_tm, $apiKey_tm, $userName_tm, $clientIp_tm),
        'status' => 'ok'
    );

    echo json_encode($response_array);
    exit;
}


add_action('wp_ajax_getOrderSite', 'getOrderSite_function');
add_action('wp_ajax_nopriv_getOrderSite', 'getOrderSite_function');
function getOrderSite_function()
{
    if (!isset($_POST['store'])) {
        return;
    }
    $userName = $_POST['store'];

    $arrContextOptions = array(
        "ssl" => array(
            "verify_peer" => false,
            "verify_peer_name" => false,
        ),
    );

    $json = file_get_contents($userName, false, stream_context_create($arrContextOptions));

    echo $json;
    exit;
}
//set id hosting dns của cloudflare
add_action('wp_ajax_setidhostingdnscloude', 'setidhostingdnscloude_function');
add_action('wp_ajax_nopriv_setidhostingdnscloude', 'setidhostingdnscloude_function');
function setidhostingdnscloude_function()
{
    $domains = $_POST['domainPost'];
    $idhosting = $_POST['idhosting'];

    $wordpress_domains =  array_filter(array_map('trim', explode("\n", $domains)), 'strlen');
    foreach ($wordpress_domains as $domain) {
        $domain = trim($domain);
        // URL của API 
        $url = 'https://api.cloudflare.com/client/v4/zones/?name=' . $domain;

        // Token cloudflare
        $token = 'swiA7Nlr_xe31JfywKBtBkJKAVKetPplhQQMrGzA';

        // Headers
        $headers = array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        );

        // Gửi yêu cầu POST
        $response = wp_remote_get($url, array(
            // 'body' => $body,
            'headers' => $headers
        ));

        // Kiểm tra phản hồi
        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            echo "Something went wrong: $error_message";
        } else {
            // Lấy nội dung của phản hồi
            $response_body = wp_remote_retrieve_body($response);
            $response_code = wp_remote_retrieve_response_code($response);
            $response_data = json_decode($response_body, true);

            if (isset($response_data['result'][0]['id'])) {

                $id = $response_data['result'][0]['id'];
                $urlgetdns = 'https://api.cloudflare.com/client/v4/zones/' . $id . '/dns_records';
                // Gửi yêu cầu POST
                $responseurlgetdns = wp_remote_get($urlgetdns, array(
                    'headers' => $headers
                ));

                // Lấy nội dung của phản hồi
                $response_body1 = wp_remote_retrieve_body($responseurlgetdns);
                $response_code = wp_remote_retrieve_response_code($responseurlgetdns);
                $response_data1 = json_decode($response_body1, true);

                // // Kiểm tra và lấy giá trị id
                // exit;
                $dns_records = $response_data1['result'];

                if (empty($dns_records)) {
                    $url = "https://api.cloudflare.com/client/v4/zones/$id/dns_records";
                    $body = json_encode(array(
                        'type' => 'A',
                        'name' => $domain,
                        'content' =>  $idhosting,
                        'proxied' => true,
                        "ttl" => 1
                    ));
                    // JSON body
                    // $body1 = json_encode(array(
                    //     'type' => 'A',
                    //     'name' => 'admin',
                    //     'content' =>  $ip_server,
                    //     'proxied' => false,
                    //     "ttl" => 0
                    // ));
                    // Gửi yêu cầu POST
                    $response = wp_remote_post($url, array(
                        'body' => $body,
                        'headers' => $headers
                    ));
                    // Gửi yêu cầu POST
                    // $response1 = wp_remote_post($url, array(
                    //     'body' => $body1,
                    //     'headers' => $headers
                    // ));
                    $response_body = wp_remote_retrieve_body($response);
                    $response_code = wp_remote_retrieve_response_code($response);
                    // $response_body = wp_remote_retrieve_body($response1);
                    if ($response_code == 200) {
                        $response_data = json_decode($response_body, true);

                        // Kiểm tra nội dung phản hồi từ API
                        if (isset($response_data['success']) && $response_data['success'] === true) {
                            echo 'Thông báo: Thao tác thành công!<br>';
                        } else {
                            echo 'Thông báo: Có lỗi xảy ra trong phản hồi API!<br>';
                        }
                    } else {
                        echo 'Thông báo: Gửi yêu cầu thất bại. Mã trạng thái HTTP: ' . $response_code . '<br>';
                    }
                } else {
                    // Function to delete a DNS record by ID
                    foreach ($dns_records as $record) {
                        $record_id = $record['id'];
                        $delete_url = "https://api.cloudflare.com/client/v4/zones/$id/dns_records/$record_id";
                        $delete_response = wp_remote_request($delete_url, array(
                            'method'  => 'DELETE',
                            'headers' => $headers
                        ));

                        if (is_wp_error($delete_response)) {
                            error_log('Failed to delete DNS record ID ' . $record_id . ': ' . $delete_response->get_error_message());
                        } else {
                            $delete_body = wp_remote_retrieve_body($delete_response);
                            $delete_data = json_decode($delete_body, true);
                            error_log('Deleted record ID: ' . $record_id . ', Response: ' . print_r($delete_data, true));

                            $url = "https://api.cloudflare.com/client/v4/zones/$id/dns_records";
                            $body = json_encode(array(
                                'type' => 'A',
                                'name' => $domain,
                                'content' =>  $idhosting,
                                'proxied' => true,
                                "ttl" => 1
                            ));
                            // JSON body
                            // $body1 = json_encode(array(
                            //     'type' => 'A',
                            //     'name' => 'admin',
                            //     'content' =>  $ip_server,
                            //     'proxied' => false,
                            //     "ttl" => 0
                            // ));
                            // Gửi yêu cầu POST
                            $response = wp_remote_post($url, array(
                                'body' => $body,
                                'headers' => $headers
                            ));
                            // Gửi yêu cầu POST
                            // $response1 = wp_remote_post($url, array(
                            //     'body' => $body1,
                            //     'headers' => $headers
                            // ));
                            $response_body = wp_remote_retrieve_body($response);
                            $response_code = wp_remote_retrieve_response_code($response);
                            // $response_body = wp_remote_retrieve_body($response1);
                            if ($response_code == 200) {
                                $response_data = json_decode($response_body, true);

                                // Kiểm tra nội dung phản hồi từ API
                                if (isset($response_data['success']) && $response_data['success'] === true) {
                                    echo 'Thông báo: Thao tác thành công!<br>';
                                } else {
                                    echo 'Thông báo: Có lỗi xảy ra trong phản hồi API!<br>';
                                }
                            } else {
                                echo 'Thông báo: Gửi yêu cầu thất bại. Mã trạng thái HTTP: ' . $response_code . '<br>';
                            }
                            // echo 'Response Body:<pre>';
                            // echo json_encode($response_body);
                            // echo '</pre>';
                            echo '<hr>';
                        }
                    }
                }
            } else {
                // echo 'ID không tồn tại trong phản hồi.<br>';
                foreach ($response_data['errors'] as $error) {
                    echo 'Error Code: ' . $error['code'] . '<br>';
                    echo 'Error Message: ' . $error['message'] . '<br>';
                }
            }
        }
    }
    exit;
}

// set domain cloudflare 
add_action('wp_ajax_setcloudflare', 'setcloudflare_function');
add_action('wp_ajax_nopriv_setcloudflare', 'setcloudflare_function');

function setcloudflare_function()
{
    $domains = $_POST['domainPost'];
    $check_bool_TMVPCS = $_POST['checkTMVPCS'];
    $check_bool_sitenewupdate = $_POST['checkSiteNewNode'];
    if ($check_bool_TMVPCS == null) {
        $apiUser = "cuonglv";
        $apiKey  = "a880dd0536b14e218e2ac63120425dea";
        $userName = "cuonglv";
        $clientIp = "118.71.35.244";
    } else {
        $apiUser = "hjyulxttadjanie";
        $apiKey  = "b71a26e052fb45b498ea98dd47dee62f";
        $userName = "hjyulxttadjanie";
        $clientIp = "118.71.35.244";
    }

    $wordpress_domains =  array_filter(array_map('trim', explode("\n", $domains)), 'strlen');
    foreach ($wordpress_domains as $domain) {
        $domain = trim($domain);
        $parts = explode(".", $domain);
        $domainSLD =  $parts[0];
        $domainTLD = $parts[1];
        if ($check_bool_TMVPCS == null) {
            $api_set_domain = 'https://api.namecheap.com/xml.response?ApiUser=' . $apiUser . '&ApiKey=' . $apiKey . ' &UserName=' . $userName . '&Command=namecheap.domains.dns.setCustom&ClientIp=1.53.8.82&TLD=' . $domainTLD . '&NameServers=alaric.ns.cloudflare.com,tessa.ns.cloudflare.com&SLD=' . $domainSLD . '';
        } else {
            $api_set_domain = 'https://api.namecheap.com/xml.response?ApiUser=' . $apiUser . '&ApiKey=' . $apiKey . ' &UserName=' . $userName . '&Command=namecheap.domains.dns.setCustom&ClientIp=1.53.8.82&TLD=' . $domainTLD . '&NameServers=elijah.ns.cloudflare.com,harlee.ns.cloudflare.com&SLD=' . $domainSLD . '';
        }
        $response_set_domain = curl_get($api_set_domain);
        $xml = simplexml_load_string($response_set_domain);
        if ($xml && $xml->CommandResponse && $xml->CommandResponse->DomainDNSSetCustomResult && $xml->attributes()->Status == "OK") {
            $domainNameUpdated = $xml->CommandResponse->DomainDNSSetCustomResult->attributes()->Updated;
            echo $xml->CommandResponse->DomainDNSSetCustomResult->attributes()->Domain . ' đã được cập nhật trang thái ' . $domainNameUpdated . '<br>';
        } else {
            echo ((string) $xml->Errors->Error) . '<br/>';
            echo '<pre>';
            print_r($xml);
            echo '</pre>';
        }
        // $response = wp_remote_post('https://api.cloudflare.com/client/v4/zones', array(
        //     'headers' => array(
        //         'Content-Type' => 'application/json',
        //         'X-Auth-Email' => 'manhtruong2001nt@gmail.com',
        //         'X-Auth-Key' => '541ed13d7ac30be447a25f0761dcc5760bbeb',
        //     ),
        //     'body' => json_encode(array(
        //         'account' => array(
        //             'id' => '<ACCOUNT_ID>',
        //         ),
        //         'name' => $domain,
        //         'type' => 'full',
        //     )),
        // ));

        // // Kiểm tra xem có lỗi không
        // if (is_wp_error($response)) {
        //     // Xử lý lỗi
        //     $error_message = $response->get_error_message();
        //     echo "Error adding domain {$domain}: $error_message\n";
        // } else {
        //     // Xử lý phản hồi từ Cloudflare
        //     $response_code = wp_remote_retrieve_response_code($response);
        //     $response_body = wp_remote_retrieve_body($response);

        //     // Đảm bảo request thành công
        //     if ($response_code === 200 || $response_code === 201) {
        //         echo "Domain {$domain} added successfully.\n";
        //     } else {
        //         echo "Error adding domain {$domain}: $response_body\n";
        //     }
        // }
    }
    exit;
}


// set dns domain cloudflare site moi
add_action('wp_ajax_setdnscloudflare', 'setdnscloudflare_function');
add_action('wp_ajax_nopriv_setdnscloudflare', 'setdnscloudflare_function');

function setdnscloudflare_function()
{
    $domains = $_POST['domainPost'];
    $check_bool_TMVPCS = $_POST['checkTMVPCS'];
    $check_bool_sitenewupdate = $_POST['checkSiteNewNode'];
    $ip_server = $_POST['selectedValue'];

    if ($check_bool_TMVPCS == null) {
        $apiUser = "cuonglv";
        $apiKey  = "a880dd0536b14e218e2ac63120425dea";
        $userName = "cuonglv";
    } else {
        $apiUser = "hjyulxttadjanie";
        $apiKey  = "b71a26e052fb45b498ea98dd47dee62f";
        $userName = "hjyulxttadjanie";
    }
    $wordpress_domains =  array_filter(array_map('trim', explode("\n", $domains)), 'strlen');
    foreach ($wordpress_domains as $domain) {
        $domain = trim($domain);
        // URL của API 
        $url = 'https://api.cloudflare.com/client/v4/zones/?name=' . $domain;

        if ($check_bool_TMVPCS == null) {
            $apiUser = "cuonglv";
            $apiKey  = "a880dd0536b14e218e2ac63120425dea";
            $userName = "cuonglv";
            // Token
            $token = 'swiA7Nlr_xe31JfywKBtBkJKAVKetPplhQQMrGzA'; // Thay 'your_token_here' bằng token thật của bạn
        } else {
            $apiUser = "hjyulxttadjanie";
            $apiKey  = "b71a26e052fb45b498ea98dd47dee62f";
            $userName = "hjyulxttadjanie";
            $clientIp = "118.71.35.244";
            // Token
            $token = 'WG-H1uWb1s49QLNI7KB24S1thhZuQUSig-BwX3Fz'; // Thay 'your_token_here' bằng token thật của bạn
        }
        // Headers
        $headers = array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        );

        // Gửi yêu cầu POST
        $response = wp_remote_get($url, array(
            // 'body' => $body,
            'headers' => $headers
        ));

        // Kiểm tra phản hồi
        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            echo "Something went wrong: $error_message";
        } else {
            // Lấy nội dung của phản hồi
            $response_body = wp_remote_retrieve_body($response);
            $response_code = wp_remote_retrieve_response_code($response);
            $response_data = json_decode($response_body, true);

            // // // Kiểm tra và lấy giá trị id
            // echo '<pre>';
            // print_r($response_data['result']);
            // // print_r( $response_data['result'][0]['zone_id']);
            // echo '</pre>';
            // exit;
            if (isset($response_data['result'][0]['id'])) {

                $id = $response_data['result'][0]['id'];
                $urlgetdns = 'https://api.cloudflare.com/client/v4/zones/' . $id . '/dns_records';
                // echo $urlgetdns;
                // Gửi yêu cầu POST
                $responseurlgetdns = wp_remote_get($urlgetdns, array(
                    'headers' => $headers
                ));
                // echo '<pre>';
                // print_r( $responseurlgetdns);
                // echo '</pre>';
                // exit;
                // Lấy nội dung của phản hồi
                $response_body1 = wp_remote_retrieve_body($responseurlgetdns);
                $response_code = wp_remote_retrieve_response_code($responseurlgetdns);
                $response_data1 = json_decode($response_body1, true);

                // // Kiểm tra và lấy giá trị id
                // echo '<pre>';
                // print_r($response_data1['result']);
                // // print_r( $response_data1['result'][0]['zone_id']);
                // echo '</pre>';
                // exit;
                $dns_records = $response_data1['result'];
                // echo '<pre>';
                // // print_r($dns_records['result']);
                // print_r($dns_records);
                // echo '</pre>';
                // exit;
                if (empty($dns_records)) {
                    $url = "https://api.cloudflare.com/client/v4/zones/$id/dns_records";
                    $body = json_encode(array(
                        'type' => 'A',
                        'name' => $domain,
                        'content' =>  '45.77.98.179',
                        'proxied' => true,
                        "ttl" => 1
                    ));
                    // JSON body
                    $body1 = json_encode(array(
                        'type' => 'A',
                        'name' => 'admin',
                        'content' =>  $ip_server,
                        'proxied' => false,
                        "ttl" => 0
                    ));
                    // Gửi yêu cầu POST
                    $response = wp_remote_post($url, array(
                        'body' => $body,
                        'headers' => $headers
                    ));
                    // Gửi yêu cầu POST
                    $response1 = wp_remote_post($url, array(
                        'body' => $body1,
                        'headers' => $headers
                    ));
                    $response_body = wp_remote_retrieve_body($response);
                    $response_body = wp_remote_retrieve_body($response1);
                    echo 'Response Body:<pre>';
                    print_r(json_decode($response_body, true));
                    echo '</pre>';
                    echo '<hr>';
                } else {
                    // Function to delete a DNS record by ID
                    foreach ($dns_records as $record) {
                        $record_id = $record['id'];
                        $delete_url = "https://api.cloudflare.com/client/v4/zones/$id/dns_records/$record_id";
                        $delete_response = wp_remote_request($delete_url, array(
                            'method'  => 'DELETE',
                            'headers' => $headers
                        ));

                        if (is_wp_error($delete_response)) {
                            error_log('Failed to delete DNS record ID ' . $record_id . ': ' . $delete_response->get_error_message());
                        } else {
                            $delete_body = wp_remote_retrieve_body($delete_response);
                            $delete_data = json_decode($delete_body, true);
                            error_log('Deleted record ID: ' . $record_id . ', Response: ' . print_r($delete_data, true));

                            $url = "https://api.cloudflare.com/client/v4/zones/$id/dns_records";
                            $body = json_encode(array(
                                'type' => 'A',
                                'name' => $domain,
                                'content' =>  '45.77.98.179',
                                'proxied' => true,
                                "ttl" => 1
                            ));
                            // JSON body
                            $body1 = json_encode(array(
                                'type' => 'A',
                                'name' => 'admin',
                                'content' =>  $ip_server,
                                'proxied' => false,
                                "ttl" => 0
                            ));
                            // Gửi yêu cầu POST
                            $response = wp_remote_post($url, array(
                                'body' => $body,
                                'headers' => $headers
                            ));
                            // Gửi yêu cầu POST
                            $response1 = wp_remote_post($url, array(
                                'body' => $body1,
                                'headers' => $headers
                            ));
                            $response_body = wp_remote_retrieve_body($response);
                            $response_body = wp_remote_retrieve_body($response1);
                            echo 'Response Body:<pre>';
                            print_r(json_decode($response_body, true));
                            echo '</pre>';
                            echo '<hr>';
                        }
                    }
                }



                $urlChangeSSL = 'https://api.cloudflare.com/client/v4/zones/' . $id . '/settings/ssl';

                $bodyChangeSSL = json_encode(array(
                    'value' => 'full' // Giá trị bạn muốn cập nhật
                ));
                // Gửi yêu cầu PATCH
                $response = wp_remote_request($urlChangeSSL, array(
                    'method'    => 'PATCH',
                    'body'      => $bodyChangeSSL,
                    'headers'   => $headers
                ));
                if (is_wp_error($response)) {
                    $error_message = $response->get_error_message();
                    echo "Something went wrong: $error_message";
                }
                // Kiểm tra phản hồi
                if (is_wp_error($response)) {
                    $error_message = $response->get_error_message();
                    echo "Something went wrong: $error_message";
                } else {
                    // Lấy nội dung của phản hồi
                    $response_body = wp_remote_retrieve_body($response);
                    $response_code = wp_remote_retrieve_response_code($response);

                    // Giải mã JSON của phản hồi
                    $response_data = json_decode($response_body, true);

                    // In ra phản hồi để kiểm tra
                    echo 'Response Code: ' . $response_code . '<br>';
                    echo 'Response Body:<pre>';
                    print_r($response_data);
                    echo '</pre>';
                    $domain = trim($domain);
                    $parts = explode(".", $domain);
                    $domainSLD =  $parts[0];
                    $domainTLD = $parts[1];
                    if ($check_bool_TMVPCS == null) {
                        $api_set_domain = 'https://api.namecheap.com/xml.response?ApiUser=' . $apiUser . '&ApiKey=' . $apiKey . ' &UserName=' . $userName . '&Command=namecheap.domains.dns.setCustom&ClientIp=1.53.8.82&TLD=' . $domainTLD . '&NameServers=alaric.ns.cloudflare.com,tessa.ns.cloudflare.com&SLD=' . $domainSLD . '';
                    } else {
                        $api_set_domain = 'https://api.namecheap.com/xml.response?ApiUser=' . $apiUser . '&ApiKey=' . $apiKey . ' &UserName=' . $userName . '&Command=namecheap.domains.dns.setCustom&ClientIp=1.53.8.82&TLD=' . $domainTLD . '&NameServers=elijah.ns.cloudflare.com,harlee.ns.cloudflare.com&SLD=' . $domainSLD . '';
                    }
                    // $api_set_domain = 'https://api.namecheap.com/xml.response?ApiUser=' . $apiUser . '&ApiKey=' . $apiKey . ' &UserName=' . $userName . '&Command=namecheap.domains.dns.setCustom&ClientIp=1.53.8.82&TLD=' . $domainTLD . '&NameServers=alaric.ns.cloudflare.com,tessa.ns.cloudflare.com&SLD=' . $domainSLD . '';
                    $response_set_domain = curl_get($api_set_domain);
                    $xml = simplexml_load_string($response_set_domain);
                    if ($xml && $xml->CommandResponse && $xml->CommandResponse->DomainDNSSetCustomResult && $xml->attributes()->Status == "OK") {
                        $domainNameUpdated = $xml->CommandResponse->DomainDNSSetCustomResult->attributes()->Updated;
                        echo $xml->CommandResponse->DomainDNSSetCustomResult->attributes()->Domain . ' đã được cập nhật trang thái ' . $domainNameUpdated . '<br>';
                    } else {
                        echo ((string) $xml->Errors->Error) . '<br/>';
                        echo '<pre>';
                        print_r($xml);
                        echo '</pre>';
                    }
                }
            } else {
                // echo 'ID không tồn tại trong phản hồi.<br>';
                foreach ($response_data['errors'] as $error) {
                    echo 'Error Code: ' . $error['code'] . '<br>';
                    echo 'Error Message: ' . $error['message'] . '<br>';
                }
            }
        }
    }
    exit;
}
// buy domain 
add_action('wp_ajax_buydomain', 'buydomain_function');
add_action('wp_ajax_nopriv_buydomain', 'buydomain_function');

function curl_get($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

function curl_post($url, $data)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

function buydomain_function()
{
    $check_bool_TMVPCS = $_POST['checkTMVPCS'];
    $check_bool_sitenewupdate = $_POST['checkSiteNewNode'];
    if ($check_bool_TMVPCS == null) {
        $apiUser = "cuonglv";
        $apiKey  = "a880dd0536b14e218e2ac63120425dea";
        $userName = "cuonglv";
        $clientIp = "118.71.35.244";
        $api_get_total_money = curl_get('https://api.namecheap.com/xml.response?ApiUser=' . $apiUser . '&ApiKey=' . $apiKey . '&UserName=' . $userName . '&Command=namecheap.users.getBalances&ClientIp=' . $clientIp . '');

        // echo $apiKey.'<br>';
        // echo $apiUser.'<br>';
        // echo $userName.'<br>';
        // echo $clientIp.'<br>';
    } else {
        $apiUser = "hjyulxttadjanie";
        $apiKey  = "b71a26e052fb45b498ea98dd47dee62f";
        $userName = "hjyulxttadjanie";
        $clientIp = "118.71.35.244";
        $api_get_total_money = curl_get('https://api.namecheap.com/xml.response?ApiUser=' . $apiUser . '&ApiKey=' . $apiKey . '&UserName=' . $userName . '&Command=namecheap.users.getBalances&ClientIp=' . $clientIp . '');
    }
    $domains = $_POST['domainPost'];
    $ip_server = $_POST['optionServer'];

    $wordpress_domains =  array_filter(array_map('trim', explode("\n", $domains)), 'strlen');
    // count domain 
    $total_domain = count($wordpress_domains);
    $price_domain_total = $total_domain * 11.16;
    // end count domain 

    $xml = simplexml_load_string($api_get_total_money);
    if ($xml && $xml->CommandResponse && $xml->CommandResponse->UserGetBalancesResult->attributes()->AvailableBalance <= $price_domain_total) {
        echo '<img src="https://media.tenor.com/B9nrfzM9mEAAAAAM/discord.gif" >';
        die();
    } else {
        foreach ($wordpress_domains as $domain) {
            $domain = trim($domain);
            namecheap_api($domain, $apiUser, $apiKey, $userName, $ip_server, $clientIp, $check_bool_TMVPCS, $check_bool_sitenewupdate);

            // echo '<a href="' . $domain . ' ">' . $domain . '</a></br>';
        }
        exit;
    }
}
function namecheap_api($domain, $apiUser, $apiKey, $userName, $ip_server, $clientIp, $check_bool_TMVPCS, $check_bool_sitenewupdate)
{
    $api_url_check_domain = 'https://api.namecheap.com/xml.response?ApiUser=' . $apiUser . '&ApiKey=' . $apiKey . ' &UserName=' . $userName . '&Command=namecheap.domains.check&ClientIp=' . $clientIp . '&DomainList=' . $domain;

    $response_check_domain = curl_get($api_url_check_domain);
    // Xử lý kết quả trả về 
    $xml = simplexml_load_string($response_check_domain);
    if ($xml && $xml->CommandResponse && $xml->CommandResponse->DomainCheckResult && $xml->attributes()->Status == "OK") {
        $domainName = $xml->CommandResponse->DomainCheckResult->attributes()->Domain;

        $parts = explode(".", $domainName);
        // Lấy phần domain (trong trường hợp này là "dynamicdealsusa")
        // https://dashingdiscountsusa.com/
        $domainSLD =  $parts[0];
        // $domainSLD =  'milkshakeonlineshop';
        // Lấy phần sau dấu chấm (trong trường hợp này là "com")
        $domainTLD = $parts[1];
        // $domainTLD = 'com';

        $check_bool_domain = $xml->CommandResponse->DomainCheckResult->attributes()->Available;
        if ($check_bool_domain == 'true') {
            //buydomain
            $result_buy_domain = api_create_domain_namecheap($domainName, $apiUser, $apiKey, $userName, $ip_server, $clientIp);
            // $result_buy_domain = 'true';
            //endbuydomain

            if ($result_buy_domain == 'true') {
                //custom DNS advanced
                if ($check_bool_TMVPCS == null) {
                    $api_post_url_customHost = 'https://api.namecheap.com/xml.response?ApiUser=' . $apiUser . '&ApiKey=' . $apiKey . ' &UserName=' . $userName . '&Command=namecheap.domains.dns.setHosts&ClientIp=' . $clientIp . '&SLD=' . $domainSLD . '&TLD=' . $domainTLD . '&HostName1=@&RecordType1=A&Address1=' . $ip_server . '&TTL1=1799&HostName2=www&RecordType2=A&Address2=' . $ip_server . '&TTL2=1799&HostName3=@&RecordType3=TXT&Address3=v=spf1 include:_spf.smtp.com ~all&TTL3=1799&HostName4=_dmarc&RecordType4=TXT&Address4=v=DMARC1; p=reject; rua=mailto:dmarc@' . $domainSLD . '.' . $domainTLD . '&TTL4=1799&HostName5=smtpcomcustomers._domainkey&RecordType5=TXT&Address5=k=rsa; p=MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAvBIEZY/5RECxx0yiRlbT+ah60XnTW3NRxyMCoZMqnkcQSTkuTSv6hLCHG87h/HZ/XrPlowuLrqCZx74LK6KhILrOTfVlAt4PxT99TyRcLgCc315P5D/wzX03ikmCU9ZZ+OebNW45CWaVf96fZ93QkKPmlabF5ae3Dv74D0mBjTVefbX30fWY3zne6jErkxhRbSRPuEI88s8gf9BZOcnVpk0snO+x5TqPBolcm9aztjNRE6jrf9izxCzw5fXSoZmqBTIIqYva1kes413Nh081Fkr0mg8mxJ+NfsFYNyOZ4kg8XjJCHZyAIPkzvQUSowXf2y2aV5d/KzG15OHZFRKxuQIDAQAB&TTL5=1799';
                } else {
                    $api_post_url_customHost = 'https://api.namecheap.com/xml.response?ApiUser=' . $apiUser . '&ApiKey=' . $apiKey . ' &UserName=' . $userName . '&Command=namecheap.domains.dns.setHosts&ClientIp=' . $clientIp . '&SLD=' . $domainSLD . '&TLD=' . $domainTLD . '&HostName1=*&RecordType1=A&Address1=' . $ip_server . '&TTL1=1799&HostName2=@&RecordType2=A&Address2=' . $ip_server . '&TTL2=1799';
                }
                $response_setDNS = curl_get($api_post_url_customHost);
                $xml_customDNS = simplexml_load_string($response_setDNS);
                //end custom DNS advanced
                echo $xml_customDNS->CommandResponse->DomainDNSSetHostsResult;
                //ghi file script zoc terminal 
                if ($xml_customDNS->CommandResponse->DomainDNSSetHostsResult->attributes()->IsSuccess == 'true') {
                    // echo '<span style="font-weight:600;">Nhấp vào link để tới website của bạn: </span>';
                    api_cloudflare_namecheap($domain, $apiUser, $apiKey, $userName, $ip_server, $clientIp, $check_bool_TMVPCS, $check_bool_sitenewupdate);
                    // thực thi shell_exec 
                    //dev-null để loại bỏ mọi đầu ra 

                    // shell_exec(get_template_directory() . '/myScript/myscript.expect 2&1');
                    $output = shell_exec(get_template_directory().'/myScript/myscript.expect 2&1');
                    echo '<pre>'.$output.'</pre>';



                    // shell_exec(get_template_directory() . '/myScript/myscript.expect > /dev/null & 2&1');

                    //in ra màn hình 
                    echo '<div style="margin: 0 auto;
                    width: 419px;
                    display: flex;
                    justify-content: flex-start;
                    align-items: center;
                    gap: 10px;
                    border-radius: 10px;
                    background: #f3f3f3;">
                    <a style="color: blue;padding: 16px;width: 100%;text-align: left;" href="https://' . $domainName . '" target="_blank">
                    <svg data-v-42c8dc1c="" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" width="40" height="40" viewBox="0 0 48 48" class="iconify iconify--flat-color-icons"><path fill="#CFD8DC" d="M5 19h38v19H5z"></path><path fill="#B0BEC5" d="M5 38h38v4H5z"></path><path fill="#455A64" d="M27 24h12v18H27z"></path><path fill="#E3F2FD" d="M9 24h14v11H9z"></path><path fill="#1E88E5" d="M10 25h12v9H10z"></path><path fill="#90A4AE" d="M36.5 33.5c-.3 0-.5.2-.5.5v2c0 .3.2.5.5.5s.5-.2.5-.5v-2c0-.3-.2-.5-.5-.5"></path><g fill="#558B2F"><circle cx="24" cy="19" r="3"></circle><circle cx="36" cy="19" r="3"></circle><circle cx="12" cy="19" r="3"></circle></g><path fill="#7CB342" d="M40 6H8c-1.1 0-2 .9-2 2v3h36V8c0-1.1-.9-2-2-2m-19 5h6v8h-6zm16 0h-5l1 8h6zm-26 0h5l-1 8H9z"></path><g fill="#FFA000"><circle cx="30" cy="19" r="3"></circle><path d="M45 19c0 1.7-1.3 3-3 3s-3-1.3-3-3s1.3-3 3-3z"></path><circle cx="18" cy="19" r="3"></circle><path d="M3 19c0 1.7 1.3 3 3 3s3-1.3 3-3s-1.3-3-3-3z"></path></g><path fill="#FFC107" d="M32 11h-5v8h6zm10 0h-5l2 8h6zm-26 0h5v8h-6zM6 11h5l-2 8H3z"></path></svg>
                    https://' . $domainName . '</a></div>';
                } else {
                    echo  'Custom DNS ' . $domain . ' lỗi <br/>';
                }
            } else {
                echo 'Buy domain' . $domain . ' lỗi rồi<br/>';
            }
        } else {
            echo  '<span style="font-weight:600;">Domain ' . $domain . ' đã tồn tại</span><br/>';
        }
    } else {
        echo ((string) $xml->Errors->Error) . '<br/>';
        echo '<span style="font-weight:600;">Không có tên miền nào được tìm thấy hoặc có lỗi xảy ra.</span><br/>';
    }
}
function api_create_domain_namecheap($domain_need_buy, $apiUser, $apiKey, $userName, $ip_server, $clientIp)
{
    // $apiUser = "cuonglv";
    // $apiKey  = "a880dd0536b14e218e2ac63120425dea";
    // $userName = "cuonglv";
    // $clientIp = "222.252.28.26";
    $api_url_buy_domain = 'https://api.namecheap.com/xml.response?ApiUser=' . $apiUser . '&ApiKey=' . $apiKey . '&UserName=' . $userName . '&Command=namecheap.domains.create&ClientIp=' . $clientIp . '&DomainName=' . $domain_need_buy . '&Years=1&AuxBillingFirstName=Luong Viet&AuxBillingLastName=Cuong&AuxBillingAddress1=Ha Noi&AuxBillingStateProvince=HA NOI&AuxBillingPostalCode=100000&AuxBillingCountry=VN&AuxBillingPhone=+1.977314454&AuxBillingEmailAddress=cuonglv@dropify.com.vn&AuxBillingOrganizationName=HN&AuxBillingCity=Ha Noi&TechFirstName=Luong Viet&TechLastName=Cuong&TechAddress1=Ha Noi&TechStateProvince=HA NOI&TechPostalCode=100000&TechCountry=VN&TechPhone=+1.977314454&TechEmailAddress=cuonglv@dropify.com.vn&TechOrganizationName=HN&TechCity=Ha Noi&AdminFirstName=Luong Viet&AdminLastName=Cuong&AdminAddress1=Ha Noi&AdminStateProvince=HA NOI&AdminPostalCode=100000&AdminCountry=VN&AdminPhone=+1.977314454&AdminEmailAddress=cuonglv@dropify.com.vn&AdminOrganizationName=HA NOI&AdminCity=Ha Noi&RegistrantFirstName=Luong Viet&RegistrantLastName=Cuong&RegistrantAddress1=Ha Noi&RegistrantStateProvince=HA NOI&RegistrantPostalCode=100000&RegistrantCountry=VN&RegistrantPhone=+1.977314454&RegistrantEmailAddress=cuonglv@dropify.com.vn&RegistrantOrganizationName=HA NOI&RegistrantCity=Ha Noi';

    $response_buy_domain = curl_get($api_url_buy_domain);
    $xml = simplexml_load_string($response_buy_domain);
    if ($xml && $xml->CommandResponse && $xml->CommandResponse->DomainCreateResult && $xml->attributes()->Status == "OK") {
        $domainName = $xml->CommandResponse->DomainCreateResult->attributes()->Registered;
        $domainChargedAmount = $xml->CommandResponse->DomainCreateResult->attributes()->ChargedAmount;
        echo $domain_need_buy . ' đã mua với số tiền ' . $domainChargedAmount . '<br/>';
    }
    return $domainName;
}

function check_total_dollar_namecheap($apiUser, $apiKey, $userName, $clientIp)
{
    $api_url_check_total_namecheap = 'https://api.namecheap.com/xml.response?ApiUser=' . $apiUser . '&ApiKey=' . $apiKey . '&UserName=' . $userName . '&Command=namecheap.users.getBalances&ClientIp=' . $clientIp . '';
    $response_check_total_namecheap = curl_get($api_url_check_total_namecheap);
    $xml = simplexml_load_string($response_check_total_namecheap);
    if ($xml && $xml->CommandResponse && $xml->CommandResponse->UserGetBalancesResult && $xml->attributes()->Status == "OK") {
        $AvailableBalance = $xml->CommandResponse->UserGetBalancesResult->attributes()->AvailableBalance;
        $CurrencyAccount = $xml->CommandResponse->UserGetBalancesResult->attributes()->Currency;
        return $AvailableBalance . " " . $CurrencyAccount;
    } else {
        return ((string) $xml->Errors->Error) . '<br/>';
    }
    exit;
}


// update option url 
function update_options_in_database()
{
    global $wpdb;

    $url = home_url();

    // Thực hiện câu lệnh SQL để cập nhật option_value cho option_id = 1
    $wpdb->update(
        $wpdb->options,
        array('option_value' => '' . $url . ''),
        array('option_id' => 1)
    );

    // Thực hiện câu lệnh SQL để cập nhật option_value cho option_id = 2
    $wpdb->update(
        $wpdb->options,
        array('option_value' => '' . $url . ''),
        array('option_id' => 2)
    );
}

//function API cloudflare 
function api_cloudflare_namecheap($domain, $apiUser, $apiKey, $userName, $ip_server, $clientIp, $check_bool_TMVPCS, $check_bool_sitenewupdate)
{
    // URL của API
    $url = 'https://api.cloudflare.com/client/v4/zones/';
    if ($check_bool_TMVPCS == null) {
        $apiUser = "cuonglv";
        $apiKey  = "a880dd0536b14e218e2ac63120425dea";
        $userName = "cuonglv";
        $clientIp = "118.71.35.244";
        // JSON body
        $body = json_encode(array(
            'name' => '' . $domain . '',
            'account' => array('id' => 'a8781ecabf472c0756e7c35343a0f926'),
            'jump_start' => true,
            'type' => 'full'
        ));
        // Token
        $token = 'swiA7Nlr_xe31JfywKBtBkJKAVKetPplhQQMrGzA'; // Thay 'your_token_here' bằng token thật của bạn
    } else {
        $apiUser = "hjyulxttadjanie";
        $apiKey  = "b71a26e052fb45b498ea98dd47dee62f";
        $userName = "hjyulxttadjanie";
        $clientIp = "118.71.35.244";
        // JSON body
        $body = json_encode(array(
            'name' => '' . $domain . '',
            'account' => array('id' => '2b6bef081f0de57c085184b8cfd6e15b'),
            'jump_start' => true,
            'type' => 'full'
        ));
        // Token
        $token = 'WG-H1uWb1s49QLNI7KB24S1thhZuQUSig-BwX3Fz'; // Thay 'your_token_here' bằng token thật của bạn
    }
    // // JSON body
    // $body = json_encode(array(
    //     'name' => '' . $domain . '',
    //     'account' => array('id' => 'a8781ecabf472c0756e7c35343a0f926'),
    //     'jump_start' => true,
    //     'type' => 'full'
    // ));

    // // Token
    // $token = 'swiA7Nlr_xe31JfywKBtBkJKAVKetPplhQQMrGzA'; // Thay 'your_token_here' bằng token thật của bạn

    // Headers
    $headers = array(
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    );

    // Gửi yêu cầu POST
    $response = wp_remote_post($url, array(
        'body' => $body,
        'headers' => $headers
    ));


    // Kiểm tra phản hồi
    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo "Something went wrong: $error_message";
    } else {
        // Lấy nội dung của phản hồi
        $response_body = wp_remote_retrieve_body($response);
        $response_code = wp_remote_retrieve_response_code($response);

        // echo 'Response Code: ' . $response_code . '<br>';
        // echo 'Response Body:<pre>';
        // print_r(json_decode($response_body, true));
        // echo '</pre>';
        // Giải mã JSON của phản hồi
        $response_data = json_decode($response_body, true);

        // Kiểm tra và lấy giá trị id
        if (isset($response_data['result']['id'])) {
            $id = $response_data['result']['id'];
            echo 'ID: ' . $id . '<br>';
            // URL của API
            $url = 'https://api.cloudflare.com/client/v4/zones/' . $id . '/dns_records';
            if ($check_bool_sitenewupdate == null) {
                // JSON body
                $body = json_encode(array(
                    'type' => 'A',
                    'name' => $domain,
                    'content' =>  $ip_server,
                    'proxied' => true,
                    "ttl" => 1
                ));
                // JSON body
                $body1 = json_encode(array(
                    'type' => 'A',
                    'name' => 'www',
                    'content' =>  $ip_server,
                    'proxied' => true,
                    "ttl" => 1
                ));
                // JSON body
                $body2 = json_encode(array(
                    'type' => 'CNAME',
                    'name' => '*',
                    'content' =>  $domain,
                    'proxied' => true,
                    "ttl" => 1
                ));
                // Gửi yêu cầu POST
                $response = wp_remote_post($url, array(
                    'body' => $body,
                    'headers' => $headers
                ));
                // Gửi yêu cầu POST
                $response1 = wp_remote_post($url, array(
                    'body' => $body1,
                    'headers' => $headers
                ));
                // Gửi yêu cầu POST
                $response2 = wp_remote_post($url, array(
                    'body' => $body2,
                    'headers' => $headers
                ));
                $response_body = wp_remote_retrieve_body($response);
                $response_body = wp_remote_retrieve_body($response1);
                $response_body = wp_remote_retrieve_body($response2);
                echo 'Response Body:<pre>';
                print_r(json_decode($response_body, true));
                echo '</pre>';
                echo '<hr>';
            } else {
                $body = json_encode(array(
                    'type' => 'A',
                    'name' => $domain,
                    'content' =>  '45.77.98.179',
                    'proxied' => true,
                    "ttl" => 1
                ));
                // JSON body
                $body1 = json_encode(array(
                    'type' => 'A',
                    'name' => 'admin',
                    'content' =>  $ip_server,
                    'proxied' => false,
                    "ttl" => 1
                ));
                // Gửi yêu cầu POST
                $response = wp_remote_post($url, array(
                    'body' => $body,
                    'headers' => $headers
                ));
                // Gửi yêu cầu POST
                $response1 = wp_remote_post($url, array(
                    'body' => $body1,
                    'headers' => $headers
                ));
                $response_body = wp_remote_retrieve_body($response);
                $response_body = wp_remote_retrieve_body($response1);
                echo 'Response Body:<pre>';
                print_r(json_decode($response_body, true));
                echo '</pre>';
                echo '<hr>';
            }


            $url = 'https://api.cloudflare.com/client/v4/zones/' . $id . '/settings/always_use_https';
            $urlChangeSSL = 'https://api.cloudflare.com/client/v4/zones/' . $id . '/settings/ssl';
            // JSON body
            $body = json_encode(array(
                'value' => 'on' // Giá trị bạn muốn cập nhật
            ));
            $bodyChangeSSL = json_encode(array(
                'value' => 'full' // Giá trị bạn muốn cập nhật
            ));
            // Gửi yêu cầu PATCH
            $response = wp_remote_request($url, array(
                'method'    => 'PATCH',
                'body'      => $body,
                'headers'   => $headers
            ));
            // Gửi yêu cầu PATCH
            $response1 = wp_remote_request($urlChangeSSL, array(
                'method'    => 'PATCH',
                'body'      => $bodyChangeSSL,
                'headers'   => $headers
            ));
            if (is_wp_error($response1)) {
                $error_message = $response1->get_error_message();
                echo "Something went wrong: $error_message";
            }
            // Kiểm tra phản hồi
            if (is_wp_error($response)) {
                $error_message = $response->get_error_message();
                echo "Something went wrong: $error_message";
            } else {
                // Lấy nội dung của phản hồi
                $response_body = wp_remote_retrieve_body($response);
                $response_code = wp_remote_retrieve_response_code($response);

                // Giải mã JSON của phản hồi
                $response_data = json_decode($response_body, true);

                // In ra phản hồi để kiểm tra
                echo 'Response Code: ' . $response_code . '<br>';
                echo 'Response Body:<pre>';
                print_r($response_data);
                echo '</pre>';
                $fp = fopen(get_template_directory() . '/myScript/myscript.expect', 'w'); //mở file ở chế độ write-only
                fwrite($fp, '#!/usr/bin/expect' . "\n");
                fwrite($fp, 'set hostname "' . $ip_server . '"' . "\n");
                fwrite($fp, 'set username "root"' . "\n");
                fwrite($fp, 'set password "!N4eFD*tD(B5_yo%"' . "\n");
                fwrite($fp, 'spawn ssh $username@$hostname' . "\n");
                fwrite($fp, 'expect {
                    "*assword:" {
                        send "$password\r"
                    }
                    "*yes/no*" {
                        send "yes\r"
                        exp_continue
                    }
                }' . "\n");
                fwrite($fp, 'expect "$ "' . "\n");
                fwrite($fp, 'send "add_site ' . $domain . '\r"' . "\n");
                fwrite($fp, 'expect "$ "' . "\n");
                fwrite($fp, 'sleep 30' . "\n");
                fwrite($fp, 'expect eof' . "\n");
                fwrite($fp, 'exit' . "\n");

                fclose($fp);
                // // In ra phản hồi để kiểm tra
                // echo 'Response Code: ' . $response_code . '<br>';
                // echo 'Response Body:<pre>';
                // print_r($response_data);
                // echo '</pre>';
                // $fp = fopen(get_template_directory() . '/myScript/myscript.expect', 'w'); //mở file ở chế độ write-only
                // fwrite($fp, '#!/usr/bin/expect' . "\n");
                // fwrite($fp, 'set hostname "' . $ip_server . '"' . "\n");
                // fwrite($fp, 'set username "root"' . "\n");
                // fwrite($fp, 'set password "Pass2024@"' . "\n");
                // fwrite($fp, 'spawn ssh $username@$hostname' . "\n");
                // fwrite($fp, 'expect {
                //     "*assword:" {
                //         send "$password\r"
                //     }
                //     "*yes/no*" {
                //         send "yes\r"
                //         exp_continue
                //     }
                // }' . "\n");
                // fwrite($fp, 'expect "$ "' . "\n");
                // fwrite($fp, 'send "larvps\r"' . "\n");
                // fwrite($fp, 'expect "$ "' . "\n");
                // fwrite($fp, 'send "1\r"' . "\n");
                // fwrite($fp, 'expect "$ "' . "\n");

                // fwrite($fp, 'sleep 1' . "\n");
                // fwrite($fp, 'send "2\r"' . "\n");
                // fwrite($fp, 'expect "$ "' . "\n");
                // fwrite($fp, 'sleep 1' . "\n");
                // if ($check_bool_sitenewupdate == null) {
                //     fwrite($fp, 'send "' . $domain . '\r"' . "\n");
                // } else {
                //     fwrite($fp, 'send "admin.' . $domain . '\r"' . "\n");
                // }
                // fwrite($fp, 'sleep 1' . "\n");
                // fwrite($fp, 'expect "$ "' . "\n");
                // fwrite($fp, 'send "y\r"' . "\n");
                // fwrite($fp, 'expect "$ "' . "\n");
                // fwrite($fp, 'sleep 1' . "\n");

                // fwrite($fp, 'send "y\r"' . "\n");
                // fwrite($fp, 'expect "$ "' . "\n");

                // fwrite($fp, 'sleep 40' . "\n");
                // fwrite($fp, 'expect eof' . "\n");
                // fwrite($fp, 'exit' . "\n");

                // fclose($fp);


                $domain = trim($domain);
                $parts = explode(".", $domain);
                $domainSLD =  $parts[0];
                $domainTLD = $parts[1];
                if ($check_bool_TMVPCS == null) {
                    $api_set_domain = 'https://api.namecheap.com/xml.response?ApiUser=' . $apiUser . '&ApiKey=' . $apiKey . ' &UserName=' . $userName . '&Command=namecheap.domains.dns.setCustom&ClientIp=1.53.8.82&TLD=' . $domainTLD . '&NameServers=alaric.ns.cloudflare.com,tessa.ns.cloudflare.com&SLD=' . $domainSLD . '';
                } else {
                    $api_set_domain = 'https://api.namecheap.com/xml.response?ApiUser=' . $apiUser . '&ApiKey=' . $apiKey . ' &UserName=' . $userName . '&Command=namecheap.domains.dns.setCustom&ClientIp=1.53.8.82&TLD=' . $domainTLD . '&NameServers=elijah.ns.cloudflare.com,harlee.ns.cloudflare.com&SLD=' . $domainSLD . '';
                }
                // $api_set_domain = 'https://api.namecheap.com/xml.response?ApiUser=' . $apiUser . '&ApiKey=' . $apiKey . ' &UserName=' . $userName . '&Command=namecheap.domains.dns.setCustom&ClientIp=1.53.8.82&TLD=' . $domainTLD . '&NameServers=alaric.ns.cloudflare.com,tessa.ns.cloudflare.com&SLD=' . $domainSLD . '';
                $response_set_domain = curl_get($api_set_domain);
                $xml = simplexml_load_string($response_set_domain);
                if ($xml && $xml->CommandResponse && $xml->CommandResponse->DomainDNSSetCustomResult && $xml->attributes()->Status == "OK") {
                    $domainNameUpdated = $xml->CommandResponse->DomainDNSSetCustomResult->attributes()->Updated;
                    echo $xml->CommandResponse->DomainDNSSetCustomResult->attributes()->Domain . ' đã được cập nhật trang thái ' . $domainNameUpdated . '<br>';
                } else {
                    echo ((string) $xml->Errors->Error) . '<br/>';
                    echo '<pre>';
                    print_r($xml);
                    echo '</pre>';
                }
            }
        } else {
            // echo 'ID không tồn tại trong phản hồi.<br>';
            foreach ($response_data['errors'] as $error) {
                echo 'Error Code: ' . $error['code'] . '<br>';
                echo 'Error Message: ' . $error['message'] . '<br>';
            }
        }
    }
}
// api_cloudflare_namecheap("cosmolandtechus.com", "113.192.8.160");