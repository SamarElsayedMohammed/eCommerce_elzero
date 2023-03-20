$(function () {
    'use strict';

    // switch between login and sign up

    $('.login-page h1 span').click(function () {
        $(this).addClass('selected').siblings().removeClass('selected');
        $('.login-page form').hide();
        $('.' + $(this).data('class')).fadeIn(100);
    });


    // Calls the selectBoxIt method on your HTML select box
    $("select").selectBoxIt({

        // Uses the Twitter Bootstrap theme for the drop down
        // theme: "bootstrap",
        autoWidth: false

    });

    $('[placeholder]').focus(function () {
        $(this).attr('data-text', $(this).attr('placeholder'));
        $(this).attr('placeholder', '');
    }).blur(function () {
        $(this).attr('placeholder', $(this).attr('data-text'));
    });

    // add * on required fieds

    $('input').each(function () {


        if ($(this).attr('required') === 'required') {

            $(this).after('<span class="asterisk">*</span>');

        }

    });

    //show password when hover on eye icon
    var passField = $('.password');
    $('.show-pass').hover(function () {
        passField.attr('type', 'text');
    }, function () {
        passField.attr('type', 'password');
    });

    //confirmation message to delete

    $('.confirm').click(function () {
        return confirm('are you sure');

    });
    // $('.live-name').keyup(function () {
    //     $('.live-preview .caption h3').text($(this).val());
    // });

    // $('.live-desc').keyup(function () {
    //     $('.live-preview .caption p').text($(this).val());
    // });

    // $('.live-price').keyup(function () {
    //     $('.live-preview .price-tag').text('$'+$(this).val());
    // });

    $('.live').keyup(function () {
        $($(this).data('class')).text($(this).val());
    });


});