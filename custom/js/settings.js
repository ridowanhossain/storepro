$(document).ready(function() {
    $("#shopSettingsForm").unbind('submit').bind('submit', function() {
        var form = $(this);

        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: form.serialize(),
            dataType: 'json',
            success:function(response) {
                if(response.success == true) {
                    $(".shopSettingsMessages").html('<div class="alert alert-success">'+
                        '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                        '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> '+ response.messages +
                        '</div>');
                } else {
                    $(".shopSettingsMessages").html('<div class="alert alert-warning">'+
                        '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                        '<strong><i class="glyphicon glyphicon-exclamation-sign"></i></strong> '+ response.messages +
                        '</div>');
                }
            }
        });

        return false;
    });
});