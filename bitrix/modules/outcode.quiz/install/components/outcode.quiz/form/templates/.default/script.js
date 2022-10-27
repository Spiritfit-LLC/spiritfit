jQuery(function($) {
    $(document).ready(function() {
        $('.open-share-block').unbind();
        $('.open-share-block').click(function(e) {
            e.preventDefault();
            let wrapper = $(this).parents('.share-wrap').eq(0).find('.share');
            $(wrapper).toggle('opened');
        });
        $('.share__link').unbind();
        $('.share__link').click(function(e) {
            e.preventDefault();

            var block = $(this).parents('.share').eq(0);
            if ( $(this).hasClass('share-copy') ) {
                MyQuiz.Share.copyUrl( window.location.origin + $(this).data('url') );
            } else {
                var type = $(this).attr("href").replace("#", "");
                var url = window.location.origin + $(this).data('url');
                var title = $(this).data('title');
                var description = $(this).data('description');

                var image = "";
                if ($(".mySwiper2 img").length > 0) {
                    image = window.location.origin + $(".mySwiper2 img").eq(0).attr("src");
                }

                switch (type) {
                    case "vk":
                        MyQuiz.Share.vk(url, title, image);
                        break;
                    case "fb":
                        MyQuiz.Share.fb(url, title, image, description);
                        break;
                    case "tg":
                        MyQuiz.Share.tg(url, title);
                        break;
                    case "od":
                        MyQuiz.Share.od(url, title, image);
                        break;
                    case "tw":
                        MyQuiz.Share.tw(url, title);
                        break;
                    case "whatsapp":
                        MyQuiz.Share.whatsapp(url);
                        break;
                    case "viber":
                        MyQuiz.Share.viber(url);
                        break;
                }
            }

            $(block).removeClass("open");
            $(block).hide();
        });

        $('.question-form').unbind()
        $('.question-form').submit(function(e) {
            e.preventDefault();

            var form = new FormData( $(this)[0] );
            var formObj = $(this);

            $(formObj).find('.question-form__error').text('');

            BX.ajax.runComponentAction(quizComponentName, 'sendAnswer', {
                mode: 'class',
                data: form,
                method:'POST'
            }).then(function (response) {
                if( response.data.error != '' ) {
                    $(formObj).find('.question-form__error').text(response.data.error);
                } else {
                    if( response.data.result.ID == 0 ) {
                        $(formObj).html('<div class="success">'+quizComponentExistMsg+' '+response.data.result.RESULT+'</div>');
                    } else {
                        $(formObj).html('<div class="success">'+quizComponentSuccessMsg+' '+response.data.result.RESULT+'</div>');
                    }
                }
            }, function (response) {
                $(formObj).find('.question-form__error').text(response.errors[0].message);
            });

            return false;
        });
    });

    var MyQuiz = MyQuiz || {};
    MyQuiz.Share = {
        whatsapp: function(purl) {
            var url = 'whatsapp://send?text=';
            if (purl) {
                url += encodeURIComponent(purl);
            }
            MyApp.Share.openWindow(url);
        },
        viber: function(purl) {
            var url = 'viber://forward?text=';
            if (purl) {
                url += encodeURIComponent(purl);
            }
            MyApp.Share.openWindow(url);
        },
        vk: function(purl, ptitle, pimg) {
            var url = 'http://vk.com/share.php?';
            if (purl) {
                url += 'url=' + encodeURIComponent(purl);
            }
            if (ptitle) {
                url += '&title=' + encodeURIComponent(ptitle);
            }
            if (this.pimg) {
                url += '&image=' + encodeURIComponent(pimg);
            }
            url += '&noparse=true';

            MyApp.Share.openWindow(url);
        },
        fb: function(purl, ptitle, pimg, text) {
            var url = 'https://www.fb.com/sharer.php?s=100';
            if (ptitle) {
                url += '&p[title]=' + encodeURIComponent(ptitle);
            }
            if (text) {
                url += '&p[summary]=' + encodeURIComponent(text);
            }
            if (purl) {
                url += '&p[url]=' + encodeURIComponent(purl);
            }
            if (pimg) {
                url += '&p[images][0]=' + encodeURIComponent(pimg);
            }

            MyApp.Share.openWindow(url);
        },
        tg: function(purl, ptitle) {
            var url = 'https://telegram.me/share/url?';
            if (purl) {
                url += 'url=' + encodeURIComponent(purl);
            }
            if (ptitle) {
                url += '&text=' + encodeURIComponent(ptitle);
            }

            MyApp.Share.openWindow(url);
        },
        od: function(purl, ptitle, pimg) {
            var url = 'https://connect.ok.ru/offer?';
            if (purl) {
                url += 'url=' + encodeURIComponent(purl);
            }
            if (ptitle) {
                url += '&title=' + encodeURIComponent(ptitle);
            }
            if (pimg) {
                url += '&imageUrl=' + encodeURIComponent(pimg);
            }

            MyApp.Share.openWindow(url);
        },
        tw: function(purl, ptitle) {

            var url = "https://twitter.com/intent/tweet?",

                MAX_LEN_TW = 140,
                content = ptitle,
                site_url = purl,
                index = MAX_LEN_TW;

            while ((content + site_url).length > MAX_LEN_TW) {
                index = content.lastIndexOf(' ', index - 1);
                if (index !== -1 && index - 4 - site_url.length <= MAX_LEN_TW) {
                    content = content.slice(0, index);
                    content += '... ';
                } else if (index === -1) {
                    content = '';
                }
            }
            if (purl) {
                url += "original_referer=" + encodeURIComponent(purl);
            }
            if (ptitle) {
                url += "&text=" + encodeURIComponent(content);
            }
            url += "&tw_p=tweetbutton";
            if (purl) {
                url += "&url=" + encodeURIComponent(site_url);
            }

            MyApp.Share.openWindow(url);
        },
        openWindow: function(url) {
            var maxWidth = 500;
            if ($(window).width() < 600) {
                maxWidth = $(window).width() - 40;
            }
            window.open(url, '', 'toolbar=0,status=0,width=' + maxWidth + ',height=436');
        },
        copyUrl: function( url ) {
            if (!window.getSelection) {
                return;
            }

            const dummy = document.createElement('p');
            dummy.textContent = url; //window.location.href;
            document.body.appendChild(dummy)

            const range = document.createRange();
            range.setStartBefore(dummy);
            range.setEndAfter(dummy)

            const selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);

            document.execCommand('copy');
            document.body.removeChild(dummy);
        }
    };
});