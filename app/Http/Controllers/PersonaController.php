<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use Illuminate\Http\Request;

class PersonaController extends Controller
{
    /**
     * ペルソナ一覧を表示
     */
    public function index()
    {
        $personas = Persona::active()
            ->orderBy('difficulty_level')
            ->orderBy('name')
            ->get();

        // 難易度別にグループ化
        $groupedPersonas = $personas->groupBy('difficulty_level');

        return view('personas.index', compact('personas', 'groupedPersonas'));
    }

    /**
     * 特定のペルソナの詳細を表示
     */
    public function show(Persona $persona)
    {
        // アクティブなペルソナのみ表示
        if (!$persona->is_active) {
            abort(404);
        }

        return view('personas.show', compact('persona'));
    }

    /**
     * ペルソナサンプル事例を表示
     */
    public function samples()
    {
        // サンプルペルソナデータを配列で定義
        $samplePersonas = [
            [
                'name' => '山崎 玲奈',
                'age' => '22歳',
                'background' => '四年制大学在学中（国際教養学部4年生）',
                'family' => '父（64歳）、母（52歳）、本人は大学の近くで一人暮らし',
                'consultation_month' => '6～7月',
                'consultation_content' => '先日総合商社に勤める従姉と会う機会があり、仕事のことなど色々な話を聞くことができた。彼女はとても生き生きとしていて自分も彼女のように総合商社に就職したいと思うようになった。現在、3社から内定をもらっているが、総合商社ではないし、比較すると何だか見劣りするような気持ちになってしまい、どの会社に決めれば良いのかわからなくなった。就職活動をやり直した方が良いのだろうか。悩んでいるため、相談したい。'
            ],
            [
                'name' => '野口 大輔',
                'age' => '35歳',
                'background' => '四年制大学（文学部国文学科）卒業後、出版社に就職し、13年目',
                'family' => '独身一人暮らし',
                'consultation_month' => '',
                'consultation_content' => '現在の会社に入社してからずっと、編集職としてやりがいを持って働いていたが、今年の4月に異動となり、営業中心の仕事内容になった。元々営業には苦手意識があり、モチベーションが低下している。そんな折、交際相手から転勤するかもしれないとの話があり、今後の働き方について色々と考えるようになったが、どうしたら良いかわからなくなり、相談したい。'
            ],
            [
                'name' => '加藤 彩',
                'age' => '44歳',
                'background' => '四年制大学（国際学部）卒業後、外食産業に就職し、22年目',
                'family' => '夫（47歳）、長男（8歳）、長女（5歳）',
                'consultation_month' => '',
                'consultation_content' => '子どもたちの育児のため、今は短時間勤務をしている。会社は働く環境づくりに積極的で、小学校卒業まで短時間勤務制度の利用が可能である。長女の産休・育休明けの復帰時には、長く制度を利用して二人の子どもの育児と仕事を両立させたいと考えていたが、今後その選択で良いのかと迷うようになったため、相談したい。'
            ],
            [
                'name' => '中谷 則幸',
                'age' => '62歳',
                'background' => '二年制ビジネス専門学校卒業後、自動車ディーラーに入社。販売店営業職、販売店店長、営業本部長を経て60歳で定年退職。再雇用制度は活用せず、現在は無職',
                'family' => '妻（60歳）、長男（30歳）、長女（28歳）',
                'consultation_month' => '',
                'consultation_content' => '多忙な日々から解放され、やり切ったという達成感もあったため、これからはゆっくりしたいと思い、定年退職後はのんびりと過ごしていた。しかし数か月前に、小・中学時代の同級生達と久しぶりに会い、それぞれの道や夢を見つけ、生き生きと生活しているのを見聞きして、何もしていない自分に焦りを感じた。その後再就職活動を始めたが、うまくいかずどうしたら良いかわからない。今になって再雇用を断ったことを後悔し始めている。'
            ],
            [
                'name' => '山辺 直美',
                'age' => '51歳',
                'background' => '四年制大学（文学部）卒業後、1回の転職を経て中堅食品製造業に就職し、23年目。現在、総務部経理課係長',
                'family' => '夫（63歳）、長男（19歳）',
                'consultation_month' => '',
                'consultation_content' => '就職氷河期世代で就職活動に苦労したため、仕事には常に真剣に向き合ってきた。最近、知人が急逝したり友人の癌による入院の話を聞き、今後の生き方について漠然とした不安を覚えるようになった。そんな矢先に上司から昇進の内示を受け、嬉しく思うと同時に、このまま仕事中心の生き方を続けて良いのか考えるようになり、昇進を受けるかどうかも含めてどうしたら良いか、相談したい。'
            ]
        ];

        return view('personas.samples', compact('samplePersonas'));
    }
}