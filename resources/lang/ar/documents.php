<?php

return [

    // --- الأنواع الرئيسية ---
    'types' => [
        'PIÈCES ADMINISTRATIVES ORDINAIRES / PAGE' => 'وثائق إدارية عادية / صفحة',
        'CERTIFICATS MÉDICAUX / PAGE' => 'شهادات طبية / صفحة',
        'DOCUMENTS ADULAIRES / PAGE' => 'وثائق عدلية / صفحة',
        'PIÈCES ÉTABLIES À L’ÉTRANGER / PAGE' => 'وثائق محررة بالخارج / صفحة',
        'DOSSIERS (240 mots / page)' => 'ملفات (240 كلمة / صفحة)',
        'INTERPRÉTARIAT / SIMULTANÉ OU CONSÉCUTIF' => 'الترجمة الفورية أو التتابعية',
    ],

    // --- الأنواع الفرعية ---
    'sous_types' => [

        // وثائق إدارية
        'PIÈCES ADMINISTRATIVES ORDINAIRES / PAGE' => [
            "Certificat de résidence" => "شهادة السكنى",
            "Attestation de célibat" => "شهادة العزوبة",
            "Extrait d’acte de naissance" => "مستخرج عقد الازدياد",
            "Copie intégrale d’acte de naissance" => "نسخة كاملة من عقد الازدياد",
            "Extrait du casier judiciaire" => "مستخرج السجل العدلي",
            "Fiche anthropometrique" => "البطاقة التعريفية",
            "Attestation de travail" => "شهادة العمل",
            "Attestation de parenté" => "شهادة القرابة",
            "Certificat de concordance" => "شهادة المطابقة",
            "Certificat de propriété" => "شهادة الملكية",
            "Inscription au registre du commerce" => "التسجيل في السجل التجاري",
            "Certificat de scolarité/d’inscription" => "شهادة مدرسية / التسجيل",
            "Bulletin de notes (semestre/Baccalauréat marocain)" => "بيان النقط (الفصل / البكالوريا المغربية)",
            "Bulletin de notes (mission)" => "بيان النقط (مدارس البعثة)",
            "Copies supplémentaires/page" => "نسخ إضافية / صفحة",
        ],

        // شهادات طبية
        'CERTIFICATS MÉDICAUX / PAGE' => [
            "Certificat médical ordinaire" => "شهادة طبية عادية",
            "Autres pièces médicales" => "وثائق طبية أخرى",
        ],



        // وثائق عدلية
        'DOCUMENTS ADULAIRES / PAGE' => [
            "Acte de mariage" => "عقد الزواج",
            "Acte récognitif de mariage" => "عقد الإشهاد بالزواج",
            "Acte de mariage mixte" => "عقد الزواج المختلط",
            "Reprise en mariage" => "استئناف الزواج",
            "Acte de divorce établi avant 2004" => "عقد الطلاق قبل 2004",
            "Acte de divorce suivant le code de la famille 2004" => "عقد الطلاق وفق مدونة الأسرة 2004",
            "Témoignages/Actes divers (divers irrévocalbles)" => "شهادات / عقود متنوعة (غير قابلة للنقض)",
            "Additif rectificatif" => "ملحق تصحيحي",
            "Acte testimonial" => "عقد إشهاد",
            "Renonciation de parenté" => "التنازل عن القرابة",
            "Absence de conjoint" => "غياب الزوج",
            "Désistement" => "تنازل",
            "Kafala" => "الكفالة",
            "Acte de prise en charge" => "عقد التكفل",
            "Procuration" => "التوكيل",
            "Remise d’enfants" => "تسليم الأطفال",
            "Acte d’héritier" => "شهادة الوراثة",
            "Inventaire successoral" => "جرد التركة",
            "Partage successoral" => "قسمة التركة",
            "Acte d’achat / vente" => "عقد البيع / الشراء",
            "Copies supplémentaires / page" => "نسخ إضافية / صفحة",
        ],

        // وثائق محررة بالخارج
        'PIÈCES ÉTABLIES À L’ÉTRANGER / PAGE' => [
            "Copie intégrale d’acte de naissance" => "نسخة كاملة من عقد الازدياد",
            "Acte de naissance" => "عقد الازدياد",
            "Certificat de capacité à mariage" => "شهادة القدرة على الزواج",
            "Certificat de divorce" => "شهادة الطلاق",
            "Extrait du casier judiciaire" => "مستخرج السجل العدلي",
            "Attestation de célibat" => "شهادة العزوبة",
            "Certificat de résidence" => "شهادة السكنى",
            "Acte de mariage" => "عقد الزواج",
        ],

        // ملفات
        'DOSSIERS (240 mots / page)' => [
            "Dossiers juridiques (jugements – actes notariés – procès-verbaux – statuts)"
            => "ملفات قانونية (أحكام – عقود موثقة – محاضر – أنظمة أساسية)",
            "Dossiers techniques" => "ملفات تقنية",
        ],

        // الترجمة الفورية / التتابعية
        'INTERPRÉTARIAT / SIMULTANÉ OU CONSÉCUTIF' => [
            "Assemblées générales" => "الجمعيات العامة",
            "Conseils d’administration" => "مجالس الإدارة",
            "Séances de conclusion d'actes" => "جلسات إبرام العقود",
            "Débats aux audiences des tribunaux" => "المرافعات في جلسات المحاكم",
        ],
    ]
];
