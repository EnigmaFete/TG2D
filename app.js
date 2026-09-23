/**
 * TG2D MAROC — LUXURY EDITORIAL INTERACTIVE ENGINE (AHMA BTP UX/UI)
 * Pinned 300-frame interactive canvas engine, smooth text fade-out after first 10 images,
 * animated odometers, interactive project rail, and luxury frosted navbar.
 */

document.addEventListener('DOMContentLoaded', () => {
  /* ==========================================================================
     1. NAVIGATION & SCROLL LISTENER (GLASSMORPHISM)
     ========================================================================== */
  const header = document.getElementById('headerLockup');
  const navLogo = document.getElementById('navLogo');
  const navBurger = document.getElementById('navBurger');
  const mobClose = document.getElementById('mobClose');
  const mobileDrawer = document.getElementById('mobileDrawer');
  const heroTrack = document.getElementById('heroTrack');

  function updateHeaderOnScroll() {
    if (!header) return;
    const scrollY = window.scrollY;

    if (scrollY > 40) {
      header.classList.add('scrolled');
      // When we scroll past the pinned hero track into the light presentation section
      const trackHeight = heroTrack ? heroTrack.offsetHeight - window.innerHeight : 800;
      if (scrollY > trackHeight + 40) {
        header.classList.add('scrolled-light');
        if (navLogo) navLogo.src = 'assets/images/tg2d-logo-dark.png';
      } else {
        header.classList.remove('scrolled-light');
        if (navLogo) navLogo.src = 'assets/images/tg2d-logo-light.png';
      }
    } else {
      header.classList.remove('scrolled');
      header.classList.remove('scrolled-light');
      if (navLogo) navLogo.src = 'assets/images/tg2d-logo-light.png';
    }
  }

  window.addEventListener('scroll', updateHeaderOnScroll, { passive: true });
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
     2. PINNED HERO 300-FRAME ENGINE & SMOOTH TEXT FADE-OUT
     ========================================================================== */
  const canvas = document.getElementById('heroCanvas');
  const heroContent = document.getElementById('heroContent');
  const heroFoot = document.getElementById('heroFoot');

  if (canvas) {
    const ctx = canvas.getContext('2d', { alpha: false });
    const TOTAL_FRAMES = 300;
    const FRAME_PATH = (idx) => `scroll-frames-webp/ezgif-frame-${String(idx).padStart(3, '0')}.webp`;
    const images = new Array(TOTAL_FRAMES + 1);

    let currentFrame = 1;
    let targetFrame = 1;
    let lastDrawn = -1;

    function resizeCanvas() {
      const dpr = Math.min(window.devicePixelRatio || 1, 2);
      const rect = canvas.getBoundingClientRect();
      canvas.width = Math.round(rect.width * dpr);
      canvas.height = Math.round(rect.height * dpr);

      if (images[Math.round(currentFrame)] && images[Math.round(currentFrame)].complete) {
        drawFrame(Math.round(currentFrame));
      }
    }

    function drawFrame(frameIdx) {
      const img = images[frameIdx];
      if (!img || !img.complete || img.naturalWidth === 0) return;

      const w = canvas.width;
      const h = canvas.height;

      ctx.imageSmoothingEnabled = true;
      ctx.imageSmoothingQuality = 'high';

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

    function preloadFrames() {
      // First frame loaded eagerly
      const first = new Image();
      first.src = FRAME_PATH(1);
      first.onload = () => {
        images[1] = first;
        resizeCanvas();
        drawFrame(1);
        loadMilestones();
      };

      function loadMilestones() {
        // Milestone frames for responsive scrub
        const miles = [10, 25, 50, 75, 100, 125, 150, 175, 200, 225, 250, 275, 300];
        miles.forEach(i => {
          if (!images[i]) {
            const m = new Image();
            m.src = FRAME_PATH(i);
            m.onload = () => { images[i] = m; };
          }
        });

        // Load rest of frames progressively
        for (let i = 2; i <= TOTAL_FRAMES; i++) {
          if (!images[i]) {
            const im = new Image();
            im.src = FRAME_PATH(i);
            images[i] = im;
          }
        }
      }
    }

    /* Update text opacity and displacement based on frame progression */
    function updateTextFade(frame) {
      // Smooth fade-out strictly during the first 10 images (frames 1 -> 10)
      const fadeLimit = 10;
      if (frame <= fadeLimit) {
        const progress = (frame - 1) / (fadeLimit - 1); // 0 at frame 1, 1 at frame 10
        const opacity = Math.max(0, 1 - progress);
        const translateY = -30 * progress;

        if (heroContent) {
          heroContent.style.opacity = opacity.toFixed(3);
          heroContent.style.transform = `translateY(${translateY.toFixed(1)}px)`;
          heroContent.style.pointerEvents = opacity <= 0.05 ? 'none' : 'auto';
        }
        if (heroFoot) {
          heroFoot.style.opacity = opacity.toFixed(3);
          heroFoot.style.transform = `translateY(${translateY.toFixed(1)}px)`;
          heroFoot.style.pointerEvents = opacity <= 0.05 ? 'none' : 'auto';
        }
      } else {
        if (heroContent) {
          heroContent.style.opacity = '0';
          heroContent.style.transform = 'translateY(-30px)';
          heroContent.style.pointerEvents = 'none';
        }
        if (heroFoot) {
          heroFoot.style.opacity = '0';
          heroFoot.style.transform = 'translateY(-30px)';
          heroFoot.style.pointerEvents = 'none';
        }
      }
    }

    function renderLoop() {
      // Smooth lerp towards target frame
      currentFrame += (targetFrame - currentFrame) * 0.18;
      const rounded = Math.max(1, Math.min(TOTAL_FRAMES, Math.round(currentFrame)));

      // Find nearest loaded frame if current isn't ready
      let toDraw = rounded;
      if (!images[toDraw] || !images[toDraw].complete) {
        for (let off = 1; off <= 25; off++) {
          if (images[rounded - off] && images[rounded - off].complete) { toDraw = rounded - off; break; }
          if (images[rounded + off] && images[rounded + off].complete) { toDraw = rounded + off; break; }
        }
      }

      if (toDraw !== lastDrawn && images[toDraw] && images[toDraw].complete) {
        drawFrame(toDraw);
      }

      updateTextFade(currentFrame);
      requestAnimationFrame(renderLoop);
    }

    /* Scrub based on scroll within the pinned hero track */
    function syncHeroScrub() {
      if (!heroTrack) return;
      const rect = heroTrack.getBoundingClientRect();
      const trackHeight = heroTrack.offsetHeight - window.innerHeight;

      if (trackHeight > 0) {
        const scrolled = -rect.top;
        const progress = Math.min(Math.max(scrolled / trackHeight, 0), 1);
        targetFrame = Math.max(1, Math.min(TOTAL_FRAMES, 1 + progress * (TOTAL_FRAMES - 1)));
      }
    }

    window.addEventListener('scroll', syncHeroScrub, { passive: true });
    window.addEventListener('resize', resizeCanvas, { passive: true });

    preloadFrames();
    resizeCanvas();
    renderLoop();
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
      const duration = 1600;
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
     4. PROJETS EMBLÉMATIQUES HORIZONTAL RAIL (DYNAMIC SIDE PANEL)
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
      card.addEventListener('mouseenter', () => updateActiveProject(card));
      card.addEventListener('click', () => updateActiveProject(card));
    });

    frRail.addEventListener('scroll', () => {
      let closestCard = cards[0];
      let minDistance = Infinity;
      const railCenter = frRail.scrollLeft + frRail.clientWidth / 2;

      cards.forEach(card => {
        const cardCenter = card.offsetLeft + card.offsetWidth / 2;
        const distance = Math.abs(railCenter - cardCenter);
        if (distance < minDistance) {
          minDistance = distance;
          closestCard = card;
        }
      });

      if (closestCard) {
        updateActiveProject(closestCard);
      }
    }, { passive: true });
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
});
