function onYouTubeIframeAPIReady() {
    var dataIdVideo = $('#player').attr('data-id-video')
    player = new YT.Player('player', {
      width: '1200px',
      height: '640px',
      videoId: dataIdVideo,
  });
}
  



