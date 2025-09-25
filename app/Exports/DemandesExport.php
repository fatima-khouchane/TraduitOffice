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

            $sousTypes = collect($documents)->map(function ($doc) {
                $categorie = __('documents.types.' . $doc['categorie']) ?? $doc['categorie'];
                $sousType = __('documents.sous_types.' . $doc['categorie'] . '.' . $doc['sous_type']) ?? $doc['sous_type'];
                return $categorie . ': ' . $sousType;
            })->implode("\n"); // chaque doc sur une nouvelle ligne


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
            __('pdf.nom_titulaire'),
            __('pdf.nom_demandeur'),
            __('pdf.cin'),
            __('pdf.telephone'),
            __('pdf.date_debut'),
            __('pdf.date_fin'),
            __('pdf.prix_total'),
            __('pdf.documents_traduits'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // En-tête (ligne 1)
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF0D47A1'], // bleu foncé
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                ],
            ],
            // Bordures pour tout le tableau
            'A1:H' . ($this->demandes->count() + 1) => [
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                ],
            ],
            // Colonne prix_total alignée à droite
            'G2:G' . ($this->demandes->count() + 1) => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
            ],
            // Colonnes dates centrées
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
                $count = $this->demandes->count() + 2; // ligne total = header + nb lignes + 1
    
                $total = $this->demandes->sum('prix_total');

                // Écrire "Total:" traduit
                $sheet->setCellValue('F' . $count, __('pdf.total') . ':');
                $sheet->setCellValue('G' . $count, $total);

                // Style de la ligne total
                $sheet->getStyle('A' . $count . ':H' . $count)->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FF0D47A1'], // bleu foncé
                    ],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                ]);

                // Alignement droite pour le total
                $sheet->getStyle('G' . $count)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // Ajuster automatiquement la largeur des colonnes
                foreach (range('A', 'H') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
