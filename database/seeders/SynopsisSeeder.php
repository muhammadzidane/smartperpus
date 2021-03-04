<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Synopsis;

class SynopsisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $synopses = array(
            array(
                'book_id' => 1,
                'text'    =>

                'Yuji Itadori seorang murid SMA dengan kemampuan atletik yang luar biasa.Kesehariannya adalah menjenguk kakeknya yang terbaring di rumah sakit. Suatu hari, segel "objek terkutuk" yang berada di sekolahnya terlepas, monster-monster pun mulai bermunculan. Yuji menyusup ke dalam gedung sekolah demi menolong senior di klubnya, akan tetapi...!?',
            ),
            array(
                'book_id' => 2,
                'text'    =>

                'Pada zaman Taisho di Jepang, hiduplah Tanjiro Kamado,seorang anak laki-laki berhati lembut yang hidup dari      menjual arang. Namun, kehidupan damainya tiba-tiba hancur saat iblis membantai seluruh keluarganya.
                Hanya Nezuko, salah satu adik perempuannya, yang bertahan hidup. Masalahnya, Nezuko pun berubah menjadi iblis! Demi mengembalikan Nezuko menjadi manusia dan membalaskan dendam keluarganya, Tanjiro bertekad untuk menekuni jalan sebagai seorang pembasmi iblis...!!',
            ),
            array(
                'book_id' => 3,
                'text'    =>

                'Pertama kalinya Heiji Hattori berhadapan dengan si Kid Pencuri yang mengincar "Fairy Lip "! Di kasus lain Makoto Kyogoku terlibat dalam insiden di lokasi syuting TV drama. . . ! ? Selanjutnya ada informasi baru terkuaknya bos Organisasi Baju Hitam!!',
            ),
            array(
                'book_id' => 4,
                'text'    =>

                'Fairy Tail kedatangan anggota baru! Tepat di saat Natsu, Lucy, Happy dan kawan-kawan diminta untuk mengikuti quest yang sudah berlangsung selama 100 tahun dari benua seberang sana! Apakah Natsu dkk bisa memahami inti dari quest yang akan diberikan!? Bagaimana Natsu dkk akan menjalani quest-nya!? Di sisi lain, sepertinya anggota baru Fairy Tail ada yang mencurigakan! Ikuti petulangan baru “Fairy Tail” yang seru!',
            ),
            array(
                'book_id' => 5,
                'text'    =>

                'Pertandingan melawan Shiratorizawa telah sampai pada set kelima. Cedera yang dialami Tsukishima menyebabkan kekosongan pada pilar pertahanan Karasuno, tapi mereka terus berjuang dengan tekad kuat untuk merebut angka! Pertarungan sengit yang menguras stamina ini akhirnya sampai pada babak terakhir.
                Siapakah yang akan mendapatkan predikat wakil prefektur?',
            ),
        );

        foreach ($synopses as $synopsis) {
            Synopsis::create(
                array(
                    'text'    => $synopsis['text'],
                    'book_id' => $synopsis['book_id'],
                )
            );
        }
    }
}
