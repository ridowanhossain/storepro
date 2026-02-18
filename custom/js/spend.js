var managespendtable;

$(document).ready(function () {
  // Initialize DataTable
  managespendtable = $("#manageinvocetable").DataTable({
    ajax: "php_action/fetchSpend.php",
    order: [],
    lengthMenu: [
      [10, 25, 50, 100, 500, 1000, -1],
      [10, 25, 50, 100, 500, 1000, "All"],
    ],
    language: {
      sProcessing: "প্রসেসিং হচ্ছে...",
      sLengthMenu: "_MENU_ টি তথ্য দেখান",
      sZeroRecords: "কোন তথ্য পাওয়া যায়নি",
      sEmptyTable: "টেবিলে কোন তথ্য নেই",
      sInfo: "মোট _TOTAL_ টির মধ্যে _START_ থেকে _END_ দেখানো হচ্ছে",
      sInfoEmpty: "মোট 0 টির মধ্যে 0 থেকে 0 দেখানো হচ্ছে",
      sInfoFiltered: "(মোট _MAX_ টি তথ্য থেকে বাছাইকৃত)",
      sInfoPostFix: "",
      sSearch: "অনুসন্ধান:",
      sUrl: "",
      sInfoThousands: ",",
      sLoadingRecords: "লোডিং...",
      oPaginate: {
        sFirst: "প্রথম",
        sLast: "শেষ",
        sNext: "পরবর্তী",
        sPrevious: "পূর্ববর্তী",
      },
      oAria: {
        sSortAscending: ": কলামটি উর্ধ্বক্রমে সাজাতে ক্লিক করুন",
        sSortDescending: ": কলামটি নিম্নক্রমে সাজাতে ক্লিক করুন",
      },
    },
  });

  // Initialize date pickers
  $("#spend_date").datepicker({
    dateFormat: "dd/mm/yy",
    changeMonth: true,
    changeYear: true,
  });

  // Set today's date as default (SAFE WAY)
  var today = new Date();
  var dd = today.getDate();
  var mm = today.getMonth() + 1;
  var yyyy = today.getFullYear();

  if (dd < 10) dd = "0" + dd;
  if (mm < 10) mm = "0" + mm;

  $("#spend_date").val(dd + "/" + mm + "/" + yyyy);

  $("#edit_spend_date").datepicker({
    dateFormat: "dd/mm/yy",
    changeMonth: true,
    changeYear: true,
  });

  // Add Spend Form Submit
  $("#submitspendForm")
    .unbind("submit")
    .bind("submit", function (e) {
      e.preventDefault(); // Explicitly prevent default submission

      $(".text-danger").remove();
      $(".form-group").removeClass("has-error").removeClass("has-success");

      var category = $("#spend_category").val();
      var description = $("#spend_description").val();
      var amount = $("#spend_amount").val();
      var spendDate = $("#spend_date").val();

      var valid = true;

      if (category == "") {
        $("#spend_category").after(
          '<p class="text-danger">ক্যাটাগরি নির্বাচন করুন</p>',
        );
        $("#spend_category").closest(".form-group").addClass("has-error");
        valid = false;
      }

      if (description == "") {
        $("#spend_description").after('<p class="text-danger">বিবরণ লিখুন</p>');
        $("#spend_description").closest(".form-group").addClass("has-error");
        valid = false;
      }

      if (amount == "" || amount <= 0) {
        $("#spend_amount").after(
          '<p class="text-danger">টাকার পরিমাণ লিখুন</p>',
        );
        $("#spend_amount").closest(".form-group").addClass("has-error");
        valid = false;
      }

      if (spendDate == "") {
        $("#spend_date").after(
          '<p class="text-danger">তারিখ নির্বাচন করুন</p>',
        );
        $("#spend_date").closest(".form-group").addClass("has-error");
        valid = false;
      }

      if (valid) {
        var form = $(this);
        $("#createspendBtn").button("loading");

        $.ajax({
          url: form.attr("action"),
          type: form.attr("method"),
          data: form.serialize(),
          dataType: "json",
          success: function (response) {
            $("#createspendBtn").button("reset");

            if (response.success == true) {
              managespendtable.ajax.reload(null, false);
              $("#submitspendForm")[0].reset();
              $(".text-danger").remove();
              $(".form-group")
                .removeClass("has-error")
                .removeClass("has-success");

              // Reset date to today
              $("#spend_date").val(dd + "/" + mm + "/" + yyyy);

              $("#add-spend-messages").html(
                '<div class="alert alert-success">' +
                  '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                  '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> ' +
                  response.messages +
                  "</div>",
              );

              $(".alert-success")
                .delay(500)
                .show(10, function () {
                  $(this)
                    .delay(2000)
                    .hide(10, function () {
                      $(this).remove();
                      $("#addspend").modal("hide");
                    });
                });
            }
          },
        });
      }

      return false;
    });
});

