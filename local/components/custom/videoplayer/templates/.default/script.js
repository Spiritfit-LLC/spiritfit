$(document).ready(function(){

    var component=$("#"+component_id);
    var video = component.find('video');            // Получаем элемент video
    var videoTrack = component.find(".video-track"); // Получаем элемент Видеодорожки
    var time = component.find(".timeline");          // Получаем элемент времени видео
    var btnPlayStop = component.find(".start-stop");           // Получаем кнопку проигрывания
    var btnRewind = component.find(".rewind");       // Получаем кнопки перемотки назад
    var btnForward = component.find(".forward");     // Получаем кнопку перемотки вперёд
    var controls=component.find(".buttons");


    function StartStopVideo(){
        if (component.data('status')==='stop'){
            video.trigger('play');
            videoPlay = setInterval(function() {
                let videoTime = Math.round(video.get(0).currentTime)
                let videoLength = Math.round(video.get(0).duration)
                time.css({
                    'width':(videoTime * 100) / videoLength + '%'
                });
            }, 10)
            component.data('status', 'play');
            component.removeClass('stop');
            component.addClass('play');
            setTimeout(function(){
                controls.fadeOut(300)
            }, 3000);
        }
        else{
            video.trigger('pause'); // Останавливает воспроизведение
            clearInterval(videoPlay) // убирает работу интервала

            component.removeClass('play');
            component.addClass('stop');

            component.data('status', 'stop');
            controls.fadeIn(300);

        }
    }


    btnPlayStop.click(StartStopVideo);
    video.click(StartStopVideo);

    // Нажимаем на кнопку перемотать назад
    btnRewind.on("click", function() {
        video.get(0).currentTime -= 5; // Уменьшаем время на пять секунд
    });

    // Нажимаем на кнопку перемотать вперёд
    btnForward.on("click", function() {
        video.get(0).currentTime += 5; // Увеличиваем время на пять секунд
    });


    videoTrack.on("click", function(e) {
        let posX = e.clientX - 8; // Вычисляем позицию нажатия
        let timePos = (posX * 100) / $(this).width(); // Вычисляем процент перемотки
        time.get(0).style.width = timePos + '%'; // Присваиваем процент перемотки
        video.get(0).currentTime = (timePos * Math.round(video.get(0).duration)) / 100 // Перематываем
    });

    $('.videoPlayer').mouseover(function(){
        controls.fadeIn(300);
    });

    $('.videoPlayer').mouseleave(function(){
        if (component.data('status')==='play'){
            setTimeout(function(){
                controls.fadeOut(300)
            }, 3000);
        }
    })
})