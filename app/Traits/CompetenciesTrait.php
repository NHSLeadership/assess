<?php
namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\Yaml\Yaml;

trait CompetenciesTrait
{
    public function createYml()
    {
        $options = [
            1 => 'Strongly disagree',
            2 => 'Disagree',
            3 => 'Neutral',
            4 => 'Agree',
            5 => 'Strongly agree',
        ];

        $fileContent = [
            'Competency1' => [
//                [
//                    'element' => 'input',
//                    'type' => 'text'
//                    'name' => 'first_name',
//                    'label' => 'First name',
//                    'hint' => null,
//                    'minLength' => null,
//                    'maxLength' => 255,
//                    'required' => true,
//                ],
//                [
//                    'element' => 'input',
//                    'type' => 'text'
//                    'name' => 'last_name',
//                    'label' => 'Last name',
//                    'hint' => null,
//                    'minLength' => null,
//                    'maxLength' => 255,
//                    'required' => true,
//                ],
//                [
//                    'element' => 'checkbox',
//                    'name' => 'terms',
//                    'label' => 'Terms and conditions',
//                    'hint' => null,
//                    'minLength' => null,
//                    'maxLength' => null,
//                    'required' => true,
//                ],
                [
                    'element'   => 'radio',
                    'name'      => 'management',
                    'defaults'   => [1 => 'Strongly disagree'],
                    'label'     => 'Management is easy',
                    'options'   => $options,
                    'hint'      => null,
                    'minLength' => null,
                    'maxLength' => null,
                    'required'  => true,
                ],
                [
                    'element'   => 'checkbox',
                    'name'      => 'leadership',
                    'defaults'   => [3 => 'Neutral'],
                    'label'     => 'Leadership is easy',
                    'options'   => $options,
                    'hint'      => null,
                    'minLength' => null,
                    'maxLength' => null,
                    'required'  => true,
                ],
                [
                    'element'   => 'dropdown',
                    'name'      => 'excellence',
                    'defaults'   => [2 => 'Disagree'],
                    'label'     => 'Excellence is easy',
                    'options'   => $options,
                    'hint'      => null,
                    'minLength' => null,
                    'maxLength' => null,
                    'required'  => true,
                ],
            ],
        ];

        //dd( Yaml::dump($this->components) );
        Storage::disk('local')->put('competencies.yml', Yaml::dump($fileContent));
    }
}
