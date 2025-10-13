<?php

return [

    // --- Main Types ---
    'types' => [
        'PIÈCES ADMINISTRATIVES ORDINAIRES / PAGE' => 'Ordinary Administrative Documents / Page',
        'CERTIFICATS MÉDICAUX / PAGE' => 'Medical Certificates / Page',
        'DOCUMENTS ADULAIRES / PAGE' => 'Adoul Documents / Page',
        'PIÈCES ÉTABLIES À L’ÉTRANGER / PAGE' => 'Documents Issued Abroad / Page',
        'DOSSIERS (240 mots / page)' => 'Files (240 words / page)',
        'INTERPRÉTARIAT / SIMULTANÉ OU CONSÉCUTIF' => 'Interpretation / Simultaneous or Consecutive',
    ],

    // --- Sub-types ---
    'sous_types' => [

        // Administrative documents
        'PIÈCES ADMINISTRATIVES ORDINAIRES / PAGE' => [
            "Certificat de résidence" => "Certificate of Residence",
            "Attestation de célibat" => "Certificate of Celibacy",
            "Extrait d’acte de naissance" => "Extract of Birth Certificate",
            "Copie intégrale d’acte de naissance" => "Full Copy of Birth Certificate",
            "Extrait du casier judiciaire" => "Criminal Record Extract",
            "Fiche anthropometrique" => "Anthropometric Record",
            "Attestation de travail" => "Work Certificate",
            "Attestation de parenté" => "Kinship Certificate",
            "Certificat de concordance" => "Concordance Certificate",
            "Certificat de propriété" => "Property Certificate",
            "Inscription au registre du commerce" => "Commercial Register Registration",
            "Certificat de scolarité/d’inscription" => "School/Enrollment Certificate",
            "Bulletin de notes (semestre/Baccalauréat marocain)" => "Transcript (Semester / Moroccan Baccalaureate)",
            "Bulletin de notes (mission)" => "Transcript (Mission School)",
            "Copies supplémentaires/page" => "Additional Copies / Page",
        ],

        // Medical certificates
        'CERTIFICATS MÉDICAUX / PAGE' => [
            "Certificat médical ordinaire" => "Ordinary Medical Certificate",
            "Autres pièces médicales" => "Other Medical Documents",
        ],

        // Adoul documents
        'DOCUMENTS ADULAIRES / PAGE' => [
            "Acte de mariage" => "Marriage Certificate",
            "Acte récognitif de mariage" => "Recognition of Marriage Certificate",
            "Acte de mariage mixte" => "Mixed Marriage Certificate",
            "Reprise en mariage" => "Marriage Resumption",
            "Acte de divorce établi avant 2004" => "Divorce Certificate (before 2004)",
            "Acte de divorce suivant le code de la famille 2004" => "Divorce Certificate (Family Code 2004)",
            "Témoignages/Actes divers (divers irrévocalbles)" => "Testimonies / Miscellaneous Acts (Irrevocable)",
            "Additif rectificatif" => "Corrective Addendum",
            "Acte testimonial" => "Testimonial Document",
            "Renonciation de parenté" => "Renunciation of Kinship",
            "Absence de conjoint" => "Absence of Spouse",
            "Désistement" => "Withdrawal",
            "Kafala" => "Kafala (Guardianship)",
            "Acte de prise en charge" => "Custody Document",
            "Procuration" => "Power of Attorney",
            "Remise d’enfants" => "Child Handover",
            "Acte d’héritier" => "Heir Certificate",
            "Inventaire successoral" => "Inheritance Inventory",
            "Partage successoral" => "Inheritance Distribution",
            "Acte d’achat / vente" => "Purchase / Sale Deed",
            "Copies supplémentaires / page" => "Additional Copies / Page",
        ],

        // Documents issued abroad
        'PIÈCES ÉTABLIES À L’ÉTRANGER / PAGE' => [
            "Copie intégrale d’acte de naissance" => "Full Copy of Birth Certificate",
            "Acte de naissance" => "Birth Certificate",
            "Certificat de capacité à mariage" => "Certificate of Capacity to Marry",
            "Certificat de divorce" => "Divorce Certificate",
            "Extrait du casier judiciaire" => "Criminal Record Extract",
            "Attestation de célibat" => "Certificate of Celibacy",
            "Certificat de résidence" => "Certificate of Residence",
            "Acte de mariage" => "Marriage Certificate",
        ],

        // Files
        'DOSSIERS (240 mots / page)' => [
            "Dossiers juridiques (jugements – actes notariés – procès-verbaux – statuts)"
            => "Legal Files (Judgments – Notarial Acts – Reports – Statutes)",
            "Dossiers techniques" => "Technical Files",
        ],

        // Interpretation
        'INTERPRÉTARIAT / SIMULTANÉ OU CONSÉCUTIF' => [
            "Assemblées générales" => "General Assemblies",
            "Conseils d’administration" => "Board Meetings",
            "Séances de conclusion d'actes" => "Contract Signing Sessions",
            "Débats aux audiences des tribunaux" => "Court Hearings Debates",
        ],
    ]
];
