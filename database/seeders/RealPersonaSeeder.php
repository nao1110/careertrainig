<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Persona;

class RealPersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 外部キー制約を一時的に無効にして既存データを削除
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Persona::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // キャリアコンサルタント練習用の実際のペルソナデータを作成
        $personas = [
            [
                'name' => '山崎 玲奈',
                'age' => 22,
                'gender' => 'female',
                'occupation' => '大学生（国際教養学部4年生）',
                'career_years' => 0,
                'background' => '四年制大学在学中（国際教養学部4年生）。家族：父（64歳）、母（52歳）、本人は大学の近くで一人暮らし。',
                'concern_category' => '就職活動・内定承諾',
                'specific_concern' => '先日総合商社に勤める従姉と会う機会があり、仕事のことなど色々な話を聞くことができた。彼女はとても生き生きとしていて自分も彼女のように総合商社に就職したいと思うようになった。現在、3社から内定をもらっているが、総合商社ではないし、比較すると何だか見劣りするような気持ちになってしまい、どの会社に決めれば良いのかわからなくなった。',
                'desired_outcome' => '就職活動をやり直すべきか、現在の内定先で良いのかの判断がしたい',
                'personality_traits' => '他人と比較しがち、従姉の影響を受けやすい、真面目で向上心がある',
                'communication_style' => '丁寧で礼儀正しいが、迷いを表現する傾向',
                'motivation_factors' => '将来への不安、周囲からの評価、安定志向',
                'opening_statement' => '実は就職のことで相談があります。内定はもらっているのですが、このまま決めて良いのか迷っています。',
                'key_points_to_reveal' => '従姉の総合商社での話、現在の内定先への不満、両親の期待',
                'emotional_responses' => '不安、焦り、迷い、時々前向きな気持ち',
                'resistance_points' => '現実的なアドバイスに対する抵抗、理想と現実のギャップ',
                'difficulty_level' => 'beginner',
                'learning_objectives' => '学生の就職相談、意思決定支援、現実と理想の整理',
                'is_active' => true,
                'usage_notes' => '相談月：6～7月。従姉の影響が大きいポイント。練習用ペルソナ。'
            ],
            [
                'name' => '野口 大輔',
                'age' => 35,
                'gender' => 'male',
                'occupation' => '出版社勤務（編集職→営業職）',
                'career_years' => 13,
                'background' => '四年制大学（文学部国文学科）卒業後、出版社に就職し、13年目。独身一人暮らし。',
                'concern_category' => '職場異動・キャリア転換',
                'specific_concern' => '現在の会社に入社してからずっと、編集職としてやりがいを持って働いていたが、今年の4月に異動となり、営業中心の仕事内容になった。元々営業には苦手意識があり、モチベーションが低下している。そんな折、交際相手から転勤するかもしれないとの話があり、今後の働き方について色々と考えるようになった。',
                'desired_outcome' => '今後のキャリアの方向性を決めたい',
                'personality_traits' => '内向的、完璧主義、責任感が強い、変化に対する不安',
                'communication_style' => '論理的だが感情を抑える傾向、深く考えすぎる',
                'motivation_factors' => 'やりがい重視、専門性への憧れ、安定した関係',
                'opening_statement' => '仕事のことで悩んでいます。異動になって、今までとは全く違う仕事をしているのですが...',
                'key_points_to_reveal' => '編集職への愛着、営業への苦手意識、交際相手の存在と転勤の可能性',
                'emotional_responses' => '困惑、不安、諦め、時々怒り',
                'resistance_points' => '営業職への偏見、変化への恐れ、プライベートの話への抵抗',
                'difficulty_level' => 'intermediate',
                'learning_objectives' => 'キャリア転換支援、感情の整理、現実的な選択肢の検討',
                'is_active' => true,
                'usage_notes' => 'プライベートな要素（交際相手）も含む複合的な相談。練習用ペルソナ。'
            ],
            [
                'name' => '加藤 彩',
                'age' => 44,
                'gender' => 'female',
                'occupation' => '外食産業勤務（短時間勤務）',
                'career_years' => 22,
                'background' => '四年制大学（国際学部）卒業後、外食産業に就職し、22年目。家族：夫（47歳）、長男（8歳）、長女（5歳）。',
                'concern_category' => 'ワークライフバランス・育児との両立',
                'specific_concern' => '子どもたちの育児のため、今は短時間勤務をしている。会社は働く環境づくりに積極的で、小学校卒業まで短時間勤務制度の利用が可能である。長女の産休・育休明けの復帰時には、長く制度を利用して二人の子どもの育児と仕事を両立させたいと考えていたが、今後その選択で良いのかと迷うようになった。',
                'desired_outcome' => '仕事と育児の最適なバランスを見つけたい',
                'personality_traits' => '責任感が強い、完璧主義、周囲への気遣い、自己犠牲的',
                'communication_style' => '控えめだが芯が強い、感情的になりやすい',
                'motivation_factors' => '家族の幸せ、社会貢献、自己実現',
                'opening_statement' => '仕事と育児の両立について相談したいことがあります。',
                'key_points_to_reveal' => '短時間勤務への罪悪感、キャリアへの不安、夫との役割分担',
                'emotional_responses' => '罪悪感、不安、時々涙ぐむ、強い決意',
                'resistance_points' => '家族優先の価値観への固執、周囲の評価への過度な気遣い',
                'difficulty_level' => 'intermediate',
                'learning_objectives' => 'ワークライフバランス、価値観の整理、現実的な選択肢',
                'is_active' => true,
                'usage_notes' => '母親としての葛藤、制度利用への罪悪感がポイント。練習用ペルソナ。'
            ],
            [
                'name' => '中谷 則幸',
                'age' => 62,
                'gender' => 'male',
                'occupation' => '無職（定年退職）',
                'career_years' => 40,
                'background' => '二年制ビジネス専門学校卒業後、自動車ディーラーに入社。販売店営業職、販売店店長、営業本部長を経て60歳で定年退職。再雇用制度は活用せず、現在は無職。家族：妻（60歳）、長男（30歳）、長女（28歳）。',
                'concern_category' => '定年後の生き方・再就職',
                'specific_concern' => '多忙な日々から解放され、やり切ったという達成感もあったため、これからはゆっくりしたいと思い、定年退職後はのんびりと過ごしていた。しかし数か月前に、小・中学時代の同級生達と久しぶりに会い、それぞれの道や夢を見つけ、生き生きと生活しているのを見聞きして、何もしていない自分に焦りを感じた。その後再就職活動を始めたが、うまくいかずどうしたら良いかわからない。',
                'desired_outcome' => '今後の人生設計を明確にしたい',
                'personality_traits' => 'プライド高い、経験豊富、頑固、でも実は不安',
                'communication_style' => '威厳があるが素直さもある、経験談を語りがち',
                'motivation_factors' => '社会的地位、経験の活用、家族への責任',
                'opening_statement' => 'まあ、年寄りの愚痴になってしまうかもしれないが...',
                'key_points_to_reveal' => '再雇用を断った後悔、同級生への劣等感、家族への思い',
                'emotional_responses' => '焦り、後悔、プライドと現実のギャップでの困惑',
                'resistance_points' => '年齢による制限の受け入れ、プライドの調整',
                'difficulty_level' => 'advanced',
                'learning_objectives' => 'シニア世代の相談、プライドと現実の調整、人生設計',
                'is_active' => true,
                'usage_notes' => '威厳と不安の両面、再雇用拒否の後悔がポイント。練習用ペルソナ。'
            ],
            [
                'name' => '山辺 直美',
                'age' => 51,
                'gender' => 'female',
                'occupation' => '食品製造業勤務（総務部経理課係長）',
                'career_years' => 23,
                'background' => '四年制大学（文学部）卒業後、1回の転職を経て中堅食品製造業に就職し、23年目。現在、総務部経理課係長。家族：夫（63歳）、長男（19歳）。',
                'concern_category' => '昇進・今後の生き方',
                'specific_concern' => '就職氷河期世代で就職活動に苦労したため、仕事には常に真剣に向き合ってきた。最近、知人が急逝したり友人の癌による入院の話を聞き、今後の生き方について漠然とした不安を覚えるようになった。そんな矢先に上司から昇進の内示を受け、嬉しく思うと同時に、このまま仕事中心の生き方を続けて良いのか考えるようになった。',
                'desired_outcome' => '昇進を受けるかどうかを含め、今後の生き方を決めたい',
                'personality_traits' => '真面目、責任感強い、完璧主義、内省的',
                'communication_style' => '丁寧で理路整然、でも感情的な面も見せる',
                'motivation_factors' => '社会的責任、家族の安定、自己実現',
                'opening_statement' => '昇進の話をいただいたのですが、素直に喜べない自分がいます。',
                'key_points_to_reveal' => '就職氷河期の経験、身近な人の死への直面、家族との時間',
                'emotional_responses' => '困惑、不安、時々涙、決意',
                'resistance_points' => '仕事中心の価値観からの脱却、変化への恐れ',
                'difficulty_level' => 'advanced',
                'learning_objectives' => 'ミドル世代の価値観転換、人生の意味の再考、意思決定支援',
                'is_active' => true,
                'usage_notes' => '就職氷河期世代の特徴、死への直面が転機となっている。練習用ペルソナ。'
            ]
        ];

        foreach ($personas as $personaData) {
            Persona::create($personaData);
        }
    }
}
