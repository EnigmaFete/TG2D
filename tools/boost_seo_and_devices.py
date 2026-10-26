#!/usr/bin/env python3
"""
TG2D BTP Maroc — Ultimate SEO & Cross-Device Optimization Engine
Optimizes metadata, OpenGraph, Twitter Cards, Schema.org JSON-LD,
resource hints, and image performance (loading=lazy, decoding=async)
WITHOUT altering any UI layouts, CSS, or visual styles.
"""

import os
import re
import json

PAGE_CONFIGS = {
    "index.html": {
        "title": "TG2D — Construire aujourd’hui. Bâtir durablement. | Entreprise Générale BTP Maroc",
        "desc": "TG2D est une entreprise générale marocaine spécialisée dans la construction tous corps d'état (TCE) : projets résidentiels, commerciaux, industriels et logistiques à Casablanca et sur l'ensemble du Maroc.",
        "keywords": "entreprise BTP Maroc, entreprise générale de construction Casablanca, BTP Casablanca, travaux tous corps d'état, gros œuvre Maroc, génie civil Maroc, TG2D, conducteur de travaux Maroc, bâtiment industriel Maroc",
        "og_image": "https://tg2d.ma/assets/images/hero-construction-workers.jpg",
        "og_image_alt": "Équipe d'ingénieurs et techniciens sur chantier BTP TG2D au Maroc",
        "og_type": "website",
        "tw_title": "TG2D — Construire aujourd’hui. Bâtir durablement. | Entreprise BTP Maroc",
        "tw_desc": "Construction Tous Corps d’État | Résidentiel | Commercial | Industriel | Logistique au Maroc.",
        "schema": None # already set in previous step
    },
    "a-propos.html": {
        "title": "À propos — TG2D BTP & Travaux Généraux Maroc",
        "desc": "Découvrez TG2D : entreprise générale de construction tous corps d'état au Maroc. Organisation en 3 divisions, conduite de travaux rigoureuse et engagements durables.",
        "keywords": "à propos TG2D, entreprise générale BTP Maroc, histoire TG2D, direction de chantier Maroc, constructeur Casablanca, gouvernance BTP",
        "og_image": "https://tg2d.ma/assets/images/tg2d-team-safety-vests.jpg",
        "og_image_alt": "Direction technique et équipe d'ingénieurs TG2D Maroc",
        "og_type": "website",
        "tw_title": "À propos de TG2D — Entreprise Générale Tous Corps d'État",
        "tw_desc": "Organisation en 3 divisions, ingénierie de pointe et maîtrise d'exécution au Maroc.",
        "schema": {
            "@context": "https://schema.org",
            "@graph": [
                {
                    "@type": "AboutPage",
                    "@id": "https://tg2d.ma/a-propos.html#webpage",
                    "url": "https://tg2d.ma/a-propos.html",
                    "name": "À propos de TG2D — Entreprise Générale BTP Maroc",
                    "description": "Découvrez TG2D : entreprise générale de construction tous corps d'état au Maroc, organisation en 3 divisions spécialisées, conduite de travaux rigoureuse et engagements durables.",
                    "breadcrumb": {
                        "@id": "https://tg2d.ma/a-propos.html#breadcrumb"
                    },
                    "inLanguage": "fr-MA"
                },
                {
                    "@type": "BreadcrumbList",
                    "@id": "https://tg2d.ma/a-propos.html#breadcrumb",
                    "itemListElement": [
                        {
                            "@type": "ListItem",
                            "position": 1,
                            "name": "Accueil",
                            "item": "https://tg2d.ma/index.html"
                        },
                        {
                            "@type": "ListItem",
                            "position": 2,
                            "name": "À propos",
                            "item": "https://tg2d.ma/a-propos.html"
                        }
                    ]
                }
            ]
        }
    },
    "expertises.html": {
        "title": "Nos Expertises — TG2D BTP & Travaux Généraux Maroc",
        "desc": "Découvrez les 6 pôles d'ingénierie et corps d'état de TG2D : Gros Œuvre, Génie Civil, VRD, Étanchéité, Second Œuvre et Coordination Tous Corps d'État au Maroc.",
        "keywords": "expertises BTP Maroc, gros œuvre Casablanca, génie civil Maroc, VRD Maroc, étanchéité bâtiment, second œuvre, coordination TCE",
        "og_image": "https://tg2d.ma/assets/images/chantier-structure-elevations.jpg",
        "og_image_alt": "Chantier d'élévation et gros œuvre en béton armé TG2D",
        "og_type": "website",
        "tw_title": "Nos Expertises BTP & Génie Civil — TG2D Maroc",
        "tw_desc": "6 pôles d'ingénierie et corps d'état fondamentaux de la construction et des infrastructures au Maroc.",
        "schema": {
            "@context": "https://schema.org",
            "@graph": [
                {
                    "@type": "CollectionPage",
                    "@id": "https://tg2d.ma/expertises.html#webpage",
                    "url": "https://tg2d.ma/expertises.html",
                    "name": "Nos Expertises BTP & Génie Civil — TG2D Maroc",
                    "description": "6 pôles d'ingénierie et corps d'état de TG2D : Gros Œuvre, Génie Civil, VRD, Étanchéité, Second Œuvre et Conduite de Travaux TCE au Maroc.",
                    "breadcrumb": {
                        "@id": "https://tg2d.ma/expertises.html#breadcrumb"
                    },
                    "inLanguage": "fr-MA"
                },
                {
                    "@type": "BreadcrumbList",
                    "@id": "https://tg2d.ma/expertises.html#breadcrumb",
                    "itemListElement": [
                        {
                            "@type": "ListItem",
                            "position": 1,
                            "name": "Accueil",
                            "item": "https://tg2d.ma/index.html"
                        },
                        {
                            "@type": "ListItem",
                            "position": 2,
                            "name": "Expertises",
                            "item": "https://tg2d.ma/expertises.html"
                        }
                    ]
                }
            ]
        }
    },
    "projets.html": {
        "title": "Nos Réalisations d'Ouvrages & Infrastructures — TG2D Maroc",
        "desc": "Découvrez le portfolio des réalisations de TG2D au Maroc : Showroom BRICOMA Dar Bouazza, Plateforme Logistique Bouskoura, Villas de standing et complexes tertiaires.",
        "keywords": "projets BTP Maroc, réalisations TG2D, références chantiers Maroc, showroom Bricoma Dar Bouazza, plateforme logistique Bouskoura, construction villas Casablanca",
        "og_image": "https://tg2d.ma/assets/images/centre-logistique-bouskoura.jpg",
        "og_image_alt": "Centre Logistique Bouskoura réalisé par TG2D",
        "og_type": "website",
        "tw_title": "Nos Réalisations d'Ouvrages & Infrastructures — TG2D Maroc",
        "tw_desc": "Portfolio d'ouvrages commerciaux, industriels, logistiques et résidentiels livrés avec rigueur au Maroc.",
        "schema": {
            "@context": "https://schema.org",
            "@graph": [
                {
                    "@type": "CollectionPage",
                    "@id": "https://tg2d.ma/projets.html#webpage",
                    "url": "https://tg2d.ma/projets.html",
                    "name": "Nos Réalisations d'Ouvrages & Infrastructures — TG2D Maroc",
                    "description": "Portfolio des réalisations de TG2D au Maroc : Showrooms commerciaux, plateformes logistiques, résidences de standing et réhabilitations TCE.",
                    "breadcrumb": {
                        "@id": "https://tg2d.ma/projets.html#breadcrumb"
                    },
                    "inLanguage": "fr-MA"
                },
                {
                    "@type": "BreadcrumbList",
                    "@id": "https://tg2d.ma/projets.html#breadcrumb",
                    "itemListElement": [
                        {
                            "@type": "ListItem",
                            "position": 1,
                            "name": "Accueil",
                            "item": "https://tg2d.ma/index.html"
                        },
                        {
                            "@type": "ListItem",
                            "position": 2,
                            "name": "Réalisations",
                            "item": "https://tg2d.ma/projets.html"
                        }
                    ]
                }
            ]
        }
    },
    "realisations.html": {
        "title": "Nos Réalisations & Chantiers Livrés — TG2D Maroc",
        "desc": "Consultez nos projets livrés et études de cas BTP au Maroc : bâtiments commerciaux, entrepôts industriels et résidences d'exception.",
        "keywords": "réalisations BTP Maroc, chantiers livrés Casablanca, références constructeur Maroc, projets génie civil, portfolio TG2D",
        "og_image": "https://tg2d.ma/assets/images/showroom-bricoma-darbouazza.jpg",
        "og_image_alt": "Showroom commercial BRICOMA Dar Bouazza réalisé par TG2D",
        "og_type": "website",
        "tw_title": "Nos Réalisations & Chantiers Livrés — TG2D Maroc",
        "tw_desc": "Consultez nos projets livrés et études de cas BTP au Maroc : bâtiments commerciaux, entrepôts industriels et résidences d'exception.",
        "schema": {
            "@context": "https://schema.org",
            "@graph": [
                {
                    "@type": "CollectionPage",
                    "@id": "https://tg2d.ma/realisations.html#webpage",
                    "url": "https://tg2d.ma/realisations.html",
                    "name": "Nos Réalisations & Chantiers Livrés — TG2D Maroc",
                    "description": "Consultez nos projets livrés et études de cas BTP au Maroc : bâtiments commerciaux, entrepôts industriels et résidences d'exception.",
                    "breadcrumb": {
                        "@id": "https://tg2d.ma/realisations.html#breadcrumb"
                    },
                    "inLanguage": "fr-MA"
                },
                {
                    "@type": "BreadcrumbList",
                    "@id": "https://tg2d.ma/realisations.html#breadcrumb",
                    "itemListElement": [
                        {
                            "@type": "ListItem",
                            "position": 1,
                            "name": "Accueil",
                            "item": "https://tg2d.ma/index.html"
                        },
                        {
                            "@type": "ListItem",
                            "position": 2,
                            "name": "Réalisations",
                            "item": "https://tg2d.ma/realisations.html"
                        }
                    ]
                }
            ]
        }
    },
    "carrieres.html": {
        "title": "Carrières & Recrutement — TG2D BTP & Travaux Généraux Maroc",
        "desc": "Rejoignez TG2D au Maroc : offres d'emploi pour ingénieurs travaux, conducteurs de travaux, chefs de chantier, métreurs et ingénieurs méthodes.",
        "keywords": "recrutement BTP Maroc, emploi ingénieur travaux Casablanca, conducteur de travaux recrutement Maroc, chef de chantier emploi, carrières TG2D",
        "og_image": "https://tg2d.ma/assets/images/tg2d-team-safety-vests.jpg",
        "og_image_alt": "Équipe technique et ingénieurs de chantier TG2D Maroc",
        "og_type": "website",
        "tw_title": "Carrières & Recrutement BTP Maroc — TG2D",
        "tw_desc": "Rejoignez l'exigence technique sur le terrain et en bureau d'études chez TG2D.",
        "schema": {
            "@context": "https://schema.org",
            "@graph": [
                {
                    "@type": "WebPage",
                    "@id": "https://tg2d.ma/carrieres.html#webpage",
                    "url": "https://tg2d.ma/carrieres.html",
                    "name": "Carrières & Recrutement BTP Maroc — TG2D",
                    "description": "Rejoignez TG2D : opportunités de carrières pour conducteurs de travaux, ingénieurs d'études, chefs de chantier et métreurs au Maroc.",
                    "breadcrumb": {
                        "@id": "https://tg2d.ma/carrieres.html#breadcrumb"
                    },
                    "inLanguage": "fr-MA"
                },
                {
                    "@type": "BreadcrumbList",
                    "@id": "https://tg2d.ma/carrieres.html#breadcrumb",
                    "itemListElement": [
                        {
                            "@type": "ListItem",
                            "position": 1,
                            "name": "Accueil",
                            "item": "https://tg2d.ma/index.html"
                        },
                        {
                            "@type": "ListItem",
                            "position": 2,
                            "name": "Carrières",
                            "item": "https://tg2d.ma/carrieres.html"
                        }
                    ]
                }
            ]
        }
    },
    "contact.html": {
        "title": "Contact & Devis — TG2D BTP & Travaux Généraux Maroc",
        "desc": "Contactez le bureau technique de TG2D pour l'étude de votre projet de construction BTP au Maroc. Réponse sous 48h ouvrées, chiffrage d'appels d'offres et analyse de DCE.",
        "keywords": "contact entreprise BTP Maroc, devis construction Casablanca, chiffrage appel d'offres BTP, bureau d'études TG2D téléphone adresse",
        "og_image": "https://tg2d.ma/assets/images/hero-construction-workers.jpg",
        "og_image_alt": "Contact et direction technique TG2D Casablanca",
        "og_type": "website",
        "tw_title": "Contact & Devis Technique — TG2D BTP Maroc",
        "tw_desc": "Étude de faisabilité, chiffrage d'appels d'offres et consultation DCE sous 48h ouvrées.",
        "schema": {
            "@context": "https://schema.org",
            "@graph": [
                {
                    "@type": "ContactPage",
                    "@id": "https://tg2d.ma/contact.html#webpage",
                    "url": "https://tg2d.ma/contact.html",
                    "name": "Contact & Devis BTP Maroc — TG2D",
                    "description": "Contactez la direction technique de TG2D pour chiffrage d'appels d'offres et analyse de DCE de construction sous 48h au Maroc.",
                    "breadcrumb": {
                        "@id": "https://tg2d.ma/contact.html#breadcrumb"
                    },
                    "inLanguage": "fr-MA"
                },
                {
                    "@type": "BreadcrumbList",
                    "@id": "https://tg2d.ma/contact.html#breadcrumb",
                    "itemListElement": [
                        {
                            "@type": "ListItem",
                            "position": 1,
                            "name": "Accueil",
                            "item": "https://tg2d.ma/index.html"
                        },
                        {
                            "@type": "ListItem",
                            "position": 2,
                            "name": "Contact",
                            "item": "https://tg2d.ma/contact.html"
                        }
                    ]
                }
            ]
        }
    },
    "journal.html": {
        "title": "Journal des Chantiers & Études de Cas | TG2D Maroc",
        "desc": "Analyses techniques, retours d'expérience chantiers, guides de conformité parasismique et innovations génie civil au Maroc par TG2D.",
        "keywords": "journal BTP Maroc, actualités chantiers, retour expérience BTP, ingénierie structure Maroc, guide RPS 2011, études de cas construction",
        "og_image": "https://tg2d.ma/assets/images/bureau-etudes-plans-techniques.jpg",
        "og_image_alt": "Journal technique et ingénierie BTP TG2D Maroc",
        "og_type": "website",
        "tw_title": "Journal des Chantiers & Études de Cas | TG2D Maroc",
        "tw_desc": "Analyses techniques, retours d'expérience chantiers et guides de conformité parasismique au Maroc.",
        "schema": None
    },
    "service-construction-tous-corps-detat.html": {
        "title": "Construction Tous Corps d'État (TCE) | Entreprise Générale TG2D Maroc",
        "desc": "Pilotage intégral clé en main de projets BTP au Maroc : études techniques, gros œuvre, second œuvre et lots techniques avec engagement contractuel strict.",
        "keywords": "construction tous corps d'état Maroc, entreprise générale TCE Casablanca, contractant général Maroc, clé en main BTP",
        "og_image": "https://tg2d.ma/assets/images/chantier-structure-elevations.jpg",
        "og_image_alt": "Construction Tous Corps d'État BTP par TG2D Maroc",
        "og_type": "article",
        "tw_title": "Construction TCE Maroc | TG2D",
        "tw_desc": "Pilotage global et réalisation clé en main de projets de construction tous corps d'état.",
        "schema": None
    },
    "service-gros-oeuvre-structure.html": {
        "title": "Gros Œuvre & Structure | Génie Civil TG2D Maroc",
        "desc": "Fondations profondes, radiers, structures béton armé et charpentes métalliques pour bâtiments industriels, commerciaux et résidentiels au Maroc.",
        "keywords": "gros œuvre Maroc, structure béton armé Casablanca, fondations spéciales, génie civil bâtiment Maroc, coulage béton",
        "og_image": "https://tg2d.ma/assets/images/chantier-ferraillage-dalle.jpg",
        "og_image_alt": "Ferraillage de dalle et gros œuvre structure béton armé TG2D",
        "og_type": "article",
        "tw_title": "Gros Œuvre & Structure Béton Armé | TG2D Maroc",
        "tw_desc": "Maîtrise complète de la structure porteuse : fondations, radiers, voiles et planchers béton armé.",
        "schema": None
    },
    "service-second-oeuvre-finitions.html": {
        "title": "Second Œuvre & Finitions Architecturales | TG2D Maroc",
        "desc": "Aménagement intérieur, cloisons sèches, faux plafonds, revêtements de sols, menuiserie, électricité et plomberie pour projets tertiaires et résidentiels.",
        "keywords": "second œuvre Maroc, finitions bâtiment Casablanca, cloisons faux plafonds, aménagement bureaux Maroc, revêtements standing",
        "og_image": "https://tg2d.ma/assets/images/facade-detail.jpg",
        "og_image_alt": "Finitions architecturales et aménagement second œuvre TG2D",
        "og_type": "article",
        "tw_title": "Second Œuvre & Finitions Architecturales | TG2D Maroc",
        "tw_desc": "Cloisons, faux plafonds, revêtements nobles et réseaux techniques exécutés avec soin.",
        "schema": None
    },
    "service-projets-commerciaux-retail.html": {
        "title": "Projets Commerciaux & Retail | Construction Showrooms Maroc | TG2D",
        "desc": "Conception-construction d'espaces commerciaux, galeries marchandes et showrooms de vente à fort trafic au Maroc selon les standards retail internationaux.",
        "keywords": "construction commerciale Maroc, construction showroom Casablanca, aménagement retail, bâtiment commercial BTP",
        "og_image": "https://tg2d.ma/assets/images/showroom-bricoma-darbouazza.jpg",
        "og_image_alt": "Construction et aménagement showroom commercial BRICOMA Dar Bouazza",
        "og_type": "article",
        "tw_title": "Construction Commerciale & Retail Maroc | TG2D",
        "tw_desc": "Showrooms commerciaux, galeries marchandes et magasins livrés dans le respect des délais.",
        "schema": None
    },
    "service-projets-industriels-logistiques.html": {
        "title": "Projets Industriels & Logistiques | Entrepôts & Hubs Maroc | TG2D",
        "desc": "Construction d'usines, entrepôts logistiques, plateformes de distribution sous température dirigée et parcs d'activités aux normes FM Global.",
        "keywords": "construction entrepôt logistique Maroc, usine industrielle Casablanca, plateforme logistique Bouskoura, dalle industrielle",
        "og_image": "https://tg2d.ma/assets/images/centre-logistique-bouskoura.jpg",
        "og_image_alt": "Plateforme logistique et entrepôt industriel moderne TG2D Bouskoura",
        "og_type": "article",
        "tw_title": "Construction Industrielle & Logistique Maroc | TG2D",
        "tw_desc": "Entrepôts grande hauteur, dalles industrielles haute résistance et usines clés en main.",
        "schema": None
    },
    "service-renovation-rehabilitation.html": {
        "title": "Rénovation & Réhabilitation Tous Corps d'État | TG2D Maroc",
        "desc": "Restructuration lourde, renforcement structurel, rénovation thermique et remise aux normes d'immeubles de bureaux, hôtels et bâtiments patrimoniaux.",
        "keywords": "rénovation lourde Maroc, réhabilitation bâtiment Casablanca, renforcement structurel carbone, mise aux normes immeuble",
        "og_image": "https://tg2d.ma/assets/images/facade-detail.jpg",
        "og_image_alt": "Rénovation et réhabilitation de façade bâtiment tertiaire TG2D",
        "og_type": "article",
        "tw_title": "Rénovation & Réhabilitation TCE | TG2D Maroc",
        "tw_desc": "Restructuration lourde et modernisation de bâtiments tertiaires et résidentiels au Maroc.",
        "schema": None
    },
    "bureau-etudes-ingenierie.html": {
        "title": "Bureau d'Études & Ingénierie Structure | TG2D Maroc",
        "desc": "Cellule d'ingénierie intégrée TG2D Maroc : plans d'exécution béton armé, notes de calcul parasismique RPS 2011, modélisation BIM 3D et optimisation des ratios techniques.",
        "keywords": "bureau d'études structure Maroc, calcul béton armé, note de calcul RPS 2011, modélisation BIM Casablanca, plans d'exécution génie civil",
        "og_image": "https://tg2d.ma/assets/images/bureau-etudes-plans-techniques.jpg",
        "og_image_alt": "Plans d'ingénierie et bureau d'études structure TG2D Maroc",
        "og_type": "article",
        "tw_title": "Ingénierie & Méthodes | TG2D Maroc",
        "tw_desc": "Optimisation des structures et modélisation BIM pour maîtres d'ouvrage exigeants.",
        "schema": None
    },
    "qualite-normes-lpee.html": {
        "title": "Contrôle Qualité & Normes LPEE / NM | TG2D BTP Maroc",
        "desc": "Protocoles rigoureux de contrôle qualité sur chantiers : essais d'écrasement béton, certification d'aciers, agréments LPEE et respect strict des normes marocaines.",
        "keywords": "contrôle qualité BTP Maroc, essais béton LPEE, normes marocaines NM, agrément laboratoire BTP, conformité ferraillage",
        "og_image": "https://tg2d.ma/assets/images/eng-qualite.jpg",
        "og_image_alt": "Contrôle qualité et essais laboratoire LPEE chantier TG2D",
        "og_type": "article",
        "tw_title": "Qualité & Normes LPEE | TG2D Maroc",
        "tw_desc": "Protocoles d'essais, traçabilité des matériaux et conformité aux normes marocaines NM.",
        "schema": None
    },
    "securite-hse-chantiers.html": {
        "title": "Sécurité des Chantiers & Charte HSE | TG2D Maroc",
        "desc": "Politique zéro accident TG2D : plans de sécurité et protection de la santé (SPS), port des EPI obligatoires, audits de sécurité et formation continue des compagnons.",
        "keywords": "sécurité chantier BTP Maroc, charte HSE construction, prévention accidents chantier, plan SPS Maroc, EPI sécurité bâtiment",
        "og_image": "https://tg2d.ma/assets/images/tg2d-team-safety-vests.jpg",
        "og_image_alt": "Équipe TG2D équipée des EPI réglementaires sur chantier",
        "og_type": "article",
        "tw_title": "Sécurité HSE & Chantiers | TG2D Maroc",
        "tw_desc": "Exigence stricte de sécurité, prévention des risques et culture zéro accident.",
        "schema": None
    },
    "developpement-durable-rse.html": {
        "title": "Développement Durable & Démarche RSE BTP | TG2D Maroc",
        "desc": "Engagements éco-responsables de TG2D : gestion et valorisation des déchets de chantier, bétons bas carbone, certification HQE / LEED et ancrage social local.",
        "keywords": "développement durable BTP Maroc, éco-construction Casablanca, RSE entreprise BTP, valorisation déchets chantier, HQE Maroc",
        "og_image": "https://tg2d.ma/assets/images/pourquoi-tg2d-banner.png",
        "og_image_alt": "Engagements durables et démarche RSE TG2D Maroc",
        "og_type": "article",
        "tw_title": "Développement Durable & RSE | TG2D Maroc",
        "tw_desc": "Pratiques durables, gestion des déchets et éco-construction au Maroc.",
        "schema": None
    },
    "btp-casablanca-grand-casablanca.html": {
        "title": "Entreprise BTP Casablanca & Grand Casablanca | TG2D Construction",
        "desc": "TG2D réalise vos chantiers tous corps d'état à Casablanca, Bouskoura, Nouaceur, Dar Bouazza, Mohammedia et Zenata : bureaux, usines, commerces et villas.",
        "keywords": "entreprise BTP Casablanca, constructeur Grand Casablanca, chantier Bouskoura Nouaceur, BTP Dar Bouazza, travaux Mohammedia",
        "og_image": "https://tg2d.ma/assets/images/hero-construction-workers.jpg",
        "og_image_alt": "Chantiers BTP Casablanca et Grand Casablanca TG2D",
        "og_type": "article",
        "tw_title": "Entreprise BTP Casablanca | TG2D",
        "tw_desc": "Travaux tous corps d'état et génie civil à Casablanca et sa périphérie industrielle.",
        "schema": None
    },
    "btp-rabat-sale-kenitra.html": {
        "title": "Entreprise BTP Rabat, Salé & Kénitra | TG2D Construction",
        "desc": "Entreprise générale de construction opérant sur l'axe Rabat-Salé-Témara et la zone industrielle de Kénitra (Atlantic Free Zone) pour projets industriels et tertiaires.",
        "keywords": "entreprise BTP Rabat, constructeur Salé Kénitra, travaux Atlantic Free Zone, bâtiment tertiaire Rabat, génie civil Témara",
        "og_image": "https://tg2d.ma/assets/images/chantier-structure-elevations.jpg",
        "og_image_alt": "Chantiers BTP Rabat Salé Kénitra TG2D",
        "og_type": "article",
        "tw_title": "Entreprise BTP Rabat-Salé-Kénitra | TG2D",
        "tw_desc": "Construction TCE sur l'axe institutionnel et industriel Rabat Salé Kénitra.",
        "schema": None
    },
    "btp-tanger-tanger-med.html": {
        "title": "Entreprise BTP Tanger & Tanger Med | TG2D Construction",
        "desc": "Construction d'usines aéronautiques et automobiles, plateformes logistiques et entrepôts dans les zones franches Tanger Automotive City et Tanger Free Zone.",
        "keywords": "entreprise BTP Tanger, constructeur Tanger Med, zone franche TAC TFZ, usine automobile Tanger, entrepôt logistique Tanger",
        "og_image": "https://tg2d.ma/assets/images/centre-logistique-bouskoura.jpg",
        "og_image_alt": "Chantiers BTP Tanger Med et Tanger Automotive City TG2D",
        "og_type": "article",
        "tw_title": "Entreprise BTP Tanger & Tanger Med | TG2D",
        "tw_desc": "Ouvrages industriels et logistiques dans le hub économique du Nord marocain.",
        "schema": None
    },
    "btp-marrakech-sud.html": {
        "title": "Entreprise BTP Marrakech & Régions Sud | TG2D Construction",
        "desc": "Réalisation de projets hôteliers, résidences d'exception, parcs tertiaires et bâtiments industriels à Marrakech, Agadir et les régions Sud du Maroc.",
        "keywords": "entreprise BTP Marrakech, constructeur Agadir, hôtel resort chantier Marrakech, villa de luxe Marrakech BTP, génie civil Sud Maroc",
        "og_image": "https://tg2d.ma/assets/images/chantier-ferraillage-dalle.jpg",
        "og_image_alt": "Chantiers BTP Marrakech et Régions Sud TG2D",
        "og_type": "article",
        "tw_title": "Entreprise BTP Marrakech & Sud | TG2D",
        "tw_desc": "Construction hôtelière, résidentielle et commerciale à Marrakech et dans le Sud.",
        "schema": None
    },
    "projet-showroom-bricoma-dar-bouazza.html": {
        "title": "Showroom BRICOMA Dar Bouazza | Étude de Cas & Projet | TG2D Maroc",
        "desc": "Découvrez la réalisation du showroom commercial BRICOMA à Dar Bouazza par TG2D : gros œuvre, charpente, façades et second œuvre en tous corps d'état.",
        "keywords": "projet Bricoma Dar Bouazza, showroom Bricoma travaux, construction commerciale Dar Bouazza, référence chantier TG2D",
        "og_image": "https://tg2d.ma/assets/images/showroom-bricoma-darbouazza.jpg",
        "og_image_alt": "Façade et aménagements Showroom Bricoma Dar Bouazza",
        "og_type": "article",
        "tw_title": "Projet Showroom Bricoma Dar Bouazza | TG2D",
        "tw_desc": "Construction tous corps d'état d'un showroom retail de référence à Dar Bouazza.",
        "schema": None
    },
    "projet-centre-logistique-bouskoura.html": {
        "title": "Centre Logistique Bouskoura | Étude de Cas & Projet | TG2D Maroc",
        "desc": "Étude de cas détaillée sur la construction de la plateforme logistique de Bouskoura : dallage haute résistance, quais niveleurs et structure béton armé.",
        "keywords": "projet plateforme logistique Bouskoura, entrepôt logistique travaux, dalle industrielle Bouskoura, référence logistique TG2D",
        "og_image": "https://tg2d.ma/assets/images/centre-logistique-bouskoura.jpg",
        "og_image_alt": "Plateforme logistique Bouskoura quais niveleurs et structure",
        "og_type": "article",
        "tw_title": "Centre Logistique Bouskoura | TG2D",
        "tw_desc": "Étude de cas : réalisation d'un hub logistique haute performance à Bouskoura.",
        "schema": None
    },
    "projet-residence-villas-standing-casablanca.html": {
        "title": "Villas & Résidences de Standing | Réalisations TG2D Casablanca",
        "desc": "Construction haut de gamme de villas contemporaines et résidences fermées à Casablanca : béton architectonique, domotique et finitions de prestige.",
        "keywords": "villas haut standing Casablanca, résidence de luxe Anfa Bouskoura, construction villa architecte, finitions de prestige Maroc",
        "og_image": "https://tg2d.ma/assets/images/project-cover-1.png",
        "og_image_alt": "Villa d'architecte contemporaine haut standing Casablanca",
        "og_type": "article",
        "tw_title": "Résidences & Villas Standing Casablanca | TG2D",
        "tw_desc": "Construction et finitions de prestige pour projets résidentiels d'exception.",
        "schema": None
    },
    "projet-renovation-rehabilitation-tce.html": {
        "title": "Rénovation & Réhabilitation TCE | Projets & Savoir-Faire | TG2D Maroc",
        "desc": "Rénovation complète d'immeubles tertiaires et restructuration de plateaux de bureaux au Maroc en site occupé avec maintien de l'exploitation.",
        "keywords": "rénovation bureau Casablanca, réhabilitation immeuble tertiaire, rénovation TCE site occupé, restructuration bâtiment Maroc",
        "og_image": "https://tg2d.ma/assets/images/facade-detail.jpg",
        "og_image_alt": "Rénovation lourde immeuble de bureaux Casablanca",
        "og_type": "article",
        "tw_title": "Rénovation & Réhabilitation TCE | TG2D",
        "tw_desc": "Restructuration de plateaux tertiaires et réhabilitation tous corps d'état.",
        "schema": None
    },
    "guide-fondations-sols-maroc.html": {
        "title": "Guide Fondations & Sols au Maroc | TG2D Ingénierie",
        "desc": "Guide technique sur le choix des fondations au Maroc selon la géotechnique locale : semelles superficielles, radiers généraux, pieux forés et argiles gonflantes.",
        "keywords": "guide fondations Maroc, argiles gonflantes Casablanca, radier général calcul, pieux forés génie civil, rapport géotechnique sol Maroc",
        "og_image": "https://tg2d.ma/assets/images/chantier-fondations-longrines.jpg",
        "og_image_alt": "Chantier de fondations profondes et longrines béton armé TG2D",
        "og_type": "article",
        "tw_title": "Guide Fondations & Sols au Maroc | TG2D",
        "tw_desc": "Comprendre les types de fondations selon les contraintes géotechniques au Maroc.",
        "schema": None
    },
    "guide-normes-parasismiques-rps2011.html": {
        "title": "Guide Normes Parasismiques RPS 2011 Maroc | TG2D BTP",
        "desc": "Comprendre et appliquer le Règlement de Construction Parasismique (RPS 2011 version 2014/2026) au Maroc : zonage sismique, ductilité et contreventement.",
        "keywords": "norme parasismique Maroc, RPS 2011, calcul parasismique bâtiment, zone sismique Maroc, contreventement voile béton armé",
        "og_image": "https://tg2d.ma/assets/images/bureau-etudes-plans-techniques.jpg",
        "og_image_alt": "Plans techniques de calcul parasismique RPS 2011 TG2D",
        "og_type": "article",
        "tw_title": "Normes Parasismiques RPS 2011 Maroc | TG2D",
        "tw_desc": "Guide de conformité parasismique et calcul des structures en zone sismique.",
        "schema": None
    }
}

