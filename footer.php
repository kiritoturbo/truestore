<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
</script>
<script>
    $(document).ready(function() {

        // Mảng chứa các đối tượng màu sắc cho các khoảng thời gian trong ngày
        var backgrounds = [{
                startHour: 0,
                endHour: 6,
                color: "black"
            },
            {
                startHour: 6,
                endHour: 12,
                color: "lightblue"
            },
            {
                startHour: 12,
                endHour: 18,
                color: "lightyellow"
            },
            {
                startHour: 18,
                endHour: 24,
                color: "orange"
            }
        ];

        // Hàm để cập nhật nền dựa trên thời gian hiện tại
        function updateBackground() {
            var now = new Date();
            var hour = now.getHours();


            // Tìm màu sắc tương ứng với thời gian hiện tại
            var bgColor = "white"; // Mặc định
            // for (var i = 0; i < backgrounds.length; i++) {
            //     if (hour >= backgrounds[i].startHour && hour <script backgrounds[i].endHour) {
            //         bgColor = backgrounds[i].color;
            //         break;
            //     }
            // }

            // Cập nhật nền của trang web
            $('body').css('background-color', bgColor);
        }

        // Cập nhật nền mỗi phút
        // setInterval(updateBackground, 72000);

        // Ban đầu, cập nhật nền dựa trên thời gian hiện tại
        // updateBackground();
        // $(".domain_post").keydown(function(event) {
        //     if (event.keyCode == 13) {
        //         event.preventDefault();
        //         checkdomain();
        //     }
        // })
        $('#favcolor').change(function() {
            var selectedColor = $(this).val();
            console.log("Selected color:", selectedColor);
            $('body').css('background-color', selectedColor);
            // $('.custom-card').css('background-color', selectedColor);
        });

        getTotalDollarAccount();

        $(".set-null-textarea").click(function() {
            $(".domain_post").val("");
            $(".domain_post").focus();
        })

        $(".open-tab-brower").click(function() {
            var domainPost = $(".domain_post").val();
            if (domainPost === "") {
                alert("Vui lòng nhập domain.");
                $("#domainInput").focus();
                return;
            }
            // Lấy nội dung của textarea chứa các URL bạn muốn mở
            var urls = $(".domain_post").val().split("\n");

            // Sử dụng $.each() để lặp qua mảng các URL và mở từng URL trong một tab mới
            $.each(urls, function(index, url) {
                if (url.trim() !== "") {
                    // Kiểm tra xem URL đã có "http://" hoặc "https://" chưa
                    if (!/^https?:\/\//i.test(url.trim())) {
                        url = "https://" + url.trim(); // Nếu không có, thêm "https://"
                    }
                    window.open(url, '_blank');
                }
            });
        });

        $("#tm-site-web").on('change', function() {
            console.log($('#tm-site-web:checked').val());
        });
        $("#nodejs-site-web").on('change', function() {
            console.log($('#nodejs-site-web:checked').val());
        });
        $("#domainInput").on('change', function() {
            let domainPost = $(".domain_post").val().split('\n');
            let total = domainPost.length;
            if (domainPost[0] != '') {
                $(".total_domain_input").html("Có " + total + " domain")
            } else {
                $(".total_domain_input").html("")
            }
            // if (total > 14) {
            //     alert("Nhập dưới 15 domain")
            //     return
            // }
        });
        $("#domainGAinput").on('change', function() {
            let domainPost = $("#domainGAinput").val().split('\n');
            let total = domainPost.length;
            if (domainPost[0] != '') {
                $("#left-textare").html("Có " + total + " domain")
            } else {
                $("#left-textare").html("")   
            }
            // if (total > 14) {
            //     alert("Nhập dưới 15 domain")
            //     return
            // }
        });
        $("#gaInput").on('change', function() {
            let domainPost = $("#gaInput").val().split('\n');
            let total = domainPost.length;
            if (domainPost[0] != '') {
                $("#right-textare").html("Có " + total + " GA")
            } else {
                $("#right-textare").html("")
            }
            // if (total > 14) {
            //     alert("Nhập dưới 15 domain")
            //     return
            // }
        });
        $(".call-ajax").click(function() {
            checkdomain();
            // getTotalDollarAccount();

        });
        $(".set-server-namecheap").click(function() {
            var domainPost = $(".domain_post").val();
            if (domainPost === "") {
                alert("Vui lòng nhập domain.");
                $("#domainInput").focus();
                return;
            }
            setCloudflare();
        })
        $(".convert-hosting-custom").click(function() {
            var idhosting = $("#idHostingcustom").val().trim();
            var domainPost = $(".domain_post").val();
        
            if (idhosting === "") {
                alert("Vui lòng nhập id server.");
                $("#idHostingcustom").focus();
                return;
            }
            if (domainPost === "") {
                alert("Vui lòng nhập domain.");
                $(".domain_post").focus();
                return;
            }
            $.ajax({
            // Hàm ajax
            type: "post",
            dataType: "html",
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: {
                // action: "setcloudflare",
                action: "setidhostingdnscloude",
                idhosting: idhosting,
                domainPost: domainPost,
            },
            beforeSend: function() {
                $(".overlay").show();
            },
            success: function(response) {
                console.log(response)
                $(".overlay").hide();
                $(".display-post").html(response);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("The following error occured: " + textStatus, errorThrown);
            },
        });
        })
        $(".set-dns-namecheap").click(function() {
            var checkTMVPCS = $('#tm-site-web:checked').val();
            var checkSiteNewNode = $('#nodejs-site-web:checked').val();
            var domainPost = $(".domain_post").val();
            var selectedValue = $("#idServerHost").find(":selected").val();

            if (domainPost === "") {
                alert("Vui lòng nhập domain.");
                $("#domainInput").focus();
                return;
            }
            $.ajax({
                // Hàm ajax
                type: "post",
                dataType: "html",
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                data: {
                    action: "setdnscloudflare",
                    domainPost: domainPost,
                    checkTMVPCS: checkTMVPCS,
                    checkSiteNewNode: checkSiteNewNode,
                    selectedValue: selectedValue
                },
                beforeSend: function() {
                    $(".overlay").show();
                },
                success: function(response) {
                    console.log(response)
                    $(".overlay").hide();
                    $(".display-post").html(response);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("The following error occured: " + textStatus, errorThrown);
                },
            });
        })
    });

    function getTotalDollarAccount() {
        //start thông tin gửi tới telegram nếu domain thiếu 
        const userNameLogin = '<?php global $current_user;
                                echo $current_user->display_name; ?>'; //get username

        // Telegram bot details
        const botToken = '7443709966:AAGlbg6JVzGK1E7EJC6XGR6XUf4-qyk9RtA'; // Replace with your bot token
        const chatId = '6305052475'; // Replace with your chat ID
        // Function to send a message to Telegram
        const sendTelegramMessage = (message) => {
            $.ajax({
                url: `https://api.telegram.org/bot${botToken}/sendMessage`,
                type: 'post',
                data: {
                    chat_id: chatId,
                    text: message,
                },
                success: function(response) {
                    console.log('Telegram message sent:', response);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Telegram Error:', textStatus, errorThrown);
                }
            });
        };

        //end thông tin gửi tới telegram nếu domain thiếu
        $.ajax({
            type: "post",
            dataType: "html",
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: {
                action: "getTotalDollarAccount",
            },
            // beforeSend: function() {
            //     $(".overlay").show();
            // },
            success: function(response) {
                var data = JSON.parse(response);
                var totalDollar = data.total_dollar_cuonglv;
                var totalDollar_tm = data.total_dollar_tm;
                console.log(totalDollar)
                // $(".overlay").hide();
                $("#item-info-namecheap").html(totalDollar);
                $("#item-info-namecheap_tm").html(totalDollar_tm);
                const message = `Hết tiền`;
                // if (totalDollar > '0 USD') {
                //     sendTelegramMessage(message);
                // }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR, textStatus, errorThrown)
                console.log("The following error occured: " + textStatus, errorThrown);
            },
            complete: function() {
                // Gọi lại hàm sau một khoảng thời gian nhất định (ví dụ: 5 giây)
                setTimeout(getTotalDollarAccount, 300000);
            }
        });
    }

    function setCloudflare() {
        var checkTMVPCS = $('#tm-site-web:checked').val();
        var checkSiteNewNode = $('#nodejs-site-web:checked').val();
        var domainPost = $(".domain_post").val();
        $.ajax({
            // Hàm ajax
            type: "post",
            dataType: "html",
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: {
                action: "setcloudflare",
                domainPost: domainPost,
                checkTMVPCS: checkTMVPCS,
                checkSiteNewNode: checkSiteNewNode,
            },
            beforeSend: function() {
                $(".overlay").show();
            },
            success: function(response) {
                console.log(response)
                $(".overlay").hide();
                $(".display-post").html(response);
                // Phát nhạc trong khoảng 30 giây
                var audio = new Audio(
                    "<?php echo get_template_directory_uri() ?>/assets/image/music.mp3");
                audio.play();
                setTimeout(function() {
                    audio.pause();
                }, 10000); // 30000 milliseconds = 30 seconds
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("The following error occured: " + textStatus, errorThrown);
            },
        });
    }

    function checkdomain() {
        var checkTMVPCS = $('#tm-site-web:checked').val();
        var checkSiteNewNode = $('#nodejs-site-web:checked').val();
        var domainPost = $(".domain_post").val();
        var selectedValue = $("#idServerHost").find(":selected").val();
        if (domainPost === "") {
            alert("Vui lòng nhập domain.");
            $("#domainInput").focus();
            return;
        }
        // Bắt sự kiện khi giá trị của phần tử select thay đổi
        // document.getElementById("idServerHost").addEventListener("change", function() {
        //     // Lấy giá trị mới được chọn
        //     var selectedValue = this.value;
        // });
        // var isValidDomain = isValidDomainName(domainPost);
        // if (isValidDomain) {
        //     // alert("Domain hợp lệ: " + domainPost);
        // } else {
        //     alert("Domain không hợp lệ.");
        //     $("#domainInput").focus();
        //     return;
        // }
        $.ajax({
            // Hàm ajax
            type: "post",
            dataType: "html",
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: {
                action: "buydomain",
                domainPost: domainPost,
                optionServer: selectedValue,
                checkTMVPCS: checkTMVPCS,
                checkSiteNewNode: checkSiteNewNode
            },
            beforeSend: function() {
                $(".overlay").show();
            },
            success: function(response) {
                console.log(response)
                $(".overlay").hide();
                $(".display-post").html(response);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("The following error occured: " + textStatus, errorThrown);
            },
        });
    }

    function isValidDomainName(domain) {
        // Thực hiện các kiểm tra hợp lệ tùy thuộc vào yêu cầu của bạn
        // Ví dụ đơn giản: kiểm tra xem domain có chứa ký tự đặc biệt hay không
        var pattern = /^[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.[a-z]{2,}$/;
        return pattern.test(domain);
    }
</script>
</body>

</html>