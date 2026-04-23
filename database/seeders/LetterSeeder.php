<?php

namespace Database\Seeders;

use App\Models\Letter;
use App\Models\User;
use App\Services\LetterService;
use Illuminate\Database\Seeder;

class LetterSeeder extends Seeder
{
    public function run(): void
    {
        $service = app(LetterService::class);
        $admin = User::query()->where('email', env('ADMIN_EMAIL', 'admin@input-surat.local'))->first();

        $statuses = Letter::statuses();
        $categories = ['Internal', 'Eksternal', 'Keuangan', 'Operasional'];

        for ($i = 1; $i <= 20; $i++) {
            $date = now()->subDays(20 - $i);

            Letter::create([
                'letter_no' => $service->generateLetterNo($date),
                'date' => $date->toDateString(),
                'subject' => "Contoh Surat #{$i}",
                'sender' => 'Admin Office',
                'recipient' => "Penerima {$i}",
                'category' => $categories[array_rand($categories)],
                'status' => $statuses[array_rand($statuses)],
                'notes' => 'Data seed untuk demo Input Surat.',
                'created_by' => $admin?->id,
                'updated_by' => $admin?->id,
            ]);
        }
    }
}
