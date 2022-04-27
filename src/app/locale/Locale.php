<?php

namespace App\Locale;

use Phalcon\Di\Injectable;
use Phalcon\Translate\Adapter\NativeArray;
use Phalcon\Translate\InterpolatorFactory;
use Phalcon\Translate\TranslateFactory;

class Locale extends Injectable
{
    /**
     * @return NativeArray
     */
    // public function getTranslator(): NativeArray
    // {
    //     $language = $this->request->getQuery('locale');
    //     $messages = [];
        
    //     $translationFile = '../app/messages/' . $language . '.php';

    //     if (true !== file_exists($translationFile)) {
    //         $translationFile = '../app/messages/en.php';
    //     }
        
    //     require $translationFile;

    //     $interpolator = new InterpolatorFactory();
    //     $factory      = new TranslateFactory($interpolator);
    //     return $factory->newInstance(
    //         'array',
    //         [
    //             'content' => $messages,
    //         ]
    //     );
    // }
    public function getTranslator(): NativeArray
    {
        // print_r($this->locale);
        // die;
        $language = $this->request->get('locale');
        // echo $language;
        // die;
        $messages = [];
        $translationFile = '../app/messages/' . $language . '.php';

        if (true !== file_exists($translationFile)) {
            $translationFile = '../app/messages/en.php';
        }
        // $cache = $this->cache->get();
        require $translationFile;
        $interpolator = new InterpolatorFactory();
        $factory      = new TranslateFactory($interpolator);

        
        if ($this->cache->has('array')) {
            return $factory->newInstance(
                'array',
                [
                    'content' => $this->cache->get('array'),
                ]
            );
        }
        $this->cache->set('array', $messages);
        return $factory->newInstance(
            'array',
            [
                'content' => $messages,
            ]
        );
    }
}
