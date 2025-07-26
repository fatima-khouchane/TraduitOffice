<?php

namespace App\Exports;

use App\Models\Demande;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class DemandesExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    protected $demandes;

    public function __construct($demandes)
    {
        $this->demandes = $demandes;
    }

    public function collection()
    {
        return $this->demandes->map(function ($demande) {
            $documents = is_string($demande->documents) ? json_decode($demande->documents, true) : $demande->documents;
            $sousTypes = collect($documents)->pluck('sous_type')->implode(', ');

            return [
                'nom_titulaire' => $demande->nom_titulaire,
                'nom_demandeur' => $demande->nom_demandeur,
                'cin' => $demande->cin,
                'telephone' => $demande->telephone,
                'date_debut' => optional($demande->date_debut)->format('d/m/Y'),
                'date_fin' => optional($demande->date_fin)->format('d/m/Y'),
                'prix_total' => $demande->prix_total,
                'documents' => $sousTypes,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nom Titulaire',
            'Nom Demandeur',
            'CIN',
            'Téléphone',
            'Date Début',
            'Date Fin',
            'Prix Total',
            'Documents',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style de base pour la feuille (optionnel)
        return [
            // En-tête (ligne 1)
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF4CAF50'], // vert moyen
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                ],
            ],
            // Tout le tableau (pour avoir bordure fine)
            'A1:H' . ($this->demandes->count() + 1) => [
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                ],
            ],
            // Colonne prix_total alignée à droite
            'G2:G' . ($this->demandes->count() + 1) => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
            ],
            // Colonne dates centrées
            'E2:F' . ($this->demandes->count() + 1) => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $count = $this->demandes->count() + 2; // ligne du total = header + nb lignes + 1
    
                $total = $this->demandes->sum('prix_total');

                // Écrire "Total:" dans la colonne F, et la somme dans la colonne G
                $sheet->setCellValue('F' . $count, 'Total:');
                $sheet->setCellValue('G' . $count, $total);

                // Style de la ligne total
                $sheet->getStyle('A' . $count . ':H' . $count)->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['argb' => 'FF000000']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFB9F6CA'], // vert clair
                    ],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                ]);

                // Alignement droite pour le total
                $sheet->getStyle('G' . $count)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // Ajuster automatiquement la largeur des colonnes A à H
                foreach (range('A', 'H') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
