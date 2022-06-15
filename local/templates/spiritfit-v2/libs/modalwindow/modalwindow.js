//***************Работа с диалоговыми окнами*******************//

var AnimationsTypes={
    'fadeIn':'Fade',
    'stickyUp':'Sticky',
    'justMe':'JustMe',
    'slideIn':'Slide'
}

class ModalWindow{
    constructor(headertext, content, animation, clickable_overlay=true, closer=true) {
        this.modal=null;
        this.header=null;
        this.closer=null;
        this.view=null;
        this.onClose=null;


        this.className='dlg-modal'

        this.content=content;

        this.status=false;
        this.animation_type=animation;

        this.overlay=document.createElement('div');
        if (this.animation_type==AnimationsTypes['justMe']){
            this.overlay.id="full_overlay"
        }
        else{
            this.overlay.className='overlay'
        }

        this.dlg_formed(headertext, closer)



        if (clickable_overlay){
            this.overlay.addEventListener('click', ()=>{
                this.close()
            })
        }
    }

    change_header_text(header_text){
        this.header.innerText=header_text
    }
    change_content(content){
        var dlg_content=document.querySelector('.dlg-content');
        dlg_content.removeChild(dlg_content.lastElementChild)
        dlg_content.appendChild(content)
    }

    close(){
        if (this.status==true){
            if (this.animation_type==AnimationsTypes['justMe']){
                this.modal.className=this.animation_type+'Out';
            }
            else{
                this.modal.className=this.className+' '+this.animation_type+'Out';
            }
            this.status=false;
        }
        if (this.onClose!=null){
            this.onClose();
        }
    }
    show(){
        if (this.status==false){
            if (this.animation_type==AnimationsTypes['justMe']){
                this.modal.className=this.animation_type+'In';
            }
            else{
                this.modal.className=this.className+' '+this.animation_type+'In';
                this.modal.style.top='calc(50% - '+ this.modal.offsetHeight/2+'px)'
            }
            this.status=true;
        }
    }

    set_overlay_background_color(color){
        this.overlay.style.background=color;
    }
    set_button_background_color_on_justme(color){
        if (this.animation_type==AnimationsTypes['justMe']){
            this.closer.style.background=color;
        }
    }

    get_all_modal_element(){
        return this.modal;
    }

    dlg_formed(headertext, closer){
        this.modal=document.createElement('div');
        if (this.animation_type==AnimationsTypes['justMe']){
            this.modal.className=this.animation_type+'Out';
            this.modal.id="dlg_modal"
        }
        else{
            this.modal.className=this.className+' '+this.animation_type+'Out';
        }


        this.view=document.createElement('div')
        if (this.animation_type==AnimationsTypes['justMe']){
            this.view.id='dlg_view'
        }
        else{
            this.view.className='dlg-view'
        }


        this.header=document.createElement('div');
        this.header.className='dlg-header';

        if (closer){
            if (this.animation_type!=AnimationsTypes['justMe']){
                this.closer=document.createElement('span');
                this.closer.className='dlg-close-btn';
                this.header.appendChild(this.closer);

            }
            else{
                this.closer=document.createElement('button')
                this.closer.className='close-btn'
                this.closer.innerText='Закрыть'
            }
        }


        var header_text=document.createElement('h3');
        header_text.className='dlg-header-text';
        header_text.innerText=headertext;

        this.header.appendChild(header_text);


        this.view.appendChild(this.header);

        var dlgcontent=document.createElement('div')
        dlgcontent.className='dlg-content'
        dlgcontent.appendChild(this.content)


        this.view.appendChild(dlgcontent);
        if (closer){
            if (this.animation_type==AnimationsTypes['justMe']){
                this.view.appendChild(this.closer)
            }
            this.closer.addEventListener('click', ()=>{
                this.close();
            })
        }

        this.modal.appendChild(this.view)
        document.body.appendChild(this.modal);
        document.body.appendChild(this.overlay);
    }

    set_on_Close(func){
        this.onClose=func;
    }

    // set_overlay_clickable(clickable=true){
    //     var clonedElement = this.overlay.cloneNode(true);
    //     this.overlay.replaceWith(clonedElement);
    //     this.overlay=document.getElementsByClassName('overlay')[0]
    //
    //     if (clickable===true){
    //         console.log(clickable, this.overlay)
    //         this.overlay.addEventListener('click', ()=>{
    //             this.close()
    //         });
    //     }
    // }
}
