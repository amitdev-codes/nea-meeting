<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute स्वीकृत हुनुपर्छ।',
    'accepted_if' => ':attribute :other :value हुँदा स्वीकृत हुनुपर्छ।',
    'active_url' => ':attribute मान्य URL होइन।',
    'after' => ':attribute :date पछिको मिति हुनुपर्छ।',
    'after_or_equal' => ':attribute :date भन्दा वा सो बराबरको मिति हुनुपर्छ।',
    'alpha' => ':attribute केवल अक्षर मात्र समावेश गर्न सकिन्छ।',
    'alpha_dash' => ':attribute केवल अक्षर, अंक, हाइफन, र अन्डरस्कोर समावेश गर्न सकिन्छ।',
    'alpha_num' => ':attribute केवल अक्षर र अंक समावेश गर्न सकिन्छ।',
    'array' => ':attribute एक एरे हुनुपर्छ।',
    'before' => ':attribute :date भन्दा पहिलेको मिति हुनुपर्छ।',
    'before_or_equal' => ':attribute :date भन्दा पहिलेको वा सो बराबरको मिति हुनुपर्छ।',
    'between' => [
        'numeric' => ':attribute :min र :max बीच हुनुपर्छ।',
        'file' => ':attribute :min र :max किलोग्राम बीच हुनुपर्छ।',
        'string' => ':attribute :min र :max अक्षर बीच हुनुपर्छ।',
        'array' => ':attribute :min र :max वस्तुहरूको बीचमा हुनुपर्छ।',
    ],
    'boolean' => ':attribute सत्य वा झूठ हुनुपर्छ।',
    'confirmed' => ':attribute पुष्टि मेल खाँदैन।',
    'date' => ':attribute मान्य मिति होइन।',
    'date_equals' => ':attribute :date सँग समान मिति हुनुपर्छ।',
    'date_format' => ':attribute फर्म्याट :format सँग मेल खाँदैन।',
    'different' => ':attribute र :other फरक हुनुपर्छ।',
    'digits' => ':attribute :digits अंक हुनुपर्छ।',
    'digits_between' => ':attribute :min र :max अंक बीच हुनुपर्छ।',
    'dimensions' => ':attribute को छवि आयाम मान्य छैन।',
    'distinct' => ':attribute मा दोहोर्याइएका मानहरू छन्।',
    'email' => ':attribute मान्य इमेल ठेगाना हुनुपर्छ।',
    'ends_with' => ':attribute निम्नमध्ये एकमा समाप्त हुनुपर्छ: :values।',
    'exists' => ':attribute चयन गरिएको मान्य छैन।',
    'file' => ':attribute एक फाइल हुनुपर्छ।',
    'filled' => ':attribute मा मान हुनु पर्छ।',
    'gt' => [
        'numeric' => ':attribute :value भन्दा ठूलो हुनुपर्छ।',
        'file' => ':attribute :value किलोग्राम भन्दा ठूलो हुनुपर्छ।',
        'string' => ':attribute :value अक्षर भन्दा ठूलो हुनुपर्छ।',
        'array' => ':attribute मा :value भन्दा बढी वस्तुहरू हुनुपर्छ।',
    ],
    'gte' => [
        'numeric' => ':attribute :value भन्दा ठूलो वा सो बराबर हुनुपर्छ।',
        'file' => ':attribute :value किलोग्राम भन्दा ठूलो वा सो बराबर हुनुपर्छ।',
        'string' => ':attribute :value अक्षर भन्दा ठूलो वा सो बराबर हुनुपर्छ।',
        'array' => ':attribute मा :value वस्तुहरू वा सो भन्दा बढी हुनुपर्छ।',
    ],
    'image' => ':attribute एक छवि हुनुपर्छ।',
    'in' => ':attribute चयन गरिएको मान्य छैन।',
    'in_array' => ':attribute :other मा छैन।',
    'integer' => ':attribute एक पूर्णांक हुनुपर्छ।',
    'ip' => ':attribute मान्य IP ठेगाना हुनुपर्छ।',
    'ipv4' => ':attribute मान्य IPv4 ठेगाना हुनुपर्छ।',
    'ipv6' => ':attribute मान्य IPv6 ठेगाना हुनुपर्छ।',
    'json' => ':attribute मान्य JSON स्ट्रिङ हुनुपर्छ।',
    'lt' => [
        'numeric' => ':attribute :value भन्दा कम हुनुपर्छ।',
        'file' => ':attribute :value किलोग्राम भन्दा कम हुनुपर्छ।',
        'string' => ':attribute :value अक्षर भन्दा कम हुनुपर्छ।',
        'array' => ':attribute मा :value वस्तुहरू भन्दा कम हुनुपर्छ।',
    ],
    'lte' => [
        'numeric' => ':attribute :value भन्दा कम वा सो बराबर हुनुपर्छ।',
        'file' => ':attribute :value किलोग्राम भन्दा कम वा सो बराबर हुनुपर्छ।',
        'string' => ':attribute :value अक्षर भन्दा कम वा सो बराबर हुनुपर्छ।',
        'array' => ':attribute :value वस्तुहरू भन्दा बढी हुनु हुँदैन।',
    ],
    'max' => [
        'numeric' => ':attribute :max भन्दा बढी हुनु हुँदैन।',
        'file' => ':attribute :max किलोग्राम भन्दा बढी हुनु हुँदैन।',
        'string' => ':attribute :max अक्षर भन्दा बढी हुनु हुँदैन।',
        'array' => ':attribute :max वस्तुहरू भन्दा बढी हुनु हुँदैन।',
    ],
    'mimes' => ':attribute निम्न प्रकारको फाइल हुनुपर्छ: :values।',
    'mimetypes' => ':attribute निम्न प्रकारको फाइल हुनुपर्छ: :values।',
    'min' => [
        'numeric' => ':attribute कम्तिमा :min हुनुपर्छ।',
        'file' => ':attribute कम्तिमा :min किलोग्राम हुनुपर्छ।',
        'string' => ':attribute कम्तिमा :min अक्षर हुनुपर्छ।',
        'array' => ':attribute कम्तिमा :min वस्तुहरू हुनुपर्छ।',
    ],
    'multiple_of' => ':attribute :value को गुणनफल हुनुपर्छ।',
    'not_in' => ':attribute चयन गरिएको मान्य छैन।',
    'not_regex' => ':attribute को फर्म्याट मान्य छैन।',
    'numeric' => ':attribute एक संख्या हुनुपर्छ।',
    'password' => 'पासवर्ड गलत छ।',
    'present' => ':attribute उपस्थित हुनुपर्छ।',
    'regex' => ':attribute को फर्म्याट मान्य छैन।',
    'required' => ':attribute भर्नु पर्छ।',
    'required_if' => ':attribute :other :value हुँदा भर्नु पर्छ।',
    'required_unless' => ':attribute :values मा :other नहुँदा भर्नु पर्छ।',
    'required_with' => ':attribute :values हुँदा भर्नु पर्छ।',
    'required_with_all' => ':attribute :values हुँदा भर्नु पर्छ।',
    'required_without' => ':attribute :values नहुँदा भर्नु पर्छ।',
    'required_without_all' => ':attribute कुनै पनि :values नहुँदा भर्नु पर्छ।',
    'same' => ':attribute र :other मिल्नुपर्छ।',
    'size' => [
        'numeric' => ':attribute को आकार :size हुनुपर्छ।',
        'file' => ':attribute को आकार :size किलोग्राम हुनुपर्छ।',
        'string' => ':attribute को आकार :size अक्षर हुनुपर्छ।',
        'array' => ':attribute मा :size वस्तुहरू हुनुपर्छ।',
    ],
    'starts_with' => ':attribute निम्नमध्ये एकले सुरू गर्नुपर्छ: :values।',
    'string' => ':attribute एक स्ट्रिङ हुनुपर्छ।',
    'timezone' => ':attribute मान्य समय क्षेत्र हुनुपर्छ।',
    'unique' => ':attribute पहिले नै अवस्थित छ।',
    'uploaded' => ':attribute अपलोड गर्न असफल।',
    'url' => ':attribute को फर्म्याट मान्य छैन।',
    'uuid' => ':attribute मान्य UUID हुनुपर्छ.',

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'कस्टम-सन्देश',
        ],
    ],

    'attributes' => [],

];
