/**
 * TG2D MAROC — Fixed Head Page Interactive Scroll Engine
 * Locks the head page completely in place until the 300-frame animation is 100% finished.
 * Once frame 300 is reached, allows natural downward scrolling through the rest of the website.
 */

document.addEventListener('DOMContentLoaded', () => {
  /* ==========================================================================
     1. CONSTANTS & DOM REFERENCES
     ========================================================================== */
  const TOTAL_FRAMES = 300;
  const FRAME_PATH = (index) => `scroll-frames-webp/ezgif-frame-${String(index).padStart(3, '0')}.webp`;

  const canvas = document.getElementById('heroCanvas');
  if (canvas) {
    const ctx = canvas.getContext('2d', { alpha: false });
    const heroIntro = document.getElementById('heroIntro');
    const heroLoader = document.getElementById('heroLoader');
    const loaderFill = document.getElementById('loaderFill');

    // Images cache
    const images = new Array(TOTAL_FRAMES + 1);
    let loadedCount = 0;
    let currentFrame = 1;
    let targetFrame = 1;
    let lastDrawnFrame = -1;
    let isAnimationFinished = false;

    /* ==========================================================================
       2. RESIZE & HI-DPI CRISP CANVAS RENDERING
       ========================================================================== */
    function resizeCanvas() {
      const dpr = Math.min(window.devicePixelRatio || 1, 2);
      const rect = canvas.getBoundingClientRect();
      
      // Set actual render buffer to match device physical pixels
      canvas.width = Math.round(rect.width * dpr);
      canvas.height = Math.round(rect.height * dpr);

      if (images[Math.round(currentFrame)] && images[Math.round(currentFrame)].complete) {
        drawFrame(Math.round(currentFrame));
      }
    }

    function drawFrame(frameIndex) {
      const img = images[frameIndex];
      if (!img || !img.complete || img.naturalWidth === 0) return;

      const w = canvas.width;
      const h = canvas.height;

      // Enable ultra-crisp bicubic interpolation
      ctx.imageSmoothingEnabled = true;
      ctx.imageSmoothingQuality = 'high';

      // Mathematical Object-fit: Cover algorithm in physical pixels
      const imgAspect = img.naturalWidth / img.naturalHeight;
      const canvasAspect = w / h;

      let renderW, renderH, offsetX, offsetY;

      if (canvasAspect > imgAspect) {
        renderW = w;
        renderH = Math.round(w / imgAspect);
        offsetX = 0;
        offsetY = Math.round((h - renderH) / 2);
      } else {
        renderH = h;
        renderW = Math.round(h * imgAspect);
        offsetX = Math.round((w - renderW) / 2);
        offsetY = 0;
      }

      ctx.fillStyle = '#06070a';
      ctx.fillRect(0, 0, w, h);
      ctx.drawImage(img, offsetX, offsetY, renderW, renderH);
      lastDrawnFrame = frameIndex;
    }

    /* ==========================================================================
       3. HIGH-RESILIENCE FRAME PRELOADER
       ========================================================================== */
    function preloadFrames() {
      // 1. Immediately load & render Frame 1
      const firstImg = new Image();
      firstImg.src = FRAME_PATH(1);
      images[1] = firstImg;

      firstImg.onload = () => {
        loadedCount++;
        resizeCanvas();
        drawFrame(1);
        updatePreloaderBar();
        preloadMilestones();
      };
      firstImg.onerror = () => {
        preloadMilestones();
      };

      // 2. Preload distributed milestone frames across 1..300 so scrubbing is immediately responsive
      function preloadMilestones() {
        const milestones = [25, 50, 75, 100, 125, 150, 175, 200, 225, 250, 275, 300];
        let pending = milestones.length;

        milestones.forEach(idx => {
          if (!images[idx]) {
            const img = new Image();
            img.src = FRAME_PATH(idx);
            img.onload = img.onerror = () => {
              loadedCount++;
              updatePreloaderBar();
              pending--;
              if (pending <= 0) {
                streamRemainingBatches(2, 25);
              }
            };
            images[idx] = img;
          }
        });
      }

      // 3. Stream all remaining frames smoothly
      function streamRemainingBatches(startIdx, batchSize) {
        const endIdx = Math.min(startIdx + batchSize - 1, TOTAL_FRAMES);

        for (let i = startIdx; i <= endIdx; i++) {
          if (!images[i]) {
            const img = new Image();
            img.src = FRAME_PATH(i);
            img.onload = img.onerror = () => {
              loadedCount++;
              updatePreloaderBar();
            };
            images[i] = img;
          }
        }

        if (endIdx < TOTAL_FRAMES) {
          setTimeout(() => {
            streamRemainingBatches(endIdx + 1, batchSize);
          }, 12);
        }
      }

      function updatePreloaderBar() {
        const pct = Math.min(Math.round((loadedCount / TOTAL_FRAMES) * 100), 100);
        if (loaderFill) loaderFill.style.width = `${pct}%`;

        if (loadedCount >= 15 && heroLoader && !heroLoader.classList.contains('hidden')) {
          heroLoader.classList.add('hidden');
        }
      }
    }

    /* ==========================================================================
       4. CINEMATIC RENDER LOOP WITH ZERO-STALL FALLBACK
       ========================================================================== */
    function renderLoop() {
      const lerpFactor = 0.18;
      currentFrame += (targetFrame - currentFrame) * lerpFactor;

      if (Math.abs(targetFrame - currentFrame) < 0.04) {
        currentFrame = targetFrame;
      }

      const roundedFrame = Math.max(1, Math.min(TOTAL_FRAMES, Math.round(currentFrame)));

      // If requested frame is still buffering, locate the nearest loaded frame
      let frameToDraw = roundedFrame;
      if (!images[frameToDraw] || !images[frameToDraw].complete || images[frameToDraw].naturalWidth === 0) {
        for (let offset = 1; offset <= 30; offset++) {
          const prev = roundedFrame - offset;
          if (prev >= 1 && images[prev] && images[prev].complete && images[prev].naturalWidth > 0) {
            frameToDraw = prev;
            break;
          }
          const next = roundedFrame + offset;
          if (next <= TOTAL_FRAMES && images[next] && images[next].complete && images[next].naturalWidth > 0) {
            frameToDraw = next;
            break;
          }
        }
      }

      if (frameToDraw !== lastDrawnFrame && images[frameToDraw] && images[frameToDraw].complete && images[frameToDraw].naturalWidth > 0) {
        drawFrame(frameToDraw);
      }

      requestAnimationFrame(renderLoop);
    }

    /* ==========================================================================
       5. STRICT BULLETPROOF TOP-PINNED ENGINE (ZERO PAGE SCROLL UNTIL FRAME 300)
       ========================================================================== */
    function setScrollLock(locked) {
      if (locked) {
        document.documentElement.style.overflow = 'hidden';
        document.body.style.overflow = 'hidden';
      } else {
        document.documentElement.style.overflow = '';
        document.body.style.overflow = '';
      }
    }

    // Start with page strictly locked at the top
    if (window.scrollY <= 5) {
      window.scrollTo(0, 0);
      setScrollLock(true);
    }

    function applyFrame(newTarget) {
      targetFrame = Math.max(1, Math.min(TOTAL_FRAMES, newTarget));
      const rounded = Math.round(targetFrame);

      if (images[rounded] && images[rounded].complete && images[rounded].naturalWidth > 0) {
        currentFrame = targetFrame;
        drawFrame(rounded);
      }

      // Intro Typography Fade during first ~30 frames
      if (heroIntro) {
        if (targetFrame > 3) {
          const fade = Math.min((targetFrame - 3) / 30, 1);
          heroIntro.style.opacity = (1 - fade).toFixed(3);
          heroIntro.style.transform = `translateY(-${(fade * 30).toFixed(1)}px)`;
          heroIntro.style.pointerEvents = fade >= 0.8 ? 'none' : 'auto';
        } else {
          heroIntro.style.opacity = '1';
          heroIntro.style.transform = 'translateY(0)';
          heroIntro.style.pointerEvents = 'auto';
        }
      }
    }

    function handleWheel(e) {
      // 1. While animation is not finished, COMPLETELY PREVENT PAGE SCROLL
      if (!isAnimationFinished) {
        e.preventDefault();
        e.stopPropagation();
        window.scrollTo(0, 0);

        if (e.deltaY > 0) {
          const step = Math.max(0.5, Math.abs(e.deltaY) * 0.08);
          applyFrame(targetFrame + step);

          if (targetFrame >= TOTAL_FRAMES) {
            targetFrame = TOTAL_FRAMES;
            isAnimationFinished = true;
            setScrollLock(false);
          }
        } else if (e.deltaY < 0) {
          const step = Math.max(0.5, Math.abs(e.deltaY) * 0.08);
          applyFrame(targetFrame - step);
        }
        return false;
      }

      // 2. If animation finished and user scrolled back to top and scrolls UP:
      if (isAnimationFinished && window.scrollY <= 0 && e.deltaY < 0) {
        e.preventDefault();
        isAnimationFinished = false;
        setScrollLock(true);
        window.scrollTo(0, 0);
        applyFrame(targetFrame - Math.max(0.5, Math.abs(e.deltaY) * 0.08));
        return false;
      }
    }

    window.addEventListener('wheel', handleWheel, { passive: false, capture: true });

    // Ensure scroll position is pinned at 0 while animation is running
    window.addEventListener('scroll', () => {
      if (!isAnimationFinished && window.scrollY > 0) {
        window.scrollTo(0, 0);
      }
    }, { passive: false });

    // Touch events for mobile/tablets
    let touchStartY = 0;
    function handleTouchStart(e) {
      if (e.touches && e.touches.length > 0) {
        touchStartY = e.touches[0].clientY;
      }
    }

    function handleTouchMove(e) {
      if (!isAnimationFinished) {
        e.preventDefault();
        e.stopPropagation();
        window.scrollTo(0, 0);

        if (!e.touches || e.touches.length === 0) return false;
        const currentY = e.touches[0].clientY;
        const deltaY = touchStartY - currentY;
        touchStartY = currentY;

        if (deltaY > 0) {
          const step = Math.max(0.5, deltaY * 0.14);
          applyFrame(targetFrame + step);

          if (targetFrame >= TOTAL_FRAMES) {
            targetFrame = TOTAL_FRAMES;
            isAnimationFinished = true;
            setScrollLock(false);
          }
        } else if (deltaY < 0) {
          const step = Math.max(0.5, Math.abs(deltaY) * 0.14);
          applyFrame(targetFrame - step);
        }
        return false;
      }

      if (isAnimationFinished && window.scrollY <= 0 && e.touches && e.touches.length > 0) {
        const currentY = e.touches[0].clientY;
        const deltaY = touchStartY - currentY;
        if (deltaY < 0) {
          e.preventDefault();
          isAnimationFinished = false;
          setScrollLock(true);
          window.scrollTo(0, 0);
          applyFrame(targetFrame - Math.max(0.5, Math.abs(deltaY) * 0.14));
          return false;
        }
      }
    }

    window.addEventListener('touchstart', handleTouchStart, { passive: true });
    window.addEventListener('touchmove', handleTouchMove, { passive: false, capture: true });

    // Keyboard navigation (ArrowDown, PageDown, Space)
    window.addEventListener('keydown', (e) => {
      if (!isAnimationFinished) {
        if (['ArrowDown', 'PageDown', ' ', 'Spacebar'].includes(e.key)) {
          e.preventDefault();
          applyFrame(targetFrame + 6);
          if (targetFrame >= TOTAL_FRAMES) {
            targetFrame = TOTAL_FRAMES;
            isAnimationFinished = true;
            setScrollLock(false);
          }
        } else if (['ArrowUp', 'PageUp'].includes(e.key)) {
          e.preventDefault();
          applyFrame(targetFrame - 6);
        }
      }
    });

    window.addEventListener('resize', () => {
      resizeCanvas();
    });

    function unlockAndScroll() {
      applyFrame(TOTAL_FRAMES);
      isAnimationFinished = true;
      setScrollLock(false);
      const manifesto = document.getElementById('manifesto');
      if (manifesto) {
        manifesto.scrollIntoView({ behavior: 'smooth' });
      }
    }

    const scrollPrompt = document.querySelector('.hero-scroll-prompt');
    if (scrollPrompt) {
      scrollPrompt.style.cursor = 'pointer';
      scrollPrompt.addEventListener('click', unlockAndScroll);
    }

    preloadFrames();
    resizeCanvas();
    applyFrame(1);
    requestAnimationFrame(renderLoop);
  }

  // Header scroll state
  const header = document.getElementById('header');
  const heroElem = document.getElementById('hero');
  if (header) {
    function updateHeader() {
      if (heroElem) {
        const heroBottom = heroElem.getBoundingClientRect().bottom;
        if (heroBottom <= 80) {
          header.classList.add('scrolled-past-hero');
        } else {
          header.classList.remove('scrolled-past-hero');
        }
      }
      if (window.scrollY > 30) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }
    }
    window.addEventListener('scroll', updateHeader, { passive: true });
    updateHeader();
  }

  /* ==========================================================================
     6. METRIC NUMBER ANIMATION (RESTARTS EVERY TIME ON SCROLL INTO VIEW)
     ========================================================================== */
  function initMetricsAnimation() {
    const metricCards = document.querySelectorAll('.metric-value');
    const metricsSec = document.querySelector('.metrics-section');
    if (!metricCards.length || !metricsSec) return;

    // Track active requestAnimationFrame IDs per element to avoid overlaps
    const animMap = new Map();

    function animateMetric(el) {
      const rawTarget = el.getAttribute('data-target') || el.textContent.replace(/\s+/g, '');
      const target = parseInt(rawTarget, 10);
      if (isNaN(target)) return;

      // Cancel any ongoing animation for this element
      if (animMap.has(el)) {
        cancelAnimationFrame(animMap.get(el));
        animMap.delete(el);
      }

      const duration = 1600; // ms duration for counting
      let startTime = null;

      function step(timestamp) {
        if (!startTime) startTime = timestamp;
        const elapsed = timestamp - startTime;
        const progress = Math.min(elapsed / duration, 1);

        // Smooth cubic ease-out curve
        const easeOut = 1 - Math.pow(1 - progress, 3);
        const currentVal = Math.round(easeOut * target);

        el.textContent = currentVal.toLocaleString('fr-FR');

        if (progress < 1) {
          const frameId = requestAnimationFrame(step);
          animMap.set(el, frameId);
        } else {
          el.textContent = target.toLocaleString('fr-FR');
          animMap.delete(el);
        }
      }

      // Reset to 0 before counting up
      el.textContent = '0';
      const frameId = requestAnimationFrame(step);
      animMap.set(el, frameId);
    }

    function resetMetric(el) {
      if (animMap.has(el)) {
        cancelAnimationFrame(animMap.get(el));
        animMap.delete(el);
      }
      el.textContent = '0';
    }

    if ('IntersectionObserver' in window) {
      let wasIntersecting = false;
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            if (!wasIntersecting) {
              wasIntersecting = true;
              metricCards.forEach(el => animateMetric(el));
            }
          } else {
            if (wasIntersecting) {
              wasIntersecting = false;
              metricCards.forEach(el => resetMetric(el));
            }
          }
        });
      }, {
        threshold: 0.15,
        rootMargin: '0px 0px -40px 0px'
      });

      observer.observe(metricsSec);
    } else {
      // Fallback scroll listener
      let isVisible = false;
      function checkView() {
        const rect = metricsSec.getBoundingClientRect();
        const inView = rect.top < window.innerHeight * 0.85 && rect.bottom > 50;
        if (inView && !isVisible) {
          isVisible = true;
          metricCards.forEach(el => animateMetric(el));
        } else if (!inView && isVisible) {
          isVisible = false;
          metricCards.forEach(el => resetMetric(el));
        }
      }
      window.addEventListener('scroll', checkView, { passive: true });
      checkView();
    }
  }

  initMetricsAnimation();

  /* ==========================================================================
     7. LUXURY MOBILE DRAWER & BODY SCROLL LOCK
     ========================================================================== */
  const menuToggle = document.getElementById('menuToggle');
  const mobileDrawer = document.getElementById('mobileDrawer');

  function openMobileMenu() {
    if (!mobileDrawer || !menuToggle) return;
    mobileDrawer.classList.add('open');
    menuToggle.classList.add('active');
    document.body.classList.add('menu-open');
    menuToggle.setAttribute('aria-expanded', 'true');
  }

  function closeMobileMenu() {
    if (!mobileDrawer || !menuToggle) return;
    mobileDrawer.classList.remove('open');
    menuToggle.classList.remove('active');
    document.body.classList.remove('menu-open');
    menuToggle.setAttribute('aria-expanded', 'false');
  }

  if (menuToggle && mobileDrawer) {
    menuToggle.addEventListener('click', (e) => {
      e.stopPropagation();
      if (mobileDrawer.classList.contains('open')) {
        closeMobileMenu();
      } else {
        openMobileMenu();
      }
    });

    // Close when clicking any drawer link
    mobileDrawer.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        closeMobileMenu();
      });
    });

    // Close when pressing Escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && mobileDrawer.classList.contains('open')) {
        closeMobileMenu();
      }
    });
  }

  // Active Nav Links on Homepage Scroll
  const navItems = document.querySelectorAll('.nav-item');
  const sections = ['hero', 'manifesto', 'expertise', 'fleet', 'portfolio', 'contact'];

  if (navItems.length && document.getElementById('hero')) {
    window.addEventListener('scroll', () => {
      const scrollPos = window.scrollY + 200;
      sections.forEach((id, idx) => {
        const el = document.getElementById(id);
        if (el) {
          const top = el.offsetTop;
          const height = el.offsetHeight;
          if (scrollPos >= top && scrollPos < top + height) {
            navItems.forEach(item => item.classList.remove('active'));
            if (navItems[idx]) navItems[idx].classList.add('active');
          }
        }
      });
    }, { passive: true });
  }

  /* ==========================================================================
     8. PORTFOLIO CATEGORY FILTER (projets.html)
     ========================================================================== */
  const filterBtns = document.querySelectorAll('.filter-btn');
  const projectItems = document.querySelectorAll('.project-item');

  if (filterBtns.length && projectItems.length) {
    filterBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        filterBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const filter = btn.getAttribute('data-filter');
        projectItems.forEach(item => {
          if (filter === 'all' || item.getAttribute('data-category') === filter) {
            item.style.display = 'flex';
          } else {
            item.style.display = 'none';
          }
        });
      });
    });
  }

  /* ==========================================================================
     9. RFP FORM SUBMISSION (contact.html & index.html)
     ========================================================================== */
  const rfpForm = document.getElementById('rfpForm');
  const rfpSubmit = document.getElementById('rfpSubmit');
  const rfpAlert = document.getElementById('rfpAlert');

  if (rfpForm) {
    rfpForm.addEventListener('submit', (e) => {
      e.preventDefault();
      if (rfpSubmit) {
        rfpSubmit.disabled = true;
        rfpSubmit.innerHTML = '<span>Traitement du dossier en cours...</span>';
      }

      setTimeout(() => {
        if (rfpAlert) rfpAlert.classList.remove('hidden');
        rfpForm.reset();
        if (rfpSubmit) {
          rfpSubmit.disabled = false;
          rfpSubmit.innerHTML = '<span>Dossier Transmis avec Succès ✓</span>';
        }
      }, 700);
    });
  }
});