// Edit Spend
function editspend(spendId) {
  if (spendId) {
    $("#spendId").remove();
    $(".text-danger").remove();
    $(".form-group").removeClass("has-error").removeClass("has-success");

    $.ajax({
      url: "php_action/fetchSelectedSpend.php",
      type: "post",
      data: { spendId: spendId },
      dataType: "json",
      success: function (response) {
        $("#edit_spend_category").val(response.category);
        $("#edit_spend_description").val(response.description);
        $("#edit_spend_amount").val(response.total);
        $("#edit_spend_date").val(response.spend_date);

        $(".editspendFooter").after(
          '<input type="hidden" name="Id" id="spendId" value="' +
            response.id +
            '" />',
        );

        $("#editspendForm")
          .unbind("submit")
          .bind("submit", function (e) {
            e.preventDefault(); // Explicitly prevent default submission

            $(".text-danger").remove();
            $(".form-group")
              .removeClass("has-error")
              .removeClass("has-success");

            var category = $("#edit_spend_category").val();
            var description = $("#edit_spend_description").val();
            var amount = $("#edit_spend_amount").val();

            var valid = true;

            if (category == "") {
              $("#edit_spend_category").after(
                '<p class="text-danger">ক্যাটাগরি নির্বাচন করুন</p>',
              );
              $("#edit_spend_category")
                .closest(".form-group")
                .addClass("has-error");
              valid = false;
            }

            if (description == "") {
              $("#edit_spend_description").after(
                '<p class="text-danger">বিবরণ লিখুন</p>',
              );
              $("#edit_spend_description")
                .closest(".form-group")
                .addClass("has-error");
              valid = false;
            }

            if (amount == "" || amount <= 0) {
              $("#edit_spend_amount").after(
                '<p class="text-danger">টাকার পরিমাণ লিখুন</p>',
              );
              $("#edit_spend_amount")
                .closest(".form-group")
                .addClass("has-error");
              valid = false;
            }

            if (valid) {
              var form = $(this);
              $("#editspendBtn").button("loading");

              $.ajax({
                url: form.attr("action"),
                type: form.attr("method"),
                data: form.serialize(),
                dataType: "json",
                success: function (response) {
                  if (response.success == true) {
                    $("#editspendBtn").button("reset");
                    managespendtable.ajax.reload(null, false);
                    $(".text-danger").remove();
                    $(".form-group")
                      .removeClass("has-error")
                      .removeClass("has-success");

                    $("#edit-spend-messages").html(
                      '<div class="alert alert-success">' +
                        '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                        '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> ' +
                        response.messages +
                        "</div>",
                    );

                    $(".alert-success")
                      .delay(500)
                      .show(10, function () {
                        $(this)
                          .delay(2000)
                          .hide(10, function () {
                            $(this).remove();
                            $("#editspend").modal("hide");
                          });
                      });
                  }
                },
              });
            }

            return false;
          });
      },
    });
  } else {
    alert("ত্রুটি! পেজটি রিফ্রেশ করুন");
  }
}

// Remove Spend
