(function() {
    'use strict';

    const LateralMenu = {
        mainBtn: null,
        socialButtons: null,
        isMenuOpen: false,
        animationInProgress: false,

        init: function() {
            document.addEventListener('DOMContentLoaded', this.domReady.bind(this));
        },

        domReady: function() {
            this.mainBtn = document.getElementById('lateralMenuBtn');
            this.socialButtons = document.getElementById('lateralSocialButtons');

            if (!this.mainBtn || !this.socialButtons) {
                console.warn('MAK-PC Lateral Menu: Elementos no encontrados');
                return;
            }

            this.bindEvents();
            console.log('MAK-PC: Menú lateral inicializado correctamente');
        },

        bindEvents: function() {
            this.mainBtn.addEventListener('click', this.toggleMenu.bind(this));
            document.addEventListener('click', this.handleOutsideClick.bind(this));

            this.socialButtons.addEventListener('click', function(e) {
                e.stopPropagation();
            });

            this.bindSocialButtons();
            this.bindKeyboardEvents();
            window.addEventListener('resize', this.handleResize.bind(this));
        },

        bindSocialButtons: function() {
            const socialBtns = this.socialButtons.querySelectorAll('.lateral-btn');

            socialBtns.forEach((btn, index) => {
                btn.addEventListener('mouseenter', this.handleHoverEnter.bind(this, btn));
                btn.addEventListener('mouseleave', this.handleHoverLeave.bind(this, btn));
                btn.addEventListener('click', this.handleSocialClick.bind(this, btn, index));
                btn.addEventListener('keydown', this.handleSocialKeydown.bind(this, btn, index, socialBtns));
            });
        },

        bindKeyboardEvents: function() {
            this.mainBtn.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.toggleMenu();
                }
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.isMenuOpen) {
                    this.closeMenu();
                }
            });
        },

        openMenu: function() {
            if (this.animationInProgress) return;

            this.animationInProgress = true;
            this.isMenuOpen = true;

            this.mainBtn.classList.add('lateral-active');
            this.socialButtons.style.display = 'block';
            this.socialButtons.classList.remove('lateral-hide');
            this.socialButtons.classList.add('lateral-show');

            setTimeout(() => {
                this.animationInProgress = false;
            }, 600);
        },

        closeMenu: function() {
            if (this.animationInProgress) return;

            this.animationInProgress = true;
            this.isMenuOpen = false;

            this.mainBtn.classList.remove('lateral-active');
            this.socialButtons.classList.remove('lateral-show');
            this.socialButtons.classList.add('lateral-hide');

            setTimeout(() => {
                this.socialButtons.style.display = 'none';
                this.animationInProgress = false;
            }, 400);
        },

        toggleMenu: function(e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }

            if (this.isMenuOpen) {
                this.closeMenu();
            } else {
                this.openMenu();
            }
        },

        handleOutsideClick: function(e) {
            const lateralContainer = document.querySelector('.lateral-menu-container');

            if (!lateralContainer.contains(e.target) && this.isMenuOpen) {
                this.closeMenu();
            }
        },

        handleHoverEnter: function(btn) {
            if (this.isMenuOpen && !this.animationInProgress) {
                btn.style.transform = 'scale(1.15)';
            }
        },

        handleHoverLeave: function(btn) {
            if (this.isMenuOpen && !this.animationInProgress) {
                btn.style.transform = 'scale(1)';
            }
        },

        handleSocialClick: function(btn, index, e) {
            if (!this.isMenuOpen) {
                e.preventDefault();
                return false;
            }

            btn.style.transform = 'scale(0.95)';
            setTimeout(() => {
                btn.style.transform = 'scale(1)';
            }, 150);

            setTimeout(() => {
                this.closeMenu();
            }, 200);
        },

        handleSocialKeydown: function(btn, index, socialBtns, e) {
            if (!this.isMenuOpen) return;

            if (e.key === 'ArrowUp' && index > 0) {
                e.preventDefault();
                socialBtns[index - 1].focus();
            } else if (e.key === 'ArrowDown' && index < socialBtns.length - 1) {
                e.preventDefault();
                socialBtns[index + 1].focus();
            } else if (e.key === 'Escape') {
                e.preventDefault();
                this.closeMenu();
                this.mainBtn.focus();
            }
        },

        handleResize: function() {
            if (this.isMenuOpen) {
                const width = window.innerWidth;
                if (width < 480 && this.isMenuOpen) {
                    this.closeMenu();
                }
            }
        },

        getAPI: function() {
            return {
                open: this.openMenu.bind(this),
                close: this.closeMenu.bind(this),
                toggle: this.toggleMenu.bind(this),
                isOpen: () => this.isMenuOpen,
                getState: () => ({
                    isOpen: this.isMenuOpen,
                    animating: this.animationInProgress
                })
            };
        }
    };

    LateralMenu.init();
    window.MAKPCLateralMenu = LateralMenu.getAPI();
})();
