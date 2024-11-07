$(document).ready(function () {
  console.log(1);
  var config = {
    apiKey: "AIzaSyAqVca3OVfQ9ClXCrcuNKqU3Afj-p_0ntc",
    authDomain: "king-fruit-slot.firebaseapp.com",
    databaseURL: "https://king-fruit-slot.firebaseio.com",
    projectId: "king-fruit-slot",
    storageBucket: "king-fruit-slot.appspot.com",
    messagingSenderId: "883685425114",
    appId: "1:883685425114:web:cf2524285992e54a4b0ae3",
    measurementId: "G-PKPH1S7TXS",
  };
  console.log(myAjax.ajaxurl);
  const app = firebase.initializeApp(config);
  const appDb = app.database().ref();

  $(document).on("click", ".pixel_add_default_button", function () {
    var id = $(this).data("id");
    var pixelStr = document.getElementById("pixel_add_default_value").value;
    var pxs = pixelStr.split("|");
    var dataSave = "";

    for (var i = 0; i < pxs.length; i++) {
      dataSave += pxs[i].trim();

      if (i != pxs.length - 1) {
        dataSave += "DHV";
      }
    }

    id = String(id);

    if (id.length > 3) {
      dataSave += "DHV" + id;
    }

    appDb.child("PXTRUE").child("ALL").set(dataSave);
    window.location.reload();
  });

  $(document).on("click", ".pixel_remove_default_button", function () {
    appDb.child("PXTRUE").child("ALL").remove();
    window.location.reload();
  });

  $(document).on("click", ".remove_pixel_default", function () {
    var id = $(this).data("id");
    id = id.split("DROPIFY");

    var pixelStr = id[0];
    var index = id[1];

    var dataSave = "";
    var pxs = pixelStr.split("DHV");
    var lastIndex = 0;

    if (index == pxs.length - 1) {
      lastIndex = 1;
    }

    for (var i = 0; i < pxs.length; i++) {
      if (i == index) continue;
      dataSave += pxs[i];

      if (i != pxs.length - 1) {
        if (lastIndex == 1) {
          if (i + 1 < index) {
            dataSave += "DHV";
          }
        } else {
          dataSave += "DHV";
        }
      }
    }

    if (dataSave.length > 3) {
      appDb.child("PXTRUE").child("ALL").set(dataSave);
    } else {
      appDb.child("PXTRUE").child("ALL").remove();
    }

    window.location.reload();
  });

  $(document).on("click", ".pixel_add_store_button", function () {
    var storeStr = document.getElementById("store_add_default_value").value;
    var pixelStr = document.getElementById("pixel_add_default_value").value;
    var pxs = pixelStr.split("|");
    var dataSave = "";

    for (var i = 0; i < pxs.length; i++) {
      dataSave += pxs[i].trim();

      if (i != pxs.length - 1) {
        dataSave += "|";
      }
    }

    storeStr = storeStr.replace("https://", "");
    storeStr = storeStr.replace("www.", "");
    storeStr = storeStr.replaceAll("/", "");
    storeStr = storeStr.replaceAll(".", "");
    var storeArr = storeStr.trim();

    if (storeArr.length > 0) {
      appDb.child("PXTRUE2").child(storeArr).child("pixel_ids").set(dataSave);
    }

    window.location.reload();
  });

  $(document).on("click", ".remove_pixel_store", function () {
    var id = $(this).data("id");
    var storeName = String(id);
    appDb.child("PXTRUE2").child(storeName).child("pixel_ids").set("");
    window.location.reload();
  });

  $(document).on("blur", "#store_value", function () {
    var storeStr = document.getElementById("store_value").value;
    storeStr = storeStr.replace("https://", "");
    storeStr = storeStr.replace("www.", "");
    storeStr = storeStr.replaceAll("/", "");
    storeStr = storeStr.replaceAll(".", "");

    var storeFinal = storeStr.trim();

    if (storeFinal.length > 0) {
      var pixelNodes = appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("pixel_ids");

      pixelNodes.once("value", function (snapshot) {
        var pixelStr = snapshot.val();

        if (typeof pixelStr != "undefined" && pixelStr) {
          $("#pixel_value").val(pixelStr);
        } else {
          $("#pixel_value").val("");
        }
      });

      var gaNodes = appDb.child("PXTRUE2").child(storeFinal).child("ga_ids");

      gaNodes.once("value", function (snapshot) {
        var gaStr = snapshot.val();

        if (typeof gaStr != "undefined" && gaStr) {
          $("#ga_value").val(gaStr);
        } else {
          $("#ga_value").val("");
        }
      });
    }
  });

  $(document).on("click", ".clear_cache_button", function () {
    var storeStr = document.getElementById("store_value").value;
    storeStr = storeStr.replace("https://", "");
    storeStr = storeStr.replace("www.", "");
    storeStr = storeStr.replaceAll("/", "");
    var domainStr = "http://207.246.121.223:3006/clear-cache?tag=" + storeStr;

    var storeFinal = storeStr.trim();

    if (storeFinal.length > 0) {
      $.ajax({
        url: myAjax.ajaxurl,
        type: "POST",
        data: {
          store: domainStr,
        },
        success: function (data) {
          swal(data, {
            icon: "success",
          });
        },
        error: function (e) {
          swal("Lỗi", {
            icon: "error",
          });
        },
      });
    }
  });

  $(document).on("click", ".revalid_product_button", function () {
    var storeStr = document.getElementById("store_value").value;
    storeStr = storeStr.replace("https://", "");
    storeStr = storeStr.replace("www.", "");
    storeStr = storeStr.replaceAll("/", "");
    var domainStr = "https://" + storeStr + "/api/revalidate?tag=product";

    var storeFinal = storeStr.trim();

    if (storeFinal.length > 0) {
      $.ajax({
        url: myAjax.ajaxurl,
        type: "POST",
        data: {
          store: domainStr,
        },
        success: function (data) {
          swal(data, {
            icon: "success",
          });
        },
        error: function (e) {
          swal("Lỗi", {
            icon: "error",
          });
        },
      });
    }
  });

  $(document).on("click", ".revalid_all_button", function () {
    var storeStr = document.getElementById("store_value").value;
    storeStr = storeStr.replace("https://", "");
    storeStr = storeStr.replace("www.", "");
    storeStr = storeStr.replaceAll("/", "");
    var domainStr = "https://" + storeStr + "/api/revalidate?tag=all";

    var storeFinal = storeStr.trim();

    if (storeFinal.length > 0) {
      $.ajax({
        url: myAjax.ajaxurl,
        type: "POST",
        data: {
          store: domainStr,
        },
        success: function (data) {
          swal(data, {
            icon: "success",
          });
        },
        error: function (e) {
          swal("Lỗi", {
            icon: "error",
          });
        },
      });
    }
  });
  $(document).on("click", ".ga_update_002_button", function () {
    var domainGAinput = $("#domainGAinput").val();
    var gaInput = $("#gaInput").val();
    var imgUrl = document.getElementById("imgproxy_url_value").value;
    var pixelStr = document.getElementById("pixel_value").value;

    // Chia các domain và GA thành mảng
    var domains = domainGAinput
      .split("\n")
      .map(function (item) {
        return item.trim();
      })
      .filter(Boolean); // Loại bỏ các dòng trống

    var gaIDs = gaInput
      .split("\n")
      .map(function (item) {
        return item.trim();
      })
      .filter(Boolean); // Loại bỏ các dòng trống

    for (var i = 0; i < domains.length; i++) {
      var storeStr = domains[i];
      var gaStr = gaIDs[i];

      storeStr = storeStr.replace("https://", "");
      storeStr = storeStr.replace("www.", "");
      storeStr = storeStr.replaceAll("/", "");
      var domainStr = "https://" + storeStr + "/api/revalidate?tag=all";
      var apiStr = "https://admin." + storeStr + "/wp-json/wc";
      storeStr = storeStr.replaceAll(".", "");

      var storeFinal = storeStr.trim();
      if (storeFinal.length > 0) {
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("company")
          .set("HAALO ECOMMERCE PTE. LTD");
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("address")
          .set(
            "3 Coleman Street #03-24, Peninsula Shopping Complex, 179804, Singapore"
          );
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("email")
          .set("support@helpingcenter.network");
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("paypal_client_id")
          .set(
            "AYik9q9KjVgRJNhYWSrGdC9GYG1cmdaKFCQ6NwMimVUu6hB-QCI58j1gd9RYPm8E4xTvYmlgrFZIRfe2"
          );

        appDb.child("PXTRUE2").child(storeFinal).child("wp_api").set(apiStr);
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("imgproxy_url")
          .set(imgUrl); // Bạn cần đặt giá trị imgUrl ở đây
        appDb.child("PXTRUE2").child(storeFinal).child("phone").set("");
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("pixel_ids")
          .set(pixelStr); // Bạn cần đặt giá trị pixelStr ở đây
        appDb.child("PXTRUE2").child(storeFinal).child("ga_ids").set(gaStr);
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("wp_auth")
          .set(
            "ck_9eb7bb32ef2d39df2931d106ecb5bfbcc97dce30:cs_bb58f4304c9d3f8ed9b109b9ed5eaf3196565b84"
          );

        $.ajax({
          url: myAjax.ajaxurl,
          type: "POST",
          data: {
            action: "getOrderSite",
            store: domainStr,
          },
          success: function (data) {
            swal(data, {
              icon: "success",
            });
          },
          error: function (e) {
            swal("Lỗi", {
              icon: "error",
            });
          },
        });
      }
    }
  });
  $(document).on("click", ".ga_update_003_button", function () {
    var domainGAinput = $("#domainGAinput").val();
    var gaInput = $("#gaInput").val();

    var imgUrl = document.getElementById("imgproxy_url_value").value;
    var pixelStr = document.getElementById("pixel_value").value;

    // Chia các domain và GA thành mảng
    var domains = domainGAinput
      .split("\n")
      .map(function (item) {
        return item.trim();
      })
      .filter(Boolean); // Loại bỏ các dòng trống

    var gaIDs = gaInput
      .split("\n")
      .map(function (item) {
        return item.trim();
      })
      .filter(Boolean); // Loại bỏ các dòng trống

    for (var i = 0; i < domains.length; i++) {
      var storeStr = domains[i];
      var gaStr = gaIDs[i];

      storeStr = storeStr.replace("https://", "");
      storeStr = storeStr.replace("www.", "");
      storeStr = storeStr.replaceAll("/", "");
      var domainStr = "https://" + storeStr + "/api/revalidate?tag=all";
      var apiStr = "https://admin." + storeStr + "/wp-json/wc";
      storeStr = storeStr.replaceAll(".", "");

      var storeFinal = storeStr.trim();
      if (storeFinal.length > 0) {
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("company")
          .set("Crpt Puppies Hub PTE. LTD");
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("address")
          .set(
            "3 Coleman Street #03-24, Peninsula Shopping Complex, 179804, Singapore"
          );
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("email")
          .set("support@cs-support.team");
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("paypal_client_id")
          .set(
            "Ae0uICwuDcUApxG_3ktMj7MOp_HY-lZjxVX4cXT8T09LWImgaE_SRJn44xvYg-MANY3ZNkRmRpRpdPVj"
          );

        appDb.child("PXTRUE2").child(storeFinal).child("wp_api").set(apiStr);
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("imgproxy_url")
          .set(imgUrl);
        appDb.child("PXTRUE2").child(storeFinal).child("phone").set("");
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("pixel_ids")
          .set(pixelStr);
        appDb.child("PXTRUE2").child(storeFinal).child("ga_ids").set(gaStr);
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("wp_auth")
          .set(
            "ck_9eb7bb32ef2d39df2931d106ecb5bfbcc97dce30:cs_bb58f4304c9d3f8ed9b109b9ed5eaf3196565b84"
          );
        $.ajax({
          url: myAjax.ajaxurl,
          type: "POST",
          data: {
            action: "getOrderSite",
            store: domainStr,
          },
          success: function (data) {
            swal(data, {
              icon: "success",
            });
          },
          error: function (e) {
            swal("Lỗi", {
              icon: "error",
            });
          },
        });
      }
    }
  });
  $(document).on("click", ".ga_update_222_button", function () {
    var domainGAinput = $("#domainGAinput").val();
    var gaInput = $("#gaInput").val();
    var imgUrl = document.getElementById("imgproxy_url_value").value;
    var pixelStr = document.getElementById("pixel_value").value;
    // Chia các domain và GA thành mảng
    var domains = domainGAinput
      .split("\n")
      .map(function (item) {
        return item.trim();
      })
      .filter(Boolean); // Loại bỏ các dòng trống

    var gaIDs = gaInput
      .split("\n")
      .map(function (item) {
        return item.trim();
      })
      .filter(Boolean); // Loại bỏ các dòng trống

    for (var i = 0; i < domains.length; i++) {
      var storeStr = domains[i];
      var gaStr = gaIDs[i];

      storeStr = storeStr.replace("https://", "");
      storeStr = storeStr.replace("www.", "");
      storeStr = storeStr.replaceAll("/", "");
      var domainStr = "https://" + storeStr + "/api/revalidate?tag=all";
      var apiStr = "https://admin." + storeStr + "/wp-json/wc";
      storeStr = storeStr.replaceAll(".", "");

      var storeFinal = storeStr.trim();
      if (storeFinal.length > 0) {
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("company")
          .set("Trust Store Ecommerce Pte. Ltd");
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("address")
          .set("1 Tampines North Drive 1, #06-08, Singapore, 528559");
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("email")
          .set("contact@truststore.support");
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("paypal_client_id")
          .set(
            "AcB1L7iGJitE7DLlaPbovIb3esPIsalbwy10bBzgo3xjVAa2I5optsWtYRXJB_TmV4NVTCRVEmMdvxXJ"
          );

        appDb.child("PXTRUE2").child(storeFinal).child("wp_api").set(apiStr);
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("imgproxy_url")
          .set(imgUrl);
        appDb.child("PXTRUE2").child(storeFinal).child("phone").set("");
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("pixel_ids")
          .set(pixelStr);
        appDb.child("PXTRUE2").child(storeFinal).child("ga_ids").set(gaStr);
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("wp_auth")
          .set(
            "ck_9eb7bb32ef2d39df2931d106ecb5bfbcc97dce30:cs_bb58f4304c9d3f8ed9b109b9ed5eaf3196565b84"
          );

        $.ajax({
          url: myAjax.ajaxurl,
          type: "POST",
          data: {
            action: "getOrderSite",
            store: domainStr,
          },
          success: function (data) {
            swal(data, {
              icon: "success",
            });
          },
          error: function (e) {
            swal("Lỗi", {
              icon: "error",
            });
          },
        });
      }
    }
  });
  $(document).on("click", ".ga_update_666_button", function () {
    var domainGAinput = $("#domainGAinput").val();
    var gaInput = $("#gaInput").val();
    var imgUrl = document.getElementById("imgproxy_url_value").value;
    var pixelStr = document.getElementById("pixel_value").value;
    console.log(domainGAinput);
    // Chia các domain và GA thành mảng
    var domains = domainGAinput
      .split("\n")
      .map(function (item) {
        return item.trim();
      })
      .filter(Boolean); // Loại bỏ các dòng trống

    var gaIDs = gaInput
      .split("\n")
      .map(function (item) {
        return item.trim();
      })
      .filter(Boolean); // Loại bỏ các dòng trống

    for (var i = 0; i < domains.length; i++) {
      var storeStr = domains[i];
      var gaStr = gaIDs[i];

      storeStr = storeStr.replace("https://", "");
      storeStr = storeStr.replace("www.", "");
      storeStr = storeStr.replaceAll("/", "");
      var domainStr = "https://" + storeStr + "/api/revalidate?tag=all";
      var apiStr = "https://admin." + storeStr + "/wp-json/wc";
      storeStr = storeStr.replaceAll(".", "");

      var storeFinal = storeStr.trim();
      if (storeFinal.length > 0) {
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("company")
          .set("StellarBuy Ecommerce Pte. Ltd");
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("address")
          .set("1 Tampines North Drive 1, #06-08, Singapore, 528559");
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("email")
          .set("contact@stellarbuyecommerce.com");
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("paypal_client_id")
          .set(
            "AZOjMz0V8b4VoY9-gz-pWuVfvHFEXbU1z0aD01ROSo-5M8kdFW0CaxB7o1ss-aX8s1mwlPSK_9aEpG_X"
          );

        appDb.child("PXTRUE2").child(storeFinal).child("wp_api").set(apiStr);
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("imgproxy_url")
          .set(imgUrl);
        appDb.child("PXTRUE2").child(storeFinal).child("phone").set("");
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("pixel_ids")
          .set(pixelStr);
        appDb.child("PXTRUE2").child(storeFinal).child("ga_ids").set(gaStr);
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("wp_auth")
          .set(
            "ck_9eb7bb32ef2d39df2931d106ecb5bfbcc97dce30:cs_bb58f4304c9d3f8ed9b109b9ed5eaf3196565b84"
          );

        $.ajax({
          url: myAjax.ajaxurl,
          type: "POST",
          data: {
            action: "getOrderSite",
            store: domainStr,
          },
          success: function (data) {
            swal(data, {
              icon: "success",
            });
          },
          error: function (e) {
            swal("Lỗi", {
              icon: "error",
            });
          },
        });
      }
    }
  });
  $(document).on("click", ".ga_update_888_button", function () {
    var domainGAinput = $("#domainGAinput").val();
    var gaInput = $("#gaInput").val();
    var imgUrl = document.getElementById("imgproxy_url_value").value;
    var pixelStr = document.getElementById("pixel_value").value;
    // Chia các domain và GA thành mảng
    var domains = domainGAinput
      .split("\n")
      .map(function (item) {
        return item.trim();
      })
      .filter(Boolean); // Loại bỏ các dòng trống

    var gaIDs = gaInput
      .split("\n")
      .map(function (item) {
        return item.trim();
      })
      .filter(Boolean); // Loại bỏ các dòng trống

    for (var i = 0; i < domains.length; i++) {
      var storeStr = domains[i];
      var gaStr = gaIDs[i];

      storeStr = storeStr.replace("https://", "");
      storeStr = storeStr.replace("www.", "");
      storeStr = storeStr.replaceAll("/", "");
      var domainStr = "https://" + storeStr + "/api/revalidate?tag=all";
      var apiStr = "https://admin." + storeStr + "/wp-json/wc";
      storeStr = storeStr.replaceAll(".", "");

      var storeFinal = storeStr.trim();
      if (storeFinal.length > 0) {
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("company")
          .set("OasisDeals Ecommerce Pte. Ltd");
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("address")
          .set("1 Tampines North Drive 1, #06-08, Singapore, 528559");
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("email")
          .set("contact@oasisdealsecommerce.com");
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("paypal_client_id")
          .set(
            "AWfQTL9QTDtVJ91Cq1leOMZ549zT0BGh4HbfzLMi8j7bl9VUQx8RrSSrEDS3ZQC_OJpCjeik1xA5bTbV"
          );

        appDb.child("PXTRUE2").child(storeFinal).child("wp_api").set(apiStr);
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("imgproxy_url")
          .set(imgUrl);
        appDb.child("PXTRUE2").child(storeFinal).child("phone").set("");
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("pixel_ids")
          .set(pixelStr);
        appDb.child("PXTRUE2").child(storeFinal).child("ga_ids").set(gaStr);
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("wp_auth")
          .set(
            "ck_9eb7bb32ef2d39df2931d106ecb5bfbcc97dce30:cs_bb58f4304c9d3f8ed9b109b9ed5eaf3196565b84"
          );

        $.ajax({
          url: myAjax.ajaxurl,
          type: "POST",
          data: {
            action: "getOrderSite",
            store: domainStr,
          },
          success: function (data) {
            swal(data, {
              icon: "success",
            });
          },
          error: function (e) {
            swal("Lỗi", {
              icon: "error",
            });
          },
        });
      }
    }
  });
  $(document).on("click", ".ga_update_DTH_button", function () {
    var domainGAinput = $("#domainGAinput").val();
    var gaInput = $("#gaInput").val();
    var imgUrl = document.getElementById("imgproxy_url_value").value;
    var pixelStr = document.getElementById("pixel_value").value;
    // Chia các domain và GA thành mảng
    var domains = domainGAinput
      .split("\n")
      .map(function (item) {
        return item.trim();
      })
      .filter(Boolean); // Loại bỏ các dòng trống

    var gaIDs = gaInput
      .split("\n")
      .map(function (item) {
        return item.trim();
      })
      .filter(Boolean); // Loại bỏ các dòng trống

    for (var i = 0; i < domains.length; i++) {
      var storeStr = domains[i];
      var gaStr = gaIDs[i];

      storeStr = storeStr.replace("https://", "");
      storeStr = storeStr.replace("www.", "");
      storeStr = storeStr.replaceAll("/", "");
      var domainStr = "https://" + storeStr + "/api/revalidate?tag=all";
      var apiStr = "https://admin." + storeStr + "/wp-json/wc";
      storeStr = storeStr.replaceAll(".", "");

      var storeFinal = storeStr.trim();
      if (storeFinal.length > 0) {
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("company")
          .set("DTH Ecommerce Pte. Ltd");
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("address")
          .set("1 Tampines North Drive 1, #06-08 T-Space, 528559, Singapore");
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("email")
          .set("support@dth-support.com");
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("paypal_client_id")
          .set(
            "AWLnAWhSTPY7hufx3EfzdYIXiKttypfnhBQx3X96QkFj1Y_nDuJEZlYG_OI8XYDo-i3QGavUDWMZ8For"
          );

        appDb.child("PXTRUE2").child(storeFinal).child("wp_api").set(apiStr);
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("imgproxy_url")
          .set(imgUrl);
        appDb.child("PXTRUE2").child(storeFinal).child("phone").set("");
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("pixel_ids")
          .set(pixelStr);
        appDb.child("PXTRUE2").child(storeFinal).child("ga_ids").set(gaStr);
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("wp_auth")
          .set(
            "ck_9eb7bb32ef2d39df2931d106ecb5bfbcc97dce30:cs_bb58f4304c9d3f8ed9b109b9ed5eaf3196565b84"
          );

        $.ajax({
          url: myAjax.ajaxurl,
          type: "POST",
          data: {
            action: "getOrderSite",
            store: domainStr,
          },
          success: function (data) {
            swal(data, {
              icon: "success",
            });
          },
          error: function (e) {
            swal("Lỗi", {
              icon: "error",
            });
          },
        });
      }
    }
  });
  $(document).on("click", ".ga_update_NKH_button", function () {
    var domainGAinput = $("#domainGAinput").val();
    var gaInput = $("#gaInput").val();
    var imgUrl = document.getElementById("imgproxy_url_value").value;
    var pixelStr = document.getElementById("pixel_value").value;
    // Chia các domain và GA thành mảng
    var domains = domainGAinput
      .split("\n")
      .map(function (item) {
        return item.trim();
      })
      .filter(Boolean); // Loại bỏ các dòng trống

    var gaIDs = gaInput
      .split("\n")
      .map(function (item) {
        return item.trim();
      })
      .filter(Boolean); // Loại bỏ các dòng trống

    for (var i = 0; i < domains.length; i++) {
      var storeStr = domains[i];
      var gaStr = gaIDs[i];

      storeStr = storeStr.replace("https://", "");
      storeStr = storeStr.replace("www.", "");
      storeStr = storeStr.replaceAll("/", "");
      var domainStr = "https://" + storeStr + "/api/revalidate?tag=all";
      var apiStr = "https://admin." + storeStr + "/wp-json/wc";
      storeStr = storeStr.replaceAll(".", "");

      var storeFinal = storeStr.trim();
      if (storeFinal.length > 0) {
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("company")
          .set("NKH Ecommerce Pte. Ltd");
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("address")
          .set("1 Tampines North Drive 1, #06-08 T-Space, 528559, Singapore");
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("email")
          .set("contact@support-nkh.com");
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("paypal_client_id")
          .set(
            "AWqk1wztQ_JYRRrz-ulzNoVmuE5SeWTISYfE8dY4_W7RwLenBOOUtsP75uUvFWiopOX6bx0TQekHVtoZ"
          );

        appDb.child("PXTRUE2").child(storeFinal).child("wp_api").set(apiStr);
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("imgproxy_url")
          .set(imgUrl);
        appDb.child("PXTRUE2").child(storeFinal).child("phone").set("");
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("pixel_ids")
          .set(pixelStr);
        appDb.child("PXTRUE2").child(storeFinal).child("ga_ids").set(gaStr);
        appDb
          .child("PXTRUE2")
          .child(storeFinal)
          .child("wp_auth")
          .set(
            "ck_9eb7bb32ef2d39df2931d106ecb5bfbcc97dce30:cs_bb58f4304c9d3f8ed9b109b9ed5eaf3196565b84"
          );
        $.ajax({
          url: myAjax.ajaxurl,
          type: "POST",
          data: {
            action: "getOrderSite",
            store: domainStr,
          },
          success: function (data) {
            swal(data, {
              icon: "success",
            });
          },
          error: function (e) {
            swal("Lỗi", {
              icon: "error",
            });
          },
        });
      }
    }
  });

  $(document).on("click", ".update_002_button", function () {
    var storeStr = document.getElementById("store_value").value;
    var imgUrl = document.getElementById("imgproxy_url_value").value;
    var gaStr = document.getElementById("ga_value").value;
    var pixelStr = document.getElementById("pixel_value").value;

    storeStr = storeStr.replace("https://", "");
    storeStr = storeStr.replace("www.", "");
    storeStr = storeStr.replaceAll("/", "");
    var domainStr = "https://" + storeStr + "/api/revalidate?tag=all";
    var apiStr = "https://admin." + storeStr + "/wp-json/wc";
    storeStr = storeStr.replaceAll(".", "");

    var storeFinal = storeStr.trim();
    if (storeFinal.length > 0) {
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("company")
        .set("HAALO ECOMMERCE PTE. LTD");
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("address")
        .set(
          "3 Coleman Street #03-24, Peninsula Shopping Complex, 179804, Singapore"
        );
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("email")
        .set("support@helpingcenter.network");
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("paypal_client_id")
        .set(
          "AYik9q9KjVgRJNhYWSrGdC9GYG1cmdaKFCQ6NwMimVUu6hB-QCI58j1gd9RYPm8E4xTvYmlgrFZIRfe2"
        );

      appDb.child("PXTRUE2").child(storeFinal).child("wp_api").set(apiStr);
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("imgproxy_url")
        .set(imgUrl);
      appDb.child("PXTRUE2").child(storeFinal).child("phone").set("");
      appDb.child("PXTRUE2").child(storeFinal).child("pixel_ids").set(pixelStr);
      appDb.child("PXTRUE2").child(storeFinal).child("ga_ids").set(gaStr);
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("wp_auth")
        .set(
          "ck_9eb7bb32ef2d39df2931d106ecb5bfbcc97dce30:cs_bb58f4304c9d3f8ed9b109b9ed5eaf3196565b84"
        );

      $.ajax({
        url: myAjax.ajaxurl,
        type: "POST",
        data: {
          action: "getOrderSite",
          store: domainStr,
        },
        success: function (data) {
          swal(data, {
            icon: "success",
          });
        },
        error: function (e) {
          swal("Lỗi", {
            icon: "error",
          });
        },
      });
    }
  });

  $(document).on("click", ".update_003_button", function () {
    var storeStr = document.getElementById("store_value").value;
    var imgUrl = document.getElementById("imgproxy_url_value").value;
    var gaStr = document.getElementById("ga_value").value;
    var pixelStr = document.getElementById("pixel_value").value;

    storeStr = storeStr.replace("https://", "");
    storeStr = storeStr.replace("www.", "");
    storeStr = storeStr.replaceAll("/", "");
    var domainStr = "https://" + storeStr + "/api/revalidate?tag=all";
    var apiStr = "https://admin." + storeStr + "/wp-json/wc";
    storeStr = storeStr.replaceAll(".", "");

    var storeFinal = storeStr.trim();

    if (storeFinal.length > 0) {
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("company")
        .set("Crpt Puppies Hub PTE. LTD");
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("address")
        .set(
          "3 Coleman Street #03-24, Peninsula Shopping Complex, 179804, Singapore"
        );
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("email")
        .set("support@cs-support.team");
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("paypal_client_id")
        .set(
          "Ae0uICwuDcUApxG_3ktMj7MOp_HY-lZjxVX4cXT8T09LWImgaE_SRJn44xvYg-MANY3ZNkRmRpRpdPVj"
        );

      appDb.child("PXTRUE2").child(storeFinal).child("wp_api").set(apiStr);
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("imgproxy_url")
        .set(imgUrl);
      appDb.child("PXTRUE2").child(storeFinal).child("phone").set("");
      appDb.child("PXTRUE2").child(storeFinal).child("pixel_ids").set(pixelStr);
      appDb.child("PXTRUE2").child(storeFinal).child("ga_ids").set(gaStr);
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("wp_auth")
        .set(
          "ck_9eb7bb32ef2d39df2931d106ecb5bfbcc97dce30:cs_bb58f4304c9d3f8ed9b109b9ed5eaf3196565b84"
        );

      $.ajax({
        url: myAjax.ajaxurl,
        type: "POST",
        data: {
          action: "getOrderSite",
          store: domainStr,
        },
        success: function (data) {
          swal(data, {
            icon: "success",
          });
        },
        error: function (e) {
          swal("Lỗi", {
            icon: "error",
          });
        },
      });
    }
  });

  $(document).on("click", ".update_222_button", function () {
    var storeStr = document.getElementById("store_value").value;
    var imgUrl = document.getElementById("imgproxy_url_value").value;
    var gaStr = document.getElementById("ga_value").value;
    var pixelStr = document.getElementById("pixel_value").value;

    storeStr = storeStr.replace("https://", "");
    storeStr = storeStr.replace("www.", "");
    storeStr = storeStr.replaceAll("/", "");
    var domainStr = "https://" + storeStr + "/api/revalidate?tag=all";
    var apiStr = "https://admin." + storeStr + "/wp-json/wc";
    storeStr = storeStr.replaceAll(".", "");

    var storeFinal = storeStr.trim();

    if (storeFinal.length > 0) {
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("company")
        .set("Trust Store Ecommerce Pte. Ltd");
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("address")
        .set("1 Tampines North Drive 1, #06-08, Singapore, 528559");
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("email")
        .set("contact@truststore.support");
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("paypal_client_id")
        .set(
          "AcB1L7iGJitE7DLlaPbovIb3esPIsalbwy10bBzgo3xjVAa2I5optsWtYRXJB_TmV4NVTCRVEmMdvxXJ"
        );

      appDb.child("PXTRUE2").child(storeFinal).child("wp_api").set(apiStr);
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("imgproxy_url")
        .set(imgUrl);
      appDb.child("PXTRUE2").child(storeFinal).child("phone").set("");
      appDb.child("PXTRUE2").child(storeFinal).child("pixel_ids").set(pixelStr);
      appDb.child("PXTRUE2").child(storeFinal).child("ga_ids").set(gaStr);
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("wp_auth")
        .set(
          "ck_9eb7bb32ef2d39df2931d106ecb5bfbcc97dce30:cs_bb58f4304c9d3f8ed9b109b9ed5eaf3196565b84"
        );

      $.ajax({
        url: myAjax.ajaxurl,
        type: "POST",
        data: {
          action: "getOrderSite",
          store: domainStr,
        },
        success: function (data) {
          swal(data, {
            icon: "success",
          });
        },
        error: function (e) {
          swal("Lỗi", {
            icon: "error",
          });
        },
      });
    }
  });

  $(document).on("click", ".update_666_button", function () {
    var storeStr = document.getElementById("store_value").value;
    var imgUrl = document.getElementById("imgproxy_url_value").value;
    var gaStr = document.getElementById("ga_value").value;
    var pixelStr = document.getElementById("pixel_value").value;

    storeStr = storeStr.replace("https://", "");
    storeStr = storeStr.replace("www.", "");
    storeStr = storeStr.replaceAll("/", "");
    var domainStr = "https://" + storeStr + "/api/revalidate?tag=all";
    var apiStr = "https://admin." + storeStr + "/wp-json/wc";
    storeStr = storeStr.replaceAll(".", "");

    var storeFinal = storeStr.trim();

    if (storeFinal.length > 0) {
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("company")
        .set("StellarBuy Ecommerce Pte. Ltd");
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("address")
        .set("1 Tampines North Drive 1, #06-08, Singapore, 528559");
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("email")
        .set("contact@stellarbuyecommerce.com");
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("paypal_client_id")
        .set(
          "AZOjMz0V8b4VoY9-gz-pWuVfvHFEXbU1z0aD01ROSo-5M8kdFW0CaxB7o1ss-aX8s1mwlPSK_9aEpG_X"
        );

      appDb.child("PXTRUE2").child(storeFinal).child("wp_api").set(apiStr);
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("imgproxy_url")
        .set(imgUrl);
      appDb.child("PXTRUE2").child(storeFinal).child("phone").set("");
      appDb.child("PXTRUE2").child(storeFinal).child("pixel_ids").set(pixelStr);
      appDb.child("PXTRUE2").child(storeFinal).child("ga_ids").set(gaStr);
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("wp_auth")
        .set(
          "ck_9eb7bb32ef2d39df2931d106ecb5bfbcc97dce30:cs_bb58f4304c9d3f8ed9b109b9ed5eaf3196565b84"
        );

      $.ajax({
        url: myAjax.ajaxurl,
        type: "POST",
        data: {
          action: "getOrderSite",
          store: domainStr,
        },
        success: function (data) {
          swal(data, {
            icon: "success",
          });
        },
        error: function (e) {
          swal("Lỗi", {
            icon: "error",
          });
        },
      });
    }
  });

  $(document).on("click", ".update_888_button", function () {
    var storeStr = document.getElementById("store_value").value;
    var imgUrl = document.getElementById("imgproxy_url_value").value;
    var gaStr = document.getElementById("ga_value").value;
    var pixelStr = document.getElementById("pixel_value").value;

    storeStr = storeStr.replace("https://", "");
    storeStr = storeStr.replace("www.", "");
    storeStr = storeStr.replaceAll("/", "");
    var domainStr = "https://" + storeStr + "/api/revalidate?tag=all";
    var apiStr = "https://admin." + storeStr + "/wp-json/wc";
    storeStr = storeStr.replaceAll(".", "");

    var storeFinal = storeStr.trim();

    if (storeFinal.length > 0) {
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("company")
        .set("OasisDeals Ecommerce Pte. Ltd");
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("address")
        .set("1 Tampines North Drive 1, #06-08, Singapore, 528559");
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("email")
        .set("contact@oasisdealsecommerce.com");
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("paypal_client_id")
        .set(
          "AWfQTL9QTDtVJ91Cq1leOMZ549zT0BGh4HbfzLMi8j7bl9VUQx8RrSSrEDS3ZQC_OJpCjeik1xA5bTbV"
        );

      appDb.child("PXTRUE2").child(storeFinal).child("wp_api").set(apiStr);
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("imgproxy_url")
        .set(imgUrl);
      appDb.child("PXTRUE2").child(storeFinal).child("phone").set("");
      appDb.child("PXTRUE2").child(storeFinal).child("pixel_ids").set(pixelStr);
      appDb.child("PXTRUE2").child(storeFinal).child("ga_ids").set(gaStr);
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("wp_auth")
        .set(
          "ck_9eb7bb32ef2d39df2931d106ecb5bfbcc97dce30:cs_bb58f4304c9d3f8ed9b109b9ed5eaf3196565b84"
        );

      $.ajax({
        url: myAjax.ajaxurl,
        type: "POST",
        data: {
          action: "getOrderSite",
          store: domainStr,
        },
        success: function (data) {
          swal(data, {
            icon: "success",
          });
        },
        error: function (e) {
          swal("Lỗi", {
            icon: "error",
          });
        },
      });
    }
  });

  $(document).on("click", ".update_DTH_button", function () {
    var storeStr = document.getElementById("store_value").value;
    var imgUrl = document.getElementById("imgproxy_url_value").value;
    var gaStr = document.getElementById("ga_value").value;
    var pixelStr = document.getElementById("pixel_value").value;

    storeStr = storeStr.replace("https://", "");
    storeStr = storeStr.replace("www.", "");
    storeStr = storeStr.replaceAll("/", "");
    var domainStr = "https://" + storeStr + "/api/revalidate?tag=all";
    var apiStr = "https://admin." + storeStr + "/wp-json/wc";
    storeStr = storeStr.replaceAll(".", "");

    var storeFinal = storeStr.trim();

    if (storeFinal.length > 0) {
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("company")
        .set("DTH Ecommerce Pte. Ltd");
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("address")
        .set("1 Tampines North Drive 1, #06-08 T-Space, 528559, Singapore");
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("email")
        .set("support@dth-support.com");
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("paypal_client_id")
        .set(
          "AWLnAWhSTPY7hufx3EfzdYIXiKttypfnhBQx3X96QkFj1Y_nDuJEZlYG_OI8XYDo-i3QGavUDWMZ8For"
        );

      appDb.child("PXTRUE2").child(storeFinal).child("wp_api").set(apiStr);
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("imgproxy_url")
        .set(imgUrl);
      appDb.child("PXTRUE2").child(storeFinal).child("phone").set("");
      appDb.child("PXTRUE2").child(storeFinal).child("pixel_ids").set(pixelStr);
      appDb.child("PXTRUE2").child(storeFinal).child("ga_ids").set(gaStr);
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("wp_auth")
        .set(
          "ck_9eb7bb32ef2d39df2931d106ecb5bfbcc97dce30:cs_bb58f4304c9d3f8ed9b109b9ed5eaf3196565b84"
        );

      $.ajax({
        url: myAjax.ajaxurl,
        type: "POST",
        data: {
          action: "getOrderSite",
          store: domainStr,
        },
        success: function (data) {
          swal(data, {
            icon: "success",
          });
        },
        error: function (e) {
          swal("Lỗi", {
            icon: "error",
          });
        },
      });
    }
  });

  $(document).on("click", ".update_NKH_button", function () {
    var storeStr = document.getElementById("store_value").value;
    var imgUrl = document.getElementById("imgproxy_url_value").value;
    var gaStr = document.getElementById("ga_value").value;
    var pixelStr = document.getElementById("pixel_value").value;

    storeStr = storeStr.replace("https://", "");
    storeStr = storeStr.replace("www.", "");
    storeStr = storeStr.replaceAll("/", "");
    var domainStr = "https://" + storeStr + "/api/revalidate?tag=all";
    var apiStr = "https://admin." + storeStr + "/wp-json/wc";
    storeStr = storeStr.replaceAll(".", "");

    var storeFinal = storeStr.trim();

    if (storeFinal.length > 0) {
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("company")
        .set("NKH Ecommerce Pte. Ltd");
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("address")
        .set("1 Tampines North Drive 1, #06-08 T-Space, 528559, Singapore");
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("email")
        .set("contact@support-nkh.com");
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("paypal_client_id")
        .set(
          "AWqk1wztQ_JYRRrz-ulzNoVmuE5SeWTISYfE8dY4_W7RwLenBOOUtsP75uUvFWiopOX6bx0TQekHVtoZ"
        );

      appDb.child("PXTRUE2").child(storeFinal).child("wp_api").set(apiStr);
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("imgproxy_url")
        .set(imgUrl);
      appDb.child("PXTRUE2").child(storeFinal).child("phone").set("");
      appDb.child("PXTRUE2").child(storeFinal).child("pixel_ids").set(pixelStr);
      appDb.child("PXTRUE2").child(storeFinal).child("ga_ids").set(gaStr);
      appDb
        .child("PXTRUE2")
        .child(storeFinal)
        .child("wp_auth")
        .set(
          "ck_9eb7bb32ef2d39df2931d106ecb5bfbcc97dce30:cs_bb58f4304c9d3f8ed9b109b9ed5eaf3196565b84"
        );

      $.ajax({
        url: myAjax.ajaxurl,
        type: "POST",
        data: {
          action: "getOrderSite",
          store: domainStr,
        },
        success: function (data) {
          swal(data, {
            icon: "success",
          });
        },
        error: function (e) {
          swal("Lỗi", {
            icon: "error",
          });
        },
      });
    }
  });
});
