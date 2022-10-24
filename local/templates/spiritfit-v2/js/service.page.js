$(document).ready(function(){
    try{
        if (typeof owlOtions !== "undefined"){
            $('.owl-carousel').owlCarousel(owlOtions);
        }
        else{
            $('.owl-carousel').owlCarousel(
                {
                    loop:true,
                    margin:10,
                    nav:true,
                    items:1,
                    dots:false,
                    // navText: [
                    //     '<svg width="35" height="35" viewBox="0 0 64 64"><path d="M45.69,62.84,16,33.15a1.6,1.6,0,0,1,0-2.3L45.69,1.16a1.59,1.59,0,0,1,2.31,0,1.61,1.61,0,0,1,0,2.31L19.47,32,48,60.53a1.61,1.61,0,0,1,0,2.31,1.61,1.61,0,0,1-2.31,0Z" transform="translate(-15.51 -0.67)"/></svg>',
                    //     '<svg width="35" height="35" viewBox="0 0 64 64"><path d="M17.16,63.33A1.5,1.5,0,0,1,16,62.84a1.61,1.61,0,0,1,0-2.31L44.53,32,16,3.47a1.61,1.61,0,0,1,0-2.31,1.6,1.6,0,0,1,2.31,0L48,30.85a1.6,1.6,0,0,1,0,2.3L18.31,62.84A1.49,1.49,0,0,1,17.16,63.33Z" transform="translate(-15.51 -0.67)"/></svg>'
                    // ],
                    navText:false,
                    autoplay:true,

                }
            );
        }
    }
    catch (e) {
        console.log(e)
    }
})