<?php

return [

    // --- Types principaux ---
    'types' => [
        'PIÈCES ADMINISTRATIVES ORDINAIRES / PAGE',
        'CERTIFICATS MÉDICAUX / PAGE',
        'DOCUMENTS ADULAIRES / PAGE',
        'PIÈCES ÉTABLIES À L’ÉTRANGER / PAGE',
        'DOSSIERS (240 mots / page)',
        'INTERPRÉTARIAT / SIMULTANÉ OU CONSÉCUTIF',
    ],

    // --- Sous-types ---
    'sous_types' => [

        // Pièces administratives
        'PIÈCES ADMINISTRATIVES ORDINAIRES / PAGE' => [
            "Certificat de résidence",
            "Attestation de célibat",
            "Extrait d’acte de naissance",
            "Copie intégrale d’acte de naissance",
            "Extrait du casier judiciaire",
            "Fiche anthropometrique",
            "Attestation de travail",
            "Attestation de parenté",
            "Certificat de concordance",
            "Certificat de propriété",
            "Inscription au registre du commerce",
            "Certificat de scolarité/d’inscription",
            "Bulletin de notes (semestre/Baccalauréat marocain)",
            "Bulletin de notes (mission)",
            "Copies supplémentaires/page",
        ],

        // Certificats médicaux
        'CERTIFICATS MÉDICAUX / PAGE' => [
            "Certificat médical ordinaire",
            "Autres pièces médicales",
        ],

        // Documents adoulaires
        'DOCUMENTS ADULAIRES / PAGE' => [
            "Acte de mariage",
            "Acte récognitif de mariage",
            "Acte de mariage mixte",
            "Reprise en mariage",
            "Acte de divorce établi avant 2004",
            "Acte de divorce suivant le code de la famille 2004",
            "Témoignages/Actes divers (divers irrévocalbles)",
            "Additif rectificatif",
            "Acte testimonial",
            "Renonciation de parenté",
            "Absence de conjoint",
            "Désistement",
            "Kafala",
            "Acte de prise en charge",
            "Procuration",
            "Remise d’enfants",
            "Acte d’héritier",
            "Inventaire successoral",
            "Partage successoral",
            "Acte d’achat / vente",
            "Copies supplémentaires / page",
        ],

        // Pièces établies à l’étranger
        'PIÈCES ÉTABLIES À L’ÉTRANGER / PAGE' => [
            "Copie intégrale d’acte de naissance",
            "Acte de naissance",
            "Certificat de capacité à mariage",
            "Certificat de divorce",
            "Extrait du casier judiciaire",
            "Attestation de célibat",
            "Certificat de résidence",
            "Acte de mariage",
        ],

        // Dossiers
        'DOSSIERS (240 mots / page)' => [
            "Dossiers juridiques (jugements – actes notariés – procès-verbaux – statuts)",
            "Dossiers techniques",
        ],

        // Interprétariat
        'INTERPRÉTARIAT / SIMULTANÉ OU CONSÉCUTIF' => [
            "Assemblées générales",
            "Conseils d’administration",
            "Séances de conclusion d'actes",
            "Débats aux audiences des tribunaux",
        ],
    ]
];
