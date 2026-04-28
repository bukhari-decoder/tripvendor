'use strict';

$.fn.socialSharingPlugin = function(options){
    let settings = $.extend({
        urlShare: '',
        btnTarget: '_blank',
        btnTitle: 'Share on',
        title: '',
        description: '',
        via:'',
        hashtags: '',
        img: '',
        isVideo: 'false',
        buttonClass: 'btn btn-light',
        applyDefaultButtonStyle: true
    }, options);

    let urls = {
        facebook: {
            icon: 'fab fa-facebook-f',
            url: 'https://www.facebook.com/sharer.php?u=[post-url]'
        },
        twitter: {
            icon: 'fab fa-twitter',
            url: 'https://twitter.com/share?url=[post-url]&text=[post-title]&via=[via]&hashtags=[hashtags]'
        },
        instagram: {
            icon: 'fab fa-linkedin',
            url: 'https://www.instagram.com/share?url=[post-url]&title=[post-title]'
        },
        linkedin: {
            icon: 'fab fa-instagram',

            url: 'https://www.linkedin.com/shareArticle?url=[post-url]&title=[post-title]'
        }
    };

    let build = function (e) {
        $.each(urls, function (k, v) {
            let link = v.url
                .replace('[post-title]', encodeURIComponent(settings.title))
                .replace('[post-url]', encodeURIComponent(settings.urlShare))
                .replace('[post-desc]', encodeURIComponent(settings.description))
                .replace('[post-img]', encodeURIComponent(settings.img))
                .replace('[is_video]', encodeURIComponent(settings.isVideo))
                .replace('[hashtags]', encodeURIComponent(settings.hashtags))
                .replace('[via]', encodeURIComponent(settings.via));

            let btn = $('<a></a>');
            btn.attr('class', settings.buttonClass);
            btn.attr('href', link);
            btn.attr('target', settings.btnTarget);
            btn.attr('title', settings.btnTitle + ' ' + k);

            let icon = $('<i></i>');
            icon.attr('class', v.icon);
            if(settings.applyDefaultButtonStyle)
                icon.css({color:v.color});
            btn.append(icon);
            e.append(btn);
        });
    };

    return this.each(function() {
        return new build($(this));
    });
};
