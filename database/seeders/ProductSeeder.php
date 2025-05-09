<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $competitions = [
            [
                'name' => 'Kompetisi Debat Bahasa Indonesia (KDBI)',
                'description' => 'Kompetisi Debat Bahasa Indonesia (KDBI) adalah ajang kompetisi debat akademik menggunakan Bahasa Indonesia yang diselenggarakan oleh Universitas Nasional. Kompetisi ini bertujuan untuk menguji kemampuan mahasiswa dalam menyampaikan argumen, analisis kritis, dan public speaking dalam Bahasa Indonesia.',
                'price' => 300000,
                'code' => 'KDBI2025',
                'registration_start' => '2025-07-23',
                'registration_end' => '2025-09-07',
                'requirements' => "- Peserta adalah mahasiswa aktif S1/D3/D4 dari perguruan tinggi di Indonesia\n- Setiap tim terdiri dari 3 orang dari universitas yang sama\n- Memiliki kartu tanda mahasiswa (KTM) yang masih berlaku\n- Melengkapi data diri pada formulir pendaftaran\n- Melakukan pembayaran biaya pendaftaran",
                'prizes' => "- Juara 1: Rp 5.000.000 + Sertifikat + Trophy\n- Juara 2: Rp 3.000.000 + Sertifikat + Trophy\n- Juara 3: Rp 2.000.000 + Sertifikat + Trophy\n- Harapan 1: Rp 1.000.000 + Sertifikat\n- Harapan 2: Rp 500.000 + Sertifikat",
                'active' => true
            ],
            [
                'name' => 'Short Movie Competition (SMC)',
                'description' => 'Short Movie Competition (SMC) adalah kompetisi pembuatan film pendek untuk mahasiswa dengan tema "Bio University, Technology, Health". Kompetisi ini bertujuan untuk mengeksplorasi kreativitas mahasiswa dalam menyampaikan pesan melalui media audio visual.',
                'price' => 350000,
                'code' => 'SMC2025',
                'registration_start' => '2025-07-23',
                'registration_end' => '2025-09-07',
                'requirements' => "- Peserta adalah mahasiswa aktif S1/D3/D4 dari perguruan tinggi di Indonesia\n- Setiap tim terdiri dari maksimal 5 orang dari universitas yang sama\n- Memiliki kartu tanda mahasiswa (KTM) yang masih berlaku\n- Durasi film maksimal 15 menit termasuk opening dan credit title\n- Tema film sesuai dengan tema UNAS Fest 2025\n- Film belum pernah dipublikasikan sebelumnya\n- Melengkapi data diri pada formulir pendaftaran\n- Melakukan pembayaran biaya pendaftaran",
                'prizes' => "- Juara 1: Rp 7.000.000 + Sertifikat + Trophy\n- Juara 2: Rp 5.000.000 + Sertifikat + Trophy\n- Juara 3: Rp 3.000.000 + Sertifikat + Trophy\n- Best Actor/Actress: Rp 1.000.000 + Sertifikat\n- Best Cinematography: Rp 1.000.000 + Sertifikat",
                'active' => true
            ],
            [
                'name' => 'Scientific Paper Competition (SPC)',
                'description' => 'Scientific Paper Competition (SPC) adalah kompetisi penulisan karya tulis ilmiah dengan tema "Bio University, Technology, Health". Kompetisi ini bertujuan untuk mendorong mahasiswa dalam menghasilkan karya tulis ilmiah yang berkualitas dan bermanfaat bagi masyarakat.',
                'price' => 250000,
                'code' => 'SPC2025',
                'registration_start' => '2025-07-23',
                'registration_end' => '2025-09-07',
                'requirements' => "- Peserta adalah mahasiswa aktif S1/D3/D4 dari perguruan tinggi di Indonesia\n- Setiap tim terdiri dari maksimal 3 orang dari universitas yang sama\n- Memiliki kartu tanda mahasiswa (KTM) yang masih berlaku\n- Karya tulis belum pernah dipublikasikan atau diikutsertakan dalam kompetisi lain\n- Karya tulis mengikuti format yang ditentukan panitia\n- Tema karya tulis sesuai dengan tema UNAS Fest 2025\n- Melengkapi data diri pada formulir pendaftaran\n- Melakukan pembayaran biaya pendaftaran",
                'prizes' => "- Juara 1: Rp 4.000.000 + Sertifikat + Trophy\n- Juara 2: Rp 2.500.000 + Sertifikat + Trophy\n- Juara 3: Rp 1.500.000 + Sertifikat + Trophy\n- Best Paper: Rp 1.000.000 + Sertifikat",
                'active' => true
            ],
            [
                'name' => 'English Debate Competition (EDC)',
                'description' => 'English Debate Competition (EDC) adalah ajang kompetisi debat akademik menggunakan Bahasa Inggris yang diselenggarakan oleh Universitas Nasional. Kompetisi ini bertujuan untuk menguji kemampuan mahasiswa dalam menyampaikan argumen, analisis kritis, dan public speaking dalam Bahasa Inggris.',
                'price' => 350000,
                'code' => 'EDC2025',
                'registration_start' => '2025-07-23',
                'registration_end' => '2025-09-07',
                'requirements' => "- Peserta adalah mahasiswa aktif S1/D3/D4 dari perguruan tinggi di Indonesia\n- Setiap tim terdiri dari 3 orang dari universitas yang sama\n- Memiliki kartu tanda mahasiswa (KTM) yang masih berlaku\n- Format debat menggunakan British Parliamentary System\n- Melengkapi data diri pada formulir pendaftaran\n- Melakukan pembayaran biaya pendaftaran",
                'prizes' => "- Juara 1: Rp 6.000.000 + Sertifikat + Trophy\n- Juara 2: Rp 4.000.000 + Sertifikat + Trophy\n- Juara 3: Rp 2.500.000 + Sertifikat + Trophy\n- Best Speaker: Rp 1.000.000 + Sertifikat",
                'active' => true
            ]
        ];
        
        foreach ($competitions as $competition) {
            Product::create($competition);
        }
    }
}