<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Fluents\Input;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use AdityaZanjad\Validator\Rules\TypeString as TypeStr;

use function AdityaZanjad\Validator\validate;

#[CoversClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(TypeStr::class)]
#[CoversFunction('\AdityaZanjad\Validator\validate')]
final class StringValidationRuleTest extends TestCase
{
    /**
     * Assert that the validator succeeds when the given fields are valid.
     *
     * @return void
     */
    public function testStringValidationRulePasses(): void
    {
        $validator = validate([
            'english'   =>  '1234! Get on the dance floor!',
            'hindi'     =>  "हो। गए, उनका एक समय में बड़ा नाम था। पूरे देश में तालाब बनते थे बनाने वाले भी पूरे देश में थे। कहीं यह विद्या जाति के विद्यालय | सिखाई जाती थी तो कहीं यह जात से हट कर एक विशेष पांत भी जाती थी। बनाने वाले लोग कहीं एक जगह बसे मिलते थे तो कहीं -घूम कर इस काम को करते थे। I 국 घम गजधर एक सुन्दर शब्द है, तालाब बनाने वालों को आदर के साथ याद करने के लिए। राजस्थान के कुछ भागों में यह शब्द आज भी बाकी है। गजधर यानी जो गज को धारण करता है। और गज वही जो नापने के काम आता है। लेकिन फिर भी समाज ने इन्हें तीन हाथ की लोहे की छड़ लेकर घूमने वाला मिस्त्री नहीं माना। गजधर जो समाज को गहराई को नाप ले - उसे ऐसा दर्जा दिया गया है। गजधर वास्तुकार थे। गांव-समाज हो या नगर-समाज - उसके नव निर्माण की, रख-रखाव की ज़िम्मेदारी गजधर निभाते थे। नगर नियोजन से लेकर छोटे से छोटे निर्माण के काम गजधर के कधों पर टिके थे। वे योजना बनाते थे, कुल काम की लागत निकालते थे, काम में लगने वाली सारी सामग्री जुटाते थे और इस सबके बदले वे अपने जजमान से ऐसा कुछ नहीं मांग बैठते थे, जो वे दे न पाएं। लोग भी ऐसे थे कि उनसे जो कुछ बनता, वे गजधर को भेंट कर देते। काम पूरा होने पर पारिश्रमिक के अलावा गजधर को सम्मान ' भी मिलता था। सरोपा भेंट करना अब शायद सिर्फ सिख परंपरा में ही बचा समाज की गहराई नापते रहे हैं गुणाधर",
            'japanese'  =>  '転ツ築転でぱクス制中れぼ併題だち東未城ク物九ユウ際同ヒヲク蔓一ラスエ年日ホヘク円果進ドげルょ行使少描際ぜれゅ。飯くっぶこ堀住き神挙びく文半レム後仲社処ラヤ員方タワ機細だくぞが険味ニミオ能皇じごフく式渡キエク予仁ッわこ。解へやど緊永新53漢画時4院ぼたるわ議広トき断価選イレ摘福ゆろぶ期公瑞ナメネリ上並よい。

載かぴ貯稿ほン派務ろるふ官異道びしよる調機授ケ使財こひご会子げーべわ相人縦つがぜ新写わんそよ後第棋よぴトゃ遠野ヘ暮息曲燃で。見ウ掲話ツイユ気副53亮23者フヒワロ功奇ぼゃた斎致規ノア強界ホソ上連ト西鉱ど枝知ヨ富労いじこ懸調や客周私東さ。36京ネエワヤ面百イルシツ形月サソ者音結ふフぜッ護洋ラウホ埼鷲刊るつ時最チタ監東い検流じ占族チ値4筋ヲキ能反念ぼゅ。

処ミ女再なたっス通出ユセサソ時輸うねど球読き議趣シエユヒ禁土だラよン息原請ヱ視万咲ウヘヨホ暮体すラゃ短万オマ分思し浴6詳すざゃへ。増な全教語行よ家37着だぞな躍周ごろトよ来予ぶ恵街おやめ力性半ロヘサ信間げトゃ突与ち。満共いひち口過などぐ密論物ノソネム不際ばめ哲歩シエヌ問象ろべるど欧然間ス書座れがう派考マソ来8請モ極64思モヱエコ活採ワ史行天セ証傘忌昆瑞ぐまかも。

投ほクろぽ抑6治をスおぱ装慣テヒヲヘ半6書レメリタ監際レコア上6品ぶぞは歩何新ン絡海スイミエ九表スけ託問トユナ治魚ヲホキ欺求のやぼ前情シイ奏分ろ接進案物めトょは。位ぎ静3庭局松ルびつ案都評マテカ消順アカ子楽マイナシ豊写もぼ急持のん訪去ぼむちレ政器ド構準シイホヒ月阜め化訴直チエマホ極事でひっイ戦賀せ。

決暖モ離間ぞ提49延シユム新原東豊マヲ週齢キツユニ点藤方ク守明ミイツ故要づめ転5江のをるげ権毎覧ヒ上名ぽくほら約住るーと満岩復児導ずわス。供ルアメム号健銀おほぴつ捨本ーがこぎ打然ナリチ護詳レセ掲応ス山客報モワ察応オ投設ふだしゃ者生びざ岡次リぞこ不必ホ留児らび振8禁ヨメ都朝紀ね。

展ヤ一端ニ線3族ロクネモ域著たをど図雨セテ切新やぞー課介きぶが地含和能ルえ広馬とげはあ属文りゆひ注語ヲワツハ郵1画あかな会情井数っン下脱意ばるほも。義ヨロルヱ住機れゃへ本評そ演場スヨコア涙軽スみ載口ぞ千漏コキ包1減ア声乗こ政正ミフケ度新ヲヤネ掲利映ル伝住37織ルみょだ環復ムヌレ変仁借わでッど。

済ネセ福昨にむひ去読ユレスラ掲妻えそラや制疑ヤク夕外コメオ井58権ぶかまつ属懇ルマ納法フやあく人78素ヘフ阪景相行は後碁アヱケヘ阿4練ま縄反念覚すリしか。周取ぴかあこ応上サスヒ供従みさづな提真もいょほ族小フ部指露ロウエ氏景必ょ提補ウエ政要ういす要能立マニケナ気前ゃ売段でらぶゆ録戦ツム常8挙海鳥歳ょラにむ。

著ぱがか文四ノ以嘆るごほむ潟獲ルょうな豊士セコリタ断新きや込邦へぽょル捕広政ぱしそ開78車だてぐ狙関雑子心りへぐべ駐冷勧封ろスつわ。凸レス今終東ウレハテ一校19住ロ継報れと塚71国ソ外空オクウ経営ざトく湾去クそぞ身疑遅ヌチフケ制場孝ラかルあ。花モキヨウ市無件ロア全芸へよリ伴学でぼぞざ注合チトウシ費境ルタカ太府ろも必画載ンぼゃ四警フ店面ヤメラヌ績済腕異っぽ。

試きスと場伊を作合じてぐフ芸元スせつお作写シヒキ強周つがむま暮来でう作国いン感需ソヌイ模表しクぐ一61序削本92健ノソロタ委殺スルエ大味第ぶめきる岩限ょはよ。合ン本読え基出ホ雲棋発む倉行郎レょもじ百動タ公54内カヒキ宮7著ぎに壮制クエテ自度ヱ告置リハ対甲避欺茶へとね。検い歌7他えス了立野ネヌノ金連ユ万得読ロ稿編をイえ真場ナカ読並ケヨチ県著陸ぐう安詳名小民フげべ。

下が市雪ミチハ伝具ム表員ずンびも進集アヘ図博や線周紀視9貴ざ詰防い講伎先つし。秒チ無95算むわ椅駒ヌラワム初相路況ゅぐぴと署94士モノフ費7民シヱケ前書コニク語著ぎ所準督ンとま面著ひらんへ校質マワ託過ぱ人無伴飯ぎト。辞やよイ転省トる画戦マタツ題7木た妊務マニ球多とつ愛辞業クリ正社ソ多郊リ巳帝ド供特ハニラヱ国心ヤカル後券戻杯緊迫ぶトりわ。'
        ], [
            'english'   =>  'string',
            'hindi'     =>  'string',
            'japanese'  =>  'string'
        ]);

        $validator->validate();
        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertNull($validator->errors()->firstOf('english'));
        $this->assertNull($validator->errors()->firstOf('hindi'));
        $this->assertNull($validator->errors()->firstOf('japanese'));
    }

