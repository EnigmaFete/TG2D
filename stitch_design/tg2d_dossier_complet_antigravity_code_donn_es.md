# TG2D — DOSSIER TECHNIQUE COMPLET & CODE SOURCE INTÉGRAL
## Entreprise Générale Tous Corps d'État (BTP & Génie Civil — Maroc)
### Document prêt pour injection automatisée / Agent Antigravity

---

## 1. MÉTADONNÉES & STRUCTURE DU PROJET

- **Nom du Projet** : TG2D — Travaux Généraux de Développement Durable
- **Secteur** : BTP, Construction Tous Corps d'État (TCE), Génie Civil, Réhabilitation
- **Implantation** : Casablanca & Rayonnement National (Maroc)
- **Coordonnées de référence** :
  - Siège : 57 Rue Abou Alaazahar, Quartier des Hôpitaux, 20100 Casablanca, Maroc
  - Téléphone : +212 618-392328 / +212 (0) 5 22 XX XX XX
  - E-mail général : contact@tg2d.ma
  - E-mail technique / AO : contact@tg2d.ma
  - E-mail RH : recrutement@tg2d.ma
- **Framework & Stack Recommandée** : HTML5 sémantique, Tailwind CSS v3.4+, Lucide Icons / Heroicons, Google Fonts (Manrope & Inter).
- **Arborescence des Pages (Routes)** :
  1. `/` : `index.html` (Accueil)
  2. `/a-propos` : `a-propos.html` (À propos & Gouvernance)
  3. `/expertises` : `expertises.html` (Nos expertises TCE)
  4. `/realisations` : `realisations.html` (Nos réalisations & Projets)
  5. `/carrieres` : `carrieres.html` (Carrières & Recrutement RH)
  6. `/contact` : `contact.html` (Contact, Devis & Dépôt DCE)

---

## 2. DESIGN SYSTEM & JETONS DE CONCEPTION (DESIGN TOKENS)

```json
{
  "theme": {
    "fontFamily": {
      "sans": ["Manrope", "Inter", "system-ui", "sans-serif"],
      "mono": ["JetBrains Mono", "monospace"]
    },
    "colors": {
      "primary": {
        "DEFAULT": "#08bcd8",
        "hover": "#069eb6",
        "light": "#e6f8fa",
        "dark": "#057082"
      },
      "slate": {
        "50": "#f8fafc",
        "100": "#f1f5f9",
        "200": "#e2e8f0",
        "300": "#cbd5e1",
        "700": "#334155",
        "800": "#1e293b",
        "900": "#0f172a"
      },
      "surface": {
        "page": "#f7fafc",
        "card": "#ffffff",
        "border": "#e2e8f0"
      }
    },
    "borderRadius": {
      "card": "0.75rem",
      "button": "0.5rem"
    }
  }
}
```

---

## 3. ASSETS & RESSOURCES GRAPHIQUES
- **Logo TG2D** : `tg2d-logo.png` (Dimensions 1024x313, SVG/PNG transparent)
- **Image Hero Accueil** : `golden-hour-construction-crew.png` (Ouvrage BTP en cours, grue à tour, ingénieurs avec EPI au soleil couchant)
- **CDN Scripts requis** :
  - Tailwind CSS : `<script src="https://cdn.tailwindcss.com"></script>`
  - Google Fonts : `<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">`

---

## 4. TEMPLATES & CODE SOURCE COMPLET PAR PAGE

### PAGE 1 : ACCUEIL (`index.html`)

