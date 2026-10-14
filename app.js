/**
 * TG2D MAROC — LUXURY EDITORIAL INTERACTIVE ENGINE (AHMA BTP UX/UI)
 * High-performance 300-frame interactive canvas engine, lazy image pipeline,
 * event-driven RAF rendering, animated odometers, and luxury brand cursor.
 */

document.addEventListener('DOMContentLoaded', () => {
  /* ==========================================================================
     1. NAVIGATION & SCROLL LISTENER (THROTTLED WITH RAF)
     ========================================================================== */
  const header = document.getElementById('headerLockup');
  const navLogo = document.getElementById('navLogo');
  const navBurger = document.getElementById('navBurger');
  const mobClose = document.getElementById('mobClose');
  const mobileDrawer = document.getElementById('mobileDrawer');
  const heroTrack = document.getElementById('heroTrack');

  let isHeaderScrolled = false;
  let isHeaderLight = false;
  let isScrollTicking = false;

  let cachedTrackHeight = heroTrack ? Math.max(heroTrack.offsetHeight - window.innerHeight, 400) : 800;
  function updateCachedDimensions() {
    cachedTrackHeight = heroTrack ? Math.max(heroTrack.offsetHeight - window.innerHeight, 400) : 800;
  }
  window.addEventListener('resize', updateCachedDimensions, { passive: true });

  function updateHeaderOnScroll() {
    if (!header) return;
    const scrollY = window.scrollY;
    const shouldBeScrolled = scrollY > 40;
    const shouldBeLight = shouldBeScrolled && (scrollY > cachedTrackHeight + 40);

    if (shouldBeScrolled !== isHeaderScrolled) {
      isHeaderScrolled = shouldBeScrolled;
      header.classList.toggle('scrolled', isHeaderScrolled);
    }

    if (shouldBeLight !== isHeaderLight) {
      isHeaderLight = shouldBeLight;
      header.classList.toggle('scrolled-light', isHeaderLight);
    }
  }

  function onWindowScroll() {
    if (!isScrollTicking) {
      isScrollTicking = true;
      requestAnimationFrame(() => {
        updateHeaderOnScroll();
        syncHeroScrub();
        isScrollTicking = false;
      });
    }
  }

  window.addEventListener('scroll', onWindowScroll, { passive: true });
  updateHeaderOnScroll();

  /* Mobile Drawer Toggle */
  if (navBurger && mobileDrawer) {
    navBurger.addEventListener('click', () => {
      mobileDrawer.classList.add('is-open');
      document.body.style.overflow = 'hidden';
    });
  }

  if (mobClose && mobileDrawer) {
    mobClose.addEventListener('click', () => {
      mobileDrawer.classList.remove('is-open');
      document.body.style.overflow = '';
    });
  }

  if (mobileDrawer) {
    mobileDrawer.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        mobileDrawer.classList.remove('is-open');
        document.body.style.overflow = '';
      });
    });
  }

  /* ==========================================================================
     2. HIGH-PERFORMANCE PINNED HERO 300-FRAME ENGINE
     ========================================================================== */
  const canvas = document.getElementById('heroCanvas');
  const heroContent = document.getElementById('heroContent');
  const heroFoot = document.getElementById('heroFoot');

  let syncHeroScrub = () => {};

  if (canvas && heroTrack) {
    const ctx = canvas.getContext('2d', { alpha: false, desynchronized: true });
    const TOTAL_FRAMES = 300;
    const FRAME_PATH = (idx) => `scroll-frames-webp/ezgif-frame-${String(idx).padStart(3, '0')}.webp`;
    const images = new Array(TOTAL_FRAMES + 1);

    let currentFrame = 1;
    let targetFrame = 1;
    let lastDrawn = -1;
    let isHeroVisible = true;
    let isCanvasRendering = false;
    let lastOpacityVal = -1;

    // Viewport intersection observer to completely halt canvas processing when offscreen
    const heroObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        isHeroVisible = entry.isIntersecting;
        if (isHeroVisible && Math.abs(targetFrame - currentFrame) > 0.05) {
          triggerCanvasLoop();
        }
      });
    }, { threshold: 0.01 });

    heroObserver.observe(heroTrack);

    function resizeCanvas() {
      const dpr = Math.min(window.devicePixelRatio || 1, 1.75);
      const rect = canvas.getBoundingClientRect();
      const newWidth = Math.round(rect.width * dpr);
      const newHeight = Math.round(rect.height * dpr);

      if (canvas.width !== newWidth || canvas.height !== newHeight) {
        canvas.width = newWidth;
        canvas.height = newHeight;
        lastDrawn = -1;
        const frameToDraw = Math.round(currentFrame);
        if (images[frameToDraw] && images[frameToDraw].complete) {
          drawFrame(frameToDraw);
        }
      }
    }

    function drawFrame(frameIdx) {
      const img = images[frameIdx];
      if (!img || !img.complete || img.naturalWidth === 0) return;

      const w = canvas.width;
      const h = canvas.height;

      ctx.imageSmoothingEnabled = true;
      ctx.imageSmoothingQuality = 'medium';

      const imgAspect = img.naturalWidth / img.naturalHeight;
      const canvasAspect = w / h;

      let rw, rh, ox, oy;
      if (canvasAspect > imgAspect) {
        rw = w;
        rh = Math.round(w / imgAspect);
        ox = 0;
        oy = Math.round((h - rh) / 2);
      } else {
        rh = h;
        rw = Math.round(h * imgAspect);
        ox = Math.round((w - rw) / 2);
        oy = 0;
      }

      ctx.fillStyle = '#070B16';
      ctx.fillRect(0, 0, w, h);
      ctx.drawImage(img, ox, oy, rw, rh);
      lastDrawn = frameIdx;
    }

    /* On-Demand Intelligent Frame Loader (Zero Network Flooding & Ultra Fast Memory) */
    function ensureFramesNear(target) {
      const center = Math.round(target);
      const start = Math.max(1, center - 2);
      const end = Math.min(TOTAL_FRAMES, center + 4);
      for (let i = start; i <= end; i++) {
        if (!images[i]) loadSingleFrame(i);
      }
    }

    function preloadFrames() {
      // Step 1: Eagerly load ONLY initial frames (1..5)
      for (let i = 1; i <= 5; i++) {
        loadSingleFrame(i, i === 1 ? () => { resizeCanvas(); drawFrame(1); } : null);
      }
      // Step 2: Milestone samples for instant scrub feedback (every 30 frames)
      const milestones = [30, 60, 90, 120, 150, 180, 210, 240, 270, 300];
      milestones.forEach(i => loadSingleFrame(i));
    }

    function loadSingleFrame(idx, callback) {
      if (images[idx]) return;
      const img = new Image();
      img.src = FRAME_PATH(idx);
      if (img.decode) {
        img.decode().then(() => {
          images[idx] = img;
          if (callback) callback();
        }).catch(() => {
          images[idx] = img;
          if (callback) callback();
        });
      } else {
        img.onload = () => {
          images[idx] = img;
          if (callback) callback();
        };
      }
    }

    /* Update text opacity with zero-DOM-thrash caching */
    function updateTextFade(frame) {
      const fadeLimit = 10;
      let opacity = 0;
      let translateY = -30;

      if (frame <= fadeLimit) {
        const progress = (frame - 1) / (fadeLimit - 1);
        opacity = Math.max(0, 1 - progress);
        translateY = -30 * progress;
      }

      const roundedOpacity = Math.round(opacity * 100) / 100;
      if (roundedOpacity !== lastOpacityVal) {
        lastOpacityVal = roundedOpacity;
        const opacityStr = roundedOpacity.toString();
        const transformStr = `translate3d(0, ${translateY.toFixed(1)}px, 0)`;
        const pointerEvents = roundedOpacity <= 0.05 ? 'none' : 'auto';

        if (heroContent) {
          heroContent.style.opacity = opacityStr;
          heroContent.style.transform = transformStr;
          heroContent.style.pointerEvents = pointerEvents;
        }
        if (heroFoot) {
          heroFoot.style.opacity = opacityStr;
          heroFoot.style.transform = transformStr;
          heroFoot.style.pointerEvents = pointerEvents;
        }
      }
    }

    /* Event-Driven Render Loop: Only runs when animating */
    function renderLoop() {
      if (!isHeroVisible) {
        isCanvasRendering = false;
        return;
      }

      currentFrame += (targetFrame - currentFrame) * 0.22;
      const diff = Math.abs(targetFrame - currentFrame);

      if (diff < 0.02) {
        currentFrame = targetFrame;
      }

      const rounded = Math.max(1, Math.min(TOTAL_FRAMES, Math.round(currentFrame)));

      let toDraw = rounded;
      if (!images[toDraw] || !images[toDraw].complete) {
        for (let off = 1; off <= 30; off++) {
          if (images[rounded - off] && images[rounded - off].complete) { toDraw = rounded - off; break; }
          if (images[rounded + off] && images[rounded + off].complete) { toDraw = rounded + off; break; }
        }
      }

      if (toDraw !== lastDrawn && images[toDraw] && images[toDraw].complete) {
        drawFrame(toDraw);
      }

      updateTextFade(currentFrame);

      if (diff >= 0.02) {
        requestAnimationFrame(renderLoop);
      } else {
        isCanvasRendering = false;
      }
    }

    function triggerCanvasLoop() {
      if (!isCanvasRendering && isHeroVisible) {
        isCanvasRendering = true;
        requestAnimationFrame(renderLoop);
      }
    }

    syncHeroScrub = function() {
      if (!heroTrack || !isHeroVisible) return;
      const rect = heroTrack.getBoundingClientRect();

      if (cachedTrackHeight > 0) {
        const scrolled = -rect.top;
        const progress = Math.min(Math.max(scrolled / cachedTrackHeight, 0), 1);
        targetFrame = Math.max(1, Math.min(TOTAL_FRAMES, 1 + progress * (TOTAL_FRAMES - 1)));
        ensureFramesNear(targetFrame);
        triggerCanvasLoop();
      }
    };

    window.addEventListener('resize', resizeCanvas, { passive: true });

    preloadFrames();
    resizeCanvas();
    syncHeroScrub();
  }

  /* ==========================================================================
     3. ANIMATED NUMBER ODOMETERS (COUNT-UP)
     ========================================================================== */
  const odometers = document.querySelectorAll('.odo');
  if (odometers.length > 0) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const el = entry.target;
          const targetNum = parseInt(el.getAttribute('data-count'), 10);
          if (!isNaN(targetNum) && !el.classList.contains('counted')) {
            el.classList.add('counted');
            animateCount(el, targetNum);
          }
        }
      });
    }, { threshold: 0.2 });

    odometers.forEach(odo => observer.observe(odo));

    function animateCount(el, target) {
      const duration = 1400;
      const startTime = performance.now();

      function step(now) {
        const elapsed = now - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const easeOut = 1 - Math.pow(1 - progress, 3);
        const currentVal = Math.floor(easeOut * target);

        if (target >= 1000) {
          el.textContent = currentVal.toLocaleString('fr-FR');
        } else {
          el.textContent = currentVal;
        }

        if (progress < 1) {
          requestAnimationFrame(step);
        } else {
          el.textContent = target >= 1000 ? target.toLocaleString('fr-FR') : target;
        }
      }

      requestAnimationFrame(step);
    }
  }

  /* ==========================================================================
     4. PROJETS EMBLÉMATIQUES HORIZONTAL RAIL
     ========================================================================== */
  const frRail = document.getElementById('frRail');
  const frIdx = document.getElementById('frIdx');
  const frCat = document.getElementById('frCat');
  const frTitle = document.getElementById('frTitle');
  const frDesc = document.getElementById('frDesc');

  if (frRail && frIdx && frCat && frTitle && frDesc) {
    const cards = frRail.querySelectorAll('.fr-card');
    const frLink = document.getElementById('frLink');

    function updateActiveProject(card) {
      const idx = card.getAttribute('data-idx') || '01';
      const cat = card.getAttribute('data-cat') || 'Résidentiel';
      const title = card.getAttribute('data-title') || '';
      const desc = card.getAttribute('data-desc') || '';
      const url = card.getAttribute('data-url') || card.getAttribute('href') || 'projets.html';

      frIdx.textContent = idx;
      frCat.textContent = cat;
      frTitle.innerHTML = `<a href="${url}" style="color: inherit; text-decoration: none;">${title}</a>`;
      frDesc.textContent = desc;
      if (frLink) {
        frLink.setAttribute('href', url);
      }

      cards.forEach(c => c.style.borderColor = '');
      card.style.borderColor = 'var(--c-gold)';
    }

    cards.forEach(card => {
      card.addEventListener('mouseenter', () => updateActiveProject(card), { passive: true });
    });
  }

  /* ==========================================================================
     5. NOS MÉTIERS DECK TRACK PROGRESS
     ========================================================================== */
  const deckCards = document.querySelectorAll('.dcard');
  const deckFill = document.querySelector('.deck__fill');
  const deckCur = document.querySelector('.deck__rail b');

  if (deckCards.length > 0 && deckFill) {
    const deckObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const index = Array.from(deckCards).indexOf(entry.target);
          if (index !== -1) {
            const pct = ((index + 1) / deckCards.length) * 100;
            deckFill.style.width = `${pct}%`;
            if (deckCur) deckCur.textContent = String(index + 1).padStart(2, '0');
          }
        }
      });
    }, { threshold: 0.5 });

    deckCards.forEach(card => deckObserver.observe(card));
  }

  /* ==========================================================================
     6. TG2D LUXURY BRAND CUSTOM CURSOR (ZERO-JANK HIGH-FPS ENGINE)
     ========================================================================== */
  function initTg2dCursor() {
    // Completely skip on touch/mobile devices
    if (window.matchMedia('(hover: none) or (pointer: coarse)').matches) {
      return;
    }

    const wrapper = document.createElement('div');
    wrapper.className = 'tg2d-cursor-wrapper is-hidden';
    wrapper.setAttribute('aria-hidden', 'true');

    wrapper.innerHTML = `
      <div class="tg2d-cursor-ring">
        <img src="assets/images/tg2d-icon.png" alt="" class="tg2d-cursor-logo" loading="eager" />
      </div>
      <div class="tg2d-cursor-dot"></div>
      <div class="tg2d-cursor-label"></div>
    `;
    document.body.appendChild(wrapper);

    const dot = wrapper.querySelector('.tg2d-cursor-dot');
    const ring = wrapper.querySelector('.tg2d-cursor-ring');
    const label = wrapper.querySelector('.tg2d-cursor-label');

    let mouseX = -100;
    let mouseY = -100;
    let ringX = -100;
    let ringY = -100;
    let dotX = -100;
    let dotY = -100;
    let isVisible = false;
    let isCursorLoopActive = false;

    function renderCursor() {
      if (!isVisible) {
        isCursorLoopActive = false;
        return;
      }

      // Fast responsive dot
      dotX += (mouseX - dotX) * 0.85;
      dotY += (mouseY - dotY) * 0.85;
      dot.style.transform = `translate3d(${dotX.toFixed(1)}px, ${dotY.toFixed(1)}px, 0)`;

      // Smooth follower ring
      ringX += (mouseX - ringX) * 0.22;
      ringY += (mouseY - ringY) * 0.22;
      ring.style.transform = `translate3d(${ringX.toFixed(1)}px, ${ringY.toFixed(1)}px, 0)`;
      label.style.transform = `translate3d(${ringX.toFixed(1)}px, ${ringY.toFixed(1)}px, 0) translate(-50%, 0)`;

      const dist = Math.abs(mouseX - ringX) + Math.abs(mouseY - ringY);
      if (dist > 0.15) {
        requestAnimationFrame(renderCursor);
      } else {
        isCursorLoopActive = false;
      }
    }

    function wakeCursor() {
      if (!isCursorLoopActive && isVisible) {
        isCursorLoopActive = true;
        requestAnimationFrame(renderCursor);
      }
    }

    window.addEventListener('mousemove', (e) => {
      mouseX = e.clientX;
      mouseY = e.clientY;

      if (!isVisible) {
        isVisible = true;
        wrapper.classList.remove('is-hidden');
        ringX = mouseX;
        ringY = mouseY;
        dotX = mouseX;
        dotY = mouseY;
      }

      wakeCursor();
    }, { passive: true });

    document.addEventListener('mouseleave', () => {
      isVisible = false;
      wrapper.classList.add('is-hidden');
    });

    document.addEventListener('mouseenter', () => {
      isVisible = true;
      wrapper.classList.remove('is-hidden');
      wakeCursor();
    });

    window.addEventListener('mousedown', () => {
      wrapper.classList.add('is-clicking');
    }, { passive: true });

    window.addEventListener('mouseup', () => {
      wrapper.classList.remove('is-clicking');
    }, { passive: true });

    // Efficient delegated hover detection
    let hoverTimeout = null;
    document.addEventListener('mouseover', (e) => {
      if (hoverTimeout) return;
      hoverTimeout = setTimeout(() => {
        hoverTimeout = null;
        const cardTarget = e.target.closest('.fr-card, .dcard, .cx-card, .grid-card, .pjv-slab, .fl-card, .service-card-item, .engagement-card, .why-tg2d-card, .book-spread');
        const clickableTarget = e.target.closest('a, button, input, select, textarea, [role="button"], .btn, .nav-dd-btn, .h-cta, .hero-btn, .book-tab-btn, .book-nav-btn');

        if (cardTarget) {
          wrapper.classList.add('is-card-hover');
          wrapper.classList.remove('is-hovering');
          const customText = cardTarget.getAttribute('data-cursor-text') || 'DÉCOUVRIR';
          label.textContent = customText;
        } else if (clickableTarget) {
          wrapper.classList.add('is-hovering');
          wrapper.classList.remove('is-card-hover');
          label.textContent = '';
        } else {
          wrapper.classList.remove('is-hovering', 'is-card-hover');
          label.textContent = '';
        }
      }, 16);
    }, { passive: true });
  }

  initTg2dCursor();

  /* ==========================================================================
     7. TG2D BOOKPAGES SPREAD ENGINE (3D ANIMATED PROJECT SHOWCASE)
     ========================================================================== */
  function initProjectBookEngine() {
    const bookContainer = document.getElementById('projectBookContainer');
    if (!bookContainer) return;

    const spreads = bookContainer.querySelectorAll('.book-spread');
    const tabBtns = document.querySelectorAll('.book-tab-btn');
    const prevBtn = document.getElementById('bookPrevBtn');
    const nextBtn = document.getElementById('bookNextBtn');
    const curIndicator = document.getElementById('bookCurPage');
    const totalIndicator = document.getElementById('bookTotalPages');

    if (!spreads.length) return;

    let currentSpreadIdx = 0;
    let isFlipping = false;
    const totalSpreads = spreads.length;

    if (totalIndicator) {
      totalIndicator.textContent = String(totalSpreads * 2).padStart(2, '0');
    }

    function showSpread(targetIdx, forceDirection = null) {
      if (targetIdx < 0) targetIdx = 0;
      if (targetIdx >= totalSpreads) targetIdx = totalSpreads - 1;
      if (targetIdx === currentSpreadIdx && spreads[currentSpreadIdx].style.display === 'grid') return;
      if (isFlipping) return;

      const direction = forceDirection || (targetIdx > currentSpreadIdx ? 'forward' : 'backward');
      const prevIdx = currentSpreadIdx;
      currentSpreadIdx = targetIdx;
      isFlipping = true;

      spreads.forEach((spread, idx) => {
        spread.classList.remove('is-flipping-forward', 'is-flipping-backward');
        if (idx === currentSpreadIdx) {
          spread.style.display = 'grid';
          // Force layout reflow to ensure clean animation trigger
          void spread.offsetWidth;
          spread.classList.add(direction === 'forward' ? 'is-flipping-forward' : 'is-flipping-backward');
        } else {
          spread.style.display = 'none';
        }
      });

      tabBtns.forEach((btn, idx) => {
        btn.classList.toggle('is-active', idx === currentSpreadIdx);
      });

      if (curIndicator) {
        const pageNum = (currentSpreadIdx * 2) + 1;
        curIndicator.textContent = String(pageNum).padStart(2, '0') + '-' + String(pageNum + 1).padStart(2, '0');
      }

      if (prevBtn) prevBtn.disabled = currentSpreadIdx === 0;
      if (nextBtn) nextBtn.disabled = currentSpreadIdx === totalSpreads - 1;

      setTimeout(() => {
        isFlipping = false;
      }, 550);
    }

    tabBtns.forEach((btn, idx) => {
      btn.addEventListener('click', () => showSpread(idx));
    });

    if (prevBtn) {
      prevBtn.addEventListener('click', () => {
        if (currentSpreadIdx > 0) showSpread(currentSpreadIdx - 1, 'backward');
      });
    }

    if (nextBtn) {
      nextBtn.addEventListener('click', () => {
        if (currentSpreadIdx < totalSpreads - 1) showSpread(currentSpreadIdx + 1, 'forward');
      });
    }

    // Keyboard navigation (ArrowLeft / ArrowRight) when inside book stage
    const bookStage = document.querySelector('.book-stage');
    if (bookStage) {
      document.addEventListener('keydown', (e) => {
        const rect = bookStage.getBoundingClientRect();
        const isInView = rect.top < window.innerHeight && rect.bottom > 0;
        if (!isInView) return;

        if (e.key === 'ArrowRight' && currentSpreadIdx < totalSpreads - 1) {
          showSpread(currentSpreadIdx + 1, 'forward');
        } else if (e.key === 'ArrowLeft' && currentSpreadIdx > 0) {
          showSpread(currentSpreadIdx - 1, 'backward');
        }
      });

      // Touch swipe support for mobile
      let touchStartX = 0;
      let touchEndX = 0;
      bookStage.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
      }, { passive: true });

      bookStage.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        const diff = touchEndX - touchStartX;
        if (Math.abs(diff) > 40) {
          if (diff < 0 && currentSpreadIdx < totalSpreads - 1) {
            showSpread(currentSpreadIdx + 1, 'forward');
          } else if (diff > 0 && currentSpreadIdx > 0) {
            showSpread(currentSpreadIdx - 1, 'backward');
          }
        }
      }, { passive: true });
    }

    // Initialize first spread without animation delay
    spreads.forEach((spread, idx) => {
      spread.style.display = idx === 0 ? 'grid' : 'none';
    });
    tabBtns.forEach((btn, idx) => {
      btn.classList.toggle('is-active', idx === 0);
    });
    if (curIndicator) {
      curIndicator.textContent = '01-02';
    }
    if (prevBtn) prevBtn.disabled = true;
    if (nextBtn) nextBtn.disabled = totalSpreads <= 1;
  }

  initProjectBookEngine();

  /* ==========================================================================
     12. JOURNAL & PROJECT CASE STUDY FILTER ENGINE
     ========================================================================== */
  function initJournalFilterEngine() {
    const filterBar = document.getElementById('journalFilterBar');
    const featuredCard = document.querySelector('.journal-featured-card');
    const gridCards = document.querySelectorAll('.journal-card');

    if (!filterBar) return;

    const filterBtns = filterBar.querySelectorAll('.journal-filter-btn');

    filterBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        filterBtns.forEach(b => b.classList.remove('is-active'));
        btn.classList.add('is-active');

        const filter = btn.getAttribute('data-filter');

        // Handle featured card
        if (featuredCard) {
          const featuredCat = featuredCard.getAttribute('data-category');
          if (filter === 'all' || filter === featuredCat) {
            featuredCard.style.display = 'grid';
            featuredCard.style.opacity = '1';
            featuredCard.style.transform = 'translateY(0)';
          } else {
            featuredCard.style.display = 'none';
          }
        }

        // Handle grid cards
        gridCards.forEach(card => {
          const cat = card.getAttribute('data-category');
          if (filter === 'all' || filter === cat) {
            card.style.display = 'flex';
            card.style.opacity = '0';
            card.style.transform = 'translateY(12px)';
            setTimeout(() => {
              card.style.opacity = '1';
              card.style.transform = 'translateY(0)';
            }, 30);
          } else {
            card.style.display = 'none';
          }
        });
      });
    });
  }

  initJournalFilterEngine();
});


