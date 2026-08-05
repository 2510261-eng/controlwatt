document.addEventListener('DOMContentLoaded', () => {

    const themeToggle = document.getElementById('theme-toggle');

    const applyTheme = (isLight) => {

        document.body.classList.toggle('light', isLight);

        document.body.classList.toggle('light-mode', isLight);

        document.documentElement.setAttribute('data-theme', isLight ? 'light' : 'dark');

        localStorage.setItem('theme', isLight ? 'light' : 'dark');

    };

    const savedTheme = localStorage.getItem('theme');

    applyTheme(savedTheme === 'light');

    themeToggle?.addEventListener('click', () => {

        const currentlyLight = document.body.classList.contains('light') || document.body.classList.contains('light-mode');

        applyTheme(!currentlyLight);

    });

    document.querySelectorAll('.size-btn').forEach((button) => {

        button.addEventListener('click', () => {

            const size = button.dataset.size;

            document.body.classList.remove('small-font', 'large-font');

            if (size === 'small') {

                document.body.classList.add('small-font');

            } else if (size === 'large') {

                document.body.classList.add('large-font');

            }

            localStorage.setItem('font', size);

        });

    });

});
