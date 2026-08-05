import './bootstrap';
import './theme';
import './ocr';


document.addEventListener('DOMContentLoaded', () => {


    const themeToggle = document.getElementById('theme-toggle');


    const applyTheme = (theme) => {


        document.body.classList.remove(
            'light',
            'light-mode'
        );


        if(theme === 'light'){

            document.body.classList.add(
                'light',
                'light-mode'
            );

        }


        document.documentElement.setAttribute(
            'data-theme',
            theme
        );


        localStorage.setItem(
            'theme',
            theme
        );


    };



    // Cargar tema guardado

    const savedTheme = localStorage.getItem('theme') || 'dark';


    applyTheme(savedTheme);



    // Botón cambiar tema

    themeToggle?.addEventListener('click',()=>{


        const currentTheme =
            document.body.classList.contains('light')
            ? 'dark'
            : 'light';



        applyTheme(currentTheme);


    });





    // ==========================
    // TAMAÑO DE LETRA
    // ==========================


    const sizeButtons =
        document.querySelectorAll('.size-btn');



    const applyFontSize = (size)=>{


        document.body.classList.remove(
            'small-font',
            'large-font'
        );


        if(size === 'small'){

            document.body.classList.add(
                'small-font'
            );

        }


        if(size === 'large'){

            document.body.classList.add(
                'large-font'
            );

        }



        sizeButtons.forEach(button=>{


            button.classList.toggle(
                'active',
                button.dataset.size === size
            );


        });


    };



    const savedFont =
        localStorage.getItem('font-size') || 'normal';



    applyFontSize(savedFont);



    sizeButtons.forEach(button=>{


        button.addEventListener('click',()=>{


            const size =
            button.dataset.size || 'normal';


            applyFontSize(size);


            localStorage.setItem(
                'font-size',
                size
            );


        });


    });


});
document.addEventListener("DOMContentLoaded",()=>{


const button = document.getElementById("profile-toggle");

const menu = document.getElementById("profile-menu");


if(button){

    button.onclick = ()=>{

        menu.classList.toggle("active");

    };

}


});