```html
<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accueil — TG2D BTP & Travaux Généraux</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { sans: ['Manrope', 'sans-serif'] },
          colors: { brand: { cyan: '#08bcd8', cyanHover: '#06a3bb', dark: '#0f172a', card: '#1e293b' } }
        }
      }
    }
  </script>
</head>
<body class="bg-[#f7fafc] text-slate-800 antialiased font-sans">

  <!-- NAVIGATION COMMUNE -->
  <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
      <div class="flex items-center gap-4">
        <a href="index.html" class="flex items-center gap-3">
          <div class="h-10 w-auto flex items-center font-extrabold text-2xl tracking-tighter text-[#08bcd8]">
            TG2D
          </div>
          <span class="hidden sm:inline-block border-l border-slate-200 pl-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500">
            Entreprise Générale Tous Corps d'État
          </span>
        </a>
      </div>
      <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-600">
        <a href="index.html" class="text-[#08bcd8] font-bold">Accueil</a>
        <a href="a-propos.html" class="hover:text-slate-900 transition-colors">À propos</a>
        <a href="expertises.html" class="hover:text-slate-900 transition-colors">Nos expertises</a>
        <a href="realisations.html" class="hover:text-slate-900 transition-colors">Nos réalisations</a>
        <a href="carrieres.html" class="hover:text-slate-900 transition-colors">Carrières</a>
        <a href="contact.html" class="hover:text-slate-900 transition-colors">Contact</a>
      </nav>
      <div class="flex items-center gap-4">
        <a href="contact.html" class="px-5 py-2.5 rounded-md bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold uppercase tracking-wider transition-all shadow-sm">
          Demander un devis
        </a>
      </div>
    </div>
  </header>

  <!-- 1. HERO BANNER IMMERSIF -->
  <section class="relative min-h-[580px] lg:min-h-[640px] flex items-center bg-slate-950 text-white overflow-hidden">
    <img src="assets/golden-hour-construction-crew.png" alt="Chantier TG2D au coucher du soleil" class="absolute inset-0 w-full h-full object-cover opacity-60">
    <div class="absolute inset-0 bg-gradient-to-r from-slate-950/90 via-slate-950/70 to-transparent"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 w-full">
      <div class="max-w-2xl">
        <div class="text-[#08bcd8] text-xs font-bold uppercase tracking-widest mb-3 flex items-center gap-2">
          <span class="w-2 h-2 rounded-full bg-[#08bcd8]"></span>
          Entreprise Générale Tous Corps d’État
        </div>
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight mb-4">
          Construire aujourd’hui.<br><span class="text-[#08bcd8]">Bâtir durablement.</span>
        </h1>
        <p class="text-base sm:text-lg text-slate-200 font-normal leading-relaxed mb-8">
          De Casablanca à l’ensemble du Royaume, nous concrétisons vos projets d’envergure avec un haut niveau d’exigence technique et environnementale.
        </p>
        <div class="flex flex-wrap items-center gap-4">
          <a href="realisations.html" class="px-6 py-3.5 rounded-md bg-[#08bcd8] hover:bg-[#06a3bb] text-slate-950 text-sm font-bold uppercase tracking-wider transition-all shadow-lg">
            Découvrir nos réalisations
          </a>
          <a href="contact.html" class="px-6 py-3.5 rounded-md bg-white/10 hover:bg-white/20 border border-white/20 backdrop-blur-md text-white text-sm font-bold uppercase tracking-wider transition-all">
            Nous contacter
          </a>
        </div>
      </div>
      <div class="mt-16 pt-6 border-t border-white/10 flex flex-wrap gap-6 text-[11px] font-mono tracking-wider text-slate-300">
        <div><span class="text-slate-400">ACTIVITÉ:</span> T.C.E. MAROC</div>
        <div><span class="text-slate-400">STANDARDS:</span> RPS2011 &amp; NM</div>
        <div><span class="text-slate-400">PÉRIMÈTRE:</span> AXE CASA-TANGER &amp; RÉGIONS</div>
      </div>
    </div>
  </section>

  <!-- 2. À PROPOS DE TG2D -->
  <section class="py-20 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid lg:grid-cols-12 gap-12 items-center">
        <div class="lg:col-span-5">
          <span class="text-xs font-bold uppercase tracking-widest text-[#08bcd8]">À propos de TG2D</span>
          <h2 class="text-3xl font-extrabold text-slate-900 mt-2 mb-6 leading-tight">
            Un partenaire de confiance pour vos projets de construction au Maroc.
          </h2>
          <a href="a-propos.html" class="inline-flex items-center text-sm font-bold text-[#08bcd8] hover:underline">
            Notre histoire &rarr;
          </a>
        </div>
        <div class="lg:col-span-7">
          <p class="text-slate-600 leading-relaxed mb-8">
            TG2D est une entreprise marocaine spécialisée dans la construction tous corps d’état, accompagnant ses clients dans la réalisation de projets résidentiels, commerciaux, industriels et logistiques. Basée à Casablanca, TG2D s’appuie sur plus de 3 années d’expérience, une équipe bénéficiant de plus de 20 années d’expérience dans le développement immobilier et une organisation capable de mobiliser les compétences nécessaires à la réalisation de projets avec un haut niveau d’exigence dans différentes régions du Maroc. Notre ambition est de devenir un partenaire de référence en plaçant la qualité, la rigueur et la satisfaction client au cœur de notre démarche.
          </p>
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="p-4 bg-slate-50 border border-slate-200 rounded-lg">
              <div class="text-2xl font-black text-slate-900">+3 ans</div>
              <div class="text-xs text-slate-500 mt-1">d'expérience sous la marque TG2D</div>
            </div>
            <div class="p-4 bg-slate-50 border border-slate-200 rounded-lg">
              <div class="text-2xl font-black text-slate-900">+20 ans</div>
              <div class="text-xs text-slate-500 mt-1">d'expertise équipes &amp; immobilier</div>
            </div>
            <div class="p-4 bg-slate-50 border border-slate-200 rounded-lg">
              <div class="text-2xl font-black text-slate-900">National</div>
              <div class="text-xs text-slate-500 mt-1">Casablanca &amp; tout le Maroc</div>
            </div>
            <div class="p-4 bg-slate-50 border border-slate-200 rounded-lg">
              <div class="text-2xl font-black text-slate-900">Qualité</div>
              <div class="text-xs text-slate-500 mt-1">Rigueur &amp; satisfaction client</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 3. NOS EXPERTISES (6 CARTES) -->
  <section class="py-20 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-end mb-12">
        <div>
          <span class="text-xs font-bold uppercase tracking-widest text-[#08bcd8]">Nos expertises</span>
          <h2 class="text-3xl font-extrabold text-slate-900 mt-2">Une approche globale de la construction</h2>
          <p class="text-slate-600 mt-2 max-w-2xl">
            TG2D propose une approche tous corps d’état, permettant à nos clients de bénéficier d’une coordination globale des différents intervenants du chantier.
          </p>
        </div>
        <a href="expertises.html" class="hidden md:inline-flex text-sm font-bold text-[#08bcd8] hover:underline">
          Découvrir toutes nos expertises &rarr;
        </a>
      </div>

      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="p-6 bg-white border border-slate-200 rounded-lg shadow-sm">
          <div class="text-xs font-mono font-bold text-[#08bcd8] mb-2">LOT 01 // TCE</div>
          <h3 class="text-lg font-bold text-slate-900 mb-2">Construction tous corps d’état</h3>
          <p class="text-sm text-slate-600">Prise en charge intégrale de la chaîne constructive avec un interlocuteur unique pour coordonner tous les intervenants.</p>
        </div>
        <div class="p-6 bg-white border border-slate-200 rounded-lg shadow-sm">
          <div class="text-xs font-mono font-bold text-[#08bcd8] mb-2">LOT 02 // STRUCT</div>
          <h3 class="text-lg font-bold text-slate-900 mb-2">Gros œuvre &amp; structure</h3>
          <p class="text-sm text-slate-600">Infrastructures et superstructures en béton armé, fondations spéciales et charpentes aux normes techniques rigoureuses.</p>
        </div>
        <div class="p-6 bg-white border border-slate-200 rounded-lg shadow-sm">
          <div class="text-xs font-mono font-bold text-[#08bcd8] mb-2">LOT 03 // FIN</div>
          <h3 class="text-lg font-bold text-slate-900 mb-2">Second œuvre &amp; finitions</h3>
          <p class="text-sm text-slate-600">Cloisonnement, revêtements, menuiseries et fluides réalisés avec une haute exigence esthétique et qualitative.</p>
        </div>
        <div class="p-6 bg-white border border-slate-200 rounded-lg shadow-sm">
          <div class="text-xs font-mono font-bold text-[#08bcd8] mb-2">LOT 04 // RET</div>
          <h3 class="text-lg font-bold text-slate-900 mb-2">Bâtiments commerciaux &amp; retail</h3>
          <p class="text-sm text-slate-600">Conception et agencement d'espaces de vente, showrooms et commerces respectant des plannings d'ouverture stricts.</p>
        </div>
        <div class="p-6 bg-white border border-slate-200 rounded-lg shadow-sm">
          <div class="text-xs font-mono font-bold text-[#08bcd8] mb-2">LOT 05 // IND</div>
          <h3 class="text-lg font-bold text-slate-900 mb-2">Projets industriels &amp; logistiques</h3>
          <p class="text-sm text-slate-600">Entrepôts, dallages industriels haute résistance, zones de stockage et plateformes répondant aux flux lourds.</p>
        </div>
        <div class="p-6 bg-white border border-slate-200 rounded-lg shadow-sm">
          <div class="text-xs font-mono font-bold text-[#08bcd8] mb-2">LOT 06 // RENOV</div>
          <h3 class="text-lg font-bold text-slate-900 mb-2">Rénovation &amp; réhabilitation</h3>
          <p class="text-sm text-slate-600">Restructuration lourde, renforcement structurel et valorisation énergétique de bâtiments existants en site occupé ou libre.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- 4. NOS RÉALISATIONS (3 PROJETS PHARE) -->
  <section class="py-20 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-end mb-12">
        <div>
          <span class="text-xs font-bold uppercase tracking-widest text-[#08bcd8]">Nos réalisations</span>
          <h2 class="text-3xl font-extrabold text-slate-900 mt-2">Des projets qui témoignent de notre savoir-faire</h2>
          <p class="text-slate-600 mt-2">Chaque chantier représente une nouvelle exigence et une nouvelle responsabilité.</p>
        </div>
        <a href="realisations.html" class="text-sm font-bold text-[#08bcd8] hover:underline">
          Voir l'ensemble des réalisations &rarr;
        </a>
      </div>

      <div class="grid md:grid-cols-3 gap-8">
        <!-- Projet 1 -->
        <div class="border border-slate-200 rounded-lg overflow-hidden bg-slate-50 flex flex-col justify-between">
          <div class="p-6">
            <div class="flex items-center justify-between text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">
              <span>Dar Bouazza</span>
              <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800">Commercial</span>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">Showroom BRICOMA</h3>
            <p class="text-sm text-slate-600 mb-4">Participation à la construction et la réalisation des travaux tous corps d’état du showroom commercial BRICOMA à Dar Bouazza. Coordination globale et finitions soignées.</p>
          </div>
          <div class="px-6 py-4 bg-slate-100 border-t border-slate-200 flex justify-between items-center text-xs">
            <span class="font-mono text-slate-500">RÉF // BR-DAR-01</span>
            <a href="realisations.html" class="font-bold text-[#08bcd8] hover:underline">Voir le projet &rarr;</a>
          </div>
        </div>

        <!-- Projet 2 -->
        <div class="border border-slate-200 rounded-lg overflow-hidden bg-slate-50 flex flex-col justify-between">
          <div class="p-6">
            <div class="flex items-center justify-between text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">
              <span>Bouskoura</span>
              <span class="px-2 py-0.5 rounded bg-sky-100 text-sky-800">Logistique &amp; Industriel</span>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">Centre Logistique</h3>
            <p class="text-sm text-slate-600 mb-4">Réalisation des travaux liés à la construction d’un centre logistique majeur. Coordination rigoureuse des corps d’état pour les exigences professionnelles et d’exploitation.</p>
          </div>
          <div class="px-6 py-4 bg-slate-100 border-t border-slate-200 flex justify-between items-center text-xs">
            <span class="font-mono text-slate-500">RÉF // LOG-BOU-02</span>
            <a href="realisations.html" class="font-bold text-[#08bcd8] hover:underline">Voir le projet &rarr;</a>
          </div>
        </div>

        <!-- Projet 3 -->
        <div class="border border-slate-200 rounded-lg overflow-hidden bg-slate-50 flex flex-col justify-between">
          <div class="p-6">
            <div class="flex items-center justify-between text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">
              <span>Casablanca &amp; Régions</span>
              <span class="px-2 py-0.5 rounded bg-indigo-100 text-indigo-800">Résidentiel</span>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">Projets Résidentiels</h3>
            <p class="text-sm text-slate-600 mb-4">Construction et travaux tous corps d’état pour projets résidentiels et villas standing, avec une attention rigoureuse à la qualité de structure et au niveau de finitions.</p>
          </div>
          <div class="px-6 py-4 bg-slate-100 border-t border-slate-200 flex justify-between items-center text-xs">
            <span class="font-mono text-slate-500">RÉF // RES-CAS-03</span>
            <a href="realisations.html" class="font-bold text-[#08bcd8] hover:underline">Voir le projet &rarr;</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 5. NOTRE ENGAGEMENT (4 PILIERS) -->
  <section class="py-20 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center max-w-3xl mx-auto mb-16">
        <span class="text-xs font-bold uppercase tracking-widest text-[#08bcd8]">Notre engagement</span>
        <h2 class="text-3xl font-extrabold text-slate-900 mt-2 mb-4">Qualité, sécurité et maîtrise des travaux</h2>
        <p class="text-slate-600">Nous plaçons l’exigence, la rigueur et la sécurité au cœur de chaque projet pour livrer des ouvrages durables, conformes et performants.</p>
      </div>

      <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="p-6 bg-white border border-slate-200 rounded-lg">
          <div class="text-3xl font-black text-[#08bcd8] mb-3">01</div>
          <h3 class="font-bold text-slate-900 mb-2">Conformité aux normes</h3>
          <p class="text-xs text-slate-600 leading-relaxed mb-4">Respect rigoureux des réglementations parasismiques marocaines RPS2011 et des Normes Marocaines (NM).</p>
          <a href="a-propos.html" class="text-xs font-bold text-[#08bcd8] hover:underline">En savoir plus &rarr;</a>
        </div>
        <div class="p-6 bg-white border border-slate-200 rounded-lg">
          <div class="text-3xl font-black text-[#08bcd8] mb-3">02</div>
          <h3 class="font-bold text-slate-900 mb-2">Contrôles techniques</h3>
          <p class="text-xs text-slate-600 leading-relaxed mb-4">Essais de sol, écrasements d’éprouvettes béton et validation continue avec des laboratoires agréés (LPEE).</p>
          <a href="a-propos.html" class="text-xs font-bold text-[#08bcd8] hover:underline">En savoir plus &rarr;</a>
        </div>
        <div class="p-6 bg-white border border-slate-200 rounded-lg">
          <div class="text-3xl font-black text-[#08bcd8] mb-3">03</div>
          <h3 class="font-bold text-slate-900 mb-2">Assurance &amp; garantie</h3>
          <p class="text-xs text-slate-600 leading-relaxed mb-4">Couverture complète Tous Risques Chantier (TRC), Responsabilité Civile et garantie décennale systématique.</p>
          <a href="a-propos.html" class="text-xs font-bold text-[#08bcd8] hover:underline">En savoir plus &rarr;</a>
        </div>
        <div class="p-6 bg-white border border-slate-200 rounded-lg">
          <div class="text-3xl font-black text-[#08bcd8] mb-3">04</div>
          <h3 class="font-bold text-slate-900 mb-2">Démarche HSE</h3>
          <p class="text-xs text-slate-600 leading-relaxed mb-4">Port des EPI obligatoire, protocoles de sécurité rigoureux et respect strict des conditions de travail sur le terrain.</p>
          <a href="a-propos.html" class="text-xs font-bold text-[#08bcd8] hover:underline">En savoir plus &rarr;</a>
        </div>
      </div>
    </div>
  </section>

  <!-- 6. VALEURS DIRECTRICES (6 PRINCIPES) -->
  <section class="py-20 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="mb-12">
        <span class="text-xs font-bold uppercase tracking-widest text-[#08bcd8]">Nos principes directeurs</span>
        <h2 class="text-3xl font-extrabold text-slate-900 mt-2">Les valeurs qui guident chaque chantier</h2>
        <p class="text-slate-600 mt-2">Une culture d’ingénierie et de rigueur opérationnelle partagée par l’ensemble de nos équipes.</p>
      </div>

      <div class="grid md:grid-cols-3 gap-6">
        <div class="p-6 border border-slate-200 rounded-lg bg-slate-50">
          <h3 class="text-base font-bold text-slate-900 mb-2">1. Qualité</h3>
          <p class="text-xs text-slate-600 leading-relaxed">Nous accordons une importance particulière à la qualité des matériaux, à la précision de l’exécution et au respect des exigences techniques du projet.</p>
        </div>
        <div class="p-6 border border-slate-200 rounded-lg bg-slate-50">
          <h3 class="text-base font-bold text-slate-900 mb-2">2. Rigueur</h3>
          <p class="text-xs text-slate-600 leading-relaxed">Une construction réussie nécessite une organisation rigoureuse. Nos équipes assurent le suivi et la coordination des différentes phases du chantier.</p>
        </div>
        <div class="p-6 border border-slate-200 rounded-lg bg-slate-50">
          <h3 class="text-base font-bold text-slate-900 mb-2">3. Respect des délais</h3>
          <p class="text-xs text-slate-600 leading-relaxed">Nous intégrons la planification et la coordination des différents intervenants afin de contribuer au respect du calendrier défini pour chaque projet.</p>
        </div>
        <div class="p-6 border border-slate-200 rounded-lg bg-slate-50">
          <h3 class="text-base font-bold text-slate-900 mb-2">4. Transparence</h3>
          <p class="text-xs text-slate-600 leading-relaxed">Nous privilégions une communication claire avec nos clients et partenaires afin de maintenir une bonne visibilité sur l’avancement du projet.</p>
        </div>
        <div class="p-6 border border-slate-200 rounded-lg bg-slate-50">
          <h3 class="text-base font-bold text-slate-900 mb-2">5. Sécurité</h3>
          <p class="text-xs text-slate-600 leading-relaxed">La sécurité des collaborateurs, des intervenants et des installations constitue un élément essentiel de notre approche du chantier.</p>
        </div>
        <div class="p-6 border border-slate-200 rounded-lg bg-slate-50">
          <h3 class="text-base font-bold text-slate-900 mb-2">6. Engagement</h3>
          <p class="text-xs text-slate-600 leading-relaxed">Nous considérons chaque projet comme un engagement à long terme avec notre client. Notre objectif est de construire une relation basée sur la confiance.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- 7. POURQUOI TG2D ? (5 ATOUTS DIFFÉRENCIATEURS) -->
  <section class="py-20 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid lg:grid-cols-12 gap-12 items-start">
        <div class="lg:col-span-4">
          <span class="text-xs font-bold uppercase tracking-widest text-[#08bcd8]">Pourquoi TG2D ?</span>
          <h2 class="text-3xl font-extrabold text-slate-900 mt-2 mb-6 leading-tight">
            Un partenaire unique pour vos projets de construction
          </h2>
          <p class="text-sm text-slate-600 mb-6">
            Faire appel à TG2D, c'est choisir un interlocuteur unique capable de piloter l'ensemble des dimensions techniques, administratives et calendaires de vos ouvrages.
          </p>
          <a href="contact.html" class="px-5 py-3 rounded-md bg-slate-900 text-white text-xs font-bold uppercase tracking-wider inline-block">
            Discuter avec nos ingénieurs
          </a>
        </div>
        <div class="lg:col-span-8 space-y-4">
          <div class="p-5 bg-white border border-slate-200 rounded-lg">
            <div class="font-bold text-slate-900 text-sm mb-1">01. Une approche tous corps d’état</div>
            <p class="text-xs text-slate-600">La coordination des différents corps de métier permet une approche globale du projet et facilite le suivi pour le client sans rupture de chaîne.</p>
          </div>
          <div class="p-5 bg-white border border-slate-200 rounded-lg">
            <div class="font-bold text-slate-900 text-sm mb-1">02. Une équipe expérimentée</div>
            <p class="text-xs text-slate-600">Une équipe bénéficiant de plus de 20 années d’expérience cumulée dans le développement immobilier, avec une maîtrise approfondie des enjeux de conception et de réalisation.</p>
          </div>
          <div class="p-5 bg-white border border-slate-200 rounded-lg">
            <div class="font-bold text-slate-900 text-sm mb-1">03. Une expérience régionale concrète</div>
            <p class="text-xs text-slate-600">Une expérience concrète développée à Casablanca et dans le Grand Casablanca, notamment à Dar Bouazza, Bouskoura et zones industrielles périphériques.</p>
          </div>
          <div class="p-5 bg-white border border-slate-200 rounded-lg">
            <div class="font-bold text-slate-900 text-sm mb-1">04. Une capacité d’intervention nationale</div>
            <p class="text-xs text-slate-600">TG2D dispose d’une organisation permettant d’étudier et d’accompagner des projets dans différentes régions du Maroc.</p>
          </div>
          <div class="p-5 bg-white border border-slate-200 rounded-lg">
            <div class="font-bold text-slate-900 text-sm mb-1">05. Une vision orientée client</div>
            <p class="text-xs text-slate-600">Nous cherchons à comprendre les objectifs du client avant de définir l’organisation et les moyens nécessaires à la réalisation du projet.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 8. BANDEAU CHIFFRES CLÉS (DARK SECTION) -->
  <section class="py-16 bg-slate-950 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-xs font-mono font-bold text-[#08bcd8] tracking-widest uppercase mb-8">Repères chiffrés // TG2D en quelques chiffres</div>
      <div class="grid grid-cols-2 md:grid-cols-6 gap-6 text-center md:text-left">
        <div>
          <div class="text-3xl lg:text-4xl font-extrabold text-[#08bcd8]">+3 ans</div>
          <div class="text-xs text-slate-400 mt-1">d’expérience dans le secteur BTP</div>
        </div>
        <div>
          <div class="text-3xl lg:text-4xl font-extrabold text-[#08bcd8]">+20 ans</div>
          <div class="text-xs text-slate-400 mt-1">d’expertise en développement immobilier</div>
        </div>
        <div>
          <div class="text-2xl lg:text-3xl font-extrabold text-white">Casablanca</div>
          <div class="text-xs text-slate-400 mt-1">notre implantation historique</div>
        </div>
        <div>
          <div class="text-2xl lg:text-3xl font-extrabold text-white">Grand Casa</div>
          <div class="text-xs text-slate-400 mt-1">une région majeure d’expérience</div>
        </div>
        <div>
          <div class="text-2xl lg:text-3xl font-extrabold text-white">Maroc</div>
          <div class="text-xs text-slate-400 mt-1">capacité d’intervention nationale</div>
        </div>
        <div>
          <div class="text-2xl lg:text-3xl font-extrabold text-[#08bcd8]">T.C.E.</div>
          <div class="text-xs text-slate-400 mt-1">approche globale de la construction</div>
        </div>
      </div>
    </div>
  </section>

  <!-- 9. NOTRE APPROCHE (5 ÉTAPES) -->
  <section class="py-20 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="mb-16">
        <span class="text-xs font-bold uppercase tracking-widest text-[#08bcd8]">Notre approche</span>
        <h2 class="text-3xl font-extrabold text-slate-900 mt-2">De votre vision à la réalisation</h2>
        <p class="text-slate-600 mt-2 max-w-2xl">
          Chaque projet commence par l’écoute. Nous analysons les besoins du client, les contraintes du site, les exigences techniques et les objectifs du projet afin de mettre en place une organisation adaptée.
        </p>
      </div>

      <div class="grid sm:grid-cols-2 lg:grid-cols-5 gap-6">
        <div class="p-6 bg-slate-50 border border-slate-200 rounded-lg">
          <div class="text-xs font-mono font-bold text-slate-400 mb-2">ÉTAPE 01</div>
          <h3 class="font-bold text-slate-900 text-sm mb-2">Écoute &amp; analyse</h3>
          <p class="text-xs text-slate-600">Comprendre votre projet, vos objectifs et vos contraintes techniques ou financières.</p>
        </div>
        <div class="p-6 bg-slate-50 border border-slate-200 rounded-lg">
          <div class="text-xs font-mono font-bold text-slate-400 mb-2">ÉTAPE 02</div>
          <h3 class="font-bold text-slate-900 text-sm mb-2">Préparation</h3>
          <p class="text-xs text-slate-600">Définir les besoins techniques, les ressources matérielles et l’organisation du chantier.</p>
        </div>
        <div class="p-6 bg-slate-50 border border-slate-200 rounded-lg">
          <div class="text-xs font-mono font-bold text-slate-400 mb-2">ÉTAPE 03</div>
          <h3 class="font-bold text-slate-900 text-sm mb-2">Coordination</h3>
          <p class="text-xs text-slate-600">Mobiliser et coordonner les différents corps de métier, intervenants et bureaux d'études.</p>
        </div>
        <div class="p-6 bg-slate-50 border border-slate-200 rounded-lg">
          <div class="text-xs font-mono font-bold text-slate-400 mb-2">ÉTAPE 04</div>
          <h3 class="font-bold text-slate-900 text-sm mb-2">Réalisation</h3>
          <p class="text-xs text-slate-600">Assurer le suivi des travaux avec une attention constante portée à la qualité et à la conformité.</p>
        </div>
        <div class="p-6 bg-slate-50 border border-slate-200 rounded-lg">
          <div class="text-xs font-mono font-bold text-slate-400 mb-2">ÉTAPE 05</div>
          <h3 class="font-bold text-slate-900 text-sm mb-2">Livraison</h3>
          <p class="text-xs text-slate-600">Finaliser le projet et accompagner le client jusqu’à la réception effective des travaux.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- 10. CLIENTS & PARTENAIRES -->
  <section class="py-20 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="mb-12">
        <span class="text-xs font-bold uppercase tracking-widest text-[#08bcd8]">Nos clients &amp; partenaires</span>
        <h2 class="text-3xl font-extrabold text-slate-900 mt-2">Des partenaires de confiance à travers tout le Royaume</h2>
        <p class="text-slate-600 mt-2">TG2D accompagne différents profils de clients et donneurs d’ordre.</p>
      </div>

      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-12">
        <div class="p-4 bg-white border border-slate-200 rounded-lg text-center font-bold text-xs text-slate-800">Particuliers</div>
        <div class="p-4 bg-white border border-slate-200 rounded-lg text-center font-bold text-xs text-slate-800">Entreprises</div>
        <div class="p-4 bg-white border border-slate-200 rounded-lg text-center font-bold text-xs text-slate-800">Promoteurs Immobiliers</div>
        <div class="p-4 bg-white border border-slate-200 rounded-lg text-center font-bold text-xs text-slate-800">Investisseurs</div>
        <div class="p-4 bg-white border border-slate-200 rounded-lg text-center font-bold text-xs text-slate-800">Acteurs Commerciaux</div>
        <div class="p-4 bg-white border border-slate-200 rounded-lg text-center font-bold text-xs text-slate-800">Opérateurs Logistiques</div>
      </div>

      <div class="p-6 bg-white border border-slate-200 rounded-lg">
        <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-4">Écosystème &amp; Parties Prenantes</div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs font-semibold text-slate-700">
          <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-[#08bcd8]"></span> Architectes &amp; Maîtrises d’Œuvre</div>
          <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-[#08bcd8]"></span> Bureaux d’Études Techniques (BET)</div>
          <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-[#08bcd8]"></span> Entreprises Générales Partenaires</div>
          <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-[#08bcd8]"></span> Écosystème BTP du Maroc</div>
        </div>
      </div>
    </div>
  </section>

  <!-- 11. CTA FINAL -->
  <section class="py-20 bg-slate-950 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="p-10 lg:p-14 rounded-2xl bg-slate-900 border border-slate-800 flex flex-col lg:flex-row items-center justify-between gap-8">
        <div class="max-w-2xl">
          <div class="text-xs font-mono font-bold uppercase tracking-widest text-[#08bcd8] mb-2">Notre Projet, Notre Engagement</div>
          <h2 class="text-3xl sm:text-4xl font-extrabold mb-4">Vous avez un projet ? Construisons-le ensemble.</h2>
          <p class="text-slate-300 text-sm leading-relaxed mb-6">
            Vous avez un projet résidentiel, commercial, industriel ou logistique au Maroc ? TG2D met son expertise, ses équipes et son savoir-faire au service de votre projet. Que vous soyez particulier, entreprise, promoteur ou investisseur, nous sommes à votre disposition pour étudier vos besoins et vous proposer une solution adaptée.
          </p>
          <div class="text-xs font-mono text-slate-400">
            TG2D — Construction Tous Corps d’État | Résidentiel • Commercial • Industriel • Logistique • Casablanca • Maroc
          </div>
        </div>
        <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto shrink-0">
          <a href="contact.html" class="px-6 py-3.5 rounded-md bg-[#08bcd8] hover:bg-[#06a3bb] text-slate-950 text-xs font-bold uppercase tracking-wider text-center">
            Demander un devis
          </a>
          <a href="tel:+212618392328" class="px-6 py-3.5 rounded-md bg-slate-800 hover:bg-slate-700 text-white border border-slate-700 text-xs font-bold uppercase tracking-wider text-center">
            +212 618-392328
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- FOOTER OFFICIEL -->
  <footer class="bg-slate-900 text-slate-400 py-16 border-t border-slate-800 text-xs">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-10">
      <div>
        <div class="text-xl font-black text-[#08bcd8] tracking-tight mb-3">TG2D</div>
        <p class="leading-relaxed mb-4">
          TG2D — Travaux Généraux de Développement Durable est une entreprise marocaine spécialisée dans la construction tous corps d’état, au service de projets résidentiels, commerciaux, industriels et logistiques.
        </p>
        <div class="font-mono text-[10px] text-slate-500 uppercase">Construction Tous Corps d'État</div>
      </div>
      <div>
        <h4 class="font-bold text-white uppercase tracking-wider text-xs mb-4">Navigation</h4>
        <ul class="space-y-2">
          <li><a href="index.html" class="hover:text-white">Accueil</a></li>
          <li><a href="a-propos.html" class="hover:text-white">À propos</a></li>
          <li><a href="expertises.html" class="hover:text-white">Nos expertises</a></li>
          <li><a href="realisations.html" class="hover:text-white">Nos réalisations</a></li>
          <li><a href="carrieres.html" class="hover:text-white">Carrières</a></li>
          <li><a href="contact.html" class="hover:text-white">Contact</a></li>
        </ul>
      </div>
      <div>
        <h4 class="font-bold text-white uppercase tracking-wider text-xs mb-4">Nos expertises</h4>
        <ul class="space-y-2">
          <li>Construction TCE</li>
          <li>Gros œuvre &amp; Structure</li>
          <li>Second œuvre &amp; Finitions</li>
          <li>Bâtiments Commerciaux &amp; Retail</li>
          <li>Projets Industriels &amp; Logistiques</li>
          <li>Rénovation &amp; Réhabilitation</li>
        </ul>
      </div>
      <div>
        <h4 class="font-bold text-white uppercase tracking-wider text-xs mb-4">Coordonnées</h4>
        <p class="mb-2 text-slate-300">57 Rue Abou Alaazahar, Quartier des Hôpitaux, 20100 Casablanca, Maroc</p>
        <p class="mb-2"><a href="tel:+212618392328" class="text-white hover:underline">+212 618-392328</a></p>
        <p class="mb-2"><a href="mailto:contact@tg2d.ma" class="text-[#08bcd8] hover:underline">contact@tg2d.ma</a></p>
        <p class="text-slate-500">Lun - Sam : 08h00 - 19h00</p>
      </div>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pt-8 border-t border-slate-800 flex flex-col sm:flex-row justify-between text-[11px] text-slate-500">
      <div>&copy; 2026 TG2D — Travaux Généraux de Développement Durable. Tous droits réservés.</div>
      <div class="flex gap-6 mt-4 sm:mt-0">
        <a href="#" class="hover:text-slate-400">Mentions légales</a>
        <a href="#" class="hover:text-slate-400">Confidentialité</a>
      </div>
    </div>
  </footer>

</body>
</html>
```

