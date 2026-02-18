$(document).ready(function () {
  // order date picker
  $("#startDate").datepicker({
    dateFormat: "dd/mm/yy",
  });
  // order date picker
  $("#endDate").datepicker({
    dateFormat: "dd/mm/yy",
  });

  $("#getSpendReportForm")
    .unbind("submit")
    .bind("submit", function () {
      var startDate = $("#startDate").val();
      var endDate = $("#endDate").val();

      if (startDate == "" || endDate == "") {
        if (startDate == "") {
          $("#startDate").closest(".form-group").addClass("has-error");
          $("#startDate").after(
            '<p class="text-danger">শুরুর তারিখ প্রয়োজন</p>',
          );
        } else {
          $(".form-group").removeClass("has-error");
          $(".text-danger").remove();
        }

        if (endDate == "") {
          $("#endDate").closest(".form-group").addClass("has-error");
          $("#endDate").after(
            '<p class="text-danger">শেষের তারিখ প্রয়োজন</p>',
          );
        } else {
          $(".form-group").removeClass("has-error");
          $(".text-danger").remove();
        }
      } else {
        $(".form-group").removeClass("has-error");
        $(".text-danger").remove();

        var form = $(this);

        $.ajax({
          url: form.attr("action"),
          type: form.attr("method"),
          data: form.serialize(),
          dataType: "text",
          success: function (response) {
            var mywindow = window.open(
              "",
              "Spend Report System",
              "height=400,width=600",
            );
            mywindow.document.write(
              "<html><head><title>Spend Report Slip</title>",
            );
            mywindow.document.write(
              '<style>@import url("https://fonts.googleapis.com/css2?family=Noto+Serif+Bengali:wght@100..900&display=swap"); body { font-family: "Noto Serif Bengali", serif; }</style>',
            );
            mywindow.document.write("</head><body>");
            mywindow.document.write(response);
            mywindow.document.write("</body></html>");

            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10

            mywindow.document.fonts.ready.then(function () {
              setTimeout(function () {
                mywindow.print();
                mywindow.close();
              }, 500);
            });
          }, // /success
        }); // /ajax
      } // /else

      return false;
    });
});
