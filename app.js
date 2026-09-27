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

  function updateHeaderOnScroll() {
    if (!header) return;
    const scrollY = window.scrollY;
    const shouldBeScrolled = scrollY > 40;
    const trackHeight = heroTrack ? heroTrack.offsetHeight - window.innerHeight : 800;
    const shouldBeLight = shouldBeScrolled && (scrollY > trackHeight + 40);

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

    /* Intelligent Chunked Image Preloader (Avoids Network Flooding) */
    function preloadFrames() {
      // Step 1: Eagerly load initial frames (1..15)
      for (let i = 1; i <= 15; i++) {
        loadSingleFrame(i, i === 1 ? () => { resizeCanvas(); drawFrame(1); } : null);
      }

      // Step 2: Milestone frames for instant scrub responsive feedback
      const milestones = [30, 60, 90, 120, 150, 180, 210, 240, 270, 300];
      milestones.forEach(i => loadSingleFrame(i));

      // Step 3: Progressive background batching for remaining frames (2 per tick)
      let nextFrameToLoad = 16;
      function loadNextBatch() {
        if (nextFrameToLoad > TOTAL_FRAMES) return;

        let loadedCount = 0;
        while (nextFrameToLoad <= TOTAL_FRAMES && loadedCount < 3) {
          if (!images[nextFrameToLoad]) {
            loadSingleFrame(nextFrameToLoad);
            loadedCount++;
          }
          nextFrameToLoad++;
        }

        if (nextFrameToLoad <= TOTAL_FRAMES) {
          if ('requestIdleCallback' in window) {
            requestIdleCallback(loadNextBatch, { timeout: 200 });
          } else {
            setTimeout(loadNextBatch, 60);
          }
        }
      }

      setTimeout(loadNextBatch, 500);
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
      const trackHeight = heroTrack.offsetHeight - window.innerHeight;

      if (trackHeight > 0) {
        const scrolled = -rect.top;
        const progress = Math.min(Math.max(scrolled / trackHeight, 0), 1);
        targetFrame = Math.max(1, Math.min(TOTAL_FRAMES, 1 + progress * (TOTAL_FRAMES - 1)));
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

    function updateActiveProject(card) {
      const idx = card.getAttribute('data-idx') || '01';
      const cat = card.getAttribute('data-cat') || 'Résidentiel';
      const title = card.getAttribute('data-title') || '';
      const desc = card.getAttribute('data-desc') || '';

      frIdx.textContent = idx;
      frCat.textContent = cat;
      frTitle.textContent = title;
      frDesc.textContent = desc;

      cards.forEach(c => c.style.borderColor = '');
      card.style.borderColor = 'var(--c-gold)';
    }

    cards.forEach(card => {
      card.addEventListener('mouseenter', () => updateActiveProject(card), { passive: true });
      card.addEventListener('click', () => updateActiveProject(card), { passive: true });
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
        const cardTarget = e.target.closest('.fr-card, .dcard, .cx-card, .grid-card, .pjv-slab, .fl-card');
        const clickableTarget = e.target.closest('a, button, input, select, textarea, [role="button"], .btn, .nav-dd-btn, .h-cta, .hero-btn');

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
});
