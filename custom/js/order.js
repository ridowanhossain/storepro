var manageOrderTable;

$(document).ready(function () {
  var divRequest = $(".div-request").text();

  // top nav bar
  $("#navOrder").addClass("active");

  if (divRequest == "add") {
    // add order
    // top nav child bar
    $("#topNavAddOrder").addClass("active");

    // order date picker with today's date selected
    $("#orderDate").datepicker();
    // Set today's date as default
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, "0");
    var mm = String(today.getMonth() + 1).padStart(2, "0"); // January is 0!
    var yyyy = today.getFullYear();
    var todayFormatted = dd + "/" + mm + "/" + yyyy;
    $("#orderDate").datepicker("setDate", today);

    // create order form function
    $("#createOrderForm")
      .unbind("submit")
      .bind("submit", function () {
        var form = $(this);

        $(".form-group").removeClass("has-error").removeClass("has-success");
        $(".text-danger").remove();

        var orderDate = $("#orderDate").val();
        var clientName = $("#clientName").val();
        var clientContact = $("#clientContact").val();
        var paid = $("#paid").val();
        var discount = $("#discount").val();
        var paymentType = $("#paymentType").val();
        var paymentStatus = $("#paymentStatus").val();

        // form validation
        if (orderDate == "") {
          $("#orderDate").after(
            '<p class="text-danger"> The Order Date field is required </p>',
          );
          $("#orderDate").closest(".form-group").addClass("has-error");
        } else {
          $("#orderDate").closest(".form-group").addClass("has-success");
        } // /else

        if (clientName == "") {
          $("#clientName").after(
            '<p class="text-danger"> The Client Name field is required </p>',
          );
          $("#clientName").closest(".form-group").addClass("has-error");
        } else {
          $("#clientName").closest(".form-group").addClass("has-success");
        } // /else

        if (clientContact == "") {
          $("#clientContact").after(
            '<p class="text-danger"> The Contact field is required </p>',
          );
          $("#clientContact").closest(".form-group").addClass("has-error");
        } else {
          $("#clientContact").closest(".form-group").addClass("has-success");
        } // /else

        if (paid == "") {
          $("#paid").after(
            '<p class="text-danger"> The Paid field is required </p>',
          );
          $("#paid").closest(".form-group").addClass("has-error");
        } else {
          $("#paid").closest(".form-group").addClass("has-success");
        } // /else

        if (discount == "") {
          $("#discount").after(
            '<p class="text-danger"> The Discount field is required </p>',
          );
          $("#discount").closest(".form-group").addClass("has-error");
        } else {
          $("#discount").closest(".form-group").addClass("has-success");
        } // /else

        if (paymentType == "") {
          $("#paymentType").after(
            '<p class="text-danger"> The Payment Type field is required </p>',
          );
          $("#paymentType").closest(".form-group").addClass("has-error");
        } else {
          $("#paymentType").closest(".form-group").addClass("has-success");
        } // /else

        if (paymentStatus == "") {
          $("#paymentStatus").after(
            '<p class="text-danger"> The Payment Status field is required </p>',
          );
          $("#paymentStatus").closest(".form-group").addClass("has-error");
        } else {
          $("#paymentStatus").closest(".form-group").addClass("has-success");
        } // /else

        // array validation
        var productName = document.getElementsByName("productName[]");
        var validateProduct;
        for (var x = 0; x < productName.length; x++) {
          var productNameId = productName[x].id;
          if (productName[x].value == "") {
            $("#" + productNameId + "").after(
              '<p class="text-danger"> Product Name Field is required!! </p>',
            );
            $("#" + productNameId + "")
              .closest(".form-group")
              .addClass("has-error");
          } else {
            $("#" + productNameId + "")
              .closest(".form-group")
              .addClass("has-success");
          }
        } // for

        for (var x = 0; x < productName.length; x++) {
          if (productName[x].value) {
            validateProduct = true;
          } else {
            validateProduct = false;
          }
        } // for

        var quantity = document.getElementsByName("quantity[]");
        var validateQuantity;
        for (var x = 0; x < quantity.length; x++) {
          var quantityId = quantity[x].id;
          if (quantity[x].value == "") {
            $("#" + quantityId + "").after(
              '<p class="text-danger"> Product Name Field is required!! </p>',
            );
            $("#" + quantityId + "")
              .closest(".form-group")
              .addClass("has-error");
          } else {
            $("#" + quantityId + "")
              .closest(".form-group")
              .addClass("has-success");
          }
        } // for

        for (var x = 0; x < quantity.length; x++) {
          if (quantity[x].value) {
            validateQuantity = true;
          } else {
            validateQuantity = false;
          }
        } // for

        if (
          orderDate &&
          clientName &&
          clientContact &&
          paid &&
          discount &&
          paymentType &&
          paymentStatus
        ) {
          if (validateProduct == true && validateQuantity == true) {
            // create order button
            // $("#createOrderBtn").button('loading');

            $.ajax({
              url: form.attr("action"),
              type: form.attr("method"),
              data: form.serialize(),
              dataType: "json",
              success: function (response) {
                console.log(response);
                // reset button
                $("#createOrderBtn").button("reset");

                $(".text-danger").remove();
                $(".form-group")
                  .removeClass("has-error")
                  .removeClass("has-success");

                if (response.success == true) {
                  // create order button
                  $(".success-messages").html(
                    '<div class="alert alert-success">' +
                      '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                      '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> ' +
                      response.messages +
                      '<br /> <br /> <a type="button" onclick="printOrder(' +
                      response.order_id +
                      ')" class="btn btn-primary"> <i class="glyphicon glyphicon-print"></i> Print </a>' +
                      '<a href="quick-order" class="btn btn-default" style="margin-left:10px;"> <i class="glyphicon glyphicon-plus-sign"></i> দ্রুত অর্ডার </a>' +
                      '<a href="regular-order" class="btn btn-default" style="margin-left:10px;"> <i class="glyphicon glyphicon-plus-sign"></i> নিয়মিত অর্ডার </a>' +
                      "</div>",
                  );

                  $("html, body, div.panel, div.pane-body").animate(
                    { scrollTop: "0px" },
                    100,
                  );

                  // disabled te modal footer button
                  $(".submitButtonFooter").addClass("div-hide");
                  // remove the product row
                  $(".removeProductRowBtn").addClass("div-hide");
                } else {
                  alert(response.messages);
                }
              }, // /response
            }); // /ajax
          } // if array validate is true
        } // /if field validate is true

        return false;
      }); // /create order form function
  } else if (divRequest == "manord") {
    // top nav child bar
    $("#topNavManageOrder").addClass("active");

    // Add date range filter inputs before the table
    $("#manageOrderTable").before(
      '<div class="filter-container">' +
        '<div class="row">' +
        '<div class="col-md-3">' +
        '<div class="form-group mb-0">' +
        '<label class="filter-label">শুরুর তারিখ</label>' +
        '<div class="input-group">' +
        '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>' +
        '<input type="text" id="fromDate" class="form-control filter-input" placeholder="দিন/মাস/বছর" readonly>' +
        "</div>" +
        "</div>" +
        "</div>" +
        '<div class="col-md-3">' +
        '<div class="form-group mb-0">' +
        '<label class="filter-label">শেষ তারিখ</label>' +
        '<div class="input-group">' +
        '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>' +
        '<input type="text" id="toDate" class="form-control filter-input" placeholder="দিন/মাস/বছর" readonly>' +
        "</div>" +
        "</div>" +
        "</div>" +
        '<div class="col-md-6 text-right">' +
        '<div class="filter-buttons">' +
        '<button id="searchByDate" class="btn btn-search"><i class="glyphicon glyphicon-search"></i> অনুসন্ধান করুন</button>' +
        '<button id="resetDate" class="btn btn-reset"><i class="glyphicon glyphicon-refresh"></i> রিসেট</button>' +
        '<button id="printOrder" class="btn btn-print"><i class="glyphicon glyphicon-print"></i> প্রিন্ট</button>' +
        '<a href="quick-order" class="btn btn-success"><i class="fa fa-plus-circle"></i> অর্ডার করুন</a>' +
        "</div>" +
        "</div>" +
        "</div>" +
        "</div>",
    );

    $("#printOrder").on("click", function () {
      const printContents = $("#manageOrderTable").prop("outerHTML");
      const printWindow = window.open("", "_blank");
      printWindow.document.write(
        "<html><head><title>Print Table</title><style>@import url('https://fonts.googleapis.com/css2?family=Noto+Serif+Bengali:wght@100..900&display=swap'); @page { margin: 10mm; } body{ margin: 0; padding: 0; } *{ box-sizing: border-box; } table{ font-family: 'Noto Serif Bengali', serif; font-size: 9px; width: 99% !important; margin: 10px auto; border: 1px solid #000; border-collapse: collapse; table-layout: auto;} th, td{ border: 1px solid #000; padding: 2px; text-align: center; word-break: break-all; } th:nth-child(10),td:nth-child(10), tr.summary-row td:nth-child(3) {display: none;} tr.summary-row td{text-align: right !important}</style></head><body>",
      );
      printWindow.document.write(printContents);
      printWindow.document.write("</body></html>");
      printWindow.document.close();
      printWindow.focus();
      printWindow.document.fonts.ready.then(function () {
        setTimeout(function () {
          printWindow.print();
          printWindow.close();
        }, 500);
      });
    });

    // Initialize datepickers with dd/mm/yyyy format
    $("#fromDate").datepicker({
      dateFormat: "dd/mm/yy",
      changeMonth: true,
      changeYear: true,
    });

    $("#toDate").datepicker({
      dateFormat: "dd/mm/yy",
      changeMonth: true,
      changeYear: true,
    });

    manageOrderTable = $("#manageOrderTable").DataTable({
      processing: true,
      serverSide: true,
      responsive: true, // Add responsive feature
      ajax: {
        url: "php_action/fetchOrder.php",
        type: "POST",
        data: function (d) {
          d.fromDate = $("#fromDate").val();
          d.toDate = $("#toDate").val();
        },
        error: function (xhr, error, thrown) {
          console.log("DataTables error:", error);
        },
      },
      order: [[1, "desc"]], // Sort by order ID by default
      columnDefs: [
        {
          targets: [0, -1], // first and last column
          orderable: false, // set not orderable
        },
      ],
      lengthMenu: [
        [10, 25, 50, 100, 500, 1000],
        [10, 25, 50, 100, 500, 1000],
      ],
      pageLength: 10,
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

      drawCallback: function () {
        // Summary row removed as per user request
      },
    });

    // Add event listeners for date search
    $("#searchByDate").on("click", function () {
      manageOrderTable.ajax.reload();
    });

    $("#resetDate").on("click", function () {
      $("#fromDate").val("");
      $("#toDate").val("");
      manageOrderTable.ajax.reload();
    });
  } else if (divRequest == "editOrd") {
    $("#orderDate").datepicker();

    // edit order form function
    $("#editOrderForm")
      .unbind("submit")
      .bind("submit", function (e) {
        e.preventDefault(); // Prevent default form submission
        // alert('ok');
        var form = $(this);

        $(".form-group").removeClass("has-error").removeClass("has-success");
        $(".text-danger").remove();

        var orderDate = $("#orderDate").val();
        var clientName = $("#clientName").val();
        var clientContact = $("#clientContact").val();
        var paid = $("#paid").val();
        var discount = $("#discount").val();
        var paymentType = $("#paymentType").val();
        var paymentStatus = $("#paymentStatus").val();

        // form validation
        if (orderDate == "") {
          $("#orderDate").after(
            '<p class="text-danger"> The Order Date field is required </p>',
          );
          $("#orderDate").closest(".form-group").addClass("has-error");
        } else {
          $("#orderDate").closest(".form-group").addClass("has-success");
        } // /else

        if (clientName == "") {
          $("#clientName").after(
            '<p class="text-danger"> The Client Name field is required </p>',
          );
          $("#clientName").closest(".form-group").addClass("has-error");
        } else {
          $("#clientName").closest(".form-group").addClass("has-success");
        } // /else

        if (clientContact == "") {
          $("#clientContact").after(
            '<p class="text-danger"> The Contact field is required </p>',
          );
          $("#clientContact").closest(".form-group").addClass("has-error");
        } else {
          $("#clientContact").closest(".form-group").addClass("has-success");
        } // /else

        if (paid == "") {
          $("#paid").after(
            '<p class="text-danger"> The Paid field is required </p>',
          );
          $("#paid").closest(".form-group").addClass("has-error");
        } else {
          $("#paid").closest(".form-group").addClass("has-success");
        } // /else

        if (discount == "") {
          $("#discount").after(
            '<p class="text-danger"> The Discount field is required </p>',
          );
          $("#discount").closest(".form-group").addClass("has-error");
        } else {
          $("#discount").closest(".form-group").addClass("has-success");
        } // /else

        if (paymentType == "") {
          $("#paymentType").after(
            '<p class="text-danger"> The Payment Type field is required </p>',
          );
          $("#paymentType").closest(".form-group").addClass("has-error");
        } else {
          $("#paymentType").closest(".form-group").addClass("has-success");
        } // /else

        if (paymentStatus == "") {
          $("#paymentStatus").after(
            '<p class="text-danger"> The Payment Status field is required </p>',
          );
          $("#paymentStatus").closest(".form-group").addClass("has-error");
        } else {
          $("#paymentStatus").closest(".form-group").addClass("has-success");
        } // /else

        // array validation
        var productName = document.getElementsByName("productName[]");
        var validateProduct;
        for (var x = 0; x < productName.length; x++) {
          var productNameId = productName[x].id;
          if (productName[x].value == "") {
            $("#" + productNameId + "").after(
              '<p class="text-danger"> Product Name Field is required!! </p>',
            );
            $("#" + productNameId + "")
              .closest(".form-group")
              .addClass("has-error");
          } else {
            $("#" + productNameId + "")
              .closest(".form-group")
              .addClass("has-success");
          }
        } // for

        for (var x = 0; x < productName.length; x++) {
          if (productName[x].value) {
            validateProduct = true;
          } else {
            validateProduct = false;
          }
        } // for

        var quantity = document.getElementsByName("quantity[]");
        var validateQuantity;
        for (var x = 0; x < quantity.length; x++) {
          var quantityId = quantity[x].id;
          if (quantity[x].value == "") {
            $("#" + quantityId + "").after(
              '<p class="text-danger"> Product Name Field is required!! </p>',
            );
            $("#" + quantityId + "")
              .closest(".form-group")
              .addClass("has-error");
          } else {
            $("#" + quantityId + "")
              .closest(".form-group")
              .addClass("has-success");
          }
        } // for

        for (var x = 0; x < quantity.length; x++) {
          if (quantity[x].value) {
            validateQuantity = true;
          } else {
            validateQuantity = false;
          }
        } // for

        if (
          orderDate &&
          clientName &&
          clientContact &&
          paid &&
          discount &&
          paymentType &&
          paymentStatus
        ) {
          if (validateProduct == true && validateQuantity == true) {
            // create order button
            // $("#createOrderBtn").button('loading');

            $.ajax({
              url: form.attr("action"),
              type: form.attr("method"),
              data: form.serialize(),
              dataType: "json",
              success: function (response) {
                console.log(response);
                // reset button
                $("#editOrderBtn").button("reset");

                $(".text-danger").remove();
                $(".form-group")
                  .removeClass("has-error")
                  .removeClass("has-success");

                if (response.success == true) {
                  // create order button
                  $(".success-messages").html(
                    '<div class="alert alert-success">' +
                      '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                      '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> ' +
                      response.messages,
                  );

                  $("html, body, div.panel, div.pane-body").animate(
                    { scrollTop: "0px" },
                    100,
                  );

                  // disabled te modal footer button
                  $(".editButtonFooter").addClass("div-hide");
                  // remove the product row
                  $(".removeProductRowBtn").addClass("div-hide");
                } else {
                  alert(response.messages);
                }
              }, // /response
            }); // /ajax
          } // if array validate is true
        } // /if field validate is true

        return false;
      }); // /edit order form function
  }
}); // /documernt

