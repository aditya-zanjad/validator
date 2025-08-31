<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Managers\Input;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use AdityaZanjad\Validator\Rules\TypeString as TypeStr;

use function AdityaZanjad\Validator\Presets\validate;

#[CoversClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(TypeStr::class)]
#[CoversFunction('\AdityaZanjad\Validator\Presets\validate')]
final class StringValidationRuleTest extends TestCase
{
    /**
     * Assert that the validator succeeds when the given fields are valid.
     *
     * @return void
     */
    public function testAssertionsPass(): void
    {
        $validator = validate([
            'english'   =>  '1234! Get on the dance floor!',
            'hindi'     =>  "हो। गए, उनका एक समय में बड़ा नाम था। पूरे देश में तालाब बनते थे बनाने वाले भी पूरे देश में थे। कहीं यह विद्या जाति के विद्यालय | सिखाई जाती थी तो कहीं यह जात से हट कर एक विशेष पांत भी जाती थी। बनाने वाले लोग कहीं एक जगह बसे मिलते थे तो कहीं -घूम कर इस काम को करते थे। I 국 घम गजधर एक सुन्दर शब्द है, तालाब बनाने वालों को आदर के साथ याद करने के लिए। राजस्थान के कुछ भागों में यह शब्द आज भी बाकी है। गजधर यानी जो गज को धारण करता है। और गज वही जो नापने के काम आता है। लेकिन फिर भी समाज ने इन्हें तीन हाथ की लोहे की छड़ लेकर घूमने वाला मिस्त्री नहीं माना। गजधर जो समाज को गहराई को नाप ले - उसे ऐसा दर्जा दिया गया है। गजधर वास्तुकार थे। गांव-समाज हो या नगर-समाज - उसके नव निर्माण की, रख-रखाव की ज़िम्मेदारी गजधर निभाते थे। नगर नियोजन से लेकर छोटे से छोटे निर्माण के काम गजधर के कधों पर टिके थे। वे योजना बनाते थे, कुल काम की लागत निकालते थे, काम में लगने वाली सारी सामग्री जुटाते थे और इस सबके बदले वे अपने जजमान से ऐसा कुछ नहीं मांग बैठते थे, जो वे दे न पाएं। लोग भी ऐसे थे कि उनसे जो कुछ बनता, वे गजधर को भेंट कर देते। काम पूरा होने पर पारिश्रमिक के अलावा गजधर को सम्मान ' भी मिलता था। सरोपा भेंट करना अब शायद सिर्फ सिख परंपरा में ही बचा समाज की गहराई नापते रहे हैं गुणाधर",
            'japanese'  =>  '転ツ築転でぱクス制中れぼ併題だち東未城ク物九ユウ際同ヒヲク蔓一ラスエ年日ホヘク円果進ドげルょ行使少描際ぜれゅ。飯くっぶこ堀住き神挙びく文半レム後仲社処ラヤ員方タワ機細だくぞが険味ニミオ能皇じごフく式渡キエク予仁ッわこ。解へやど緊永新53漢画時4院ぼたるわ議広トき断価選イレ摘福ゆろぶ期公瑞ナメネリ上並よい'
        ], [
            'english'   =>  'string',
            'hindi'     =>  'string',
            'japanese'  =>  'string'
        ]);

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
    public function testAssertionsFail(): void
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
}
