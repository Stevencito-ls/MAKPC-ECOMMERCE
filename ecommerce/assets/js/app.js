/**
 * MAKPC - Interactive Frontend Scripts
 */

document.addEventListener('DOMContentLoaded', function () {
  // ========================================================
  // 1. PUBLIC MOBILE DRAWER NAVIGATION
  // ========================================================
  const publicMenuToggle = document.getElementById('publicMenuToggle');
  const publicMobileDrawer = document.getElementById('publicMobileDrawer');
  const publicDrawerOverlay = document.getElementById('publicDrawerOverlay');
  const publicDrawerClose = document.getElementById('publicDrawerClose');

  function openPublicDrawer() {
    if (publicMobileDrawer) publicMobileDrawer.classList.add('open');
    if (publicDrawerOverlay) publicDrawerOverlay.classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  function closePublicDrawer() {
    if (publicMobileDrawer) publicMobileDrawer.classList.remove('open');
    if (publicDrawerOverlay) publicDrawerOverlay.classList.remove('active');
    document.body.style.overflow = '';
  }

  if (publicMenuToggle) {
    publicMenuToggle.addEventListener('click', function (e) {
      e.stopPropagation();
      openPublicDrawer();
    });
  }

  if (publicDrawerClose) {
    publicDrawerClose.addEventListener('click', closePublicDrawer);
  }

  if (publicDrawerOverlay) {
    publicDrawerOverlay.addEventListener('click', closePublicDrawer);
  }

  if (publicMobileDrawer) {
    publicMobileDrawer.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', closePublicDrawer);
    });
  }

  // Mega Menu Click Toggle for Touch & Click Devices
  const btnDepartmentsToggle = document.getElementById('btnDepartmentsToggle');
  const megaMenuDropdown = document.getElementById('megaMenuDropdown');
  if (btnDepartmentsToggle && megaMenuDropdown) {
    btnDepartmentsToggle.addEventListener('click', function (e) {
      e.stopPropagation();
      const isExpanded = megaMenuDropdown.classList.toggle('show');
      btnDepartmentsToggle.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
    });
    document.addEventListener('click', function (e) {
      if (!megaMenuDropdown.contains(e.target) && !btnDepartmentsToggle.contains(e.target)) {
        megaMenuDropdown.classList.remove('show');
        btnDepartmentsToggle.setAttribute('aria-expanded', 'false');
      }
    });
  }

  // ========================================================
  // 2. ADMIN OFF-CANVAS SIDEBAR & BACKDROP
  // ========================================================
  const menuToggle = document.getElementById('menuToggle');
  const appSidebar = document.getElementById('appSidebar') || document.querySelector('.app-sidebar');
  const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');

  // Backdrop container for admin sidebar
  let sidebarBackdrop = document.querySelector('.sidebar-backdrop');
  if (!sidebarBackdrop && appSidebar) {
    sidebarBackdrop = document.createElement('div');
    sidebarBackdrop.className = 'sidebar-backdrop';
    document.body.appendChild(sidebarBackdrop);
  }

  function openAdminSidebar() {
    if (appSidebar) appSidebar.classList.add('open');
    if (sidebarBackdrop) sidebarBackdrop.classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  function closeAdminSidebar() {
    if (appSidebar) appSidebar.classList.remove('open');
    if (sidebarBackdrop) sidebarBackdrop.classList.remove('active');
    document.body.style.overflow = '';
  }

  if (menuToggle && appSidebar) {
    menuToggle.addEventListener('click', function (e) {
      e.stopPropagation();
      if (appSidebar.classList.contains('open')) {
        closeAdminSidebar();
      } else {
        openAdminSidebar();
      }
    });

    if (sidebarCloseBtn) {
      sidebarCloseBtn.addEventListener('click', closeAdminSidebar);
    }

    if (sidebarBackdrop) {
      sidebarBackdrop.addEventListener('click', closeAdminSidebar);
    }
  }

  // ========================================================
  // 3. CATALOG MOBILE FILTER TOGGLE & DRAWER
  // ========================================================
  const mobileFilterToggle = document.getElementById('mobileFilterToggle');
  const modernSidebar = document.getElementById('modernSidebar') || document.querySelector('.modern-sidebar');
  const closeMobileFilter = document.getElementById('closeMobileFilter');
  const mobileFilterBadge = document.getElementById('mobileFilterBadge');

  function closeMobileFilterSidebar() {
    if (modernSidebar) modernSidebar.classList.remove('open');
    if (mobileFilterToggle) mobileFilterToggle.classList.remove('active');
  }

  if (mobileFilterToggle && modernSidebar) {
    mobileFilterToggle.addEventListener('click', function () {
      modernSidebar.classList.toggle('open');
      mobileFilterToggle.classList.toggle('active');
      if (modernSidebar.classList.contains('open')) {
        modernSidebar.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      }
    });

    if (closeMobileFilter) {
      closeMobileFilter.addEventListener('click', closeMobileFilterSidebar);
    }
  }

  // Close everything on Escape key
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      closePublicDrawer();
      closeAdminSidebar();
      closeMobileFilterSidebar();
    }
  });

  // Auto-dismiss alert banners after 5 seconds
  const alerts = document.querySelectorAll('.alert');
  alerts.forEach(function (alert) {
    setTimeout(function () {
      alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
      alert.style.opacity = '0';
      alert.style.transform = 'translateY(-10px)';
      setTimeout(function () {
        alert.remove();
      }, 500);
    }, 5000);
  });

  // Table live search filter
  const tableSearchInputs = document.querySelectorAll('[data-table-search]');
  tableSearchInputs.forEach(function (input) {
    const targetTableId = input.getAttribute('data-table-search');
    const table = document.getElementById(targetTableId);
    if (!table) return;

    input.addEventListener('keyup', function () {
      const term = input.value.toLowerCase().trim();
      const rows = table.querySelectorAll('tbody tr');

      rows.forEach(function (row) {
        const text = row.textContent.toLowerCase();
        if (text.includes(term)) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    });
  });

  // Confirm delete dialogs
  const confirmTriggers = document.querySelectorAll('[data-confirm]');
  confirmTriggers.forEach(function (el) {
    el.addEventListener('click', function (e) {
      const message = el.getAttribute('data-confirm') || '¿Está seguro de realizar esta acción?';
      if (!confirm(message)) {
        e.preventDefault();
      }
    });
  });

  // Price range slider dynamic label for store
  const priceRange = document.getElementById('priceRange');
  const priceDisplay = document.getElementById('priceDisplay');
  if (priceRange && priceDisplay) {
    priceRange.addEventListener('input', function () {
      priceDisplay.textContent = 'S/ ' + Number(priceRange.value).toFixed(2);
    });
  }

  // ========================================================
  // INTERACTIVE CATALOG FILTERING & SHOPPING CART
  // ========================================================
  const catalogGrid = document.getElementById('catalogProductsGrid');
  const productCards = catalogGrid ? Array.from(catalogGrid.querySelectorAll('.reference-card')) : [];
  const counterEl = document.getElementById('productCounter');
  const sidebarSearch = document.getElementById('sidebarSearchInput');
  const headerSearch = document.getElementById('headerSearchInput');
  const minPriceInput = document.getElementById('minPriceInput');
  const maxPriceInput = document.getElementById('maxPriceInput');
  const priceSlider = document.getElementById('sidebarPriceSlider');
  const resetPriceBtn = document.getElementById('resetPriceFilter');
  const brandCheckboxes = document.querySelectorAll('.filter-chk-brand');
  const popularTagBtns = document.querySelectorAll('.popular-tag-btn');
  const categoryPills = document.querySelectorAll('.cat-pill');
  const sortSelect = document.getElementById('catalogSortSelect');

  let activeCategory = 'all';

  function applyFilters() {
    if (!productCards.length) return;

    const searchTerm = (sidebarSearch?.value || '').toLowerCase().trim();
    const minP = parseFloat(minPriceInput?.value) || 0;
    const maxP = parseFloat(maxPriceInput?.value) || Infinity;

    // Selected brands
    const selectedBrands = Array.from(brandCheckboxes)
      .filter(chk => chk.checked)
      .map(chk => chk.value.toLowerCase());

    let visibleCount = 0;

    productCards.forEach(card => {
      const name = card.getAttribute('data-name') || '';
      const brand = card.getAttribute('data-brand') || '';
      const cat = card.getAttribute('data-cat') || '';
      const price = parseFloat(card.getAttribute('data-price')) || 0;

      // Check Category
      const matchCat = (activeCategory === 'all' || cat.includes(activeCategory) || activeCategory.includes(cat));

      // Check Search Term
      const matchSearch = !searchTerm || name.includes(searchTerm) || brand.includes(searchTerm) || cat.includes(searchTerm);

      // Check Price Range
      const matchPrice = price >= minP && price <= maxP;

      // Check Brands
      const matchBrand = selectedBrands.length === 0 || selectedBrands.includes(brand);

      if (matchCat && matchSearch && matchPrice && matchBrand) {
        card.style.display = '';
        visibleCount++;
      } else {
        card.style.display = 'none';
      }
    });

    if (counterEl) {
      counterEl.textContent = visibleCount;
    }

    // Active filters counter badge for mobile
    let activeFiltersCount = 0;
    if (searchTerm) activeFiltersCount++;
    if (minPriceInput?.value || maxPriceInput?.value) activeFiltersCount++;
    if (selectedBrands.length > 0) activeFiltersCount += selectedBrands.length;
    if (activeCategory !== 'all' && activeCategory !== '') activeFiltersCount++;

    if (mobileFilterBadge) {
      if (activeFiltersCount > 0) {
        mobileFilterBadge.textContent = activeFiltersCount;
        mobileFilterBadge.style.display = 'inline-block';
      } else {
        mobileFilterBadge.style.display = 'none';
      }
    }
  }

  // Real-time search listeners
  if (sidebarSearch) {
    sidebarSearch.addEventListener('input', applyFilters);
  }
  if (headerSearch) {
    headerSearch.addEventListener('input', function () {
      if (sidebarSearch) {
        sidebarSearch.value = headerSearch.value;
      }
      applyFilters();
    });
  }

  // Price slider listener
  if (priceSlider && maxPriceInput) {
    priceSlider.addEventListener('input', function () {
      maxPriceInput.value = priceSlider.value;
      applyFilters();
    });
  }

  if (minPriceInput) minPriceInput.addEventListener('input', applyFilters);
  if (maxPriceInput) maxPriceInput.addEventListener('input', applyFilters);

  if (resetPriceBtn) {
    resetPriceBtn.addEventListener('click', function () {
      if (minPriceInput) minPriceInput.value = '';
      if (maxPriceInput) maxPriceInput.value = '';
      if (priceSlider) priceSlider.value = priceSlider.max || 5000;
      applyFilters();
    });
  }

  // Brand checkboxes
  brandCheckboxes.forEach(chk => {
    chk.addEventListener('change', applyFilters);
  });

  // Popular tags buttons
  popularTagBtns.forEach(btn => {
    btn.addEventListener('click', function () {
      const targetBrand = btn.getAttribute('data-brand');
      brandCheckboxes.forEach(chk => {
        if (chk.value.toLowerCase() === targetBrand.toLowerCase()) {
          chk.checked = !chk.checked;
        }
      });
      btn.classList.toggle('active');
      applyFilters();
    });
  });

  // Category Pills
  categoryPills.forEach(pill => {
    pill.addEventListener('click', function (e) {
      e.preventDefault();
      categoryPills.forEach(p => p.classList.remove('active'));
      pill.classList.add('active');

      activeCategory = pill.getAttribute('data-filter-cat') || 'all';
      applyFilters();
    });
  });

  // Sorting
  if (sortSelect && catalogGrid) {
    sortSelect.addEventListener('change', function () {
      const val = sortSelect.value;
      const sorted = [...productCards].sort((a, b) => {
        const priceA = parseFloat(a.getAttribute('data-price')) || 0;
        const priceB = parseFloat(b.getAttribute('data-price')) || 0;
        const soldA = parseInt(a.getAttribute('data-sold')) || 0;
        const soldB = parseInt(b.getAttribute('data-sold')) || 0;

        if (val === 'precio_asc') return priceA - priceB;
        if (val === 'precio_desc') return priceB - priceA;
        if (val === 'vendidos') return soldB - soldA;
        return 0;
      });

      sorted.forEach(card => catalogGrid.appendChild(card));
    });
  }

  // ========================================================
  // SHOPPING CART DRAWER & LOCALSTORAGE SYSTEM
  // ========================================================
  let cartItems = JSON.parse(localStorage.getItem('makpc_cart') || '[]');
  const cartCounter = document.getElementById('headerCartCounter');
  const cartDrawer = document.getElementById('cartDrawer');
  const cartDrawerOverlay = document.getElementById('cartDrawerOverlay');
  const cartDrawerClose = document.getElementById('cartDrawerClose');
  const cartDrawerCount = document.getElementById('cartDrawerCount');
  const cartItemsList = document.getElementById('cartItemsList');
  const cartEmptyState = document.getElementById('cartEmptyState');
  const cartDrawerFooter = document.getElementById('cartDrawerFooter');
  const cartSubtotal = document.getElementById('cartSubtotal');
  const cartTotal = document.getElementById('cartTotal');
  const btnCartCheckout = document.getElementById('btnCartCheckout');
  const btnCartClear = document.getElementById('btnCartClear');
  const headerCartBtn = document.getElementById('headerCartBtn');
  const cartToast = document.getElementById('cartToast');
  const cartToastMsg = document.getElementById('cartToastMsg');

  function formatMoney(amount) {
    return 'S/ ' + Number(amount).toLocaleString('es-PE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  function saveCart() {
    localStorage.setItem('makpc_cart', JSON.stringify(cartItems));
    renderCart();
  }

  function openCartDrawer() {
    if (cartDrawer) cartDrawer.classList.add('open');
    if (cartDrawerOverlay) cartDrawerOverlay.classList.add('active');
    document.body.style.overflow = 'hidden';
    renderCart();
  }

  function closeCartDrawer() {
    if (cartDrawer) cartDrawer.classList.remove('open');
    if (cartDrawerOverlay) cartDrawerOverlay.classList.remove('active');
    document.body.style.overflow = '';
  }

  window.openCartDrawer = openCartDrawer;
  window.closeCartDrawer = closeCartDrawer;

  if (headerCartBtn) {
    headerCartBtn.addEventListener('click', function (e) {
      e.preventDefault();
      openCartDrawer();
    });
  }

  if (cartDrawerClose) {
    cartDrawerClose.addEventListener('click', closeCartDrawer);
  }

  if (cartDrawerOverlay) {
    cartDrawerOverlay.addEventListener('click', closeCartDrawer);
  }

  function renderCart() {
    // Total count of items
    const totalQty = cartItems.reduce((acc, item) => acc + (item.qty || 1), 0);
    if (cartCounter) cartCounter.textContent = totalQty;
    if (cartDrawerCount) cartDrawerCount.textContent = totalQty;

    if (!cartItemsList || !cartEmptyState || !cartDrawerFooter) return;

    if (cartItems.length === 0) {
      cartItemsList.innerHTML = '';
      cartEmptyState.style.display = 'block';
      cartDrawerFooter.style.display = 'none';
      return;
    }

    cartEmptyState.style.display = 'none';
    cartDrawerFooter.style.display = 'flex';

    let totalSum = 0;
    cartItemsList.innerHTML = '';

    cartItems.forEach((item, index) => {
      const price = parseFloat(item.price) || 0;
      const qty = parseInt(item.qty) || 1;
      const sub = price * qty;
      totalSum += sub;

      const isImgUrl = item.icon && (item.icon.includes('/') || item.icon.includes('.'));
      const thumbContent = isImgUrl 
        ? `<img src="${item.icon}" alt="${item.name.replace(/"/g, '&quot;')}" style="width:100%;height:100%;object-fit:cover;border-radius:6px;" onerror="this.onerror=null;this.parentElement.innerHTML='<svg width=\\'20\\' height=\\'20\\' viewBox=\\'0 0 24 24\\' fill=\\'none\\' stroke=\\'currentColor\\' stroke-width=\\'2\\'><rect width=\\'20\\' height=\\'14\\' x=\\'2\\' y=\\'3\\' rx=\\'2\\'/><line x1=\\'8\\' x2=\\'16\\' y1=\\'21\\' y2=\\'21\\'/><line x1=\\'12\\' x2=\\'12\\' y1=\\'17\\' y2=\\'21\\'/></svg>';"/>`
        : `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--cb-cyan)" stroke-width="2"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>`;

      const row = document.createElement('div');
      row.className = 'cart-item-row';
      row.innerHTML = `
        <div class="cart-item-thumb">
          ${thumbContent}
        </div>
        <div class="cart-item-info">
          <div class="cart-item-name" title="${item.name}">${item.name}</div>
          <div class="cart-item-price">${formatMoney(price)}</div>
          <div class="cart-item-controls">
            <button type="button" class="btn-qty" data-cart-minus="${index}">-</button>
            <span class="cart-item-qty">${qty}</span>
            <button type="button" class="btn-qty" data-cart-plus="${index}">+</button>
          </div>
        </div>
        <button type="button" class="btn-cart-remove" data-cart-del="${index}" title="Eliminar producto">&times;</button>
      `;
      cartItemsList.appendChild(row);
    });

    if (cartSubtotal) cartSubtotal.textContent = formatMoney(totalSum);
    if (cartTotal) cartTotal.textContent = formatMoney(totalSum);

    // Generate WhatsApp Checkout URL
    if (btnCartCheckout) {
      let waMessage = "¡Hola MAKPC Enterprises!\nDeseo realizar un pedido con los siguientes productos de su tienda web:\n\n";
      cartItems.forEach(i => {
        waMessage += `• ${i.qty || 1}x ${i.name} — ${formatMoney((parseFloat(i.price) || 0) * (i.qty || 1))}\n`;
      });
      waMessage += `\nTotal a Pagar: ${formatMoney(totalSum)}\n`;
      waMessage += `\n¿Tienen disponibilidad inmediata para envío o recojo en taller?`;

      btnCartCheckout.href = `https://wa.me/51975513327?text=${encodeURIComponent(waMessage)}`;
    }
  }

  // Delegated events for cart items
  if (cartItemsList) {
    cartItemsList.addEventListener('click', function (e) {
      const target = e.target;

      if (target.matches('[data-cart-plus]')) {
        const idx = parseInt(target.getAttribute('data-cart-plus'));
        if (cartItems[idx]) {
          cartItems[idx].qty = (cartItems[idx].qty || 1) + 1;
          saveCart();
        }
      } else if (target.matches('[data-cart-minus]')) {
        const idx = parseInt(target.getAttribute('data-cart-minus'));
        if (cartItems[idx]) {
          if (cartItems[idx].qty > 1) {
            cartItems[idx].qty -= 1;
          } else {
            cartItems.splice(idx, 1);
          }
          saveCart();
        }
      } else if (target.matches('[data-cart-del]')) {
        const idx = parseInt(target.getAttribute('data-cart-del'));
        if (cartItems[idx]) {
          cartItems.splice(idx, 1);
          saveCart();
        }
      }
    });
  }

  if (btnCartClear) {
    btnCartClear.addEventListener('click', function () {
      if (confirm('¿Estás seguro de que deseas vaciar todos los productos del carrito?')) {
        cartItems = [];
        saveCart();
        showCartToast('Se ha vaciado el carrito.');
      }
    });
  }

  function showCartToast(message) {
    if (!cartToast) return;
    if (cartToastMsg) cartToastMsg.textContent = message;
    cartToast.classList.add('show');
    setTimeout(() => {
      cartToast.classList.remove('show');
    }, 3000);
  }

  // Add to cart buttons on product cards (soporta [data-cart-add] y .btn-add-cart-action)
  document.addEventListener('click', function(e) {
    const btn = e.target.closest('[data-cart-add], .btn-add-cart-action');
    if (!btn) return;

    e.preventDefault();
    const id = btn.getAttribute('data-cart-add') || btn.getAttribute('data-id');
    const name = btn.getAttribute('data-product-name') || btn.getAttribute('data-name');
    const price = btn.getAttribute('data-product-price') || btn.getAttribute('data-price');
    const icon = btn.getAttribute('data-img') || btn.closest('.cb-product-card, .card-item-modern')?.querySelector('.cb-card-media-icon, .card-item-icon-art')?.textContent?.trim() || '📦';

    if (!id || !name) return;

    // Check if item already exists
    const existing = cartItems.find(i => i.id === id);
    if (existing) {
      existing.qty = (existing.qty || 1) + 1;
    } else {
      cartItems.push({
        id,
        name,
        price,
        qty: 1,
        icon,
        date: new Date().toISOString()
      });
    }

    saveCart();

    // Button feedback
    const originalText = btn.innerHTML;
    btn.innerHTML = '✓ ¡Añadido!';
    btn.classList.add('added');
    setTimeout(() => {
      btn.innerHTML = originalText;
      btn.classList.remove('added');
    }, 1500);

    showCartToast(`"${name}" añadido al carrito`);
  });

  // Escuchar evento externo 'cartUpdated' emitido por el armador de PC
  window.addEventListener('cartUpdated', function() {
    try {
      cartItems = JSON.parse(localStorage.getItem('makpc_cart')) || [];
      renderCart();
    } catch(err) {}
  });

  // Initial cart render
  renderCart();

  // Wishlist interactive handler
  window.toggleWishlist = function(productId, btn) {
    let wishlist = [];
    try {
      wishlist = JSON.parse(localStorage.getItem('makpc_wishlist')) || [];
    } catch (e) {
      wishlist = [];
    }
    const idStr = String(productId);
    const idx = wishlist.indexOf(idStr);
    if (idx >= 0) {
      wishlist.splice(idx, 1);
      if (btn) btn.classList.remove('active');
      showCartToast('Producto retirado de tus favoritos');
    } else {
      wishlist.push(idStr);
      if (btn) btn.classList.add('active');
      showCartToast('¡Producto guardado en tus favoritos!');
    }
    localStorage.setItem('makpc_wishlist', JSON.stringify(wishlist));
  };

  // Restore wishlist buttons state on page load
  try {
    const savedWishlist = JSON.parse(localStorage.getItem('makpc_wishlist')) || [];
    document.querySelectorAll('.cb-btn-wishlist').forEach(btn => {
      const onclickAttr = btn.getAttribute('onclick') || '';
      const match = onclickAttr.match(/toggleWishlist\((\d+)/);
      if (match && savedWishlist.includes(match[1])) {
        btn.classList.add('active');
      }
    });
  } catch(e) {}
});


