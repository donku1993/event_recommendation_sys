<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    protected $real_group_data = [
        ['澳門中華新青年協會', 'mymacau@macau.ctm.net', '若翰亞美打街7號友聯大廈地下I', '28358963', '澳門中華新青年協會（簡稱新青協）創立於2003年1月5日，是一個非牟利的青年組織，以清新、創意、求變為訴求，團結澳門廣大青年服務社會為宗旨，通過 一系列有針對性、創新性及啟發性的活動，促使新青協成為青年朋友相互學習、互勉共進的「課堂」﹔成為青年朋友發揮學識、展露才華的「舞臺」﹔成為青年朋友 溝通政府、服務社會的「橋樑」﹔成為青年朋友聯絡感情、反映心聲的「樂園」。'],
        ['澳門社區義工聯合總會', 'ugamcv@gmail.com', '澳門提督馬路163-165號合和工業大廈2樓A座及B座', '28252791', '本會為非牟利之社會服務團體，宗旨是堅持愛國愛澳，團結社區義工，發展義工服務，開展社區工作，推進和諧社區建設。'],
        ['澳門義務青年會', 'ajvmacau@yahoo.com.hk', '澳門風順堂上街2E新濤閣地下', '28973136', '"期望通過「認清賭博大使」培訓課程以及推行「認清賭博推廣日」，將""負責任博彩""概念散播到社區每一個角落，以及將博彩行為可引致的危害減至社會可接受的水平；並透過一系列推廣及考察活動，引起社會關注，達到正面宣傳""負責任博彩""概念，起著社區防治作用，活動費用全免。"'],
        ['澳門新一代協進會', 'newgen@macau.ctm.net', '澳門鏡湖馬路50號德輝大廈9樓B座', '28366359', '澳門新一代協進會是由兒科專科醫生方文達倡議創辦，由一班熱心的專業人士組成，成立於1993年5月，並於澳門政府立案為非牟利團體。'],
        ['澳門青年志願者協會', 'myva2008@hotmail.com', '澳門澳門慕拉士大馬路218號澳門日報大廈12樓', '28356482', '澳門青年志願者協會自2002年創立以來，一直秉承並傳揚“奉獻、友愛、互助、進步”的志願精神，並以團結廣大愛國愛澳青年參與各項志願服務為宗旨，堅守自願參與、助人自助、持之以恆的原則，推動著澳門志願者事業的持續發展，同時亦有助於澳門和諧社會的建設、發展和進步。'],
        ['澳門社區青年義工發展協會', 'mcyvda05@yahoo.com.hk', '澳門澳門高士德大馬路50號皇宮大廈二樓D座', '28581244', '該會開展及協助進行的青年活動及義工服務，範疇包括康體競技比賽、放眼祖國系列活動、社區主題推廣活動、專才義工培訓、聯繫會員活動、社會義工服務、以及多項大型綜合系列活動等。該會主辦品牌系列活動有『生命與希望---人本價值探索系列活動』、『關懷青少年成長計劃』、『義&您一生系列活動』、『WALKING TOGETHER 亦師亦友系列活動』、『飛出框架義工領袖培訓計劃』等。'],
        ['旭日青年協會', 'solsubida2013@gmail.com', '澳門提督馬路168號慕拉士大廈10樓G座', '63591918', '青年社團，活力十足'],
        ['青年發展協進社', 'adjadj210@gmail.com', '澳門新口岸巴黎街南岸花園214號閣樓AQ舖', '66339335', '本社期望提供一個平台，透過帶領會員組織或參加活動，使青年朝正向價值觀發展和成長，凝聚青年正能量；發揚愛國愛澳、互助互愛、服務社區及貢獻社會；並同時鼓勵青年上進，自我增值向上流動，積極推動青年建立正確的人生觀。'],
        ['澳門醫護志願者協會', 'mamv2006@gmail.com', '澳門天神巷21號A昌勝樓C座2樓', '28356482', '澳門醫護志願者協會（簡稱醫護志協）成立於2006年11月5日，宗旨是團結愛國、愛澳從事醫護的工作人員，以醫護志願者為中心，通過籌辦醫療活動、參與社會公益事務：一方面為本澳提供醫護專業服務、回饋社會、提高公民意識、增強醫護人員為社會服務的責任感，另一方面是提升其志願精神及壯大團隊的綜合能力和加強個人在團隊裡的作用。'],
        ['澳門工業福音團契', 'ief@macau.ctm.net', '澳門黑沙環南華新村第一座地下A舖', '28453151' ,'1995年，香港工業福音團契向澳門政府註冊，成立澳門工業福音團契，並在黑沙環新填海區設立工福中心，向澳門基層勞工及其家人提供全人關懷服侍，實踐基督的愛，2001年8月獲澳門特區政府賦予公益法人資格，2004年4月為回應社會需要，開辦問題賭徒復康服務。'],
        ['歐漢琛慈善會', 'saagha@gmail.com', '澳門桔仔街八十七號二樓AB座', '28572929', '「免費戒煙門診服務」由歐漢琛慈善會於2005年2月開設，並得到社會工作局的財政及技術支援，為本澳市民提供醫護免費評估、戒煙計劃，藥物及心理輔導等服務。自2012年1月1日，「新控煙法」正式實施後，該門診加大各項服務，包括向外推廣戒煙宣傳活動，到社區、學校進行講解，亦設置流動宣傳車走訪各區，以進一步宣傳戒煙訊息。'],
        ['澳門義務工作者協會', 'info@avsm.org.mo', '澳門祐漢街市三樓,祐漢社區中心9-4室', '28474058', '本會成立於一九八六年，其成立與當時的澳門社會工作司（現時的社會工作局）有密切關係。一九八六年初，當時的社會工作司開辦了一個為期半年的『義工領袖才能訓練計劃』，培訓24名來自本澳十四個不同服務機構的資深義工，期望他們在推動義務工作發展方面，發揮力量。'],
        ['澳門學習型組織學會', 'macauceplv@gmail.com', '氹仔巴波沙總督街52號錦程閣2樓G座', '66262491', '推動社區文化建設，開辦社區學習活動，如親子活動，長者活動等等社區活動'],
        ['澳門全人教育促進會', 'info@wpedu.org.mo', '澳門北京街 230-246金融中心 7樓 E座', '28355911', '「澳門全人教育促進會」為非牟利志願機構，於2008年1月正式接力香港「全人教育基金」過去五年在澳門的工作，通過提供心理學為本的「ICAN全人教育」，提升成人和兒童的「心理素質」，從而締造更成功、進步和快樂的人生與社會'],
        ['澳門圖書館暨資訊管理協會', 'macau_mlima@yahoo.com.hk', 'P. O. Box 1422, Macau', '66976977', '由成人教育中心贊助、澳門圖書館暨資訊管理協會舉辦的“第五屆全澳圖書館義工及工讀生常識問答比賽”於四月二十六日完滿結束，各參賽隊伍表現優秀，潛能盡現。是次比賽希望透過緊張刺激的問答比賽形式，來提升義工及工讀生對圖書館的興趣；藉此加深其對圖書館的認識，讓他們進一步了解圖書館的運作，培養他們對本澳圖書館的關心以及關注圖書館事業的發展，共同為澳門圖書館事業創建美好的明天。']
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // admin user
        factory(App\Models\User::class)->states('administrator')->create([
                'name' => 'admin',
                'email' => 'admin@admin.com',
            ]);

        // normal user
        factory(App\Models\User::class, 50)->create();

        // group with true data (is group)
        foreach ($this->real_group_data as $key => $value) {
            $group_manager = factory(App\Models\User::class)->states('group_manager')->create();
            $group = factory(App\Models\Group::class)->states('group')->create([
                'user_id' => $group_manager->id,
                'name' => $value[0],
                'email' => $value[1],
                'address' => $value[2],
                'phone' => $value[3],
                'introduction' => $value[4],
                'principal_name' => $group_manager->name
            ]);

            $total_event = rand(1, 5);
            factory(App\Models\Event::class, $total_event)->create()
                ->each(function($e) use ($group) {
                    DB::table('groups_events_relation')->insert([
                            'group_id' => $group->id,
                            'event_id' => $e->id,
                            'main' => 1,
                            'created_at' => $e->created_at
                        ]);
                });
        }

        // add co_organizer relation
        $events = DB::table('events')->get();
        foreach ($events as $event) {
            if (rand(0, 1)) {
                for ($i=0; $i < rand(0, 3); $i++) { 
                    $group = DB::table('groups')->inRandomOrder()->first();
                    try {
                        DB::table('groups_events_relation')->insert([
                                'group_id' => $group->id,
                                'event_id' => $event->id,
                                'main' => 0,
                                'created_at' => $event->created_at
                            ]);
                    } catch (Exception $e) {
                        
                    }
                }
            }
        }

        // group with fake data (new group form)
        factory(App\Models\User::class, 5)->states('group_manager')->create()
            ->each(function($u) {
                factory(App\Models\Group::class)->create([
                    'user_id' => $u->id,
                    'name' => $u->name . '\'s Group',
                    'principal_name' => $u->name,
                ]);
            });

        // group with fake data (waiting group form)
        factory(App\Models\User::class, 5)->states('group_manager')->create()
            ->each(function($u) {
                factory(App\Models\Group::class)->states('waiting_group_form')->create([
                    'user_id' => $u->id,
                    'name' => $u->name . '\'s Group',
                    'principal_name' => $u->name,
                ]);
            });

        // group with fake data (rejected group form)
        factory(App\Models\User::class, 5)->states('group_manager')->create()
            ->each(function($u) {
                factory(App\Models\Group::class)->states('rejected_group_form')->create([
                    'user_id' => $u->id,
                    'name' => $u->name . '\'s Group',
                    'principal_name' => $u->name,
                ]);
            });
    }
}