DEVICE_META_BLOCK = """  <!-- Device & Cross-Platform Optimization -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover" />
  <meta name="theme-color" content="#006878" />
  <meta name="theme-color" media="(prefers-color-scheme: dark)" content="#060A14" />
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
  <meta name="apple-mobile-web-app-title" content="TG2D BTP" />
  <meta name="application-name" content="TG2D BTP" />
  <meta name="format-detection" content="telephone=no" />
  <meta name="color-scheme" content="light dark" />
  <link rel="manifest" href="/site.webmanifest" />"""

RESOURCE_HINTS_BLOCK = """  <!-- Resource Hints & DNS Prefetch -->
  <link rel="dns-prefetch" href="https://fonts.googleapis.com" />
  <link rel="dns-prefetch" href="https://fonts.gstatic.com" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />"""

LOCAL_SEO_META_BLOCK = """  <!-- Local SEO & Search Engine Crawlers -->
  <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1" />
  <meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
  <meta name="bingbot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
  <meta name="author" content="TG2D Entreprise Générale BTP Maroc" />
  <meta name="geo.region" content="MA" />
  <meta name="geo.placename" content="Casablanca, Maroc" />
  <meta name="geo.position" content="33.5731;-7.6298" />
  <meta name="ICBM" content="33.5731, -7.6298" />"""