// print order function
function printOrder(orderId = null) {
  if (orderId) {
    $.ajax({
      url: "php_action/printOrder.php",
      type: "post",
      data: { orderId: orderId },
      dataType: "text",
      success: function (response) {
        var mywindow = window.open(
          "",
          "Stock Management System",
          "height=400,width=600",
        );
        mywindow.document.write("<html><head><title>Order Invoice</title>");
        mywindow.document.write(
          "<style>@import url('https://fonts.googleapis.com/css2?family=Noto+Serif+Bengali:wght@100..900&display=swap'); body { font-family: 'Noto Serif Bengali', serif; }</style>",
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
      }, // /success function
    }); // /ajax function to fetch the printable order
  } // /if orderId
} // /print order function

function addRow() {
  $("#addRowBtn").button("loading");

  var tableLength = $("#productTable tbody tr").length;

  var tableRow;
  var arrayNumber;
  var count;

  if (tableLength > 0) {
    tableRow = $("#productTable tbody tr:last").attr("id");
    arrayNumber = $("#productTable tbody tr:last").attr("class");
    count = tableRow.substring(3);
    count = Number(count) + 1;
    arrayNumber = Number(arrayNumber) + 1;
  } else {
    // no table row
    count = 1;
    arrayNumber = 0;
  }

  $.ajax({
    url: "php_action/fetchProductData.php",
    type: "post",
    dataType: "json",
    success: function (response) {
      $("#addRowBtn").button("reset");

      var tr =
        '<tr id="row' +
        count +
        '" class="' +
        arrayNumber +
        '">' +
        "<td>" +
        '<div class="form-group">' +
        '<select class="form-control" name="productName[]" id="productName' +
        count +
        '" onchange="getProductData(' +
        count +
        ')" >' +
        '<option value="">~~নির্বাচন করুন~~</option>';
      // console.log(response);
      $.each(response, function (index, value) {
        tr += '<option value="' + value[0] + '">' + value[1] + "</option>";
      });

      tr +=
        "</select>" +
        "</div>" +
        "</td>" +
        "<td>" +
        '<div class="form-group">' +
        '<input type="text" name="brand[]" id="brand' +
        count +
        '" autocomplete="off" disabled="true" class="form-control" />' +
        '<input type="hidden" name="brand[]" id="brand' +
        count +
        '" autocomplete="off" class="form-control" />' +
        "</div>" +
        "<td>" +
        '<div class="form-group">' +
        '<input type="number" name="rate[]" id="rate' +
        count +
        '" autocomplete="off" class="form-control" step="0.01" min="0" onkeyup="syncRateValue(' +
        count +
        "); getTotal(" +
        count +
        '); updateOrderSummary();" onchange="syncRateValue(' +
        count +
        "); getTotal(" +
        count +
        '); updateOrderSummary();" />' +
        '<input type="hidden" name="rateValue[]" id="rateValue' +
        count +
        '" autocomplete="off" class="form-control" />' +
        "</div>" +
        "</td>" +
        "<td>" +
        '<div class="form-group">' +
        '<input type="number" name="quantity[]" id="quantity' +
        count +
        '" oninput="getTotal(' +
        count +
        ')" autocomplete="off" class="form-control" min="0" step="any" inputmode="decimal" pattern="^[0-9]*\\.?[0-9]+$" />' +
        "</div>" +
        "</td>" +
        "</td>" +
        "<td>" +
        '<div class="form-group">' +
        '<input type="text" name="clor[]" id="clor' +
        count +
        '" autocomplete="off" disabled="true" class="form-control" />' +
        '<input type="hidden" name="clor[]" id="clor' +
        count +
        '" autocomplete="off" class="form-control" />' +
        "</div>" +
        "</td>" +
        "<td>" +
        '<div class="form-group">' +
        '<input type="text" name="total[]" id="total' +
        count +
        '" autocomplete="off" class="form-control" disabled="true" />' +
        '<input type="hidden" name="totalValue[]" id="totalValue' +
        count +
        '" autocomplete="off" class="form-control" />' +
        "</div>" +
        "</td>" +
        "<td>" +
        '<div class="form-group">' +
        '<button class="btn btn-default removeProductRowBtn" type="button" onclick="removeProductRow(' +
        count +
        ')"><i class="glyphicon glyphicon-trash"></i></button>' +
        "</div>" +
        "</td>" +
        "</tr>";
      if (tableLength > 0) {
        $("#productTable tbody tr:last").after(tr);
      } else {
        $("#productTable tbody").append(tr);
      }
    }, // /success
  }); // get the product data
} // /add row

function removeProductRow(row = null) {
  if (row) {
    $("#row" + row).remove();

    subAmount();
  } else {
    alert("error! Refresh the page again");
  }
}

// select on product data
function getProductData(row = null) {
  if (row) {
    var productId = $("#productName" + row).val();

    if (productId == "") {
      $("#rate" + row).val("");

      $("#quantity" + row).val("");
      $("#total" + row).val("");

      // remove check if product name is selected
      // var tableProductLength = $("#productTable tbody tr").length;
      // for(x = 0; x < tableProductLength; x++) {
      // 	var tr = $("#productTable tbody tr")[x];
      // 	var count = $(tr).attr('id');
      // 	count = count.substring(3);

      // 	var productValue = $("#productName"+row).val()

      // 	if($("#productName"+count).val() == "") {
      // 		$("#productName"+count).find("#changeProduct"+productId).removeClass('div-hide');
      // 		console.log("#changeProduct"+count);
      // 	}
      // } // /for
    } else {
      $.ajax({
        url: "php_action/fetchSelectedProduct.php",
        type: "post",
        data: { productId: productId },
        dataType: "json",
        success: function (response) {
          // setting the rate value into the rate input field

          $("#brand" + row).val(response.brand_name);
          $("#clor" + row).val(response.clor);
          $("#rate" + row).val(response.rate);
          $("#rateValue" + row).val(response.rate);

          $("#quantity" + row).val(1);

          // Add this helper function near the top of the file
          function formatNumber(number) {
            return Number(number)
              .toFixed(2)
              .replace(/\.?0+$/, "");
          }

          // Update in getProductData function
          var total = Number(response.rate) * 1;
          $("#total" + row).val(formatNumber(total));
          $("#totalValue" + row).val(total.toFixed(2));

          // check if product name is selected
          // var tableProductLength = $("#productTable tbody tr").length;
          // for(x = 0; x < tableProductLength; x++) {
          // 	var tr = $("#productTable tbody tr")[x];
          // 	var count = $(tr).attr('id');
          // 	count = count.substring(3);

          // 	var productValue = $("#productName"+row).val()

          // 	if($("#productName"+count).val() != productValue) {
          // 		// $("#productName"+count+" #changeProduct"+count).addClass('div-hide');
          // 		$("#productName"+count).find("#changeProduct"+productId).addClass('div-hide');
          // 		console.log("#changeProduct"+count);
          // 	}
          // } // /for

          subAmount();
        }, // /success
      }); // /ajax function to fetch the product data
    }
  } else {
    alert("no row! please refresh the page");
  }
} // /select on product data

// table total
function getTotal(row = null) {
  if (row) {
    var total =
      Number($("#rate" + row).val()) * Number($("#quantity" + row).val());
    total = total.toFixed(2);
    $("#total" + row).val(total);
    $("#totalValue" + row).val(total);

    subAmount();
  } else {
    alert("no row !! please refresh the page");
  }
}

function subAmount() {
  var tableProductLength = $("#productTable tbody tr").length;
  var totalSubAmount = 0;
  for (x = 0; x < tableProductLength; x++) {
    var tr = $("#productTable tbody tr")[x];
    var count = $(tr).attr("id");
    count = count.substring(3);

    totalSubAmount = Number(totalSubAmount) + Number($("#total" + count).val());
  } // /for

  totalSubAmount = totalSubAmount.toFixed(2);

  // sub total
  $("#subTotal").val(totalSubAmount);
  $("#subTotalValue").val(totalSubAmount);

  // vat
  var vat = (Number($("#subTotal").val()) / 100) * 0;
  vat = vat.toFixed(2);
  $("#vat").val(vat);
  $("#vatValue").val(vat);

  // total amount
  var totalAmount = Number($("#subTotal").val()) + Number($("#vat").val());
  totalAmount = totalAmount.toFixed(2);
  $("#totalAmount").val(totalAmount);
  $("#totalAmountValue").val(totalAmount);

  var discount = $("#discount").val();
  if (discount) {
    var grandTotal = Number($("#totalAmount").val()) - Number(discount);
    grandTotal = grandTotal.toFixed(2);
    $("#grandTotal").val(grandTotal);
    $("#grandTotalValue").val(grandTotal);
  } else {
    $("#grandTotal").val(totalAmount);
    $("#grandTotalValue").val(totalAmount);
  } // /else discount

  var paidAmount = $("#paid").val();
  if (paidAmount) {
    paidAmount = Number($("#grandTotal").val()) - Number(paidAmount);
    paidAmount = paidAmount.toFixed(2);
    $("#due").val(paidAmount);
    $("#dueValue").val(paidAmount);
  } else {
    $("#due").val($("#grandTotal").val());
    $("#dueValue").val($("#grandTotal").val());
  } // else

  // Update Payment Status
  var dueValue = Number($("#due").val());
  dueValue = Number(dueValue.toFixed(2)); // Fix floating point precision
  if (dueValue <= 0) {
    $("#paymentStatus").val("1"); // Paid
    $("input[name='paymentStatus']").val("1");
  } else {
    $("#paymentStatus").val("3"); // Due
    $("input[name='paymentStatus']").val("3");
  }
} // /sub total amount

function discountFunc() {
  var discount = $("#discount").val();
  var totalAmount = Number($("#totalAmount").val());
  totalAmount = totalAmount.toFixed(2);

  var grandTotal;
  if (totalAmount) {
    grandTotal = Number($("#totalAmount").val()) - Number($("#discount").val());
    grandTotal = grandTotal.toFixed(2);

    $("#grandTotal").val(grandTotal);
    $("#grandTotalValue").val(grandTotal);
  } else {
  }

  var paid = $("#paid").val();

  var dueAmount;
  if (paid) {
    dueAmount = Number($("#grandTotal").val()) - Number($("#paid").val());
    dueAmount = dueAmount.toFixed(2);

    $("#due").val(dueAmount);
    $("#dueValue").val(dueAmount);
  } else {
    $("#due").val($("#grandTotal").val());
    $("#dueValue").val($("#grandTotal").val());
  }

  // Update Payment Status
  var dueValue = Number($("#due").val());
  dueValue = Number(dueValue.toFixed(2)); // Fix floating point precision
  if (dueValue <= 0) {
    $("#paymentStatus").val("1"); // Paid
    $("input[name='paymentStatus']").val("1");
  } else {
    $("#paymentStatus").val("3"); // Due
    $("input[name='paymentStatus']").val("3");
  }
} // /discount function

function paidAmount() {
  var grandTotal = $("#grandTotal").val();
  var paid = $("#paid").val();

  if (grandTotal) {
    // If paid field is empty, treat it as 0
    var paidVal = paid ? Number(paid) : 0;
    var grandTotalVal = Number(grandTotal);

    var dueValue = grandTotalVal - paidVal;
    dueValue = dueValue.toFixed(2);

    // Set due values
    $("#due").val(dueValue);
    $("#dueValue").val(dueValue);

    // Auto set payment status based on due value
    // Fix floating point precision for comparison
    var dueNum = Number(dueValue);

    if (dueNum <= 0) {
      $("#paymentStatus").val("1"); // Paid
      $("input[name='paymentStatus']").val("1"); // Update hidden input
    } else {
      $("#paymentStatus").val("3"); // Due
      $("input[name='paymentStatus']").val("3"); // Update hidden input
    }
  }
}

function resetOrderForm() {
  // reset the input field
  $("#createOrderForm")[0].reset();
  // remove remove text danger
  $(".text-danger").remove();
  // remove form group error
  $(".form-group").removeClass("has-success").removeClass("has-error");
} // /reset order form

// remove order from server
function removeOrder(orderId = null) {
  if (orderId) {
    $("#removeOrderBtn")
      .unbind("click")
      .bind("click", function () {
        $("#removeOrderBtn").button("loading");

        $.ajax({
          url: "php_action/removeOrder.php",
          type: "post",
          data: { orderId: orderId },
          dataType: "json",
          success: function (response) {
            $("#removeOrderBtn").button("reset");

            if (response.success == true) {
              manageOrderTable.ajax.reload(null, false);
              // hide modal
              $("#removeOrderModal").modal("hide");
              // success messages
              $("#success-messages").html(
                '<div class="alert alert-success">' +
                  '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                  '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> ' +
                  response.messages +
                  "</div>",
              );

              // remove the mesages
              $(".alert-success")
                .delay(500)
                .show(10, function () {
                  $(this)
                    .delay(3000)
                    .hide(10, function () {
                      $(this).remove();
                    });
                }); // /.alert
            } else {
              // error messages
              $(".removeOrderMessages").html(
                '<div class="alert alert-warning">' +
                  '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                  '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> ' +
                  response.messages +
                  "</div>",
              );

              // remove the mesages
              $(".alert-success")
                .delay(500)
                .show(10, function () {
                  $(this)
                    .delay(3000)
                    .hide(10, function () {
                      $(this).remove();
                    });
                }); // /.alert
            } // /else
          }, // /success
        }); // /ajax function to remove the order
      }); // /remove order button clicked
  } else {
    alert("error! refresh the page again");
  }
}
// /remove order from server

// ==================== RETURN ORDER FUNCTIONALITY ====================

// Open return modal and load order items
function openReturnModal(orderId) {
  if (!orderId) {
    alert("অবৈধ অর্ডার আইডি");
    return;
  }

  // Clear previous data
  $("#returnOrderId").text(orderId);
  $("#returnClientName").text("");
  $("#returnProductTableBody").html("");
  $("#totalReturnAmount").text("০ টাকা");
  $(".returnOrderMessages").html("");

  // Fetch order details
  $.ajax({
    url: "php_action/fetchOrderItems.php",
    type: "POST",
    data: { orderId: orderId },
    dataType: "json",
    success: function (response) {
      console.log("Return modal response:", response); // Debug log

      if (response.success) {
        // Set client name
        $("#returnClientName").text(response.client_name);

        // Calculate previous total return amount
        var prevTotalAmount = 0;
        $.each(response.items, function (index, item) {
          prevTotalAmount +=
            parseFloat(item.returned_quantity) * parseFloat(item.rate);
        });
        $("#prevReturnAmount").text(prevTotalAmount.toFixed(2) + " টাকা");

        // Build product table rows
        var tableRows = "";
        $.each(response.items, function (index, item) {
          tableRows +=
            "<tr>" +
            "<td><strong>" +
            item.product_name +
            "</strong><br/>" +
            '<small class="text-muted">ব্র্যান্ড: ' +
            item.brand_name +
            "</small></td>" +
            '<td class="text-right">' +
            parseFloat(item.rate).toFixed(2) +
            " টাকা</td>" +
            '<td class="text-center">' +
            parseFloat(item.original_quantity).toFixed(2) +
            " " +
            item.clor +
            "</td>" +
            '<td class="text-center" style="color: #dc3545;">' +
            parseFloat(item.returned_quantity).toFixed(2) +
            " " +
            item.clor +
            "</td>" +
            '<td class="text-center" style="font-weight: bold; color: #28a745;">' +
            parseFloat(item.available_quantity).toFixed(2) +
            " " +
            item.clor +
            "</td>" +
            '<td class="text-right">' +
            parseFloat(item.total).toFixed(2) +
            " টাকা</td>" +
            "<td>" +
            '<input type="number" class="form-control return-quantity" ' +
            'data-order-item-id="' +
            item.order_item_id +
            '" ' +
            'data-product-id="' +
            item.product_id +
            '" ' +
            'data-rate="' +
            item.rate +
            '" ' +
            'data-max-quantity="' +
            item.quantity +
            '" ' +
            'min="0" max="' +
            item.quantity +
            '" step="any" value="0" ' +
            'placeholder="ফেরতের পরিমাণ">' +
            "</td>" +
            "</tr>";
        });

        $("#returnProductTableBody").html(tableRows);

        // Add event listeners for return quantity inputs
        $(".return-quantity").on("input", calculateTotalReturnAmount);

        // Show modal
        $("#returnOrderModal").modal("show");
      } else {
        console.error("Return modal error:", response.message); // Debug log
        alert(response.message || "অর্ডার আইটেম লোড করতে ব্যর্থ");
      }
    },
    error: function (xhr, status, error) {
      console.error("AJAX error:", status, error, xhr.responseText); // Debug log
      alert("সার্ভার ত্রুটি! অনুগ্রহ করে আবার চেষ্টা করুন।");
    },
  });
}

// Calculate total return amount
function calculateTotalReturnAmount() {
  var totalAmount = 0;

  $(".return-quantity").each(function () {
    var inputVal = $(this).val();

    // Handle decimal inputs that start with a dot (e.g., ".5")
    if (inputVal && inputVal.charAt(0) === ".") {
      inputVal = "0" + inputVal;
      $(this).val(inputVal);
    }

    var quantity = parseFloat(inputVal);
    // Check for NaN or invalid input
    if (isNaN(quantity) || quantity === null || quantity === undefined) {
      quantity = 0;
    }

    var rate = parseFloat($(this).data("rate")) || 0;
    var maxQuantity = parseFloat($(this).data("max-quantity")) || 0;

    // Validate quantity
    if (quantity > maxQuantity) {
      $(this).val(maxQuantity);
      quantity = maxQuantity;
    }

    if (quantity < 0) {
      $(this).val(0);
      quantity = 0;
    }

    totalAmount += quantity * rate;
  });

  $("#totalReturnAmount").text(totalAmount.toFixed(2) + " টাকা");
}

// Process return button click
$("#processReturnBtn").on("click", function () {
  var orderId = $("#returnOrderId").text();
  var returns = [];
  var hasReturns = false;

  // Collect return data
  $(".return-quantity").each(function () {
    var quantity = parseFloat($(this).val()) || 0;
    if (quantity > 0) {
      hasReturns = true;
      returns.push({
        order_item_id: $(this).data("order-item-id"),
        product_id: $(this).data("product-id"),
        return_quantity: quantity,
      });
    }
  });

  // Validate
  if (!hasReturns) {
    $(".returnOrderMessages").html(
      '<div class="alert alert-warning">' +
        '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
        "অনুগ্রহ করে অন্তত একটি পণ্যের ফেরতের পরিমাণ লিখুন" +
        "</div>",
    );
    return;
  }

  // Disable button and show loading
  $("#processReturnBtn").prop("disabled", true).text("প্রক্রিয়াকরণ হচ্ছে...");

  // Submit return
  $.ajax({
    url: "php_action/process_return.php",
    type: "POST",
    data: {
      order_id: orderId,
      returns: returns,
    },
    dataType: "json",
    success: function (response) {
      $("#processReturnBtn").prop("disabled", false).text("ফেরত সম্পন্ন করুন");

      if (response.success) {
        $(".returnOrderMessages").html(
          '<div class="alert alert-success">' +
            '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
            '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> ' +
            response.message +
            "<br/>মোট ফেরতের পরিমাণ: " +
            response.return_amount.toFixed(2) +
            " টাকা" +
            "</div>",
        );

        // Refresh the order table
        if (typeof manageOrderTable !== "undefined") {
          manageOrderTable.ajax.reload(null, false);
        }

        // Close modal after 2 seconds
        setTimeout(function () {
          $("#returnOrderModal").modal("hide");
        }, 2000);
      } else {
        $(".returnOrderMessages").html(
          '<div class="alert alert-danger">' +
            '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
            '<strong><i class="glyphicon glyphicon-remove-sign"></i></strong> ' +
            response.message +
            "</div>",
        );
      }
    },
    error: function () {
      $("#processReturnBtn").prop("disabled", false).text("ফেরত সম্পন্ন করুন");
      $(".returnOrderMessages").html(
        '<div class="alert alert-danger">' +
          '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
          "সার্ভার ত্রুটি! অনুগ্রহ করে আবার চেষ্টা করুন।" +
          "</div>",
      );
    },
  });
});
