;(function(){'use strict';window.cookieAllowAccess===void 0&&(window.cookieAllowAccess=function(a){var b='cookieUseAccess=',c=a,d=0<=document.cookie.split('; ').indexOf(b),e=function(){var a=document.getElementsByTagName('body')[0],e=document.createElement('link');e.href=c.cssFile,e.type='text/css',e.rel='stylesheet',a.appendChild(e);var f=document.createElement('div');f.id='cookiePopUp',f.classList.add('cookiePopUp-'+c.theme,'cookiePopUp'),f.innerHTML=c.text,f.b=document.createElement('button'),f.b.innerText=c.buttonText;var g=new Date;g.setDate(g.getDate()+c.expires),f.b.addEventListener('click',function(){document.cookie=b+'; expires='+g.toUTCString()+'; path=/',a.removeChild(f)}),f.appendChild(f.b),a.appendChild(f)};d||window.addEventListener('load',function(){e()})})})(),cookieAllowAccess({theme:'dark',buttonText:'Ok',text:`<span>Мы используем файлы cookie для сбора и анализа информации о производительности и использовании сайта, а также для улучшения и индивидуальной настройки предоставления информации. Продолжая пользоваться сайтом, вы соглашаетесь с <a href="https://www.wonder.legal/ru/creation-modele/%D0%BF%D0%BE%D0%BB%D0%B8%D1%82%D0%B8%D0%BA%D0%B0-%D0%B8%D1%81%D0%BF%D0%BE%D0%BB%D1%8C%D0%B7%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D1%8F-%D0%BA%D1%83%D0%BA%D0%B8-%D1%84%D0%B0%D0%B9%D0%BB%D0%BE%D0%B2" target="_blank">Политикой использования</a>.</span>`,expires:30,cssFile:'/local/templates/spiritfit-corp/css/cookieAllowAccess.min.css'});