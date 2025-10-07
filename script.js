(function () {
    const tabs = Array.from(document.querySelectorAll('.tab'));
    const contentArea = document.getElementById('content-area');
    const sidebar = document.querySelector('.sidebar');
    const validHashes = tabs.map(t => t.dataset.hash);

    function activateTab(tabEl, pushHash = true) {
        if (!tabEl) return;

        const isAlreadyActive = tabEl.classList.contains('active');
        const isInicio = tabEl.dataset.hash === 'inicio';

        if (isAlreadyActive && !isInicio) {
            tabs.forEach(t => t.classList.remove('active'));
            sidebar.classList.remove('hide');
            sidebar.classList.add('show');
            contentArea.innerHTML = `
                <h1>Bem-vindo</h1>
                <p>Selecione uma aba ao lado para visualizar o conteúdo.</p>
            `;
            if (pushHash) {
                window.location.href = 'index.php';
            }
            return;
        }

        tabs.forEach(t => t.classList.remove('active'));
        tabEl.classList.add('active');

        if (!isInicio) {
            sidebar.classList.remove('show');
            sidebar.classList.add('hide');
        } else {
            sidebar.classList.remove('hide');
            sidebar.classList.add('show');
        }

        setTimeout(() => {
            const target = tabEl.dataset.target;
            const pasta = tabEl.dataset.pasta;

            fetch(`ajax_handler.php?pasta=${encodeURIComponent(pasta)}&target=${encodeURIComponent(target)}`)
                .then(res => res.text())
                .then(html => {
                    contentArea.style.opacity = 0;
                    setTimeout(() => {
                        contentArea.innerHTML = html;
                        contentArea.style.opacity = 1;
                    }, 2000);
                });

            if (pushHash) {
                const newHash = tabEl.dataset.hash;
                if (window.location.hash !== '#' + newHash) {
                    history.pushState(null, '', '#' + newHash);
                }
            }
        }, 1); // ← esse fechamento estava fora de lugar
    }

    // clique e teclado
    tabs.forEach(tab => {
        tab.addEventListener('click', () => activateTab(tab));
        tab.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                activateTab(tab);
            }
        });
    });

    // back/forward
    window.addEventListener('hashchange', () => {
        setActiveByHash(window.location.hash);
    });

    // ao carregar
    document.addEventListener('DOMContentLoaded', () => {
        setActiveByHash(window.location.hash || '#inicio');
    });
})();