    /**
     * Assert that the validator fails when the given string is an invalid string.
     *
     * @return void
     */
    public function testStringValidationRuleFails(): void
    {
        $validator = validate([
            'abc'       =>  ['this is a string.'],
            'xyz'       =>  ['this is a string!' => 'this is a string !'],
            'array'     =>  [1, 2, 3, 4, 5, 6],
            'int'       =>  12345682385,
            'float'     =>  57832572.23478235,
            'object'    =>  (object) ['abc' => 1234]
        ], [
            'abc'       =>  'string',
            'xyz'       =>  'string',
            'array'     =>  'string',
            'int'       =>  'string',
            'float'     =>  'string',
            'object'    =>  'string'
        ]);

        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->all());
        $this->assertNotEmpty($validator->errors()->firstOf('abc'));
        $this->assertNotEmpty($validator->errors()->firstOf('xyz'));
        $this->assertNotEmpty($validator->errors()->firstOf('array'));
        $this->assertNotEmpty($validator->errors()->firstOf('int'));
        $this->assertNotEmpty($validator->errors()->firstOf('float'));
        $this->assertNotEmpty($validator->errors()->firstOf('object'));

    }

    /**
     * Assert that the validation rule is skipped when given field is missing or is set to null.
     *
     * @return void
     */
    public function testStringValidationRuleIsSkipped()
    {
        $validator = validate([
            'xyz' => null
        ], [
            'abc'   =>  'string',
            'xyz'   =>  'string'
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertNull($validator->errors()->firstOf('abc'));
        $this->assertNull($validator->errors()->firstOf('xyz'));
    }

    /**
     * Assert that the validation rule is skipped when given field is missing or is set to null.
     *
     * @return void
     */
    public function testStringValidationRuleIsAppliedToRequiredFields()
    {
        $validator = validate([
            'xyz' => null
        ], [
            'abc' => 'string|required',
            'xyz' => 'string|required'
        ]);

        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->all());
        $this->assertNotNull($validator->errors()->firstOf('abc'));
        $this->assertNotNull($validator->errors()->firstOf('xyz'));
    }
}
