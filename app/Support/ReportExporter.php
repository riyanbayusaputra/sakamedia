<?php

namespace App\Support;

use App\Filament\Resources\Subscriptions\SubscriptionResource;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\CSV\Options as CsvOptions;
use OpenSpout\Writer\CSV\Writer as CsvWriter;
use OpenSpout\Writer\XLSX\Options as XlsxOptions;
use OpenSpout\Writer\XLSX\Writer as XlsxWriter;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportExporter
{
    public static function download(string $report, string $format, ReportFilters $filters): StreamedResponse
    {
        abort_unless(auth()->user()?->is_admin, 403);
        abort_unless(in_array($format, ['xlsx', 'csv'], true), 404);

        $definition = self::definition($report, $filters);

        $filename = sprintf(
            'laporan-%s_%s_sd_%s.%s',
            $report,
            $filters->start->format('Y-m-d'),
            $filters->end->format('Y-m-d'),
            $format
        );

        return response()->streamDownload(function () use ($definition, $format, $filters) {
            if ($format === 'csv') {
                self::writeCsv($definition);
            } else {
                self::writeXlsx($definition, $filters);
            }
        }, $filename, [
            'Content-Type' => $format === 'csv'
                ? 'text/csv; charset=UTF-8'
                : 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private static function definition(string $report, ReportFilters $filters): array
    {
        return match ($report) {
            'pendaftaran' => self::pendaftaran($filters),
            'pemasangan' => self::pemasangan($filters),
            'modem' => self::modemTerpasang($filters),
            'stok' => self::stokModem($filters),
            default => abort(404),
        };
    }

    private static function pendaftaran(ReportFilters $filters): array
    {
        $status = SubscriptionResource::statusOptions();

        $rows = $filters->registrations()
            ->with(['user', 'technician'])
            ->orderBy('created_at')
            ->orderBy('id')
            ->lazy(500)
            ->map(fn ($s) => [
                $s->created_at?->format('Y-m-d H:i'),
                $s->name,
                $s->phone,
                $s->installation_address,
                $s->product_name,
                $s->user?->name ?? '-',
                $s->technician?->name ?? '-',
                $s->survey_date?->format('Y-m-d') ?? '-',
                $status[$s->status] ?? ucfirst($s->status),
                $s->notes ?? '',
            ]);

        return [
            'title' => 'Laporan Pendaftaran',
            'sheet' => 'Pendaftaran',
            'filters' => $filters->describeFilters(),
            'numbered' => true,
            'headers' => ['Tgl Daftar', 'Pelanggan', 'Telepon', 'Alamat', 'Paket', 'Sales', 'Teknisi', 'Tgl Survei', 'Status', 'Catatan'],
            'widths' => [18, 26, 16, 45, 20, 18, 18, 14, 14, 35],
            'rows' => $rows,
        ];
    }

    private static function pemasangan(ReportFilters $filters): array
    {
        $rows = $filters->activations()
            ->with(['user', 'technician', 'modem.modemModel'])
            ->orderBy('activated_at')
            ->orderBy('id')
            ->lazy(500)
            ->map(function ($s) {
                $modem = $s->modem;

                return [
                    $s->activated_at?->format('Y-m-d H:i'),
                    $s->name,
                    $s->phone,
                    $s->installation_address,
                    $s->product_name,
                    $s->user?->name ?? '-',
                    $s->technician?->name ?? '-',
                    $s->team ?? '-',
                    $s->odp ?? '-',
                    $s->odp_port ?? '-',
                    $s->odc ?? '-',
                    $modem?->modemModel?->name ?? '-',
                    $modem?->modemModel ? strtoupper($modem->modemModel->type) : '-',
                    $modem?->serial_number ?? '-',
                    implode('; ', $s->materials ?? []) ?: '-',
                    $s->latitude !== null ? (float) $s->latitude : '',
                    $s->longitude !== null ? (float) $s->longitude : '',
                    $s->maps_url ?? '',
                ];
            });

        return [
            'title' => 'Laporan Pemasangan (Aktivasi)',
            'sheet' => 'Pemasangan',
            'filters' => $filters->describeFilters(),
            'numbered' => true,
            'headers' => [
                'Tgl Aktivasi', 'Pelanggan', 'Telepon', 'Alamat', 'Paket', 'Sales', 'Teknisi', 'Team',
                'ODP', 'ODP Port', 'ODC', 'Modem', 'Tipe', 'SN Modem', 'Material', 'Latitude', 'Longitude', 'Link Maps',
            ],
            'widths' => [18, 26, 16, 45, 20, 18, 18, 18, 8, 10, 8, 20, 8, 24, 40, 12, 12, 45],
            'rows' => $rows,
        ];
    }

    private static function modemTerpasang(ReportFilters $filters): array
    {
        $rows = $filters->installedModems()
            ->with(['modemModel', 'subscription.technician', 'subscription.user'])
            ->orderBy('installed_at')
            ->orderBy('id')
            ->lazy(500)
            ->map(fn ($m) => [
                $m->installed_at?->format('Y-m-d H:i'),
                $m->serial_number,
                $m->modemModel?->name ?? '-',
                $m->modemModel ? strtoupper($m->modemModel->type) : '-',
                $m->subscription?->name ?? '-',
                $m->subscription?->installation_address ?? '-',
                $m->subscription?->technician?->name ?? '-',
                $m->subscription?->team ?? '-',
                $m->subscription?->user?->name ?? '-',
            ]);

        return [
            'title' => 'Laporan Modem Terpasang',
            'sheet' => 'Modem Terpasang',
            'filters' => $filters->describeFilters(),
            'numbered' => true,
            'headers' => ['Tgl Pasang', 'SN', 'Model', 'Tipe', 'Pelanggan', 'Alamat', 'Teknisi', 'Team', 'Sales'],
            'widths' => [18, 26, 20, 8, 26, 45, 18, 18, 18],
            'rows' => $rows,
        ];
    }

    private static function stokModem(ReportFilters $filters): array
    {
        $models = $filters->stockByModel()->orderBy('name')->get();

        $rows = $models->map(fn ($m) => [
            $m->name,
            strtoupper($m->type),
            (int) $m->masuk_count,
            (int) $m->dipasang_count,
            (int) $m->tersedia_count,
            (int) $m->rusak_count,
            (int) $m->total_count,
        ])->all();

        $rows[] = [
            'TOTAL',
            '',
            (int) $models->sum('masuk_count'),
            (int) $models->sum('dipasang_count'),
            (int) $models->sum('tersedia_count'),
            (int) $models->sum('rusak_count'),
            (int) $models->sum('total_count'),
        ];

        return [
            'title' => 'Laporan Stok Modem per Model',
            'sheet' => 'Stok Modem',
            'filters' => 'Hanya periode yang berlaku (filter teknisi, sales, dan paket tidak memengaruhi stok)',
            'numbered' => false,
            'headers' => ['Model', 'Tipe', 'Masuk (periode)', 'Dipasang (periode)', 'Tersedia sekarang', 'Rusak / Retur', 'Total unit'],
            'widths' => [28, 8, 16, 18, 18, 14, 12],
            'rows' => $rows,
        ];
    }

    private static function writeXlsx(array $definition, ReportFilters $filters): void
    {
        $options = new XlsxOptions();

        $widths = $definition['widths'];
        if ($definition['numbered']) {
            array_unshift($widths, 6);
        }
        foreach ($widths as $index => $width) {
            $options->setColumnWidth($width, $index + 1);
        }

        $writer = new XlsxWriter($options);
        $writer->openToFile('php://output');
        $writer->getCurrentSheet()->setName($definition['sheet']);

        $bold = (new Style())->setFontBold();

        $writer->addRow(Row::fromValues([$definition['title']], $bold));
        $writer->addRow(Row::fromValues(['Periode: ' . $filters->label()]));
        $writer->addRow(Row::fromValues(['Filter: ' . $definition['filters']]));
        $writer->addRow(Row::fromValues([
            'Diekspor: ' . now()->translatedFormat('d M Y, H:i') . ' oleh ' . (auth()->user()?->name ?? '-'),
        ]));
        $writer->addRow(Row::fromValues(['']));

        $headers = $definition['numbered']
            ? array_merge(['No'], $definition['headers'])
            : $definition['headers'];

        $writer->addRow(Row::fromValues($headers, $bold));

        $number = 0;

        foreach ($definition['rows'] as $values) {
            $values = array_map(fn ($value) => self::safe($value, false), $values);

            if ($definition['numbered']) {
                array_unshift($values, ++$number);
            }

            $writer->addRow(Row::fromValues($values));
        }

        $writer->close();
    }

    private static function writeCsv(array $definition): void
    {
        $writer = new CsvWriter(new CsvOptions());
        $writer->openToFile('php://output');

        $headers = $definition['numbered']
            ? array_merge(['No'], $definition['headers'])
            : $definition['headers'];

        $writer->addRow(Row::fromValues($headers));

        $number = 0;

        foreach ($definition['rows'] as $values) {
            $values = array_map(fn ($value) => self::safe($value, true), $values);

            if ($definition['numbered']) {
                array_unshift($values, ++$number);
            }

            $writer->addRow(Row::fromValues($values));
        }

        $writer->close();
    }

    /**
     * Cegah formula injection: teks yang diawali karakter rumus tidak boleh dieksekusi Excel.
     * Di XLSX hanya awalan "=" yang berbahaya (sel teks), di CSV semua karakter rumus.
     */
    private static function safe(mixed $value, bool $csv): mixed
    {
        if ($value === null) {
            return '';
        }

        if (! is_string($value) || $value === '' || is_numeric($value)) {
            return $value;
        }

        $dangerous = $csv
            ? in_array($value[0], ['=', '+', '-', '@', "\t", "\r"], true)
            : $value[0] === '=';

        return $dangerous ? "'" . $value : $value;
    }
}