---

### PAGE 2 : CONTACT & DEVIS (`contact.html`)
*(Contient le formulaire d'expression de besoin technique complet, les vérifications d'instruction et la FAQ pour maîtres d'ouvrage)*

```html
<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact & Devis — TG2D BTP & Travaux Généraux</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <script>
    tailwind.config = {
      theme: { extend: { fontFamily: { sans: ['Manrope', 'sans-serif'] } } }
    }
  </script>
</head>
<body class="bg-[#f7fafc] text-slate-800 antialiased font-sans">

  <!-- En-tête standard identique -->
  <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
      <div class="flex items-center gap-4">
        <a href="index.html" class="font-extrabold text-2xl tracking-tighter text-[#08bcd8]">TG2D</a>
        <span class="hidden sm:inline-block border-l border-slate-200 pl-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500">
          Contact &amp; Direction Technique
        </span>
      </div>
      <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-600">
        <a href="index.html" class="hover:text-slate-900">Accueil</a>
        <a href="a-propos.html" class="hover:text-slate-900">À propos</a>
        <a href="expertises.html" class="hover:text-slate-900">Nos expertises</a>
        <a href="realisations.html" class="hover:text-slate-900">Nos réalisations</a>
        <a href="carrieres.html" class="hover:text-slate-900">Carrières</a>
        <a href="contact.html" class="text-[#08bcd8] font-bold">Contact</a>
      </nav>
      <a href="contact.html" class="px-5 py-2.5 rounded-md bg-slate-900 text-white text-xs font-bold uppercase tracking-wider">Demander un devis</a>
    </div>
  </header>

  <main class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-12">
      <div class="text-xs font-mono font-bold text-[#08bcd8] uppercase tracking-widest mb-2">Bureau Technique &amp; Relation Client // REF-TG2D-DCE-2024</div>
      <h1 class="text-4xl font-extrabold text-slate-900 mb-4">Parlons de votre projet</h1>
      <p class="text-slate-600 max-w-3xl leading-relaxed">
        Échangeons sur vos contraintes techniques, vos délais et la faisabilité de votre ouvrage de construction ou de génie civil. Nos équipes d'ingénieurs analysent vos dossiers de consultation et vous accompagnent avec rigueur.
      </p>
      <div class="mt-6 flex flex-wrap gap-8 text-xs font-semibold text-slate-600 border-y border-slate-200 py-3">
        <div>Délai d'analyse : <span class="text-slate-900 font-bold">48h ouvrées</span></div>
        <div>Bureau d'études : <span class="text-slate-900 font-bold">Génie Civil &amp; VRD</span></div>
        <div>Normes : <span class="text-slate-900 font-bold">HQE / BTP Durable</span></div>
        <div>Périmètre : <span class="text-slate-900 font-bold">Maroc &amp; Régions</span></div>
      </div>
    </div>

    <div class="grid lg:grid-cols-12 gap-12">
      <!-- Coordonnées & Processus -->
      <div class="lg:col-span-5 space-y-6">
        <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-sm space-y-4">
          <h3 class="font-bold text-slate-900 text-sm border-b pb-3">Coordonnées directes du bureau</h3>
          <div>
            <div class="text-xs text-slate-500 font-medium">Courriel officiel (Appels d'offres &amp; DCE)</div>
            <a href="mailto:contact@tg2d.ma" class="font-bold text-[#08bcd8] text-sm">contact@tg2d.ma</a>
          </div>
          <div>
            <div class="text-xs text-slate-500 font-medium">Téléphone standard</div>
            <div class="font-bold text-slate-900 text-sm">+212 (0) 5 22 XX XX XX / +212 618-392328</div>
          </div>
          <div>
            <div class="text-xs text-slate-500 font-medium">Bureau central &amp; Siège</div>
            <div class="text-slate-700 text-xs">57 Rue Abou Alaazahar, Quartier des Hôpitaux, 20100 Casablanca</div>
          </div>
          <div>
            <div class="text-xs text-slate-500 font-medium">Plages de réception technique</div>
            <div class="text-slate-700 text-xs">Lundi au Vendredi : 08h30 – 18h00</div>
          </div>
        </div>

        <div class="bg-slate-50 p-6 rounded-lg border border-slate-200 space-y-4">
          <h3 class="font-bold text-slate-900 text-sm">Protocole d'instruction de votre dossier</h3>
          <div class="text-xs text-slate-600 space-y-3">
            <div><strong class="text-slate-800">1. Vérification &amp; Complétude</strong> : Contrôle des plans d'implantation, CCTP, études de sol et pièces marché.</div>
            <div><strong class="text-slate-800">2. Faisabilité &amp; Dimensionnement</strong> : Analyse des cadences engins et intégration éco-responsable.</div>
            <div><strong class="text-slate-800">3. Cadrage direct &amp; Devis</strong> : Entretien de cadrage avec la direction des travaux et transmission de l'offre chiffrée.</div>
          </div>
        </div>
      </div>

      <!-- Formulaire d'expression de besoin technique -->
      <div class="lg:col-span-7 bg-white p-8 rounded-lg border border-slate-200 shadow-sm">
        <h3 class="font-bold text-lg text-slate-900 mb-1">Formulaire d'expression de besoin technique</h3>
        <p class="text-xs text-slate-500 mb-6">Les champs annotés d'un astérisque (*) sont requis pour l'instruction par nos ingénieurs.</p>
        <form class="space-y-4 text-xs">
          <div class="grid sm:grid-cols-2 gap-4">
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Nom et prénom *</label>
              <input type="text" placeholder="Ex. Youssef El Mansouri" class="w-full px-3 py-2 border rounded border-slate-300">
            </div>
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Entreprise / Maître d'ouvrage</label>
              <input type="text" placeholder="Ex. Société d'Aménagement" class="w-full px-3 py-2 border rounded border-slate-300">
            </div>
          </div>
          <div class="grid sm:grid-cols-2 gap-4">
            <div>
              <label class="block font-semibold text-slate-700 mb-1">E-mail professionnel *</label>
              <input type="email" placeholder="nom@entreprise.ma" class="w-full px-3 py-2 border rounded border-slate-300">
            </div>
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Téléphone de contact *</label>
              <input type="tel" placeholder="+212 6 XX XX XX XX" class="w-full px-3 py-2 border rounded border-slate-300">
            </div>
          </div>
          <div class="grid sm:grid-cols-2 gap-4">
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Type de prestation souhaitée *</label>
              <select class="w-full px-3 py-2 border rounded border-slate-300">
                <option>Construction Tous Corps d'État (TCE)</option>
                <option>Gros œuvre &amp; Génie Civil</option>
                <option>Projet Industriel ou Logistique</option>
                <option>Bâtiment Commercial ou Showroom</option>
                <option>Rénovation &amp; Réhabilitation lourde</option>
              </select>
            </div>
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Localisation du chantier *</label>
              <input type="text" placeholder="Casablanca, Tanger, Rabat..." class="w-full px-3 py-2 border rounded border-slate-300">
            </div>
          </div>
          <div>
            <label class="block font-semibold text-slate-700 mb-1">Description succincte du besoin *</label>
            <textarea rows="4" placeholder="Précisez la nature des travaux, les délais d'exécution prévus, les contraintes d'accès..." class="w-full px-3 py-2 border rounded border-slate-300"></textarea>
          </div>
          <div class="border-2 border-dashed border-slate-200 rounded-lg p-6 text-center bg-slate-50">
            <div class="text-xs text-slate-500 font-medium">Glissez-déposez vos pièces techniques (PDF, DWG, ZIP jusqu'à 25 Mo)</div>
            <div class="text-[10px] text-slate-400 mt-1">CCTP, DPGF, plans d'architecte ou études géotechniques</div>
          </div>
          <div class="pt-4 flex items-center justify-between">
            <div class="text-[11px] text-slate-500">Réponse sous 48h ouvrées par un chargé d'affaires</div>
            <button type="submit" class="px-6 py-3 bg-slate-900 text-white font-bold rounded uppercase tracking-wider hover:bg-slate-800">
              Envoyer ma demande technique &rarr;
            </button>
          </div>
        </form>
      </div>
    </div>
  </main>

  <footer class="bg-slate-900 text-slate-400 py-12 border-t border-slate-800 text-xs text-center">
    <p>&copy; 2026 TG2D — Travaux Généraux de Développement Durable. Tous droits réservés.</p>
  </footer>
</body>
</html>
```

---

### PAGE 3 : À PROPOS (`a-propos.html`)
*(Contient l'historique, la méthodologie, l'organisation en 3 divisions et les chartes HSE/Qualité)*

```html
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>À propos — TG2D BTP & Travaux Généraux</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <script>tailwind.config = { theme: { extend: { fontFamily: { sans: ['Manrope', 'sans-serif'] } } } }</script>
</head>
<body class="bg-[#f7fafc] text-slate-800 antialiased font-sans">
  <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 h-20 flex items-center justify-between">
      <a href="index.html" class="font-extrabold text-2xl text-[#08bcd8]">TG2D</a>
      <nav class="hidden md:flex gap-8 text-sm font-medium text-slate-600">
        <a href="index.html">Accueil</a>
        <a href="a-propos.html" class="text-[#08bcd8] font-bold">À propos</a>
        <a href="expertises.html">Nos expertises</a>
        <a href="realisations.html">Nos réalisations</a>
        <a href="carrieres.html">Carrières</a>
        <a href="contact.html">Contact</a>
      </nav>
      <a href="contact.html" class="px-5 py-2.5 rounded bg-slate-900 text-white text-xs font-bold uppercase">Demander un devis</a>
    </div>
  </header>

  <main class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
    <section>
      <span class="text-xs font-mono font-bold text-[#08bcd8] uppercase tracking-widest">Présentation Institutionnelle</span>
      <h1 class="text-4xl font-extrabold text-slate-900 mt-2 mb-4">À propos de TG2D</h1>
      <p class="text-slate-600 max-w-3xl leading-relaxed">
        Une structure d’ingénierie et de travaux tout corps d’état alliant rigueur d’exécution, transparence méthodologique et engagement pour des constructions durables au Maroc.
      </p>
    </section>

    <!-- Pôles Opérationnels -->
    <section class="grid md:grid-cols-3 gap-6">
      <div class="p-6 bg-white border border-slate-200 rounded-lg">
        <div class="text-xs font-bold text-[#08bcd8] uppercase mb-2">Division Études</div>
        <h3 class="text-lg font-bold text-slate-900 mb-2">Bureau d'Études &amp; Méthodes</h3>
        <p class="text-xs text-slate-600 leading-relaxed">Assure le cadrage économique, la validation structurelle et la faisabilité constructif en amont de toute mobilisation matérielle.</p>
      </div>
      <div class="p-6 bg-white border border-slate-200 rounded-lg">
        <div class="text-xs font-bold text-[#08bcd8] uppercase mb-2">Division Chantier</div>
        <h3 class="text-lg font-bold text-slate-900 mb-2">Conduite de Travaux &amp; Supervision</h3>
        <p class="text-xs text-slate-600 leading-relaxed">Garantit l’exécution conforme sur le terrain, le management des équipes et le respect scrupuleux des délais fixés.</p>
      </div>
      <div class="p-6 bg-white border border-slate-200 rounded-lg">
        <div class="text-xs font-bold text-[#08bcd8] uppercase mb-2">Division Approvisionnement</div>
        <h3 class="text-lg font-bold text-slate-900 mb-2">Logistique, Matériel &amp; Achats</h3>
        <p class="text-xs text-slate-600 leading-relaxed">Assure la continuité logistique des chantiers grâce à des partenariats industriels éprouvés et un parc matériel entretenu.</p>
      </div>
    </section>
  </main>
</body>
</html>
```

---

### PAGE 4 : NOS EXPERTISES (`expertises.html`)
*(Contient les 6 fiches techniques détaillées de chaque lot : Gros œuvre, Génie civil, VRD, Étanchéité, Second œuvre, Coordination TCE)*

```html
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nos expertises — TG2D BTP & Travaux Généraux</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <script>tailwind.config = { theme: { extend: { fontFamily: { sans: ['Manrope', 'sans-serif'] } } } }</script>
</head>
<body class="bg-[#f7fafc] text-slate-800 antialiased font-sans">
  <header class="sticky top-0 z-50 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 h-20 flex items-center justify-between">
      <a href="index.html" class="font-extrabold text-2xl text-[#08bcd8]">TG2D</a>
      <nav class="hidden md:flex gap-8 text-sm font-medium text-slate-600">
        <a href="index.html">Accueil</a>
        <a href="a-propos.html">À propos</a>
        <a href="expertises.html" class="text-[#08bcd8] font-bold">Nos expertises</a>
        <a href="realisations.html">Nos réalisations</a>
        <a href="carrieres.html">Carrières</a>
        <a href="contact.html">Contact</a>
      </nav>
      <a href="contact.html" class="px-5 py-2.5 rounded bg-slate-900 text-white text-xs font-bold uppercase">Demander un devis</a>
    </div>
  </header>

  <main class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
    <div>
      <span class="text-xs font-mono font-bold text-[#08bcd8] uppercase tracking-widest">Pôles d'Ingénierie &amp; Corps d'État</span>
      <h1 class="text-4xl font-extrabold text-slate-900 mt-2 mb-4">Nos domaines d'intervention technique</h1>
      <p class="text-slate-600 max-w-3xl">TG2D déploie son savoir-faire d'entreprise générale et de pilotage technique sur les corps d'état fondamentaux de la construction et des infrastructures durables au Maroc.</p>
    </div>

    <!-- LOT 01 -->
    <div class="p-8 bg-white border border-slate-200 rounded-lg shadow-sm space-y-4">
      <div class="flex justify-between items-center text-xs font-mono text-[#08bcd8] font-bold">
        <span>LOT 01 // CODE: BAT-GO-01</span>
        <span>BÉTON ARMÉ &amp; SUPERSTRUCTURES</span>
      </div>
      <h2 class="text-2xl font-bold text-slate-900">Bâtiment, Structures &amp; Gros Œuvre</h2>
      <p class="text-sm text-slate-600">Réalisation des infrastructures et superstructures selon les règles de l'art (Eurocodes 2, règles BAEL 91 modifiées 99, RPS 2000/2011). Maîtrise rigoureuse des banches, coffrages spéciaux, ferraillages à haute adhérence et coulage de bétons techniques (BAP, bétons hydrofuges, bétons bas carbone).</p>
      <div class="grid sm:grid-cols-2 gap-4 text-xs bg-slate-50 p-4 rounded border">
        <div><strong>Périmètres :</strong> Semelles isolées, radiers, puits d'assise, voiles périphériques, planchers précontraints.</div>
        <div><strong>Contrôle &amp; Livrables :</strong> Fiches de coulage, agrément aciers, écrasement d'éprouvettes LPEE certifié.</div>
      </div>
    </div>

    <!-- LOT 02 -->
    <div class="p-8 bg-white border border-slate-200 rounded-lg shadow-sm space-y-4">
      <div class="flex justify-between items-center text-xs font-mono text-[#08bcd8] font-bold">
        <span>LOT 02 // CODE: GC-FOND-02</span>
        <span>OUVRAGES HYDRAULIQUES &amp; SPÉCIAUX</span>
      </div>
      <h2 class="text-2xl font-bold text-slate-900">Génie Civil &amp; Ouvrages d'Art</h2>
      <p class="text-sm text-slate-600">Infrastructures lourdes, bassins techniques, réservoirs et structures industrielles soumis à de fortes contraintes mécaniques ou chimiques.</p>
      <div class="grid sm:grid-cols-2 gap-4 text-xs bg-slate-50 p-4 rounded border">
        <div><strong>Périmètres :</strong> Radier étanche, cuvelage, réservoirs d'eau potable, murs de soutènement et dallages haute résistance.</div>
        <div><strong>Normes :</strong> Fascicule 74 / Eurocode 7 (Géotech) / DTR Maroc.</div>
      </div>
    </div>
  </main>
</body>
</html>
```

---

### PAGE 5 : NOS RÉALISATIONS (`realisations.html`)
*(Contient la grille complète de chantiers livrés avec fiches techniques : Showroom BRICOMA, Plateforme Logistique, Ensemble Résidentiel, Bassin de Décantation, etc.)*

```html
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nos réalisations — TG2D BTP & Travaux Généraux</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <script>tailwind.config = { theme: { extend: { fontFamily: { sans: ['Manrope', 'sans-serif'] } } } }</script>
</head>
<body class="bg-[#f7fafc] text-slate-800 antialiased font-sans">
  <header class="sticky top-0 z-50 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 h-20 flex items-center justify-between">
      <a href="index.html" class="font-extrabold text-2xl text-[#08bcd8]">TG2D</a>
      <nav class="hidden md:flex gap-8 text-sm font-medium text-slate-600">
        <a href="index.html">Accueil</a>
        <a href="a-propos.html">À propos</a>
        <a href="expertises.html">Nos expertises</a>
        <a href="realisations.html" class="text-[#08bcd8] font-bold">Nos réalisations</a>
        <a href="carrieres.html">Carrières</a>
        <a href="contact.html">Contact</a>
      </nav>
      <a href="contact.html" class="px-5 py-2.5 rounded bg-slate-900 text-white text-xs font-bold uppercase">Demander un devis</a>
    </div>
  </header>

  <main class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
    <div>
      <span class="text-xs font-mono font-bold text-[#08bcd8] uppercase tracking-widest">Portfolio d'Ouvrages Livrés</span>
      <h1 class="text-4xl font-extrabold text-slate-900 mt-2 mb-4">Nos réalisations d'ouvrages &amp; d'infrastructures</h1>
      <p class="text-slate-600 max-w-3xl">Une sélection de projets conduits avec rigueur méthodologique, maîtrise prédictive des calendriers et respect scrupuleux des exigences de nos maîtres d'ouvrage.</p>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div class="bg-white border rounded-lg p-6 space-y-4">
        <div class="text-xs font-mono text-slate-400">RÉF. TG2D-TER-01 // CASABLANCA</div>
        <h3 class="text-lg font-bold text-slate-900">Complexe Administratif &amp; Siège Tertiaire</h3>
        <p class="text-xs text-slate-600">Entreprise Générale — Gros Œuvre, Voiles BAP, Étanchéité SBS et VRD Privatifs. R+5 avec niveau sous-sol cuvelé.</p>
      </div>
      <div class="bg-white border rounded-lg p-6 space-y-4">
        <div class="text-xs font-mono text-slate-400">RÉF. TG2D-LOG-02 // MOHAMMEDIA</div>
        <h3 class="text-lg font-bold text-slate-900">Plateforme Logistique &amp; Pôle de Distribution</h3>
        <p class="text-xs text-slate-600">Gros Œuvre, Dallage industriel haute charge &amp; VRD Lourds (40T trafic intense). Surface couverte : 12 800 m².</p>
      </div>
      <div class="bg-white border rounded-lg p-6 space-y-4">
        <div class="text-xs font-mono text-slate-400">RÉF. TG2D-RES-03 // BOUSKOURA</div>
        <h3 class="text-lg font-bold text-slate-900">Ensemble Résidentiel Contemporain &amp; Villas</h3>
        <p class="text-xs text-slate-600">Bâtiment Gros Œuvre soigné &amp; Finitions d’étanchéité inversée. Béton blanc architectonique brut de décoffrage.</p>
      </div>
    </div>
  </main>
</body>
</html>
```

---

### PAGE 6 : CARRIÈRES & RECRUTEMENT (`carrieres.html`)
*(Contient les offres d'emploi pour conducteurs de travaux, ingénieurs méthodes/chiffrage, chefs de chantier, ainsi que le formulaire de candidature)*

```html
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carrières & Recrutement — TG2D BTP & Travaux Généraux</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <script>tailwind.config = { theme: { extend: { fontFamily: { sans: ['Manrope', 'sans-serif'] } } } }</script>
</head>
<body class="bg-[#f7fafc] text-slate-800 antialiased font-sans">
  <header class="sticky top-0 z-50 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 h-20 flex items-center justify-between">
      <a href="index.html" class="font-extrabold text-2xl text-[#08bcd8]">TG2D</a>
      <nav class="hidden md:flex gap-8 text-sm font-medium text-slate-600">
        <a href="index.html">Accueil</a>
        <a href="a-propos.html">À propos</a>
        <a href="expertises.html">Nos expertises</a>
        <a href="realisations.html">Nos réalisations</a>
        <a href="carrieres.html" class="text-[#08bcd8] font-bold">Carrières</a>
        <a href="contact.html">Contact</a>
      </nav>
      <a href="contact.html" class="px-5 py-2.5 rounded bg-slate-900 text-white text-xs font-bold uppercase">Demander un devis</a>
    </div>
  </header>

  <main class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
    <div>
      <span class="text-xs font-mono font-bold text-[#08bcd8] uppercase tracking-widest">RH &amp; Développement des Talents</span>
      <h1 class="text-4xl font-extrabold text-slate-900 mt-2 mb-4">Rejoignez l'exigence technique sur le terrain et en bureau d'études</h1>
      <p class="text-slate-600 max-w-3xl">TG2D recrute des ingénieurs méthodes, des conducteurs de travaux et des profils techniques rigoureux pour piloter des chantiers de bâtiment et génie civil à fort niveau de conformité.</p>
    </div>

    <!-- Postes ouverts -->
    <div class="space-y-4">
      <div class="p-6 bg-white border border-slate-200 rounded-lg flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <span class="text-xs font-mono text-[#08bcd8] font-bold">RÉF : REC-CT-01 // CDI // EXP 5-8 ANS</span>
          <h3 class="text-lg font-bold text-slate-900">Conducteur de Travaux Principal — Gros Œuvre &amp; TCE</h3>
          <p class="text-xs text-slate-500">Casablanca / Chantiers Axe Casa-Tanger</p>
        </div>
        <a href="#candidature" class="px-4 py-2 bg-slate-900 text-white rounded text-xs font-bold uppercase">Postuler à cette offre</a>
      </div>

      <div class="p-6 bg-white border border-slate-200 rounded-lg flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <span class="text-xs font-mono text-[#08bcd8] font-bold">RÉF : REC-ING-02 // CDI // EXP 3-5 ANS</span>
          <h3 class="text-lg font-bold text-slate-900">Ingénieur Bureau d'Études &amp; Méthodes (EXE / Chiffrage)</h3>
          <p class="text-xs text-slate-500">Siège Casablanca</p>
        </div>
        <a href="#candidature" class="px-4 py-2 bg-slate-900 text-white rounded text-xs font-bold uppercase">Postuler à cette offre</a>
      </div>
    </div>

    <!-- Formulaire de candidature -->
    <div id="candidature" class="bg-white p-8 border rounded-lg max-w-2xl mx-auto space-y-4">
      <h3 class="font-bold text-lg text-slate-900">Déposer une candidature spontanée</h3>
      <p class="text-xs text-slate-500">Envoyez votre CV et vos références de chantiers à notre pôle RH : <strong class="text-slate-800">recrutement@tg2d.ma</strong></p>
      <form class="space-y-4 text-xs">
        <input type="text" placeholder="Nom et prénom" class="w-full p-2 border rounded">
        <input type="email" placeholder="Adresse e-mail" class="w-full p-2 border rounded">
        <input type="tel" placeholder="Numéro de téléphone" class="w-full p-2 border rounded">
        <select class="w-full p-2 border rounded">
          <option>Conduite de travaux</option>
          <option>Bureau d'études / Chiffrage</option>
          <option>Chef de chantier / Terrain</option>
        </select>
        <button type="submit" class="w-full py-3 bg-[#08bcd8] hover:bg-[#06a3bb] text-slate-950 font-bold uppercase rounded">
          Transmettre ma candidature &rarr;
        </button>
      </form>
    </div>
  </main>
</body>
</html>
```

---

## 5. INSTRUCTIONS D'INCORPORATION AUTOMATIQUE DANS ANTIGRAVITY

1. **Création du projet** : Créez un dossier racine `tg2d-website/` avec les répertoires `assets/`, `styles/` et `pages/`.
2. **Placement des fichiers** : Copiez le code HTML ci-dessus dans les fichiers respectifs :
   - `index.html`
   - `a-propos.html`
   - `expertises.html`
   - `realisations.html`
   - `carrieres.html`
   - `contact.html`
3. **Images & Logos** : Placez `tg2d-logo.png` et `golden-hour-construction-crew.png` dans le sous-dossier `assets/`.
4. **Feuille de style / Tailwind** : Les scripts CDN Tailwind avec la configuration `theme.extend` intégrée fonctionnent immédiatement sans configuration de build complexe, ou peuvent être importés dans un bundler Vite/Next/Astro selon vos pipelines Antigravity.
