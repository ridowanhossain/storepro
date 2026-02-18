$(document).ready(function () {
  // order date picker
  $("#startDate").datepicker({
    dateFormat: "dd/mm/yy",
  });
  // order date picker
  $("#endDate").datepicker({
    dateFormat: "dd/mm/yy",
  });

  // Use 'on' for cleaner event handling
  $("#getOrderReportForm").on("submit", function (e) {
    e.preventDefault();

    var startDate = $("#startDate").val();
    var endDate = $("#endDate").val();

    if (startDate == "" || endDate == "") {
      if (startDate == "") {
        $("#startDate").closest(".form-group").addClass("has-error");
        $("#startDate").after(
          '<p class="text-danger">শুরুর তারিখ প্রয়োজন</p>',
        );
      } else {
        // remove error text field
        $("#startDate").closest(".form-group").find(".text-danger").remove();
        $("#startDate").closest(".form-group").removeClass("has-error");
      }

      if (endDate == "") {
        $("#endDate").closest(".form-group").addClass("has-error");
        $("#endDate").after('<p class="text-danger">শেষের তারিখ প্রয়োজন</p>');
      } else {
        // remove error text field
        $("#endDate").closest(".form-group").find(".text-danger").remove();
        $("#endDate").closest(".form-group").removeClass("has-error");
      }
    } else {
      $(".form-group").removeClass("has-error");
      $(".text-danger").remove();

      var form = $(this);

      // Open window immediately to avoid popup blockers
      var mywindow = window.open(
        "",
        "Stock Management System",
        "height=400,width=600",
      );

      if (
        !mywindow ||
        mywindow.closed ||
        typeof mywindow.closed == "undefined"
      ) {
        alert(
          "পপ-আপ উইন্ডোটি ব্রাউজার দ্বারা বন্ধ করা হয়েছে। অনুগ্রহ করে এই সাইটের জন্য পপ-আপ অনুমতি দিন।",
        );
        $(".form-group").removeClass("has-error");
        return false;
      }

      mywindow.document.write(
        "<html><head><title>Processing...</title></head><body>তথ্য লোড হচ্ছে...</body></html>",
      );

      $.ajax({
        url: form.attr("action"),
        type: form.attr("method"),
        data: form.serialize(),
        dataType: "text",
        success: function (response) {
          mywindow.document.open();
          mywindow.document.write(
            "<html><head><title>Order Report Slip</title>",
          );
          mywindow.document.write(
            '<style>@import url("https://fonts.googleapis.com/css2?family=Noto+Serif+Bengali:wght@100..900&display=swap"); body { font-family: "Noto Serif Bengali", serif; }</style>',
          );
          mywindow.document.write("</head><body>");
          mywindow.document.write(response);
          mywindow.document.write("</body></html>");

          mywindow.document.close(); // necessary for IE >= 10
          mywindow.focus(); // necessary for IE >= 10

          // Use a timeout to ensure content is loaded before print
          mywindow.document.fonts.ready.then(function () {
            setTimeout(function () {
              mywindow.print();
              mywindow.close();
            }, 500);
          });
        },
        error: function (xhr, status, error) {
          if (mywindow) mywindow.close();
          alert(
            "রিপোর্ট তৈরিতে সমস্যা হয়েছে: " +
              error +
              "\n" +
              (xhr.responseText ? xhr.responseText.substring(0, 500) : ""),
          );
        },
      }); // /ajax
    } // /else

    return false;
  });
});
