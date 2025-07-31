<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prefecture;

class PrefectureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prefectures = [
            // 北海道・東北
            ['code' => '01', 'code_en' => 'hokkaido', 'name' => '北海道', 'region' => '北海道・東北', 'display_order' => 1],
            ['code' => '02', 'code_en' => 'aomori', 'name' => '青森県', 'region' => '北海道・東北', 'display_order' => 2],
            ['code' => '03', 'code_en' => 'iwate', 'name' => '岩手県', 'region' => '北海道・東北', 'display_order' => 3],
            ['code' => '04', 'code_en' => 'miyagi', 'name' => '宮城県', 'region' => '北海道・東北', 'display_order' => 4],
            ['code' => '05', 'code_en' => 'akita', 'name' => '秋田県', 'region' => '北海道・東北', 'display_order' => 5],
            ['code' => '06', 'code_en' => 'yamagata', 'name' => '山形県', 'region' => '北海道・東北', 'display_order' => 6],
            ['code' => '07', 'code_en' => 'fukushima', 'name' => '福島県', 'region' => '北海道・東北', 'display_order' => 7],
            
            // 関東
            ['code' => '08', 'code_en' => 'ibaraki', 'name' => '茨城県', 'region' => '関東', 'display_order' => 8],
            ['code' => '09', 'code_en' => 'tochigi', 'name' => '栃木県', 'region' => '関東', 'display_order' => 9],
            ['code' => '10', 'code_en' => 'gunma', 'name' => '群馬県', 'region' => '関東', 'display_order' => 10],
            ['code' => '11', 'code_en' => 'saitama', 'name' => '埼玉県', 'region' => '関東', 'display_order' => 11],
            ['code' => '12', 'code_en' => 'chiba', 'name' => '千葉県', 'region' => '関東', 'display_order' => 12],
            ['code' => '13', 'code_en' => 'tokyo', 'name' => '東京都', 'region' => '関東', 'display_order' => 13],
            ['code' => '14', 'code_en' => 'kanagawa', 'name' => '神奈川県', 'region' => '関東', 'display_order' => 14],
            
            // 中部
            ['code' => '15', 'code_en' => 'niigata', 'name' => '新潟県', 'region' => '中部', 'display_order' => 15],
            ['code' => '16', 'code_en' => 'toyama', 'name' => '富山県', 'region' => '中部', 'display_order' => 16],
            ['code' => '17', 'code_en' => 'ishikawa', 'name' => '石川県', 'region' => '中部', 'display_order' => 17],
            ['code' => '18', 'code_en' => 'fukui', 'name' => '福井県', 'region' => '中部', 'display_order' => 18],
            ['code' => '19', 'code_en' => 'yamanashi', 'name' => '山梨県', 'region' => '中部', 'display_order' => 19],
            ['code' => '20', 'code_en' => 'nagano', 'name' => '長野県', 'region' => '中部', 'display_order' => 20],
            ['code' => '21', 'code_en' => 'gifu', 'name' => '岐阜県', 'region' => '中部', 'display_order' => 21],
            ['code' => '22', 'code_en' => 'shizuoka', 'name' => '静岡県', 'region' => '中部', 'display_order' => 22],
            ['code' => '23', 'code_en' => 'aichi', 'name' => '愛知県', 'region' => '中部', 'display_order' => 23],
            
            // 近畿
            ['code' => '24', 'code_en' => 'mie', 'name' => '三重県', 'region' => '近畿', 'display_order' => 24],
            ['code' => '25', 'code_en' => 'shiga', 'name' => '滋賀県', 'region' => '近畿', 'display_order' => 25],
            ['code' => '26', 'code_en' => 'kyoto', 'name' => '京都府', 'region' => '近畿', 'display_order' => 26],
            ['code' => '27', 'code_en' => 'osaka', 'name' => '大阪府', 'region' => '近畿', 'display_order' => 27],
            ['code' => '28', 'code_en' => 'hyogo', 'name' => '兵庫県', 'region' => '近畿', 'display_order' => 28],
            ['code' => '29', 'code_en' => 'nara', 'name' => '奈良県', 'region' => '近畿', 'display_order' => 29],
            ['code' => '30', 'code_en' => 'wakayama', 'name' => '和歌山県', 'region' => '近畿', 'display_order' => 30],
            
            // 中国
            ['code' => '31', 'code_en' => 'tottori', 'name' => '鳥取県', 'region' => '中国', 'display_order' => 31],
            ['code' => '32', 'code_en' => 'shimane', 'name' => '島根県', 'region' => '中国', 'display_order' => 32],
            ['code' => '33', 'code_en' => 'okayama', 'name' => '岡山県', 'region' => '中国', 'display_order' => 33],
            ['code' => '34', 'code_en' => 'hiroshima', 'name' => '広島県', 'region' => '中国', 'display_order' => 34],
            ['code' => '35', 'code_en' => 'yamaguchi', 'name' => '山口県', 'region' => '中国', 'display_order' => 35],
            
            // 四国
            ['code' => '36', 'code_en' => 'tokushima', 'name' => '徳島県', 'region' => '四国', 'display_order' => 36],
            ['code' => '37', 'code_en' => 'kagawa', 'name' => '香川県', 'region' => '四国', 'display_order' => 37],
            ['code' => '38', 'code_en' => 'ehime', 'name' => '愛媛県', 'region' => '四国', 'display_order' => 38],
            ['code' => '39', 'code_en' => 'kochi', 'name' => '高知県', 'region' => '四国', 'display_order' => 39],
            
            // 九州・沖縄
            ['code' => '40', 'code_en' => 'fukuoka', 'name' => '福岡県', 'region' => '九州・沖縄', 'display_order' => 40],
            ['code' => '41', 'code_en' => 'saga', 'name' => '佐賀県', 'region' => '九州・沖縄', 'display_order' => 41],
            ['code' => '42', 'code_en' => 'nagasaki', 'name' => '長崎県', 'region' => '九州・沖縄', 'display_order' => 42],
            ['code' => '43', 'code_en' => 'kumamoto', 'name' => '熊本県', 'region' => '九州・沖縄', 'display_order' => 43],
            ['code' => '44', 'code_en' => 'oita', 'name' => '大分県', 'region' => '九州・沖縄', 'display_order' => 44],
            ['code' => '45', 'code_en' => 'miyazaki', 'name' => '宮崎県', 'region' => '九州・沖縄', 'display_order' => 45],
            ['code' => '46', 'code_en' => 'kagoshima', 'name' => '鹿児島県', 'region' => '九州・沖縄', 'display_order' => 46],
            ['code' => '47', 'code_en' => 'okinawa', 'name' => '沖縄県', 'region' => '九州・沖縄', 'display_order' => 47],
        ];

        foreach ($prefectures as $prefecture) {
            Prefecture::updateOrCreate(
                ['code' => $prefecture['code']],
                $prefecture
            );
        }
    }
}