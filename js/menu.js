let menu_button = document.querySelector('._menu_button');
menu_button.addEventListener('click', function(){
    let fa = document.querySelectorAll('.fa');
    for(let i=0; i<fa.length; i++){
        fa[i].classList.toggle('rotate');
    }
    let menu = document.querySelector('.menu_nav');
    menu.classList.toggle('_show');
})
