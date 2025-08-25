<?php

$data = [
    'projects' => [
        123 => null,
        'tasks' => null,
        'hello world!' => [
            '1234! Get on the dance floor!'
        ]
    ],

    'employees' => [
        //
    ]
];

$rules = [
    // '*.*'           =>  'required|array|min:1'
    // 'projects.*'    =>  'required|string|min:1',
    'projects.*.*'      =>  'required|array|min:1',
    // 'projects.*.*.*'    =>  'required|string|min:1'
];


function resolvePaths(array $data, string $path, array $rules)
{
    $ref    =   &$data;
    $params =   explode('.', $path);

    $result = [];

    foreach ($params as $param) {
        if ($param === '*') {
            if (empty($ref)) {
                foreach ($result as $key => $v) {
                    $result["{$key}.0"] = null;
                    unset($result[$key]);
                }

                continue;
            }

            foreach (array_keys($ref) as $keyToAdd) {
                $newKey = !array_key_exists($keyToAdd, $ref) ? "0" : "{$keyToAdd}";

                foreach ($result as $key => $value) {
                    $result["{$key}.{$newKey}"] = $value;
                    // unset($result[$key]);
                }
            };

            

            // foreach ($result as $key => $v) {
            //     foreach ($keysToAdd as $keyToAdd) {
            //         $result["{$key}.{$keyToAdd}"] = null;
            //     }

            //     unset($result[$key]);
            // }

            continue;
        }

        if (empty($result)) {
            $result[$param] =   null;
            $ref            =   &$ref[$param];

            continue;
        }

        foreach ($result as $key => $value) {
            $result["{$key}.{$param}"]  =   null;
            $ref                        =   &$ref[$param];
            
            unset($result[$key]);
        }

        continue;
    }

    return array_fill_keys(array_keys($result), $rules[$path]);
}

var_dump(resolvePaths($data, 'projects.*.*', $rules));