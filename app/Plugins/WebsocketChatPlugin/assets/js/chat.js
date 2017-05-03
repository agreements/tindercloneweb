$(document).ready(function() {
    var selector = '.ul_padding_left li';
    $(selector).on('click', function() {
        $(selector).removeClass('active');
        $(this).addClass('active');
    });
})
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
});
$(document).ready(function() {
    $('#all').click(function() {
        $('#all-col').show();
        $('#matches-col').hide();
        $('#online-col').hide();
        $('#star-col').hide();
    });
    $('#matches').click(function() {
        $('#all-col').hide();
        $('#matches-col').show();
        $('#online-col').hide();
        $('#star-col').hide();
    });
    $('#online').click(function() {
        $('#all-col').hide();
        $('#matches-col').hide();
        $('#online-col').show();
        $('#star-col').hide();
    });
    $('#star').click(function() {
        $('#all-col').hide();
        $('#matches-col').hide();
        $('#online-col').hide();
        $('#star-col').show();
    });
});
$(document).ready(function() {
    $('#left-first-div').click(function() {
        $("#first_img_div").show();
        $("#second_img_div").hide();
        $("#third_img_div").hide();
    })
    $('#left-second-div').click(function() {
        $("#first_img_div").hide();
        $("#second_img_div").show();
        $("#third_img_div").hide();
    })
    $('#left-third-div').click(function() {
        $("#first_img_div").hide();
        $("#second_img_div").hide();
        $("#third_img_div").show();
    })
    // $(document).ready(function() {
    //     $(document).ready(function() {
    //         $('#submit_chat').click(function() {
    //             var x = $('#chat_input').val();
    //             var y = $('<li>').addClass(
    //                 'display_block chat_li_style'
    //             ).html(x);
    //             y.appendTo('.chat_ul');
    //         });
    //     });
    // });
});