def process_html_file(filename, config):
    if not os.path.exists(filename):
        print(f"Skipping {filename}: not found")
        return

    with open(filename, 'r', encoding='utf-8') as f:
        html = f.read()

    # Skip index.html head replacement if already done, but still optimize its images
    if filename != "index.html":
        # 1. Update viewport
        html = re.sub(
            r'<meta\s+name=["\']viewport["\'][^>]*>',
            DEVICE_META_BLOCK,
            html,
            count=1,
            flags=re.IGNORECASE
        )

        # 2. Add or update keywords
        if 'name="keywords"' not in html and "name='keywords'" not in html:
            kw_tag = f'  <meta name="keywords" content="{config["keywords"]}" />\n'
            # Insert after meta description
            if '<meta name="description"' in html:
                html = re.sub(r'(<meta\s+name=["\']description["\'][^>]*>)', r'\1\n' + kw_tag, html, count=1, flags=re.IGNORECASE)
            elif "<title>" in html:
                html = re.sub(r'(<title>.*?</title>)', r'\1\n' + kw_tag, html, count=1, flags=re.DOTALL | re.IGNORECASE)

        # 3. Add Local SEO & crawlers block if not present
        if 'name="geo.region"' not in html:
            if '<meta name="robots"' in html:
                html = re.sub(r'<meta\s+name=["\']robots["\'][^>]*>', LOCAL_SEO_META_BLOCK, html, count=1, flags=re.IGNORECASE)
            elif '<link rel="canonical"' in html:
                html = re.sub(r'(<link\s+rel=["\']canonical["\'][^>]*>)', r'\1\n' + LOCAL_SEO_META_BLOCK, html, count=1, flags=re.IGNORECASE)

        # 4. Canonical check & harmonisation
        expected_canonical = f'<link rel="canonical" href="https://tg2d.ma/{filename}" />'
        if '<link rel="canonical"' in html:
            html = re.sub(r'<link\s+rel=["\']canonical["\'][^>]*>', expected_canonical, html, count=1, flags=re.IGNORECASE)
        else:
            html = re.sub(r'(</title>)', r'\1\n  ' + expected_canonical, html, count=1, flags=re.IGNORECASE)

        # 5. Open Graph updates
        og_block = f"""  <!-- Open Graph -->
  <meta property="og:locale" content="fr_MA" />
  <meta property="og:type" content="{config['og_type']}" />
  <meta property="og:site_name" content="TG2D Entreprise Générale BTP" />
  <meta property="og:title" content="{config['title']}" />
  <meta property="og:description" content="{config['desc']}" />
  <meta property="og:url" content="https://tg2d.ma/{filename}" />
  <meta property="og:image" content="{config['og_image']}" />
  <meta property="og:image:width" content="1200" />
  <meta property="og:image:height" content="630" />
  <meta property="og:image:alt" content="{config['og_image_alt']}" />"""

        if '<meta property="og:locale"' in html:
            # Replace existing OG block
            html = re.sub(
                r'<!--\s*Open Graph.*?-->\s*<meta\s+property=["\']og:locale["\'].*?(?=(<!--\s*Twitter|<script|<link\s+rel=["\']preconnect|<style))',
                og_block + '\n\n',
                html,
                count=1,
                flags=re.DOTALL | re.IGNORECASE
            )
        elif '<meta property="og:type"' in html:
            html = re.sub(
                r'<meta\s+property=["\']og:type["\'].*?(?=(<!--\s*Twitter|<script|<link\s+rel=["\']preconnect|<style))',
                og_block + '\n\n',
                html,
                count=1,
                flags=re.DOTALL | re.IGNORECASE
            )

        # 6. Twitter Card updates
        tw_block = f"""  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="{config['tw_title']}" />
  <meta name="twitter:description" content="{config['tw_desc']}" />
  <meta name="twitter:image" content="{config['og_image']}" />
  <meta name="twitter:image:alt" content="{config['og_image_alt']}" />"""

        if '<meta name="twitter:card"' in html:
            html = re.sub(
                r'<!--\s*Twitter Card.*?-->\s*<meta\s+name=["\']twitter:card["\'].*?(?=(<!--\s*Schema|<script|<link\s+rel=["\']preconnect|<style))',
                tw_block + '\n\n',
                html,
                count=1,
                flags=re.DOTALL | re.IGNORECASE
            )
        else:
            # Insert after OG block
            html = html.replace(og_block, og_block + '\n\n' + tw_block)

        # 7. Add Schema.org JSON-LD if specified and missing
        if config.get("schema") and "application/ld+json" not in html:
            schema_json = json.dumps(config["schema"], indent=2, ensure_ascii=False)
            schema_tag = f"""  <!-- Schema.org JSON-LD Structured Data -->
  <script type="application/ld+json">
{schema_json}
  </script>
"""
            # Insert before Google Fonts or style
            if '<link rel="preconnect" href="https://fonts.googleapis.com"' in html:
                html = html.replace('<link rel="preconnect" href="https://fonts.googleapis.com"', schema_tag + '\n  <link rel="preconnect" href="https://fonts.googleapis.com"')
            elif '<link rel="stylesheet"' in html:
                html = re.sub(r'(<link\s+rel=["\']stylesheet["\'])', schema_tag + '\n  \\1', html, count=1, flags=re.IGNORECASE)

        # 8. Ensure DNS Prefetch and Resource hints
        if '<link rel="dns-prefetch"' not in html:
            if '<link rel="preconnect" href="https://fonts.googleapis.com"' in html:
                html = re.sub(
                    r'<link\s+rel=["\']preconnect["\']\s+href=["\']https://fonts\.googleapis\.com["\']\s*/>\s*<link\s+rel=["\']preconnect["\']\s+href=["\']https://fonts\.gstatic\.com["\']\s*crossorigin\s*/>',
                    RESOURCE_HINTS_BLOCK,
                    html,
                    count=1,
                    flags=re.IGNORECASE
                )

    # 9. OPTIMIZE ALL IMAGES (Cross-device speed & loading performance)
    # Parse <img> tags and add loading/decoding appropriately
    def optimize_img(match):
        img_str = match.group(0)
        
        # Don't duplicate attributes
        has_loading = 'loading=' in img_str
        has_decoding = 'decoding=' in img_str
        has_priority = 'fetchpriority=' in img_str
        
        # Determine role of image
        is_logo = 'tg2d-logo' in img_str or 'nav-logo' in img_str or 'h-8' in img_str or 'h-7' in img_str
        is_hero = ('hero-' in img_str or 'chantier-coulage' in img_str or 'chantier-vue-aerienne' in img_str or 
                   'bureau-etudes-plans' in img_str or 'chantier-structure' in img_str or 'chantier-ferraillage' in img_str or
                   'chantier-fondations' in img_str or 'chantier-terrassement' in img_str) and not is_logo
        
        attrs_to_add = []
        if not has_decoding:
            attrs_to_add.append('decoding="async"')
            
        if not has_loading:
            if is_hero and not is_logo:
                attrs_to_add.append('loading="eager"')
                if not has_priority:
                    attrs_to_add.append('fetchpriority="high"')
            elif not is_logo:
                attrs_to_add.append('loading="lazy"')
                
        if not attrs_to_add:
            return img_str
            
        insertion = ' ' + ' '.join(attrs_to_add)
        if img_str.endswith('/>'):
            return img_str[:-2].rstrip() + insertion + ' />'
        elif img_str.endswith('>'):
            return img_str[:-1].rstrip() + insertion + '>'
        return img_str

    html = re.sub(r'<img\s+[^>]*?>', optimize_img, html, flags=re.IGNORECASE)

    with open(filename, 'w', encoding='utf-8') as f:
        f.write(html)

    print(f"Successfully optimized {filename}!")


def main():
    print("Starting SEO & Cross-Device Optimization on all TG2D content pages...")
    for filename, config in sorted(PAGE_CONFIGS.items()):
        process_html_file(filename, config)
    print("\nAll 28 pages successfully processed!")


if __name__ == "__main__":
    main()
