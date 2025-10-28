/**
 * ====================================================================
 * SISTEMA DE MELHORIAS - JAVASCRIPT
 * Funções auxiliares para usar as novas melhorias de UI/UX
 * ====================================================================
 */

// ====================================================================
// 1. SISTEMA DE TOASTS/NOTIFICAÇÕES
// ====================================================================

const Toast = {
    container: null,
    
    init() {
        if (!this.container) {
            this.container = document.createElement('div');
            this.container.className = 'toast-container';
            document.body.appendChild(this.container);
        }
    },
    
    show(type, title, message, duration = 5000) {
        this.init();
        
        const icons = {
            success: 'bi-check-circle-fill',
            error: 'bi-x-circle-fill',
            warning: 'bi-exclamation-triangle-fill',
            info: 'bi-info-circle-fill'
        };
        
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <i class="bi ${icons[type]} toast-icon"></i>
            <div class="toast-content">
                <div class="toast-title">${title}</div>
                ${message ? `<div class="toast-message">${message}</div>` : ''}
            </div>
            <button class="toast-close" onclick="Toast.close(this)">
                <i class="bi bi-x"></i>
            </button>
        `;
        
        this.container.appendChild(toast);
        
        if (duration > 0) {
            setTimeout(() => this.close(toast), duration);
        }
        
        return toast;
    },
    
    close(element) {
        const toast = element.classList ? element : element.closest('.toast');
        if (toast) {
            toast.classList.add('hiding');
            setTimeout(() => toast.remove(), 300);
        }
    },
    
    success(title, message, duration) {
        return this.show('success', title, message, duration);
    },
    
    error(title, message, duration) {
        return this.show('error', title, message, duration);
    },
    
    warning(title, message, duration) {
        return this.show('warning', title, message, duration);
    },
    
    info(title, message, duration) {
        return this.show('info', title, message, duration);
    }
};

// ====================================================================
// 2. LOADING OVERLAY
// ====================================================================

const Loading = {
    overlay: null,
    
    show(message = 'Carregando...') {
        if (!this.overlay) {
            this.overlay = document.createElement('div');
            this.overlay.className = 'loading-overlay';
            this.overlay.innerHTML = `
                <div>
                    <div class="loading-spinner"></div>
                    ${message ? `<p style="color: white; margin-top: 1rem; text-align: center;">${message}</p>` : ''}
                </div>
            `;
            document.body.appendChild(this.overlay);
        }
        this.overlay.style.display = 'flex';
    },
    
    hide() {
        if (this.overlay) {
            this.overlay.style.display = 'none';
        }
    }
};

// ====================================================================
// 3. BREADCRUMBS DINÂMICOS
// ====================================================================

const Breadcrumbs = {
    create(items) {
        const nav = document.createElement('nav');
        nav.setAttribute('aria-label', 'breadcrumb');
        
        const ol = document.createElement('ol');
        ol.className = 'breadcrumb';
        
        items.forEach((item, index) => {
            const li = document.createElement('li');
            li.className = 'breadcrumb-item';
            
            if (index === items.length - 1) {
                li.classList.add('active');
                li.setAttribute('aria-current', 'page');
                li.innerHTML = item.icon ? `<i class="bi ${item.icon}"></i> ${item.text}` : item.text;
            } else {
                const a = document.createElement('a');
                a.href = item.url;
                a.innerHTML = item.icon ? `<i class="bi ${item.icon}"></i> ${item.text}` : item.text;
                li.appendChild(a);
            }
            
            ol.appendChild(li);
        });
        
        nav.appendChild(ol);
        return nav;
    }
};

// ====================================================================
// 4. BUSCA COM CLEAR BUTTON
// ====================================================================

function initSearchBox(inputId) {
    const searchBox = document.getElementById(inputId);
    if (!searchBox) return;
    
    const wrapper = searchBox.parentElement;
    wrapper.classList.add('search-box');
    
    // Adicionar ícone de busca
    const searchIcon = document.createElement('i');
    searchIcon.className = 'bi bi-search search-icon';
    wrapper.insertBefore(searchIcon, searchBox);
    
    // Adicionar botão clear
    const clearBtn = document.createElement('button');
    clearBtn.className = 'clear-search';
    clearBtn.type = 'button';
    clearBtn.innerHTML = '<i class="bi bi-x-circle-fill"></i>';
    clearBtn.onclick = () => {
        searchBox.value = '';
        searchBox.dispatchEvent(new Event('input'));
        wrapper.classList.remove('has-value');
        searchBox.focus();
    };
    wrapper.appendChild(clearBtn);
    
    // Monitorar valor
    searchBox.addEventListener('input', () => {
        if (searchBox.value) {
            wrapper.classList.add('has-value');
        } else {
            wrapper.classList.remove('has-value');
        }
    });
    
    // Verificar valor inicial
    if (searchBox.value) {
        wrapper.classList.add('has-value');
    }
}

// ====================================================================
// 5. TOOLTIPS
// ====================================================================

function initTooltips() {
    document.querySelectorAll('[data-tooltip]').forEach(element => {
        const wrapper = document.createElement('span');
        wrapper.className = 'tooltip-wrapper';
        element.parentNode.insertBefore(wrapper, element);
        wrapper.appendChild(element);
        
        const tooltip = document.createElement('div');
        tooltip.className = 'tooltip-content';
        tooltip.textContent = element.getAttribute('data-tooltip');
        wrapper.appendChild(tooltip);
    });
}

// ====================================================================
// 6. AVATARES DE USUÁRIO
// ====================================================================

function createAvatar(name, size = 'md', online = false) {
    const div = document.createElement('div');
    div.className = `user-avatar size-${size}`;
    if (online) div.classList.add('online');
    
    // Pegar iniciais
    const initials = name.split(' ')
        .map(n => n[0])
        .join('')
        .toUpperCase()
        .substring(0, 2);
    
    div.textContent = initials;
    
    return div;
}

// ====================================================================
// 7. STATUS INDICATORS
// ====================================================================

function createStatusDot(type, pulse = false) {
    const span = document.createElement('span');
    span.className = `status-dot status-${type}`;
    if (pulse) span.classList.add('pulse');
    return span;
}

// ====================================================================
// 8. CARD DROPDOWN MENU
// ====================================================================

function initCardMenus() {
    document.querySelectorAll('.card-menu-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const menu = btn.nextElementSibling;
            
            // Fechar outros menus
            document.querySelectorAll('.card-dropdown-menu.show').forEach(m => {
                if (m !== menu) m.classList.remove('show');
            });
            
            menu.classList.toggle('show');
        });
    });
    
    // Fechar menu ao clicar fora
    document.addEventListener('click', () => {
        document.querySelectorAll('.card-dropdown-menu.show').forEach(menu => {
            menu.classList.remove('show');
        });
    });
}

// ====================================================================
// 9. BOTÃO LOADING STATE
// ====================================================================

function setButtonLoading(button, loading = true) {
    if (loading) {
        button.classList.add('btn-loading');
        button.disabled = true;
        if (!button.querySelector('span')) {
            button.innerHTML = `<span>${button.textContent}</span>`;
        }
    } else {
        button.classList.remove('btn-loading');
        button.disabled = false;
    }
}

// ====================================================================
// 10. EMPTY STATE
// ====================================================================

function createEmptyState(icon, title, message, actionText, actionUrl) {
    const div = document.createElement('div');
    div.className = 'empty-state';
    div.innerHTML = `
        <div class="empty-state-icon">
            <i class="bi ${icon}"></i>
        </div>
        <h3 class="empty-state-title">${title}</h3>
        <p class="empty-state-message">${message}</p>
        ${actionText ? `
            <div class="empty-state-action">
                <a href="${actionUrl}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> ${actionText}
                </a>
            </div>
        ` : ''}
    `;
    return div;
}

// ====================================================================
// 11. SKELETON LOADING
// ====================================================================

function createSkeleton(type = 'card') {
    const div = document.createElement('div');
    div.className = `skeleton skeleton-${type}`;
    return div;
}

function showSkeletons(container, count = 3, type = 'card') {
    for (let i = 0; i < count; i++) {
        container.appendChild(createSkeleton(type));
    }
}

function hideSkeletons(container) {
    container.querySelectorAll('.skeleton').forEach(s => s.remove());
}

// ====================================================================
// 12. TABS MODERNAS
// ====================================================================

function initTabs() {
    document.querySelectorAll('.nav-tabs-modern .nav-link').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            
            // Remover active de todos
            link.closest('.nav-tabs-modern').querySelectorAll('.nav-link').forEach(l => {
                l.classList.remove('active');
            });
            
            // Adicionar active no clicado
            link.classList.add('active');
            
            // Mostrar conteúdo correspondente
            const target = link.getAttribute('data-tab');
            if (target) {
                document.querySelectorAll('.tab-pane').forEach(pane => {
                    pane.style.display = 'none';
                });
                document.getElementById(target)?.style.display = 'block';
            }
        });
    });
}

// ====================================================================
// 13. CONTADOR DE RESULTADOS
// ====================================================================

function updateResultsCount(total, filtered = null) {
    let text = `Exibindo <strong>${total}</strong> ${total === 1 ? 'resultado' : 'resultados'}`;
    if (filtered !== null && filtered !== total) {
        text = `Exibindo <strong>${filtered}</strong> de <strong>${total}</strong> resultados`;
    }
    
    const counter = document.querySelector('.results-count');
    if (counter) {
        counter.innerHTML = text;
    } else {
        const div = document.createElement('div');
        div.className = 'results-count';
        div.innerHTML = text;
        return div;
    }
}

// ====================================================================
// 14. FILTROS COM BADGES
// ====================================================================

function createFilterBadge(label, value, onRemove) {
    const span = document.createElement('span');
    span.className = 'filter-badge';
    span.innerHTML = `
        ${label}: ${value}
        <button class="remove-filter" type="button">
            <i class="bi bi-x"></i>
        </button>
    `;
    
    span.querySelector('.remove-filter').addEventListener('click', () => {
        span.remove();
        if (onRemove) onRemove();
    });
    
    return span;
}

// ====================================================================
// 15. CONFIRMAÇÃO MODERNA (usando SweetAlert2)
// ====================================================================

async function confirmarAcao(titulo, mensagem, tipo = 'warning') {
    const result = await Swal.fire({
        title: titulo,
        text: mensagem,
        icon: tipo,
        showCancelButton: true,
        confirmButtonColor: '#4e73df',
        cancelButtonColor: '#858796',
        confirmButtonText: 'Sim, confirmar!',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    });
    
    return result.isConfirmed;
}

async function confirmarExclusao(nome) {
    return await confirmarAcao(
        'Confirmar exclusão?',
        `Tem certeza que deseja excluir "${nome}"? Esta ação não pode ser desfeita.`,
        'warning'
    );
}

// ====================================================================
// 16. INICIALIZAÇÃO AUTOMÁTICA
// ====================================================================

document.addEventListener('DOMContentLoaded', () => {
    // Inicializar tooltips
    initTooltips();
    
    // Inicializar menus de card
    initCardMenus();
    
    // Inicializar tabs
    initTabs();
    
    // Inicializar busca em todos os inputs com classe search-input
    document.querySelectorAll('.search-input').forEach(input => {
        initSearchBox(input.id);
    });
    
    console.log('✅ Sistema de melhorias carregado com sucesso!');
});

// ====================================================================
// 17. HELPER: COPIAR PARA CLIPBOARD
// ====================================================================

async function copiarTexto(texto) {
    try {
        await navigator.clipboard.writeText(texto);
        Toast.success('Copiado!', 'Texto copiado para a área de transferência');
        return true;
    } catch (err) {
        Toast.error('Erro', 'Não foi possível copiar o texto');
        return false;
    }
}

// ====================================================================
// 18. HELPER: FORMATAR MOEDA
// ====================================================================

function formatarMoeda(valor) {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(valor);
}

// ====================================================================
// 19. HELPER: FORMATAR DATA
// ====================================================================

function formatarData(data, incluirHora = false) {
    const d = new Date(data);
    const opcoes = {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    };
    
    if (incluirHora) {
        opcoes.hour = '2-digit';
        opcoes.minute = '2-digit';
    }
    
    return d.toLocaleDateString('pt-BR', opcoes);
}

// ====================================================================
// 20. HELPER: DEBOUNCE (para busca)
// ====================================================================

function debounce(func, wait = 300) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// ====================================================================
// EXPORTAR FUNÇÕES GLOBAIS
// ====================================================================

window.Toast = Toast;
window.Loading = Loading;
window.Breadcrumbs = Breadcrumbs;
window.initSearchBox = initSearchBox;
window.initTooltips = initTooltips;
window.createAvatar = createAvatar;
window.createStatusDot = createStatusDot;
window.initCardMenus = initCardMenus;
window.setButtonLoading = setButtonLoading;
window.createEmptyState = createEmptyState;
window.showSkeletons = showSkeletons;
window.hideSkeletons = hideSkeletons;
window.updateResultsCount = updateResultsCount;
window.createFilterBadge = createFilterBadge;
window.confirmarAcao = confirmarAcao;
window.confirmarExclusao = confirmarExclusao;
window.copiarTexto = copiarTexto;
window.formatarMoeda = formatarMoeda;
window.formatarData = formatarData;
window.debounce = debounce;

