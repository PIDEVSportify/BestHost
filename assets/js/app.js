import '../css/app.css';

import '@fortawesome/fontawesome-free/css/all.min.css';

import 'bootstrap';
import './components/deleteBtn';
import './components/submitBtn';
import './components/toast';
$(document).ready(function() {
    $('nav.menu a').click(function() {
        $(this).parent().find('.current').removeClass('current');
        $(this).addClass('current');
    });
    $('a[data-connect]').click(function() {
        var i = $(this).find('i');
        i.hasClass('icon-collapse-top') ? i.removeClass('icon-collapse-top').addClass('icon-collapse') : i.removeClass('icon-collapse').addClass('icon-collapse-top');
        $(this).parent().parent().toggleClass('all').next().slideToggle();
    });
    $(window).scroll(function() {
        var w = $(window).width();
        if (w < 768) {
            $('#top-button').hide();
        } else {
            var e = $(window).scrollTop();
            e > 150 ? $('#top-button').fadeIn() : $('#top-button').fadeOut();
        }
    });
});