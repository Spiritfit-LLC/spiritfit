<div class="modal-overlay"></div>
<div class="personal-change-profile-photo">
    <div class="personal-change-profile-photo-header -modal-header">
        <div class="personal-change-profile-photo-title">
            <!--НУЖНО ХЕДЕР БРАТЬ ИЗ АДМИНКИ-->
            <span>Фотография профиля</span>
        </div>
        <div class="personal-change-profile-photo-closer">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M13 12l5-5-1-1-5 5-5-5-1 1 5 5-5 5 1 1 5-5 5 5 1-1z"/></svg>
        </div>
    </div>
    <div class="personal-change-profile-photo-body">
        <form class="personal-change-profile-photo-form" action="<?=$arResult['ajax']?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="ACTION" value="UPDATE_PERSONAL_PHOTO">
            <input type="hidden" name="old-photo-id" value="<?=$arResult['LK_FIELDS']['OLD_PHOTO_ID']?>">
            <input class="personal-change-profile-photo-file-input" type="file" name="new-photo-file" onchange="changeProfilePhoto(this);" accept="image/*">

            <input type="text" disabled placeholder="Файл" class="input input--light input--text is-hide-mobile" id="new-profile-photo-filename">
            <span class="input button-outline -modal-btn" onclick="$('.personal-change-profile-photo-file-input').trigger('click')">ВЫБРАТЬ ФОТО</span>
        </form>
        <div class="personal-change-profile-photo-photo">
            <div class="personal-profile-photo">
                <img src="<?=$arResult['LK_FIELDS']['PERSONAL_PHOTO']?>" height="400" width="400" class="profile-personal-photo">
                <div class="personal-change-profile-photo-preloader">
                    <div class="preloader__row">
                        <div class="preloader__item"></div>
                        <div class="preloader__item"></div>
                    </div>
                </div>
            </div>
        </div>
        <span class="error-message-text"></span>
    </div>
</div>

<script>
    function changeProfilePhoto(target){
        var url=$(target.form).attr('action');
        var postData=$(target.form).serializeArray();

        var form_data = new FormData();
        var file = target.files[0];
        form_data.append('new-photo-file', file);
        for(var i=0; i<postData.length; i++){
            form_data.append(postData[i].name, postData[i].value);
        }
        $.ajax({
            url:url,
            method:'POST',
            data:form_data,
            processData: false,
            contentType: false,
            enctype: 'multipart/form-data',
            success:function(res){
                var success=res['result'];
                var message=res['message'];

                if (success){
                    //ЧИТАЕМ ПАРАМЕТРЫ ЗАПРОСА
                    var params = window
                        .location
                        .search
                        .replace('?','')
                        .split('&')
                        .reduce(
                            function(p,e){
                                var a = e.split('=');
                                p[ decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
                                return p;
                            },
                            {}
                        );
                    if (!Object.keys(params).includes('profile') && !Object.keys(params).includes('change')) {
                        var url = new URL(window.location.href);
                        url.searchParams.append('profile', 'Y');
                        window.location.href=url.href;
                    }
                    else{
                        window.location.reload();
                    }
                }
                else{
                    $('.personal-change-profile-photo-body .error-message-text').html(message);
                }
            },
            error:function(){
                $('.personal-change-profile-photo-body .error-message-text').html('Не удалось связаться с сервером');
            }
        });

    }
    $(document).ready(function(){

        //Изменение фотографии профиля
        $('.change-profile-photo-btn span').click(function(){
            $('.modal-overlay').addClass('active');
            $('.personal-change-profile-photo').addClass('active');
        });
        function closeChangePhotoModal(){
            $('.modal-overlay').removeClass('active');
            $('.personal-change-profile-photo').removeClass('active');
        }
        $('.personal-change-profile-photo-closer').click(closeChangePhotoModal);
        $('.modal-overlay').click(closeChangePhotoModal);
    })
</script>