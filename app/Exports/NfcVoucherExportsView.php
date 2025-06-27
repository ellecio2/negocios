<?php

namespace App\Exports;

use App\Models\NfcVoucher;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class NfcVoucherExportsView implements FromView, WithStyles
{
    protected $nfc_vouchers;

    public function __construct($nfc_vouchers)
    {
        $this->nfc_vouchers = $nfc_vouchers;
    }

    public function view(): View
    {
        return view('seller.pos.exports.nfc_chourches', [
            'nfc_vouchers' => $this->nfc_vouchers
        ]);
    }
    
    //estilos de exel
    public function styles(Worksheet $sheet)
    {
        $nfc_vouchersCount = count($this->nfc_vouchers);

        // Aplica los estilos a la tabla en Excel
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        $sheet->getStyle('A1:H1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('DDDDDD');
        $sheet->getStyle('A2:H' . ($nfc_vouchersCount + 1))->getAlignment()->setWrapText(true);

        // Ajusta automáticamente el ancho de las columnas
        foreach(range('A', 'H') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Agrega líneas alternas de color a las filas
        for ($row = 2; $row <= $nfc_vouchersCount + 1; $row += 2) {
            $sheet->getStyle('A' . $row . ':H' . $row)->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'F2F2F2' // Color gris claro
                    ]
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'] // Color negro
                    ]
                ]
            ]);
        }

        // Agrega bordes a todas las celdas
        $sheet->getStyle('A1:H' . ($nfc_vouchersCount + 1))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'] // Color negro
                ]
            ]
        ]);
    }


}
